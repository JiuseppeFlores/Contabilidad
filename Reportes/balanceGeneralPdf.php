<?php

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once('../conexion.php');
require_once('../Tcpdf/tcpdf.php');

$fechaInicial = '2023-01-01';
$fechaFinal = '2023-12-31';

$filtro = '';
if ($fechaInicial != '' && $fechaFinal != '') {
    $filtro .= " WHERE tco.fecha >= '$fechaInicial' AND tco.fecha <= '$fechaFinal' ";
    $fechaInicialFormato = new DateTime($fechaInicial);
    $fechaInicialFormato = $fechaInicialFormato->format('d/m/Y');
    $fechaFinalFormato = new DateTime($fechaFinal);
    $fechaFinalFormato = $fechaFinalFormato->format('d/m/Y');
}

$sql = "SELECT tbc.*, SUM(tba.debe) totalDebe FROM tblCuentas tbc LEFT JOIN tblAsientos tba ON tbc.idCuenta = tba.idCuenta GROUP BY tbc.idCuenta, tbc.codigo, tbc.descripcion, tbc.grupo, tbc.rubro, tbc.titulo, tbc.compuesta, tbc.subcuenta, tbc.nivel, tbc.moneda;";
$query = sqlsrv_query($con, $sql);
$listaGrupo = array();
$listaRubro = array();
$listaTitulo = array();
$listaCompuesta = array();
$listaSubcuenta = array();
while ($row = sqlsrv_fetch_array($query)) {
    switch ($row['nivel']) {
        case 'G':
            $listaGrupo[] = $row;
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
        default:
            break;
    }
}
$listaCompuesta2 = $listaCompuesta;
// print_r($listaCompuesta2);
// echo '<br><br>';

foreach ($listaCompuesta as $key => $value) {
    $value['children'] = array();
    foreach ($listaSubcuenta as $key2 => $value2) {
        // print_r($value);
        // echo '<br><br>';
        if ($value['compuesta'] == $value2['compuesta'] && $value['titulo'] == $value2['titulo'] && $value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo']) {
            $value['children'][] = $value2;
        }
        $listaCompuesta[$key] = $value;
    }
}

// print_r($listaCompuesta);

foreach ($listaTitulo as $key => $value) {
    $value['children'] = array();
    foreach ($listaCompuesta as $key2 => $value2) {
        // print_r($value);
        // echo '<br><br>';
        if (count($value2['children']) != 0) {
            if ($value['titulo'] == $value2['titulo'] && $value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo']) {
                $value['children'][] = $value2;
            }
            $listaTitulo[$key] = $value;
        }
    }
}

// print_r($listaTitulo);

foreach ($listaRubro as $key => $value) {
    $value['children'] = array();
    foreach ($listaTitulo as $key2 => $value2) {
        // print_r($value);
        // echo '<br><br>';
        if (count($value2['children']) != 0) {
            if ($value['rubro'] == $value2['rubro'] && $value['grupo'] == $value2['grupo']) {
                $value['children'][] = $value2;
            }
            $listaRubro[$key] = $value;
        }
    }
}

// print_r($listaRubro);

foreach ($listaGrupo as $key => $value) {
    $value['children'] = array();
    foreach ($listaRubro as $key2 => $value2) {
        // print_r($value);
        // echo '<br><br>';
        if (count($value2['children']) != 0) {
            if ($value['grupo'] == $value2['grupo']) {
                $value['children'][] = $value2;
            }
            $listaGrupo[$key] = $value;
        }
    }
}

// print_r($listaGrupo);

class MYPDF extends TCPDF
{
    public function Header()
    {
        // if ($_COOKIE['base_subdominio'] == 'sindan') {
        //     // Logo
        //     $image_file = '../images/excelKardex/logo_sindan.png';
        //     $this->Image($image_file, 8, 8, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // } else if ($_COOKIE['base_subdominio'] == 'saborandino') {
        //     $image_file = '../images/excelKardex/logo_sabor_andino.jpg';
        //     $this->Image($image_file, 8, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // }
        $this->SetFont('helvetica', '', 10);
        $this->MultiCell(50, 10, "NIT   181252025\nGESTION    2020\nN° DE PAG.: " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "", 0, 'L', 0, 1, '170', '8', true);
        $this->MultiCell(23, 10, "EMPRESA\nDIRECCION", 0, 'L', 0, 1, '10', '8', true);
        $this->MultiCell(100, 10, "SABOR ANDINO\nURB. VIRGEN DEL CARMEN CALLE ARICA No.777", 0, 'L', 0, 1, '33', '8', true);
    }
    public function Footer()
    {
    }
}
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
// Configurar las propiedades del documento
$pdf->SetCreator('STIS');
$pdf->SetAuthor('STIS');
$pdf->SetTitle('BALANCE GENERAL');
$pdf->SetSubject('BALANCE GENERAL');

// Establecer las dimensiones y la orientación del papel
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
$pdf->SetMargins(10, 25, 10, true);
$pdf->SetAutoPageBreak(true, 10);

// Agregar una página
$pdf->AddPage();

// Agregar contenido al documento
$pdf->SetFont('helvetica', '', 14);
// $pdf->Cell(0, 10, '¡Hola, TCPDF!', 0, 1, 'C');
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
<td colspan="3">Desde ' . $fechaInicialFormato . '    Hasta ' . $fechaFinalFormato . '</td>
</tr>
</table>
';
$pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
$pdf->SetFont('helvetica', '', 9);
$tabla = '
<table cellpadding="2">';
foreach ($listaGrupo as $key => $value) {
    if (count($value['children']) != 0) {
        $tabla .= '
        <tr>
        <td colspan="48"><u><b>' . $value['descripcion'] . '</b></u></td>
        </tr>';
        foreach ($value['children'] as $keyRubro => $valueRubro) {
            if (count($valueRubro['children']) != 0) {
                foreach ($valueRubro['children'] as $key => $valueTitulo) {
                    if (count($valueTitulo['children']) != 0) {
                        $tabla .= '
                        <tr>
                        <td></td>
                        <td colspan="47"><u><b>' . $valueTitulo['descripcion'] . '</b></u></td>
                        </tr>';
                        foreach ($valueTitulo['children'] as $key => $valueCompuesta) {
                            if (count($valueCompuesta['children']) != 0) {
                                foreach ($valueCompuesta['children'] as $key => $valueSubcuenta) {
                                    if ($valueSubcuenta['totalDebe'] > 0) {
                                        $tabla .= '
                                        <tr>
                                        <td border="1" colspan="10">' . $valueSubcuenta['codigo'] . '</td>
                                        <td border="1" colspan="28">' . $valueSubcuenta['descripcion'] . '</td>
                                        <td align="rigth" border="1" colspan="10">' . $valueSubcuenta['totalDebe'] . '</td>
                                        </tr>';
                                    }
                                }
                            } else {
                                $tabla .= '
                                <tr>
                                <td border="1" colspan="10">1000000</td>
                                <td border="1" colspan="28">' . $valueCompuesta['descripcion'] . '</td>
                                <td align="rigth" border="1" colspan="10">11,093.20</td>
                                </tr>';
                            }
                        }
                        $tabla .= '
                        <tr>
                        <td align="rigth" colspan="38"><b>SUBTOTAL DISPONIBLE</b></td>
                        <td align="rigth" border="1" colspan="10">121231</td>
                        </tr>';
                    }
                }
            }
        }
        $tabla .= '
        <tr>
        <td align="rigth" colspan="38"><b>TOTAL ' . $value['descripcion'] . '</b></td>
        <td align="rigth" border="1" colspan="10">191919191</td>
        </tr>';
    }
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