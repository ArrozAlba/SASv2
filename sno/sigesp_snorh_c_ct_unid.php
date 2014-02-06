<?php
class sigesp_snorh_c_ct_unid
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_ct_unid()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_ct_unid
		//		   Access: public (sigesp_snorh_d_ct_unid)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_ct_unid
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ct_unid)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_unidad($as_codcestic,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_unidad
		//		   Access: public (sigesp_snorh_d_ct_unid)
		//	    Arguments: as_codcestic  // código de Cesta Ticket
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todas las unidades administrativas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_totrows=0;
		$ls_sql="SELECT sno_cestaticunidadadm.minorguniadm, sno_cestaticunidadadm.ofiuniadm, sno_cestaticunidadadm.uniuniadm, ".
				"		sno_cestaticunidadadm.depuniadm,sno_cestaticunidadadm.prouniadm, sno_unidadadmin.desuniadm, ".
				"		sno_cestaticunidadadm.est1cestic, sno_cestaticunidadadm.est2cestic ".
				"  FROM sno_cestaticunidadadm, sno_unidadadmin ".
				" WHERE sno_cestaticunidadadm.codemp = '".$this->ls_codemp."' ".
				"   AND sno_cestaticunidadadm.codcestic = '".$as_codcestic."' ".
				"   AND sno_cestaticunidadadm.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_cestaticunidadadm.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_cestaticunidadadm.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_cestaticunidadadm.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_cestaticunidadadm.prouniadm = sno_unidadadmin.prouniadm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Unidad MÉTODO->uf_load_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codigo=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];
				$ls_desuniadm=$row["desuniadm"];
				$ls_est1cestic=$row["est1cestic"];
				$ls_est2cestic=$row["est2cestic"];	
				$ao_object[$ai_totrows][1]="<input name=txtcodigo".$ai_totrows." type=text id=txtcodigo".$ai_totrows." value=".$ls_codigo." class=sin-borde size='20' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdesuniadm".$ai_totrows." type=text id=txtdesuniadm".$ai_totrows." value='".$ls_desuniadm."' class=sin-borde size='40' title='".$ls_desuniadm."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtest1cestic".$ai_totrows." type=text id=txtest1cestic".$ai_totrows." value='".$ls_est1cestic."' class=sin-borde size='8' maxlength='5' onKeyUp=ue_validarcomillas(this);>";
				$ao_object[$ai_totrows][4]="<input name=txtest2cestic".$ai_totrows." type=text id=txtest2cestic".$ai_totrows." value='".$ls_est2cestic."' class=sin-borde size='25' maxlength='22' onKeyUp=ue_validarcomillas(this);>";
				$ao_object[$ai_totrows][5]="<input name=chkaplica".$ai_totrows." type=checkbox id=chkaplica".$ai_totrows." value='1' class=sin-borde checked>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT minorguniadm,ofiuniadm, uniuniadm, depuniadm,prouniadm, desuniadm ".
					"  FROM sno_unidadadmin ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codemp NOT IN (SELECT codemp FROM sno_cestaticunidadadm  ".
					" 						WHERE sno_cestaticunidadadm.codemp = '".$this->ls_codemp."' ".
					"   					  AND sno_cestaticunidadadm.codcestic = '".$as_codcestic."' ".
					"						  AND sno_cestaticunidadadm.minorguniadm = sno_unidadadmin.minorguniadm ".
					"						  AND sno_cestaticunidadadm.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					" 						  AND sno_cestaticunidadadm.uniuniadm = sno_unidadadmin.uniuniadm ".
					"						  AND sno_cestaticunidadadm.depuniadm = sno_unidadadmin.depuniadm ".
					"						  AND sno_cestaticunidadadm.prouniadm = sno_unidadadmin.prouniadm) ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Unidad MÉTODO->uf_load_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_totrows=$ai_totrows+1;
					$ls_codigo=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];
					$ls_desuniadm=$row["desuniadm"];
					$ls_est1cestic="";
					$ls_est2cestic="";	
					$ao_object[$ai_totrows][1]="<input name=txtcodigo".$ai_totrows." type=text id=txtcodigo".$ai_totrows." value=".$ls_codigo." class=sin-borde size='20' readonly>";
					$ao_object[$ai_totrows][2]="<input name=txtdesuniadm".$ai_totrows." type=text id=txtdesuniadm".$ai_totrows." value='".$ls_desuniadm."' class=sin-borde size='40' title='".$ls_desuniadm."' readonly>";
					$ao_object[$ai_totrows][3]="<input name=txtest1cestic".$ai_totrows." type=text id=txtest1cestic".$ai_totrows." value='".$ls_est1cestic."' class=sin-borde size='8' maxlength='5' onKeyUp=ue_validarcomillas(this);>";
					$ao_object[$ai_totrows][4]="<input name=txtest2cestic".$ai_totrows." type=text id=txtest2cestic".$ai_totrows." value='".$ls_est2cestic."' class=sin-borde size='25' maxlength='22' onKeyUp=ue_validarcomillas(this);>";
					$ao_object[$ai_totrows][5]="<input name=chkaplica".$ai_totrows." type=checkbox id=chkaplica".$ai_totrows." value='1' class=sin-borde>";
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}// end function uf_load_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_unidad($as_codigo,$as_codcestic)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_unidad
		//		   Access: private
 		//	    Arguments: as_codigo  // código de la Unidad administrativa
 		//	    		   as_codcestic  // código del método de Cesta ticket
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la unidad por cesta tciket está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_minorguniadm=substr($as_codigo,0,4);
		$ls_ofiuniadm=substr($as_codigo,4,2);
		$ls_uniuniadm=substr($as_codigo,6,2);
		$ls_depuniadm=substr($as_codigo,8,2);
		$ls_prouniadm=substr($as_codigo,10,2);
		$ls_sql="SELECT codemp ".
				"  FROM sno_cestaticunidadadm ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND minorguniadm='".$ls_minorguniadm."' ".
				"   AND ofiuniadm='".$ls_ofiuniadm."'".
				"   AND uniuniadm='".$ls_uniuniadm."' ".
				"   AND depuniadm='".$ls_depuniadm."' ".
				"   AND prouniadm='".$ls_prouniadm."' ".
				"   AND codcestic='".$as_codcestic."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Unidad MÉTODO->uf_select_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidad($as_codigo,$as_codcestic,$as_est1cestic,$as_est2cestic,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidad
		//		   Access: private
		//	    Arguments: as_codigo  // código de la Unidad Administrativa
		//				   as_codcestic  // Código del método de cesta Ticket
		//				   as_est1cestic  // estatus 1 de cesta Ticket
		//				   as_est2cestic  // estatus 2 de cesta Ticket
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_cestaticunidadadm
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_minorguniadm=substr($as_codigo,0,4);
		$ls_ofiuniadm=substr($as_codigo,4,2);
		$ls_uniuniadm=substr($as_codigo,6,2);
		$ls_depuniadm=substr($as_codigo,8,2);
		$ls_prouniadm=substr($as_codigo,10,2);
		$ls_sql="INSERT INTO sno_cestaticunidadadm(codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,codcestic,est1cestic,est2cestic)".
			    " VALUES ('".$this->ls_codemp."','".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."',". 
			    "'".$ls_prouniadm."','".$as_codcestic."','".$as_est1cestic."','".$as_est2cestic."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad MÉTODO->uf_insert_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Unidad ".$as_codigo." por cesta ticket ".$as_codcestic;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_unidad($as_codigo,$as_codcestic,$as_est1cestic,$as_est2cestic,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_unidad
		//		   Access: private
		//	    Arguments: as_codigo  // código de la Unidad Administrativa
		//				   as_codcestic  // Código del método de cesta Ticket
		//				   as_est1cestic  // estatus 1 de cesta Ticket
		//				   as_est2cestic  // estatus 2 de cesta Ticket
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_cestaticunidadadm
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_minorguniadm=substr($as_codigo,0,4);
		$ls_ofiuniadm=substr($as_codigo,4,2);
		$ls_uniuniadm=substr($as_codigo,6,2);
		$ls_depuniadm=substr($as_codigo,8,2);
		$ls_prouniadm=substr($as_codigo,10,2);
		$ls_sql="UPDATE sno_cestaticunidadadm  ".
			    "   SET est1cestic='".$as_est1cestic."', ".
				"       est2cestic='".$as_est2cestic."' ".
			    " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcestic='".$as_codcestic."' ".
				"   AND minorguniadm='".$ls_minorguniadm."' ".
			    "   AND ofiuniadm='".$ls_ofiuniadm."' ".
				"   AND uniuniadm='".$ls_uniuniadm."' ".
				"   AND depuniadm='".$ls_depuniadm."' ".
				"   AND prouniadm='".$ls_prouniadm."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad MÉTODO->uf_update_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Unidad ".$as_codigo." por cesta ticket ".$as_codcestic;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_codigo,$as_codcestic,$as_est1cestic,$as_est2cestic,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_ct_unid)
		//	    Arguments: as_codigo  // código de la Unidad Administrativa
		//				   as_codcestic  // Código del método de cesta Ticket
		//				   as_est1cestic  // estatus 1 de cesta Ticket
		//				   as_est2cestic  // estatus 2 de cesta Ticket
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_cestaticunidadadm
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_codigo=str_replace("-","",$as_codigo);		
		if($this->uf_select_unidad($as_codigo,$as_codcestic)===false)
		{
			$lb_valido=$this->uf_insert_unidad($as_codigo,$as_codcestic,$as_est1cestic,$as_est2cestic,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_update_unidad($as_codigo,$as_codcestic,$as_est1cestic,$as_est2cestic,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_unidad($as_codigo,$as_codcestic,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_unidad
		//		   Access: public (sigesp_snorh_d_profesion)
		//	    Arguments: as_codigo  // código de la Unidad Administrativa
		//				   as_codcestic  // Código del método de cesta Ticket
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_cestaticunidadadm
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_codigo=str_replace("-","",$as_codigo);		
		$ls_minorguniadm=substr($as_codigo,0,4);
		$ls_ofiuniadm=substr($as_codigo,4,2);
		$ls_uniuniadm=substr($as_codigo,6,2);
		$ls_depuniadm=substr($as_codigo,8,2);
		$ls_prouniadm=substr($as_codigo,10,2);
		$ls_sql="DELETE ".
				"  FROM sno_cestaticunidadadm ".
			    " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcestic='".$as_codcestic."' ".
				"   AND minorguniadm='".$ls_minorguniadm."' ".
			    "   AND ofiuniadm='".$ls_ofiuniadm."' ".
				"   AND uniuniadm='".$ls_uniuniadm."' ".
				"   AND depuniadm='".$ls_depuniadm."' ".
				"   AND prouniadm='".$ls_prouniadm."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Unidad MÉTODO->uf_delete_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Eliminó la Unidad ".$as_codigo." por cesta ticket ".$as_codcestic;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
    }// end function uf_delete_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>