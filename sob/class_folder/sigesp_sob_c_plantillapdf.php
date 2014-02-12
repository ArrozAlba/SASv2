<?PHP
class sigesp_sob_c_plantillapdf
{
	var $li_anchotabla;//ancho maximo de la tabla de detalles del reporte
	var $io_pdf;//objeto de la clase ezpdf
	var $ls_tipopagina;//LETTER,LEGAL....
	var $la_data;//arreglo con TODA la data a ser mostrada
	var $la_datacabecera;//arreglo con la data de la cabecera
	var $la_datadetalle;//arreglo con la data del detalle
	var $li_filasdatacabecera;//cantidad de registros a ser mostrados en la cabecera
	var $li_filasdatadetalle;//cantidad de registros a ser mostrados en el detalle
	var $la_tituloscabecera;//Arreglo con los titulos de los campos a ser mostrados en la cabecera
	var $la_titulosdetalle;//Arreglo con los titulos de los campos a ser mostrados en el detalle
	var $li_filastituloscabecera;//cantidad de columnas a ser mostradas en la cabecera 
	var $li_filastitulosdetalle;//cantidad de columnas a ser mostradas en el detalle
	var $li_filasdata;//cantidad de registros en el arreglo de data
	var $ls_imagen;//ruta de la imagen a mostrar en el encabezado
	var $li_anchoimagen;//ancho de la imagen a mostrar
	var $li_altoimagen;//alto de la imagen a mostrar
	var $ls_tituloencabezado;//Titulo del reporte
	var $li_anchopagina;//ancho de la pagina en px
	var $li_altopagina;//alto de la pagina en px
	var $li_margenderecho;//margen derecho en cm
	var $li_margenizquierdo;//margen izq en cm
	var $li_margensuperior;//margen superior en cm
	var $li_margeninferior;//margen inferior en cm
	var $li_conversion;//factor de conversion de cm a px
	var $ls_fechadesde;//fecha de inicio del reporte
	var $ls_fechahasta;//fecha de finalizacion del reporte
	var $io_imagen;//objeto imagen;
	
	
	function sigesp_sob_c_plantillapdf($aa_data,$aa_tituloscabecera,$aa_titulosdetalle,$as_tituloencabezado,$as_fechadesde,$as_fechahasta,$as_imagen="",$ai_anchoimagen=0,$ai_altoimagen=0,
										$ai_margenderecho,$ai_margenizquierdo,$ai_margensuperior,
										$ai_margeninferior,$as_orientacion="landscape")
	{
		////////////////////////////////////////////////////////
		//       Function: sigesp_sob_c_plantillapdf
		//		    Acess: private 
		//    Description: Constructor de la clase
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 02/06/2006 
		////////////////////////////////////////////////////////
		
		require_once("../shared/ezpdf/class.ezpdf.php");
		require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
		$this->io_funsob=new sigesp_sob_c_funciones_sob();
		$this->la_tituloscabecera=$aa_tituloscabecera;//Arreglo con los titulos de las columnas de la cabecera
		$this->la_data=$aa_data;//arreglo con la data a ser mostrada en el reporte	
		$this->la_titulosdetalle=$aa_titulosdetalle;//Arreglo con los titulos de las columnas del detalle
		$this->li_filasdata=(count($aa_data, COUNT_RECURSIVE) / count($aa_data)) - 1;//cantidad de registros a ser mostrados
		if($aa_tituloscabecera!="")
			$this->li_filastituloscabecera=(count($aa_tituloscabecera, COUNT_RECURSIVE) / count($aa_tituloscabecera)) - 1;	//cantidad de columnas del reporte (cabecera)
		$this->li_filastitulosdetalle=(count($aa_titulosdetalle, COUNT_RECURSIVE) / count($aa_titulosdetalle)) - 1;	//cantidad de columnas del reporte (detalle)*/
		$this->ls_tituloencabezado=$as_tituloencabezado;//Titulo del reporte
		$this->ls_fechadesde=$as_fechadesde;//Inicio del rango de fecha del reporte
		$this->ls_fechahasta=$as_fechahasta;//Finalizacion del rango de fechas del reporte
		$this->ls_imagen=$as_imagen;//Ruta de la imagen del reporte (logo), por defecto vacio
		$this->li_margenderecho=$ai_margenderecho;//en cm
		$this->li_margenizquierdo=$ai_margenizquierdo;//en cm
		$this->li_margensuperior=$ai_margensuperior;//en cm
		$this->li_margeninferior=$ai_margeninferior;//en cm
		$this->li_conversion=612/216;//factor de conversion de cm a pixels
		if($this->li_filastitulosdetalle<8)//Dependiendo de la cantidad de columnas a mostrar se determina el tipo de pagina
		{
			$this->io_pdf=new Cezpdf('LETTER',$as_orientacion);	
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina=612;//ancho en pixel de la pagina tipo CARTA 
				$this->li_altopagina=792;//alto ' ' ''     ''       ''          ''			
			}
			else
			{
				$this->li_anchopagina=792;//ancho en pixel de la pagina tipo CARTA 
				$this->li_altopagina=612;//alto ' ' ''     ''       ''          ''			
			}	
			
		}
		elseif($this->li_filastitulosdetalle>=8 && $this->li_filastitulosdetalle<=10)
		{
			$this->io_pdf=new Cezpdf('LEGAL',$as_orientacion);
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina=612;
				$this->li_altopagina=1008;
			}
			else
			{
				$this->li_anchopagina=1008;
				$this->li_altopagina=612;
			}
			
		}
		elseif($this->li_filastitulosdetalle>=11 && $this->li_filastitulosdetalle<=13)
		{
			$this->io_pdf=new Cezpdf('SRA3',$as_orientacion);
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina=907.09;
				$this->li_altopagina=1275.59;
			}
			else
			{
				$this->li_anchopagina=1275.59;
				$this->li_altopagina=907.09;
			}
		}
		elseif($this->li_filastitulosdetalle>=14 && $this->li_filastitulosdetalle<=17)
		{
			$this->io_pdf=new Cezpdf('A2',$as_orientacion);
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina= 1190.55;
				$this->li_altopagina=1683.78;
			}
			else
			{
				$this->li_anchopagina=1683.78 ;
				$this->li_altopagina=1190.55;
			}
		}	
		elseif($this->li_filastitulosdetalle>=18 && $this->li_filastitulosdetalle<=25)
		{
			$this->io_pdf=new Cezpdf('A1',$as_orientacion);
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina= 1683.78;
				$this->li_altopagina=2383.94;
			}
			else
			{
				$this->li_anchopagina=2383.94;
				$this->li_altopagina=1683.78;
			}
		}
		elseif($this->li_filastitulosdetalle>=26 && $this->li_filastitulosdetalle<=30)
		{
			$this->io_pdf=new Cezpdf('A0',$as_orientacion);//'' 'A0' (,),
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina= 2383.94;
				$this->li_altopagina=3370.39;
			}
			else
			{
				$this->li_anchopagina=3370.39;
				$this->li_altopagina=2383.94;
			}
		}
		elseif($this->li_filastitulosdetalle>=31 /*&& $this->li_filastitulosdetalle<=30*/)
		{
			$this->io_pdf=new Cezpdf('2A0',$as_orientacion);//'2A0' (,),
			if($as_orientacion=='portrait')	
			{
				$this->li_anchopagina= 3370.39;
				$this->li_altopagina=4767.87;
			}
			else
			{
				$this->li_anchopagina=4767.87;
				$this->li_altopagina=3370.39;
			}
		}
		
				
		$this->li_anchotabla=$this->li_anchopagina-$this->li_margenderecho-$this->li_margenizquierdo-10;//El ancho de la tabla es proporcional al tamaño de la pagina		
		$this->li_anchoimagen=$ai_anchoimagen;//ancho de la imagen, por defecto 0
		$this->li_altoimagen=$ai_altoimagen;// alto    ''   ''      ''    ''    ''
		$this->io_pdf->selectFont('../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$this->io_pdf->ezSetMargins($this->li_margensuperior,$this->li_margeninferior,$this->li_margenizquierdo,$this->li_margenderecho); // Configuración de los margenes en centímetros		
	}
	
	function uf_print_encabezado_pagina()
	{
		/////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina								   //
		//		    Acess: private 													   //
		//    Description: función que imprime los encabezados por página			   //
		//	   Creado Por: Ing. Laura Cabre											   //		
		// Fecha Creación: 02/06/2006 												   //		
		/////////////////////////////////////////////////////////////////////////////////
		$this->io_imagen=$this->io_pdf->openObject();//la imagen se repetira en todas las paginas
		//$this->io_pdf->saveState();
		if ($this->ls_imagen!="")//si la ruta de la imagen es proporcionada, esta se imprime en cada pagina
		{
			$x=$this->li_margenizquierdo;
			$y=$this->li_altopagina-$this->li_altoimagen-10;
			$this->io_pdf->addJpegFromFile($this->ls_imagen,$x,$y,$this->li_anchoimagen,$this->li_altoimagen); // Agregar Logo en cada pagina			
		}	
		//$this->io_pdf->restoreState();
		$this->io_pdf->closeObject();
		$this->io_pdf->addObject($this->io_imagen,'all');				
		$li_tm=$this->io_pdf->getTextWidth(14,$this->ls_tituloencabezado);		
		$x=($this->li_anchopagina/2)-($li_tm/2);
		$y=$this->li_altopagina-$this->li_margensuperior-20;
		$this->io_pdf->addText($x,$y,14,"<b>".$this->ls_tituloencabezado."</b>"); // Agregar el título
		
		$ls_fecha="Desde ".$this->ls_fechadesde." hasta ".$this->ls_fechahasta;
		$li_tm=$this->io_pdf->getTextWidth(12,$ls_fecha);		
		$x=($this->li_anchopagina/2)-($li_tm/2);
		$y=$this->li_altopagina-$this->li_margensuperior-40;
		$this->io_pdf->addText($x,$y,12,$ls_fecha); // Agregar rango de Fechas
		
			
	}// end function uf_print_encabezadopagina
	//----------------------------------------------------------------------------------------------------------------------//
	function uf_print_cabecera()
	{		
		$li_filasdata=$this->li_filasdatacabecera;
		$li_filastituloscabecera=$this->li_filastituloscabecera;
		$li_index=0;
		for($li_titulos=1;$li_titulos<=$li_filastituloscabecera;$li_titulos++)//Se construye dinamicamente el arreglo de la data de la cabecera
		{																	  //dependiendo de los campos que fueron seleccionados por el usuario 
				$la_dataaux["titulo"]="<u><b>".$this->la_tituloscabecera["titulo"][$li_titulos].":</b></u> ";
				$la_dataaux["valor"]=$this->la_datacabecera[$this->la_tituloscabecera["campo"][$li_titulos]];
				$la_data[$li_index]=$la_dataaux;
				$li_index++;				
		}				
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
				 'showLines'=>0, // Mostrar Líneas
				 'shaded'=>0, // Sombra entre líneas
				 'shadeCol'=>array(0,0,0),
				 'shadeCol2'=>array(0,0,0),
				 'fontSize' => 12,
				 'titleFontSize' => 14,
				 'xPos'=>$this->li_margenizquierdo,
				 'xOrientation'=>'right');
				 $this->io_pdf->ezText('                     ',40);//espacio en blanco
				 $this->io_pdf->ezTable($la_data,'','',$la_config);//se imprime la tabla
				  	
	}
	
	//-----------------------------------------------------------------------------------------------------------------------//
	function uf_print_detalle($aa_titulodetalle="")
	{
		
		//$this->io_pdf->stopObject($this->io_imagen,'all');
		if($aa_titulodetalle!="")//Se imprime titulo si este no viene vacio
		{
			$this->io_pdf->ezText('                     ',15);//Inserto una linea en blanco*/	
			$this->io_pdf->ezText("<b>".$aa_titulodetalle."</b>",12,array('justification'=>"center"));
			$this->io_pdf->ezText('                     ',15);//Inserto una linea en blanco*/		 			
		}
		else
		{
			$this->io_pdf->ezText('                     ',60);//Inserto una linea en blanco*/		
		}
		$li_filasdata=$this->li_filasdatadetalle;
		$li_filastitulosdetalle=$this->li_filastitulosdetalle;
		for($li_data=1;$li_data<=$li_filasdata;$li_data++)//Arreglo con la data a mostrar dependiendo de los campos que selecciono el usuario
		{			
			for($li_titulos=1;$li_titulos<=$li_filastitulosdetalle;$li_titulos++)
			{
				$la_dataaux[$this->la_titulosdetalle["titulo"][$li_titulos]]=$this->la_datadetalle[$this->la_titulosdetalle["campo"][$li_titulos]][$li_data];
			}
			$la_data[$li_data-1]=$la_dataaux;			
		}		
		//print_r($this->la_datadetalle);
		
		for ($li_titulos=1;$li_titulos<=$li_filastitulosdetalle;$li_titulos++)//Titulos de las columnas, personalizadas de tal forma que tengan justificaciones
		{																	  //distintas dependiendo de la columna que se esta imprimiendo			
			$la_columna[$this->la_titulosdetalle["titulo"][$li_titulos]]="<b>".$this->la_titulosdetalle["titulo"][$li_titulos]."</b>";
			if($this->la_titulosdetalle["campo"][$li_titulos]=="monto" || 
				$this->la_titulosdetalle["campo"][$li_titulos]=="prepar" || 
				$this->la_titulosdetalle["campo"][$li_titulos]=="canparobr"||
				$this->la_titulosdetalle["campo"][$li_titulos]=="montoobra"||
				$this->la_titulosdetalle["campo"][$li_titulos]=="monmaxcon"||
				$this->la_titulosdetalle["campo"][$li_titulos]=="totalanticipo"
				)
				$la_cols[$this->la_titulosdetalle["titulo"][$li_titulos]]["justification"]="right";
			elseif($this->la_titulosdetalle["campo"][$li_titulos]=="desobr" || 
					$this->la_titulosdetalle["campo"][$li_titulos]=="nompar" 
					)
				$la_cols[$this->la_titulosdetalle["titulo"][$li_titulos]]["justification"]="left";				
			else
				$la_cols[$this->la_titulosdetalle["titulo"][$li_titulos]]["justification"]="center";
		}
		
		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
				 'showLines'=>2, // Mostrar Líneas
				 'shaded'=>1, // Sombra entre líneas
				 'shadeCol'=>array(0.941176,0.972549,1),
				 'fontSize' => 12,
				 'titleFontSize' => 14,
				  'xPos'=>'center',
				 'xOrientation'=>'center',
				 'width'=>$this->li_anchotabla, // Ancho de la tabla
				 'maxWidth'=>$this->li_anchotabla,
				 'cols'=>$la_cols);   	 		
					
			$this->io_pdf->ezTable($la_data,$la_columna,'',$la_config);	//se imprime la tabla.		
			//$this->io_pdf->addObject($this->io_imagen,'all');	
	}
	
	function uf_print_total(&$aa_cols="")
	{
		//print "entro"
		$li_filas=(count($aa_cols, COUNT_RECURSIVE) / count($aa_cols)) - 1;
		for($li_i=1;$li_i<=$li_filas;$li_i++)
		{
			$li_acumulador=0;
			for($li_j=1;$li_j<=$this->li_filasdatadetalle;$li_j++)
			{
				$li_acumulador=$li_acumulador+$this->io_funsob->uf_convertir_cadenanumero($this->la_datadetalle[$aa_cols["campo"][$li_i]][$li_j]);
			}	
			$aa_cols["acumulado"][$li_i]=$this->io_funsob->uf_convertir_numerocadena($li_acumulador);		
		}
		$li_index=0;
		for($li_i=1;$li_i<=$li_filas;$li_i++)
		{																	  
				$la_dataaux["titulo"]="<u><b>".$aa_cols["titulo"][$li_i]."</b></u>";
				$la_dataaux["valor"]=$aa_cols["acumulado"][$li_i]." Bs.";
				$la_data[$li_index]=$la_dataaux;
				$li_index++;				
		}				
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
				 'showLines'=>0, // Mostrar Líneas
				 'shaded'=>0, // Sombra entre líneas
				 'shadeCol'=>array(0,0,0),
				 'shadeCol2'=>array(0,0,0),
				 'fontSize' => 12,
				 'titleFontSize' => 14,
				 'xPos'=>'right',
				 'xOrientation'=>'left');
		$this->io_pdf->ezSetDy(-30);
		$this->io_pdf->ezTable($la_data,'','',$la_config);		
	}
}
?>