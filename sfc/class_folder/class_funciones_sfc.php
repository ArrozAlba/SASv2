<?php
class class_funciones_sfc
{
	 var $io_funcion;
	 var $io_msgc;
	 var $io_sql;
	 var $datoemp;
	 var $io_msg;
	//-----------------------------------------------------------------------------------------------------------------------------------
 	function class_funciones_sfc()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: class_funciones_sfc
		//		   Access: public
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Zulheymar Rodríguez
		// Fecha Creación: 07/05/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_funcion = new class_funciones();
		$io_include       = new sigesp_include();
		$io_connect       = $io_include->uf_conectar();
		$this->io_sql     = new class_sql($io_connect);
		$this->datoemp    = $_SESSION["la_empresa"];
		$this->io_msg     = new class_mensajes();		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
 	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obteneroperacion()
	{
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
   
   
   function uf_generer_consecutivo($ls_tabla,$ls_campo,$ls_prefijo,$ls_serie,$li_longitud)
   {
   		$ls_codtienda=$_SESSION["ls_codtienda"];
		$ls_codcaja  =$_SESSION["ls_codcaj"];
		if($ls_serie!="")
		{
			$ls_serie=$this->io_funcion->uf_cerosderecha($ls_serie,5);
			$li_lon_pref_ser=strlen($ls_prefijo."-".$ls_serie);
		}
		else
		{
			$li_lon_pref_ser=0;
		}
		
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codigo="";
		$ls_aux="";
		$ls_filtro="";
		if($ls_prefijo!="")
		{
			$ls_filtro=" AND ".$ls_campo." like '".$ls_prefijo."-".$ls_serie."%' ";
		}
		if($ls_tabla=="sim_orden_entrega")
		{
			$ls_aux=" AND cod_caja='".$ls_codcaja."' ";
		}
		
		$ls_sql="SELECT substr(".$ls_campo.",$li_lon_pref_ser+1,".$li_longitud.") as campo FROM ".$ls_tabla.
				" WHERE  codtiend='".$ls_codtienda."' ".$ls_aux.$ls_filtro.
				" ORDER BY ".$ls_campo." DESC LIMIT 1";
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$this->io_msgc="Error en uf_select_orden ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print  $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$ls_cadena=$row["campo"];
				settype($ls_cadena,"integer");
				$li_valor=$ls_cadena+1;
				$li_ceros=$li_longitud-$li_lon_pref_ser;
				if($ls_prefijo!="")
				{
					$ls_codigo=$ls_prefijo."-".$ls_serie.$this->io_funcion->uf_cerosizquierda($li_valor,$li_ceros);
				}
				else
				{
					$ls_codigo=$this->io_funcion->uf_cerosizquierda($li_valor,$li_ceros);
				}
			}
			else
			{
				$li_valor=1;
				$li_ceros=$li_longitud-$li_lon_pref_ser;				
				if($ls_prefijo!="")
				{
					$ls_codigo=$ls_prefijo."-".$ls_serie.$this->io_funcion->uf_cerosizquierda($li_valor,$li_ceros);
				}
				else
				{
					$ls_codigo=$this->io_funcion->uf_cerosizquierda($li_valor,$li_ceros);
				}
			}			
			$this->io_sql->free_result($rs_datauni);
		}
		return $ls_codigo;	
   }
   
   
}
?>