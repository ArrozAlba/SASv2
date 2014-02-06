<?php
class sigesp_saf_class_reportbsf
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;

	function sigesp_saf_class_reportbsf()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->ds_detcontable=new class_datastore();
	}
	function uf_saf_load_movimiento($as_codemp,$as_cmpmov,$ad_fecdes,$ad_fechas,$as_tipcau,$as_coddes,$as_codhas,$ai_orden)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_movimiento
		//	           Access:   public
		//  		Arguments:   as_codemp  // codigo de empresa
		//  			         as_cmpmov  // comprobante de movimiento
		//  			         ad_fecdes  // fecha de inicio del periodo de busqueda
		//  			         ad_fechas  // fecha de cierre del periodo de busqueda
		//  			         as_tipcau  // tipo de la causa de movimiento
		//  			         as_coddes  // inicio de parametro de busqueda (codigo de activo)
		//  			         as_codhas  // cierre de parametro de busqueda (codigo de activo)
		//  			         ai_orden   // parametro que indica el orden de los resultados del reporte
		//	         Returns :   Retorna un Booleano
		//    	 Description :   Función que obtiene los datos maestros de un movimientos
		//         Creado por:   Ing. Luis Anibal Lang
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 27/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_sqlint=" AND saf_movimiento.feccmp >='".$ld_auxdesde."'".
					   " AND saf_movimiento.feccmp <='".$ld_auxhasta."'";
		}
		if(!empty($as_cmpmov))
		{
			$ls_sqlint= $ls_sqlint." AND saf_movimiento.cmpmov='".$as_cmpmov."'";
		}
		if(($as_coddes!="")&&($as_codhas!=""))
		{
			$ls_sqlint=$ls_sqlint." AND saf_dt_movimiento.codact >= '".$as_coddes."'".
								  " AND saf_dt_movimiento.codact <= '".$as_codhas."' ";
		}
		$ls_sql="SELECT saf_movimiento.cmpmov,saf_movimiento.codcau,saf_movimiento.descmp,saf_movimiento.feccmp,saf_causas.dencau".
				"  FROM saf_movimiento,saf_causas,saf_dt_movimiento".
				" WHERE saf_movimiento.codcau=saf_causas.codcau".
				"   AND saf_causas.tipcau='".$as_tipcau."'".
				"   AND saf_causas.estcat='".$ls_estcat."'".
				"   AND saf_movimiento.codemp='".$as_codemp."'".
				"   AND saf_dt_movimiento.cmpmov=saf_movimiento.cmpmov".$ls_sqlint.
				" GROUP BY saf_movimiento.cmpmov,saf_movimiento.codcau,saf_movimiento.descmp,saf_causas.dencau,".
				"          saf_movimiento.feccmp";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY saf_movimiento.cmpmov ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY saf_movimiento.cmpmov DESC";
			}
		}
		$rs_data=$this->io_sql->select($ls_sql);
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// fin function uf_saf_load_movimiento

	function uf_saf_load_dt_movimiento($as_codemp,$as_cmpmov,$as_codcau)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_dt_movimiento
		//	           Access:   public
		//  		Arguments:   as_codemp  // codigo de empresa
		//  			         as_cmpmov  // comprobante de movimiento
		//  			         as_codcau  // codigo de causa de movimiento
		//	         Returns :   Retorna un Booleano
		//    	 Description :   Función que obtiene los detalles de un movimiento
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 27/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sql=" SELECT saf_dt_movimiento.*, saf_dt_movimiento.monactaux as monact, ".
				"        (SELECT denact ".
				"           FROM saf_activo ".
				"          WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact ".
				"  FROM saf_dt_movimiento".
				" WHERE codemp='".$as_codemp."' ".
				"   AND cmpmov='".$as_cmpmov."' ".
				"   AND codcau='".$as_codcau."' ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_movimiento

	function uf_saf_load_dt_contable($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_dt_contable
		//	           Access:   public
		//  		Arguments:   as_codemp  // codigo de empresa
		//  			         as_cmpmov  // comprobante de movimiento
		//  			         as_codcau  // codigo de causa de movimiento
		//  			         ad_feccmp  // fecha del comprobante 
		//	         Returns :   Retorna un Booleano
		//    	 Description :   Función que obtiene los detalles contables de un movimiento
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 27/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codemp, cmpmov, codcau, ".
		        "       feccmp, codact, ideact, ".
				"       sc_cuenta, documento, ".
				"      debhab, monto, estint, ".
				"      montoaux as monto  ".
		        "  FROM saf_contable ".
				" WHERE codemp='". $as_codemp ."'".
				"   AND cmpmov='". $as_cmpmov ."'".
				"   AND codcau='". $as_codcau ."'".
				"   AND feccmp='". $ad_feccmp ."'";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_contable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detcontable->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_contable

	function uf_siv_load_dt_movreasignacion($as_codemp,$as_cmpmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_movreasignacion
		//         Access: private
		//      Argumento: as_codemp    // codigo de empresa
		//  			   as_cmpmov    // No de comprobante de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a una reasignacion de activos en la tabla saf_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado por: Ing. Yozelin Barragan           
		//Fecha de Cracion: 09/06/2006						Fecha de Ultima Modificación: 27/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql="SELECT saf_dt_traslado.*, saf_dt_movimiento.desmov,saf_dt_movimiento.monactaux as monact,".
				"      (SELECT feccmp".
				"         FROM saf_movimiento".
				"        WHERE saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov".
				"          AND saf_movimiento.codemp=saf_dt_movimiento.codemp) AS feccmp,".
				"      (SELECT descmp".
				"         FROM saf_movimiento".
				"        WHERE saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov ".
				"          AND saf_movimiento.codemp=saf_dt_movimiento.codemp) AS descmp,".
				"      (SELECT codcau".
				"         FROM saf_movimiento".
				"        WHERE saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov".
				"          AND saf_movimiento.codemp=saf_dt_movimiento.codemp) AS codcau,".
				"      (SELECT dencau".
				"         FROM saf_causas,saf_movimiento".
				"        WHERE saf_causas.codcau=saf_movimiento.codcau".
				"          AND saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov AND saf_causas.estcat='".$ls_estcat."') AS dencau,".
				"      (SELECT denact".
				"         FROM saf_activo".
				"        WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact".
				"  FROM saf_dt_traslado,saf_dt_movimiento".
				" WHERE saf_dt_traslado.cmpmov=saf_dt_movimiento.cmpmov".
				"   AND saf_dt_traslado.codact=saf_dt_movimiento.codact".
				"   AND saf_dt_traslado.ideact=saf_dt_movimiento.ideact".
				"   AND saf_dt_movimiento.codemp='".$as_codemp."'".
				"   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->reporte MÉTODO->uf_siv_load_dt_movreasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}//else
		return $lb_valido;
	} // end function uf_siv_load_dt_movreasignacion

	function uf_saf_load_modificacion($as_codemp,$as_cmpmov,$as_coddes,$as_codhas,$ad_fecdes,$ad_fechas,$ai_orden)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_modificacion
		//	           Access:   public
		//  		Arguments:   as_codemp  // codigo de empresa
		//  			         as_cmpmov  // comprobante de movimiento
		//  			         ad_fecdes  // fecha de inicio del periodo de busqueda
		//  			         ad_fechas  // fecha de cierre del periodo de busqueda
		//  			         as_coddes  // inicio de parametro de busqueda (codigo de activo)
		//  			         as_codhas  // cierre de parametro de busqueda (codigo de activo)
		//  			         ai_orden   // parametro que indica el orden de los resultados del reporte
		//	         Returns :   Retorna un Booleano
		//    	 Description :   Función que obtiene los datos maestros de un movimientos
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 27/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql="SELECT saf_movimiento.*,".
				"       (SELECT codact".
				"          FROM saf_partes".
				"         WHERE saf_movimiento.cmpmov=saf_partes.cmpmov".
				"           AND saf_movimiento.codemp=saf_partes.codemp".
				"         GROUP BY cmpmov,codact) AS codact,".
				"       (SELECT ideact".
				"          FROM saf_partes".
				"         WHERE saf_movimiento.cmpmov=saf_partes.cmpmov".
				"           AND saf_movimiento.codemp=saf_partes.codemp".
				"         GROUP BY cmpmov,ideact) AS ideact,".
				"       (SELECT dencau".
				"          FROM saf_causas".
				"         WHERE saf_movimiento.codcau=saf_causas.codcau AND saf_causas.estcat='".$ls_estcat."') AS dencau,".
				"       (SELECT denact".
				"          FROM saf_activo,saf_partes".
				"         WHERE saf_activo.codact=saf_partes.codact".
				"           AND saf_partes.cmpmov=saf_movimiento.cmpmov".
				"         GROUP BY cmpmov,denact) AS denact".
				"  FROM saf_movimiento,saf_dt_movimiento".
				" WHERE saf_movimiento.cmpmov IN (SELECT cmpmov FROM saf_partes GROUP BY cmpmov)".
				"   AND saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov".
				"   AND saf_movimiento.codemp='".$as_codemp."'";
		if($as_cmpmov)
		{
			$ls_sql=$ls_sql." AND saf_movimiento.cmpmov= '".$as_cmpmov."'";
		}
		if(($as_coddes!="")&&($as_codhas!=""))
		{
			$ls_sql=$ls_sql." AND saf_dt_movimiento.codact >= '".$as_coddes."'".
							" AND saf_dt_movimiento.codact <= '".$as_codhas."' ";
		}
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_sql=$ls_sql." AND saf_movimiento.feccmp >='". $ld_auxdesde ."'".
							" AND saf_movimiento.feccmp <='". $ld_auxhasta ."'";
		}
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY saf_movimiento.cmpmov";
			}
			else
			{
				$ls_sql=$ls_sql."ORDER BY saf_dt_movimiento.codact";
			}
		}

		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			print($this->io_sql->message);
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_modificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_modificacion

	function uf_saf_load_dt_modificacion($as_codemp,$as_cmpmov,$as_codact,$as_ideact)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_dt_modificacion
		//	           Access:   public
		//  		Arguments:   as_codemp  // codigo de empresa
		//  			         as_cmpmov  // comprobante de movimiento
		//  			         as_codact  // codigo de activo
		//  			         as_ideact  // identificador de activo
		//	         Returns :   Retorna un Booleano
		//    	 Description :   Función que obtiene los detalles de un movimiento
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 27/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codemp, codact, ideact,  ".
		        "       codpar, denpar, estpar,  ".
				"       cmpmov, vidautil,        ".
				"       cossalaux as cossal,     ".
		        "       montoaux as monto        ".
		        "  FROM saf_partes               ".
				" WHERE codemp='".$as_codemp."'  ".
				"   AND cmpmov='".$as_cmpmov."'  ".
				"   AND codact='".$as_codact."'  ".
				"   AND ideact='".$as_ideact."'  ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_modificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_modificacion

	function uf_saf_load_activos($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_status,$as_codrespri,
								 $as_codresuso,$as_coduniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_activos
		//	           Access:   public
		//  		Arguments:   $as_codemp    // codigo de empresa
		//  			         $ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         $ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         $ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         $as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         $as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//  			         $as_status    // estatus del activo 
		//  			         $as_codrespri // codigo de responsable primario
		//  			         $as_codresuso // codigo de responsable por uso
		//  			         $as_coduniadm // codigo de uniadad ejecutora
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de activos fijos, en base a los parametros indicados
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   14/06/2006						Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT saf_activo.codact,saf_activo.denact,saf_activo.maract,saf_activo.modact,saf_activo.feccmpact,".
				"       saf_activo.costoaux as costo".
				"  FROM saf_activo,saf_dta".
				" WHERE saf_activo.codemp='".$as_codemp."'".
				"   AND saf_activo.codemp=saf_dta.codemp".
				"   AND saf_activo.codact=saf_dta.codact";

		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sql=$ls_sql." AND saf_activo.codact >='". $as_coddesde ."'".
						    " AND saf_activo.codact <='". $as_codhasta ."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sql=$ls_sql." AND saf_activo.feccmpact >='". $ld_auxdesde ."'".
							" AND saf_activo.feccmpact <='". $ld_auxhasta ."'";
		}
		if(!empty($as_codrespri))
		{
			$ls_sql=$ls_sql." AND saf_dta.codrespri='".$as_codrespri."'";
		}
		if(!empty($as_codresuso))
		{
			$ls_sql=$ls_sql." AND saf_dta.codres='".$as_codresuso."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_sql=$ls_sql." AND saf_dta.coduniadm='".$as_coduniadm."'";
		}
		if($ai_ordenact==0)
		{$ls_order="codact";}
		else
		{$ls_order="denact";}
		if($as_status==1){$ls_sql=$ls_sql." AND saf_dta.estact='R'";}
		if($as_status==2){$ls_sql=$ls_sql." AND saf_dta.estact='I'";}
		if($as_status==3){$ls_sql=$ls_sql." AND saf_dta.estact='A'";}
		if($as_status==4){$ls_sql=$ls_sql." AND saf_dta.estact='M'";}
		if($as_status==5){$ls_sql=$ls_sql." AND saf_dta.estact='C'";}
		if($as_status==6){$ls_sql=$ls_sql." AND saf_dta.estact='D'";}
		
		$ls_sql=$ls_sql." GROUP BY saf_activo.codact,saf_activo.denact,saf_activo.maract,saf_activo.modact,saf_activo.feccmpact,".
				        "          saf_activo.costoaux".
						" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_activos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_activos
	
	function uf_saf_select_dt_activo($as_codemp,$as_codact,$as_status)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:  uf_saf_select_dt_activo
		//	           Access:  public
		//  		Arguments:  as_codemp     // codigo de empresa
		//  			        as_codact    // codigo de activo
		//  			        as_status    // parametro de busqueda. Estatus del activo
		//	         Returns :  Retorna un Booleano
		//	      Description:  Función que se encarga de obtener los detalles de un activo
		//         Creado por:  Ing. Luis Anibal Lang           
		//     Modificado por:  Ing. Yozelin Barragan           
		//   Fecha de Cracion:  14/06/2006						Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->con);
		$ls_sqlest="";
		if($as_status==1){$ls_sqlest=" AND saf_dta.estact='R'";}
		if($as_status==2){$ls_sqlest=" AND saf_dta.estact='I'";}
		if($as_status==3){$ls_sqlest=" AND saf_dta.estact='A'";}
		if($as_status==4){$ls_sqlest=" AND saf_dta.estact='M'";}
		if($as_status==5){$ls_sqlest=" AND saf_dta.estact='C'";}
		if($as_status==6){$ls_sqlest=" AND saf_dta.estact='D'";}
		$ls_sql="SELECT saf_dta.ideact,saf_dta.seract,saf_dta.idchapa,saf_dta.fecincact,saf_dta.fecdesact,saf_dta.estact,".
				"       (SELECT nomper FROM sno_personal ".
				"         WHERE saf_dta.codrespri=sno_personal.codper) AS nomrespri,".
				"       (SELECT apeper FROM sno_personal ".
				"         WHERE saf_dta.codrespri=sno_personal.codper) AS aperespri,".
				"       (SELECT nomper FROM sno_personal ".
				"         WHERE saf_dta.codres=sno_personal.codper) AS nomres,".
				"       (SELECT apeper FROM sno_personal ".
				"         WHERE saf_dta.codres=sno_personal.codper) AS aperes,".
				"       (SELECT denuniadm FROM spg_unidadadministrativa ".
				"         WHERE saf_dta.coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm".
				"  FROM saf_dta".
				" WHERE saf_dta.codemp='". $as_codemp ."'".
				$ls_sqlest.
				"   AND saf_dta.codact='". $as_codact ."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_select_dt_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_select_dt_activo

	function uf_saf_load_depactivos($as_codemp,$ai_ordenact,$as_coddesde,$as_codhasta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_depactivos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de depreciacion de activos fijos
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:  Ing. Yozelin Barragan           
		//   Fecha de Cracion:  14/06/2006						Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlcod="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlcod=" AND codact >='". $as_coddesde ."' AND codact <='". $as_codhasta ."'";
		}
		
		if($ai_ordenact==0)
		{
			$ls_order="codact";
		}
		else
		{
			$ls_order="denact";
		}
		$ls_sql="SELECT saf_depreciacion.codact,saf_depreciacion.ideact,".
				"      (SELECT denact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS denact,".
				"      (SELECT costoaux FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS costo,".
				"      (SELECT vidautil FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS vidautil,".
				"      (SELECT cossalaux FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS cossal,".
				"      (SELECT feccmpact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS feccmpact,".
				"      (SELECT fecincact FROM saf_dta".
				"        WHERE saf_dta.codact=saf_depreciacion.codact".
				"          AND saf_dta.ideact=saf_depreciacion.ideact".
				"          AND saf_dta.codemp=saf_depreciacion.codemp".
				"        GROUP BY saf_dta.codemp,saf_depreciacion.ideact,saf_depreciacion.codact,saf_dta.fecincact) AS fecincact".
				"  FROM saf_depreciacion".
				" WHERE codemp='".$as_codemp."'".
				$ls_sqlcod.
				" GROUP BY saf_depreciacion.codemp,saf_depreciacion.codact,saf_depreciacion.ideact".
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_depactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_depactivos
	
	function uf_saf_select_dt_depactivo($as_codemp,$as_codact,$as_ideact)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:  uf_saf_select_dt_depactivo
		//	           Access:  public
		//  		Arguments:  as_codemp  // codigo de empresa
		//  			        as_codact  // codigo de activo
		//  			        as_ideact  // identificador de activo
		//	         Returns :  Retorna un Booleano
		//	      Description:  Función que se encarga de obtener los detalles de un activo
		//         Creado por:  Ing. Luis Anibal Lang           
		//     Modificado por:  Ing. Yozelin Barragan           
		//   Fecha de Cracion:  14/06/2006						Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->con);
		$ls_sqlest="";
		$ls_sql="SELECT ideact,fecdep, mondepmenaux as mondepmen, ".
				"		mondepanoaux as mondepano,  ".
				"		mondepacuaux as mondepacu ".
                "  FROM saf_depreciacion           ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codact='".$as_codact."' ".
				"   AND ideact='".$as_ideact."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_select_dt_depactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_select_dt_depactivo
	
	
	function uf_saf_load_depmensual($as_codemp,$ai_ordenact,$ad_fecdep)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_depactivos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_fecdep    // fecha de busqueda de la depreciacion
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de depreciacion de activos fijos
		//         Creado por:   Ing. Luis Anibal Lang           
		//     Modificado por:  Ing. Yozelin Barragan           
		//   Fecha de Cracion:  07/08/2006						Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_fecdep=$this->io_funcion->uf_convertirdatetobd($ad_fecdep);
		if($ai_ordenact==0)
		{
			$ls_order="codact";
		}
		else
		{
			$ls_order="denact";
		}
		$ls_sql="SELECT saf_depreciacion.codact,saf_depreciacion.ideact, ".
		        "       saf_depreciacion.mondepmenaux as mondepmen, ".
				"       saf_depreciacion.mondepacuaux as mondepacu, ".
				"      (SELECT denact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS denact,".
				"      (SELECT costoaux FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS costo,".
				"      (SELECT vidautil FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS vidautil,".
				"      (SELECT cossalaux FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS cossal,".
				"      (SELECT feccmpact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS feccmpact,".
				"      (SELECT fecincact FROM saf_dta".
				"        WHERE saf_dta.codact=saf_depreciacion.codact".
				"          AND saf_dta.ideact=saf_depreciacion.ideact".
				"          AND saf_dta.codemp=saf_depreciacion.codemp".
				"        GROUP BY saf_depreciacion.ideact,saf_depreciacion.codact,saf_dta.fecincact) AS fecincact".
				"  FROM saf_depreciacion".
				" WHERE codemp='".$as_codemp."'".
				"   AND fecdep='".$ad_fecdep."'".
				" GROUP BY codemp,codact,ideact,mondepmen,mondepacu".
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_depactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_depactivos

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Listado de Catalogo SIGECOF                            //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_sigecof($as_codemp,$ai_ordenact,$as_coddesde,$as_codhasta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_sigecof
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el listado de SIGECOF
		//         Creado por:   Ing. Luis Anibal Lang     
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   06/09/2006						Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlcod="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlcod=" WHERE catalogo >='". $as_coddesde ."' AND catalogo <='". $as_codhasta ."'";
		}
		
		if($ai_ordenact==0)
		{
			$ls_order="catalogo";
		}
		else
		{
			$ls_order="dencat";
		}
		$ls_sql="SELECT * FROM saf_catalogo".
				$ls_sqlcod.
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_sigecof ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_sigecof

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Listado de Activos Fijos                               //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_defactivos($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_defactivos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el listado de activos fijos, en base a los parametros indicados
		//         Creado por:   Ing. Luis Anibal Lang  
		//     Modificado por:   Ing. Yozelin Barragan           
		//   Fecha de Cracion:   25/09/2006							Fecha de Ultima Modificación: 28/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND codact >='".$as_coddesde."'".
					   " AND codact <='".$as_codhasta."'";
		}
		
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND feccmpact >='".$ld_auxdesde."'".
							      " AND feccmpact <='".$ld_auxhasta."'";
		}
		if($ai_ordenact==0)
		{
			$ls_order="codact";
		}
		else
		{
			$ls_order="denact";
		}
		$ls_sql="SELECT codact,denact,maract,modact,catalogo,costoaux as costo,feccmpact".
				" FROM saf_activo".
				" WHERE codemp='".$as_codemp."' ".$ls_sqlint.
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_defactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_defactivos
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Comprobante de Incorporacion                           //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_unidadadministrativas($as_codemp,$as_coduniadm,&$as_denuniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_unidadadministrativas
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_coduniadm // codigo de unidad administrativa
		//  			         as_denuniadm // denominacion de la unidad administrativa
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener la denominacion de una unidad ejecutora
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   26/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT denuniadm".
				" FROM spg_unidadadministrativa".
				" WHERE codemp='".$as_codemp."'".
				" AND   coduniadm='".$as_coduniadm."'";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_unidadadministrativas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_denuniadm= $row["denuniadm"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_unidadadministrativas

	function uf_saf_load_responsable($as_codemp,$as_codper,&$as_nomper,&$as_cedper,&$as_cargo)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_responsable
		//	           Access:   public
		//  		Arguments:   as_codemp // codigo de empresa
		//  			         as_codper // codigo de personal
		//  			         as_nomper // nombre del personal
		//  			         as_cedper // cedula del personal
		//  			         as_cargo  // cargo del personal
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener el nombre de un personal
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   14/06/2006							Fecha de Ultima Modificación:   14/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT sno_personal.codper,sno_personal.cedper,sno_personal.nomper,sno_personal.apeper,".
				" CASE  sno_nomina.racnom WHEN 0 then sno_cargo.descar ELSE sno_asignacioncargo.denasicar END AS cargo".
			    " FROM  sno_personal, sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo ".
			 	" WHERE sno_personal.codemp='". $as_codemp ."'".
			    " AND sno_personal.codper='". $as_codper ."'".
			    " AND sno_nomina.espnom=0".
			    " AND sno_personal.codemp = sno_personalnomina.codemp".
			    " AND sno_personal.codper = sno_personalnomina.codper".
			    " AND sno_personalnomina.codemp = sno_nomina.codemp".
			    " AND sno_personalnomina.codnom = sno_nomina.codnom".
			    " AND sno_personalnomina.codemp = sno_cargo.codemp".
			    " AND sno_personalnomina.codnom = sno_cargo.codnom".
			    " AND sno_personalnomina.codcar = sno_cargo.codcar".
			    " AND sno_personalnomina.codemp = sno_asignacioncargo.codemp".
			    " AND sno_personalnomina.codnom = sno_asignacioncargo.codnom".
			    " AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_responsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_nomper= $row["apeper"].", ".$row["nomper"];
				$as_cedper= $row["cedper"];
				$as_cargo= $row["cargo"];
			}
			else
			{
				$this->io_msg->message("El personal no tiene cargo asociado");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_responsable

	function uf_saf_load_dt_compmovimiento($as_codemp,$as_cmpmov,$as_codres)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_compmovimiento
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         as_codres  // codigo de responsable primario
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   26/09/2006						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_int="";
		$this->io_sql=new class_sql($this->con);
		if($as_codres!="")
		{
			$ls_int= $ls_int." AND   saf_dta.codrespri='".$as_codres."'";
		}
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql=" SELECT saf_dt_movimiento.codemp,saf_dt_movimiento.cmpmov,saf_dt_movimiento.codact,".
				"        saf_dt_movimiento.ideact,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp,".
				"        COUNT(saf_dt_movimiento.ideact) AS cantidad,".
				"       (SELECT dencau FROM saf_causas".
				" 	      WHERE saf_causas.codcau=saf_dt_movimiento.codcau AND estcat='".$ls_estcat."') AS dencau,".
				"       (SELECT coduniadm FROM saf_dta".
				" 	      WHERE saf_dta.codact=saf_dt_movimiento.codact".
				"           AND saf_dta.ideact=saf_dt_movimiento.ideact) AS coduniadm,".
				"       (SELECT denact FROM saf_activo".
				" 	      WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact,".
				"       (SELECT catalogo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS catalogo,".
				"       (SELECT costoaux FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS costo".
				"  FROM saf_dt_movimiento,saf_dta".
				" WHERE saf_dt_movimiento.codemp='".$as_codemp."' ".
				"   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."' ".$ls_int.
				"   AND saf_dta.codemp=saf_dt_movimiento.codemp".
				"   AND saf_dta.codact=saf_dt_movimiento.codact".
				"   AND saf_dta.ideact=saf_dt_movimiento.ideact".
				" GROUP BY saf_dt_movimiento.codemp,saf_dt_movimiento.codact, ".
				"          saf_dt_movimiento.ideact,saf_dt_movimiento.cmpmov, ".
				"          saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_compmovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_compmovimiento
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Comprobante de Incorporacion                           //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_dt_compreasignacion($as_codemp,$as_cmpmov,$as_codrespri,$as_codresuso)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_compreasignacion
	//	           Access:   public
	//  		Arguments:   as_codemp     // codigo de empresa
	//  			         as_cmpmov     // comprobante de movimiento
	//  			         as_codrespri  // codigo de responsable primario
	//  			         as_codresuso  // codigo de responsable por uso
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   28/09/2006						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_int="";
		$this->io_sql=new class_sql($this->con);
		if($as_codrespri!="")
		{
			$ls_int= $ls_int." AND   saf_dta.codrespri='".$as_codrespri."'";
		}
		if($as_codresuso!="")
		{
			$ls_int= $ls_int." AND   saf_dta.codres='".$as_codresuso."'";
		}
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql=" SELECT saf_dt_movimiento.codemp,saf_dt_movimiento.cmpmov,saf_dt_movimiento.codact,".
				"        saf_dt_movimiento.ideact,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp,".
				"        COUNT(saf_dt_movimiento.ideact) AS cantidad,".
				"       (SELECT dencau FROM saf_causas".
				" 	      WHERE saf_causas.codcau=saf_dt_movimiento.codcau AND estcat='".$ls_estcat."') AS dencau,".
				"       (SELECT coduniadm FROM saf_dta".
				" 	      WHERE saf_dta.codact=saf_dt_movimiento.codact".
				"           AND saf_dta.ideact=saf_dt_movimiento.ideact) AS coduniadm,".
				"       (SELECT denact FROM saf_activo".
				" 	      WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact,".
				"       (SELECT catalogo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS catalogo,".
				"       (SELECT codpai FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS codpai,".
				"       (SELECT codest FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS codest,".
				"       (SELECT codmun FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS codmun,".
				"       (SELECT costo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS costo".
				"  FROM saf_dt_movimiento,saf_dta".
				" WHERE saf_dt_movimiento.codemp='".$as_codemp."' ".
				"   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."' ".
				$ls_int.
				"   AND saf_dta.codemp=saf_dt_movimiento.codemp".
				"   AND saf_dta.codact=saf_dt_movimiento.codact".
				"   AND saf_dta.ideact=saf_dt_movimiento.ideact".
				" GROUP BY saf_dt_movimiento.codemp,saf_dt_movimiento.codact,saf_dt_movimiento.ideact,saf_dt_movimiento.cmpmov,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_compreasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_compreasignacion
	
	function uf_saf_load_ubicacion($as_codpai,$as_codest,$as_codmun,&$as_denpai,&$as_denest,&$as_denmun)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_ubicacion
		//	           Access:   public
		//  		Arguments:   as_codpai // codigo de pais
		//  			         as_codest // codigo de estado
		//  			         as_codmun // codigo de municipio
		//  			         as_denpai // denominacion de pais
		//  			         as_denest // denominacion de estado
		//  			         as_denmun // denominacion de municipio
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener las denominaciones de un pais, estado y municipio
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   28/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT sigesp_municipio.denmun,".
				" (SELECT desest FROM sigesp_estados".
				"   WHERE sigesp_municipio.codpai=sigesp_estados.codpai".
				"   AND   sigesp_estados.codest='". $as_codest ."') AS denest,".
				" (SELECT despai FROM sigesp_pais".
				"   WHERE sigesp_municipio.codpai=sigesp_pais.codpai) AS denpai".
				" FROM  sigesp_municipio".
				" WHERE sigesp_municipio.codpai='".$as_codpai."'".
				" AND   sigesp_municipio.codest='".$as_codest."'".
				" AND   sigesp_municipio.codmun='".$as_codmun."'";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_ubicacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_denpai= $row["denpai"];
				$as_denest= $row["denest"];
				$as_denmun= $row["denmun"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_ubicacion
	
	function  uf_saf_select_last_date($as_codemp,$as_codact,$as_ideact,$ad_fecdep,$ai_mondepmen,$ai_mondepano,$ai_mondepacu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_last_date
		//         Access: public  
		//      Argumento: $as_codigo       //codigo de rotulacion
		//                 $as_denominacion //denominacion de la rotulacion
		//                 $as_empleo       //empleo de la rotulacion
		//                 $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo rotulacion en la tabla saf_rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_depreciacion (codemp, codact, ideact, fecdep, mondepmen, mondepano, mondepacu, estcon) ".
				  " VALUES('".$as_codemp."','".$as_codact."','".$as_ideact."','".$ad_fecdep."','".$ai_mondepmen."',".
				  "        '".$ai_mondepano."','".$ai_mondepacu."','0')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_select_last_date ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin uf_saf_insert_rotulacion

   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valor_config($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_valor_config
		//		   Access: public
		//	    Arguments: 
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		// Modificado por: Ing. Yozelin Barragan            
		// Fecha Creación: 21/05/2007 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
	   		    "  FROM sigesp_config ".
			    " WHERE codemp='".$as_codemp."' ".
			    "   AND codsis='SAF' ".
			    "   AND seccion='CATEGORIA' ".
			    "   AND entry='TIPO-CATEGORIA-CSG-CGR' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->articulo ->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_valor=trim($row["value"]);
				$lb_valido=true; 
			}
			else
			{
				$li_valor="0";
			}
		}
		return $li_valor;
	}// end function uf_select_config
   //----------------------------------------------------------------------------------------------------------------------------------

} //fin  class sigesp_siv_class_report
?>
