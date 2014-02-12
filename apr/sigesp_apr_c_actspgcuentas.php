<?php 
class sigesp_apr_c_actspgcuentas
{
	//-----------------------------------------------------------------------------------------------------------------------------------
    function sigesp_apr_c_actspgcuentas()
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
		
		$this->ls_database_source= $_SESSION["ls_database"];
		$this->ls_database_target= $_SESSION["ls_data_des"];
		$this->io_mensajes= new class_mensajes();		
		$this->io_funciones= new class_funciones();
		$this->io_validacion= new class_validacion();
		$this->io_fecha= new class_fecha();
		$io_conect= new sigesp_include();
		$io_conexion_origen= $io_conect->uf_conectar();
		$io_conexion_destino= $io_conect->uf_conectar($this->ls_database_target);
		$this->io_sql_origen= new class_sql($io_conexion_origen);
		$this->io_sql_destino= new class_sql($io_conexion_destino);
		$this->io_function_db=new class_funciones_db($io_conexion_destino);					
		$this->io_seguridad= new sigesp_c_seguridad();
    }
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
		$ls_sql="SELECT spg_cuenta ".
				"  FROM saf_catalogo ".
				" WHERE trim(spg_cuenta)<>'' ".
				"UNION ".
				"SELECT spg_cuenta_act as spg_cuenta ".
				"  FROM saf_activo ".
				" WHERE trim(spg_cuenta_act)<>'' ".
				"UNION ".
				"SELECT spg_cuenta_dep as spg_cuenta ".
				"  FROM saf_activo ".
				" WHERE trim(spg_cuenta_dep)<>'' ".
				"UNION ".
				"SELECT spg_cuenta ".
				"  FROM sigesp_cargos ".
				" WHERE trim(spg_cuenta)<>'' ".
				"UNION ".
				"SELECT spg_cuenta ".
				"  FROM siv_articulo ".
				" WHERE trim(spg_cuenta)<>'' ".
				"UNION ".
				"SELECT spg_cuenta ".
				"  FROM sep_conceptos ".
				" WHERE trim(spg_cuenta)<>'' ".
				"UNION ".
				"SELECT spg_cuenta ".
				"  FROM soc_servicios ".
				" WHERE trim(spg_cuenta)<>'' ".
				"UNION ".
				"SELECT cueprecon as spg_cuenta ".
				"  FROM sno_concepto ".
				" WHERE trim(cueprecon)<>'' ".
				"UNION ".
				"SELECT cueprepatcon as spg_cuenta ".
				"  FROM sno_concepto ".
				" WHERE trim(cueprepatcon)<>'' ".
				"UNION ".
				"SELECT cueprecon as spg_cuenta ".
				"  FROM sno_hconcepto ".
				" WHERE trim(cueprecon)<>'' ".
				"UNION ".
				"SELECT cueprepatcon as spg_cuenta ".
				"  FROM sno_hconcepto ".
				" WHERE trim(cueprepatcon)<>'' ".
				" GROUP BY spg_cuenta ".
				" ORDER BY spg_cuenta ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Cuentas Presupuestarias.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$ai_totrows=0;
			while(($row=$this->io_sql_origen->fetch_row($io_recordset))&&$lb_valido)
			{
				$ai_totrows++;
				$ls_cuentadestino="";
				$ls_spgcuenta=$this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
				$this->uf_load_cuentadestino($ls_spgcuenta,&$ls_cuentadestino);
				$ao_object[$ai_totrows][1]="<input name=txtspgcuentaant".$ai_totrows." type=text id=txtspgcuentaant".$ai_totrows." class=sin-borde size=30 maxlength=25 value='".$ls_spgcuenta."' style=text-align:center readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtspgcuentaact".$ai_totrows." type=text id=txtspgcuentaact".$ai_totrows." class=sin-borde size=30 maxlength=25 value='".$ls_cuentadestino."' style=text-align:center readonly><a href='javascript:ue_catalogo_spgcuentas(".$ai_totrows.");'><img src='../shared/imagebank/tools/buscar.gif' width='15' height='15' border='0' title='Agregar Compromisos'></a>";
			}
		}		
		return $lb_valido;
	}//fin uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_table()
	{
		$lb_valido=true;
		$ls_sql="";
		$lb_existe = $this->io_function_db->uf_select_table('apr_presupuestario');
		if (!$lb_existe)
		{
			// CUENTA PRESUPUESTARIAS ORIGEN Y DESTINO
			switch($_SESSION["ls_gestor_destino"])
			{
				case "MYSQL":
				$ls_sql="CREATE TABLE  apr_presupuestario ( ".
						"  spg_cuentaorigen varchar(25) NOT NULL, ".
						"  spg_cuentadestino varchar(25) NOT NULL, ".
						"  PRIMARY KEY  (spg_cuentaorigen) ".
						") ENGINE=InnoDB;";
				break;
				
				case "POSTGRE":
				$ls_sql="CREATE TABLE  apr_presupuestario ( ".
						"  spg_cuentaorigen varchar(25) NOT NULL, ".
						"  spg_cuentadestino varchar(25) NOT NULL, ".
						"  PRIMARY KEY  (spg_cuentaorigen));";
				break;
			}
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if($li_row===false)
			{ 
				 $lb_valido=false;
			}
		}
        return $lb_valido;	
	}//end uf_create_table
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
		$ls_sql="SELECT spg_cuentadestino ".
				"  FROM apr_presupuestario ".
				" WHERE spg_cuentaorigen='".$as_cuentaorigen."' ";
		$io_recordset=$this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Cuentas Contables.\r\n".$this->io_sql_destino->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			while(($row=$this->io_sql_destino->fetch_row($io_recordset)))
			{
				$as_cuentadestino=$this->io_validacion->uf_valida_texto($row["spg_cuentadestino"],0,25,"");
			}
		}		
		return $lb_valido;
	}//end uf_load_cuentadestino
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas()
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
		$ls_sql="DELETE FROM apr_presupuestario ";
		$lb_valido=$this->uf_procesar_sql($ls_sql);
		$li_total=$_POST["totrow"];
		for($li_i=1;($li_i<=$li_total)&&($lb_valido);$li_i++)
		{
			$ls_spgcuentaant=trim($_POST["txtspgcuentaant".$li_i]);
			$ls_spgcuentaact=trim($_POST["txtspgcuentaact".$li_i]);
			if($ls_spgcuentaact!="")
			{
				$ls_sql="INSERT INTO apr_presupuestario (spg_cuentaorigen, spg_cuentadestino) VALUES ('".$ls_spgcuentaant."','".$ls_spgcuentaact."') ";
				$lb_valido=$this->uf_procesar_sql($ls_sql);
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Asocio la cuenta Presupuestaria Origen ".$ls_spgcuentaant." con la Cuenta Presupuestaria Destino".$ls_spgcuentaact;
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
        	print $this->io_sql_destino->message; 
		}
		return $lb_valido;
	}// end function uf_procesar_sql
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>