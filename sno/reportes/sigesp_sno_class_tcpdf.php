<?php
require_once('../../shared/class_folder/tcpdf/tcpdf.php');

class sigesp_sno_class_tcpdf extends TCPDF 
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_listadoconceptos($as_codconc,$as_nomcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_listadoconceptos
		//		   Access: private 
		//	    Arguments: as_codconc // Código de Concepto
		//	   			   as_nomcon // Nombre de Concepto
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->Ln(4);
		$this->SetFont("helvetica","B",7);
		$this->MultiCell(0,8,'Concepto '.$as_codconc.' - '.$as_nomcon.'',0, 1, 'L', 0, '0', 0);	
		$this->Ln(3);	
	}// end function uf_print_cabecera_listadoconceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_listadoconceptos($la_data,$ai_tottra,$ai_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_listadoconceptos
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$w=array(10,15,110,40,25);
		$this->SetFont("helvetica","B",6);
		$style1 = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "0", "color" => array(0, 0, 0));	
		$header=array('NRO','CEDULA','NOMBRE Y APELLIDO','CARGO','MONTO');
		for($i=0;$i<count($header);$i++)
		{
			$this->MultiCell($w[$i],6,$header[$i],0,'C', 0, 0, 0 ,0, true, 0);
		}
		$this->Ln();
		unset($style1);
		$fill=0;
		$this->SetFont("helvetica","",6);
		$y=0;
		foreach($la_data as $row) 
		{
			if ($y!=0)
			{
				$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,$y, true, 0);		
			}
			else
			{
				$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,0, true, 0);		
			}
			$this->MultiCell($w[1],6,strtolower($row[1]),0,'C', 0, 0, 0 ,0, true, 0);
			$y=($this->getY() + 3);		
			$this->MultiCell($w[2],6,$row[2],0,'L', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[3],6,$row[3],0,'C', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[4],6,$row[4],0,'R', 0, 0, 0 ,0, true, 0);
		}
	}// end function uf_print_detalle_listadoconceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_listadobanco($as_codban,$as_nomban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_listadobanco
		//		   Access: private 
		//	    Arguments: as_codban // Código de Banco
		//	   			   as_nomban // Nombre de Banco
		//    Description: función que imprime la cabecera por Banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->Ln(4);
		$this->SetFont("helvetica","B",7);
		$this->MultiCell(0,8,'Banco '.$as_codban.' - '.$as_nomban.'',0, 1, 'L', 0, '0', 0);	
		$this->Ln(3);	
	}// end function uf_print_cabecera_listadobanco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_listadobanco($la_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_listadobanco
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$w=array(10,15,110,40,25);
		$w=array(10,15,140,25);
		$this->SetFont("helvetica","B",6);
		$style1 = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "0", "color" => array(0, 0, 0));	
		$this->Ln(3);
		//$header=array('NRO','CEDULA','APELLIDOS Y NOMBRES','CUENTA BANCARIA','MONTO');
		$header=array('NRO','CEDULA','APELLIDOS Y NOMBRES','MONTO');
		for($i=0;$i<count($header);$i++)
		{
			$this->MultiCell($w[$i],6,$header[$i],0,'C', 0, 0, 0 ,0, true, 0);
		}
		$this->Ln(3);
		unset($style1);
		$fill=0;
		$this->SetFont("helvetica","",6);
		$y=0;
		foreach($la_data as $row) 
		{
			if ($y!=0)
			{
				$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,$y, true, 0);		
			}
			else
			{
				$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,0, true, 0);		
			}
			$this->MultiCell($w[1],6,strtolower($row[1]),0,'C', 0, 0, 0 ,0, true, 0);
			$y=($this->getY() + 3);		
			$this->MultiCell($w[2],6,$row[2],0,'L', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[3],6,$row[3],0,'C', 0, 0, 0 ,0, true, 0);
			//$this->MultiCell($w[4],6,$row[4],0,'R', 0, 0, 0 ,0, true, 0);
		}
	}// end function uf_print_detalle_listadobanco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera_listadobanco($ai_totalpersonas,$ai_total)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera_listadobanco
		//		   Access: private 
		//	    Arguments: as_codban // Código de Banco
		//	   			   as_nomban // Nombre de Banco
		//    Description: función que imprime la cabecera por Banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->Ln(4);
		$this->SetFont("helvetica","B",7);
		$this->Cell(0,6, 'TOTAL GENERAL  PERSONAS('.$ai_totalpersonas.')           '.$ai_total.'                ', 1, 1, 'R'); 
		$this->Ln(3);	
	}// end function uf_print_cabecera_listadobanco
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>