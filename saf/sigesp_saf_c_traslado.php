<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_traslado
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_traslado()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	function uf_saf_select_traslado($as_codemp,$as_cmpmov,$ad_fectraact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_fectraact // fecha del traslado del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un traslado en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 05/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_traslado".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND fectraact='". $ad_fectraact ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_select_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_traslado
	
	function uf_saf_select_dt_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$as_codact,$as_ideact,&$as_codres,&$as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_dt_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_fectraact // fecha del traslado del activo
		//                 $as_codact    //codigo del activo
		//                 $as_ideact    //identificación del elemento u objeto
		//                 $as_codres    //codigo de responsable anterior
		//                 $as_coduniadm //codigo de unidad administrativa anterior
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un detalle asociado a un traslado en la tabla saf_dt_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/04/2006 								Fecha Última Modificación : 07/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_dt_traslado".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND fectraact='". $ad_fectraact ."'".
				" AND codact='". $as_codact ."'".
				" AND ideact='". $as_ideact ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_select_dt_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codres=$row["codres"];
				$as_coduniadm=$row["coduniadm"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_dt_traslado

	function uf_saf_select_activo($as_codemp,$as_codact,$as_seract,$as_ideact,&$as_codres,&$as_nomres,&$as_coduniadm,&$as_denuniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activo
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo de activo
		//                 $as_seract //serial del activo
		//                 $as_ideact //identificador del activo
		//                 $as_codres //codigo de responsable del activo
		//                 $as_nomres //nombre del responsable del activo
		//                 $as_coduniadm //codigo de unidad administrativa
		//                 $as_denuniadm //denominacion de unidad administrativa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene serial, responsable y unidad administrativa relacionados con un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 05/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT saf_dta.*,spg_unidadadministrativa.denuniadm, sno_personal.nomper,sno_personal.apeper".
				  " FROM saf_dta,spg_unidadadministrativa,sno_personal  ".
				  " WHERE saf_dta.coduniadm=spg_unidadadministrativa.coduniadm".
				  " AND saf_dta.codres=sno_personal.codper".
				  " AND saf_dta.codemp='".$as_codemp."'".
				  " AND saf_dta.codact='".$as_codact."'". 
				  " AND saf_dta.ideact='".$as_ideact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_select_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_seract= $row["ideact"];
				$as_codres= $row["codres"];
				$as_nomres= $row["nomper"]." ".$row["apeper"];
				$as_denuniadm= $row["denuniadm"];
				$as_coduniadm= $row["coduniadm"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_activo
	
	function  uf_saf_insert_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$as_obstra,$as_codusureg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $ad_fectraact //fecha del traslado
		//                 $as_obstra //observaciones del cambio de responsable
		//                 $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de traslado de activos en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/04/2006 								Fecha Última Modificación : 06/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO saf_traslado (codemp,cmpmov,fectraact,obstra,codusureg) ".
					" VALUES('".$as_codemp."','".$as_cmpmov."','".$ad_fectraact."','".$as_obstra."','".$as_codusureg."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_insert_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Traslado ".$as_cmpmov." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_traslado

	function  uf_saf_insert_dt_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$as_codact,$as_ideact,$as_obstraact,$as_coduniadm,$as_codres,$as_coduniadmnew,$as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_cmpmov     //Nº del Comprobante del Movimiento
		//                 $ad_fectraact  //fecha del traslado
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $as_obstraact  //observacion del traslado
		//                 $as_coduniadm  //codigo de unidad administrativa actual
		//                 $as_codres     //codigo de responsable actual
		//                 $as_coduniadmnew //codigo de unidad administrativa nueva
		//                 $as_codresnew  //codigo de responsable nuevo
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de traslado de activos en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/04/2006 								Fecha Última Modificación : 06/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO saf_dt_traslado (codemp,cmpmov,fectraact,codact,ideact,obstraact,coduniadm,codres,coduniadmnew,codresnew) ".
				  " VALUES('".$as_codemp."','".$as_cmpmov."','".$ad_fectraact."','".$as_codact."','".$as_ideact."',".
				  " '".$as_obstraact."','".$as_coduniadm."','".$as_codres."','".$as_coduniadmnew."','".$as_codresnew."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_insert_dt_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Activo ". $as_codact ." al Traslado ".$as_cmpmov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_dt_traslado

	function uf_saf_update_dta($as_codemp,$as_codact,$as_ideact,$as_codresnew,$as_coduniadmnew,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dta
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codact       //codigo del activo
		//                 $as_ideact       //identificación del elemento u objeto
		//                 $as_codresnew    //codigo del nuevo responsable
		//                 $as_coduniadmnew //codigo de la nueva unidad administrativa
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables y la unidad administrativa de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta SET   codres='". $as_codresnew ."', coduniadm='". $as_coduniadmnew ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_dta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Responsable y la Unidad  del Activo ".$as_codact." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dta
	
	function uf_siv_load_dt_traslado($as_codemp,$as_cmpmov,$ad_fectraact,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_traslado
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_fectraact // fecha del traslado del activo
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a un traslado de activos en la tabla saf_dt_traslados
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/04/2006							Fecha Última Modificación : 07/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM saf_dt_traslado".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND fectraact='". $ad_fectraact ."'".
				" ORDER BY codact";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_siv_load_dt_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codact=    $row["codact"];
				$ld_fectraact= $row["fectraact"];
				$ls_idact=     $row["ideact"];
				$ls_obstraact= $row["obstraact"];
				$ls_coduniadm= $row["coduniadm"];
				$ls_codres=    $row["codres"];
				$ls_coduniadmnew= $row["coduniadmnew"];
				$ls_codresnew= $row["codresnew"];
				$ld_fectraact=$this->io_funcion->uf_convertirfecmostrar($ld_fectraact);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtfectraact".$ai_totrows." type=text id=txtfectraact".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ld_fectraact."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodact".$ai_totrows."    type=text id=txtcodact".$ai_totrows."    class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtidact".$ai_totrows."     type=text id=txtidact".$ai_totrows."     class=sin-borde size=17 maxlength=15 value='".$ls_idact."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtobstraact".$ai_totrows." type=text id=txtobstraact".$ai_totrows." class=sin-borde size=40 value='".$ls_obstraact."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtcoduniadm".$ai_totrows." type=text id=txtcoduniadm".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadm."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtcodres".$ai_totrows."    type=text id=txtcodres".$ai_totrows."    class=sin-borde size=12 maxlength=10 value='".$ls_codres."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtcoduniadmnew".$ai_totrows." type=text id=txtcoduniadmnew".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadmnew."' readonly>";
				$ao_object[$ai_totrows][8]="<input name=txtcodresnew".$ai_totrows." type=text id=txtcodresnew".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_codresnew."' readonly>";
				$ao_object[$ai_totrows][9]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0>";
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_load_dt_traslado

	function uf_saf_delete_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_fectraact // fecha del traslado del activo
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un traslado en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 05/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM saf_traslado".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND fectraact='". $ad_fectraact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_delete_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Traslado ".$as_cmpmov." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end function uf_saf_delete_traslado

	function uf_saf_delete_dt_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$as_codact,$as_ideact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_dt_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_fectraact // fecha del traslado del activo
		//                 $as_codact    //codigo del activo
		//                 $as_ideact    //identificación del elemento u objeto
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina el detalle asociado a  un traslado en la tabla saf_dt_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 05/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM saf_dt_traslado".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND fectraact='". $ad_fectraact ."'".
				" AND codact='". $as_codact ."'".
				" AND ideact='". $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_delete_dt_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Activo ". $as_codact ." del Traslado ".$as_cmpmov." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end function uf_saf_delete_dt_traslado


} 
?>
