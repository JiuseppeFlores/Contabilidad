<?php

require_once('../conexion.php');
require_once('../Tcpdf/tcpdf.php');

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
<td colspan="3">Desde 01/01/2020    Hasta 31/05/2023</td>
</tr>
<tr style="font-size: 7px;">
<td></td>
</tr>
<tr style="font-size: 12px;">
<td>CUENTA</td>
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
$pdf->SetFont('helvetica', '', 10);
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
</table>
';
$pdf->WriteHTMLCell(0, 0, '', '60', $tabla, 0, 0);

$pdf->output('LIBRO MAYOR.pdf', 'I');
