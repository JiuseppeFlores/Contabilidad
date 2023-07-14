<?php

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once('../conexion.php');
require_once('../php/functions.php');
require_once('../Tcpdf/tcpdf.php');

$codigoCuenta = '11000.02';
$fechaInicial = isset($_POST['fechaInicial']) ? $_POST['fechaInicial'] : '2023-01-01';
$fechaFinal = isset($_POST['fechaFinal']) ? $_POST['fechaFinal'] : '2023-12-01';

$filtro = '';
if ($fechaInicial != '' && $fechaFinal != '') {
    $filtro .= " WHERE tco.fecha >= '$fechaInicial' AND tco.fecha <= '$fechaFinal' ";
    $fechaInicialFormato = new DateTime($fechaInicial);
    $fechaInicialFormato = $fechaInicialFormato->format('d/m/Y');
    $fechaFinalFormato = new DateTime($fechaFinal);
    $fechaFinalFormato = $fechaFinalFormato->format('d/m/Y');
}

// para la consulta a la base datos
$sql = "SELECT tcu.codigo, tcu.descripcion, SUM(tas.debe) debe, SUM(tas.haber) haber FROM tblCuentas tcu LEFT JOIN tblAsientos tas ON tcu.idCuenta = tas.idCuenta LEFT JOIN tblComprobantes tco ON tas.idComprobante = tco.idComprobante $filtro   GROUP BY tcu.codigo, tcu.descripcion ORDER BY tcu.codigo ASC;";
$query = sqlsrv_query($con, $sql);
$listaRegistros = array();
while ($row = sqlsrv_fetch_array($query)) {
    $listaRegistros[] = $row;
}

class MYPDF extends TCPDF
{
    public function Header()
    {
        $datosEmpresa = obtenerDatosEmpresa();
        if ($_COOKIE['conta_subdomain'] == 'sabor_andino') {
            $image_file = '../Images/logo_sabor_andino.jpg';
            $this->Image($image_file, 163, 5, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else if ($_COOKIE['conta_subdomain'] == 'sindan'){
            $image_file = '../Images/logo_sindan.png';
            $this->Image($image_file, 163, 5, 35, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->SetFont('helvetica', '', 9);
        // $this->MultiCell(50, 10, "NIT   181252025\nGESTION    2023", 0, 'L', 0, 1, '170', '8', true);
        $this->MultiCell(23, 10, "EMPRESA\nDIRECCION\nNIT\nN° DE PAG.", 0, 'L', 0, 1, '20', '8', true);
        $this->MultiCell(100, 10, $datosEmpresa['nombre']."\n".$datosEmpresa['direccion']."\n".$datosEmpresa['nit']."\n" . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "", 0, 'L', 0, 1, '43', '8', true);
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
$pdf->SetTitle('BALANCE DE SUMAS Y SALDOS');
$pdf->SetSubject('BALANCE DE SUMAS Y SALDOS');

// Establecer las dimensiones y la orientación del papel
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
$pdf->SetMargins(20, 25, 10, true);
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
<td colspan="3"><b>BALANCE DE SUMAS Y SALDOS</b></td>
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
<table border="1" cellpadding="2">
<tr align="center">
<th colspan="2">CODIGOS</th>
<th colspan="6">DESCRIPCION</th>
<th colspan="2">DEBE</th>
<th colspan="2">HABER</th>
<th colspan="2">DEUDOR</th>
<th colspan="2">ACREEDOR</th>
</tr>
';
if (count($listaRegistros) > 0) {
    $saldo = 0;
    $debeTotal = 0;
    $haberTotal = 0;
    $deudorTotal = 0;
    $acreedorTotal = 0;
    foreach ($listaRegistros as $key => $value) {
        $debe = floatval($value['debe']);
        $haber = floatval($value['haber']);
        $saldo = $debe - $haber;
        echo '<br>';
        echo 'debe: ' . $debe . '<br>';
        echo 'haber: ' . $haber . '<br>';
        echo 'saldo: ' . $saldo . '<br>';
        if ($saldo < 0) {
            $acreedor = $saldo;
            $deudor = 0;
        } else {
            $deudor = $saldo;
            $acreedor = 0;
        }

        $debeTotal = $debeTotal + $debe;
        $haberTotal = $haberTotal + $haber;
        $deudorTotal = $deudorTotal + $deudor;
        $acreedorTotal = $acreedorTotal + $acreedor;

        $tabla .= '
        <tr align="center">
        <td colspan="2" align="left">' . $value['codigo'] . '</td>
        <td colspan="6" align="left">' . $value['descripcion'] . '</td>
        <td colspan="2" align="rigth">' . number_format($value['debe'], 2) . '</td>
        <td colspan="2" align="rigth">' . number_format($value['haber'], 2) . '</td>
        <td colspan="2" align="rigth">' . number_format($deudor, 2) . '</td>
        <td colspan="2" align="rigth">' . number_format((-1) * $acreedor, 2) . '</td>
        </tr>
        ';
    }
} else {
    $tabla .= '
    <tr align="center">
    <td colspan="16">No hay registros en este periodo de fechas.</td>
    </tr>
    ';
}
$tabla .= '</table>';
$tabla .= '
<table border="1" cellpadding="3">
<tr align="right">
<td align="right" colspan="8"><b>TOTALES</b></td>
<td colspan="2">' . number_format($debeTotal, 2) . '</td>
<td colspan="2">' . number_format($haberTotal, 2) . '</td>
<td colspan="2">' . number_format($deudorTotal, 2) . '</td>
<td colspan="2">' . number_format((-1) * $acreedorTotal, 2) . '</td>
</tr>
</table>
';

$pdf->WriteHTMLCell(0, 0, '', '45', $tabla, 0, 0);
ob_end_clean();
$pdf->output('BALANCE DE SUMAS Y SALDOS.pdf', 'I');
