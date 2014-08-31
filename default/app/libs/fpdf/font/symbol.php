<?php session_start();

	include_once("../clases/clase_articulo.php");
	include_once("../clases/clase_pdf_articulo.php");
	
	$articulo = new articulo();
	$parametro=$_SESSION["id2"]; 
	$articulo->settipoarticulo($parametro);
	$lista    = $articulo->buscarticulo();
	

	$pdf=new PDF('L','mm','Letter');
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	$pdf->SetMargins(20,20,20);
	$pdf->Ln(0);

  	$pdf->SetFont('Arial','B',14);
	$pdf->SetFillColor(170, 0, 0);
        $pdf->SetTextColor(255);

	$pdf->Cell(40,6,'Codigo',1,0,'C',true);
	$pdf->Cell(55,6,'Descripcion',1,0,'C',true);
	$pdf->Cell(35,6,'Tipo',1,0,'C',true);
	$pdf->Cell(35,6,'U/M',1,0,'C',true);
	$pdf->Cell(35,6,'Existencia',1,0,'C',true);
	$pdf->Cell(40,6,'Ubicacion',1,1,'C',true);

	
	if($lista=="-1")				
				{
					header("Location: ../php/error.php?url=../controladores/cierra_consulta.php & codigo=14 & mensaje2= Volver al Menu de Inicio.  ");	
				
				}
				else
				{
	   				for($i=0;$i<count($lista);$i++)
	     			{
		   			 	$codarti     = $lista[$i][1];
						$descrip     = $lista[$i][2];
						$tipo     	 = $lista[$i][3];
						$idmedida    = $lista[$i][4];
						$existencia  = $lista[$i][5];
						$ubicacion   = $lista[$i][6];
						$nombmedida  = $lista[$i][7];
							
						if($i%2 == 1)
							{
								$pdf->SetFillColor(249, 255, 167);
									$pdf->SetTextColor(0);
								$pdf->Cell(40,6,$codarti,1,0,'C',true);
								$pdf->Cell(55,6,$descrip,1,0,'C',true);
								$pdf->Cell(35,6,$tipo ,1,0,'C',true);
								$pdf->Cell(35,6,$nombmedida  ,1,0,'C',true);
								$pdf->Cell(35,6,$existencia  ,1,0,'C',true);
								$pdf->Cell(40,6,$ubicacion  ,1,1,'C',true);
								
							}
						else
							{
								$pdf->SetFillColor(253, 255, 212);
									$pdf->SetTextColor(0);
								
								$pdf->Cell(40,6,$codarti,1,0,'C',true);
								$pdf->Cell(55,6,$descrip,1,0,'C',true);
								$pdf->Cell(35,6,$tipo ,1,0,'C',true);
								$pdf->Cell(35,6,$nombmedida  ,1,0,'C',true);
								$pdf->Cell(35,6,$existencia  ,1,0,'C',true);
								$pdf->Cell(40,6,$ubicacion  ,1,1,'C',true);
							}
					}
				}
	
		$pdf->Output();	
		//Clase PDF
/*


include_once("../php/fpdf/fpdf.php");
class PDF extends FPDF
{	
	function Header()
	{
		$this->SetFont('Arial','BI',16);
		$this->Cell(0,18,'Lista de Articulos',0,0,'C', 0);
		$this->Ln(20);
			
	}

	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','BI',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C',0);
		
			$FechaActual=date("d/m/Y h:m a");
			$this->SetFont('Arial','I',10);
			$this->Cell(0,4,$FechaActual,0,0);
	}
}*/
?>