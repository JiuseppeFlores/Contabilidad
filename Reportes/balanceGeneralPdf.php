<?php

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once('../conexion.php');
require_once('../php/functions.php');
require_once('../Tcpdf/tcpdf.php');

$datosGestion = obtenerDatosGestion($con);

$fechaInicial = $datosGestion['fechaInicial'];
$fechaFinal = isset($_POST['fechaFinal']) ? $_POST['fechaFinal'] : '2023-06-30';

$filtro = '';
if ($fechaInicial != '' && $fechaFinal != '') {
    $filtro .= " WHERE tco.fecha >= '$fechaInicial' AND tco.fecha <= '$fechaFinal' ";
    $fechaInicialFormato = new DateTime($fechaInicial);
    $fechaInicialFormato = $fechaInicialFormato->format('d/m/Y');
    $fechaFinalFormato = new DateTime($fechaFinal);
    $fechaFinalFormato = $fechaFinalFormato->format('d/m/Y');
}

$sqlLista = "SELECT tbc.*, SUM(tba.debe) totalDebe, SUM(tba.haber) totalHaber FROM tblCuentas tbc LEFT JOIN tblAsientos tba ON tbc.idCuenta = tba.idCuenta LEFT JOIN tblComprobantes tco ON tba.idComprobante = tco.idComprobante $filtro  AND tbc.movimiento = 1 GROUP BY tbc.idCuenta, tbc.codigo, tbc.descripcion, tbc.grupo, tbc.rubro, tbc.titulo, tbc.compuesta, tbc.subcuenta, tbc.auxiliar, tbc.nivel, tbc.movimiento, tbc.moneda ORDER BY tbc.codigo ASC;";
// echo $sqlLista;
// echo '<br>';
$queryLista = sqlsrv_query($con, $sqlLista);
$listaGrupoLista = array();
$listaRubroLista = array();
$listaTituloLista = array();
$listaCompuestaLista = array();
$listaSubcuentaLista = array();
$listaAuxiliarLista = array();
while ($row = sqlsrv_fetch_array($queryLista)) {
    switch ($row['nivel']) {
        case 'G':
            if ($row['descripcion'] == 'ACTIVO' || $row['descripcion'] == 'PASIVO' || $row['descripcion'] == 'PATRIMONIO') {
                $row['children'] = array();
                $listaGrupoLista[] = $row;
            }
            break;
        case 'R':
            $row['children'] = array();
            $listaRubroLista[] = $row;
            break;
        case 'T':
            $row['children'] = array();
            $listaTituloLista[] = $row;
            break;
        case 'C':
            $row['children'] = array();
            $listaCompuestaLista[] = $row;
            break;
        case 'S':
            $row['children'] = array();
            $listaSubcuentaLista[] = $row;
            break;
        case 'A':
            $row['children'] = array();
            $listaAuxiliarLista[] = $row;
            break;
        default:
            break;
    }
}
// Para ver mejor los arrays
// echo '<br><br>======LISTA GRUPO======<br><br>';
// print("<pre>" . print_r($listaGrupoLista, true) . "</pre>");

$sql = "SELECT tbc.*, SUM(tba.debe) totalDebe, SUM(tba.haber) totalHaber FROM tblCuentas tbc LEFT JOIN tblAsientos tba ON tbc.idCuenta = tba.idCuenta GROUP BY tbc.idCuenta, tbc.codigo, tbc.descripcion, tbc.grupo, tbc.rubro, tbc.titulo, tbc.compuesta, tbc.subcuenta, tbc.auxiliar, tbc.nivel, tbc.movimiento, tbc.moneda ORDER BY tbc.codigo ASC;";
// echo $sql;
$query = sqlsrv_query($con, $sql);
$listaGrupo = array();
$listaRubro = array();
$listaTitulo = array();
$listaCompuesta = array();
$listaSubcuenta = array();
$listaAuxiliar = array();
while ($row = sqlsrv_fetch_array($query)) {
    switch ($row['nivel']) {
        case 'G':
            if ($row['descripcion'] == 'ACTIVO' || $row['descripcion'] == 'PASIVO' || $row['descripcion'] == 'PATRIMONIO') {
                $listaGrupo[] = $row;
            }
            break;
        case 'R':
            $listaRubro[] = $row;
            break;
        case 'T':
            $listaTitulo[] = $row;
            break;
        case 'C':
            $listaCompuesta[] = $row;
            break;
        case 'S':
            $listaSubcuenta[] = $row;
            break;
        case 'A':
            $listaAuxiliar[] = $row;
            break;
        default:
            break;
    }
}


foreach ($listaSubcuenta as $key => $value) {
    $value['children'] = array();
    foreach ($listaAuxiliarLista as $key2 => $value2) {
        if ($value['subcuenta'] == $value2['subcuenta'] && $value['compuesta'] == $value2['compuesta'] && $value['titulo'] == $value2['titulo'] && $value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo'] && ($value2['totalDebe'] != null || $value2['totalHaber'] != null)) {
            $value['children'][] = $value2;
        }
    }
    $listaSubcuenta[$key] = $value;
}

foreach ($listaSubcuenta as $key => $value) {
    if (count($value['children']) == 0) {
        if (!in_array($value, $listaSubcuentaLista)) {
            unset($listaSubcuenta[$key]);
        } else {
            // unset($listaSubcuenta[$key]);
            // array_splice($listaSubcuenta, $key, 1);
        }
    }
}

foreach ($listaCompuesta as $key => $value) {
    $value['children'] = array();
    foreach ($listaSubcuenta as $key2 => $value2) {
        // print_r($value);
        // echo '<br><br>';
        // if (count($value2['children']) != 0) {
        if ($value['compuesta'] == $value2['compuesta'] && $value['titulo'] == $value2['titulo'] && $value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo'] && ($value2['totalDebe'] != null || $value2['totalHaber'] != null || count($value2['children']) != 0)) {
            $value['children'][] = $value2;
        }
        // }
    }
    $listaCompuesta[$key] = $value;
}

foreach ($listaCompuesta as $key => $value) {
    if (count($value['children']) == 0) {
        if (!in_array($value, $listaCompuestaLista)) {
            // array_splice($listaCompuesta, $key, 1);
            unset($listaCompuesta[$key]);
        } else {
            // unset($listaSubcuenta[$key]);
            // array_splice($listaSubcuenta, $key, 1);
        }
    }
}

foreach ($listaTitulo as $key => $value) {
    $value['children'] = array();
    foreach ($listaCompuesta as $key2 => $value2) {
        // if (count($value2['children']) != 0) {
        if ($value['titulo'] == $value2['titulo'] && $value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo'] && ($value2['totalDebe'] != null || $value2['totalHaber'] != null || count($value2['children']) != 0)) {
            // echo '<br><br>============<br><br>';
            // echo '<br>' . $value['descripcion'] . '<br>';
            // print("<pre>" . print_r($value2, true) . "</pre>");
            $value['children'][] = $value2;
        }
        // }
    }
    $listaTitulo[$key] = $value;
}

foreach ($listaRubro as $key => $value) {
    $value['children'] = array();
    foreach ($listaTitulo as $key2 => $value2) {
        if (count($value2['children']) != 0) {
            if ($value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo']) {
                $value['children'][] = $value2;
            }
        }
    }
    $listaRubro[$key] = $value;
}

foreach ($listaGrupo as $key => $value) {
    $value['children'] = array();
    foreach ($listaRubro as $key2 => $value2) {
        if (count($value2['children']) != 0) {
            if ($value['grupo'] == $value2['grupo']) {
                $value['children'][] = $value2;
            }
        }
    }
    $listaGrupo[$key] = $value;
}

$datosEmpresa = obtenerDatosEmpresa();
$nombreEmpresa = $datosEmpresa['nombre'];
$direccion = $datosEmpresa['direccion'];
$nit = $datosEmpresa['nit'];

define('fechaInicialFormato', $fechaInicialFormato);
define('fechaFinalFormato', $fechaFinalFormato);
define('nombreEmpresa', $nombreEmpresa);
define('direccion', $direccion);
define('nit', $nit);

class MYPDF extends TCPDF
{
    public function Header()
    {
        $datosEmpresa = obtenerDatosEmpresa();
        if ($_COOKIE['conta_subdomain'] == 'sabor_andino') {
            $image_file = '../Images/logo_sabor_andino.jpg';
            $this->Image($image_file, 163, 5, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else if ($_COOKIE['conta_subdomain'] == 'sindan') {
            $image_file = '../Images/logo_sindan.png';
            $this->Image($image_file, 163, 5, 35, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->SetFont('helvetica', '', 9);
        // $this->MultiCell(50, 10, "NIT   181252025\nGESTION    2023\nN° DE PAG.: " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "", 0, 'L', 0, 1, '170', '8', true);
        $this->MultiCell(23, 10, "EMPRESA\nDIRECCION\nNIT\nN° DE PAG.", 0, 'L', 0, 1, '20', '8', true);
        $this->MultiCell(100, 10, nombreEmpresa . "\n" . direccion . "\n" . nit . "\n" . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "", 0, 'L', 0, 1, '43', '8', true);
        // Agregar contenido al documento
        $this->SetFont('helvetica', '', 14);
        $tabla = '';
        $tabla .= '
        <table border="0" cellpaddind="2">
        <tr align="center">
        <td colspan="3"><b>BALANCE GENERAL</b></td>
        </tr>
        <tr align="center" style="font-size: 10px;">
        <td colspan="3">Experesado en Bs.</td>
        </tr>
        <tr align="center" style="font-size: 11px;">
        <td colspan="3">Desde ' . fechaInicialFormato . '    Hasta ' . fechaFinalFormato . '</td>
        </tr>
        </table>
        ';
        $this->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
    }
    public function Footer()
    {
    }
}
$carta = array(215.9, 279.4);
$pdf = new MYPDF('P', 'mm', $carta, true, 'UTF-8', false);
// Configurar las propiedades del documento
$pdf->SetCreator('STIS');
$pdf->SetAuthor('STIS');
$pdf->SetTitle('BALANCE GENERAL');
$pdf->SetSubject('BALANCE GENERAL');

// Establecer las dimensiones y la orientación del papel
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
$pdf->SetMargins(20, 45, 10, true);
$pdf->SetAutoPageBreak(true, 10);

// Agregar una página
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 9);
$tabla = '
<table border="0" cellpadding="2">';
if (count($listaGrupo) != 0 && (count($listaCompuestaLista) != 0 || count($listaSubcuentaLista) != 0 || count($listaAuxiliarLista) != 0)) {
    foreach ($listaGrupo as $key => $value) {
        // switch ($value['descripcion']) {
        //     case 'ACTIVO':
        //         $operacion = 'ACTIVO';
        //         break;
        //     case 'PASIVO':
        //         $operacion = 'PASIVO';
        //         break;
        //     case 'PATRIMONIO':
        //         $operacion = 'PATRIMONIO';
        //         break;
        //     default:
        //         $operacion = '';
        //         break;
        // }

        // $tabla .= '
        // <tr>
        // <td border="1" colspan="10">1000</td>
        // <td border="1" colspan="28">10000</td>
        // <td align="rigth" border="1" colspan="10">100000</td>
        // </tr>';
        // $tabla .= '<tr><td>grupo</td></tr>';
        if (count($value['children']) != 0) {
            $totalGrupo = 0;
            $tabla .= '
        <tr>
        <td colspan="48"><u><b>' . $value['descripcion'] . '</b></u></td>
        </tr>';
            foreach ($value['children'] as $keyRubro => $valueRubro) {
                // $tabla .= '
                // <tr>
                // <td border="1" colspan="10">1000</td>
                // <td border="1" colspan="28">10000</td>
                // <td align="rigth" border="1" colspan="10">100000</td>
                // </tr>';
                if (count($valueRubro['children']) != 0) {
                    // $tabla .= '
                    // <tr>
                    // <td></td>
                    // <td colspan="47"><u><b>' . $valueRubro['descripcion'] . '</b></u></td>
                    // </tr>';
                    foreach ($valueRubro['children'] as $key => $valueTitulo) {
                        // $tabla .= '
                        // <tr>
                        // <td border="1" colspan="10">2000</td>
                        // <td border="1" colspan="28">20000</td>
                        // <td align="rigth" border="1" colspan="10">200000</td>
                        // </tr>';
                        if (count($valueTitulo['children']) != 0) {
                            // $tabla .= '
                            // <tr>
                            // <td border="1" colspan="10">1000</td>
                            // <td border="1" colspan="28">10000</td>
                            // <td align="rigth" border="1" colspan="10">100000</td>
                            // </tr>';

                            // echo '<br><br>============<br><br>';
                            // echo '<br>' . $valueTitulo['descripcion'] . '<br>';
                            // print("<pre>" . print_r($valueTitulo['children'], true) . "</pre>");

                            // print_r($valueTitulo['children']);
                            $subtotal = 0;
                            $tabla .= '
                        <tr>
                        <td></td>
                        <td colspan="47"><u><b>' . $valueTitulo['descripcion'] . '</b></u></td>
                        </tr>';
                            foreach ($valueTitulo['children'] as $key => $valueCompuesta) {

                                if (count($valueCompuesta['children']) != 0) {

                                    foreach ($valueCompuesta['children'] as $key => $valueSubcuenta) {
                                        if (count($valueSubcuenta['children']) != 0) {
                                            foreach ($valueSubcuenta['children'] as $key => $valueAuxiliar) {
                                                // $tabla .= '
                                                // <tr>
                                                // <td border="1" colspan="10">1000</td>
                                                // <td border="1" colspan="28">10000</td>
                                                // <td align="rigth" border="1" colspan="10">100000</td>
                                                // </tr>';
                                                if (($valueAuxiliar['totalDebe'] > 0 || $valueAuxiliar['totalHaber'] > 0) && $valueAuxiliar['movimiento'] == true) {
                                                    switch ($value['descripcion']) {
                                                        case 'ACTIVO':
                                                            $valueResultado = $valueAuxiliar['totalDebe'] - $valueAuxiliar['totalHaber'];
                                                            break;
                                                        case 'PASIVO':
                                                            $valueResultado = $valueAuxiliar['totalHaber'] - $valueAuxiliar['totalDebe'];
                                                            break;
                                                        case 'PATRIMONIO':
                                                            $valueResultado = $valueAuxiliar['totalHaber'] - $valueAuxiliar['totalDebe'];
                                                            break;
                                                        default:
                                                            $valueResultado = 0;
                                                            break;
                                                    }
                                                    if ($valueResultado != 0) {

                                                        $tabla .= '
                                                    <tr>
                                                    <td border="1" colspan="10">' . $valueAuxiliar['codigo'] . '</td>
                                                    <td border="1" colspan="28">' . $valueAuxiliar['descripcion'] . '</td>
                                                    <td align="rigth" border="1" colspan="10">' . number_format($valueResultado, 2) . '</td>
                                                    </tr>';
                                                    }

                                                    $subtotal = $subtotal + $valueResultado;
                                                }
                                            }
                                            // } else if ($valueSubcuenta['totalDebe'] > 0) {
                                        } else if (($valueSubcuenta['totalDebe'] > 0 || $valueSubcuenta['totalHaber'] > 0) && $valueSubcuenta['movimiento'] == true) {
                                            switch ($value['descripcion']) {
                                                case 'ACTIVO':
                                                    $valueResultado = $valueSubcuenta['totalDebe'] - $valueSubcuenta['totalHaber'];
                                                    break;
                                                case 'PASIVO':
                                                    $valueResultado = $valueSubcuenta['totalHaber'] - $valueSubcuenta['totalDebe'];
                                                    break;
                                                case 'PATRIMONIO':
                                                    $valueResultado = $valueSubcuenta['totalHaber'] - $valueSubcuenta['totalDebe'];
                                                    break;
                                                default:
                                                    $valueResultado = 0;
                                                    break;
                                            }
                                            if ($valueResultado != 0) {
                                                $tabla .= '
                                        <tr>
                                        <td border="1" colspan="10">' . $valueSubcuenta['codigo'] . '</td>
                                        <td border="1" colspan="28">' . $valueSubcuenta['descripcion'] . '</td>
                                        <td align="rigth" border="1" colspan="10">' . number_format($valueResultado, 2) . '</td>
                                        </tr>';
                                            }
                                            $subtotal = $subtotal + $valueResultado;
                                        }
                                    }
                                } else if (($valueCompuesta['totalDebe'] > 0 || $valueCompuesta['totalHaber'] > 0) && $valueCompuesta['movimiento'] == true) {
                                    // echo 'debe: ' . gettype(intval($valueCompuesta['totalDebe'])) . ' - ' . $valueCompuesta['totalDebe'] . '<br>';
                                    // echo 'haber: ' . gettype($valueCompuesta['totalHaber']) . ' - ' . $valueCompuesta['totalHaber'] . '<br>';
                                    // echo 'movimiento: ' . gettype($valueCompuesta['movimiento']) . ' - ' . $valueCompuesta['movimiento'] . '<br>';
                                    switch ($value['descripcion']) {
                                        case 'ACTIVO':
                                            $valueResultado = $valueCompuesta['totalDebe'] - $valueCompuesta['totalHaber'];
                                            break;
                                        case 'PASIVO':
                                            $valueResultado = $valueCompuesta['totalHaber'] - $valueCompuesta['totalDebe'];
                                            break;
                                        case 'PATRIMONIO':
                                            $valueResultado = $valueCompuesta['totalHaber'] - $valueCompuesta['totalDebe'];
                                            break;
                                        default:
                                            $valueResultado = 0;
                                            break;
                                    }
                                    if ($valueResultado != 0) {
                                        $tabla .= '
                                <tr>
                                <td border="1" colspan="10">' . $valueCompuesta['codigo'] . '</td>
                                <td border="1" colspan="28">' . $valueCompuesta['descripcion'] . '</td>
                                <td align="rigth" border="1" colspan="10">' . number_format($valueResultado, 2) . '</td>
                                </tr>';
                                    }
                                    $subtotal = $subtotal + $valueResultado;
                                }
                            }
                            $tabla .= '
                        <tr>
                        <td align="rigth" colspan="38"><b>SUBTOTAL ' . $valueTitulo['descripcion'] . '</b></td>
                        <td align="rigth" border="1" colspan="10">' . number_format($subtotal, 2) . '</td>
                        </tr>';
                        }
                        $totalGrupo = $totalGrupo + $subtotal;
                        switch ($value['descripcion']) {
                            case 'ACTIVO':
                                $totalActivo = $totalGrupo;
                                break;
                            case 'PASIVO':
                                $totalPasivo = $totalGrupo;
                                break;
                            case 'PATRIMONIO':
                                $totalPatrimonio = $totalGrupo;
                                break;
                            default:

                                break;
                        }
                    }
                }
            }
            $tabla .= '
        <tr>
        <td align="rigth" colspan="38"><b>TOTAL ' . $value['descripcion'] . '</b></td>
        <td align="rigth" border="1" colspan="10"> ' . number_format($totalGrupo, 2) . '</td>
        </tr>';
        }
    }
    $tabla .= '
    <tr>
    <td align="rigth" colspan="38"><b>TOTAL PASIVO Y PATRIMONIO</b></td>
    <td align="rigth" border="1" colspan="10">' . number_format($totalPasivo + $totalPatrimonio, 2) . '</td>
    </tr>
    ';
} else {
    $tabla .= '
    <tr>
    <td colspan="48">No existen registros en este periodo de tiempo.</td>
    </tr>';
}
$tabla .= '
</table>';
// $tabla = '
// <table border="1" cellpadding="2">
// <tr align="center">
// <th colspan="2">FECHA</th>
// <th>T</th>
// <th>N°</th>
// <th>T./C.</th>
// <th colspan="6">DESCRIPCION</th>
// <th colspan="2">CHEQUE</th>
// <th colspan="2">DEBE</th>
// <th colspan="2">HABER</th>
// <th colspan="2">SALDOS</th>
// </tr>
// ';
// if (count($listaAsientos) > 0) {
//     $saldo = 0;
//     $debeTotal = 0;
//     $haberTotal = 0;
//     foreach ($listaAsientos as $key => $value) {
//         $fecha = $value['fecha']->format('d/m/Y');

//         $debe = floatval($value['debe']);
//         $haber = floatval($value['haber']);
//         $saldo = number_format($saldo + $debe - $haber, 2);

//         $debeTotal = number_format($debeTotal + $debe, 2);
//         $haberTotal = number_format($haberTotal + $haber, 2);

//         $tabla .= '
//         <tr align="center">
//         <td colspan="2">' . $fecha . '</td>
//         <td></td>
//         <td></td>
//         <td>' . $value['tipoCambio'] . '</td>
//         <td colspan="6" align="left">' . $value['referencia'] . '</td>
//         <td colspan="2">' . $value['cheque'] . '</td>
//         <td colspan="2">' . $value['debe'] . '</td>
//         <td colspan="2">' . $value['haber'] . '</td>
//         <td colspan="2">' . $saldo . '</td>
//         </tr>
//         ';
//     }
// } else {
//     $tabla .= '
//     <tr align="center">
//     <td colspan="19">No hay registros en este periodo de fechas.</td>
//     </tr>
//     ';
// }
// $tabla .= '</table>';
// $tabla .= '
// <table border="0" cellpadding="3">
// <tr align="center">
// <td align="right" colspan="13"></td>
// <td colspan="2">' . $debeTotal . '</td>
// <td colspan="2">' . $haberTotal . '</td>
// <td colspan="2"></td>
// </tr>
// <tr align="center">
// <td align="right" colspan="13">SALDO DEUDOR</td>
// <td colspan="4">' . $saldo . '</td>
// <td colspan="2"></td>
// </tr>
// </table>
// ';
// =======================================

$pdf->WriteHTMLCell(0, 0, '', '45', $tabla, 0, 0);
ob_end_clean();
$pdf->output('LIBRO MAYOR.pdf', 'I');
