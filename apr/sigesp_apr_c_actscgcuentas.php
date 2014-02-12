<?php 
class sigesp_apr_c_actscgcuentas
{
	//-----------------------------------------------------------------------------------------------------------------------------------
    function sigesp_apr_c_actscgcuentas()
    {
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_actualizar_cuentas_result_".$ld_fecha.".txt";
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
		$this->io_seguridad= new sigesp_c_seguridad();
		
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
		$this->io_sql_destino= new class_sql($io_conexion_destino);
		$this->io_function_db= new class_funciones_db($io_conexion_destino);					
    }//end sigesp_apr_c_actscgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_load_cuentas(&$ai_totrows,&$ao_object)
	{   
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_load_cuentas
		// 	   Access:  public
		//  Arguments:  $ai_totrows----> Total de Filas,
		//              $ao_object---->  Arreglo de Objetos,
		//	  Returns:  $lb_valido ---> Boolean
		//Description:  Función que carga las cuentas contables utilizadas
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT c_resultad as sc_cuentaorigen".
				"  FROM sigesp_empresa ".
				" WHERE trim(c_resultad)<>'' ".
				"UNION ".
				"SELECT c_resultan as sc_cuentaorigen".
				"  FROM sigesp_empresa ".
				" WHERE trim(c_resultan)<>'' ".
				"UNION ".
				"SELECT scctaben as sc_cuentaorigen".
				"  FROM sigesp_empresa ".
				" WHERE trim(scctaben)<>'' ".
				"UNION ".
				"SELECT c_financiera as sc_cuentaorigen".
				"  FROM sigesp_empresa ".
				" WHERE trim(c_financiera)<>'' ".
				"UNION ".
				"SELECT c_fiscal as sc_cuentaorigen".
				"  FROM sigesp_empresa ".
				" WHERE trim(c_fiscal)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM rpc_proveedor ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM rpc_beneficiario ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM saf_activo ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM sigesp_deducciones ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM siv_articulo ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM scb_ctabanco ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM scb_colocacion ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT sc_cuenta as sc_cuentaorigen".
				"  FROM sno_beneficiario ".
				" WHERE trim(sc_cuenta)<>'' ".
				"UNION ".
				"SELECT cueconnom as sc_cuentaorigen".
				"  FROM sno_nomina ".
				" WHERE trim(cueconnom)<>'' ".
				"UNION ".
				"SELECT cueaboper as sc_cuentaorigen".
				"  FROM sno_personalnomina ".
				" WHERE trim(cueaboper)<>'' ".
				"UNION ".
				"SELECT cueconcon as sc_cuentaorigen".
				"  FROM sno_concepto ".
				" WHERE trim(cueconcon)<>'' ".
				"UNION ".
				"SELECT cueconpatcon as sc_cuentaorigen".
				"  FROM sno_concepto ".
				" WHERE trim(cueconpatcon)<>'' ".
				"UNION ".
				"SELECT cueconnom as sc_cuentaorigen".
				"  FROM sno_hnomina ".
				" WHERE trim(cueconnom)<>'' ".
				"UNION ".
				"SELECT cueaboper as sc_cuentaorigen".
				"  FROM sno_hpersonalnomina ".
				" WHERE trim(cueaboper)<>'' ".
				"UNION ".
				"SELECT cueconcon as sc_cuentaorigen".
				"  FROM sno_hconcepto ".
				" WHERE trim(cueconcon)<>'' ".
				"UNION ".
				"SELECT cueconpatcon as sc_cuentaorigen".
				"  FROM sno_hconcepto ".
				" WHERE trim(cueconpatcon)<>'' ".
				" GROUP BY sc_cuentaorigen ".
				" ORDER BY sc_cuentaorigen ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Cuentas Contables.\r\n".$this->io_sql_origen->message."";
			$this->io_mensajes->message($ls_cadena); 
		}
		else
		{
			$ai_totrows=0;
			while(($row=$this->io_sql_origen->fetch_row($io_recordset))&&$lb_valido)
			{
				$ai_totrows++;
				$ls_sccuentao=$this->io_validacion->uf_valida_texto($row["sc_cuentaorigen"],0,25,"");
				$ls_sccuentad="";
				$lb_valido=$this->uf_load_cuentadestino($ls_sccuentao,&$ls_sccuentad);
				
				$ao_object[$ai_totrows][1]="<input name=txtsccuentaant".$ai_totrows." type=text id=txtsccuentaant".$ai_totrows." class=sin-borde size=40 maxlength=25 value='".$ls_sccuentao."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtsccuentaact".$ai_totrows." type=text id=txtsccuentaact".$ai_totrows." class=sin-borde size=50 maxlength=25 value='".$ls_sccuentad."' readonly> ".
										   "<a href=javascript:ue_search_scg('".$ai_totrows."');><img src='../shared/imagebank/tools20/buscar.gif' alt='Buscar' width='20' height='20' border='0'></a>";
			}
		}		
		return $lb_valido;
	}//end uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_table()
	{
		$lb_valido=true;
		$ls_sql="";
		$lb_existe = $this->io_function_db->uf_select_table('apr_contable');
		if (!$lb_existe)
		{
			// CUENTA CONTABLES ORIGEN Y DESTINO
			switch($_SESSION["ls_gestor_destino"])
			{
				case "MYSQL":
				$ls_sql="CREATE TABLE  apr_contable ( ".
						"  scg_cuentaorigen varchar(25) NOT NULL, ".
						"  scg_cuentadestino varchar(25) NOT NULL, ".
						"  PRIMARY KEY  (scg_cuentaorigen) ".
						") ENGINE=InnoDB;";
				break;
				
				case "POSTGRE":
				$ls_sql="CREATE TABLE  apr_contable ( ".
						"  scg_cuentaorigen varchar(25) NOT NULL, ".
						"  scg_cuentadestino varchar(25) NOT NULL, ".
						"  PRIMARY KEY  (scg_cuentadestino));";
				break;
			}
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if($li_row===false)
			{ 
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	}//end uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas
		//		   Access: private
		//	    Arguments: as_codnom  // Código de la nómina
		//	    		   ai_anocurnom  // año en curso
		//	    		   as_peractnom  // período Actual de la nómina
		//	      Returns: lb_valido True si se ejecutaron los delete ó False si hubo error en los delete
		//	  Description: Funcion que elimina todos los registros del personal con el viejo código
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM apr_contable ";
		$lb_valido=$this->uf_procesar_sql($ls_sql);
		$li_total=$_POST["totrow"];
		for($li_i=1;($li_i<=$li_total)&&($lb_valido);$li_i++)
		{
			$ls_sccuentaant=trim($_POST["txtsccuentaant".$li_i]);
			$ls_sccuentaact=trim($_POST["txtsccuentaact".$li_i]);
			if($ls_sccuentaact!="")
			{
				$ls_sql="INSERT INTO apr_contable (scg_cuentaorigen, scg_cuentadestino) VALUES ('".$ls_sccuentaant."','".$ls_sccuentaact."') ";
				$lb_valido=$this->uf_procesar_sql($ls_sql);
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Asocio la cuenta Contable Origen ".$ls_sccuentaant." con la Cuenta Contable Destino".$ls_sccuentaact;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas
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
		return $lb_valido;
	}// end function uf_procesar_sql
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_load_cuentadestino($as_cuentaorigen,&$as_cuentadestino)
	{   
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_load_cuentadestino
		// 	   Access:  public
		//  Arguments:  $as_cuentaorigen----> Total de Filas,
		//              $as_cuentaorigen---->  Arreglo de Objetos,
		//	  Returns:  $lb_valido ---> Boolean
		//Description:  Función que carga las cuentas contables utilizadas
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT scg_cuentadestino ".
				"  FROM apr_contable ".
				" WHERE scg_cuentaorigen='".$as_cuentaorigen."' ";
		$io_recordset=$this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error en la base de datos destino.\r\n".$this->io_sql_destino->message."";
			$this->io_mensajes->message($ls_cadena); 
		}
		else
		{
			while(($row=$this->io_sql_destino->fetch_row($io_recordset)))
			{
				$as_cuentadestino=$this->io_validacion->uf_valida_texto($row["scg_cuentadestino"],0,25,"");
			}
		}	
		return $lb_valido;
	}//end uf_load_cuentadestino
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>