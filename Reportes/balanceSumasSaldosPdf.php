<?php
require_once('../conexion.php');
require_once('../Tcpdf/tcpdf.php');

$codigoCuenta = '11000.02';
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
        // if ($_COOKIE['base_subdominio'] == 'sindan') {
        //     // Logo
        //     $image_file = '../images/excelKardex/logo_sindan.png';
        //     $this->Image($image_file, 8, 8, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // } else if ($_COOKIE['base_subdominio'] == 'saborandino') {
        //     $image_file = '../images/excelKardex/logo_sabor_andino.jpg';
        //     $this->Image($image_file, 8, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // }
        $this->SetFont('helvetica', '', 10);
        $this->MultiCell(30, 10, "NIT   181252025\nGESTION    2020", 0, 'L', 0, 1, '170', '8', true);
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
$pdf->SetTitle('BALANCE DE SUMAS Y SALDOS');
$pdf->SetSubject('BALANCE DE SUMAS Y SALDOS');

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
        $saldo = number_format($debe - $haber, 2);
        if ($saldo < 0) {
            $acreedor = $saldo;
            $deudor = 0.00;
        } else {
            $deudor = $saldo;
            $acreedor = 0.00;
        }

        $debeTotal = number_format($debeTotal + $debe, 2);
        $haberTotal = number_format($haberTotal + $haber, 2);
        $deudorTotal = number_format($deudorTotal + $deudor, 2);
        $acreedorTotal = number_format($acreedorTotal + $acreedor, 2);

        $tabla .= '
        <tr align="center">
        <td colspan="2" align="left">' . $value['codigo'] . '</td>
        <td colspan="6" align="left">' . $value['descripcion'] . '</td>
        <td colspan="2" align="rigth">' . $value['debe'] . '</td>
        <td colspan="2" align="rigth">' . $value['haber'] . '</td>
        <td colspan="2" align="rigth">' . number_format($deudor, 2) . '</td>
        <td colspan="2" align="rigth">' . number_format($acreedor, 2) . '</td>
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
<td colspan="2">' . $debeTotal . '</td>
<td colspan="2">' . $haberTotal . '</td>
<td colspan="2">' . $deudorTotal . '</td>
<td colspan="2">' . $acreedorTotal . '</td>
</tr>
</table>
';

$pdf->WriteHTMLCell(0, 0, '', '45', $tabla, 0, 0);

$pdf->output('BALANCE DE SUMAS Y SALDOS.pdf', 'I');
