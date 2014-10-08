<?php 

Load::lib('fpdf/fpdf');

class PDF extends FPDF
{	
	private $tipo;

	function Header()
	{
		//Logo Reparar Ruta
		//$ruta = PUBLIC_PATH. 'default/public/img/cintillo.png';
		//$this->Image($ruta,8,8,199,25);
		//Título		
		$t=utf8_decode('PLANILLA DE AFILIACIÓN');
		$this->Ln(20);
		$this->SetFont('Times','B',12);
		$this->Cell(0,10,$t,0,1,'C');
		$this->SetFont('Times','B',8);
		$this->Cell(0,1,'Sistema Autogestionado de Salud',0,1,'C');
		$this->SetFont('Times','B',12);
		// Quitar por recomendacion d xiomanra $this->Cell(0,10,'EMPRESA MIXTA SOCIALISTA ARROZ DEL ALBA, S.A.',0,1,'C');
		$this->Ln(10);		
	}
	function Footer()
	{
		$this->SetFont('Times','',12);
		$this->SetY(-22);
		$this->SetFont('Times','I',9);
		$this->Cell(0,2,utf8_decode('Carretera nacional vía Turen, sector E, planta Arroz del Alba, S.A Piritu Estado Portuguesa'),0,1,'C',0);
		$this->SetFont('Times','BI',9);
		$this->Cell(0,4,utf8_decode('Teléfonos 0256-3361377 / 3361455 / 3361333 / 3362000 / 3361255 '),0,0,'C',0);
	}
}

?>