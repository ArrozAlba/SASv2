<?php
class class_funciones_cfg
{
	function class_funciones_cfg()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  class_funciones_nomina
		//	Description:  Constructor de la Clase
		//////////////////////////////////////////////////////////////////////////////
	}
	
   //--------------------------------------------------------------
   function uf_obteneroperacion()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obteneroperacion
		//	Returns:	$operacion valor de la variable
		//	Description: Función que obtiene que tipo de operación se va a ejecutar
		//////////////////////////////////////////////////////////////////////////////
		
		if(array_key_exists("operacion",$_POST))
		{
			$operacion=$_POST["operacion"];
		}
		else
		{
			$operacion="NUEVO";
		}
   		return $operacion; 
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenerexiste()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenerexiste
		//	Returns:	$existe valor de la variable
		//	Description: Función que obtiene si existe el registro ó no
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("existe",$_POST))
		{
			$existe=$_POST["existe"];
		}
		else
		{
			$existe="FALSE";
		}
   		return $existe; 
   }
   //--------------------------------------------------------------
	
   //--------------------------------------------------------------
   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_seleccionarcombo
		//	Arguments:    as_valores  // valores que contiene el combo
		//				  as_seleccionado  // Valor que se debe seleccionar
		//				  aa_parametro  // arreglo de valores
		//				  li_total  // Valor toatl de valores
		//	Description: Función que seleciona un valor de un combo
		//////////////////////////////////////////////////////////////////////////////
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenervalor
		//	Arguments:    as_valor  // Variable que deseamos obtener
		//				  as_valordefecto  // Valor por defecto de la variable
		//	Returns:	  $valor contenido de la variable
		//	Description: Función que obtiene el valor de una variable que viene de un submit
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenervariable($as_variable, $as_caso1, $as_caso2, $as_valor1, $as_valor2, $as_defecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenervariable
		//	Arguments: as_variable  // Variable que deseamos obtener
		//			   as_caso1  // condición 1
		//			   as_caso2  // condición 2
		//			   as_valor1  // Valor si se cumple la condición 1
		//			   as_valor2  // Valor si se cumple la condición 2
		//	  		   as_defecto  // Valor por defecto de la variable
		//	Returns:	 $valor contenido de la variable
		//	Description: Función que dependiendo del caso trae un valor u otro
		//////////////////////////////////////////////////////////////////////////////
		switch($as_variable)
		{
			case $as_caso1:
				$valor = $as_valor1;
				break;
					
			case $as_caso2:
				$valor = $as_valor2;
				break;					
			
			default:
				$valor = $as_defecto;
				break;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------

  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=str_replace(".",",",$as_valor);
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$as_valor = substr($as_valor,0,$li_poscoma+3);
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenertipo()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenertipo
		//	Description: Función que obtiene que tipo de llamada del catalogo
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("tipo",$_GET))
		{
			$tipo=$_GET["tipo"];
		}
		else
		{
			$tipo="";
		}
   		return $tipo; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenervalor_get($as_variable,$as_valordefecto)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenertipo
		//	Description: Función que obtiene que tipo de llamada del catalogo
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_variable,$_GET))
		{
			$valor=$_GET[$as_variable];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_asignarvalor($as_valor, $as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_asignarvalor
		//	Arguments:    as_valor  // Variable que deseamos obtener
		//				  as_valordefecto  // Valor por defecto de la variable
		//	Returns:	  $valor contenido de la variable
		//	Description: Función que obtiene el valor de una variable que viene de un submit
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
		
		if ($valor=="")
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------
   
   function uf_load_seguridad($as_sistema,$as_ventanas,&$as_permisos="",&$aa_seguridad,&$aa_permisos,$as_path="../../")
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_seguridad
		//		   Access: public (en todas las clases que usen seguridad)
		//	    Arguments: as_sistema // Sistema del que se desea verificar la seguridad
		//				   as_ventanas // Ventana del que se desea verificar la seguridad
		//				   as_permisos  // persimo si puede entrar ó no a la página
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//				   aa_permisos  // arreglo de permisos (incluir, modificar, eliminar, etc )
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$io_seguridad= new sigesp_c_seguridad();
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_logusr=$_SESSION["la_logusr"];
		$aa_seguridad["empresa"]=$ls_empresa;
		$aa_seguridad["logusr"]=$ls_logusr;
		$aa_seguridad["sistema"]=$as_sistema;
		$aa_seguridad["ventanas"]=$as_ventanas;
		$as_permisos="";
		$aa_permisos = array();
		$aa_permisos["leer"]="";
		$aa_permisos["incluir"]="";
		$aa_permisos["cambiar"]="";
		$aa_permisos["eliminar"]="";
		$aa_permisos["imprimir"]="";
		$aa_permisos["anular"]="";
		$aa_permisos["ejecutar"]="";
		if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
		{	
			if($ls_logusr=="PSEGIS")
			{
				$as_permisos="1";
				$aa_permisos=$io_seguridad->uf_sss_load_permisossigesp();
			}
			else
			{
				$as_permisos=$_POST["permisos"];
				$aa_permisos["leer"]=$_POST["leer"];
				$aa_permisos["incluir"]=$_POST["incluir"];
				$aa_permisos["cambiar"]=$_POST["cambiar"];
				$aa_permisos["eliminar"]=$_POST["eliminar"];
				$aa_permisos["imprimir"]=$_POST["imprimir"];
				$aa_permisos["anular"]=$_POST["anular"];
				$aa_permisos["ejecutar"]=$_POST["ejecutar"];
			}
		}
		else
		{
			$as_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$as_sistema,$as_ventanas,$aa_permisos);
		}
		unset($io_seguridad);
   }// end function uf_load_seguridad
   
   //----------------------------------------------------------------------------------------------------------------------------
   function uf_print_permisos($as_permisos,$aa_permisos,$as_logusr,$as_accion)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_permisos
		//		   Access: public
		//	    Arguments: as_permisos  // permisos que tiene el usuario en la página
		//				   aa_permisos  // arreglo de permisos (incluir, modificar, eliminar, etc )
		//				   as_logusr  // login de usuario
		//				   as_accion  // acción que va a ejecutar si no tiene permiso el usuario
		//	  Description: Función que imprime el permiso de seguridad en las páginas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if (($as_permisos)||($as_logusr=="PSEGIS"))
		{
			print("<input type=hidden name=permisos id=permisos value='$as_permisos'>");
			print("<input type=hidden name=leer id=leer value='$aa_permisos[leer]'>");
			print("<input type=hidden name=incluir id=incluir value='$aa_permisos[incluir]'>");
			print("<input type=hidden name=cambiar id=cambiar value='$aa_permisos[cambiar]'>");
			print("<input type=hidden name=eliminar id=eliminar value='$aa_permisos[eliminar]'>");
			print("<input type=hidden name=imprimir id=imprimir value='$aa_permisos[imprimir]'>");
			print("<input type=hidden name=anular id=anular value='$aa_permisos[anular]'>");
			print("<input type=hidden name=ejecutar id=ejecutar value='$aa_permisos[ejecutar]'>");
		}
		else
		{
			print("<script language=JavaScript>");
			print("".$as_accion."");
			print("</script>");
		}
   }// end function uf_print_permisos
   
   //----------------------------------------------------------------------------------------------------------------------------
   function uf_load_seguridad_reporte($as_sistema,$as_ventanas,$as_descripcion)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_seguridad_reporte
		//		   Access: public (en todas las clases que usen seguridad)
		//	    Arguments: as_sistema // Sistema del que se desea verificar la seguridad
		//				   as_ventanas // Ventana del que se desea verificar la seguridad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		$io_seguridad= new sigesp_c_seguridad();
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_logusr=$_SESSION["la_logusr"];
		$la_seguridad["empresa"]=$ls_empresa;
		$la_seguridad["logusr"]=$ls_logusr;
		$la_seguridad["sistema"]=$as_sistema;
		$la_seguridad["ventanas"]=$as_ventanas;
		$ls_evento="REPORT";
		$lb_valido= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$as_descripcion);
		unset($io_seguridad);
		return $lb_valido;
   }// end function uf_load_seguridad
	
   //-----------------------------------------------------------------------------------------------------------------------------
   function uf_formato_estructura($as_codestpro, &$as_codestpro1, &$as_codestpro2, &$as_codestpro3, &$as_codestpro4,&$as_codestpro5)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formato_estructura
		//		   Access: public
		//	    Arguments: $as_codestpro   // La estructura Presupuestaria completa
		//				   $as_codestpro1  // Codigo de Estrutura Presupuestaria 1
		//				   $as_codestpro2  // Codigo de Estrutura Presupuestaria 2
		//				   $as_codestpro3  // Codigo de Estrutura Presupuestaria 3
		//				   $as_codestpro4  // Codigo de Estrutura Presupuestaria 4
		//				   $as_codestpro5  // Codigo de Estrutura Presupuestaria 5
		//	  Description: Función que convierte la estructura presupuestaria completa y le da formato por nivel
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/01/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_empresa=$_SESSION["la_empresa"];
		$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$li_longestpro1= (25-$ls_loncodestpro1)+1;
		$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$li_longestpro2= (25-$ls_loncodestpro2)+1;
		$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$li_longestpro3= (25-$ls_loncodestpro3)+1;
		$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$li_longestpro4= (25-$ls_loncodestpro4)+1;
		$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
		$li_longestpro5= (25-$ls_loncodestpro5)+1;
		$as_codestpro1= substr($as_codestpro,0,25);
		$as_codestpro2= substr($as_codestpro,25,25);
		$as_codestpro3= substr($as_codestpro,50,25);
		$as_codestpro4= substr($as_codestpro,75,25);
		$as_codestpro5= substr($as_codestpro,100,25);
		$as_codestpro1= substr($as_codestpro1,$li_longestpro1-1,$ls_loncodestpro1);
		$as_codestpro2= substr($as_codestpro2,$li_longestpro2-1,$ls_loncodestpro2);
		$as_codestpro3= substr($as_codestpro3,$li_longestpro3-1,$ls_loncodestpro3);
		$as_codestpro4= substr($as_codestpro4,$li_longestpro4-1,$ls_loncodestpro4);
		$as_codestpro5= substr($as_codestpro5,$li_longestpro5-1,$ls_loncodestpro5);
	}// end function uf_formato_estructura	
  function uf_convertirdatetobd($as_fecha)
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_convertirdatetobd
	  // Descripción:   método que convierte el formato de una fecha tipo caracter a formato (yyyy/mm/dd)
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
      $ls_fecreg=""; 
 	  $li_pos=strpos($as_fecha,"/");
 	  $li_pos2=strpos($as_fecha,"-");
	  if(($li_pos==2)||($li_pos2==2))
  	  {
		 $ls_fecreg=(substr($as_fecha,6,4)."-".substr($as_fecha,3,2)."-".substr($as_fecha,0,2)); 
 	  }
	  elseif(($li_pos==4)||($li_pos2==4))
 	  {
	 	 $ls_fecreg=str_replace("/","-",$as_fecha);
	  }
      return $ls_fecreg;
  } // end function
  function uf_convertirfecmostrar($as_fecha)
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_convertirfecmostrar
	  // Descripción:   método que convierte el formato de una fecha tipo caracter a formato (dd/mm/yyyy)
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
      $ls_fecha="";
	  $li_pos=strpos($as_fecha,"-"); 
	  $li_pos2=strpos($as_fecha,"/"); 
	  if(($li_pos==4)||($li_pos2==4))
	  {
   		$ls_fecha=(substr($as_fecha,8,2)."/".substr($as_fecha,5,2)."/".substr($as_fecha,0,4)); 
 	  }
	  elseif(($li_pos==2)||($li_pos2==2))
	  {
		$ls_fecha=$as_fecha;
	  }
      return $ls_fecha;
   } // end function()
	
}
?>