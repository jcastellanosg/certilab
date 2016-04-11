<?php

require_once('tcpdf.php');

class MYPDF extends TCPDF
{
    var $htmlHeader;

    public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L')
    {
        $this->SetXY($x + 20, $y); // 20 = margin left
        $this->SetFont('times', 'BI', 20, '', 'false');
        $this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
        $this->Cell($width, $height, $textval, 0, false, $align);
    }




    // Page footer
    public function Footer()
    {
        $texto1 = 'La presente factura de venta se asimila en todos los efectos legales a la letra de cambio según el articulo 774 del Codigo de Comercio';
        $texto2 = 'declarando recibido a conformidad los productos o servicios';
        $this->SetFont('helvetica', 'I', 8);
        $this->SetY(-25);
        $this->Cell(0, 20, $texto1, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetY(-20);
        $this->Cell(0, 20, $texto2, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetY(-15);
        $this->Cell(0, 20, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }




    public function setHtmlHeader($htmlHeader) {
        $this->htmlHeader = $htmlHeader;
    }

    public function Header() {
        $this->writeHTMLCell(
            $w = 0, $h = 0, $x = '', $y = '',
            $this->htmlHeader, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    }

}
