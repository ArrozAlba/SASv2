<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - class_graficas
 // Autor:       - Ing. Edgar Pastrán
 // Descripcion: - Clase que dibuja una grafica de torta y devuelve una imagen de la torta
 // Fecha:       - 01/06/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class class_graficas
{
  /*************************
          ATRIBUTOS
  **************************/
  var $ancho_img   = 580; //Ancho de la imagen  
  var $alto_img    = 240; //Alto de la Imagen
  var $imagen;            //Objeto que contiene laImagen
  var $color_fondo;       //Color a usar de fondo de la grafica
  var $color_fuente;      //Color a usar en los textos de la grafica
  var $io_funnum;         //Objeto usado para mostrar las cantidades en formato correcto
  
  /*************************
          FUNCIONES
  **************************/
//////////////////////////////////////////////////////////////////////////////////////
//                                CONSTRUCTOR
//////////////////////////////////////////////////////////////////////////////////////    
  function class_graficas()
  {
    //require_once("../class_folder/class_funciones_numericas_sev.php");
    //$this->io_funnum = new class_funciones_numericas_sev();
  }
//////////////////////////////////////////////////////////////////////////////////////

  
//////////////////////////////////////////////////////////////////////////////////////
//                 FUNCION QUE CREA LA IMAGEN Y LOS COLORES A USAR
//////////////////////////////////////////////////////////////////////////////////////    
  function inicializar()
  {
	//Creamos la imagen
    $this->imagen = imagecreate($this->ancho_img,$this->alto_img);
    	
	//Asignamos un color al fondo y a  las letras  a usar en la grafica
	$this->color_fondo  = imagecolorallocate($this->imagen,255,255,255);// blanco
	$this->color_fuente = imagecolorallocate($this->imagen,0,0,0);      // negro
	
	//Colocamos el color de fondo
	imagefilledrectangle($this->imagen,0,0,$this->ancho_img,$this->alto_img,$this->color_fondo);	
  }
//////////////////////////////////////////////////////////////////////////////////////

  
//////////////////////////////////////////////////////////////////////////////////////
//                 FUNCIONES QUE DAN VALORES A LOS ATRIBUTOS
//////////////////////////////////////////////////////////////////////////////////////      
  function set_dimension_imagen($li_ancho,$li_alto)
  {
    $this->ancho_img = $li_ancho;
    $this->alto_img  = $li_alto;
  }
//////////////////////////////////////////////////////////////////////////////////////

  
//////////////////////////////////////////////////////////////////////////////////////
//                 FUNCION QUE REALIZA UNA GRAFICA DE TORTA
//////////////////////////////////////////////////////////////////////////////////////    
  function graficar_torta($la_renglones,$la_valores,$ab_3d='true')
  {
    
  $items = count($la_valores);
  if ($items <= 18)
  {
    //Convertimos los valores en un formato para realizar operaciones
    $total_valores = 0;
    for ($i=0; $i<$items; $i++)
    {
	  $la_valores_cadena[$i] = $la_valores[$i];
	  $total_valores += $la_valores[$i];
	  $la_valores[$i] = $this->ue_convertir_cadenanumero($la_valores[$i]);	  
	}
	
    // Calculamos las dimensiones adecuadas a la imagen
    // Calculamos el alto adecuado
    $this->set_dimension_imagen(580,240);
    $alto = $this->alto_img;
    if ($items >= 12)
    {
	  $alto = ($items * 20) + 20;
	};	
	// Calculamos el ancho adecuado
	$ancho_leyenda = 150;
	$ancho_grafica = 430;
	$adicion_mayor_cadena = 0;
	for($i = 0; $i < $items; $i++)
	{
	  if ((strlen($la_renglones[$i]) > 10) && (150+((strlen($la_renglones[$i])-10)*7) > $ancho_leyenda))
	  {
	    $ancho_leyenda = 150 + ((strlen($la_renglones[$i])-10)*7);
	  };
	  if ((strlen($la_valores_cadena[$i]) > 12) && (430+((strlen($la_valores_cadena[$i])-12)*30) > $ancho_grafica))
	  {
	    $ancho_grafica = 430 + (strlen($la_valores_cadena[$i])-12) * 30;
	  };
	}
	$ancho = $ancho_grafica + $ancho_leyenda;
	// Asignamos las dimensiones adecuadas
	$this->set_dimension_imagen($ancho,$alto);
	
	// Creamos la imagen y los colores
	$this->inicializar();
	 
    //Definimos los colores a usar
    $color1  = imagecolorallocate($this->imagen,21,135,164);    //azul verdoso
    $color2  = imagecolorallocate($this->imagen,195,24,109);   //vinotinto claro  
	$color3  = imagecolorallocate($this->imagen,255,223,0);   //amarillo claro
    $color4  = imagecolorallocate($this->imagen,24,180,123);   //verde
    $color5  = imagecolorallocate($this->imagen,219,81,198);    //purpura
    $color6  = imagecolorallocate($this->imagen,255,153,14);   //anaranjado
    $color7  = imagecolorallocate($this->imagen,127,188,255);  //Azul claro
    $color8  = imagecolorallocate($this->imagen,244,150,183);  //rosa claro    
    $color9  = imagecolorallocate($this->imagen,150,245,105);  //verde claro 
    $color10 = imagecolorallocate($this->imagen,188,123,78);  //marron claro
    $color11 = imagecolorallocate($this->imagen,116,151,103);  //verde opaco claro
    $color12 = imagecolorallocate($this->imagen,196,196,196);  //gris claro
    $color13 = imagecolorallocate($this->imagen,225,227,203);  //marron claro
    $color14 = imagecolorallocate($this->imagen,119,173,149);  //verde oscuro
    $color15 = imagecolorallocate($this->imagen,75,70,170);    //azul oscuro
    $color16 = imagecolorallocate($this->imagen,164,58,134);   //vinotinto
    $color17 = imagecolorallocate($this->imagen,186,200,8);    //verde oliva
    $color18 = imagecolorallocate($this->imagen,195,195,195);  //gris claro
    $sombra1  = imagecolorallocate($this->imagen,11,105,129);  //azul verdoso oscuro
    $sombra2  = imagecolorallocate($this->imagen,149,8,78);  //vinotinto oscuro
    $sombra3  = imagecolorallocate($this->imagen,233,207,78);  //amarillo oscuro
    $sombra4  = imagecolorallocate($this->imagen,0,149,95);  //verde oscuro
    $sombra5  = imagecolorallocate($this->imagen,199,94,183);   //purpura oscuro
    $sombra6  = imagecolorallocate($this->imagen,244,131,0);  //anaranjado oscuro    
    $sombra7  = imagecolorallocate($this->imagen,127,188,255);  //azul claro oscuro 
    $sombra8  = imagecolorallocate($this->imagen,231,88,122);   //rosa oscuro 
    $sombra9  = imagecolorallocate($this->imagen,139,243,94);  //azul oscuro 2
    $sombra10 = imagecolorallocate($this->imagen,144,107,82); //marron oscuro 2
    $sombra11 = imagecolorallocate($this->imagen,91,118,80); //verde opaco oscuro
    $sombra12 = imagecolorallocate($this->imagen,157,157,157); //gris oscuro 2
    $sombra13 = imagecolorallocate($this->imagen,182,177,140); //marron
    $sombra14 = imagecolorallocate($this->imagen,91,151,124);  //verde oscuro
    $sombra15 = imagecolorallocate($this->imagen,68,64,154);   //azul +  oscuro
    $sombra16 = imagecolorallocate($this->imagen,153,55,125);  //vinotinto oscuro
    $sombra17 = imagecolorallocate($this->imagen,172,185,7);   //verde oliva oscuro
    $sombra18 = imagecolorallocate($this->imagen,155,155,155); //gris oscuro
    $colores = array($color1,$color2,$color3,$color4,$color5,
	                 $color6,$color7,$color8,$color9,$color10,
					 $color11,$color12,$color13,$color14,$color15,
					 $color16,$color17,$color18);
    $sombras = array($sombra1,$sombra2,$sombra3,$sombra4,$sombra5,
	                 $sombra6,$sombra7,$sombra8,$sombra9,$sombra10,
					 $sombra11,$sombra12,$sombra13,$sombra14,$sombra15,
					 $sombra16,$sombra17,$sombra18);    
	    
    // Obtenemos la suma de los valores 
	$total = array_sum($la_valores);
	if ($total == 0)
	{$total = 1;}
	
	// Obtenemos los porcentajes y ángulos
	for($i=0; $i<count($la_valores); $i++)
	{
      $porcentajes[] = round(($la_valores[$i]/$total)*100, 2);
      $angulos[]     = round(($porcentajes[$i]*360)/100);
    } 
    	

    //Definimos los valores a usar en la grafica
    $coord_x_gra = ($ancho_grafica /2);       //Coordenada X del Centro de la grafica
    $coord_y_gra = ($this->alto_img/2);       //Coordenada Y del Centro de la grafica
    $ancho_gra   = ($ancho_grafica /2);       //Ancho de la grafica
    if($ab_3d && $items<=10)
		$alto_gra    = ($this->alto_img/2);       //Alto de la grafica
	else
		$alto_gra    = ($ancho_grafica/2);       //Alto de la grafica
    if($ab_3d)
		$profundidad = 35;                        //Profundidad de la grafica  
	else
		$profundidad = 0;
	
	// Dibujamos las rebanadas con los colores de las sombras solo si se tienen
	// menos de 15 renglones
	if ($items <= 10)
	{
	  $inicio = 0; 
	  for($n=0;$n<$profundidad;$n++)
	  {
        if ($n==0)
	    {
	      // Trazamos el perímetro inferior	
	      //imageellipse($this->imagen,$coord_x_gra,$coord_y_gra+$profundidad,$ancho_gra,$alto_gra,$this->color_fuente);
	    }
        for($i=0;$i<count($la_valores);$i++)
	    {
          $final = $angulos[$i]+$inicio;
          imagefilledarc($this->imagen,$coord_x_gra,$coord_y_gra-$n+$profundidad,$ancho_gra,$alto_gra,$inicio,$final,$sombras[$i],IMG_ARC_PIE);
          $inicio += $angulos[$i];
        }
      }
      
      // Dibujamos las líneas divisorias verticales  
	  $inicio = 0; 
	 /* for($i=0;$i<count($la_valores);$i++) 
	  {
        $final = $angulos[$i]+$inicio;
        $fx = $coord_x_gra + cos(deg2rad($final))*($ancho_gra/2);
        $fy = $coord_y_gra + sin(deg2rad($final))*($alto_gra/2);
        imageline($this->imagen,$fx,$fy,$fx,$fy+$profundidad,$this->color_fuente);
        $inicio += $angulos[$i];
      }  */    
    };
        
	// Dibujamos las rebanadas superiores con los colores
	$inicio = 0;
	for($i=0;$i<count($la_valores);$i++)
	{
      //$final = $angulos[$i]+$inicio;
	  imagefilledarc($this->imagen,$coord_x_gra,$coord_y_gra,
	  $ancho_gra,$alto_gra,$inicio,$angulos[$i]+$inicio,$colores[$i],IMG_ARC_PIE);
	  $inicio += $angulos[$i];
    }
    
    /*for($i=0;$i<count($valores);$i++) {
    imagefilledarc($imagen, $cx, $cy, $ancho, $alto, $inicio, $angulos[$i]+$inicio, $colores[$i], IMG_ARC_PIE);
    $inicio += $angulos[$i];
    }*/
    
	// Dibujamos las líneas divisorias  superiores
	$inicio = 0;
/*	for($i=0;$i<count($la_valores);$i++) 
	{
      $final = $angulos[$i]+$inicio;
	  $fx = $coord_x_gra + cos(deg2rad($final))*($ancho_gra/2);
	  $fy = $coord_y_gra + sin(deg2rad($final))*($alto_gra/2);
	  imageline($this->imagen,$coord_x_gra,$coord_y_gra,$fx,$fy,$this->color_fuente);
	  $inicio += $angulos[$i];
	}*/
	
	// Trazamos el perímetro superior	
	//imageellipse($this->imagen,$coord_x_gra,$coord_y_gra,$ancho_gra,$alto_gra,$this->color_fuente);
	
	//Dibujamos los valores al lado de cada pedazo de la torta
	$inicio = 0;	
	$fnt = 3; // Definimos tamaño de letra 
	$fnt_w = imagefontwidth($fnt); // Obtenemos ancho de la fuente seleccionada 
	$fnt_h = imagefontheight($fnt);// Obtenemos alto de la fuente seleccionada 	
	for($i=0;$i<count($la_valores);$i++)
	{      
	  // Obtenemos la bisectriz
      $bis = $inicio+(($angulos[$i])/2);    
      // Obtenemos las coordenadas X y Y de inicio
	  $x1 = $coord_x_gra+((cos(deg2rad($bis))*($ancho_gra/2))/1.6);  
	  $y1 = $coord_y_gra+((sin(deg2rad($bis))*($alto_gra/2))/1.6);
      // Obtenemos las coordenadas del final 
      $x2 = $coord_x_gra+((cos(deg2rad($bis))*($ancho_gra/2))*1.1);
      $y2 = $coord_y_gra+((sin(deg2rad($bis))*($alto_gra/2))*1.1);
	  // Dibujamos los círculos negros 
	  //imagefilledellipse($this->imagen,$x1,$y1,6,6,$this->color_fuente);
	  // Trazamos la linea 
	  //imageline($this->imagen,$x1,$y1,$x2,$y2,$this->color_fuente);
	  // Evaluamos si el texto vá a la derecha o izq
	  // y definimos la coordenada X del texto 
	  if($x2<$coord_x_gra)
	  {
        $txt_x = $x2-$fnt_w*strlen($la_valores_cadena[$i])-6;
      }
	  else
	  {
        $txt_x = $x2+6;
      }
	  // Definimos la coordenada Y del texto 
      $txt_y = $y2-($fnt_h/2);
      // Escribimos el valor
	  imagestring($this->imagen,$fnt,$txt_x,$txt_y,$la_valores_cadena[$i],$this->color_fuente);
	  $inicio += $angulos[$i];
    }
    
    // Dibujamos la leyenda    
	//ancho de los rectangulos;
	$ancho_rec = 14; 
	//Cordenada x de inicio de los rectangulos de la leyenda
	$x1_rec = ($this->ancho_img - $ancho_leyenda)+4;
	//Cordenada x de fin de lo rectangulos de la leyenda
	$x2_rec = $x1_rec + $ancho_rec;
	//Coordenada y del primer rectangulo de la leyenda
	$y1_rec = 10;
	// Hacemos un marco
	//$ancho_marco = 2;
	imagefilledrectangle($this->imagen,$x1_rec-4,0,$this->ancho_img,$this->alto_img,$this->color_fuente);
	imagefilledrectangle($this->imagen,$x1_rec-2,2,$this->ancho_img-2,$this->alto_img-2,$this->color_fondo);

	$inicio = 0;
	for($i=0;$i<count($la_valores);$i++)
	{
      imagefilledrectangle($this->imagen,$x1_rec,$y1_rec+($i*20),$x2_rec,($y1_rec+$ancho_rec)+($i*20),$colores[$i]);
      imagestring($this->imagen,3,$x1_rec+20,12+($i*20),$la_renglones[$i]."(".$this->ue_convertir_numerocadena($porcentajes[$i])."%)",$this->color_fuente);
      $inicio += $angulos[$i];
	}
	
	// Dibujamos el total de la suma de todos los valores involucrados
	// en la parte superior izquierda de la grafica
	if ($total_valores < 3)
	{$total_valores = round($total_valores,5);}
	else
	{$total_valores = round($total_valores,2);}
	if (strpos($total_valores,"."))
	{$total_valores = $this->ue_convertir_numerocadena($total_valores);}
	else
	{$total_valores = $this->ue_convertir_numerocadena($total_valores,"i");}
	imagestring($this->imagen,4,0,0,"TOTAL : ".$total_valores,$this->color_fuente);
  }
  else
  {
    // Creamos la imagen y los colores
	$this->inicializar();
	
	// Mandamos un mensaje
	imagestring($this->imagen,3,50,50,"No puede tener mas de 18 renglones",$this->color_fuente);
  }
  return $this->imagen;
  }
//////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////
//                 FUNCION QUE REALIZA UNA GRAFICA DE BARRAS
//////////////////////////////////////////////////////////////////////////////////////      
  function graficar_barras($la_renglones,$la_valores) 
  {    	
    $cantidad = count($la_valores);
    //Convertimos los valores en un formato para realizar operaciones
    for ($i=0; $i<$cantidad; $i++)
    {
	  $la_valores_cadena[$i] = $la_valores[$i];
	  $la_valores[$i] = $this->ue_convertir_cadenanumero($la_valores[$i]);
	}

    // Calculamos las dimensiones adecuadas a la imagen
    // Calculamos el alto adecuado
    $this->set_dimension_imagen(580,240);
    $alto  = $this->alto_img;
    $ancho = $this->ancho_img;
    $adicion_mayor_cadena = 0;
	for($i = 0; $i < $cantidad; $i++)
	{
	  if ((strlen($la_renglones[$i]) > 6) && ($this->alto_img+((strlen($la_renglones[$i])-6)*7) > $alto))
	  {
	    $alto =  $this->alto_img+((strlen($la_renglones[$i])-6)*7);
	  };
	  if ((strlen($la_valores_cadena[$i]) > 4) && (((strlen($la_valores_cadena[$i])-4)*14) > $adicion_mayor_cadena))
	  {
	    $adicion_mayor_cadena = (strlen($la_valores_cadena[$i])-4) * 14;
	  }; 
	}
	$ancho = $ancho + $adicion_mayor_cadena;
	// Asignamos las dimensiones adecuadas
	$this->set_dimension_imagen($ancho,$alto); 
	
	// Asignamos valores al margen y al origen de coordenadas
	$img_margen = 20 + ($adicion_mayor_cadena/2);  // Margen lateral
    $origen     = 190; // Origen de las barras
    
	// Creamos la imagen y los colores
	$this->inicializar();

    // Definimos los colores que usaremos    
	$color_sombra      = imagecolorallocate($this->imagen,195,195,195); // gris oscuro
	$color_barra       = imagecolorallocate($this->imagen,0,99,155);   // Azul
	$color_texto_barra = imagecolorallocate($this->imagen,255,255,255); // blanco
	    	
	// Distancia entre las barras
	$distancia = ($this->ancho_img - ($img_margen*2))/$cantidad;
	
	// Máximo y Mínimo de los valores
	$max = max($la_valores);
	if ($max <= 0)
	{$max = 1;}
	if (strpos($max,"."))
	{$max_cadena = $this->ue_convertir_numerocadena($max);}
	else
	{$max_cadena = $this->ue_convertir_numerocadena($max,"i");}
	
	$min = min($la_valores);    
	if (strpos($min,"."))
	{$min_cadena = $this->ue_convertir_numerocadena($min);}
	else
	{$min_cadena = $this->ue_convertir_numerocadena($min,"i");}
	
	// Obtenemos la escala según el valor máximo
	// y el espacio vertical de la imagen desde 
	// el origen dejando un margen superior de 10px
	$escala = ($origen - 10)/$max;

	// Definimos la fuente
	$fuente = 3;
	// Obtenemos el ancho y alto de la fuente 
	$fuente_ancho = imagefontwidth($fuente);
	$fuente_alto  = imagefontheight($fuente);
	
	// Dibujamos las líneas de los límites
	// mínimo y máximo	
	// línea del máximo 
	imageline($this->imagen,40+($adicion_mayor_cadena/2),$origen-($max*$escala),
	$this->ancho_img-(40+($adicion_mayor_cadena/2)),$origen-($max*$escala),$color_sombra);
	// línea del mínimo 
	imageline($this->imagen,40+($adicion_mayor_cadena/2),$origen-($min*$escala),
	$this->ancho_img-(40+($adicion_mayor_cadena/2)),$origen-($min*$escala),$color_sombra);
	
	// texto del valor máximo 
	imagestring($this->imagen,$fuente,35+($adicion_mayor_cadena/2)-($fuente_ancho*strlen($max_cadena)),$origen-($max*$escala)-($fuente_alto/2),$max_cadena,$this->color_fuente);	
	imagestring($this->imagen,$fuente,$this->ancho_img-(35+($adicion_mayor_cadena/2)),$origen-($max*$escala)-($fuente_alto/2),$max_cadena,$this->color_fuente);
	
	// texto del valor mínimo 
	imagestring($this->imagen,$fuente,35+($adicion_mayor_cadena/2)-($fuente_ancho*strlen($max_cadena)),$origen-($min*$escala)-($fuente_alto/2),$min_cadena,$this->color_fuente);	
	imagestring($this->imagen,$fuente,$this->ancho_img-(35+($adicion_mayor_cadena/2)),$origen-($min*$escala)-($fuente_alto/2),$min_cadena,$this->color_fuente);	
	
	// Definimos el ancho de las barras
	imagesetthickness($this->imagen,16);	
	
	// Por cada valor, dibujamos una barra
	$barra = 0;
	//foreach($la_valores as $renglon => $valor)
	for($i=0;$i<$cantidad;$i++)
	{
	    // Obtenemos las coordenadas de la barra
	    $x = intval($img_margen+($distancia/2)+($distancia*$barra));
	    $y = intval($origen-($la_valores[$i]*$escala));
	    // Dibujamos la sombra de la barra 
	    imageline($this->imagen,$x-6,$y+6,$x-6,$origen,$color_sombra);
	    // Dibujamos la barra 
	    imageline($this->imagen,$x,$y,$x,$origen,$color_barra);
	    // Escribimos el renglon
	    imagestringup($this->imagen,$fuente,$x-($fuente_alto/2),$origen+5+(strlen($la_renglones[$i])*$fuente_ancho),$la_renglones[$i],$this->color_fuente);
	    // Escribimos el valor 
	    imagestringup($this->imagen,$fuente,$x-($fuente_alto/2),$origen-5,$la_valores_cadena[$i],$color_texto_barra);	
	    $barra++;
    }
    
    imagesetthickness($this->imagen,1);
	imageline($this->imagen,10,$origen,$this->ancho_img-10,$origen,$this->color_fuente);	
    return $this->imagen;
  }
//////////////////////////////////////////////////////////////////////////////////////
function ue_convertir_cadenanumero($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: ue_convertir_cadenanumero	 //
 //	Access:  public
 //	Returns: cadena numerica con formato xxxxx.xx
 //	Description: Funcion que permite transformar una cadena numerica con
 //				  formato xx.xxx,xx a formato xxxxx.xx
 // Fecha: 21/03/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////
  
  $numero = str_replace(".", "", $numero);
  $numero = str_replace(",", ".", $numero);
  return $numero;
}

function ue_convertir_numerocadena($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: ue_convertir_numerocadena	 //
 //	Access:  public
 //	Returns: cadena numerica con formato xx.xxx,xx
 //	Description: Funcion que permite transformar una cadena numerica con
 //				  formato xxxxx.xx a formato xx.xxx,xx
 // Fecha: 21/03/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////

  $numero = number_format($numero,2, ',', '.');
  return $numero;
}
}
?>