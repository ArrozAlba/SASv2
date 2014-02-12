<?
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sev_c_pdf
 // Autor:       - Ing. Edgar Pastrán
 // Descripcion: - Clase que ayuda a la construccion de objetos pdf.
 // Fecha:       - 03/06/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
 include ('../../shared/class_folder/ezpdf/class.ezpdf.php');
 
//define("px_entre_mm",(612/216)); 
define("px_entre_mm",(72/25.4));  

class class_pdf extends Cezpdf
{
  var $ancho_pagina     = 612.00;
  var $alto_pagina      = 792.00;
  var $margen_superior  = 30;
  var $margen_inferior  = 30;
  var $margen_izquierdo = 30;
  var $margen_derecho   = 30;

  function class_pdf($pagina='LETTER',$orientacion='portrait')
  {
    $la_dimensiones = array('4A0'=>array(4767.87,6740.79),
	                        '2A0'=>array(3370.39,4767.87),
							'A0'=>array(2383.94,3370.39),
							'A1'=>array(1683.78,2383.94),
							'A2'=>array(1190.55,1683.78),
							'A3'=>array(841.89,1190.55),
							'A4'=>array(595.28,841.89),
							'A5'=>array(419.53,595.28),
							'A6'=>array(297.64,419.53),
							'A7'=>array(209.76,297.64),
							'A8'=>array(147.40,209.76),
							'A9'=>array(104.88,147.40),
							'A10'=>array(73.70,104.88),
							'B0'=>array(2834.65,4008.19),
							'B1'=>array(2004.09,2834.65),
							'B2'=>array(1417.32,2004.09),
							'B3'=>array(1000.63,1417.32),
							'B4'=>array(708.66,1000.63),
							'B5'=>array(498.90,708.66),
							'B6'=>array(354.33,498.90),
							'B7'=>array(249.45,354.33),
							'B8'=>array(175.75,249.45),
							'B9'=>array(124.72,175.75),
							'B10'=>array(87.87,124.72),
							'C0'=>array(2599.37,3676.54),
							'C1'=>array(1836.85,2599.37),
							'C2'=>array(1298.27,1836.85),
							'C3'=>array(918.43,1298.27),
							'C4'=>array(649.13,918.43),
							'C5'=>array(459.21,649.13),
							'C6'=>array(323.15,459.21),
							'C7'=>array(229.61,323.15),
							'C8'=>array(161.57,229.61),
							'C9'=>array(113.39,161.57),
							'C10'=>array(79.37,113.39),
							'RA0'=>array(2437.80,3458.27),
							'RA1'=>array(1729.13,2437.80),
							'RA2'=>array(1218.90,1729.13),
							'RA3'=>array(864.57,1218.90),
							'RA4'=>array(609.45,864.57),
							'SRA0'=>array(2551.18,3628.35),
							'SRA1'=>array(1814.17,2551.18),
							'SRA2'=>array(1275.59,1814.17),
							'SRA3'=>array(907.09,1275.59),
							'SRA4'=>array(637.80,907.09),
							'LETTER'=>array(612.00,792.00),
							'LEGAL'=>array(612.00,1008.00),
							'EXECUTIVE'=>array(521.86,756.00),
							'FOLIO'=>array(612.00,936.00));
	if (!array_key_exists($pagina,$la_dimensiones))
	{
	  $pagina = 'LETTER';
	};
	if ($orientacion == 'landscape')
	{
	  $this->ancho_pagina = $la_dimensiones[$pagina][1];
	  $this->alto_pagina  = $la_dimensiones[$pagina][0];
	}
	else
	{
	  $this->ancho_pagina = $la_dimensiones[$pagina][0];
	  $this->alto_pagina  = $la_dimensiones[$pagina][1];	    
	}	
    $this->Cezpdf($pagina,$orientacion);    
  }

  function convertir_coordenadas_mm_px(&$coord_x,&$coord_y,$ancho=0)
  {
    if ($coord_x == 'left')
    {$coord_x = $this->margen_izquierdo;}
    elseif ($coord_x == 'right')
    {$coord_x = $this->ancho_pagina - $this->margen_derecho-$ancho;}
    elseif ($coord_x == 'center')
    {$coord_x = (($this->ancho_pagina - $this->margen_derecho - $this->margen_izquierdo)/2) + $this->margen_izquierdo - ($ancho/2);}
    else
    {$coord_x = ($coord_x * px_entre_mm)+ $this->margen_izquierdo;}    
	$coord_y = $this->alto_pagina-$this->margen_superior-($coord_y * px_entre_mm);
  }

  function convertir_valor_mm_px(&$valor)
  {
     $valor = $valor *  px_entre_mm;
  }
   
  function convertir_valor_px_mm(&$valor)
  {
     $valor = $valor / px_entre_mm;
  }
  
  function convertir_colores_rgb(&$arreglo)
  {
	for($i=0;$i<count($arreglo);$i++)
	{
	  $arreglo[$i] = $arreglo[$i]/255;
	}    
  }
  
  function contar_lineas($cadena,$ancho_col,$tamano_texto)
  {
    $la_palabras = explode(" ",$cadena);
	$lineas      = 1;
	$this->convertir_valor_mm_px($ancho_col);
    $ancho_col = $ancho_col - 10;
	$ancho_disponible = $ancho_col;
	for ($n=0; $n<count($la_palabras); $n++)
	{
	  if ($this->getTextWidth($tamano_texto,$la_palabras[$n]) <= $ancho_disponible)
	  {
	    $ancho_disponible = $ancho_disponible - $this->getTextWidth($tamano_texto,$la_palabras[$n]);
		if ($n < (count($la_palabras)-1))
		{
		  $ancho_disponible = $ancho_disponible - $this->getTextWidth($tamano_texto," ");
		}
	  }
	  else
	  {
	    $la_semipalabras = explode("-",$la_palabras[$n]);
		if (count($la_semipalabras) > 1)
		{
		  for ($m=0; $m<count($la_semipalabras); $m++)
		  {
		    if ($m < (count($la_semipalabras)-1))
			{
			  if (($this->getTextWidth($tamano_texto,$la_semipalabras[$m])+$this->getTextWidth($tamano_texto,"-")) <= $ancho_disponible)
			  {
			    $lineas++;				         
				$ancho_disponible = $ancho_col;
			  }
			  else
			  {
			    $lineas++;
			    $lineas = $lineas + floor($this->getTextWidth($tamano_texto,$la_semipalabras[$m])/$ancho_col);
			    $ancho_disponible = ((1 - (($this->getTextWidth($tamano_texto,$la_semipalabras[$m])/$ancho_col)-floor($this->getTextWidth($tamano_texto,$la_semipalabras[$m])/$ancho_col))) * $ancho_col);				    
					    
			  }
			}
			else
			{
			  if ($this->getTextWidth($tamano_texto,$la_semipalabras[$m]) <= $ancho_disponible)
			  {
				$ancho_disponible = $ancho_disponible - $this->getTextWidth($tamano_texto,$la_semipalabras[$m]);
			  }
			  else
			  {
			    $lineas++;
			    $lineas = $lineas + floor($this->getTextWidth($tamano_texto,$la_semipalabras[$m])/$ancho_col);
			    $ancho_disponible = ((1 - (($this->getTextWidth($tamano_texto,$la_semipalabras[$m])/$ancho_col)-floor($this->getTextWidth($tamano_texto,$la_semipalabras[$m])/$ancho_col))) * $ancho_col);
			  }					  
			}
		  }
		}
		else
		{
		  $lineas++;
		  $lineas = $lineas + floor($this->getTextWidth($tamano_texto,$la_palabras[$n])/$ancho_col);
			      $ancho_disponible = ((1 - (($this->getTextWidth($tamano_texto,$la_palabras[$n])/$ancho_col)-floor($this->getTextWidth($tamano_texto,$la_palabras[$n])/$ancho_col))) * $ancho_col) - $this->getTextWidth($tamano_texto," ");				    
		}
	  }
	}
	return $lineas;    
  }
  
  function alinear_columnas($tamano_texto,$ancho1,$ancho2,&$cadena1,&$cadena2)
  {
    $la_cadena1 = explode("\n",$cadena1);
    $la_cadena2 = explode("\n",$cadena2);
    $li_filas = count($la_cadena1);
    for($i=0; $i < $li_filas; $i++)
    {
	   $largo1 = $this->contar_lineas($la_cadena1[$i],$ancho1,$tamano_texto);
	   $largo2 = $this->contar_lineas($la_cadena2[$i],$ancho2,$tamano_texto);
	   if ($i < ($li_filas-1))
	   {
	     $la_cadena1[$i] = $la_cadena1[$i]."\n";
	     $la_cadena2[$i] = $la_cadena2[$i]."\n";
	   };
	   if ($largo1 > $largo2)
	   {
	     $faltante="";
	     for ($j=1; $j<= ($largo1-$largo2); $j++)
	     {$faltante = $faltante."\n";}
	     $la_cadena2[$i] = $la_cadena2[$i].$faltante;
	   }
	   elseif ($largo2 > $largo1)
	   {
	     $faltante="";
	     for ($j=1; $j<= ($largo2-$largo1); $j++)
	     {$faltante = $faltante."\n";}
	     $la_cadena1[$i] = $la_cadena1[$i].$faltante;	     
	   }
	}
	$cadena1 = implode("",$la_cadena1);
	$cadena2 = implode("",$la_cadena2);
  }

  function obtener_lineas_por_fila($tamano_texto,$la_anchos_col,$la_datos)
  {
    // Arreglo que contiene las columnas que pueden determinar la cantidad de lineas de una fila (para no hacer comparaciones innecesarias)
    $la_indices_claves = array("denproy","desobr","nomempfav","nomfunres","telfunres","cauvar","fecpar","obreje","obreneje","impsoceco","obsproy");
    $la_lineas  = array();
    $filas = count($la_datos);
    if ($filas > 0)
    {
      $la_indices = array_keys($la_datos[0]);
      for ($i=0; $i<$filas; $i++)
      {
        $max = 1;
        for($j=0; $j<count($la_indices); $j++)
        {
          $indice = $la_indices[$j];
          if (array_search($indice,$la_indices_claves) !== false)
          {
		    $la_cadena = explode("\n",$la_datos[$i][$indice]);
		  
		    $lineas = 0;
		    for($x=0; $x<count($la_cadena); $x++)
		    {
		      $w = $this->contar_lineas($la_cadena[$x],$la_anchos_col[$j],$tamano_texto);
		      $lineas = $lineas + $w;
		    }
		    if ($lineas > $max)
		    {
		      $max = $lineas;
		    };
		  };
		}
		$la_lineas[$i] = $max;
	  }	  
	};
	return $la_lineas;
  }
  
  function get_alto_fila($tamano_texto,$filas,$unidad='mm')
  {
    $alto = ($this->getFontHeight($tamano_texto) * $filas) + 4;
    if ($unidad == 'mm')
    {$alto = $alto * (1/px_entre_mm);}
    return $alto;
  }
  
  function get_alto_disponible()
  {
	$li_alto_disponible = $this->y;
	$this->convertir_valor_px_mm($li_alto_disponible);
	$li_margen_inferior = $this->margen_inferior;
	$this->convertir_valor_px_mm($li_margen_inferior);
	$li_alto_disponible = $li_alto_disponible - $li_margen_inferior;
	return $li_alto_disponible;    
  }

  function get_alto_usado()
  {
    $li_alto_area_trabajo = $this->alto_pagina - $this->margen_superior - $this->margen_inferior;
	$this->convertir_valor_px_mm($li_alto_area_trabajo);    
	$li_alto_disponible = $this->get_alto_disponible();
	$li_alto_usado = $li_alto_area_trabajo - $li_alto_disponible;
	return $li_alto_usado;    
  }
  
  function get_ancho_area_trabajo()
  {
    $li_ancho_area_trabajo = $this->ancho_pagina - $this->margen_izquierdo - $this->margen_derecho;
	$this->convertir_valor_px_mm($li_ancho_area_trabajo);
	return $li_ancho_area_trabajo;
  }

  function set_margenes($superior,$inferior,$izquierdo,$derecho)
  {
     $this->margen_superior  = $superior*px_entre_mm;
     $this->margen_inferior  = $inferior*px_entre_mm;
     $this->margen_izquierdo = $izquierdo*px_entre_mm;
     $this->margen_derecho   = $derecho*px_entre_mm;
     $this->ezSetCmMargins($superior/10,$inferior/10,$izquierdo/10,$derecho/10);     
  }
  
  function numerar_paginas($tamano_letra)
  {
    $coord_y = $this->ancho_pagina/2;
    $coord_x = $this->margen_inferior*3/4;
    $this->ezStartPageNumbers($coord_y,$coord_x,$tamano_letra);
  }
  
  function add_rectangulo($coord_x,$coord_y,$ancho,$alto,$color)
  {
    $this->convertir_coordenadas_mm_px($coord_x,$coord_y);
    $this->convertir_valor_mm_px($ancho);
    $this->convertir_valor_mm_px($alto);
    $this->convertir_colores_rgb($color);
    $this->setColor($color[0],$color[1],$color[2]);
    $this->filledrectangle($coord_x,$coord_y,$ancho,$alto);    
    $this->setColor(0,0,0);
    $this->rectangle($coord_x,$coord_y,$ancho,$alto);
  }
   
  function add_linea($coord_x1,$coord_y1,$coord_x2,$coord_y2,$ancho_linea=1)
  {    
    $this->convertir_coordenadas_mm_px($coord_x1,$coord_y1);
    $this->convertir_coordenadas_mm_px($coord_x2,$coord_y2);
    $this->setLineStyle($ancho_linea);
    $this->line($coord_x1,$coord_y1,$coord_x2,$coord_y2);
  }
   
  function add_lineas($num_lineas)
  {
    $string = "";
    for($i=1;$i<=$num_lineas;$i++)
    {$string = $string."\n";}
    $this->ezText($string);
  }
  
  function add_imagen($archivo,$coord_x,$coord_y,$ancho_imagen)
  {
    $ancho_imagen = $ancho_imagen * px_entre_mm;
    $this->convertir_coordenadas_mm_px($coord_x,$coord_y,$ancho_imagen);    
    $extension = strtolower(substr(strrchr($archivo,"."),1));
    $longitud_extension = strlen($extension);
    if ($longitud_extension > 0)
    {
      if (!file_exists($archivo))
	  {
        return;
	  };
      $tmp= getimagesize($archivo);
      $ancho_img = $tmp[0];   
      $alto_img  = $tmp[1];
      $h = $ancho_imagen * ($alto_img/$ancho_img);
      $coord_y = $coord_y-$h;
      if (($extension == 'jpg')||($extension='jpeg'))
      {
        $this->addJpegFromFile($archivo,$coord_x,$coord_y,$ancho_imagen);
      }
      elseif ($extension == 'png')
      {
	    $this->addPngFromFile($archivo,$coord_x,$coord_y,$ancho_imagen); 
	  }
    }
    else
    {
      $ancho_img = imagesx($archivo);   
	  $alto_img  = imagesy($archivo);
	  $h = $ancho_imagen * ($alto_img/$ancho_img);
      $coord_y = $coord_y-$h;
      $this->addImage($archivo,$coord_x,$coord_y,$ancho_imagen,0,300);
      imagedestroy($archivo);
	}
  }
  
  function add_texto($coord_x,$coord_y,$tamano,$texto)
  { 
    $coord_x_aux = $coord_x; 
    $this->convertir_coordenadas_mm_px($coord_x_aux,$coord_y,$this->getTextWidth($tamano,$texto));  
    $lineas = explode("\n",$texto);
    for ($i=0; $i<count($lineas); $i++)
    {
      $coord_x_aux = $coord_x;
      $this->convertir_coordenadas_mm_px($coord_x_aux,$coord_y2,$this->getTextWidth($tamano,$lineas[$i]));
	  $this->addText($coord_x_aux,$coord_y-($tamano*($i+1)),$tamano,$lineas[$i]);	  
	};	
  }
  
  function add_tabla($coord_x,$datos,$opciones)
  {
    if (!is_array($opciones))
    {$opciones = array();}
    
    $columnas = array();
    // Vemos si es un arreglo bidimensional o no
    if (count($datos,COUNT_RECURSIVE) == count($datos))
    {
      // es unidimensional y lo convertimos en bidimensional
      $la_datos = array();
	  for($i=1;$i<=count($datos);$i++)
	  {
	    $la_datos[0]["titulo".$i]= $datos[$i-1];
	    $columnas[$i-1]="titulo".$i;
	  }
	}
	else
	{
	  $la_datos = $datos;
	  $columnas = array_keys($datos[0]);
	}	
	
	//Chequeamos las opciones
	//Color de fondo de las celdas
	if (array_key_exists("color_fondo",$opciones))
	{$color_fondo=$opciones["color_fondo"];}
	else
	{$color_fondo=array(255,255,255);}
	$this->convertir_colores_rgb($color_fondo);
	
	//Color del Texto en las celdas
	if (array_key_exists("color_texto",$opciones))
	{$color_texto=$opciones["color_texto"];}
	else
	{$color_texto=array(0,0,0);}
	$this->convertir_colores_rgb($color_texto);
	
	//Tamaño del Texto en las celdas
	if (array_key_exists("tamano_texto",$opciones))
	{$tamano_texto=$opciones["tamano_texto"];}
	else
	{$tamano_texto=10;}
	
	//Alineacion del texto en las celdas
	if (array_key_exists("alineacion_col",$opciones))
	{$alineacion_col=$opciones["alineacion_col"];}
	else
	{
	  $alineacion_col=array();
	  for ($i=0; $i<count($columnas);$i++)
	  {
	    $alineacion_col[$i] = 'center';
	  }
	}
	
	//Mostrar lineas de las celdas
	if (array_key_exists("lineas",$opciones))
	{$lineas=$opciones["lineas"];}
	else
	{$lineas=2;}
	
	//Anchos de las columnas
	$anchos_col = array();
	if (array_key_exists("anchos_col",$opciones))
	{
	  $anchos_col = $opciones["anchos_col"];
	  for ($i=0; $i<count($anchos_col); $i++)
	  {
	    $this->convertir_valor_mm_px($anchos_col[$i]);
	  }
	};
	
	//Atributos de las columnas
	$cols = array();
	for ($i=0; $i<count($columnas);$i++)
	{
	  $cols[$columnas[$i]] ['justification'] = $alineacion_col[$i];
	  if (count($anchos_col) > 0)
	  {
	    $cols[$columnas[$i]] ['width'] = $anchos_col[$i]; 
	  };
	}
	
	$max_ancho = $this->ancho_pagina - $this->margen_izquierdo - $this->margen_derecho;
	
	if ($coord_x === 'center')
	{$orientacion = 'center';}
	elseif ($coord_x === 'right')
	{$orientacion = 'left';}
	else
	{
	  $orientacion = 'right';
	  if ($coord_x !== 'left')
	  {$this->convertir_coordenadas_mm_px($coord_x,$coord_y);}	  
	}
	$this->ezTable($la_datos,"","",
	               array('shadeCol2'=>$color_fondo,'shadeCol'=>$color_fondo,
				         'shaded'=>2,'showHeadings'=>0,
				         'showLines'=>$lineas,
	                     'textCol'=>$color_texto,'fontSize'=>$tamano_texto,
				         'xPos'=>$coord_x,'xOrientation'=>$orientacion,
						 'maxWidth'=>$max_ancho,
						 'colGap'=>5,'rowGap'=>2,'gap'=>20,
						 'cols'=>$cols));
  }
  
}
?>