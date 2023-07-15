<?php
require_once('../conexion.php');
require_once('../php/functions.php');
require_once('../Tcpdf/tcpdf.php');

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$datosGestion = obtenerDatosGestion($con);

$codigoCuenta = isset($_POST['codigoCuenta']) ? $_POST['codigoCuenta'] : '11000001';
$fechaInicial = isset($_POST['fechaInicial']) ? $_POST['fechaInicial'] : '2023-01-01';
$fechaFinal = isset($_POST['fechaFinal']) ? $_POST['fechaFinal'] : '2023-12-31';

$filtro = '';
if ($fechaInicial != '' && $fechaFinal != '') {
    $filtro .= " AND tco.fecha >= '$fechaInicial' AND tco.fecha <= '$fechaFinal' ";
    $fechaInicialFormato = new DateTime($fechaInicial);
    $fechaInicialFormato = $fechaInicialFormato->format('d/m/Y');
    $fechaFinalFormato = new DateTime($fechaFinal);
    $fechaFinalFormato = $fechaFinalFormato->format('d/m/Y');
}

// para la consulta a la base datos
$sql = "SELECT * FROM tblCuentas tcu LEFT JOIN tblAsientos tas ON tcu.idCuenta = tas.idCuenta LEFT JOIN tblComprobantes tco ON tas.idComprobante = tco.idComprobante WHERE tcu.codigo = $codigoCuenta $filtro;";
echo $sql;
$query = sqlsrv_query($con, $sql);
$listaAsientos = array();
$nombreCuenta = '';
while ($row = sqlsrv_fetch_array($query)) {
    $listaAsientos[] = $row;
    // $nombreCuenta = $row['descripcion'];
}
$sqlCuenta = "SELECT * FROM tblCuentas tcu WHERE tcu.codigo = $codigoCuenta;";
$queryCuenta = sqlsrv_query($con, $sqlCuenta);
$rowCuenta = sqlsrv_fetch_array($queryCuenta);
$nombreCuenta = $rowCuenta['descripcion'];

$datosEmpresa = obtenerDatosEmpresa();
$nombreEmpresa = $datosEmpresa['nombre'];
$direccion = $datosEmpresa['direccion'];
$nit = $datosEmpresa['nit'];

define('fechaInicialFormato', $fechaInicialFormato);
define('fechaFinalFormato', $fechaFinalFormato);
define('codigoCuenta', $codigoCuenta);
define('nombreCuenta', $nombreCuenta);
define('nombreEmpresa', $nombreEmpresa);
define('direccion', $direccion);
define('nit', $nit);

class MYPDF extends TCPDF
{
    public function Header()
    {
        if ($_COOKIE['conta_subdomain'] == 'sabor_andino') {
            $image_file = '../Images/logo_sabor_andino.jpg';
            $this->Image($image_file, 163, 5, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else if ($_COOKIE['conta_subdomain'] == 'sindan') {
            $image_file = '../Images/logo_sindan.png';
            $this->Image($image_file, 163, 5, 35, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->SetFont('helvetica', '', 9);
        // $this->MultiCell(50, 10, "N° DE PAG.: " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "\nFECHA DE IMP. 31/05/2023\nGESTION    2023", 0, 'L', 0, 1, '150', '8', true);
        $this->MultiCell(23, 10, "EMPRESA\nDIRECCION\nNIT\nN° DE PAG.", 0, 'L', 0, 1, '20', '8', true);
        $this->MultiCell(100, 10, nombreEmpresa . "\n" . direccion . "\n" . nit . "\n" . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "", 0, 'L', 0, 1, '43', '8', true);

        // Agregar contenido al documento
        $this->SetFont('helvetica', '', 14);
        // $pdf->Cell(0, 10, '¡Hola, TCPDF!', 0, 1, 'C');
        $tabla = '';
        $tabla .= '
        <table border="0" cellpaddind="2">
        <tr align="center">
        <td colspan="3"><b>LIBRO MAYOR</b></td>
        </tr>
        <tr align="center" style="font-size: 10px;">
        <td colspan="3">Experesado en Bs.</td>
        </tr>
        <tr align="center" style="font-size: 11px;">
        <td colspan="3">Desde ' . fechaInicialFormato . '    Hasta ' . fechaFinalFormato . '</td>
        </tr>
        <tr style="font-size: 7px;">
        <td></td>
        </tr>
        <tr style="font-size: 10px;">
        <td>CUENTA ' . codigoCuenta . '</td>
        <td colspan="2">NOMBRE ' . nombreCuenta . '</td>
        </tr>
        <tr style="font-size: 7px;">
        <td></td>
        </tr>
        <tr style="font-size: 10px;">
        <td></td>
        <td>SALDO ANTERIOR</td>
        <td></td>
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
$pdf->SetTitle('LIBRO MAYOR');
$pdf->SetSubject('LIBRO MAYOR');

// Establecer las dimensiones y la orientación del papel
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
$pdf->SetMargins(20, 60, 10, true);
$pdf->SetAutoPageBreak(true, 13);

// Agregar una página
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 8);
$tabla = '
<table border="0" cellpadding="2">
<tr align="center">
<th colspan="2" border="1">FECHA</th>
<th colspan="2" border="1">T</th>
<th border="1">N°</th>
<th border="1">T./C.</th>
<th colspan="6" border="1">DESCRIPCION</th>
<th colspan="2" border="1">CHEQUE</th>
<th colspan="2" border="1">DEBE</th>
<th colspan="2" border="1">HABER</th>
<th colspan="2" border="1">SALDOS</th>
</tr>
';
if (count($listaAsientos) > 0) {
    $saldo = 0;
    $saldo2 = 0;
    $debeTotal = 0;
    $haberTotal = 0;
    foreach ($listaAsientos as $key => $value) {
        $fecha = $value['fecha']->format('d/m/Y');

        $debe = floatval($value['debe']);
        $haber = floatval($value['haber']);
        // echo 'saldo ' . gettype($saldo) . '<br>';
        // echo 'debe ' . gettype($debe) . '<br>';
        // echo 'haber ' . gettype($haber) . '<br>';
        $saldo2 = floatval($saldo2) + $debe - $haber;

        $debeTotal = $debeTotal + $debe;
        $haberTotal = $haberTotal + $haber;

        $tabla .= '
        <tr align="center">
        <td colspan="2" style="border-left: 0.7px solid black;">' . $fecha . '</td>
        <td colspan="2" style="border-left: 0.7px solid black;">' . $value['tipo'] . '</td>
        <td style="border-left: 0.7px solid black;">' . $value['numero'] . '</td>
        <td style="border-left: 0.7px solid black;">' . $value['tipoCambio'] . '</td>
        <td colspan="6" align="left" style="border-left: 0.7px solid black;">' . $value['glosa'] . '</td>
        <td colspan="2" align="left" style="border-left: 0.7px solid black;">' . $value['cheque'] . '</td>';
        if ($value['debe'] == 0) {
            $tabla .= '<td colspan="2" align="rigth" style="border-left: 0.7px solid black;"> - </td>';
        } else {
            $tabla .= '<td colspan="2" align="rigth" style="border-left: 0.7px solid black;">' . number_format($value['debe'], 2) . '</td>';
        }
        if ($value['haber'] == 0) {
            $tabla .= '<td colspan="2" align="rigth" style="border-left: 0.7px solid black;"> - </td>';
        } else {
            $tabla .= '<td colspan="2" align="rigth" style="border-left: 0.7px solid black;">' . number_format($value['haber'], 2) . '</td>';
        }
        if ($saldo2 == 0) {
            $tabla .= '<td colspan="2" align="rigth" style="border-left: 0.7px solid black; border-right: 0.7px solid black;"> - </td>';
        } else {
            $tabla .= '<td colspan="2" align="rigth" style="border-left: 0.7px solid black; border-right: 0.7px solid black;">' . number_format($saldo2, 2) . '</td>';
        }
        $tabla .= '        
        </tr>
        ';
    }
    $tabla .= '</table>';
    $tabla .= '
    <table border="0" cellpadding="2">
    <tr align="center">
    <td align="right" colspan="13" style="border-top: 0.7px solid black;"></td>
    <td colspan="2" align="rigth" style="border-top: 0.7px solid black;">' . number_format($debeTotal, 2) . '</td>
    <td colspan="2" align="rigth" style="border-top: 0.7px solid black;">' . number_format($haberTotal, 2) . '</td>
    <td colspan="2" style="border-top: 0.7px solid black;"></td>
    </tr>
    <tr align="center">';
    if ($saldo2 < 0) {
        // $tabla .= '
        // <td align="rigth" colspan="13">SALDO ACREEDOR</td>
        // <td colspan="4">' . number_format((-1)*$saldo2, 2) . '</td>';
        $tabla .= '
        <td align="rigth" colspan="13">SALDO</td>
        <td colspan="4">' . number_format($saldo2, 2) . '</td>';
    } else {
        // $tabla .= '
        // <td align="rigth" colspan="13">SALDO DEUDOR</td>
        // <td colspan="4">' . number_format($saldo2, 2) . '</td>';
        $tabla .= '
        <td align="rigth" colspan="13">SALDO</td>
        <td colspan="4">' . number_format($saldo2, 2) . '</td>';
    }
    $tabla .= '
    <td colspan="2"></td>
    </tr>
    </table>
    ';
} else {
    $tabla .= '
    <tr align="center">
    <td colspan="21">No hay registros en este periodo de fechas.</td>
    </tr>
    ';
    $tabla .= '</table>';
}

$pdf->WriteHTMLCell(0, 0, '', '60', $tabla, 0, 0);
ob_end_clean();
$pdf->output('LIBRO MAYOR.pdf', 'I');
