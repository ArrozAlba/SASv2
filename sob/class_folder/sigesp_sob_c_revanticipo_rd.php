<?php

class sigesp_sob_c_revanticipo_rd
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_revanticipo_rd()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratista($as_codcon)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_contratista
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_sql="SELECT sob_asignacion.cod_pro". 
				"  FROM sob_contrato , sob_asignacion". 
				" WHERE sob_contrato.codemp='".$this->ls_codemp."'".
				"   AND sob_contrato.codcon='".$as_codcon."'".
				"   AND sob_contrato.codemp=sob_asignacion.codemp".
				"   AND sob_contrato.codasi=sob_asignacion.codasi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_select_contratista ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$ls_codpro=$row["cod_pro"];
			}			
		}	
		return $ls_codpro;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_estatus_recepcion($as_numrecdoc,$as_codpro,&$ab_registro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_estatus_recepcion
		//         Access: public  
		//      Argumento: $ab_registro  // indica si alguna de las recepciones de documentos ha sido pasada a otro estatus
		//  			   $as_numrecdoc // numero de la recepcion de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica el estatus que se encuentra la recepcion de documentos generada desde viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT estprodoc,estaprord".
		        "  FROM cxp_rd  ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numrecdoc='".$as_numrecdoc."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'".
				"   AND procede='SOBCON'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_scv_select_estatus_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ab_registro=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_estaprord=$row["estaprord"];
				$ls_estprodoc=$row["estprodoc"];
				if(($ls_estprodoc!="R")||($ls_estaprord!=0))
				{
					$ab_registro=false;
					break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_scv_select_estatus_recepcion
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_rd_scg($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_rd_scg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle contable de una recepcion de documentos.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 18/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_scg".
				" WHERE codemp='". $this->ls_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SOBCON'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_delete_dt_rd_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_delete_dt_rd_scg
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_rd_spg($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_rd_scg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle presupuestario de una recepcion de documentos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 18/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_spg".
				" WHERE codemp='". $this->ls_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SOBCON'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_delete_dt_rd_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_delete_dt_rd_scg
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_rd_cargos($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_rd_cargos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina los detalle de cargos de una recepcion de documentos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 18/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_cargos".
				" WHERE codemp='". $this->ls_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SOBCON'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_delete_rd_cargos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_delete_rd_cargos
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_rd_deducciones($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_rd_deducciones
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle de deducciones de una recepcion de documentos 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 18/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_deducciones".
				" WHERE codemp='". $this->ls_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SOBCON'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_delete_rd_cargos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_delete_rd_deducciones
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_rd_historico($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_rd_historico
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle contable de una recepcion de documentos generada por una solicitud de 
		//                 viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_historico_rd".
				" WHERE codemp='". $this->ls_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_delete_rd_historico ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_delete_rd_historico
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_rd($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_rd
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina los detalles de una recepcion de documentos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_delete_dt_rd_spg($as_numrecdoc,$as_codpro,$aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_dt_rd_scg($as_numrecdoc,$as_codpro,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_rd_cargos($as_numrecdoc,$as_codpro,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_rd_deducciones($as_numrecdoc,$as_codpro,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_rd_historico($as_numrecdoc,$as_codpro,$aa_seguridad);
		}
		
		return $lb_valido;
	} // end  function uf_delete_dt_rd
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_rd($as_numrecdoc,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_rd
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numrecdoc // numero de recepcion de documentos
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina las recepciones de documentos originadas de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd".
				" WHERE codemp='". $this->ls_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='----------'".
				"   AND procede='SOBCON'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_delete_rd ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó  la recepcion de documento ".$as_numrecdoc." mediante el reverso".
			                 " de los anticipos asociada a la Empresa ".$this->ls_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_delete_recepcion
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_scv_obtener_anticipos($as_codcon,$ad_fecregdes,$ad_fecreghas,&$ai_totrows,&$ao_object,&$ao_title,&$as_titletable)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_obtener_anticipos
		//         Access: public  
		//      Argumento: $as_codcon      // Codigo de Contrato
		//  			   $ad_fecregdes   // Fecha desde
		//  			   $ad_fecreghas   // Fecha hasta
		//  			   $ai_totrows     // Total de filas encontradas
		//  			   $ao_object      // Arreglo de objetos para pintar el grid
		//  			   $ao_title       // Arreglo de titulos del grid
		//  			   $as_titletable  // Titulo Principal del Grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los anticipos que se les proceso la Recepcion de Documentos para imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funcion->uf_convertirdatetobd($ad_fecregdes);
		$ad_fecreghas=$this->io_funcion->uf_convertirdatetobd($ad_fecreghas);
		$ls_sql= "SELECT codcon,codant,fecant,conant".
				 "  FROM sob_anticipo".
				 " WHERE codemp='".$this->ls_codemp."'".
				 "   AND estgenrd='1'".
				 "   AND codcon LIKE '%".$as_codcon."%'".
				 "   AND fecant >='".$ad_fecregdes."'".
				 "   AND fecant <='".$ad_fecreghas."'".
				 " ORDER BY codcon,codant ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_scv_obtener_anticipos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			$ao_title[1]="Contrato";
			$ao_title[2]="Anticipo";
			$ao_title[3]="Concepto";
			$ao_title[4]="Fecha";
			$ao_title[5]="";
			$as_titletable="Anticipos";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_codcon= $row["codcon"];
				$ls_codant= $row["codant"];
				$ls_conant= $row["conant"];
				$ld_fecant= $row["fecant"];
				$ld_fecant=$this->io_funcion->uf_convertirfecmostrar($ld_fecant);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodcon".$ai_totrows." type=text id=txtcodcon".$ai_totrows." class=sin-borde size=18 value='".$ls_codcon."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodant".$ai_totrows." type=text id=txtcodant".$ai_totrows." class=sin-borde size=10 value='".$ls_codant."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtconant".$ai_totrows." type=text id=txtconant".$ai_totrows." class=sin-borde size=50 value='".$ls_conant."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtfecant".$ai_totrows." type=text id=txtfecant".$ai_totrows." class=sin-borde size=10 value='".$ld_fecant."' readonly>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodcon".$ai_totrows." type=text id=txtcodcon".$ai_totrows." class=sin-borde size=18 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodant".$ai_totrows." type=text id=txtcodant".$ai_totrows." class=sin-borde size=10 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtconant".$ai_totrows." type=text id=txtconant".$ai_totrows." class=sin-borde size=50 readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtfecant".$ai_totrows." type=text id=txtfecant".$ai_totrows." class=sin-borde size=10 readonly>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	}// fin de la function uf_scv_obtener_anticipos
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_scv_obtener_valuaciones($as_codcon,$ad_fecregdes,$ad_fecreghas,&$ai_totrows,&$ao_object,&$ao_title,&$as_titletable)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_obtener_valuaciones
		//         Access: public  
		//      Argumento: $as_codcon      // Codigo de Contrato
		//  			   $ad_fecregdes   // Fecha desde
		//  			   $ad_fecreghas   // Fecha hasta
		//  			   $ai_totrows     // Total de filas encontradas
		//  			   $ao_object      // Arreglo de objetos para pintar el grid
		//  			   $ao_title       // Arreglo de titulos del grid
		//  			   $as_titletable  // Titulo Principal del Grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las valuaciones que se les proceso la Recepcion de Documentos para imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funcion->uf_convertirdatetobd($ad_fecregdes);
		$ad_fecreghas=$this->io_funcion->uf_convertirdatetobd($ad_fecreghas);
		$ls_sql= "SELECT codcon,codval,obsval,fecha".
				 "  FROM sob_valuacion".
				 " WHERE codemp='".$this->ls_codemp."'".
				 "   AND estgenrd='1'".
				 "   AND codcon LIKE '%".$as_codcon."%'".
				 "   AND fecha >='".$ad_fecregdes."'".
				 "   AND fecha <='".$ad_fecreghas."'".
				 " ORDER BY codcon,codval ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revanticipo MÉTODO->uf_scv_obtener_valuaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			$ao_title[1]="Contrato";
			$ao_title[2]="Valuacion";
			$ao_title[3]="Concepto";
			$ao_title[4]="Fecha";
			$ao_title[5]="";
			$as_titletable="Valuaciones";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_codcon= $row["codcon"];
				$ls_codval= $row["codval"];
				$ls_obsval= $row["obsval"];
				$ld_fecha= $row["fecha"];
				$ld_fecha=$this->io_funcion->uf_convertirfecmostrar($ld_fecha);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodcon".$ai_totrows." type=text id=txtcodcon".$ai_totrows." class=sin-borde size=18 value='".$ls_codcon."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodval".$ai_totrows." type=text id=txtcodval".$ai_totrows." class=sin-borde size=10 value='".$ls_codval."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtobsval".$ai_totrows." type=text id=txtobsval".$ai_totrows." class=sin-borde size=50 value='".$ls_obsval."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtfecval".$ai_totrows." type=text id=txtfecval".$ai_totrows." class=sin-borde size=10 value='".$ld_fecha."' readonly>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodcon".$ai_totrows." type=text id=txtcodcon".$ai_totrows." class=sin-borde size=18 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodval".$ai_totrows." type=text id=txtcodval".$ai_totrows." class=sin-borde size=10 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtobsval".$ai_totrows." type=text id=txtobsval".$ai_totrows." class=sin-borde size=50 readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtfecval".$ai_totrows." type=text id=txtfecval".$ai_totrows." class=sin-borde size=10 readonly>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	}// fin de la function uf_scv_obtener_valuaciones
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_anticipo($as_codcon,$as_codant,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_anticipo
		//         Access: public  
		//      Argumento: $as_codcon    // codigo de contrato
		//  			   $as_codant    // codigo de anticipo
		//        		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" UPDATE sob_anticipo".
				"    SET estgenrd=0".
				"  WHERE codemp='".$this->ls_codemp."'".
				"    AND codcon='".$as_codcon."'".
				"    AND codant='".$as_codant."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo METODO->uf_update_estatus_anticipo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion= "Modifico el estatus de R.D. del anticipo ".$as_codant." Asociada al contrato ".$as_codcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_update_estatus_anticipo
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_valuacion($as_codcon,$as_codval,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_valuacion
		//         Access: public  
		//      Argumento: $as_codcon    // codigo de contrato
		//  			   $as_codval    // codigo de valuacion
		//        		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/05/2008							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" UPDATE sob_anticipo".
				"    SET estgenrd=0".
				"  WHERE codemp='".$this->ls_codemp."'".
				"    AND codcon='".$as_codcon."'".
				"    AND codval='".$as_codval."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->revanticipo METODO->uf_update_estatus_valuacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion= "Modifico el estatus de R.D. del anticipo ".$as_codant." Asociada al contrato ".$as_codcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_update_estatus_valuacion
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

}//end  class sigesp_scv_c_revcalcularviaticos
?>
