<?php
class class_funciones_mis
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_funciones_mis()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: class_funciones_nomina
		//		   Access: public
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
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
			$this->io_mensajes->message("CLASE->MIS MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
			$this->io_mensajes->message("CLASE->MIS MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$this->io_mensajes->message("CLASE->MIS MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	function uf_guardar_configuracion($ai_bco_feccontdep,$ai_sob_feccontcon,$aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_configuracion
		//		   Access: public (sigesp_mis_p_configuracion.php)
		//	    Arguments: ai_bco_feccontdep // Fecha de Contabilización de Depósitos
		//	    		   ai_sob_feccontcon // Fecha de Contabilización de los contratos
		//	    		   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto correctamente el proceso y false si hubo error
		//	  Description: Función que graba todos los campos de la configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//-------------------------------------CAJA Y BANCO------------------------------------------------
		if($lb_valido)
		{// fecha de Contabilización de Depósitos
			$lb_valido=$this->uf_insert_config("MIS","BANCO","FECHA_CONT_DEPOSITOS",$ai_bco_feccontdep,"C");
		}
		//-----------------------------------------OBRAS---------------------------------------------------
		if($lb_valido)
		{// fecha de Contabilización de Depósitos
			$lb_valido=$this->uf_insert_config("MIS","OBRAS","FECHA_CONT_CONTRATOS",$ai_sob_feccontcon,"C");
		}
		if($lb_valido)
		{ 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la configuración del módulo Integrador.";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
      	return ($lb_valido);  
    }// end function uf_guardar_configuracion	
	//-----------------------------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obteneroperacion()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obteneroperacion
		//		   Access: public
		//	      Returns: operacion valor de la variable
		//	  Description: Función que obtiene que tipo de operación se va a ejecutar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenerexiste()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenerexiste
		//		   Access: public
		//	      Returns: existe valor de la variable
		//	  Description: Función que obtiene si existe el registro ó no
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   //--------------------------------------------------------------
	
   //--------------------------------------------------------------
   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_seleccionarcombo
		//		   Access: public
		//	    Arguments: as_valores  // valores que contiene el combo
		//				   as_seleccionado  // Valor que se debe seleccionar
		//				   aa_parametro  // arreglo de valores
		//				   li_total  // Valor toatl de valores
		//	  Description: Función que seleciona un valor de un combo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalor
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos obtener
		//				   as_valordefecto  // Valor por defecto de la variable
		//	      Returns: valor contenido de la variable
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   }// end function uf_obtenervalor
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenervariable($as_variable, $as_caso1, $as_caso2, $as_valor1, $as_valor2, $as_defecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervariable
		//		   Access: public
		//	    Arguments: as_variable  // Variable que deseamos obtener
		//			       as_caso1  // condición 1
		//			       as_caso2  // condición 2
		//			       as_valor1  // Valor si se cumple la condición 1
		//			       as_valor2  // Valor si se cumple la condición 2
		//	  		       as_defecto  // Valor por defecto de la variable
		//	      Returns: valor contenido de la variable
		//	  Description: Función que dependiendo del caso trae un valor u otro
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   }// end function uf_obtenervariable
   //--------------------------------------------------------------

  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formatonumerico
		//		   Access: public
		//	    Arguments: as_valor  // valor sin formato numérico
		//	      Returns: as_valor valor numérico formateado
		//	  Description: Función que le da formato a los valores numéricos que vienen de la BD
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenervalor_get($as_variable,$as_valordefecto)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalor_get
		//		   Access: public
		//	  Description: Función que obtiene que tipo de llamada del catalogo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   	}// end function uf_obtenervalor_get
	//-----------------------------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_asignarvalor($as_valor, $as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_asignarvalor
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos obtener
		//				   as_valordefecto  // Valor por defecto de la variable
		//	      Returns: valor contenido de la variable
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
   }// end function uf_asignarvalor
   //--------------------------------------------------------------
	
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
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 								Fecha Última Modificación : 
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
   }// end function uf_load_seguridad
   //----------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// PARA LA CONVERSIÓN MONETARIA
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT total ".
				"  FROM sigesp_cmp ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->uf_convertir_sigespcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_convertir_sigespcmp
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spgdtcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spgdtcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, procede_doc, documento, operacion, monto ".
				"  FROM spg_dt_cmp".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_spgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"]; 
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_procede_doc=$row["procede_doc"];
				$ls_documento=$row["documento"]; 
				$ls_operacion=$row["operacion"];
				$ld_monto=$row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spg_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spgdtcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgdtcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sc_cuenta, procede_doc, documento, debhab, monto ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->uf_convertir_scgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_sc_cuenta= $row["sc_cuenta"];
				$ls_procede_doc= $row["procede_doc"];
				$ls_documento= $row["documento"];
				$ls_debhab= $row["debhab"];
				$li_monto= $row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}
		return $lb_valido;
	}// end function uf_convertir_scgdtcmp
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spidtcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT spi_cuenta, procede_doc, documento, operacion, monto".
				"  FROM spi_dt_cmp".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_spidtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_spi_cuenta=$row["spi_cuenta"];
				$ls_procede_doc=$row["procede_doc"];
				$ls_documento=$row["documento"]; 
				$ls_operacion=$row["operacion"];
				$ld_monto=$row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spi_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spidtcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovbco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovbco
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT monto, monobjret, monret ".
				"  FROM scb_movbco".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovbco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_monto=$row["monto"];
				$ld_monobjret=$row["monobjret"];
				$ld_monret=$row["monret"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monobjret);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monret);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovbco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbdtmovbco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbdtmovbco
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT cod_pro, ced_bene, numsolpag, monsolpag ".
				"  FROM scb_dt_movbco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbdtmovbco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codpro=$row["cod_pro"];
				$ls_cedben=$row["ced_bene"];
				$ls_numsolpag=$row["numsolpag"];
				$ld_monsolpag=$row["monsolpag"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsolpagaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monsolpag);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedben);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsolpag");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsolpag);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_dt_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbdtmovbco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovbcoscg($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovbcoscg
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT scg_cuenta, debhab, codded, documento, monto, monobjret ".
				"  FROM scb_movbco_scg ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovbcoscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_scgcta=$row["scg_cuenta"];
				$ls_debhab=$row["debhab"];
				$ls_codded=$row["codded"];
				$ls_documento=$row["documento"];
			    $ld_monto=$row["monto"];
				$ld_monobjret=$row["monobjret"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monobjret);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_scgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovbcoscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovbcospg($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovbcospg
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro, spg_cuenta, documento, monto ".
				"  FROM scb_movbco_spg ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovbcospg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$aa_seguridad="";
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codestpro=$row["codestpro"];
				$ls_spgcta=$row["spg_cuenta"];
				$ls_documento=$row["documento"];
			    $ld_monto=$row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovbcospg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovbcospgop($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovbcospgop
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro, spg_cuenta, documento, monto, baseimp ".
				"  FROM scb_movbco_spgop ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovbcospgop ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codestpro=$row["codestpro"];
				$ls_spgcta=$row["spg_cuenta"];
				$ls_documento=$row["documento"];
			    $ld_monto=$row["monto"];
				$ld_baseimp=$row["baseimp"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","baseimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_baseimp);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spgop",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovbcospgop
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovbcospi($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovbcospi
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT spi_cuenta, documento, monto ".
				"  FROM scb_movbco_spi ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovbcospi ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_spicta    = $row["spi_cuenta"];
				$ls_documento = $row["documento"];
			    $ld_monto 	  = $row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spi",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovbcospi
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxpsolbanco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpsolbanco
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol, monto".
				"  FROM cxp_sol_banco".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_cxpsolbanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_numsol= $row["numsol"];
				$li_monto= $row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_sol_banco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpsolbanco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovcol($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovcol
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_numcol  // Número de Colocación
		//				   as_codope  // Código de Operación
		//				   as_estcol  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT monmovcol".
				"  FROM scb_movcol".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovcol ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
		        $ld_monto=$row["monmovcol"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monmovcolaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovcol
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovcolscg($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovcolscg
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_numcol  // Número de Colocación
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sc_cuenta, debhab, codded, monto ".
				"  FROM scb_movcol_scg".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovcolscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_scgcta=$row["sc_cuenta"];
				$ls_debhab=$row["debhab"];
				$ls_codded=$row["codded"];
			    $ld_monto=$row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_scgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovcolscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovcolspg($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovcolspg
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_numcol  // Número de Colocación
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro, spg_cuenta, monto ".
				"  FROM scb_movcol_spg ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovcol_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codestpro=$row["codestpro"];
				$ls_spgcta=$row["spg_cuenta"];
			    $ld_monto=$row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovcolspg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbmovcolspi($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbmovcolspg
		//		   Access: private
		//	    Arguments: as_codban  // Código del Banco
		//				   as_ctaban  // Número de cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_numcol  // Número de Colocación
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT spi_cuenta, monto ".
				"  FROM scb_movcol_spi".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_scbmovcol_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_spicta=$row["spi_cuenta"];
			    $ld_monto=$row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol_spi",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbmovcolspi
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprd($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprd
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Rcepción de Documentos
		//				   as_codtipdoc  // código del tipo de documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT montotdoc, mondeddoc, moncardoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_cedbene."' ".
				"   AND cod_pro='".$as_codpro."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_cxprd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$li_montotdoc=$row["montotdoc"];
				$li_mondeddoc=$row["mondeddoc"];
				$li_moncardoc=$row["moncardoc"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotdocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotdoc);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondeddocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mondeddoc);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncardocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncardoc);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprd
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprdscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprdscg
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Rcepción de Documentos
		//				   as_codtipdoc  // código del tipo de documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT procede_doc, numdoccom, debhab, sc_cuenta, monto".
				"  FROM cxp_rd_scg".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_cedbene."' ".
				"   AND cod_pro='".$as_codpro."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_cxprdscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_procededoc= $row["procede_doc"];
				$ls_numdoccom= $row["numdoccom"];
				$ls_debhab= $row["debhab"];
				$ls_sccuenta= $row["sc_cuenta"];
				$li_monto= $row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoccom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sccuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprdscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprdspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprdspg
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Rcepción de Documentos
		//				   as_codtipdoc  // código del tipo de documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT procede_doc, numdoccom, codestpro, spg_cuenta, monto".
				"  FROM cxp_rd_spg".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_cedbene."' ".
				"   AND cod_pro='".$as_codpro."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_cxprdspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_procededoc= $row["procede_doc"];
				$ls_numdoccom= $row["numdoccom"];
				$ls_codestpro= $row["codestpro"];
				$ls_spgcuenta= $row["spg_cuenta"];
				$li_monto= $row["monto"];
				
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoccom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprdspg
	//-----------------------------------------------------------------------------------------------------------------------------

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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>