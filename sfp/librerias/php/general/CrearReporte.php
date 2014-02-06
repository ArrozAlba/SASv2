<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//   Clase que permite generar el archivo Xml para mostrar los datos del reporte
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Class Reporte
{
	var $NomArXml=array(); //nombre de los archivos xml 
	var $CantArchivos=1;
	var $NomRep="";   //nombre del reporte diseñado
	var $Modulo=""; // Nombre del módulo para el cual se va  a hacer el reporte
	
	Function Reporte($Modulo="")
	{
		$this->Modulo = $Modulo;
	}
	
	Function  CrearXml($NomArchivo,$Datos) 
	{	
	
		$this->NomArXml[$this->CantArchivos] = $NomArchivo;
		$this->GetNombre();
		$dom = new DOMDocument(); //crear el documento DOM
		// create root element
		$root = $dom->createElement("registros"); 
		$dom->appendChild($root);  //agregar el elemento root al final de la lista  
		//echo $dom->save("order.xml");
		while($Rg = $Datos->FetchRow())
		{	
			
			//var_dump($Rg);
			//	die();
			$Registro = $dom->createElement("registro");
			foreach($Rg as $Indice=>$valor)
			{
					if(is_numeric($Indice))
					{
						continue;
					}
					else
					{
						$Campo= $dom->createElement($Indice);
						$Registro->appendChild($Campo); 
						$text = $dom->createTextNode(utf8_encode($valor));
						$Campo->appendChild($text);			
					}
			}
			$root->appendChild($Registro);
		}
		
		 if($dom->save("../xml/".$this->NomArXml[$this->CantArchivos]))
		 {
		 	$this->CantArchivos++; 
			return true;
		 
		 }
		 else
		 {
			return false;
		 }	
		
	}
	
	
	
//	Function  CrearXml($NomArchivo,$Datos) 
//	{
//	
//		$this->NomArXml[$this->CantArchivos] = $NomArchivo;
//		$this->GetNombre();
//		$dom = new DOMDocument(); //crear el documento DOM
//		// create root element
//		$root = $dom->createElement("registros"); 
//		$dom->appendChild($root);  //agregar el elemento root al final de la lista  
//		//echo $dom->save("order.xml");
//		foreach($Datos as $Rg)
//		{	
//			$Registro = $dom->createElement("registro");
//			foreach($Rg as $Indice =>$valor)
//			{
//				if(is_numeric($Indice))
//				{
//					continue;	
//				}
//				else
//				{
//					if($valor!="" && $Indice!="_original")
//					{
//						$Campo= $dom->createElement($Indice);
//						$Registro->appendChild($Campo); 
//						$text = $dom->createTextNode($valor);
//						$Campo->appendChild($text);
//					}
//				}
//				
//			}
//			
//			$root->appendChild($Registro);
//		}
//		
//		 if($dom->save("../../xml/".$this->NomArXml[$this->CantArchivos]))
//		 {
//			return true;
//		 
//		 }
//		 else
//		 {
//			return false;
//		 }	
//		$this->CantArchivos++; 
//	}
//
//	
	
	
	Function  CrearXmlAcumCuenta($NomArchivo,$Datos) //funcion para crear un documento xml a partir de los datastores
	{
		$this->NomArXml[$this->CantArchivos] = $NomArchivo;
		$this->GetNombre();
		$dom = new DOMDocument();
		// create root element
		$root = $dom->createElement("registros");
		$dom->appendChild($root);
		//echo $dom->save("order.xml");
			
			for($i=0;$i<count($Datos);$i++)
			{
				$Registro = $dom->createElement("registro");
				if(is_array($Datos[$i]->fields))
				{
					foreach($Datos[$i]->fields as $Indice=>$valor)
					{
						if(!is_numeric($Indice))
						{
							$Campo= $dom->createElement($Indice);
							$Registro->appendChild($Campo);
							$text = $dom->createTextNode(utf8_encode($valor));
							$Campo->appendChild($text);
						}
					}
				}
				$root->appendChild($Registro);
			}
					
		 if($dom->save("../xml/".$this->NomArXml[$this->CantArchivos]))
		 {
		 	$this->CantArchivos++;
			return true;
		 }
		 else
		 {
			return false;
		 }
	}
	
		Function  CrearXmlArr2($NomArchivo,$Datos) //funcion para crear un documento xml a partir de los datastores
		{
		$this->NomArXml[$this->CantArchivos] = $NomArchivo;
		$this->GetNombre();
		$dom = new DOMDocument();
		// create root element
		$root = $dom->createElement("registros");
		$dom->appendChild($root);
		//echo $dom->save("order.xml");
			
			for($i=0;$i<count($Datos);$i++)
			{
				$Registro = $dom->createElement("registro");
				foreach($Datos[$i] as $Indice=>$valor)
				{
				
					if(!is_numeric($Indice))
					{
				
					
						$Campo= $dom->createElement($Indice);
						$Registro->appendChild($Campo);
						$text = $dom->createTextNode(utf8_encode($valor));
						$Campo->appendChild($text);
					}
				}
				
				$root->appendChild($Registro);
			}
					
		 if($dom->save("../xml/".$this->NomArXml[$this->CantArchivos]))
		 {
		 	$this->CantArchivos++;
			return true;
		 }
		 else
		 {
			return false;
		 }
		 
	}
	
	
	
	Function  CrearXmlArr($NomArchivo,$Datos) //funcion para crear un xml desde un arreglo
	{
		$this->NomArXml[$this->CantArchivos] = $NomArchivo;
		$this->GetNombre();
		$dom = new DOMDocument();
		// create root element
		$root = $dom->createElement("registros");
		$dom->appendChild($root);
		//echo $dom->save("order.xml");
			
				$Registro = $dom->createElement("registro");
				foreach($Datos as $Indice=>$valor)
				{
				if(!is_numeric($Indice))
				{
					$Campo= $dom->createElement($Indice);
					$Registro->appendChild($Campo);
					$text = $dom->createTextNode(utf8_encode($valor));
					$Campo->appendChild($text);
				}
				}
				
				$root->appendChild($Registro);
							
		 if($dom->save("../xml/".$this->NomArXml[$this->CantArchivos]))
		 {
		 	$this->CantArchivos++;
			return true;		 
		 }
		 else
		 {
			return false;
		 }
	}
		
		
	Function  EliminarXml($directorio)
	{
		if (is_dir($directorio)) 
		{
		   if ($hdir = opendir($directorio)) 
		   {
		       while (($archivo = readdir($hdir)) !== false) 
		       {
			   if (!is_dir($archivo)) 
			   {
				$tiempo =  $this->calcular_tiempo_trasnc(date('H:i'),date('H:i',filemtime("{$directorio}/".$archivo)));
				$Artiempo = explode(":",$tiempo);
				if($Artiempo[0]!='' && $Artiempo[0]>=1)
				{
					unlink ("{$directorio}/".$archivo);
				}
				//echo filemtime("xml/".$archivo);
			   }
		       }
		       closedir($hdir);
		   }
		}
	}
	
	Function GetNombre()
	{
		if($this->NomArXml[$this->CantArchivos])
		{
			$this->NomArXml[$this->CantArchivos] = $this->NomArXml[$this->CantArchivos].time().$_SERVER['REMOTE_ADDR'].".xml";
		}
	}
	
	Function MostrarReporte()
	{
		$arArchivo = file("../puertos.txt");
		$parametros = "";
		if(!$this->NomRep)
		{
			return "noreporte";
			break;
		}
		elseif(!$this->NomArXml)
		{
			return "noxml";
			break;
		}
		else
		{
			$Server = "http://".$_SERVER['SERVER_ADDR'];
			$RutaXml ="{$Server}:{$arArchivo[0]}/{$arArchivo[2]}/sfp/xml/";
			$Rutarep ="{$Server}:{$arArchivo[1]}/birt/frameset?__report=";
			for($i = 1;$i<=count($this->NomArXml);$i++)
			{
				$parametros.="&rutaarchivo{$i}={$RutaXml}{$this->NomArXml[$i]}";
			}
			$this->NomRep = $this->NomRep.".rptdesign{$parametros}";
			$RutaCompleta = "{$Rutarep}{$this->NomRep}";
			return $RutaCompleta;
		}
	}


	 function calcular_tiempo_trasnc($hora1,$hora2)
	 { 
		    $separar[1]=explode(':',$hora1); 
		    $separar[2]=explode(':',$hora2); 
		    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
		    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
		    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 
		    if($total_minutos_trasncurridos<=59) return($total_minutos_trasncurridos.' Minutos'); 
		   elseif($total_minutos_trasncurridos>59)
		  { 
			$HORA_TRANSCURRIDA = round($total_minutos_trasncurridos/60); 
			if($HORA_TRANSCURRIDA<=9) $HORA_TRANSCURRIDA=$HORA_TRANSCURRIDA; 
			$MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos%60; 
			if($MINUITOS_TRANSCURRIDOS<=9) $MINUITOS_TRANSCURRIDOS=$MINUITOS_TRANSCURRIDOS; 
			return ($HORA_TRANSCURRIDA.':'.$MINUITOS_TRANSCURRIDOS.' Horas'); 

		 }

	}
}

$oReporte= new Reporte('mcd');


?>