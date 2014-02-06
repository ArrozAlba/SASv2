<?php
class sigesp_siv_c_registroactivo
{
	function sigesp_siv_c_registroactivo()
	{
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in= new sigesp_include();
		$this->con= $in->uf_conectar();
		$this->io_sql= new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_msg= new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_load_codigoactivo($as_codart,&$as_codact)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_codigoactivo
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//	  Description: Funcion que obtiene el codigo del activo asociado al articulo.
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codact".
				"  FROM siv_articulo".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codart='".$as_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_load_codigoactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codact=$row["codact"];				
			}
			else
			{
				$as_codact="---------------";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_siv_select_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_make_seriales($as_codact,$ai_canart,&$aa_object,&$aa_title)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_make_serialess
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//	  Description: Funcion que obtiene el codigo del activo asociado al articulo.
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_title[1]="Serial";
		$aa_title[2]="Identificador";
		$aa_title[3]="Chapa";
		$aa_title[4]="Observaciones";
		for($li_i=1;$li_i<=$ai_canart;$li_i++)
		{
			$aa_object[$li_i][1]="<input name=txtseract".$li_i."     type=text id=txtseract".$li_i."     class=sin-borde size=25 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);'>";
			$aa_object[$li_i][2]="<input name=txtidact".$li_i."      type=text id=txtidact".$li_i."      class=sin-borde size=25 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
			$aa_object[$li_i][3]="<input name=txtidchapa".$li_i."    type=text id=txtidchapa".$li_i."    class=sin-borde size=25 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' onBlur='ue_rellenarcampo(this,15)'>";
			$aa_object[$li_i][4]="<input name=txtobsideact".$li_i."  type=text id=txtobsideact".$li_i."  class=sin-borde size=60 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);'>";
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_update_estatusactivo($as_numordcom,$as_numconrec,$as_codart) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_estatusactivo
		//         Access: public 
		//      Argumento: $as_numordcom //Numero de Orden de Compre /FActura
		//				   $as_numconrec //Numero Consecutivo de Recepcion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de generacion de Activos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql= "UPDATE siv_dt_recepcion".
				 "   SET estregact=1".
				 " WHERE codemp='".$this->ls_codemp."'".
				 "   AND numordcom='".$as_numordcom."'".
				 "   AND numconrec='".$as_numconrec."'".
				 "   AND codart='".$as_codart."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_update_estatusactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	    return $lb_valido;
	} // end  function uf_siv_update_estatusactivo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_remake_seriales($as_seract,$as_idact,$as_idchapa,$as_obsideact,$ai_i,&$aa_object)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_remake_seriales
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//	  Description: Funcion que obtiene el codigo del activo asociado al articulo.
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_i][1]="<input name=txtseract".$ai_i."     type=text id=txtseract".$ai_i."     value='".$as_seract."' class=sin-borde size=25 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);'>";
		$aa_object[$ai_i][2]="<input name=txtidact".$ai_i."      type=text id=txtidact".$ai_i."      value='".$as_idact."' class=sin-borde size=25 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
		$aa_object[$ai_i][3]="<input name=txtidchapa".$ai_i."    type=text id=txtidchapa".$ai_i."    value='".$as_idchapa."' class=sin-borde size=25 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' onBlur='ue_rellenarcampo(this,15)'>";
		$aa_object[$ai_i][4]="<input name=txtobsideact".$ai_i."  type=text id=txtobsideact".$ai_i."  value='".$as_obsideact."' class=sin-borde size=60 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);'>";
	}
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_saf_insert_dta($as_codart,$as_codact,$ai_canart,$as_numordcom,$as_numconrec,&$aa_object,&$aa_title,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_activo
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//				   as_codact  // Codigo del Activo
		//				   as_numordcom  // Numero de Orden de Compra
		//				   as_numconrec  // Numero consecutivo de Recepcion
		//			       aa_seguridad  // Arreglo de parametros de seguridad
		//	  Description: Funcion que Inserta un Articulo como Activo Fijo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 18/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_title[1]="Serial";
		$aa_title[2]="Identificador";
		$aa_title[3]="Chapa";
		$aa_title[4]="Observaciones";
		$lb_valido=true;
		$lb_existe=$this->uf_siv_select_estatusactivo($as_numordcom,$as_numconrec,$as_codart);
		if(!$lb_existe)
		{
			$this->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$ai_canart;$li_i++)
			{
				$ls_seract=$_POST["txtseract".$li_i];
				$ls_idact=$_POST["txtidact".$li_i];
				$ls_idchapa=$_POST["txtidchapa".$li_i];
				$ls_obsideact=$_POST["txtobsideact".$li_i];
				$lb_ok=$this->uf_siv_remake_seriales($ls_seract,$ls_idact,$ls_idchapa,$ls_obsideact,$li_i,&$aa_object);
				$lb_existe=$this->uf_siv_select_dta($as_codact,$ls_idact);
				if(!$lb_existe)
				{
					$ls_sql="INSERT INTO saf_dta (codemp, codact, ideact, seract, idchapa, obsideact, codusureg, estact)".
							" VALUES ('".$this->ls_codemp."','".$as_codact."','".$ls_idact."','".$ls_seract."','".$ls_idchapa."',".
							"		  '".$ls_obsideact."','".$this->ls_logusr."','R')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_saf_insert_dta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
						break;
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó el Detalle de Activo ".$ls_idact." Asociada al Activo ".$as_codact;
						$lb_valido=$this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					}
				}
				else
				{
					$this->io_msg->message("El Identificador ".$ls_idact." ya existe");
					$lb_valido=false;
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_siv_update_estatusactivo($as_numordcom,$as_numconrec,$as_codart);
			}
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_msg->message("El registro de Activos fue exitoso");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_msg->message("Ocurrio un error al registrar los Activos");
			}
		}
		else
		{
			$this->io_msg->message("El Registro de los Activos se habia procesado anteriormente");
			print "<script language=JavaScript>";
			print "close();" ;
			print "</script>";
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_activo
	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_dta($as_codact,$as_idact)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dta
		//		   Access: public
		//		 Argumens: as_codact  // Codigo de Activo
		//				   as_idact   // Identificador de Activo
		//	  Description: Funcion que verifica la existencia de un detalle de activo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 18/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codact".
				"  FROM saf_dta".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codact='".$as_codact."'".
				"   AND ideact='".$as_idact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_select_dta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;				
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_siv_select_dta
	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_estatusactivo($as_numordcom,$as_numconrec,$as_codart)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_estatusactivo
		//		   Access: public
		//		 Argumens: as_numordcom  // Numero de Orden de Compra
		//				   as_numconrec   // Numero Consecutivo de Recepcion
		//	  Description: Funcion que verifica la existencia de un detalle de activo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 18/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numordcom".
				"  FROM siv_dt_recepcion".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numordcom='".$as_numordcom."'".
				"   AND numconrec='".$as_numconrec."'".
				"   AND codart='".$as_codart."'".
				"   AND estregact=1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_select_estatusactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;				
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_siv_select_estatusactivo
	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_make_activos($as_codact,$ai_canart,&$aa_object,&$aa_title,&$li_i)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_make_serialess
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//	  Description: Funcion que obtiene el codigo del activo asociado al articulo.
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_title[1]="Serial";
		$aa_title[2]="Identificador";
		$aa_title[3]="Chapa";
		$aa_title[4]="Observaciones";
		$aa_title[5]="";
		$ls_sql="SELECT ideact,seract,idchapa,obsideact".
				"  FROM saf_dta".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codact='".$as_codact."'".
				"   AND estact='R'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_select_estatusactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_ideact=$row["ideact"];
				$ls_seract=$row["seract"];
				$ls_idchapa=$row["idchapa"];
				$ls_obsideact=$row["obsideact"];
				$li_i++;
				$aa_object[$li_i][1]="<input name=txtseract".$li_i."     type=text id=txtseract".$li_i."     value='".$ls_seract."'    class=sin-borde size=25 maxlength=20 readonly>";
				$aa_object[$li_i][2]="<input name=txtidact".$li_i."      type=text id=txtidact".$li_i."      value='".$ls_ideact."'    class=sin-borde size=25 maxlength=15 readonly>";
				$aa_object[$li_i][3]="<input name=txtidchapa".$li_i."    type=text id=txtidchapa".$li_i."    value='".$ls_idchapa."'   class=sin-borde size=25 maxlength=15 readonly>";
				$aa_object[$li_i][4]="<input name=txtobsideact".$li_i."  type=text id=txtobsideact".$li_i."  value='".$ls_obsideact."' class=sin-borde size=55 maxlength=15 readonly>";
				$aa_object[$li_i][5]="<input type=checkbox name=chkincorporar".$li_i." value=1 />";
			}
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_saf_incorporaractivo($as_codact,$as_ideact,$as_codart,$as_numorddes,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_incorporaractivo
		//         Access: public 
		//      Argumento: $as_codact //Codigo de Activo
		//				   $as_ideact //Identificador de Activo
		//				   $as_codart //Codigo de Articulo
		//				   $as_numorddes //Numero de Orden de Despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si ya se genero una incorporacion a partir de este despacho y en caso negativo
		//				   lo realiza
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql= "UPDATE saf_dta".
				 "   SET estact='I',".
				 "       fecincact='".date("Y-m-d")."'".
				 " WHERE codemp='".$this->ls_codemp."'".
				 "   AND codact='".$as_codact."'".
				 "   AND ideact='".$as_ideact."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_saf_incorporaractivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Incorporó el Detalle de Activo ".$as_ideact." Asociada al Activo ".$as_codact;
			$lb_valido=$this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=$this->uf_siv_update_estatusincorporacionactivo($as_codart,$as_numorddes);
		}
		if($lb_valido)
		{
			$this->io_sql->commit();
			$this->io_msg->message("La Incorporacion de Activos fue exitosa");
			print "<script language=JavaScript>";
			print "close();" ;
			print "</script>";
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_msg->message("Ocurrio un error al incorporar los Activos");
			print "<script language=JavaScript>";
			print "close();" ;
			print "</script>";
		}
	    return $lb_valido;
	} // end  function uf_saf_incorporaractivo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_incorporaciondespacho($as_codart,$as_numorddes)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_incorporaciondespacho
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//				   as_numorddes   // Numero de Orden de Despacho
		//	  Description: Funcion que verifica la existencia de una incorporacion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 18/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numorddes".
				"  FROM siv_dt_despacho".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numorddes='".$as_numorddes."'".
				"   AND codart='".$as_codart."'".
				"   AND estincact=1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_saf_select_incorporaciondespacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;				
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_saf_select_incorporaciondespacho
	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_update_estatusincorporacionactivo($as_codart,$as_numorddes) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_estatusincorporacionactivo
		//         Access: public 
		//		 Argumens: as_codart  // Codigo de Articulo
		//				   as_numorddes   // Numero de Orden de Despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de generacion de Activos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql= "UPDATE siv_dt_despacho".
				 "   SET estincact=1".
				 " WHERE codemp='".$this->ls_codemp."'".
				 "   AND numorddes='".$as_numorddes."'".
				 "   AND codart='".$as_codart."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_update_estatusincorporacionactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
		}
	    return $lb_valido;
	} // end  function uf_siv_update_estatusincorporacionactivo
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>
