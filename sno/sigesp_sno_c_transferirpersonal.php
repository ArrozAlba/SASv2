<?php
class sigesp_sno_c_transferirpersonal
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_transferirpersonal()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_transferirpersonal
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		
	}// end function sigesp_sno_c_transferirpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_cargo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_rac($as_codnom,&$ai_racnom)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_rac
		//	    Arguments: as_codigo    codigo de la nomina 
		//	      Returns: lb_valido -> variable boolean
		//	  Description: selecciona los datos de la nomina segun el codigo pasado por  parametros
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. María Beatriz Unda						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
	   	$ls_sql="SELECT racnom ".
				"  FROM sno_nomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND  codnom='".$as_codnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$ai_racnom=$rs_data->fields["racnom"];
			}
		}	
		return $lb_valido;
	}// end function uf_verificar_rac
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_transferir_personal($as_codnombus,$as_tiptra,$as_codperi,$as_anocur,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_transferir_personal
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnombus  // Código de Nómina donde se va a transferir el personal
		//                 as_tiptra     // Tipo de transferencia '1' nomina actual, '2' nomina historica
		//                 as_codperi    // Código del Periodo
		//                 as_anocur    //  Año del Periodo
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el importar completo ó False si hubo error en el importar
		//	  Description: Función que importa toda la información referente a Tablas, grado, cargo,
		//                 asignación de cargo, subnómina que el personal seleccionado tiene asociado. 
		//                 y las constantes que el concepto selecionado tiene asociado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if ($as_tiptra=='1')
		{
			$lb_valido=$this->uf_buscar_personal_tranferir($as_codnombus,$rs_data);				
		}
		elseif($as_tiptra=='2')
		{
			$lb_valido=$this->uf_buscar_personal_tranferir_historico($as_codnombus,$as_codperi,$as_anocur,$rs_data);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_subnomina($as_codnombus);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_cargo($as_codnombus);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_tabulador($as_codnombus);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_grado_paso($as_codnombus);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_asignacion_cargo($as_codnombus);
		}
		while((!$rs_data->EOF)&&($lb_valido)) 
		{
			$ls_codper=$rs_data->fields["codper"];
			$ls_codsubnom=$rs_data->fields["codsubnom"];
			$ls_codasicar=$rs_data->fields["codasicar"];
			$ls_codtab=$rs_data->fields["codtab"];
			$ls_codgra=$rs_data->fields["codgra"];
			$ls_codpas=$rs_data->fields["codpas"];
			$ls_codcar=$rs_data->fields["codcar"];				
			$lb_valido=$this->uf_verificar_personal($ls_codper,$as_codnombus,$aa_seguridad);
			$rs_data->MoveNext();
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_eliminar_personal($as_codnombus,$aa_seguridad);
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("El personal fue transferido.");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al transferir la información.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_transferir_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_buscar_personal_tranferir($as_codnom,&$rs_data)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_personal_tranferir
		//	    Arguments: as_codigo    codigo de la nomina 
		//	      Returns: lb_valido -> variable boolean
		//	  Description: selecciona los datos de la nomina segun el codigo pasado por  parametros
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. María Beatriz Unda						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
	   	$ls_sql="SELECT * ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_buscar_personal_tranferir ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_buscar_personal_tranferir
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_personal_tranferir_historico($as_codnom,$as_codperi,$as_anocur,&$rs_data)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_personal_tranferir_historico
		//	    Arguments: as_codigo  //  codigo de la nomina 
		//                 as_codperi //  codigo del periodo
		//                 as_anocur  //  año en curso
		//	      Returns: lb_valido -> variable boolean
		//	  Description: selecciona los datos de la nomina segun el codigo pasado por  parametros
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. María Beatriz Unda						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
	   	$ls_sql="SELECT * ".
				"  FROM sno_hpersonalnomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ".
				"   AND codperi='".$as_codperi."' ". 
				"   AND anocur='".$as_anocur."'  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_buscar_personal_tranferir_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_buscar_personal_tranferir_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_subnomina($as_codnombus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_subnomina
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//	      Returns:	$lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la subnòmina del personal y lo inserta en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="INSERT INTO sno_subnomina(codemp,codnom,codsubnom,dessubnom) ".
				"SELECT codemp,'".$this->ls_codnom."',codsubnom,dessubnom ".
				"  FROM sno_subnomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$as_codnombus."'".
				"   AND codsubnom NOT IN (SELECT codsubnom ".
				"							FROM sno_subnomina ".
				"						   WHERE codemp='".$this->ls_codemp."'".
				"                            AND codnom='".$this->ls_codnom."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		/*$ls_sql="SELECT codsubnom ".
				"  FROM sno_subnomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codsubnom='".$as_codsubnom."'  ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
				$ls_sql="INSERT INTO sno_subnomina(codemp,codnom,codsubnom,dessubnom) ".
				        "SELECT codemp,'".$this->ls_codnom."',codsubnom,dessubnom ".
						"  FROM sno_subnomina ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$as_codnombus."'".
						"   AND codsubnom='".$as_codsubnom."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				
			}			
			$this->io_sql->free_result($rs_data);				
		}*/
		return $lb_valido;
	}// end function uf_importar_subnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_cargo($as_codnombus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_cargo
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//	      Returns:	$lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn del cargo del personal y sino está lo inserta en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="INSERT INTO sno_cargo(codemp,codnom,codcar,descar) ".
				"SELECT codemp,'".$this->ls_codnom."',codcar,descar ".
				"  FROM sno_cargo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$as_codnombus."'".
				"   AND codcar NOT IN (SELECT codcar ".
				"						 FROM sno_cargo ".
				"						WHERE codemp='".$this->ls_codemp."'".
				"                         AND codnom='".$this->ls_codnom."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		/*$ls_sql="SELECT codcar ".
				"  FROM sno_cargo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcar='".$as_codcar."'  ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
				$ls_sql="INSERT INTO sno_cargo(codemp,codnom,codcar,descar) ".
				        "SELECT codemp,'".$this->ls_codnom."',codcar,descar ".
						"  FROM sno_cargo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$as_codnombus."'".
						"   AND codcar='".$as_codcar."'";				
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}			
			$this->io_sql->free_result($rs_data);				
		}*/
		return $lb_valido;
	}// end function uf_verificar_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_verificar_tabulador($as_codnombus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_tabulador
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//	      Returns:	$lb_valido True si se importó la tabla correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la tabla del personal y la inserta en la nómina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación :		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab) ".
				"  SELECT codemp,'".$this->ls_codnom."',codtab,destab ".
				"  FROM sno_tabulador ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$as_codnombus."'".
				"   AND codtab NOT IN (SELECT codtab ".
				"						 FROM sno_tabulador ".
				"						WHERE codemp='".$this->ls_codemp."'".
				"                         AND codnom='".$this->ls_codnom."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		/*$ls_sql="SELECT codtab ".
				"  FROM sno_tabulador ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codtab = '".$as_codtab."' ";		
					
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
				$ls_sql="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab) ".
						"  SELECT codemp,'".$this->ls_codnom."',codtab,destab ".
						"  FROM sno_tabulador ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$as_codnombus."'".
						"   AND codtab='".$as_codtab."'";						
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
			}
			$this->io_sql->free_result($rs_data);	
		}*/
		return $lb_valido;
	}// end function uf_verificar_tabulador
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_verificar_grado_paso($as_codnombus)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_grado
		//		   Access: private
		//      Arguments:
		//	      Returns: 
		//	  Description: función que busca del grado y paso y si no existelos inserta
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2008								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_campo = $this->io_conexion->Concat('codtab','codpas','codgra');
		$ls_sql= " INSERT INTO sno_grado(codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra)  ".
				 " SELECT codemp, '".$this->ls_codnom."', codtab, codpas, codgra, monsalgra, moncomgra ".
				 "   FROM sno_grado ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$as_codnombus."'".
				 "    AND ".$ls_campo ." NOT IN (SELECT ".$ls_campo ." ".
				 "						           FROM sno_grado ".
				 "						          WHERE codemp='".$this->ls_codemp."'".
				 "                                  AND codnom='".$this->ls_codnom."')";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_grado_paso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}

		/*$ls_sql= " SELECT codtab, codpas, codgra ".
				 "   FROM sno_grado ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$this->ls_codnom."'".
				 "    AND codtab='".$as_codtab."'".
				 "    AND codpas='".$as_codpas."'".
				 "    AND codgra='".$as_codgra."'"; 
		$rs_data2=$this->io_sql->select($ls_sql);
		if($rs_data2===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_grado_paso ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}	
		else
		{
		 	if($rs_data2->RecordCount()==0)
			{
				$ls_sql= " INSERT INTO sno_grado(codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra)  ".
                      	 " SELECT codemp, '".$this->ls_codnom."', codtab, codpas, codgra, monsalgra, moncomgra ".
						 "   FROM sno_grado ".
						 "  WHERE codemp='".$this->ls_codemp."'".
						 "    AND codnom='".$as_codnombus."'".
						 "    AND codtab='".$as_codtab."'".
						 "    AND codpas='".$as_codpas."'".
						 "    AND codgra='".$as_codgra."'"; 
				
				$li_row2=$this->io_sql->execute($ls_sql);
				if($li_row2===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_grado_paso ERROR->".
					                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}// fin del if			
		}// fin del else*/
		return 	$lb_valido;
	}// uf_verificar_grado_paso	
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_verificar_asignacion_cargo($as_codnombus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_asignacion_cargo
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//	      Returns:	$lb_valido True si se importó la asignación de cargo correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la asignación de cargo del personal y lo inserta en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2008							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_asignacioncargo (codemp,codnom,codasicar,denasicar,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm, ".
				" 								  claasicar,codtab,codpas,codgra, codded,codtipper,numvacasicar ,numocuasicar, codproasicar, estcla)  ".
				"SELECT codemp,'".$this->ls_codnom."',codasicar,denasicar,minorguniadm,ofiuniadm, uniuniadm, depuniadm, prouniadm,claasicar, ".
				" 		codtab,codpas,codgra, codded,codtipper,numvacasicar,1, codproasicar, estcla ".
				"  FROM sno_asignacioncargo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnombus."' ".
				"   AND codasicar NOT IN (SELECT codtab ".
				"						    FROM sno_tabulador ".
				"						   WHERE codemp='".$this->ls_codemp."'".
				"                            AND codnom='".$this->ls_codnom."')";
/*		$ls_sql="SELECT codasicar	".
					"  FROM sno_asignacioncargo ".
					" WHERE sno_asignacioncargo.codemp='".$this->ls_codemp."' ".
					"   AND sno_asignacioncargo.codnom='".$this->ls_codnom."' ".
					"   AND sno_asignacioncargo.codasicar = '".$as_codasicar."' ";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_asignacion_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
					$ls_sql="INSERT INTO sno_asignacioncargo".		 
					        "(codemp,codnom,codasicar,denasicar,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm, ".
							" claasicar,codtab,codpas,codgra, codded,codtipper,numvacasicar ,numocuasicar, codproasicar, ".
							" estcla)  ".
							"SELECT codemp,'".$this->ls_codnom."',codasicar,denasicar,minorguniadm,ofiuniadm, uniuniadm, ".
							" depuniadm, prouniadm,claasicar,codtab,codpas,codgra, codded,codtipper,numvacasicar,1, ".
							" codproasicar, estcla ".
							"  FROM sno_asignacioncargo ".
							" WHERE sno_asignacioncargo.codemp='".$this->ls_codemp."' ".
							"   AND sno_asignacioncargo.codnom='".$as_codnombus."' ".
							"   AND sno_asignacioncargo.codasicar = '".$as_codasicar."' "
							;
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_asignacion_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				
			}
			$this->io_sql->free_result($rs_data);	
		}*/
		return $lb_valido;
	}// end function uf_verificar_asignacion_cargo
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_verificar_personal($as_codper,$as_codnombus,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_personal
		//		   Access: private
		//      Arguments:
		//	      Returns: 
		//	  Description: función que busca personal, si no existe lo inserta y sino lo actualiza
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= " SELECT codper ".
				 "   FROM sno_personalnomina ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$this->ls_codnom."'".
				 "    AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}	
		else
		{
		 	if($rs_data->RecordCount()==0)
			{
				$lb_valido=$this->uf_insert_personal($as_codper,$as_codnombus);
				if ($lb_valido)
				{
					$lb_valido=$this->uf_insert_constantepersonal($as_codper);					
					
				}
				if ($lb_valido)
				{
					$lb_valido=$this->uf_insert_conceptopersonal($as_codper);
					
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion ="Inserto la información relacionada (Tabulador, Grado, Cargo, Asignación Cargo, Subnómina, personal) ".
									 " del personal ".$ls_codper. " de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			else
			{				
				$lb_valido=$this->uf_update_personal($as_codper,$as_codnombus);	
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion ="Actualizo la información relacionada (Tabulador, Grado, Cargo, Asignación Cargo, Subnómina, personal) ".
									 " del personal ".$as_codper. " de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}			
		}// fin del else
		return 	$lb_valido;
	}// uf_verificar_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_personal($as_codper,$as_codnombus)	
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personal
		//		   Access: private
		//      Arguments:
		//	      Returns: 
		//	  Description: función que inserta el personal en la nomina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= " INSERT INTO sno_personalnomina  ".
				 "(codemp,codnom,codper,codsubnom,codtab,codasicar,codgra,codpas,sueper,horper,".									
				 " minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban, ".
				 " tipcuebanper,codcar,fecingper,staper,cueaboper,fecculcontr,codded, codtipper,quivacper, ".
				 " codtabvac,sueintper,pagefeper,sueproper,codage,fecegrper,".
				 " fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic,conjub,catjub, 	".
				 " codclavia,codunirac,fecascper, pagtaqper, grado, descasicar,salnorper,coddep, estencper) ".
				 " SELECT codemp,'".$this->ls_codnom."',codper,codsubnom,codtab,codasicar,codgra,codpas,sueper,horper,".									
				 " minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban, ".
				 " tipcuebanper,codcar,fecingper,staper,cueaboper,fecculcontr,codded, codtipper,quivacper, ".
				 " codtabvac,sueintper,pagefeper,sueproper,codage,fecegrper,".
				 " fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic,conjub,catjub, 	".
				 " codclavia,codunirac,fecascper, pagtaqper, grado, descasicar,salnorper,coddep, estencper ".
				 "   FROM sno_personalnomina ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$as_codnombus."'".
				 "    AND codper='".$as_codper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_insert_personalERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return 	$lb_valido;
	}// uf_insert_personal
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_update_personal($as_codper,$as_codnombus)	
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personal
		//		   Access: private
		//      Arguments:
		//	      Returns: 
		//	  Description: función que inserta el personal en la nomina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=true;
		$ls_sql="SELECT codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, ".
				"		depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, ".
				"		fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, ".
				"		fecsusper, cauegrper, codescdoc, codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, ".
				"		codunirac, fecascper, pagtaqper, grado, descasicar,coddep,salnorper,estencper ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while (!$rs_data->EOF)
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_codsubnom=$rs_data->fields["codsubnom"];				
				$ls_codcar=$rs_data->fields["codcar"];				
				$ls_codasicar=$rs_data->fields["codasicar"];			
				$ls_codtab=$rs_data->fields["codtab"];
				$ls_codgra=$rs_data->fields["codgra"];
				$ls_codpas=$rs_data->fields["codpas"];				
				$li_sueper=$rs_data->fields["sueper"];				
				$li_horper=$rs_data->fields["horper"];			
				$ls_minorguniadm=$rs_data->fields["minorguniadm"];			
				$ls_ofiuniadm=$rs_data->fields["ofiuniadm"];			
				$ls_uniuniadm=$rs_data->fields["uniuniadm"];			
				$ls_depuniadm=$rs_data->fields["depuniadm"];			
				$ls_prouniadm=$rs_data->fields["prouniadm"];			
				$li_pagbanper=$rs_data->fields["pagbanper"];
				$ls_codban=$rs_data->fields["codban"];
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_tipcuebanper=$rs_data->fields["tipcuebanper"];
				$ld_fecingper=$rs_data->fields["fecingper"];				
				$ls_estper=$rs_data->fields["staper"];
				$ls_cueaboper=$rs_data->fields["cueaboper"];
				$ld_fecculcontr=$rs_data->fields["fecculcontr"];
				$ls_codded=$rs_data->fields["codded"];
				$ls_codtipper=$rs_data->fields["codtipper"];
				$ls_codtabvac=$rs_data->fields["codtabvac"];
				$li_sueintper=$rs_data->fields["sueintper"];
				$li_salnorper=$rs_data->fields["salnorper"];			
				$li_pagefeper=$rs_data->fields["pagefeper"];
				$li_sueproper=$rs_data->fields["sueproper"];			
				$ls_codage=$rs_data->fields["codage"];
				$ld_fecegrper=$rs_data->fields["fecegrper"];
				if($ld_fecegrper=="")
				{
					$ld_fecegrper="1900-01-01";
				}
				$ld_fecsusper=$rs_data->fields["fecsusper"];				
				if($ld_fecsusper=="")
				{
					$ld_fecsusper="1900-01-01";
				}
				$ls_cauegrper=$rs_data->fields["cauegrper"];
				$ls_codescdoc=$rs_data->fields["codescdoc"];
				$ls_codcladoc=$rs_data->fields["codcladoc"];
				$ls_codubifis=$rs_data->fields["codubifis"];
				$ls_tipcestic=$rs_data->fields["tipcestic"];
				$ls_quivacper=$rs_data->fields["quivacper"];
				$ls_conjub=$rs_data->fields["conjub"];
				$ls_catjub=$rs_data->fields["catjub"];
				$ls_codclavia=$rs_data->fields["codclavia"];
				$ls_codunirac=$rs_data->fields["codunirac"];
				$ld_fecascper=$rs_data->fields["fecascper"];
				$li_pagtaqper=$rs_data->fields["pagtaqper"];
				$ls_grado=$rs_data->fields["grado"];
				$ls_descasicar=$rs_data->fields["descasicar"];
				$ls_coddep=$rs_data->fields["coddep"];
				$ls_estencper=$rs_data->fields["estencper"];				
				
				$ls_sql="UPDATE sno_personalnomina ".
					"   SET codsubnom='".$ls_codsubnom."',".
					"		codasicar='".$ls_codasicar."',".
					"		codcar='".$ls_codcar."',".
					"		codtab='".$ls_codtab."',".
					"		codpas='".$ls_codpas."',".
					"		codgra='".$ls_codgra."',".
					"		minorguniadm='".$ls_minorguniadm."',".
					"		ofiuniadm='".$ls_ofiuniadm."',".
					"		uniuniadm='".$ls_uniuniadm."',".
					"		depuniadm='".$ls_depuniadm."',".
					"		prouniadm='".$ls_prouniadm."',".
					"		sueper=".$li_sueper.",".
					"		horper=".$li_horper.",".
					"		sueintper=".$li_sueintper.",".
					"		sueproper=".$li_sueproper.",".
					"		fecingper='".$ld_fecingper."',".
					"		fecculcontr='".$ld_fecculcontr."',".
					"		codded='".$ls_codded."',".
					"		codtipper='".$ls_codtipper."',".
					"		codtabvac='".$ls_codtabvac."',".
					"		pagefeper=".$li_pagefeper.",".
					"		pagbanper=".$li_pagbanper.",".
					"		codban='".$ls_codban."',".
					"		codcueban='".$ls_codcueban."',".
					"		tipcuebanper='".$ls_tipcuebanper."',".
					"		cueaboper='".$ls_cueaboper."',".
					"		codage='".$ls_codage."',".
					"		tipcestic='".$ls_tipcestic."', ".
					"		codescdoc='".$ls_codescdoc."', ".
					"		codcladoc='".$ls_codcladoc."', ".
					"		codubifis='".$ls_codubifis."', ".
					"		conjub='".$ls_conjub."', ".
					"		catjub='".$ls_catjub."', ".
					"		codclavia='".$ls_codclavia."', ".
					"       codunirac='".$ls_codunirac."', ".
					"       pagtaqper=".$li_pagtaqper.", ".
					"		fecascper= '".$ld_fecascper."', ".
					"       grado='".$ls_grado."', ".
					"       descasicar='".$ls_descasicar."', ".
					"       coddep='".$ls_coddep."', ".
					"       salnorper=".$li_salnorper.", ".
					"       staper='".$ls_estper."',      ".
					"       estencper='".$ls_estencper."' ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND codper='".$as_codper."'";
						
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				$rs_data->MoveNext();
			}
		}
		return 	$lb_valido;
	}// uf_update_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que graba los conceptos a personal nómina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 11/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat) ".
				"SELECT codemp,codnom,'".$as_codper."',codconc,1,0,0,0,0,0 ".
				"  FROM sno_concepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
/*		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codconc=$rs_data->fields["codconc"];
				$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codconc."',1,0,0,0,0,0)";
	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_*/valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_constantepersonal($as_codper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantepersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que graba las constantes a personal nómina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_constantepersonal (codemp,codnom,codper,codcons,moncon) ".
				"SELECT codemp,codnom,'".$as_codper."',codcons,valcon ".
				"  FROM sno_constante ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
/*		$ls_sql="SELECT codcons,valcon,topcon ".
				"  FROM sno_constante ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcons=$rs_data->fields["codcons"];
				$li_valcon=$rs_data->fields["valcon"];
				$li_topcon=$rs_data->fields["topcon"];
				
				$ls_sql="INSERT INTO sno_constantepersonal(codemp,codnom,codper,codcons,moncon,montopcon)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codcons."','".$li_valcon."',".$li_topcon.")";

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}*/
		return $lb_valido;
	}// end function uf_insert_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//--------------------------------------------------------------------------------------------------------------------------------	
    function uf_verificar_eliminar_personal($as_codnombus,$aa_seguridad)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_eliminar_personal
		//		   Access: private
		//      Arguments: as_codnombus // Código de nómina a buscar
		//                 aa_seguridad // arreglo de seguridad
		//	      Returns: 
		//	  Description: función que busca personal, si no existe lo inserta y sino lo actualiza
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= " SELECT codper ".
				 "   FROM sno_personalnomina ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$this->ls_codnom."'".
				 "    AND codper NOT IN (SELECT codper FROM sno_personalnomina ".
				  " 					 WHERE codemp='".$this->ls_codemp."'".
				 "   					 AND codnom='".$as_codnombus."') ";
				  
		$rs_data3=$this->io_sql->select($ls_sql);
		if($rs_data3===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_verificar_eliminar_personal ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}	
		else
		{
		 	while ((!$rs_data3->EOF)&&($lb_valido))
			{
				$ls_codper=$rs_data3->fields["codper"];
				$lb_valido=$this->uf_select_prestamos_activos_personal($ls_codper);
				if ($lb_valido)
				{ 
					$lb_valido=$this->uf_suspender_personal($ls_codper);
					if($lb_valido)
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="PROCESS";
						$ls_descripcion ="Actualizo a suspendido el estatus ".
										 " del personal ".$ls_codper. " de la nómina ".$this->ls_codnom." ";
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$this->io_mensajes->message("La persona ".$ls_codper." tiene prestamos activos en la nómina actual. Por lo tanto no se eliminará de la nómina, se colacará en estado suspendido.");
					}
				}
				else
				{
					
					$lb_valido=$this->uf_delete_prestamos_personal($ls_codper);					
						
					
					if ($lb_valido)
					{
						$lb_valido=$this->uf_delete_proyecto_personal($ls_codper);					
						
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_delete_constantepersonal($ls_codper);					
						
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_delete_conceptopersonal($ls_codper);
						
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_delete_personal($ls_codper);
					}
					if($lb_valido)
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="PROCESS";
						$ls_descripcion ="Elimino la información relacionada (Prestamos, Conceptos, Constantes y Personal) ".
										 " del personal ".$ls_codper. " de la nómina ".$this->ls_codnom." ";
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					}
				}
				$rs_data3->MoveNext();
			}					
		}// fin del else
		return 	$lb_valido;
	}// uf_verificar_eliminar_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_prestamos_activos_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_prestamos_activos_personal
		//		   Access: private
		//      Arguments: as_codper  // Còdigo del personal
		//	      Returns:	$lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que busca los prestamos del personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="SELECT codper ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ".
				"   AND stapre=1 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Transferir Personal MÉTODO->uf_select_prestamos_activos_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
				$lb_valido=false;				
			}			
			$this->io_sql->free_result($rs_data);				
		}
		return $lb_valido;
	}// end function uf_select_prestamos_activos_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_suspender_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suspender_personal
		//		   Access: private
		//      Arguments: as_codper // código del personal
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que suspende al personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET stapre='4'  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_suspender_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_suspender_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_delete_prestamos_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_prestamos_personal
		//		   Access: private
		//      Arguments: as_codper // código del personal
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que elimina los prestamos del personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="DELETE FROM sno_prestamosamortizado ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_prestamos_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sno_prestamosperiodo ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_prestamos_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sno_prestamos ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_prestamos_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
	}// end function uf_delete_prestamos_personal
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_delete_proyecto_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_proyecto_personal
		//		   Access: private
		//      Arguments: as_codper // código del personal
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que elimina los proyectos del  personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="DELETE FROM sno_proyectopersonal ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_proyecto_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		
		return $lb_valido;
	}// end function uf_delete_prestamos_personal
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_delete_constantepersonal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constantepersonal
		//		   Access: private
		//      Arguments: as_codper // código del personal
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que elimina las constantes personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="DELETE FROM sno_constantepersonal ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		
		return $lb_valido;
	}// end function uf_delete_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_delete_conceptopersonal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptopersonal
		//		   Access: private
		//      Arguments: as_codper // código del personal
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que elimina los conceptos personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="DELETE FROM sno_conceptopersonal ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		
		return $lb_valido;
	}// end function uf_delete_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_delete_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_personal
		//		   Access: private
		//      Arguments: as_codper // código del personal
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que elimina el personal en la nòmina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/02/2009 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ls_sql="DELETE FROM sno_personalnomina ".				
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."'  ";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		
		return $lb_valido;
	}// end function uf_delete_personal
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
