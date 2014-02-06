<?php
/*********************************************************************************************************
* @Clase que permite generar el archivo Xml para mostrar los datos del reporte.
* @Fecha de Creación: 04/2008
* @Autor: Johny Porras.
* @Última Modificación:	Modificar nombres de variables y funciones para que se rigan por los 
*                       estandares establecidos para la nueva versión.
*                		@Fecha: 19/05/08.
*						@Autor: Gusmary Balza. 
*********************************************************************************************************/

class crearreporte
{
	var $nomArXml=array(); //nombre de los archivos xml 
	var $cantArchivos=1;
	var $nomRep="";   //nombre del reporte diseñado
	

/***********************************************************************************
* @Función para crear un archivo XML a partir de un resultset.
* @parametros: nomArchivo, datos
* @retorno: true o false 
* @fecha de creación: 
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function  crearXml($nomArchivo,$datos) 
	{
		$this->nomArXml[$this->cantArchivos] = $nomArchivo;
		$this->getNombre();
		$dom = new DOMDocument(); //crear el documento DOM
		// create root element
		$root = $dom->createElement("registros"); 
		$dom->appendChild($root);  //agregar el elemento root al final de la lista  
		foreach ($datos as $Rg)
		{	
			$registro = $dom->createElement("registro");
			foreach ($Rg as $Indice =>$valor)
			{
				if (is_numeric($Indice))
				{
					continue;	
				}
				else
				{
					if ($valor!="" && $Indice!="_original")
					{
						$campo= $dom->createElement($Indice);
						$registro->appendChild($campo); 
						$text = $dom->createTextNode(utf8_encode($valor));
						$campo->appendChild($text);
					}
				}				
			}			
			$root->appendChild($registro);
		}		
		if ($dom->save("../../base/xml/reportes/$this->codsis/".$this->nomArXml[$this->cantArchivos]))
		{
			$this->cantArchivos++; 
			return true;		 
		}
		else
		{
			return false;
		}	
		
	}
	
	function  crearXml2($nomArchivo,$datos) //usada para reporte
	{
		
		$this->nomArXml[$this->cantArchivos] = $nomArchivo;
		$this->getNombre();
		$dom = new DOMDocument(); //crear el documento DOM
		// create root element
		$root = $dom->createElement("registros"); 
		$dom->appendChild($root);  //agregar el elemento root al final de la lista  
		while (!$datos->EOF)
		{	
			$registro = $dom->createElement("registro");
			
			foreach ($datos->fields as $Indice =>$valor)
			{
				if (is_numeric($Indice))
				{
					continue;	
				}
				else
				{
					if ($valor!="" && $Indice!="_original")
					{
						
						$campo= $dom->createElement($Indice);
						$registro->appendChild($campo); 
						$text = $dom->createTextNode(utf8_encode($valor));
						$campo->appendChild($text);
					}
				}
				
			}			
			$root->appendChild($registro);
			$datos->MoveNext();
		}		
		if ($dom->save("../../base/xml/reportes/$this->codsis/".$this->nomArXml[$this->cantArchivos]))
		{
			return true;
		 
		}
		else
		{
			return false;
		}	
		$this->cantArchivos++; 
	}
	
	
/***********************************************************************************
* @Función para crear un archivo XML a partir de un datastore.
* @parametros: nomArchivo, datos
* @retorno: 
* @fecha de creación:
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function  crearXmlAcumCuenta($nomArchivo,$datos) 
	{
		$this->nomArXml[$this->cantArchivos] = $nomArchivo;
		$this->getNombre();
		$dom = new DOMDocument();
		$root = $dom->createElement("registros");
		$dom->appendChild($root);
		for ($i=1; $i<=count($datos);$i++)
		{
			$registro = $dom->createElement("registro");
			foreach ($datos[$i] as $Indice=>$valor)
			{
				$campo= $dom->createElement($Indice);
				$registro->appendChild($campo);
				$text = $dom->createTextNode($valor);
				$campo->appendChild($text);
			}
				
			$root->appendChild($registro);
		}					
		if ($dom->save("../../xml/reportes/$this->codsis/".$this->nomArXml[$this->cantArchivos]))
		{
			return true;
		}
		else
		{
			return false;
		}
		$this->cantArchivos++;
	}
	
	
/***********************************************************************************
* @Función para crear un archivo XML a partir de un arreglo.
* @parametros: nomArchivo, datos
* @retorno: 
* @fecha de creación:
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function  crearXmlArr($nomArchivo,$datos) 
	{
		$this->nomArXml[$this->cantArchivos] = $nomArchivo;
		$this->getNombre();
		$dom = new DOMDocument();
		$root = $dom->createElement("registros");
		$dom->appendChild($root);
		$registro = $dom->createElement("registro");
		foreach ($Datos as $Indice=>$valor)
		{
			$campo= $dom->createElement($Indice);
			$registro->appendChild($campo);
			$text = $dom->createTextNode($valor);
			$campo->appendChild($text);
		}			
		$root->appendChild($registro);							
		if ($dom->save("../../xml/reportes/$this->codsis/".$this->nomArXml[$this->cantArchivos]))
		{
			return true;		 
		}
		else
		{
			return false;
		}
		$this->cantArchivos++;
	}
		
/***********************************************************************************
* @Función para eliminar un archivo XML luego de cierto tiempo de haberse generado.
* @parametros: nomArchivo, datos
* @retorno: 
* @fecha de creación:
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function  eliminarXml($directorio)
	{
		if (is_dir($directorio)) 
		{
		   if ($hdir = opendir($directorio)) 
		   {
		       while (($archivo = readdir($hdir)) !== false) 
		       {
					if (!is_dir($archivo)) 
			   		{
						$tiempo =  $this->calculartiempotrasnc(date("H:i"),date("H:i",filemtime("{$directorio}/".$archivo)));
						$Artiempo = explode(":",$tiempo);
						if ($Artiempo[0]!="" && $Artiempo[0]>=1)
						{
							unlink ("{$directorio}/".$archivo);
						}
			   		}
		       }
		       closedir($hdir);
		   }
		}
	}
	
	
/***********************************************************************************
* @Función para obtener el nombre de  un archivo XML.
* @parametros: 
* @retorno: 
* @fecha de creación:
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function getNombre()
	{
		if ($this->nomArXml[$this->cantArchivos])
		{
			$this->nomArXml[$this->cantArchivos] = $this->nomArXml[$this->cantArchivos].time().$_SERVER["REMOTE_ADDR"].".xml";
		}
	}
	
	
/***********************************************************************************
* @Función para mostrar el reporte a partir de un archivo XML.
* @parametros: 
* @retorno: ruta donde se encuentra el reporte
* @fecha de creación:
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function mostrarReporte()
	{
		$parametros = "";
		if(!$this->nomRep)
		{
			return "noreporte";
			break;
		}
		elseif(!$this->nomArXml)
		{
			return "noxml";
			break;
		}
		else
		{
			$server = "http://".$_SERVER["SERVER_ADDR"];
			$rutaXml ="{$server}:80/".$_SESSION['sigesp_sitioweb']."/base/xml/reportes/$this->codsis/";
			$rutaRep ="{$server}:8081/birt/frameset?__report=";
			for($i = 1;$i<=count($this->nomArXml);$i++)
			{
				$parametros.="&rutaarchivo{$i}={$rutaXml}{$this->nomArXml[$i]}";
			}
			$this->nomRep = $this->nomRep.".rptdesign{$parametros}";
			$rutaCompleta = "{$rutaRep}{$this->nomRep}";
			return $rutaCompleta;
		}
	}

	
/***********************************************************************************
* @Función para obtener el tiempo transcurrido
* @parametros: hora1, hora2
* @retorno: tiempo en horas
* @fecha de creación:
* @autor: Johny Porras.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function calculartiempotrasnc($hora1,$hora2)
	{ 
		$separar[1]=explode(":",$hora1); 
		$separar[2]=explode(":",$hora2); 
		$total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
		$total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
		$total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 
		if ($total_minutos_trasncurridos<=59) 
			return($total_minutos_trasncurridos." Minutos"); 
		elseif ($total_minutos_trasncurridos>59)
		{ 
			$HORA_TRANSCURRIDA = round($total_minutos_trasncurridos/60); 
			if ($HORA_TRANSCURRIDA<=9) 
				$HORA_TRANSCURRIDA=$HORA_TRANSCURRIDA; 
				$MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos%60; 
				if ($MINUITOS_TRANSCURRIDOS<=9)
					$MINUITOS_TRANSCURRIDOS=$MINUITOS_TRANSCURRIDOS; 
			return ($HORA_TRANSCURRIDA.":".$MINUITOS_TRANSCURRIDOS." Horas"); 
		}
	}
}
?>