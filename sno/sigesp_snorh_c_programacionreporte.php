<?PHP
class sigesp_snorh_c_programacionreporte
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_programacionreporte()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_programacionreporte
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_snorh_c_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_programacionreporte($as_codrep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_programacionreporte
		//		   Access: private
		//	    Arguments: as_codrep  // código del reporte
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la programación de reporte está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codrep ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codrep='".$as_codrep."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_select_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0406($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0406
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // código del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_dedicacion.codded, sno_tipopersonal.codtipper ".
				"  FROM sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_dedicacion.codemp='".$this->ls_codemp."' ".
				"   AND sno_dedicacion.codded<>'000'".
				"   AND sno_tipopersonal.codtipper<>'0000' ".
				"   AND sno_dedicacion.codemp = sno_tipopersonal.codemp ".
				"	AND sno_dedicacion.codded = sno_tipopersonal.codded ".
				" ORDER BY sno_dedicacion.codded,sno_tipopersonal.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0406 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_codigo="";
			$this->io_sql->begin_transaction();
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
							"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
							"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
							"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
							"			  0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0406 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
					}
				}
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0406 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0406
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0506($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0506
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // código del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_dedicacion.codded, sno_tipopersonal.codtipper ".
				"  FROM sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_dedicacion.codemp='".$this->ls_codemp."' ".
				"   AND sno_dedicacion.codded<>'000'".
				"   AND sno_tipopersonal.codtipper<>'0000' ".
				"   AND sno_dedicacion.codemp = sno_tipopersonal.codemp ".
				"	AND sno_dedicacion.codded = sno_tipopersonal.codded ".
				" ORDER BY sno_dedicacion.codded,sno_tipopersonal.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0506 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_codigo="";
			$this->io_sql->begin_transaction();
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
							"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
							"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
							"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
							"			  0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
					}
				}
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0506
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0711($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0711
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // código del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_dedicacion.codded, sno_tipopersonal.codtipper ".
				"  FROM sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_dedicacion.codemp='".$this->ls_codemp."' ".
				"   AND sno_dedicacion.codded<>'000'".
				"   AND sno_tipopersonal.codtipper<>'0000' ".
				"   AND sno_dedicacion.codemp = sno_tipopersonal.codemp ".
				"	AND sno_dedicacion.codded = sno_tipopersonal.codded ".
				" ORDER BY sno_dedicacion.codded,sno_tipopersonal.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_codigo="";
			$this->io_sql->begin_transaction();
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
							"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
							"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
							"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
							"			  0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
					}
				}
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0711
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0712($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0712
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // código del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_ded=1;($li_ded<=3)&&($lb_valido);$li_ded++)
		{
			$ls_codded=str_pad($li_ded,3,"0",0);
			$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
					"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
					"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
					"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
					"			  0,0,0,0,0,0,0,0,0,0,0,0)";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0712 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
			}
			for($li_tip=1;($li_tip<=3)&&($lb_valido);$li_tip++)
			{
				$ls_codtipper=str_pad($li_tip,4,"0",0);
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_insert_programacionreporte0712 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
			}
		}
		if($lb_valido)
		{	
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0712
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_programacionreporte($as_reporte,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_programacionreporte
		//		   Access: public (sigesp_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // código del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codrep, codded, codtipper, numcar, numcarf, numcarm, numcarv, ".
		        "       totasi, dismonasi, monene, monfeb, monmar, monabr, monmay, ".
				"		monjun, monjul, monago, monsep, monoct, monnov, mondic, carene, carfeb, carmar, carabr, carmay, carjun, ".
				"		carjul, carago, carsep, caroct, carnov, cardic, ".
				"       carenef, carfebf, carmarf, carabrf, carmayf, carjunf, ".
				"		carjulf, caragof, carsepf, caroctf, carnovf, cardicf, ".
				"       carenem, carfebm, carmarm, carabrm, carmaym, carjunm, ".
				"		carjulm, caragom, carsepm, caroctm, carnovm, cardicm, ".
				"		(SELECT desded FROM sno_dedicacion ".
				"		  WHERE sno_programacionreporte.codemp = sno_dedicacion.codemp ".
				"			AND sno_programacionreporte.codded = sno_dedicacion.codded) as desded,".
				"		(SELECT destipper FROM sno_tipopersonal ".
				"		  WHERE sno_programacionreporte.codemp = sno_tipopersonal.codemp ".
				"			AND sno_programacionreporte.codded = sno_tipopersonal.codded ".
				"			AND sno_programacionreporte.codtipper = sno_tipopersonal.codtipper) as destipper ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codrep='".$as_reporte."' ".
				" ORDER BY sno_programacionreporte.codded,sno_programacionreporte.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_load_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			$ls_codigo="";
			$ls_denominacion="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codded=$row["codded"];
				$ls_desded=$row["desded"];
				$ls_codtipper=$row["codtipper"];
				$ls_destipper=$row["destipper"];
				if($as_reporte=="0712")
				{
					switch($ls_codded)
					{
						case "001":
							$ls_desded="DOCENTE";
							break;
						case "002":
							$ls_desded="ADMINISTRATIVO";
							break;
						case "003":
							$ls_desded="OBRERO";
							break;
					}
					switch($ls_codtipper)
					{
						case "0001":
							$ls_destipper="JUBILADO";
							break;
						case "0002":
							$ls_destipper="PENSIONADO";
							break;
						case "0003":
							$ls_destipper="ASIGNACIÓN A SOBREVIVIENTE";
							break;
					}
				}
				$li_numcar=$row["numcar"];
				$li_numcarf=$row["numcarf"];
				$li_numcarm=$row["numcarm"];
				$li_numcarv=$row["numcarv"];
				$li_totasi=$this->io_fun_nomina->uf_formatonumerico($row["totasi"]);
				$li_dismonasi=$row["dismonasi"];
				$ls_distrbucionauto="";
				$ls_distrbucionmanu="";
				if($li_dismonasi==0)// Automática
				{
					$ls_distrbucionauto="Selected";
				}
				else
				{
					$ls_distrbucionmanu="Selected";
				}
				$li_monene=$this->io_fun_nomina->uf_formatonumerico($row["monene"]);
				$li_monfeb=$this->io_fun_nomina->uf_formatonumerico($row["monfeb"]);
				$li_monmar=$this->io_fun_nomina->uf_formatonumerico($row["monmar"]);
				$li_monabr=$this->io_fun_nomina->uf_formatonumerico($row["monabr"]);
				$li_monmay=$this->io_fun_nomina->uf_formatonumerico($row["monmay"]);
				$li_monjun=$this->io_fun_nomina->uf_formatonumerico($row["monjun"]);
				$li_monjul=$this->io_fun_nomina->uf_formatonumerico($row["monjul"]);
				$li_monago=$this->io_fun_nomina->uf_formatonumerico($row["monago"]);
				$li_monsep=$this->io_fun_nomina->uf_formatonumerico($row["monsep"]);
				$li_monoct=$this->io_fun_nomina->uf_formatonumerico($row["monoct"]);
				$li_monnov=$this->io_fun_nomina->uf_formatonumerico($row["monnov"]);
				$li_mondic=$this->io_fun_nomina->uf_formatonumerico($row["mondic"]);
				$li_carene=$row["carene"];
				$li_carfeb=$row["carfeb"];
				$li_carmar=$row["carmar"];
				$li_carabr=$row["carabr"];
				$li_carmay=$row["carmay"];
				$li_carjun=$row["carjun"];
				$li_carjul=$row["carjul"];
				$li_carago=$row["carago"];
				$li_carsep=$row["carsep"];
				$li_caroct=$row["caroct"];
				$li_carnov=$row["carnov"];
				$li_cardic=$row["cardic"];
				//--------------------------
				$li_carenef=$row["carenef"];
				$li_carfebf=$row["carfebf"];
				$li_carmarf=$row["carmarf"];
				$li_carabrf=$row["carabrf"];
				$li_carmayf=$row["carmayf"];
				$li_carjunf=$row["carjunf"];
				$li_carjulf=$row["carjulf"];
				$li_caragof=$row["caragof"];
				$li_carsepf=$row["carsepf"];
				$li_caroctf=$row["caroctf"];
				$li_carnovf=$row["carnovf"];
				$li_cardicf=$row["cardicf"];

				$li_carenem=$row["carenem"];
				$li_carfebm=$row["carfebm"];
				$li_carmarm=$row["carmarm"];
				$li_carabrm=$row["carabrm"];
				$li_carmaym=$row["carmaym"];
				$li_carjunm=$row["carjunm"];
				$li_carjulm=$row["carjulm"];
				$li_caragom=$row["caragom"];
				$li_carsepm=$row["carsepm"];
				$li_caroctm=$row["caroctm"];
				$li_carnovm=$row["carnovm"];
				$li_cardicm=$row["cardicm"];
				//--------------------------
				$li_numcarf=$row["numcarf"];
				$li_numcarm=$row["numcarm"];				
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_denominacion=$ls_desded;
					$ao_object[$ai_totrows][1]="<input name=txtcodigo".$ai_totrows." type=text id=txtcodigo".$ai_totrows." class=formato-azul size=4 maxlength=4 style='text-align:center' value='".$ls_codigo."' readonly><input name=txtcodded".$ai_totrows." type=hidden id=txtcodded".$ai_totrows." value='".$ls_codded."'><input name=txtcodtipper".$ai_totrows." type=hidden id=txtcodtipper".$ai_totrows." value='0000'>";
					$ao_object[$ai_totrows][2]="<input name=txtdescripcion".$ai_totrows." type=text id=txtdescripcion".$ai_totrows." class=formato-azul size=45 maxlength=45 style='text-align:center' value='".$ls_denominacion."' readonly>";
					$ao_object[$ai_totrows][3]="<input name=txtnumcar".$ai_totrows." type=text id=txtnumcar".$ai_totrows." class=formato-azul size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' value='".$li_numcar."' readonly>";
					$ao_object[$ai_totrows][4]="<input name=txtnumcarf".$ai_totrows." type=text id=txtnumcarf".$ai_totrows." class=formato-azul size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' value='".$li_numcarf."' readonly>";
					$ao_object[$ai_totrows][5]="<input name=txtnumcarm".$ai_totrows." type=text id=txtnumcarm".$ai_totrows." class=formato-azul size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' value='".$li_numcarm."' readonly>";
					$ao_object[$ai_totrows][6]="<input name=txtnumcarv".$ai_totrows." type=text id=txtnumcarv".$ai_totrows." class=formato-azul size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' value='".$li_numcarv."' readonly>";
					$ao_object[$ai_totrows][7]="<input name=txttotasi".$ai_totrows." type=text id=txttotasi".$ai_totrows." class=formato-azul size=20 maxlength=20 style='text-align:right' onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_totasi."' readonly>";
					$ao_object[$ai_totrows][8]="<input name=cmbdismonasi".$ai_totrows." type=text id=cmbdismonasi".$ai_totrows." class=formato-azul size=12 maxlength=12 readonly>";
					$ao_object[$ai_totrows][9]="<div align='center'><a href=javascript:uf_abrir_meses(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a></div>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonene".$ai_totrows." type=hidden id=txtmonene".$ai_totrows." value='".$li_monene."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonfeb".$ai_totrows." type=hidden id=txtmonfeb".$ai_totrows." value='".$li_monfeb."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonmar".$ai_totrows." type=hidden id=txtmonmar".$ai_totrows." value='".$li_monmar."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonabr".$ai_totrows." type=hidden id=txtmonabr".$ai_totrows." value='".$li_monabr."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonmay".$ai_totrows." type=hidden id=txtmonmay".$ai_totrows." value='".$li_monmay."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonjun".$ai_totrows." type=hidden id=txtmonjun".$ai_totrows." value='".$li_monjun."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonjul".$ai_totrows." type=hidden id=txtmonjul".$ai_totrows." value='".$li_monjul."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonago".$ai_totrows." type=hidden id=txtmonago".$ai_totrows." value='".$li_monago."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonsep".$ai_totrows." type=hidden id=txtmonsep".$ai_totrows." value='".$li_monsep."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonoct".$ai_totrows." type=hidden id=txtmonoct".$ai_totrows." value='".$li_monoct."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonnov".$ai_totrows." type=hidden id=txtmonnov".$ai_totrows." value='".$li_monnov."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmondic".$ai_totrows." type=hidden id=txtmondic".$ai_totrows." value='".$li_mondic."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarene".$ai_totrows." type=hidden id=txtcarene".$ai_totrows." value='".$li_carene."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarfeb".$ai_totrows." type=hidden id=txtcarfeb".$ai_totrows." value='".$li_carfeb."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmar".$ai_totrows." type=hidden id=txtcarmar".$ai_totrows." value='".$li_carmar."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarabr".$ai_totrows." type=hidden id=txtcarabr".$ai_totrows." value='".$li_carabr."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmay".$ai_totrows." type=hidden id=txtcarmay".$ai_totrows." value='".$li_carmay."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjun".$ai_totrows." type=hidden id=txtcarjun".$ai_totrows." value='".$li_carjun."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjul".$ai_totrows." type=hidden id=txtcarjul".$ai_totrows." value='".$li_carjul."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarago".$ai_totrows." type=hidden id=txtcarago".$ai_totrows." value='".$li_carago."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarsep".$ai_totrows." type=hidden id=txtcarsep".$ai_totrows." value='".$li_carsep."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaroct".$ai_totrows." type=hidden id=txtcaroct".$ai_totrows." value='".$li_caroct."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarnov".$ai_totrows." type=hidden id=txtcarnov".$ai_totrows." value='".$li_carnov."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcardic".$ai_totrows." type=hidden id=txtcardic".$ai_totrows." value='".$li_cardic."'>";
//-------------------------------------------------------------------------------------------------------------------------
				     $ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarenef".$ai_totrows." type=hidden id=txtcarenef".$ai_totrows." value='".$li_carenef."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarfebf".$ai_totrows." type=hidden id=txtcarfebf".$ai_totrows." value='".$li_carfebf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmarf".$ai_totrows." type=hidden id=txtcarmarf".$ai_totrows." value='".$li_carmarf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarabrf".$ai_totrows." type=hidden id=txtcarabrf".$ai_totrows." value='".$li_carabrf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmayf".$ai_totrows." type=hidden id=txtcarmayf".$ai_totrows." value='".$li_carmayf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjunf".$ai_totrows." type=hidden id=txtcarjunf".$ai_totrows." value='".$li_carjunf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjulf".$ai_totrows." type=hidden id=txtcarjulf".$ai_totrows." value='".$li_carjulf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaragof".$ai_totrows." type=hidden id=txtcaragof".$ai_totrows." value='".$li_caragof."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarsepf".$ai_totrows." type=hidden id=txtcarsepf".$ai_totrows." value='".$li_carsepf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaroctf".$ai_totrows." type=hidden id=txtcaroctf".$ai_totrows." value='".$li_caroctf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarnovf".$ai_totrows." type=hidden id=txtcarnovf".$ai_totrows." value='".$li_carnovf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcardicf".$ai_totrows." type=hidden id=txtcardicf".$ai_totrows." value='".$li_cardicf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarenem".$ai_totrows." type=hidden id=txtcarenem".$ai_totrows." value='".$li_carenem."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarfebm".$ai_totrows." type=hidden id=txtcarfebm".$ai_totrows." value='".$li_carfebm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmarm".$ai_totrows." type=hidden id=txtcarmarm".$ai_totrows." value='".$li_carmarm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarabrm".$ai_totrows." type=hidden id=txtcarabrm".$ai_totrows." value='".$li_carabrm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmaym".$ai_totrows." type=hidden id=txtcarmaym".$ai_totrows." value='".$li_carmaym."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjunm".$ai_totrows." type=hidden id=txtcarjunm".$ai_totrows." value='".$li_carjunm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjulm".$ai_totrows." type=hidden id=txtcarjulm".$ai_totrows." value='".$li_carjulm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaragom".$ai_totrows." type=hidden id=txtcaragom".$ai_totrows." value='".$li_caragom."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarsepm".$ai_totrows." type=hidden id=txtcarsepm".$ai_totrows." value='".$li_carsepm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaroctm".$ai_totrows." type=hidden id=txtcaroctm".$ai_totrows." value='".$li_caroctm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarnovm".$ai_totrows." type=hidden id=txtcarnovm".$ai_totrows." value='".$li_carnovm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcardicm".$ai_totrows." type=hidden id=txtcardicm".$ai_totrows." value='".$li_cardicm."'>";
//------------------------------------------------------------------------------------------------------------------------
				}
				else
				{
					$ao_object[$ai_totrows][1]="<input name=txtcodigo".$ai_totrows." type=text id=txtcodigo".$ai_totrows." class=sin-borde size=4 maxlength=4 style='text-align:center' value='".$ls_codtipper."' readonly><input name=txtcodded".$ai_totrows." type=hidden id=txtcodded".$ai_totrows." value='".$ls_codded."'><input name=txtcodtipper".$ai_totrows." type=hidden id=txtcodtipper".$ai_totrows." value='".$ls_codtipper."'>";
					$ao_object[$ai_totrows][2]="<input name=txtdescripcion".$ai_totrows." type=text id=txtdescripcion".$ai_totrows." class=sin-borde size=45 maxlength=45 style='text-align:center' value='".$ls_destipper."' readonly>";
					$ao_object[$ai_totrows][3]="<input name=txtnumcar".$ai_totrows." type=text id=txtnumcar".$ai_totrows." class=sin-borde size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' value='".$li_numcar."'>";
					$ao_object[$ai_totrows][4]="<input name=txtnumcarf".$ai_totrows." type=text id=txtnumcarf".$ai_totrows." class=sin-borde size=10 maxlength=10 style='text-align:right' onBlur='javascript: ue_actualizar_cargosf(".$ls_codded.");' onKeyUp='javascript: ue_validarnumero(this)';  value='".$li_numcarf."'>";
					$ao_object[$ai_totrows][5]="<input name=txtnumcarm".$ai_totrows." type=text id=txtnumcarm".$ai_totrows." class=sin-borde size=10 maxlength=10 style='text-align:right' onBlur='javascript: ue_actualizar_cargosm(".$ls_codded.");' onKeyUp='javascript: ue_validarnumero(this)'; value='".$li_numcarm."'>";
					$ao_object[$ai_totrows][6]="<input name=txtnumcarv".$ai_totrows." type=text id=txtnumcarv".$ai_totrows." class=sin-borde size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this)'; value='".$li_numcarv."' readonly>";
					$ao_object[$ai_totrows][7]="<input name=txttotasi".$ai_totrows." type=text id=txttotasi".$ai_totrows." class=sin-borde size=20 maxlength=20 style='text-align:right' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_actualizar_asignacion(".$ls_codded.");' onChange='javascript: ue_actualizar_meses(".$ai_totrows.");' value='".$li_totasi."'>";
					$ao_object[$ai_totrows][8]="<select name=cmbdismonasi".$ai_totrows." id=cmbdismonasi".$ai_totrows." onChange='javascript: ue_actualizar_meses(".$ai_totrows.");'><option value='0' ".$ls_distrbucionauto.">Automática</option><option value='1' ".$ls_distrbucionmanu.">Manual</option></select>";
					$ao_object[$ai_totrows][9]="<div align='center'><a href=javascript:uf_abrir_meses(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a></div>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonene".$ai_totrows." type=hidden id=txtmonene".$ai_totrows." value='".$li_monene."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonfeb".$ai_totrows." type=hidden id=txtmonfeb".$ai_totrows." value='".$li_monfeb."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonmar".$ai_totrows." type=hidden id=txtmonmar".$ai_totrows." value='".$li_monmar."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonabr".$ai_totrows." type=hidden id=txtmonabr".$ai_totrows." value='".$li_monabr."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonmay".$ai_totrows." type=hidden id=txtmonmay".$ai_totrows." value='".$li_monmay."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonjun".$ai_totrows." type=hidden id=txtmonjun".$ai_totrows." value='".$li_monjun."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonjul".$ai_totrows." type=hidden id=txtmonjul".$ai_totrows." value='".$li_monjul."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonago".$ai_totrows." type=hidden id=txtmonago".$ai_totrows." value='".$li_monago."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonsep".$ai_totrows." type=hidden id=txtmonsep".$ai_totrows." value='".$li_monsep."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonoct".$ai_totrows." type=hidden id=txtmonoct".$ai_totrows." value='".$li_monoct."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmonnov".$ai_totrows." type=hidden id=txtmonnov".$ai_totrows." value='".$li_monnov."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtmondic".$ai_totrows." type=hidden id=txtmondic".$ai_totrows." value='".$li_mondic."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarene".$ai_totrows." type=hidden id=txtcarene".$ai_totrows." value='".$li_carene."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarfeb".$ai_totrows." type=hidden id=txtcarfeb".$ai_totrows." value='".$li_carfeb."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmar".$ai_totrows." type=hidden id=txtcarmar".$ai_totrows." value='".$li_carmar."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarabr".$ai_totrows." type=hidden id=txtcarabr".$ai_totrows." value='".$li_carabr."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmay".$ai_totrows." type=hidden id=txtcarmay".$ai_totrows." value='".$li_carmay."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjun".$ai_totrows." type=hidden id=txtcarjun".$ai_totrows." value='".$li_carjun."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjul".$ai_totrows." type=hidden id=txtcarjul".$ai_totrows." value='".$li_carjul."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarago".$ai_totrows." type=hidden id=txtcarago".$ai_totrows." value='".$li_carago."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarsep".$ai_totrows." type=hidden id=txtcarsep".$ai_totrows." value='".$li_carsep."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaroct".$ai_totrows." type=hidden id=txtcaroct".$ai_totrows." value='".$li_caroct."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarnov".$ai_totrows." type=hidden id=txtcarnov".$ai_totrows." value='".$li_carnov."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcardic".$ai_totrows." type=hidden id=txtcardic".$ai_totrows." value='".$li_cardic."'>";
///-------------------------------------------------------------------------------------------------------------------------
 					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarenef".$ai_totrows." type=hidden id=txtcarenef".$ai_totrows." value='".$li_carenef."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarfebf".$ai_totrows." type=hidden id=txtcarfebf".$ai_totrows." value='".$li_carfebf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmarf".$ai_totrows." type=hidden id=txtcarmarf".$ai_totrows." value='".$li_carmarf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarabrf".$ai_totrows." type=hidden id=txtcarabrf".$ai_totrows." value='".$li_carabrf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmayf".$ai_totrows." type=hidden id=txtcarmayf".$ai_totrows." value='".$li_carmayf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjunf".$ai_totrows." type=hidden id=txtcarjunf".$ai_totrows." value='".$li_carjunf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjulf".$ai_totrows." type=hidden id=txtcarjulf".$ai_totrows." value='".$li_carjulf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaragof".$ai_totrows." type=hidden id=txtcaragof".$ai_totrows." value='".$li_caragof."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarsepf".$ai_totrows." type=hidden id=txtcarsepf".$ai_totrows." value='".$li_carsepf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaroctf".$ai_totrows." type=hidden id=txtcaroctf".$ai_totrows." value='".$li_caroctf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarnovf".$ai_totrows." type=hidden id=txtcarnovf".$ai_totrows." value='".$li_carnovf."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcardicf".$ai_totrows." type=hidden id=txtcardicf".$ai_totrows." value='".$li_cardicf."'>";
//-------------------------------------------------------------------------------------------------------------------------

$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarenem".$ai_totrows." type=hidden id=txtcarenem".$ai_totrows." value='".$li_carenem."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarfebm".$ai_totrows." type=hidden id=txtcarfebm".$ai_totrows." value='".$li_carfebm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmarm".$ai_totrows." type=hidden id=txtcarmarm".$ai_totrows." value='".$li_carmarm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarabrm".$ai_totrows." type=hidden id=txtcarabrm".$ai_totrows." value='".$li_carabrm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarmaym".$ai_totrows." type=hidden id=txtcarmaym".$ai_totrows." value='".$li_carmaym."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjunm".$ai_totrows." type=hidden id=txtcarjunm".$ai_totrows." value='".$li_carjunm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarjulm".$ai_totrows." type=hidden id=txtcarjulm".$ai_totrows." value='".$li_carjulm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaragom".$ai_totrows." type=hidden id=txtcaragom".$ai_totrows." value='".$li_caragom."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarsepm".$ai_totrows." type=hidden id=txtcarsepm".$ai_totrows." value='".$li_carsepm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcaroctm".$ai_totrows." type=hidden id=txtcaroctm".$ai_totrows." value='".$li_caroctm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcarnovm".$ai_totrows." type=hidden id=txtcarnovm".$ai_totrows." value='".$li_carnovm."'>";
					$ao_object[$ai_totrows][7]=$ao_object[$ai_totrows][7]."<input name=txtcardicm".$ai_totrows." type=hidden id=txtcardicm".$ai_totrows." value='".$li_cardicm."'>";
					
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_programacionreporte($as_codrep,$as_codded,$as_codtipper,$ai_numcar,
	                                       $ai_numcarf,$ai_numcarm,$ai_numcarv,
										   $ai_totasi,$ai_dismonasi,$ai_monene,
										   $ai_monfeb,$ai_monmar,$ai_monabr,$ai_monmay,$ai_monjun,$ai_monjul,$ai_monago,
										   $ai_monsep,$ai_monoct,$ai_monnov,$ai_mondic,
										   $ai_carene,$ai_carfeb,$ai_carmar,$ai_carabr,
										   $ai_carmay,$ai_carjun,$ai_carjul,$ai_carago,
										   $ai_carsep,$ai_caroct,$ai_carnov,$ai_cardic,
										   $ai_carenef,$ai_carfebf,$ai_carmarf,$ai_carabrf,
										   $ai_carmayf,$ai_carjunf,$ai_carjulf,$ai_caragof,
										   $ai_carsepf,$ai_caroctf,$ai_carnovf,$ai_cardicf,
										   $ai_carenem,$ai_carfebm,$ai_carmarm,$ai_carabrm,
										   $ai_carmaym,$ai_carjunm,$ai_carjulm,$ai_caragom,
										   $ai_carsepm,$ai_caroctm,$ai_carnovm,$ai_cardicm,									   
										   $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_programacionreporte
		//		   Access: private
		//	    Arguments: as_codrep  // código del reporte
		//				   as_codded  // Código de Dedicación
		//				   as_codtipper  // Código de Tipo de Personal
		//				   ai_numcar  // Número de cargos
		//				   ai_numcarf  // Número de cargos a personal femenino
		//				   ai_numcarm  // Número de cargos a personal masculino
		//				   ai_numcarv  // Número de cargos vacantes
		//				   ai_totasi  // total de asignación
		//				   ai_dismonasi  // Distribución
		//				   ai_monene  // Monto enero
		//				   ai_monfeb  // Monto Febrero
		//				   ai_monmar  // Monto Marzo
		//				   ai_monabr  // Monto Abril
		//				   ai_monmay  // Monto Mayo
		//				   ai_monjun  // Monto Junio
		//				   ai_monjul  // Monto Julio
		//				   ai_monago  // Monto Agosto
		//				   ai_monsep  // Monto Septiembre
		//				   ai_monoct  // Monto Octubre
		//				   ai_monnov  // Monto Noviembre
		//				   ai_mondic  // Monto Diciembre
		//				   ai_carene  // total cargo enero
		//				   ai_carfeb  // total cargo Febrero
		//				   ai_carmar  // total cargo Marzo
		//				   ai_carabr  // total cargo Abril
		//				   ai_carmay  // total cargo Mayo
		//				   ai_carjun  // total cargo Junio
		//				   ai_carjul  // total cargo Julio
		//				   ai_carago  // total cargo Agosto
		//				   ai_carsep  // total cargo Septiembre
		//				   ai_caroct  // total cargo Octubre
		//				   ai_carnov  // total cargo Noviembre
		//				   ai_cardic  // Monto Diciembre
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_programaciónreporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_programacionreporte ".
				"   SET numcar = ".$ai_numcar.", ".
				"		totasi = ".$ai_totasi.", ".
				"		dismonasi = ".$ai_dismonasi.", ".
				"		monene = ".$ai_monene.", ".
				"		monfeb = ".$ai_monfeb.", ".
				"		monmar = ".$ai_monmar.", ".
				"		monabr = ".$ai_monabr.", ".
				"		monmay = ".$ai_monmay.", ".
				"		monjun = ".$ai_monjun.", ".
				"		monjul = ".$ai_monjul.", ".
				"		monago = ".$ai_monago.", ".
				"		monsep = ".$ai_monsep.", ".
				"		monoct = ".$ai_monoct.", ".
				"		monnov = ".$ai_monnov.", ".
				"		mondic = ".$ai_mondic.", ".
				"		carene = ".$ai_carene.", ".
				"		carfeb = ".$ai_carfeb.", ".
				"		carmar = ".$ai_carmar.", ".
				"		carabr = ".$ai_carabr.", ".
				"		carmay = ".$ai_carmay.", ".
				"		carjun = ".$ai_carjun.", ".
				"		carjul = ".$ai_carjul.", ".
				"		carago = ".$ai_carago.", ".
				"		carsep = ".$ai_carsep.", ".
				"		caroct = ".$ai_caroct.", ".
				"		carnov = ".$ai_carnov.", ".
				"		cardic = ".$ai_cardic.", ".
				"		carenef = ".$ai_carenef.", ".
				"		carfebf = ".$ai_carfebf.", ".
				"		carmarf = ".$ai_carmarf.", ".
				"		carabrf = ".$ai_carabrf.", ".
				"		carmayf = ".$ai_carmayf.", ".
				"		carjunf = ".$ai_carjunf.", ".
				"		carjulf = ".$ai_carjulf.", ".
				"		caragof = ".$ai_caragof.", ".
				"		carsepf = ".$ai_carsepf.", ".
				"		caroctf = ".$ai_caroctf.", ".
				"		carnovf = ".$ai_carnovf.", ".
				"		cardicf = ".$ai_cardicf.", ".
				"		carenem = ".$ai_carenem.", ".
				"		carfebm = ".$ai_carfebm.", ".
				"		carmarm = ".$ai_carmarm.", ".
				"		carabrm = ".$ai_carabrm.", ".
				"		carmaym = ".$ai_carmaym.", ".
				"		carjunm = ".$ai_carjunm.", ".
				"		carjulm = ".$ai_carjulm.", ".
				"		caragom = ".$ai_caragom.", ".
				"		carsepm = ".$ai_carsepm.", ".
				"		caroctm = ".$ai_caroctm.", ".
				"		carnovm = ".$ai_carnovm.", ".
				"		cardicm = ".$ai_cardicm.", ".
				"		numcarf = ".$ai_numcarf.", ".
				"		numcarm = ".$ai_numcarm.", ".
				"		numcarv = ".$ai_numcarv." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codrep='".$as_codrep."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_update_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Programación de Reporte Reporte ".$as_codrep." Dedicación ".$as_codded." Tipo Personal".$as_codtipper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_programacionreporte($as_codrep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_programacionreporte
		//		   Access: private
		//	    Arguments: as_codrep  // código del reporte
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_programaciónreporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/12/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_programacionreporte ".
				"   SET numcar = 0, ".
				"		totasi = 0, ".
				"		dismonasi = 0, ".
				"		monene = 0, ".
				"		monfeb = 0, ".
				"		monmar = 0, ".
				"		monabr = 0, ".
				"		monmay = 0, ".
				"		monjun = 0, ".
				"		monjul = 0, ".
				"		monago = 0, ".
				"		monsep = 0, ".
				"		monoct = 0, ".
				"		monnov = 0, ".
				"		mondic = 0, ".
				"		carene = 0, ".
				"		carfeb = 0, ".
				"		carmar = 0, ".
				"		carabr = 0, ".
				"		carmay = 0, ".
				"		carjun = 0, ".
				"		carjul = 0, ".
				"		carago = 0, ".
				"		carsep = 0, ".
				"		caroct = 0, ".
				"		carnov = 0, ".
				"		cardic = 0 ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codrep='".$as_codrep."'";
       	$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programación Reporte MÉTODO->uf_delete_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Programación de Reporte Reporte ".$as_codrep;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				$this->io_mensajes->message("La Programación de Reporte Fué Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al Eliminar la Programación de Reporte.");
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>