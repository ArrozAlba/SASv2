<?php
class class_funciones_soc
{
	//-----------------------------------------------------------------------------------------------------------------------------------
 	function class_funciones_soc()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: class_funciones_soc
		//		   Access: public
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
	    
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
 	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obteneroperacion()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obteneroperacion
		//		   Access: public
		//	      Returns: operacion valor de la variable
		//	  Description: Función que obtiene que tipo de operación se va a ejecutar (NUEVO, GUARDAR, ELIMINAR)
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
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
	}// end function uf_obteneroperacion
	//-----------------------------------------------------------------------------------------------------------------------------------
 
 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtenerexiste()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenerexiste
		//		   Access: public
		//	      Returns: existe valor de la variable
		//	  Description: Función que obtiene si existe el registro ó no
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
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
	}// end function uf_obtenerexiste
 	//-----------------------------------------------------------------------------------------------------------------------------------
 	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_seleccionarcombo
		//		   Access: public
		//	    Arguments: as_valores  // valores que contiene el combo
		//				   as_seleccionado  // Valor que se debe seleccionar
		//				   aa_parametro  // arreglo de valores
		//				   li_total  // total de item del combo
		//	  Description: Función que seleciona un valor de un combo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
	}// end function uf_seleccionarcombo
 	//-----------------------------------------------------------------------------------------------------------------------------------
 
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
 
	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenervalor_get($as_variable,$as_valordefecto)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalor_get
		//		   Access: public
		//	  Description: Función que obtiene el valor de una variable GET
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$valor="";
		if(array_key_exists($as_variable,$_GET))
		{
			$valor=$_GET[$as_variable];
		}
		if(trim($valor)=="")
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   	}// end function uf_obtenervalor_get
	//-----------------------------------------------------------------------------------------------------------------------------------

   //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formatonumerico
		//		   Access: public
		//	    Arguments: as_valor  // valor sin formato numérico
		//	      Returns: as_valor valor numérico formateado
		//	  Description: Función que le da formato a los valores numéricos que vienen de la BD
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if (empty($as_valor))
		{
			$as_valor="0.00";
		}
		$as_valor=str_replace(".",",",$as_valor);
		if($as_valor<0)
		{
			$ls_temp="-";
			$as_valor=abs($as_valor);
		}
		else
		{
			$ls_temp="";
		}
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
		$as_valor=$ls_temp.$as_valor;
		$li_poscoma=strpos($as_valor, ",");
		$as_decimal=str_pad(substr($as_valor,$li_poscoma+1,2),2,"0");
		$as_valor=substr($as_valor,0,$li_poscoma+1).$as_decimal;
		return $as_valor;
	}// end function uf_formatonumerico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenertipo()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenertipo
		//		   Access: public
		//	  Description: Función que obtiene que tipo de llamada del catalogo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
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
   	}// end function uf_obtenertipo
	//-----------------------------------------------------------------------------------------------------------------------------------

   //----------------------------------------------------------------------------------------------------------------------------
   function uf_load_seguridad($as_sistema,$as_ventanas,&$as_permisos,&$aa_seguridad,&$aa_permisos)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_seguridad
		//		   Access: public (en todas las clases que usen seguridad)
		//	    Arguments: as_sistema // Sistema del que se desea verificar la seguridad
		//				   as_ventanas // Ventana del que se desea verificar la seguridad
		//				   as_permisos  // persimo si puede entrar ó no a la página
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//				   aa_permisos  // arreglo de permisos (incluir, modificar, eliminar, etc )
		//	  Description: Función que verifica si un usuario teine permiso en una pantalla y de ser asi los carga
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$io_seguridad= new sigesp_c_seguridad();
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_logusr=$_SESSION["la_logusr"];
		$aa_seguridad["empresa"]=$ls_empresa;
		$aa_seguridad["logusr"]=$ls_logusr;
		$aa_seguridad["sistema"]=$as_sistema;
		$aa_seguridad["ventanas"]=$as_ventanas;
		$as_permisos="";
		$aa_permisos["leer"]="";
		$aa_permisos["incluir"]="";
		$aa_permisos["cambiar"]="";
		$aa_permisos["eliminar"]="";
		$aa_permisos["imprimir"]="";
		$aa_permisos["anular"]="";
		$aa_permisos["ejecutar"]="";
		if($ls_logusr=="PSEGIS")
		{
			$as_permisos="1";
			$aa_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$as_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$as_sistema,$as_ventanas,$aa_permisos);
		}
		unset($io_seguridad);
   }// end function uf_load_seguridad
   //----------------------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------
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
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
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
   //--------------------------------------------------------------

   //----------------------------------------------------------------------------------------------------------------------------
   function uf_load_seguridad_reporte($as_sistema,$as_ventanas,$as_descripcion)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_seguridad_reporte
		//		   Access: public (en todas las clases que usen seguridad)
		//	    Arguments: as_sistema // Sistema del que se desea verificar la seguridad
		//				   as_ventanas // Ventana del que se desea verificar la seguridad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	  Description: Función que verifica si un usuario teine permiso en una pantalla y de ser asi los carga
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		$io_seguridad= new sigesp_c_seguridad();

		$lb_valido=true;	
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_logusr=$_SESSION["la_logusr"];
		$la_seguridad["empresa"]=$ls_empresa;
		$la_seguridad["logusr"]=$ls_logusr;
		$la_seguridad["sistema"]=$as_sistema;
		$la_seguridad["ventanas"]=$as_ventanas;
		$as_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$as_sistema,$as_ventanas,$aa_permisos);
		if (($as_permisos)||($ls_logusr=="PSEGIS"))
		{
			if($aa_permisos["imprimir"]=="1")
			{			
				$ls_evento="REPORT";
				$lb_valido= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
										$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
										$la_seguridad["ventanas"],$as_descripcion);
			}
			else
			{
				print("<script language=JavaScript>");
				print("alert('No tiene permiso para realizar esta operación.');");
				print("</script>");		
				$lb_valido=false;	
			}
		}
		else
		{
			$lb_valido=false;
		}		
		unset($io_seguridad);
		return $lb_valido;
   }// end function uf_load_seguridad_reporte
   //----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
				$li_i=$li_i+1;
			}
			if($li_i==0)
			{
				$lb_valido=$this->uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				if ($lb_valido)
				{
					$ls_valor=$this->uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			switch ($as_tipo)
			{
				case "C"://Caracter
					$valor = $as_valor;
					break;

				case "D"://Double
					$as_valor=str_replace(".","",$as_valor);
					$as_valor=str_replace(",",".",$as_valor);
					$valor = $as_valor;
					break;

				case "B"://Boolean
					$valor = $as_valor;
					break;

				case "I"://Integer
					$valor = intval($as_valor);
					break;
			}
			$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
					"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
					
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenertipo_orden()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenertipo_orden
		//		   Access: public
		//	  Description: Función que obtiene que tipo orden de compra
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("tipord",$_GET))
		{
			$tipo=$_GET["tipord"];
		}
		else
		{
			$tipo="";
		}
   		return $tipo; 
   	}// end function uf_obtenertipo_orden
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_loadmodalidad(&$ai_len1,&$ai_len2,&$ai_len3,&$ai_len4,&$ai_len5,&$as_titulo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_loadmodalidad
		//		   Access: public
		//	  Description: Función que obtiene que tipo de modalidad y da las longitudes por accion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ai_len1=$_SESSION["la_empresa"]["loncodestpro1"];
		$ai_len2=$_SESSION["la_empresa"]["loncodestpro2"];
		$ai_len3=$_SESSION["la_empresa"]["loncodestpro3"];
		$ai_len4=$_SESSION["la_empresa"]["loncodestpro4"];
		$ai_len5=$_SESSION["la_empresa"]["loncodestpro5"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$as_titulo="Estructura Presupuestaria";
				break;
				
			case "2": // Modalidad por Programatica
				$as_titulo="Estructura Programatica";
				break;
		}
   	}// end function uf_loadmodalidad
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatoprogramatica($as_codpro,&$as_programatica)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formatoprogramatica
		//		   Access: public
		//	  Description: Función que obtiene que de acuerdo a la modalidad imprime la programatica
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$li_len1=0;
		$li_len2=0;
		$li_len3=0;
		$li_len4=0;
		$li_len5=0;
		$ls_titulo="";
		$this->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_codest1=substr($as_codpro,0,25);
		$ls_codest2=substr($as_codpro,25,25);
		$ls_codest3=substr($as_codpro,50,25);
		$ls_codest4=substr($as_codpro,75,25);
		$ls_codest5=substr($as_codpro,100,25);
		$ls_codest1=substr($ls_codest1,(25-$li_len1),$li_len1);
		$ls_codest2=substr($ls_codest2,(25-$li_len2),$li_len2);
		$ls_codest3=substr($ls_codest3,(25-$li_len3),$li_len3);
		$ls_codest4=substr($ls_codest4,(25-$li_len4),$li_len4);
		$ls_codest5=substr($ls_codest5,(25-$li_len5),$li_len5);		
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$as_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3;
				break;

			case "2": // Modalidad por Programa
				$as_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3."-".$ls_codest4."-".$ls_codest5;
				break;
		}
   	}// end function uf_obtenertipo
	//-----------------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------------
    function uf_soc_select_administrador($as_codemp,$as_codusu,&$as_administrativo)
	{
     ///////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_sep_select_administrador
	 //     Argumentos :    $as_codemp ---> codigo de empresa
	 //                     $as_codusu ---> codigo de usuario
	 //                     $as_administrativo ---> codigo administrativo
     //	       Returns :	Retorna true o false si se realizo la consulta 
	 //	   Description :	Metodo que verifica  si existe un registro con esta especificacion
	 //     Creado por :    Ing. Néstor Falcón.
	 // Fecha Creación :   16/04/2007          Fecha última Modificacion : 30/01/2008   Hora = 04:35
  	 ///////////////////////////////////////////////////////////////////////////////////////////////

	   require_once("../shared/class_folder/class_sql.php");
	   require_once("../shared/class_folder/sigesp_include.php");
	   $io_include		   = new sigesp_include();
	   $this->io_conexion = $io_include->uf_conectar();
	   $this->io_sql      = new class_sql($this->io_conexion);
	   $lb_valido=false;
	   $as_ventana= $this->obtenerCodigoMenu('SOC','sigesp_soc_p_registro_orden_compra.php',&$campo);
	   $ls_sql=" SELECT administrativo ".
               " FROM   sss_derechos_usuarios ".
               " WHERE  codemp='".$as_codemp."'  AND codusu='".$as_codusu."' AND  ".
			   "        codsis='SOC' AND $campo='".$as_ventana."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SEP MÉTODO->uf_soc_select_administrador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
	  		 if ($row=$this->io_sql->fetch_row($rs_data))
			 {
			    $as_administrativo=$row["administrativo"];
				$lb_valido=true; 
			 }
		}
		unset($io_include,$this->io_conexion,$this->io_sql);
		return $lb_valido;
    }//uf_soc_select_administrador
   //---------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_load_estatus_cierre(&$li_estciespi,&$li_estciescg)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_estatus_cierre
		//		   Access: public
		//	  Description: Función que obtiene que tipo orden de compra
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 28/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
	    require_once("../shared/class_folder/class_sql.php");
	    require_once("../shared/class_folder/sigesp_include.php");
	    $io_include		   = new sigesp_include();
	    $this->io_conexion = $io_include->uf_conectar();
	    $this->io_sql      = new class_sql($this->io_conexion);
   		
		$ls_sql = "SELECT estciespg,estciespi,estciescg 
		             FROM sigesp_empresa 
					WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $this->io_mensajes->message("CLASE->class_funciones_soc();MÉTODO->uf_load_estatus_cierre();ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		   }
		else
		   {
	  	     if ($row=$this->io_sql->fetch_row($rs_data))
			    {
			      $li_estciespg = $row["estciespg"];
				  $li_estciespi = $row["estciespi"];
				  $li_estciescg = $row["estciescg"];			  
			    }
		   }
		unset($io_include,$this->io_conexion,$this->io_sql,$row);
		return $li_estciespg; 
   	}// end function uf_load_estatus_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------

		/***********************************************************************************
		* @Función que busca el código del sistema ventana
		* @parametros: 
		* @retorno: 
		* @fecha de creación: 09/10/2008
		* @autor: Ing. Yesenia Moreno de Lang
		************************************************************************************
		* @fecha modificación:
		* @descripción:
		* @autor:
		***********************************************************************************/		
			function obtenerCodigoMenu($codsis,$nomfisico,&$campo)
			{
				global $conexionbd;
				if (array_key_exists('session_activa',$_SESSION))
				{				
					$consulta = "SELECT codmenu ".
								"  FROM sss_sistemas_ventanas ".
								" WHERE codsis = '$codsis' ".
								"	AND nomfisico ='$nomfisico' ";
					$result = $this->io_sql->Execute($consulta); 
					if($result === false)
					{
						$this->valido  = false;
					}
					else
					{
						if(!$result->EOF)
						{   
							$codmenu=$result->fields["codmenu"];
						}
						$result->Close();
					}
					$campo= "codmenu";
				}
				else
				{
					$codmenu = $nomfisico;
					$campo= "nomven";
				}
				return $codmenu;
			}		
}
?>