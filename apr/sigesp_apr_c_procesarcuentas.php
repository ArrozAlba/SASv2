<?php 
class sigesp_apr_c_procesarcuentas
{
	//-----------------------------------------------------------------------------------------------------------------------------------
    function sigesp_apr_c_procesarcuentas()
    {
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_procesar_cuentas_result_".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
		$this->ls_database_source=$_SESSION["ls_database"];
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_funciones_db.php"); 
		require_once("class_folder/class_validacion.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");/////agregado el 06/12/2007
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		
		$this->ls_database_source=$_SESSION["ls_database"];
		$this->ls_database_target=$_SESSION["ls_data_des"];
		$this->io_mensajes=new class_mensajes();		
		$this->io_funciones=new class_funciones();
		$this->io_validacion=new class_validacion();
		$this->io_fecha=new class_fecha();
		$io_conect=new sigesp_include();
		$io_conexion_origen=$io_conect->uf_conectar();
		$io_conexion_destino=$io_conect->uf_conectar($this->ls_database_target);
		$this->io_sql_origen=new class_sql($io_conexion_origen);
		$this->io_sql_destino=new class_sql($io_conexion_destino);
		$this->io_function_db=new class_funciones_db($io_conexion_destino);					
		$this->io_seguridad= new sigesp_c_seguridad();
    }//end sigesp_apr_c_procesarcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_cuentas_scg($aa_seguridad)
	{   
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_procesar_cuentas
		// 	   Access:  public
		//  Arguments:  
		//Description:  Función que actualiza las cuentas contables 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT scg_cuentaorigen, scg_cuentadestino ".
				"  FROM apr_contable ";
		$io_recordset=$this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Cuentas Contables.\r\n".$this->io_sql_origen->message."";
			$this->io_mensajes->message($ls_cadena); 
		}
		else
		{
			while(($row=$this->io_sql_destino->fetch_row($io_recordset))&&$lb_valido)
			{
				$ls_sccuentao=$this->io_validacion->uf_valida_texto($row["scg_cuentaorigen"],0,25,"");
				$ls_sccuentad=$this->io_validacion->uf_valida_texto($row["scg_cuentadestino"],0,25,"");

				$ls_sql="UPDATE sigesp_empresa ".
						"	SET c_resultad='".$ls_sccuentad."' ".
						" WHERE c_resultad='".$ls_sccuentao."' ";
				$lb_valido=$this->uf_procesar_sql($ls_sql);
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_empresa ".
							"	SET c_resultan='".$ls_sccuentad."' ".
							" WHERE c_resultan='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_empresa ".
							"	SET scctaben='".$ls_sccuentad."' ".
							" WHERE scctaben='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_empresa ".
							"	SET c_financiera='".$ls_sccuentad."' ".
							" WHERE c_financiera='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_empresa ".
							"	SET c_fiscal='".$ls_sccuentad."' ".
							" WHERE c_fiscal='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE rpc_proveedor ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE rpc_beneficiario ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE saf_activo ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_deducciones ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE siv_articulo ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE scb_ctabanco ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE scb_colocacion ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_beneficiario ".
							"	SET sc_cuenta='".$ls_sccuentad."' ".
							" WHERE sc_cuenta='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_nomina ".
							"	SET cueconnom='".$ls_sccuentad."' ".
							" WHERE cueconnom='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hnomina ".
							"	SET cueconnom='".$ls_sccuentad."' ".
							" WHERE cueconnom='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_personalnomina ".
							"	SET cueaboper='".$ls_sccuentad."' ".
							" WHERE cueaboper='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hpersonalnomina ".
							"	SET cueaboper='".$ls_sccuentad."' ".
							" WHERE cueaboper='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_concepto ".
							"   SET cueconcon='".$ls_sccuentad."' ".
							" WHERE cueconcon='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconcepto ".
							"	SET cueconcon='".$ls_sccuentad."' ".
							" WHERE cueconcon='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_concepto ".
							"	SET cueconpatcon='".$ls_sccuentad."' ".
							" WHERE cueconpatcon='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconcepto ".
							"	SET cueconpatcon='".$ls_sccuentad."' ".
							" WHERE cueconpatcon='".$ls_sccuentao."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Proceso la actualización de las cuentas contables";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}		
		return $lb_valido;
	}//end uf_procesar_cuentas_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cuentas_spg($aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_procesar_cuentas_spg
		// 	   Access:  public
		//  Arguments:  
		//Description:  Función que actualiza las cuentas presupuestarias
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT spg_cuentaorigen, spg_cuentadestino ".
				"  FROM apr_presupuestario ";
		$io_recordset=$this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Cuentas Presupuestaria.\r\n".$this->io_sql_origen->message."";
			$this->io_mensajes->message($ls_cadena); 
		}
		else
		{
			while(($row=$this->io_sql_destino->fetch_row($io_recordset))&&$lb_valido)
			{
				$ls_spgcuentaant=$this->io_validacion->uf_valida_texto($row["spg_cuentaorigen"],0,25,"");
				$ls_spgcuentaact=$this->io_validacion->uf_valida_texto($row["spg_cuentadestino"],0,25,"");

				$ls_sql="UPDATE saf_catalogo ".
						"   SET spg_cuenta='".$ls_spgcuentaact."'".
						" WHERE spg_cuenta='".$ls_spgcuentaant."'";
				$lb_valido=$this->uf_procesar_sql($ls_sql);
				if($lb_valido)
				{
					$ls_sql="UPDATE saf_activo".
							"   SET spg_cuenta_act='".$ls_spgcuentaact."'".
							" WHERE spg_cuenta_act='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE saf_activo".
							"   SET spg_cuenta_dep='".$ls_spgcuentaact."'".
							" WHERE spg_cuenta_dep='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_cargos".
							"   SET spg_cuenta='".$ls_spgcuentaact."'".
							" WHERE spg_cuenta='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE siv_articulo".
							"   SET spg_cuenta='".$ls_spgcuentaact."'".
							" WHERE spg_cuenta='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sep_conceptos".
							"   SET spg_cuenta='".$ls_spgcuentaact."'".
							" WHERE spg_cuenta='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE soc_servicios".
							"   SET spg_cuenta='".$ls_spgcuentaact."'".
							" WHERE spg_cuenta='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_concepto".
							"   SET cueprecon='".$ls_spgcuentaact."'".
							" WHERE cueprecon='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_concepto".
							"   SET cueprepatcon='".$ls_spgcuentaact."'".
							" WHERE cueprepatcon='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconcepto".
							"   SET cueprecon='".$ls_spgcuentaact."'".
							" WHERE cueprecon='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconcepto".
							"   SET cueprepatcon='".$ls_spgcuentaact."'".
							" WHERE cueprepatcon='".$ls_spgcuentaant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Proceso la actualización de las cuentas presupuestarias";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		return $lb_valido;
	}// end function uf_procesar_cuentas_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_estructuras($aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_procesar_cuentas_spg
		// 	   Access:  public
		//  Arguments:  
		//Description:  Función que actualiza las cuentas presupuestarias
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT ep1origen, ep2origen, ep3origen, ep4origen, ep5origen, ep1destino, ep2destino, ep3destino, ep4destino, ep5destino ".
				"  FROM apr_estructurapresupuestaria ";
		$io_recordset=$this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Estructuras Presupuestaria.\r\n".$this->io_sql_destino->message."";
			$this->io_mensajes->message($ls_cadena); 
		}
		else
		{
			while(($row=$this->io_sql_destino->fetch_row($io_recordset))&&$lb_valido)
			{
				$ls_ep1ant=$this->io_validacion->uf_valida_texto($row["ep1origen"],0,20,"");
				$ls_ep2ant=$this->io_validacion->uf_valida_texto($row["ep2origen"],0,6,"");
				$ls_ep3ant=$this->io_validacion->uf_valida_texto($row["ep3origen"],0,3,"");
				$ls_ep4ant=$this->io_validacion->uf_valida_texto($row["ep4origen"],0,2,"");
				$ls_ep5ant=$this->io_validacion->uf_valida_texto($row["ep5origen"],0,2,"");
				$ls_ep1act=$this->io_validacion->uf_valida_texto($row["ep1destino"],0,20,"");
				$ls_ep2act=$this->io_validacion->uf_valida_texto($row["ep2destino"],0,6,"");
				$ls_ep3act=$this->io_validacion->uf_valida_texto($row["ep3destino"],0,3,"");
				$ls_ep4act=$this->io_validacion->uf_valida_texto($row["ep4destino"],0,2,"");
				$ls_ep5act=$this->io_validacion->uf_valida_texto($row["ep5destino"],0,2,"");

				$ls_sql="UPDATE saf_activo ".
						"   SET codestpro1='".$ls_ep1act."',".
						"       codestpro2='".$ls_ep2act."', ".
						"       codestpro3='".$ls_ep3act."', ".
						"       codestpro4='".$ls_ep4act."', ".
						"       codestpro5='".$ls_ep5act."' ".
						" WHERE codestpro1='".$ls_ep1ant."' ".
						"   AND codestpro2='".$ls_ep2ant."' ".
						"   AND codestpro3='".$ls_ep3ant."' ".
						"   AND codestpro4='".$ls_ep4ant."' ".
						"   AND codestpro5='".$ls_ep5ant."' ";
				$lb_valido=$this->uf_procesar_sql($ls_sql);
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_asignacioncargo ".
							"   SET codproasicar='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE codproasicar='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sigesp_cargos ".
							"   SET codestpro='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE codestpro='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_concepto ".
							"   SET codpro='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE codpro='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconcepto ".
							"   SET codpro='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE codpro='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_proyecto ".
							"   SET estproproy='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE estproproy='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hproyecto ".
							"   SET estproproy='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE estproproy='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_unidadadmin ".
							"   SET codprouniadm='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE codprouniadm='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hunidadadmin ".
							"   SET codprouniadm='".$ls_ep1act.$ls_ep2act.$ls_ep3act.$ls_ep4act.$ls_ep5act."'".
							" WHERE codprouniadm='".$ls_ep1ant.$ls_ep2ant.$ls_ep3ant.$ls_ep4ant.$ls_ep5ant."'";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE spg_unidadadministrativa ".
							"   SET codestpro1='".$ls_ep1act."',".
							"       codestpro2='".$ls_ep2act."', ".
							"       codestpro3='".$ls_ep3act."', ".
							"       codestpro4='".$ls_ep4act."', ".
							"       codestpro5='".$ls_ep5act."' ".
							" WHERE codestpro1='".$ls_ep1ant."' ".
							"   AND codestpro2='".$ls_ep2ant."' ".
							"   AND codestpro3='".$ls_ep3ant."' ".
							"   AND codestpro4='".$ls_ep4ant."' ".
							"   AND codestpro5='".$ls_ep5ant."' ";
					$lb_valido=$this->uf_procesar_sql($ls_sql);
				}
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Proceso la actualización de las estructuras presupuestarias";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		return $lb_valido;
	}// end function uf_procesar_estructuras
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_sql($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_sql
		//		   Access: private
		//	    Arguments: as_sql  // Sentencia SQL que se quiere ejecutar
		//	      Returns: lb_valido True si se ejecuto el sql ó False si hubo error en el sql
		//	  Description: Funcion que ejecuta un sql
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_row=$this->io_sql_destino->execute($as_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$ls_cadena="Error en la base de datos destino.\r\n".$this->io_sql_destino->message."";
			$this->io_mensajes->message($ls_cadena); 
		}
		else
		{
			$ls_cadena="---------------------------------------------------------------.\r\n";
			$ls_cadena=$ls_cadena.$as_sql."\r\n";
			$ls_cadena=$ls_cadena."---------------------------------------------------------------.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}		
		}
		return $lb_valido;
	}// end function uf_procesar_sql
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>