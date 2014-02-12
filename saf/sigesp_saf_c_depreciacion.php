<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_depreciacion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_depreciacion()
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
	
	function uf_saf_load_activo($as_codemp,$as_codact,&$ai_costo,&$ai_cossal,&$ai_vidautil,&$ad_feccmpact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_activo
		//         Access: public 
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codact   //codigo de activo
		//                 $ai_costo    //costo del activo
		//                 $ai_cossal   //costo de salvamiento
		//                 $ai_vidautil //vida util del activo
		//                 $ad_feccmpact //fecha de compra del activo
		//                 $ad_fecincact //fecha de incorporacion del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos de costo, costo de salvamiento y vida util
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 22/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_activo".
				" WHERE codemp='". $as_codemp ."'".
				" AND codact='". $as_codact ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_load_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_costo=$row["costo"];
				$ai_cossal=$row["cossal"];
				$ai_vidautil=$row["vidautil"];
				$ad_feccmpact=$row["feccmpact"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_load_activo

	function uf_saf_load_incorporacion($as_codemp,$as_codact,$as_ideact,&$ad_fecinc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_incorporacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $ad_fecinc     //fecha de incorporacion del actvo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la fecha de incorporacion del activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/05/2006 								Fecha Última Modificación : 23/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_dta ".
				" WHERE codemp='".$as_codemp."' ".
				" AND codact='".$as_codact."' ".
				" AND ideact='".$as_ideact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_load_incorporacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ad_fecinc= $this->io_funcion->uf_formatovalidofecha($row["fecincact"]);
				//$ad_fecinc=$row["fecincact"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_load_depreciacion
	
	function uf_saf_insert_depreciacion($as_codemp,$as_codact,$as_ideact,$ad_fecdep,$ai_mondepmen,$ai_mondepanu,$ai_mondepacu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_depreciacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $ad_fecdep     //fecha de depreciacion del activo
		//                 $ai_mondepmen  //monto de depreciacion del mes
		//                 $ai_mondepanu  //monto de depreciacion anual
		//                 $ai_mondepacu  //monto de depreciacion acumulada
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que registra en la base de datos los datos de la depreciacion de un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 24/05/2006 								Fecha Última Modificación : 27/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" INSERT INTO saf_depreciacion (codemp,codact,ideact,fecdep,mondepmen,mondepano,mondepacu,estcon)".
				" VALUES('".$as_codemp."','".$as_codact."','".$as_ideact."','".$ad_fecdep."','".$ai_mondepmen."',".
				"        '".$ai_mondepanu."','".$ai_mondepacu."',0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_insert_depreciacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Se Calculo la depreciacion del activo  ".$as_codact."  Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}

	function uf_saf_update_depreciacion($as_codemp,$as_codact,$as_ideact,$ad_fecdep,$ai_mondepmen,$ai_mondepanu,$ai_mondepacu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_depreciacion
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $ad_fecdep     //fecha de depreciacion del activo
		//                 $ai_mondepmen  //monto de depreciacion del mes
		//                 $ai_mondepanu  //monto de depreciacion anual
		//                 $ai_mondepacu  //monto de depreciacion acumulada
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los valores de la depreciacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 27/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " UPDATE saf_depreciacion ".
		          " SET    mondepmen='".$ai_mondepmen."', ".
				  "        mondepano='".$ai_mondepanu."', ".
				  "        mondepacu='".$ai_mondepacu."' ".
				  " WHERE  codemp='".$as_codemp."' ".
			   	  " AND    codact='".$as_codact."' ".
				  " AND    ideact='".$as_ideact."' ".
				  " AND    fecdep='".$ad_fecdep."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_update_depreciacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Recalculó la depreciación del Activo ".$as_codact." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtadesincorporacion
	function uf_saf_select_statusdepreciacion($as_codemp,$as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_statusdepreciacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codact    //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un activo es depreciable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 22/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_activo ".
				" WHERE codemp='".$as_codemp."' ".
				" AND codact='".$as_codact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_select_statusdepreciacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_depreciable=$row["estdepact"];
				if($ls_depreciable=="1")
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_statusdepreciacion

	function uf_saf_select_depreciacion($as_codemp,$as_codact,$as_ideact,$ad_fecdep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_depreciacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codact    //codigo de activo
		//                 $as_ideact    //identificador del activo
		//                 $ad_fecdep    //fecha de la depreciacion del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si ya existe una depreciacion para un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 22/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_depreciacion ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codact='".$as_codact."' ".
				"   AND ideact='".$as_ideact."'";
		if($ad_fecdep!="")
		{
			$ls_sql=$ls_sql."AND fecdep='". $ad_fecdep ."'";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_select_depreciacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end function uf_saf_select_depreciacion

	function uf_saf_load_depreciacion($as_codemp,$as_codact,$as_ideact,$ad_fecmod,&$ai_mondepacu,&$ad_fecdep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_depreciacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codact    //codigo de activo
		//                 $as_ideact    //identificador del activo
		//                 $ad_fecmod    //fecha de la modificacion del activo
		//                 $ai_mondepacu //monto de la depreciacion
		//                 $ad_fecdep    //fecha de la depreciacion del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si ya existe una depreciacion para un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 22/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_depreciacion ".
				" WHERE codemp='".$as_codemp."' ".
				" AND ideact='".$as_ideact."' ".
			    " AND fecdep<='".$ad_fecmod."' ".
				" ORDER BY fecdep DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->depreciacion MÉTODO->uf_saf_load_depreciacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_mondepacu=$row["mondepacu"];
				$ad_fecdep=$row["fecdep"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_load_depreciacion

} 
?>