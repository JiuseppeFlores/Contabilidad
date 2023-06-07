<?php
require_once('../conexion.php');
require_once('../Tcpdf/tcpdf.php');

$codigoCuenta = '11000.02';
$fechaInicial = '2023-01-01';
$fechaFinal = '2023-12-31';

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
$query = sqlsrv_query($con, $sql);
$listaAsientos = array();
while ($row = sqlsrv_fetch_array($query)) {
    $listaAsientos[] = $row;
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
        $this->MultiCell(50, 10, "N° DE PAG.: " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "\nFECHA DE IMP. 31/05/2023\nGESTION    2020", 0, 'L', 0, 1, '150', '8', true);
        $this->MultiCell(23, 10, "EMPRESA\nDIRECCION\nNIT", 0, 'L', 0, 1, '10', '8', true);
        $this->MultiCell(100, 10, "SABOR ANDINO\nURB. VIRGEN DEL CARMEN CALLE ARICA No.777\n181252025", 0, 'L', 0, 1, '33', '8', true);
    }
    public function Footer()
    {
    }
}
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
// Configurar las propiedades del documento
$pdf->SetCreator('STIS');
$pdf->SetAuthor('STIS');
$pdf->SetTitle('LIBRO MAYOR');
$pdf->SetSubject('LIBRO MAYOR');

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
<td colspan="3"><b>LIBRO MAYOR</b></td>
</tr>
<tr align="center" style="font-size: 10px;">
<td colspan="3">Experesado en Bs.</td>
</tr>
<tr align="center" style="font-size: 11px;">
<td colspan="3">Desde ' . $fechaInicialFormato . '    Hasta ' . $fechaFinalFormato . '</td>
</tr>
<tr style="font-size: 7px;">
<td></td>
</tr>
<tr style="font-size: 12px;">
<td>CUENTA ' . $codigoCuenta . '</td>
<td colspan="2">NOMBRE</td>
</tr>
<tr style="font-size: 7px;">
<td></td>
</tr>
<tr style="font-size: 11px;">
<td></td>
<td>SALDO ANTERIOR</td>
<td>MOVIMIENTO</td>
</tr>
</table>
';
$pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
$pdf->SetFont('helvetica', '', 9);
$tabla = '
<table border="1" cellpadding="2">
<tr align="center">
<th colspan="2">FECHA</th>
<th>T</th>
<th>N°</th>
<th>T./C.</th>
<th colspan="6">DESCRIPCION</th>
<th colspan="2">CHEQUE</th>
<th colspan="2">DEBE</th>
<th colspan="2">HABER</th>
<th colspan="2">SALDOS</th>
</tr>
';
if (count($listaAsientos) > 0) {
    $saldo = 0;
    $debeTotal = 0;
    $haberTotal = 0;
    foreach ($listaAsientos as $key => $value) {
        $fecha = $value['fecha']->format('d/m/Y');

        $debe = floatval($value['debe']);
        $haber = floatval($value['haber']);
        $saldo = number_format($saldo + $debe - $haber, 2);

        $debeTotal = number_format($debeTotal + $debe, 2);
        $haberTotal = number_format($haberTotal + $haber, 2);

        $tabla .= '
        <tr align="center">
        <td colspan="2">' . $fecha . '</td>
        <td></td>
        <td></td>
        <td>' . $value['tipoCambio'] . '</td>
        <td colspan="6" align="left">' . $value['referencia'] . '</td>
        <td colspan="2">' . $value['cheque'] . '</td>
        <td colspan="2">' . $value['debe'] . '</td>
        <td colspan="2">' . $value['haber'] . '</td>
        <td colspan="2">' . $saldo . '</td>
        </tr>
        ';
    }
} else {
    $tabla .= '
    <tr align="center">
    <td colspan="19">No hay registros en este periodo de fechas.</td>
    </tr>
    ';
}
$tabla .= '</table>';
$tabla .= '
<table border="0" cellpadding="3">
<tr align="center">
<td align="right" colspan="13"></td>
<td colspan="2">' . $debeTotal . '</td>
<td colspan="2">' . $haberTotal . '</td>
<td colspan="2"></td>
</tr>
<tr align="center">
<td align="right" colspan="13">SALDO DEUDOR</td>
<td colspan="4">' . $saldo . '</td>
<td colspan="2"></td>
</tr>
</table>
';

$pdf->WriteHTMLCell(0, 0, '', '60', $tabla, 0, 0);

$pdf->output('LIBRO MAYOR.pdf', 'I');
