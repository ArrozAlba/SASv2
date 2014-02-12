<?php
require_once('../../shared/class_folder/tcpdf/tcpdf.php');
require_once("../../shared/class_folder/class_datastore.php");;
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
$fun=new class_funciones() ;
$siginc=new sigesp_include();
$con=$siginc->uf_conectar();		
$obj=new class_datastore();

		

class sigesp_spg_class_tcpdf extends TCPDF 

{
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    //////////  F U N C I O N E S    P A R A    E L    MA Y O R    A N A L I T I C O  //////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       //--------------------------------------------------------------------------------------------------------------------------------

	
	public function uf_print_titulos_campoext ($as_mostrar)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_titulos 
	//		    Acess: private 
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. María Beatriz Unda
	// Fecha Creación: 13/08/08 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$this->SetFont("helvetica","B",7);
	if ($as_mostrar==0)
	{
		$w=array(16,28,28,65,23,23,23,27,25,23,23,23,23);		
		$header=array('Fecha','Comprobante','Documento','Detalle','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Causado','Pagado','Por Pagar');
	}
	else
	{
		$w=array(16,28,30,25,25,25,30,27,25,25,25,25);		
		$header=array('Fecha','Comprobante','Documento','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Causado','Pagado','Por Pagar');
	}
	
	for($i=0;$i<count($header);$i++)
	{
		
		if ($as_mostrar==0)
		{		
			if ($i<=3)
			{
				
				$this->Cell($w[$i],6,$header[$i],'0',0,'L',0);
				
			}
			elseif ($i>3)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'R',0);
			}
		}
		else
		{
			if ($i<=2)
			{
				
				$this->Cell($w[$i],6,$header[$i],'0',0,'L',0);
				
			}
			elseif ($i>2)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'R',0);
			}
		}
		
	}
	
	$this->Ln();
	
}// end function uf_print_titulos
	

	public function uf_print_titulos_campoext2 ($as_mostrar)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_titulos 
	//		    Acess: private 
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. María Beatriz Unda
	// Fecha Creación: 13/08/08 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$this->SetFont("helvetica","B",7);
	if ($as_mostrar==0)
	{
		$w=array(16,28,28,40,23,23,23,27,25,23,24,23,23,23);		
		$header=array('Fecha','Comprobante','Documento','Detalle','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Por Comprometer','Causado','Pagado','Por Pagar');
	}
	else
	{
		$w=array(16,28,30,25,25,25,30,27,25,25,25,25,25);		
		$header=array('Fecha','Comprobante','Documento','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Por Comprometer','Causado','Pagado','Por Pagar');
	}
	
	for($i=0;$i<count($header);$i++)
	{
		
		if ($as_mostrar==0)
		{		
			if ($i<=3)
			{
				
				$this->Cell($w[$i],6,$header[$i],'0',0,'L',0);
				
			}
			elseif ($i>3)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'R',0);
			}
		}
		else
		{
			if ($i<=2)
			{
				
				$this->Cell($w[$i],6,$header[$i],'0',0,'L',0);
				
			}
			elseif ($i>2)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'R',0);
			}
		}
		
	}
	
	$this->Ln();
	
}// end function uf_print_titulos

//--------------------------------------------------------------------------------------------------------------------------------


	public function uf_print_detalle_campoext($la_data,&$y,$tipo,$as_mostrar)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$fill=0;
	
	if ($as_mostrar==0)
	{
		$w=array(16,28,28,65,23,23,23,27,25,23,23,23,23);		
		
	}
	else
	{
		$w=array(16,28,30,25,25,25,30,27,25,25,25,25);		
		
	}
	
	$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "2,10", "color" => array(0, 0, 0));
	$this->SetFont("helvetica","",7);
	$y=0;
	foreach($la_data as $row) 
	{
		if ($y!=0)
		{
			$this->MultiCell($w[0],6,$row[0],0,'L', 0, 0, 0 ,$y, true, 0);		
		}
		else
		{
			$this->MultiCell($w[0],6,$row[0],'0','L', 0, 0, 0 ,0, true, 0);		
		}
		
		$this->MultiCell($w[1],6,$row[1],'0','L', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[2],6,$row[2],'0','L', 0, 0, 0 ,0, true, 0);		
		if ($as_mostrar==0)
		{
			$row[3]=strtolower($row[3]);
			$this->MultiCell($w[3],6,substr($row[3],0,100),'0','L', 0, 0, 0 ,0, true, 0);	
			$y=($this->getY() + 8);	
			$this->MultiCell($w[4],6,$row[4],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[5],6,$row[5],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[6],6,$row[6],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[7],6,$row[7],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[8],6,$row[8],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[9],6,$row[9],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[10],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[11],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[12],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
		}
		else
		{
			$this->MultiCell($w[3],6,$row[4],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[4],6,$row[5],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[5],6,$row[6],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[6],6,$row[7],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[7],6,$row[8],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[8],6,$row[9],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[9],6,$row[10],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[11],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
			
		}
			
		
		$this->Ln();
	}
	
	if ($as_mostrar==0)
		{
			if ($tipo==1)
			{
				$this->Ln(2);
				$this->Line(351, $this->GetY(), 137, $this->GetY(), $style);
			}
			
		}
		else
		{
			if ($tipo==1)
			{
				$this->Ln(2);
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
			}
			
		}
	
	
	
	
}// end function uf_print_detalle




	public function uf_print_detalle_campoext2($la_data,&$y,$tipo,$as_mostrar)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$fill=0;
	
	if ($as_mostrar==0)
	{
		$w=array(16,28,28,40,23,23,23,27,25,23,23,23,23);		
		
	}
	else
	{
		$w=array(16,28,30,25,25,25,30,27,25,25,25,25);		
		
	}
	
	$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "2,10", "color" => array(0, 0, 0));
	$this->SetFont("helvetica","",7);
	$y=0;
	foreach($la_data as $row) 
	{
		if ($y!=0)
		{
			$this->MultiCell($w[0],6,$row[0],0,'L', 0, 0, 0 ,$y, true, 0);		
		}
		else
		{
			$this->MultiCell($w[0],6,$row[0],'0','L', 0, 0, 0 ,0, true, 0);		
		}
		
		$this->MultiCell($w[1],6,$row[1],'0','L', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[2],6,$row[2],'0','L', 0, 0, 0 ,0, true, 0);		
		if ($as_mostrar==0)
		{
			$row[3]=strtolower($row[3]);
			$this->MultiCell($w[3],6,substr($row[3],0,65),'0','L', 0, 0, 0 ,0, true, 0);	
			$y=($this->getY() + 8);	
			$this->MultiCell($w[4],6,$row[4],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[5],6,$row[5],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[6],6,$row[6],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[7],6,$row[7],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[8],6,$row[8],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[9],6,$row[9],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[13],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[10],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[11],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[12],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
		}
		else
		{
			$this->MultiCell($w[3],6,$row[4],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[4],6,$row[5],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[5],6,$row[6],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[6],6,$row[7],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[7],6,$row[8],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[8],6,$row[9],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[9],6,$row[13],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[10],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[11],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
			
		}
			
		
		$this->Ln();
	}
	
	if ($as_mostrar==0)
		{
			if ($tipo==1)
			{
				$this->Ln(2);
				$this->Line(351, $this->GetY(), 137, $this->GetY(), $style);
			}
			
		}
		else
		{
			if ($tipo==1)
			{
				$this->Ln(2);
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
			}
			
		}
	
	
	
	
}// end function uf_print_detalle

       //--------------------------------------------------------------------------------------------------------------------------------

	public function uf_print_titulos ($as_mostrar)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_titulos 
	//		    Acess: private 
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. María Beatriz Unda
	// Fecha Creación: 13/08/08 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$this->SetFont("helvetica","B",7);
	if ($as_mostrar==0)
	{
		$w=array(16,28,28,20,35,33,23,23,33,25,20,20,20,20);		
		$header=array('Fecha','Comprobante','Documento','Detalle','Benef / Prov','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Causado','Pagado','Por Pagar');
	}
	else
	{
		$w=array(16,28,30,25,25,25,25,30,27,25,25,25,25);		
		$header=array('Fecha','Comprobante','Documento','Beneficiario','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Causado','Pagado','Por Pagar');
	}
	
	for($i=0;$i<count($header);$i++)
	{
		
		if ($as_mostrar==0)
		{		
			if ($i<4)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'L',0);	
			}
			elseif($i==4)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'C',0);
			}
			elseif ($i>4)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'R',0);
			}
		}
		else
		{
			if ($i<3)
			{
				
				$this->Cell($w[$i],6,$header[$i],'0',0,'L',0);	
			}
			
			
			elseif ($i>3)
			{
				$this->Cell($w[$i],6,$header[$i],'0',0,'R',0);
			}
		}
		
	}
	
	$this->Ln();
	
}// end function uf_print_titulos

//--------------------------------------------------------------------------------------------------------------------------------

	public function uf_print_detalle($la_data,&$y,$tipo,$as_mostrar)
	{
		

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$fill=0;
	
	if ($as_mostrar==0)
	{
		$w=array(16,28,28,27,27,33,23,23,34,25,20,20,20,20);		
		
	}
	else
	{
		$w=array(16,28,30,27,26,25,25,30,27,25,25,25,25);		
		
	}
	
	$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "2,10", "color" => array(0, 0, 0));
	$this->SetFont("helvetica","",6);
	$y=0;
	foreach($la_data as $row) 
	{
		if($row[1]=="CO0000000000001" && $row[2]=="20-ADM000000001")
		{
			//var_dump($row);
			//die();
		}
		
		if ($y!=0)
		{
			$this->MultiCell($w[0],6,$row[0],0,'L', 0, 0, 0 ,$y, true, 0);		
		}
		else
		{
			$this->MultiCell($w[0],6,$row[0],'0','L', 0, 0, 0 ,0, true, 0);		
		}
		
		$this->MultiCell($w[1],6,$row[1],'0','L', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[2],6,$row[2],'0','L', 0, 0, 0 ,0, true, 0);		
		if ($as_mostrar==0)
		{
			$row[3]=strtolower($row[3]);
			$this->MultiCell($w[3],6,substr($row[3],0,60),'0','L', 0, 0, 0 ,0, true, 0);	
			$y=($this->getY() + 8);	
			$this->MultiCell($w[4],6,substr($row[4],0,30),'0','L', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[5],6,$row[5],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[6],6,$row[6],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[7],6,$row[7],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[8],6,$row[8],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[9],6,$row[9],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[10],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[11],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[12],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[13],6,$row[13],'0','R', 0, 0, 0 ,0, true, 0);
		}
		else
		{
		//	$this->MultiCell($w[4],6,substr($row[4],0,30),'0','L', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[4],6,$row[5],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[5],6,$row[6],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[6],6,$row[7],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[7],6,$row[8],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[8],6,$row[9],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[9],6,$row[10],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[10],6,$row[11],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
			$this->MultiCell($w[11],6,$row[12],'0','R', 0, 0, 0 ,0, true, 0);
		}
			
		
		$this->Ln();
	}
	
	if ($as_mostrar==0)
		{
			if ($tipo==1)
			{
				$this->Ln(2);
				$this->Line(351, $this->GetY(), 137, $this->GetY(), $style);
			}
			
		}
		else
		{
			if ($tipo==1)
			{
				$this->Ln(2);
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
			}
			
		}
	
	
	
	
}// end function uf_print_detalle


//--------------------------------------------------------------------------------------------------------------------------------

 public function uf_print_cabecera($as_programatica,$as_denestpro)
 {
 	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_programatica // programatica del comprobante
	//	    		   as_denestpro5 // denominacion de la programatica del comprobante
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing.Yozelin Barragán
	// Fecha Creación: 21/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//if($as_programatica=='A00421010-0106')
		//{
		 //	var_dump($as_denestpro);
		 //	die();
		//}
 		$this->Ln();
		$this->SetFont("helvetica","B",7);
		$dts_empresa=$_SESSION["la_empresa"];
		$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "0", "color" => array(0, 0, 0));
		$this->SetLineStyle($style);
		if ($dts_empresa["estmodest"] == 2)
		{
			$this->MultiCell(0,8,'Programatica '.$as_programatica,1,'L', 0,1, 0 ,0, true, 0);
			$this->MultiCell(0,16,$as_denestpro,1,'L', 0,1, 0 ,0, true, 0);
		}
		else
		{
			$ls_loncodestpro1 = $dts_empresa["loncodestpro1"];
			$ls_loncodestpro2 = $dts_empresa["loncodestpro2"];
			$ls_loncodestpro3 = $dts_empresa["loncodestpro3"];
			$titulo1=substr($as_programatica,0,$ls_loncodestpro1).' '.$as_denestpro[0];
			$titulo2=substr($as_programatica,$ls_loncodestpro1,$ls_loncodestpro2).' '.$as_denestpro[1];
			$titulo3=substr($as_programatica,$ls_loncodestpro1+$ls_loncodestpro2,$ls_loncodestpro3).' '.$as_denestpro[2];
				
			$this->Cell(0,8,'ESTRUCTURA PRESUPUESTARIA',0, 1, 'L', 0, '0', 0);		
			$this->Cell(0,8,$titulo1, 1, 1, 'L', 0, '0', 0);
			$this->Cell(0,8,$titulo2, 1, 1, 'L', 0, '0', 0);
			$this->Cell(0,8,$titulo3, 1, 1, 'L', 0, '0', 0);			
							   
		}			   
	}// end function uf_print_cabecera

//--------------------------------------------------------------------------------------------------------------------------------
	
	public function uf_print_cabecera_detalle($as_spg_cuenta,$as_denominacion)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera_detalle
	//		   Access: private 
	//	    Arguments: as_spg_cuenta //cuenta
	//	    		   as_denominacion // denominacion 
	//	    		   io_pdf // Objeto PDF
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing.Yozelin Barragán
	// Fecha Creación: 21/04/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->SetFont("helvetica","B",8);
		$this->Cell(0,8,'Cuenta '.$as_spg_cuenta.'  ---  '.$as_denominacion.'',0, 1, 'L', 0, '', 0);
	}// end function uf_print_cabecera_detalle
	

//--------------------------------------------------------------------------------------------------------------------------------

	public	function uf_print_total2($la_data,$ls_tipo,$y,$as_mostrar)
	{
		//var_dump($la_data);
		//die();
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_total
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "2,10", "color" => array(0, 0, 0));
		
		if ($as_mostrar==0)
		{
			$w=array(120,30,22,22,30,22,21,23,30,23);	
			if (($ls_tipo=='1'))
			{    
				$this->Line(351, $this->GetY(), 137, $this->GetY(), $style);
				
			}
			elseif ($ls_tipo=='3')
			{   
				$w=array(120,30,22,22,30,22,21,23,30,23);	
				$this->Ln(3); 
				$this->SetFont("helvetica","B",8);
				$this->Line(351, $this->GetY(), 133, $this->GetY(), $style);
				
			}
			
		}
		else
		{
			$w=array(74,25,25,25,30,27,25,25,25,25);	
			if (($ls_tipo=='1'))
			{    
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
				
			}
			elseif ($ls_tipo=='3')
			{   
				$this->Ln(3); 
				$this->SetFont("helvetica","B",8);
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
				
			}	
			
		}
					
		foreach($la_data as $row) 
		{
			if ($y!=0)
			{
				$this->MultiCell($w[0],6,$row[0],'0','R', 0, 0, 0 ,0, true, 0);	
				
			}
			else
			{
				$this->MultiCell($w[0],6,$row[0],'0','R', 0, 0, 0 ,$y, true, 0);	
			}
			$this->MultiCell($w[1],6,$row[1],'0',0,'R',0);
			$this->MultiCell($w[2],6,$row[2],'0',0,'R',0);
			$this->MultiCell($w[3],6,$row[3],'0',0,'R',0);
			$this->MultiCell($w[4],6,$row[4],'0',0,'R',0);
			$this->MultiCell($w[5],6,$row[5],'0',0,'R',0);
			$this->MultiCell($w[6],6,$row[10],'0',0,'R',0);
			$this->MultiCell($w[6],6,$row[6],'0',0,'R',0);
			$this->MultiCell($w[7],6,$row[7],'0',0,'R',0);
			$this->MultiCell($w[8],6,$row[8],'0',0,'R',0);
			$this->MultiCell($w[9],6,$row[9],'0',0,'R',0);		
			$this->Ln();
				
		}
		unset($style);
		unset($w);
	}

	
	public	function uf_print_total($la_data,$ls_tipo,$y,$as_mostrar)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_total
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "2,10", "color" => array(0, 0, 0));
		
		if($as_mostrar==0)
		{
			$w=array(137,23,23,23,33,25,20,20,21,20);	
			if (($ls_tipo=='1'))
			{    
				$this->Line(351, $this->GetY(), 137, $this->GetY(), $style);
				
			}
			elseif ($ls_tipo=='3')
			{   
				$w=array(137,23,23,23,33,25,20,20,21,20);	
				$this->Ln(3); 
				$this->SetFont("helvetica","B",8);
				$this->Line(351, $this->GetY(), 133, $this->GetY(), $style);	
			}
			
		}
		else
		{
			$w=array(74,25,25,25,30,27,25,25,25,25);	
			if (($ls_tipo=='1'))
			{    
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
				
			}
			elseif ($ls_tipo=='3')
			{   
				$this->Ln(3); 
				$this->SetFont("helvetica","B",8);
				$this->Line(320, $this->GetY(), 74, $this->GetY(), $style);
				
			}	
			
		}
					
		foreach($la_data as $row) 
		{
			if ($y!=0)
			{
				$this->MultiCell($w[0],6,$row[0],'0','R', 0, 0, 0 ,0, true, 0);	
				
			}
			else
			{
				$this->MultiCell($w[0],6,$row[0],'0','R', 0, 0, 0 ,$y, true, 0);	
			}
			$this->Cell($w[1],6,$row[1],'0',0,'R',0);
			$this->Cell($w[2],6,$row[2],'0',0,'R',0);
			$this->Cell($w[3],6,$row[3],'0',0,'R',0);
			$this->Cell($w[4],6,$row[4],'0',0,'R',0);
			$this->Cell($w[5],6,$row[5],'0',0,'R',0);
			$this->Cell($w[6],6,$row[6],'0',0,'R',0);
			$this->Cell($w[7],6,$row[7],'0',0,'R',0);
			$this->Cell($w[8],6,$row[8],'0',0,'R',0);
			$this->Cell($w[9],6,$row[9],'0',0,'R',0);		
			$this->Ln();
				
		}
		unset($style);
		unset($w);
	}
	
	
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

           //////////  F U N C I O N E S    P A R A    E L    A C U M U L A D O    P O R    C U E N T A S  //////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------

	public function uf_print_cabecera_acumulado()
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera_acumulado 
	//		    Acess: private 
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. María Beatriz Unda
	// Fecha Creación: 13/08/08 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$w=array(22,91,22,23,23,23,24,23,27,23,23,22);
	$this->SetFont("helvetica","B",7);
	$style1 = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "0", "color" => array(0, 0, 0));	
			
	$header=array('Cuenta','Denominacion','Asignado','Aumento','Disminucion','Monto Actualizado','Pre Comprometido','Comprometido','Saldo por Comprometer','Causado','Pagado','Por Pagar');
	for($i=0;$i<count($header);$i++)
	{
		if ($i==0)
		{
			$this->MultiCell($w[$i],6,$header[$i],0,'C', 0, 0, 0 ,0, true, 0);
			
		}
		elseif ($i==1)
		{
			$this->MultiCell($w[$i],6,$header[$i],0,'L', 0, 0, 0 ,0, true, 0);
		}
		else
		{
			$this->MultiCell($w[$i],6,$header[$i],0,'R', 0, 0, 0 ,0, true, 0);
		}
	}
	
	$this->Line(349, $this->GetY()+8, 8, $this->GetY()+8, $style1);
	$this->Ln();
	$this->Ln();
	unset($style1);
	unset($w);
}

//--------------------------------------------------------------------------------------------------------------------------------

	public function uf_print_detalle_acumulado($la_data)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle_acumulado
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$fill=0;
	$w=array(22,91,22,23,23,23,24,23,27,23,23,22);
	
	$y=0;
	
	foreach($la_data as $row) 
	{
		$this->SetFont("helvetica","",7);
		if ($y!=0)
		{
			$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,$y, true, 0,true);		
		}
		else
		{
			$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,0, true, 0,true);		
		}
		$this->MultiCell($w[1],6,strtolower($row[1]),0,'L', 0, 0, 0 ,0, true, 0);
		$y=($this->getY() + 8);		
		$this->MultiCell($w[2],6,$row[2],0,'R', 0, 0, 0 ,0, true, 0,true);
		$this->MultiCell($w[3],6,$row[3],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[4],6,$row[4],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[5],6,$row[5],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[6],6,$row[6],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[7],6,$row[7],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[8],6,$row[8],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[9],6,$row[9],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[10],6,$row[10],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[10],6,$row[11],0,'R', 0, 0, 0 ,0, true, 0);	
		$this->Ln();		
	}
}


	public function uf_print_detalle_acumulado2($la_data)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle_acumulado
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$fill=0;
	$w=array(22,91,22,23,23,23,24,23,27,23,23,22);
	
	$y=0;
	
	foreach($la_data as $row) 
	{
		$this->SetFont("helvetica","",8);
		if ($y!=0)
		{
			
			if($row[12]=='S')
			{
				$this->SetFont("helvetica","B",8);
			}
			else
			{
				$this->SetFont("helvetica","",8);
			}
		
			$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,$y, true, 0,true);		
		}
		else
		{
			if($row[12]=='S')
			{
				$this->SetFont("helvetica","B",8);
			}
			else
			{
				$this->SetFont("helvetica","",8);
			}
			$this->MultiCell($w[0],6,$row[0],0,'C', 0, 0, 0 ,0, true, 0,true);		
		}
		$this->SetFont("helvetica","",7);
		$this->MultiCell($w[1],6,strtolower($row[1]),0,'L', 0, 0, 0 ,0, true, 0);
		$y=($this->getY() + 8);		
		$this->MultiCell($w[2],6,$row[2],0,'R', 0, 0, 0 ,0, true, 0,true);
		$this->MultiCell($w[3],6,$row[3],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[4],6,$row[4],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[5],6,$row[5],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[6],6,$row[6],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[7],6,$row[7],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[8],6,$row[8],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[9],6,$row[9],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[10],6,$row[10],0,'R', 0, 0, 0 ,0, true, 0);
		$this->MultiCell($w[10],6,$row[11],0,'R', 0, 0, 0 ,0, true, 0);	
		$this->Ln();		
	}
}




//--------------------------------------------------------------------------------------------------------------------------------

	public	function uf_print_total_acumulado($la_data)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_total_acumulado
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->SetFont("helvetica","B",7);
		$w=array(113,22,23,23,23,24,23,27,23,23,22);
		$style = array("width" => 0.1, "cap" => "round", "join" => "round", "dash" => "2,10", "color" => array(0, 0, 0));
		$this->Line(347, $this->GetY(), 108, $this->GetY(), $style);
		foreach($la_data as $row) 
		{
			$this->Cell($w[0],6,$row[0],0,0,'R',0);
			$this->Cell($w[1],6,$row[1],0,0,'R',0);
			$this->Cell($w[2],6,$row[2],0,0,'R',0);
			$this->Cell($w[3],6,$row[3],0,0,'R',0);
			$this->Cell($w[4],6,$row[4],0,0,'R',0);
			$this->Cell($w[5],6,$row[5],0,0,'R',0);
			$this->Cell($w[6],6,$row[6],0,0,'R',0);
			$this->Cell($w[7],6,$row[7],0,0,'R',0);
			$this->Cell($w[8],6,$row[8],0,0,'R',0);
			$this->Cell($w[9],6,$row[9],0,0,'R',0);
			$this->Cell($w[9],6,$row[10],0,0,'R',0);
			$this->Ln();
				
		}
		unset($w);
		unset($style);
	}

//--------------------------------------------------------------------------------------------------------------------------------

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
}

?>