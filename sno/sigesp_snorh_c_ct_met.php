<?php
class sigesp_snorh_c_ct_met
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_ct_met()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_profesion
		//		   Access: public (sigesp_snorh_d_ct_met)
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
	
	}// end function sigesp_snorh_c_ct_met
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ct_met)
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
		unset($this->io_personal);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ct($as_codigo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ct
		//		   Access: private
 		//	    Arguments: as_codigo  // código del cesta ticket 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el cesta ticket está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcestic ".
			    "  FROM sno_cestaticket ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcestic='".$as_codigo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_select_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_ct
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_insert_ct($as_codigo,$as_denominacion,$ad_valor,$as_metcestic,$as_codcli,$as_codprod,$as_punent,$ad_valordesc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ct
		//		   Access: private
 		//	    Arguments: as_codigo  // código del cesta ticket 
 		//	    		   as_denominacion  // denominación del cesta ticket 
 		//	    		   ad_valor  // valor del cesta ticket 
 		//	    		   as_metcestic  // método del cesta ticket 
 		//	    		   as_codcli  //  Código de Cliente
 		//	    		   as_codprod  // Código del Producto
 		//	    		   as_punent  // método del cesta ticket
		//                 ad_valordesc // valor diario del descuento
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que inserta en la tabla sno_cestaticket
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_cestaticket(codemp, codcestic, dencestic, moncestic, metcestic, codcli, codprod, punent, mondesdia)". 
			    "     VALUES ('".$this->ls_codemp."','".$as_codigo."','".$as_denominacion."',".$ad_valor.",".$as_metcestic.",".
				"			  '".$as_codcli."','".$as_codprod."','".$as_punent."',".$ad_valordesc.")";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_insert_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Método de Cesta ticket ".$as_codigo;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Método de Cesta Ticket fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_insert_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_ct
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_update_ct($as_codigo,$as_denominacion,$ad_valor,$as_metcestic,$as_codcli,$as_codprod,$as_punent,$ad_valordesc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ct
		//		   Access: private
 		//	    Arguments: as_codigo  // código del cesta ticket 
 		//	    		   as_denominacion  // denominación del cesta ticket 
 		//	    		   ad_valor  // valor del cesta ticket 
 		//	    		   as_metcestic  // método del cesta ticket 
 		//	    		   as_codcli  //  Código de Cliente
 		//	    		   as_codprod  // Código del Producto
 		//	    		   as_punent  // método del cesta ticket 
		//                 ad_valordesc // valor diario del descuento
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que inserta en la tabla sno_cestaticket
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_cestaticket ".
			    "   SET dencestic='".$as_denominacion."',".
				"       moncestic=".$ad_valor.",".
				"       metcestic=".$as_metcestic.", ".
				"		codcli='".$as_codcli."', ".
				"       codprod='".$as_codprod."', ".
				"		punent='".$as_punent."', ".
				"       mondesdia= ".$ad_valordesc." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcestic='".$as_codigo."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_update_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Método de Cesta ticket ".$as_codigo;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Método de Cesta Ticket fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_update_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_ct
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codigo,$as_denominacion,$ad_valor,$as_metcestic,$as_codcli,$as_codprod,$as_punent,$ad_valordesc,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_ct_met)
 		//	    Arguments: as_codigo  // código del cesta ticket 
 		//	    		   as_denominacion  // denominación del cesta ticket 
 		//	    		   ad_valor  // valor del cesta ticket 
 		//	    		   as_metcestic  // método del cesta ticket 
 		//	    		   as_codcli  //  Código de Cliente
 		//	    		   as_codprod  // Código del Producto
 		//	    		   as_punent  // método del cesta ticket 
		//                 ad_valordesc //valor diario del descuento
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_cestaticket
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		if ($ad_valordesc=="")
		{
			$ad_valordesc=0;
		}
		$ad_valor=str_replace(".","",$ad_valor);
		$ad_valor=str_replace(",",".",$ad_valor);	
		$ad_valordesc=str_replace(".","",$ad_valordesc);
		$ad_valordesc=str_replace(",",".",$ad_valordesc);				
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_ct($as_codigo)===false)
				{
					$lb_valido=$this->uf_insert_ct($as_codigo,$as_denominacion,$ad_valor,$as_metcestic,$as_codcli,$as_codprod,
					                               $as_punent,$ad_valordesc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Método de Cesta Ticket ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_ct($as_codigo)))
				{
					$lb_valido=$this->uf_update_ct($as_codigo,$as_denominacion,$ad_valor,$as_metcestic,$as_codcli,$as_codprod,
					                               $as_punent,$ad_valordesc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Método de Cesta Ticket no existe, no la puede actualizar.");
				}
				break;
		}
		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cestaunidad($as_codigo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cestaunidad
		//		   Access: private
 		//	    Arguments: as_codigo  // código de la cesta ticket
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el cesta ticket está regitrado en una unidad
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcestic FROM sno_cestaticunidadadm ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcestic='".$as_codigo."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_select_cestaunidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_cestaunidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_ct($as_codigo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_ct
		//		   Access: public (sigesp_snorh_d_ct_met)
 		//	    Arguments: as_codigo  // código del cesta ticket 
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que elimina en la tabla sno_cestaticket
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->uf_select_cestaunidad($as_codigo)===false)   
		{
			$ls_sql="DELETE ".
					"  FROM sno_cestaticket ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codcestic='".$as_codigo."' ";
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_delete_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Método de Cesta Ticket ".$as_codigo;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Método de Cesta Ticket fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_delete_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar El Método de Cesta Ticket, hay unidades relacionado a este.");
		}       
		return $lb_valido;
	}// end function uf_delete_ct
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ct(&$as_existe,&$as_codigo,&$as_denominacion,&$ai_valor,&$as_cmbmet,&$as_codcli,&$as_codprod,&$as_punent,&$ad_valordesc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ct
		//		   Access: public (sigesp_snorh_d_ct_met)
		//	    Arguments: as_existe  // si existe la dedicación
		//			       as_codigo  // código del cesta Ticket
		//				   as_denominacion  // denominación del cesta Ticket
		//				   ai_valor  // Valor del cesta Ticket
		//				   as_cmbmet  // Método del cesta Ticket
 		//	    		   as_codcli  //  Código de Cliente
 		//	    		   as_codprod  // Código del Producto
 		//	    		   as_punent  // método del cesta ticket 
		//                 ad_valordesc // monto del descuento diario		
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que obtiene los datos de la dedicación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codcestic, dencestic, moncestic, metcestic, codcli, codprod, punent, mondesdia ".
				"  FROM sno_cestaticket ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcestic='".$as_codigo."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_load_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";			
				$as_codigo=$row["codcestic"];
				$as_denominacion=$row["dencestic"];
				$ai_valor=number_format($row["moncestic"],2,",",".");
				$as_cmbmet=$row["metcestic"];
				$as_codcli=$row["codcli"];
				$as_codprod=$row["codprod"];
				$as_punent=$row["punent"];
				$ad_valordesc=number_format($row["mondesdia"],2,",",".");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_ct
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valor_ct($as_codnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_valor_ct
		//		   Access: private
 		//	    Arguments: as_codnom  // código de la Nómina al que esta asociado el cesta ticket
		//	      Returns: li_monto Valor del cesta ticket
		//	  Description: Funcion que verifica si el cesta ticket está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_monto=0;
		$ls_sql="SELECT moncestic ".
			    "  FROM sno_cestaticket, sno_nomina ".
				" WHERE sno_cestaticket.codcestic = sno_nomina.ctmetnom ".
				"   AND sno_cestaticket.codemp='".$this->ls_codemp."' ".
				"   AND sno_nomina.espnom='1' ".
				"   AND sno_nomina.ctnom='1' ".
				"   AND sno_nomina.codnom='".$as_codnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Cesta Ticket MÉTODO->uf_select_valor_ct ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_monto=$row["moncestic"];;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $li_monto;
	}// end function uf_select_valor_ct
	//-----------------------------------------------------------------------------------------------------------------------------------

	
}
?>