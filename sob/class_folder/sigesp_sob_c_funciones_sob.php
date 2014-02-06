<?Php
class sigesp_sob_c_funciones_sob
{
  
	var $io_function;
	var $la_empresa;
	var $io_sql;
	var $is_msg;
	
	function sigesp_sob_c_funciones_sob()
	{
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funcion=new class_funciones();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_msg=new class_mensajes();
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtenervalor($as_valor,$as_valordefecto)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalor
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos obtener
		//				   as_valordefecto  // Valor por defecto de la variable
		//	      Returns: valor contenido de la variable
		//	  Description: Función que obtiene el valor de una variable que viene de un submit y si no trae valor coloca el
		//				   por defecto 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$valor="";
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		if(trim($valor)=="")
		{
			$valor=$as_valordefecto;
		}
		return $valor; 
	}// end function uf_obtenervalor
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_convertir_cadenanumero($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: uf_convertir_cadenanumero	 //
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

function uf_convertir_numerocadena($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: uf_convertir_numerocadena	 //
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

function uf_convertir_numeroestado ($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: uf_convertir_numeroestado	 //
 //	Access:  public
 //	Returns: estado
 //	Description: un dato numerico en el estado correspondiente
 // Fecha: 21/03/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////
	$ls_estado="";
	switch($numero)
	{
		case 1:
			$ls_estado="EMITIDO";
			break;
		case 2:
			$ls_estado="ASIGNADO";
			break;
		case 3:
			$ls_estado="ANULADO";
			break;
		case 4:
			$ls_estado="CONTRATADO";
			break;
		case 5:
			$ls_estado="CONTABILIZADO";
			break;
		case 6:
			$ls_estado="MODIFICADO";
			break;
		case 7:
			$ls_estado="PARALIZADO";
			break;
		case 8:
			$ls_estado="FINALIZADO";
			break;
		case 9:
			$ls_estado="PRORROGA";
			break;	
		case 10:
			$ls_estado="INICIADO";
			break;
		case 11:
			$ls_estado="PRORROGAPARALIZADO";
			break;	
	}
	return $ls_estado;
}

function uf_convertir_numerotipoacta ($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: uf_convertir_numerotipoacta	 //
 //	Access:  public
 //	Returns: estado
 //	Description: convierte un numero en su respectivo tipo de acta
 // Fecha: 10/04/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////
	$ls_acta="";
	switch($numero)
	{
		case 1:
			$ls_acta="INICIO";
			break;
		case 2:
			$ls_acta="FINALIZACION";
			break;
		case 3:
			$ls_acta="R.PROVISIONAL";
			break;		
		case 4:
			$ls_acta="R.DEFINITIVA";
			break;	
		case 5:
			$ls_acta="PARALIZACION";
			break;
		case 6:
			$ls_acta="REANUDACION";
			break;		
		case 7:
			$ls_acta="PRORROGA";
			break;			
	}
	return $ls_acta;
}

function uf_convertir_decimalentero ($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: uf_convertir_decimalentero	 //
 //	Access:  public
 //	Returns: cadena numerica con formato entero
 //	Description: Funcion que permite transformar una cadena numerica decimal a entero
 // Fecha: 21/03/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////
	$numero=round($numero);
	return $numero;
}

function uf_convertir_letraunidad($as_letra)
{
	$ls_unidad="";
	switch ($as_letra)
	{
		case "d":
			$ls_unidad="Día(s)";
			break;
		case "m":
			$ls_unidad="Mes(es)";
			break;
		case "a":
			$ls_unidad="Año(s)";
			break;
	}
	return $ls_unidad;
	
}

function uf_convertir_numeromes($numero)
{
	$mes="";
	switch($numero)
	{
		case "01":
			$mes="Enero";
			break;
		case "02":
			$mes="Febrero";
			break;
		case "03":
			$mes="Marzo";
			break;
		case "04":
			$mes="Abril";
			break;
		case "05":
			$mes="Mayo";
			break;
		case "06":
			$mes="Junio";
			break;
		case "07":
			$mes="Julio";
			break;
		case "08":
			$mes="Agosto";
			break;
		case "09":
			$mes="Septiembre";
			break;
		case "10":
			$mes="Octubre";
			break;
		case "11":
			$mes="Noviembre";
			break;
		case "12":
			$mes="Diciembre";
			break;		
	}
	return $mes;
}

	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: Convertir numeros a letras
	 //	Access:  public
	 //	Returns: Equivalente de un numero a letras
	 //	Description: Funciones que permiten convertir numeros a letras
	 // Fecha: 15/05/2006
	 // Autor: Ivan Karam (http://www.tuxteno.com/contents.php?cid=524)
	 //////////////////////////////////////////////////////////////////////////////
	 
function unidad($numuero){  //UNIDADES
 switch ($numuero)
 {
  case 9:
  {
   $numu = "NUEVE";
   break;
  }
  case 8:
  {
   $numu = "OCHO";
   break;
  }
  case 7:
  {
   $numu = "SIETE";
   break;
  }  
  case 6:
  {
   $numu = "SEIS";
   break;
  }  
  case 5:
  {
   $numu = "CINCO";
   break;
  }  
  case 4:
  {
   $numu = "CUATRO";
   break;
  }  
  case 3:
  {
   $numu = "TRES";
   break;
  }  
  case 2:
  {
   $numu = "DOS";
   break;
  }  
  case 1:
  {
   $numu = "UN";
   break;
  }  
  case 0:
  {
   $numu = "";
   break;
  }  
 }
 return $numu; 
}

function decena($numdero){              //DECENAS
 
  if ($numdero >= 90 && $numdero <= 99)
  {
   $numd = "NOVENTA ";
   if ($numdero > 90)
    $numd = $numd."Y ".($this->unidad($numdero - 90));
  }
  else if ($numdero >= 80 && $numdero <= 89)
  {
   $numd = "OCHENTA ";
   if ($numdero > 80)
    $numd = $numd."Y ".($this->unidad($numdero - 80));
  }
  else if ($numdero >= 70 && $numdero <= 79)
  {
   $numd = "SETENTA ";
   if ($numdero > 70)
    $numd = $numd."Y ".($this->unidad($numdero - 70));
  }
  else if ($numdero >= 60 && $numdero <= 69)
  {
   $numd = "SESENTA ";
   if ($numdero > 60)
    $numd = $numd."Y ".($this->unidad($numdero - 60));
  }
  else if ($numdero >= 50 && $numdero <= 59)
  {
   $numd = "CINCUENTA ";
   if ($numdero > 50)
    $numd = $numd."Y ".($this->unidad($numdero - 50));
  }
  else if ($numdero >= 40 && $numdero <= 49)
  {
   $numd = "CUARENTA ";
   if ($numdero > 40)
    $numd = $numd."Y ".($this->unidad($numdero - 40));
  }
  else if ($numdero >= 30 && $numdero <= 39)
  {
   $numd = "TREINTA ";
   if ($numdero > 30)
    $numd = $numd."Y ".($this->unidad($numdero - 30));
  }
  else if ($numdero >= 20 && $numdero <= 29)
  {
   if ($numdero == 20)
    $numd = "VEINTE ";
   else
    $numd = "VEINTI".($this->unidad($numdero - 20));
  }
  else if ($numdero >= 10 && $numdero <= 19)
  {
   switch ($numdero){
   case 10:
   {
    $numd = "DIEZ ";
    break;
   }
   case 11:
   {     
    $numd = "ONCE ";
    break;
   }
   case 12:
   {
    $numd = "DOCE ";
    break;
   }
   case 13:
   {
    $numd = "TRECE ";
    break;
   }
   case 14:
   {
    $numd = "CATORCE ";
    break;
   }
   case 15:
   {
    $numd = "QUINCE ";
    break;
   }
   case 16:
   {
    $numd = "DIECISEIS ";
    break;
   }
   case 17:
   {
    $numd = "DIECISIETE ";
    break;
   }
   case 18:
   {
    $numd = "DIECIOCHO ";
    break;
   }
   case 19:
   {
    $numd = "DIECINUEVE ";
    break;
   }
   } 
  }
  else
   $numd = $this->unidad($numdero);
 return $numd;
}

 function centena($numc){       //CENTENAS
  if ($numc >= 100)
  {
   if ($numc >= 900 && $numc <= 999)
   {
    $numce = "NOVECIENTOS ";
    if ($numc > 900)
     $numce = $numce.($this->decena($numc - 900));
   }
   else if ($numc >= 800 && $numc <= 899)
   {
    $numce = "OCHOCIENTOS ";
    if ($numc > 800)
     $numce = $numce.($this->decena($numc - 800));
   }
   else if ($numc >= 700 && $numc <= 799)
   {
    $numce = "SETECIENTOS ";
    if ($numc > 700)
     $numce = $numce.($this->decena($numc - 700));
   }
   else if ($numc >= 600 && $numc <= 699)
   {
    $numce = "SEISCIENTOS ";
    if ($numc > 600)
     $numce = $numce.($this->decena($numc - 600));
   }
   else if ($numc >= 500 && $numc <= 599)
   {
    $numce = "QUINIENTOS ";
    if ($numc > 500)
     $numce = $numce.($this->decena($numc - 500));
   }
   else if ($numc >= 400 && $numc <= 499)
   {
    $numce = "CUATROCIENTOS ";
    if ($numc > 400)
     $numce = $numce.($this->decena($numc - 400));
   }
   else if ($numc >= 300 && $numc <= 399)
   {
    $numce = "TRESCIENTOS ";
    if ($numc > 300)
     $numce = $numce.($this->decena($numc - 300));
   }
   else if ($numc >= 200 && $numc <= 299)
   {
    $numce = "DOSCIENTOS ";
    if ($numc > 200)
     $numce = $numce.($this->decena($numc - 200));
   }
   else if ($numc >= 100 && $numc <= 199)
   {
    if ($numc == 100)
     $numce = "CIEN ";
    else
     $numce = "CIENTO ".($this->decena($numc - 100));
   }
  }
  else
   $numce = $this->decena($numc);
  
  return $numce; 
}

 function miles($nummero){                  //MILES
  if ($nummero >= 1000 && $nummero < 2000){
   $numm = "MIL ".($this->centena($nummero%1000));
  }
  if ($nummero >= 2000 && $nummero <10000){
   $numm = $this->unidad(Floor($nummero/1000))." MIL ".($this->centena($nummero%1000));
  }
  if ($nummero < 1000)
   $numm = $this->centena($nummero);
  
  return $numm;
 }

 function decmiles($numdmero){
  if ($numdmero == 10000)
   $numde = "DIEZ MIL";
  if ($numdmero > 10000 && $numdmero <20000){
   $numde = $this->decena(Floor($numdmero/1000))."MIL ".($this->centena($numdmero%1000));  
  }
  if ($numdmero >= 20000 && $numdmero <100000){
   $numde = $this->decena(Floor($numdmero/1000))." MIL ".($this->miles($numdmero%1000));  
  }  
  if ($numdmero < 10000)
   $numde = $this->miles($numdmero);
  
  return $numde;
 }  

 function cienmiles($numcmero){                  //CIENMILES
  if ($numcmero == 100000)
   $num_letracm = "CIEN MIL";
  if ($numcmero >= 100000 && $numcmero <1000000){
   $num_letracm = $this->centena(Floor($numcmero/1000))." MIL ".($this->centena($numcmero%1000));  
  }
  if ($numcmero < 100000)
   $num_letracm = $this->decmiles($numcmero);
  return $num_letracm;
 } 
//APARTIR DE AQUI EMPIEZAN A CONTAR LOS MILLONES 


 function millon($nummiero){
  if ($nummiero >= 1000000 && $nummiero <2000000){
   $num_letramm = "UN MILLON ".($this->cienmiles($nummiero%1000000));
  }
  if ($nummiero >= 2000000 && $nummiero <10000000){
   $num_letramm = $this->unidad(Floor($nummiero/1000000))." MILLONES ".($this->cienmiles($nummiero%1000000));
  }
  if ($nummiero < 1000000)
   $num_letramm = $this->cienmiles($nummiero);
  
  return $num_letramm;
 } 

 function decmillon($numerodm){
  if ($numerodm == 10000000)
   $num_letradmm = "DIEZ MILLONES";
  if ($numerodm > 10000000 && $numerodm <20000000){
   $num_letradmm = $this->decena(Floor($numerodm/1000000))."MILLONES ".($this->cienmiles($numerodm%1000000));  
  }
  if ($numerodm >= 20000000 && $numerodm <100000000){
   $num_letradmm = $this->decena(Floor($numerodm/1000000))." MILLONES ".($this->millon($numerodm%1000000));  
  }
  if ($numerodm < 10000000)
   $num_letradmm = $this->millon($numerodm);
  
  return $num_letradmm;
 }

 function cienmillon($numcmeros){
  if ($numcmeros == 100000000)
   $num_letracms = "CIEN MILLONES";
  if ($numcmeros >= 100000000 && $numcmeros <1000000000){
   $num_letracms = $this->centena(Floor($numcmeros/1000000))." MILLONES ".($this->millon($numcmeros%1000000));  
  }
  if ($numcmeros < 100000000)
   $num_letracms = $this->decmillon($numcmeros);
  return $num_letracms;
 } 

 function milmillon($nummierod){
  if ($nummierod >= 1000000000 && $nummierod <2000000000){
   $num_letrammd = "MIL ".($this->cienmillon($nummierod%1000000000));
  }
  if ($nummierod >= 2000000000 && $nummierod <10000000000){
   $num_letrammd = $this->unidad(Floor($nummierod/1000000000))." MIL ".($this->cienmillon($nummierod%1000000000));
  }
  if ($nummierod < 1000000000)
   $num_letrammd = $this->cienmillon($nummierod);
  
  return $num_letrammd;
 } 
   
function convertir($numero){
     $numf = $this->milmillon($numero);
  return $numf;
}

function uf_modificararreglo($aa_arreglo,&$aa_data,&$aa_keys)
{
	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_modificararreglo
	 //	Access:  public
	 //	Returns: arreglo modificado
	 //	Description: funcion que se encarga de reemplazar todos los indices string
	 //				 de una matriz, por enteros comenzando en 0
	 // Fecha: 15/05/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	$aa_keys=array_keys($aa_arreglo);
	$li_numrows = count($aa_keys);
	$aa_data = array();	
	for ($i=0; $i<$li_numrows; $i++)
	{
		$aa_data[$i]=$aa_arreglo[$aa_keys[$i]][1];
	}			
	return 0;
}

function uf_ventana_js ($aa_data,$aa_nombre,$pagina)
{
	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_ventana_js
	 //	Access:  public
	 //	Returns: -
	 //	Parametros: arreglo con la lista de parametros a ser pasados a la ventana, 
	 //				arreglo con la lista de nombres de los parametros, comenzando en 0
	 //				y la pagina quese desea cargar.
	 //	Descripcion: funcion que se encarga de cargar una pagina en la misma ventana, utilizando javascript
	 // Fecha: 16/05/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	print "<script language=JavaScript>";
	print "var pagina='".$pagina."';";
	for ($li_i=0;$li_i<count($aa_data);$li_i++)
	{
		if($li_i==0)
		{
			print "pagina=pagina+'?';";
		}
		//print "pagina=pagina+'".$aa_nombre[$li_i]."';";  //=".$aa_data[$li_i]."';";
		//print "pagina=pagina+'".$aa_nombre[$li_i]."=".$aa_data[$li_i]."';";
		/*if ($li_i<(count($aa_data)-1))
		{
			print "pagina=pagina+'&';";
		}*/
	}
	//print "popupWin(pagina,'win1');";
	/*print "alert('Hola');";
	print "window.open(pagina,'catalogo','menubar=no,toolbar=no,scrollbars=yes,width=100,height=100,resizable=yes,location=no,status=no,top=30,left=30');";
	/*print "if (navigator.appName=='Microsoft Internet Explorer')";
	print "{";			
	print  		"window.resizeBy(550,550);";
	print		"window.moveTo(20,20);";
	print  "}";*/		
	print "</script>";
}

function convertir_arreglo($arreglo)
{
  $ls_matriz = "new Array(new Array(";
  $la_keys = array_keys($arreglo);
  for ($li=0; $li<count($la_keys);$li++)
  {
    $ls_matriz=$ls_matriz."'".$la_keys[$li]."'";
	if ($li != count($la_keys) - 1)
	{
	  $ls_matriz = $ls_matriz.",";
	}	
  }
  $ls_matriz=$ls_matriz."),new Array(";
  for ($li=0; $li<count($la_keys);$li++)
  {
    $ls_matriz=$ls_matriz."'".$arreglo[$la_keys[$li]][1]."'";
	if ($li != count($la_keys) - 1)
	{
	  $ls_matriz = $ls_matriz.",";
	}	
  }  
  $ls_matriz=$ls_matriz."))";
  return $ls_matriz;
}

function uf_codificar($arreglo)
{
	$ls_cadena = "";
  if (is_array($arreglo))
  {
      $la_keys = array_keys($arreglo);
	  for ($li=0; $li<count($la_keys);$li++)
	  {
	    $ls_cadena=$ls_cadena.$la_keys[$li];
		if ($li != count($la_keys) - 1)
		{
		  $ls_cadena = $ls_cadena."|";
		}	
	  }
	  $ls_cadena=$ls_cadena."^";
	  for ($li=0; $li<count($la_keys);$li++)
	  {
	   if($arreglo[$la_keys[$li]][1]!="")
	    	$ls_cadena=$ls_cadena.$arreglo[$la_keys[$li]][1];
		else
			$ls_cadena=$ls_cadena."---";
		if ($li != count($la_keys) - 1)
		{
		  $ls_cadena = $ls_cadena."|";
		}	
	  }  
	}
	  return $ls_cadena;
}

function uf_decodificar($cadena)
{
	
	$ls_cad1=explode("^",$cadena);
	$ls_nombres=explode("|",$ls_cad1[0]);
	$ls_data=explode("|",$ls_cad1[1]);
	for ($li_i=0;$li_i<count($ls_nombres);$li_i++)
	{
		$la_matriz[$ls_nombres[$li_i]]=$ls_data[$li_i];
	}

	return $la_matriz;
}

function uf_codificar_arreglosdobles($aa_arreglo)
{
 	$ls_cadena="";
 	if(is_array($aa_arreglo)) 
 	{
		$li_filasregistros=$this->uf_contar_registros($aa_arreglo);
		$la_keys=array_keys($aa_arreglo);
		$li_filaskeys=count($la_keys);
		$ls_cadena="";
		for($li_j=1;$li_j<=$li_filasregistros;$li_j++)
		{
		  	for($li_i=0;$li_i<$li_filaskeys;$li_i++)
		  	{
				$ls_cadena=$ls_cadena.$la_keys[$li_i]."|";
				if($aa_arreglo[$la_keys[$li_i]][$li_j]!="")
					$ls_cadena=$ls_cadena.$aa_arreglo[$la_keys[$li_i]][$li_j];
				else
					$ls_cadena=$ls_cadena."---";
				if($li_i+1<$li_filaskeys)
				{
				  $ls_cadena=$ls_cadena."||";
				}			
			}
			if($li_j+1<=$li_filasregistros)
			{
			  $ls_cadena=$ls_cadena."^";
			}					    		  
		}	
	   return $ls_cadena;	
	}	  
}

function uf_decodificar_arreglosdobles($cadena,$aa_keys)
{
	if($cadena!="")
	{
		$la_registros=explode("^",$cadena);
		$li_filasregistros=count($la_registros);
		$la_matriz=array();
		for($li_i=0;$li_i<$li_filasregistros;$li_i++)
		{
		  $la_columnas=explode("||",$la_registros[$li_i]);
		  $li_filascolumnas=count($la_columnas);
		  for($li_j=0;$li_j<$li_filascolumnas;$li_j++)
		  {
		 	$la_clavecampo=explode("|",$la_columnas[$li_j]);
		  	$ls_clavecampo=array_search($la_clavecampo[0],$aa_keys);
	    	if($ls_clavecampo!=false)
	    	{
			  if(is_numeric($la_clavecampo[1]) && $ls_clavecampo!="Cuenta" && substr($ls_clavecampo,0,3)!="Cod")
			  	  	$la_matriz[$li_i][$ls_clavecampo]=$this->uf_convertir_numerocadena($la_clavecampo[1]); 	
			  else
			  		$la_matriz[$li_i][$ls_clavecampo]=$la_clavecampo[1]; 				 	  
			}
				
		  }
		  	 
		}
	}
	else
	{
	  $la_matriz="";
	}
	return $la_matriz;
}

function uf_decodificar_encadena($cadena,$aa_keys)
{
	$la_cadena="";
	if($cadena!="")
	{
	  	$la_registros=explode("^",$cadena);
		$li_filasregistros=count($la_registros);
		$la_matriz=array();
		for($li_i=0;$li_i<$li_filasregistros;$li_i++)
		{
		  $la_cadena=$la_cadena." (".($li_i+1).")";
		  $la_columnas=explode("||",$la_registros[$li_i]);
		  $li_filascolumnas=count($la_columnas);
		  for($li_j=0;$li_j<$li_filascolumnas;$li_j++)
		  {
		 
			$la_clavecampo=explode("|",$la_columnas[$li_j]);
		  	$ls_clavecampo=array_search($la_clavecampo[0],$aa_keys);
	    	if($ls_clavecampo!=false)
	    	{
				if(is_numeric($la_clavecampo[1]) && $ls_clavecampo!="Cuenta" && substr($ls_clavecampo,0,3)!="Cod")
					$la_cadena=$la_cadena.$ls_clavecampo.": ".$this->uf_convertir_numerocadena($la_clavecampo[1]);
				else
					$la_cadena=$la_cadena.$ls_clavecampo.": ".$la_clavecampo[1];
				if($li_j+1<$li_filascolumnas)
					$la_cadena=$la_cadena.", ";				
			}
		  }
		  	 
		}
	}
	return $la_cadena;
}

function uf_ventanasimple_js($pagina)
{
	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_ventanasimple_js
	 //	Access:  public
	 //	Returns: -
	 //	Parametros: la pagina quese desea cargar.
	 //	Descripcion: funcion que se encarga de cargar una pagina en la misma ventana, utilizando javascript
	 // Fecha: 16/05/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		print "<script language=JavaScript>";
		//$ls_string=$this->uf_codificar($arreglo);
		print "var pagina='".$pagina."';";
		print "location.href=pagina;";
		//print "window.open(pagina,'catalogo','menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,status=no,top=0,left=0');";
		print "if (navigator.appName=='Microsoft Internet Explorer')";		
		print "{";			
		print  		"window.resizeBy(550,550);";
		print		"window.moveTo(20,20);";
		print  "}";
		print "</script>";
}

function uf_decodificardata($as_token,$as_cadena,&$li_index,$as_tipo="")
{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_decodificardata
	 //	Access:  public
	 //	Returns: -
	 //	Parametros: as_token: caracter utilizado para separar la cadena
	 //				as_cadena: cadena a ser separadala pagina quese desea cargar.
	 //	Descripcion: funcion que se encarga de generar un arreglo con los campos a ser utilizados en los reportes
	 // Fecha: 31/05/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	 	$la_data=explode($as_token,$as_cadena);
		$li_index=0;
		for($li_i=0;$li_i<count($la_data);$li_i++)
		{
			$li_index++;
			$la_arreglo[$li_index][1]=$la_data[$li_i];
			$li_i++;
			$la_arreglo[$li_index][2]=$la_data[$li_i];			
			/*if($as_tipo!="")
			{
				$la_arreglo[$li_index][3]=$la_data[$li_i];
			}*/
		}
	return $la_arreglo;
}

function uf_array_merge($aa_array1,$aa_array2,$ai_rows1,$ai_rows2)
{
 	//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_array_merge
	 //	Access:  public
	 //	Returns: array
	 //	Parametros: aa_array1: arreglo en la al que se le va a incluir el 2do arreglo
	 //				aa_array2: arreglo que se va a incluir en el primer arreglo
	 //				ai_rows1:filas del primer arreglo
	 //				ai_rows2:filas del 2do arreglo	
	 //	Descripcion: funcion que se encarga de incluir la data del 2do arreglo en el primero
	 // Fecha: 06/06/2006
	 // Autor: Ing. Laura Cabré
	 ////////////////////////////////////////////////////////////////////////////// 
	 
	 $li_index=$ai_rows1+1;
	 for ($li_i=1;$li_i<=$ai_rows2;$li_i++)
	 {
	 	$aa_array1[$li_index][1]= $aa_array2[$li_i][1]; 	   
	 	$aa_array1[$li_index][2]= $aa_array2[$li_i][2];
	 	$li_index++;
	 }
	 return $aa_array1;  
}

function uf_contar_registros($aa_arreglo)
{
  	//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_array_merge
	 //	Access:  public
	 //	Returns: array
	 //	Parametros: aa_arreglo: arreglo al cual se contaran los registros	 //				
	 //	Descripcion: funcion que se encarga de contar los registros de una matriz
	 // Fecha: 19/06/2006
	 // Autor: Ing. Laura Cabré
	 ////////////////////////////////////////////////////////////////////////////// 
  $li_registros=(count($aa_arreglo, COUNT_RECURSIVE) / count($aa_arreglo)) - 1;
  return $li_registros;
}
}
?>