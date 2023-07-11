<?php
require_once('../conexion.php');
require_once('../Tcpdf/tcpdf.php');
require_once('convertidorTexto.php');

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$idComprobante = isset($GET['idComprobante']) ? $GET['idComprobante'] : '1';

// para la consulta a la base datos
$sql = "SELECT tcu.codigo, tcu.descripcion, tas.referencia, tco.fecha, tco.tipo, tco.tipoCambio, tco.numero, tco.cancelado, tco.glosa, tco.moneda, SUM(tas.debe) totalDebe, SUM(tas.haber) totalHaber, SUM(tas.debeDolar) totalDebeDolar, SUM(tas.haberDolar) totalHaberDolar FROM tblComprobantes tco LEFT JOIN tblAsientos tas ON tco.idComprobante = tas.idComprobante LEFT JOIN tblCuentas tcu ON tcu.idCuenta = tas.idCuenta WHERE tco.estado = 'ACTIVO'AND tcu.movimiento = 1 AND tco.idComprobante = $idComprobante GROUP BY tcu.codigo, tcu.descripcion, tas.referencia, tco.fecha, tco.tipo, tco.tipoCambio, tco.numero, tco.cancelado, tco.glosa, tco.moneda ORDER BY tcu.codigo ASC;";
// echo $sql;
$query = sqlsrv_query($con, $sql);
$listaComprobantes = array();
$tipo = '';
$tipoCambio = '';
$fecha = '';
$numero = '';
$cancelado = '';
$glosa = '';
$moneda = '';
while ($row = sqlsrv_fetch_array($query)) {
    $listaComprobantes[] = $row;
    $tipo = $row['tipo'];
    $tipoCambio = $row['tipoCambio'];
    $fecha = $row['fecha'];
    $numero = $row['numero'];
    $cancelado = $row['cancelado'];
    $glosa = $row['glosa'];
    $moneda = $row['moneda'];
}
$fechaFormato = '';
if ($fecha != '') {
    // $fechaFormato = new DateTime($fecha);
    $fechaFormato = $fecha->format('d/m/Y');
}
define("fechaFormatoG", $fechaFormato);
define("tipoCambioG", $tipoCambio);

class MYPDF extends TCPDF
{
    public function Header()
    {
        // if ($_COOKIE['base_subdominio'] == 'sindan') {
        //     // Logo
        $image_file = '../Images/logo_sabor_andino.jpg';
        $this->Image($image_file, 163, 5, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // } else if ($_COOKIE['base_subdominio'] == 'saborandino') {
        //     $image_file = '../images/excelKardex/logo_sabor_andino.jpg';
        //     $this->Image($image_file, 8, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // }
        $this->SetFont('helvetica', '', 9);
        // $this->MultiCell(50, 10, "N° DE PAG.: " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "\nFECHA DE IMP. 31/05/2023\nGESTION    2023", 0, 'L', 0, 1, '150', '8', true);
        $this->MultiCell(23, 10, "EMPRESA\nDIRECCION\nNIT\nN° DE PAG.", 0, 'L', 0, 1, '20', '8', true);
        $this->MultiCell(100, 10, "Sindan Organic S.R.L. (Planta 2 Sabor Andino)\nAv. 12 de diciembre N° 2216 Zona Senkata\n181252025\n" . $this->getAliasNumPage() . "/" . $this->getAliasNbPages() . "", 0, 'L', 0, 1, '43', '8', true);
    }
    public function Footer()
    {
    }
}
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$nombreComprobante = $tipo != '' ? 'DE ' . $tipo : '';
// Configurar las propiedades del documento
$pdf->SetCreator('STIS');
$pdf->SetAuthor('STIS');
$pdf->SetTitle('COMPROBANTE ' . $nombreComprobante);
$pdf->SetSubject('COMPROBANTE ' . $nombreComprobante);

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
switch ($tipo) {
    case 'INGRESO':
        $subtitulo1 = 'Recibido de:';
        break;
    case 'EGRESO':
        $subtitulo1 = 'Cancelado a:';
        break;
    case 'TRASPASO':
        $subtitulo1 = 'Detalle:';
        break;
    default:
        $subtitulo1 = '';
        break;
}
$tabla = '';
$tabla .= '
<table border="0" cellpaddind="2">
<tr align="center">
<td colspan="4"><b>COMPROBANTE ' . $nombreComprobante . '</b></td>
</tr>
<tr align="center" style="font-size: 11px;">
<td colspan="4">N° DE COMPROBANTE: ' . $numero . '</td>
</tr>
<tr align="center" style="font-size: 10px;">
<td colspan="4">Fecha: ' . $fechaFormato . '&nbsp;&nbsp;&nbsp;TC: ' . $tipoCambio . '</td>
</tr>
<tr>
<td></td>
</tr>
<tr align="left" style="font-size: 10px;">
<td colspan="4">' . $subtitulo1 . '&nbsp;&nbsp;' . $cancelado . '</td>
</tr>
<tr align="left" style="font-size: 10px;">
<td colspan="4">Por concepto de:&nbsp;&nbsp;' . $glosa . '</td>
</tr>';
// $tabla .= '
// <tr align="left" style="font-size: 10px;">
// <td colspan="1">Efectivo:&nbsp;&nbsp;' . $moneda . '</td>
// <td colspan="1">Cheque:&nbsp;&nbsp;</td>
// <td colspan="2">Bancos:&nbsp;&nbsp;</td>
// </tr>';
$tabla .='
</table>
';
$pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
$pdf->SetFont('helvetica', '', 8);
$tabla = '
<table border="1" cellpadding="1">
<tr align="center" style="font-size: 9px;">
<th colspan="2">CODIGOS</th>
<th colspan="6">DESCRIPCION</th>
<th colspan="2">DEBE BS</th>
<th colspan="2">HABER BS</th>
<th colspan="2">DEBE US</th>
<th colspan="2">HABER US</th>
</tr>
';
if (count($listaComprobantes) > 0) {
    $totalDebe = 0;
    $totalHaber = 0;
    $totalDebeUs = 0;
    $totalHaberUs = 0;
    foreach ($listaComprobantes as $key => $value) {
        // $debeUs = $value['totalDebe'] * $tipoCambio;
        // $haberUs = $value['totalHaber'] * $tipoCambio;
        $debeUs = $value['totalDebeDolar'];
        $haberUs = $value['totalHaberDolar'];
        $totalDebe = $totalDebe + $value['totalDebe'];
        $totalHaber = $totalHaber + $value['totalHaber'];
        $totalDebeUs = $totalDebeUs + $debeUs;
        $totalHaberUs = $totalHaberUs + $haberUs;
        $tabla .= '
        <tr>
        <td colspan="2">' . $value['codigo'] . '</td>
        <td colspan="6"><u>' . $value['descripcion'] . '</u><br> ' . $value['referencia'] . '</td>
        <td colspan="2" align="rigth">' . number_format($value['totalDebe'], 2) . '</td>
        <td colspan="2" align="rigth">' . number_format($value['totalHaber'], 2) . '</td>
        <td colspan="2" align="rigth">' . number_format($debeUs, 2) . '</td>
        <td colspan="2" align="rigth">' . number_format($haberUs, 2) . '</td>
        </tr>';
    }
    $tabla .= '
    <tr>
    <td colspan="8" align="right"><b>TOTALES</b></td>
    <td colspan="2" align="right">' . number_format($totalDebe, 2) . '</td>
    <td colspan="2" align="right">' . number_format($totalHaber, 2) . '</td>
    <td colspan="2" align="right">' . number_format($totalDebeUs, 2) . '</td>
    <td colspan="2" align="right">' . number_format($totalHaberUs, 2) . '</td>
    </tr>';
} else {
    $tabla .= '
    <tr align="center">
    <td colspan="16">No hay registros relacionados con el comprobante.</td>
    </tr>';
}
$tabla .= '</table>';
$tabla .= '
<table border="0" cellpadding="0">
<tr>
<td colspan="10"></td>
</tr>
<tr>
<td colspan="10"><b>Son: </b>' . numtoletras($totalDebe) . '</td>
</tr>
<tr>
<td colspan="10"></td>
</tr>
<tr>
<td colspan="10"></td>
</tr>
<tr>
<td colspan="10"></td>
</tr>
<tr>
<td colspan="10"></td>
</tr>
<tr align="center">
<td colspan="1"></td>
<td colspan="2" style="border-top: 0.7px solid black;">Recibí conforme</td>
<td colspan="1"></td>
<td colspan="2" style="border-top: 0.7px solid black;">Vo. Bo.</td>
<td colspan="1"></td>
<td colspan="2" style="border-top: 0.7px solid black;">Contabilidad</td>
<td colspan="1"></td>
</tr>
<tr align="left">
<td colspan="1"></td>
<td colspan="2">Nombre:</td>
<td colspan="1"></td>
<td colspan="2"></td>
<td colspan="1"></td>
<td colspan="2">Nombre:</td>
<td colspan="1"></td>
</tr>
<tr align="left">
<td colspan="1"></td>
<td colspan="2">CI:</td>
<td colspan="1"></td>
<td colspan="2"></td>
<td colspan="1"></td>
<td colspan="2"></td>
<td colspan="1"></td>
</tr>
</table>';
$pdf->WriteHTMLCell(0, 0, '', '60', $tabla, 0, 0);
ob_end_clean();
$pdf->output('COMPROBANTE ' . $nombreComprobante . '.pdf', 'I');
