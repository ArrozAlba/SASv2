<?php
class sigesp_sno_c_constantes
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_constantes()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_constantes
		//		   Access: public (sigesp_sno_d_constantes)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
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
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("sigesp_sno.php");
		$this->io_sno= new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
		
	}// end function sigesp_sno_c_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_constantes)
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
		unset($this->io_fun_nomina);
		unset($this->io_sno);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valor($as_codcons)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_valor
		//	    Arguments: as_codigo    codigo de la nomina 
		//	      Returns: lb_valido -> variable boolean
		//	  Description: selecciona los datos de la nomina segun el codigo pasado por  parametros
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_total=0;
		$ls_sql="SELECT count(distinct moncon) as total ".
				"  FROM sno_constantepersonal ".
				" WHERE codcons='".$as_codcons."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_select_valor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_total=$row["total"];
			}
		}	
		return $ls_total;
	}// end function uf_select_valor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_check_seguridad($as_codcons)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_seguridad
		//	    Arguments: as_codcons // Código de la Constante
		//	      Returns: lb_valido -> variable boolean
		//	  Description: verifica si la persona tiene acceso a dicha constante 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/11/2007					
		// Modificado Por: 													Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT codusu ".
				"  FROM sss_permisos_internos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codusu='".$this->ls_logusr."' ".
				"   AND codsis='SNO' ".
				"   AND SUBSTR(trim(codintper),1,4)='".$this->ls_codnom."' ".
				"   AND SUBSTR(trim(codintper),6,10)='".$as_codcons."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_check_seguridad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				 $lb_valido=true;
			}
		}	
		return $lb_valido;
	}// end function uf_select_valor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_constantes($as_codcons)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_constantes
		//	    Arguments: as_codcons // código de la constante
		//	      Returns: lb_existe -> variable boolean
		//	  Description: selecciona los datos de la constante
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcons ".
				"  FROM sno_constante ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcons='".$as_codcons."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_existe=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_select_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
		}	
		return $lb_existe;
	}// end function uf_select_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constantes($as_codcons,$as_nomcon,$as_unicon,$as_topcon,$as_valcon,$as_reicon,$as_conespseg,$as_esttopmod,$as_perenc,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantes
		//	    Arguments: as_codcons // codigo de la constante
		//	    		   as_nomcon // nombre de la constante
		//	    		   as_unicon // unidad de la constante
		//	    		   as_topcon // tope de la constante
		//	    		   as_valcon // valor de la constante
		//	    		   as_reicon // si la constante se reinicializa
		//				   as_conespseg   // constante especial en nómina
		//                 as_esttopmod     // tope modificable por persona
		//                 as_perenc // indica si la constante pertenece a encargaduría
		//	    		   aa_seguridad // arreglo de la variable de seguridad
		//	      Returns: lb_valido -> variable boolean
		//	  Description: Guarda los datos de la constante
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 01/10/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_equcon="1";
		$ls_tipnumcon="1";			
		$ls_sql="INSERT INTO sno_constante(codemp,codnom,codcons, nomcon,unicon,equcon,topcon,valcon,reicon ,tipnumcon,conespseg,                 esttopmod,conperenc) ".
		        " VALUES( '".$this->ls_codemp."','".$this->ls_codnom."','".$as_codcons."', '".$as_nomcon."','".$as_unicon."', ".
				" '".$ls_equcon."',	'".$as_topcon."',".$as_valcon.",'".$as_reicon."','".$ls_tipnumcon."','".$as_conespseg."', ".
				" ".$as_esttopmod.", '".$as_perenc."') ";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_insert_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la constante ".$as_codcons." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_const_personal($as_codcons,$as_valcon,$aa_seguridad);
			}
			if($lb_valido)
			{
				$this->io_mensajes->message("La Constante fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_insert_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_insert_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_const_personal($as_codcons,$as_moncon,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_const_personal
		//	    Arguments: as_codcons //  codigo de la constante
		//	    		   as_moncon  // monto de la constante
		//	    		   aa_seguridad // arreglo de la variable de seguridad
		//	      Returns: lb_valido -> variable boolean
		//	  Description: inserto la constanste a cada personal
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_constantepersonal (codemp,codnom,codper,codcons,moncon) ".
				"SELECT codemp,codnom,codper,'".$as_codcons."',".$as_moncon." ".
				"  FROM sno_personalnomina ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_insert_const_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la constantepersonal constante ".$as_codcons."  asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_const_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_constantes($as_codcons,$as_nomcon,$as_unicon,$as_topcon,$as_valcon,$as_reicon,$as_conespseg,$as_esttopmod,$as_perenc,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_constantes
		//	    Arguments: as_codcons // codigo de la constante
		//	    		   as_nomcon // nombre de la constante
		//	    		   as_unicon // unidad de la constante
		//	    		   as_topcon // tope de la constante
		//	    		   as_valcon // valor de la constante
		//	    		   as_reicon // si la constante se reinicializa
		//				   as_conespseg   // constante especial en nómina
		//                 as_esttopmod // tope modificable por persona
		//                 as_perenc // indica si la constante pertenece a encargaduría
		//	    		   aa_seguridad // arreglo de la variable de seguridad
		//   	  Returns: lb_valido -> variable boolean
		//	  Description: Actualiza los datos de la constante
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_equcon="1";
		$ls_tipnumcon="1";
		$ls_sql="UPDATE sno_constante ".
				"   SET nomcon = '".$as_nomcon."', ".
				"       unicon = '".$as_unicon."', ".
				"       equcon = '".$ls_equcon."', ".
			    "		topcon = ".$as_topcon.", ".
				"       valcon = ".$as_valcon.", ".
				"       reicon = '".$as_reicon."', ".
				"       tipnumcon = '".$ls_tipnumcon."', ".
				"       esttopmod = '".$as_esttopmod."', ".
				"       conespseg = '".$as_conespseg."', ".
				"       conperenc = '".$as_perenc."' ".
			    " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcons='".$as_codcons."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			print $this->io_sql->message;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_update_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la constante ".$as_codcons." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_mensajes->message("La Constante fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_update_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_update_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcons,$as_nomcon,$as_unicon,$ad_topcon,$ad_valcon,$as_reicon,$as_conespseg,
	                    $as_esttopmod,$as_perenc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_constante)
		//	    Arguments: as_codcons  // código de la constante						as_nomcon  // Nombre
		//				   as_unicon  // Unidad											ad_topcon  // Tope
		//				   ad_valcon  // valor 										    as_reicon  // si se reinicializa
		//				   as_conespseg   // constante especial en nómina
		//                 as_esttopmod // tope modificacble por personal
		//                 as_perenc // indica si la constante pertenece a encargaduria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda la constante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;				
		$ad_topcon=str_replace(".","",$ad_topcon);
		$ad_topcon=str_replace(",",".",$ad_topcon);				
		$ad_valcon=str_replace(".","",$ad_valcon);
		$ad_valcon=str_replace(",",".",$ad_valcon);				
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_constantes($as_codcons)===false)
				{
					$lb_valido=$this->uf_insert_constantes($as_codcons,$as_nomcon,$as_unicon,$ad_topcon,$ad_valcon,$as_reicon,$as_conespseg,$as_esttopmod,$as_perenc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Constante ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_constantes($as_codcons)))
				{
					$lb_valido=$this->uf_update_constantes($as_codcons,$as_nomcon,$as_unicon,$ad_topcon,$ad_valcon,$as_reicon,$as_conespseg,$as_esttopmod,$as_perenc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Constante no existe, no la puede actualizar.");
				}
				break;
		}
		
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_constantepersonal($as_codcons)
	{
		////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constantepersonal
		//	    Arguments: as_codcons   codigo del constante
		// 	      Returns: lb_valido -> variable boolean
		//	  Description: Elimina las constantes del personal en la tabla sno_constantepersonal  
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_constantepersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcons='".$as_codcons."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_delete_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_delete_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_constante($as_codcons,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constante
		//		   Access: public (sigesp_sno_d_constante)
		//	    Arguments: as_codcons  // código de constante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$lb_valido=$this->uf_delete_constantepersonal($as_codcons,$aa_seguridad);
		if($lb_valido)
		{			
			$ls_sql="DELETE ".
					"  FROM sno_constante ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codcons='".$as_codcons."' ";
					
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_delete_constante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la constante  ".$as_codcons." asociada a la nómina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Constante fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_delete_constante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
    }// end function uf_delete_constante	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_constantepersonal($as_codcons,$ai_inicio,$ai_registros,&$ai_totrows,&$ao_object,&$ai_totpag,$as_esttopmod)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_constantepersonal
		//		   Access: public (sigesp_sno_d_constpersonal)
		//	    Arguments: as_codcons // código de constante
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//                 as_esttopmod  // tope modificable por persona
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todo el personalconstante asociado a un concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_orden="";
		$ls_pag="";
		$ls_pag1="";
		$ls_ordcons=$this->io_sno->uf_select_config("SNO","CONFIG","ORDEN CONSTANTE","CODIGO","C");
		switch($ls_ordcons)
		{
			case "CODIGO":
				$ls_orden=" ORDER BY sno_personal.codper ASC ";
				break;

			case "NOMBRE":
				$ls_orden=" ORDER BY sno_personal.nomper ASC ";
				break;

			case "APELLIDO":
				$ls_orden=" ORDER BY sno_personal.apeper ASC ";
				break;

			case "UNIDAD":
				$ls_orden=" ORDER BY sno_personalnomina.minorguniadm,sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm,sno_personalnomina.prouniadm ASC ";
				break;
		}
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;
			case "INFORMIX":
				$ls_pag1= " SKIP  ".$ai_inicio." FIRST ".$ai_registros;
			
			break;
		}
		$ls_sql="SELECT ".$ls_pag1." sno_constantepersonal.codper, sno_constantepersonal.codcons, sno_constantepersonal.moncon, ".
				"       sno_personal.nomper, sno_personal.apeper, sno_personalnomina.minorguniadm, ".
			    "	    sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm,".
			    "	    sno_personalnomina.prouniadm, sno_constantepersonal.montopcon,".
				"		(SELECT COUNT(codcons) ".
				"		   FROM sno_constantepersonal ".
				"		   WHERE  codemp='".$this->ls_codemp."' AND codnom='".$this->ls_codnom."' AND codcons='".$as_codcons."') AS total ".
			    "  FROM sno_constantepersonal, sno_personalnomina, sno_personal ".
			    " WHERE sno_constantepersonal.codemp ='".$this->ls_codemp."' ".
			    "   AND sno_constantepersonal.codnom ='".$this->ls_codnom."' ".
			    "   AND sno_constantepersonal.codcons ='".$as_codcons."' ".
			    "   AND sno_constantepersonal.codnom=sno_personalnomina.codnom ".
			    "   AND sno_constantepersonal.codemp=sno_personalnomina.codemp ".
			    "   AND sno_constantepersonal.codper=sno_personalnomina.codper ".
			    "   AND sno_personalnomina.codper=sno_personal.codper ".
			    "   AND sno_personalnomina.codemp=sno_personal.codemp ".
				$ls_orden.
				$ls_pag;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_load_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$li_total=$row["total"];
				$ls_codper=$row["codper"];  
				$ls_minorguniadm=$row["minorguniadm"];
				$ls_ofiuniadm=$row["ofiuniadm"];
				$ls_uniuniadm=$row["uniuniadm"];
				$ls_depuniadm=$row["depuniadm"];
				$ls_prouniadm=$row["prouniadm"];
				$ls_uniadm=$ls_minorguniadm.$ls_ofiuniadm.$ls_uniuniadm.$ls_depuniadm.$ls_prouniadm;
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$ls_nombre=$ls_apeper.", ".$ls_nomper;
				$ls_moncon=number_format($row["moncon"],2,",",".");
				$ls_topcon=number_format($row["montopcon"],2,",",".");
				if ((trim($as_esttopmod)==1))
				{
		
					$ao_object[$ai_totrows][1]="<input type=text name=txtcodper".$ai_totrows." value=".$ls_codper." class=sin-borde  size=20 style=text-align:center readonly><input name=codper".$ai_totrows." type=hidden id=codper value='$ls_codper'>";
					$ao_object[$ai_totrows][2]="<input type=text name=txtuniadm".$ai_totrows." value=".$ls_uniadm." size=30 class=sin-borde style=text-align:center readonly >";
					$ao_object[$ai_totrows][3]="<input type=text name=txtnombre".$ai_totrows." class=sin-borde value='".$ls_nombre."' size=50  style=text-align:left readonly>";
					$ao_object[$ai_totrows][4]="<input type=text name=txttopcon".$ai_totrows." onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=".$ls_topcon." class=sin-borde size=15 style=text-align:right >";
					$ao_object[$ai_totrows][5]="<input type=text name=txtmoncon".$ai_totrows." onKeyPress=return(ue_formatonumero(this,'.',',',event)) onBlur=javascript:uf_mayor_tope(this,".$ai_totrows.") value=".$ls_moncon." class=sin-borde size=15 style=text-align:right >";
					
			  }
			  else
			   {
			   		$ao_object[$ai_totrows][1]="<input type=text name=txtcodper".$ai_totrows." value=".$ls_codper." class=sin-borde  size=20 style=text-align:center readonly><input name=codper".$ai_totrows." type=hidden id=codper value='$ls_codper'>";
					$ao_object[$ai_totrows][2]="<input type=text name=txtuniadm".$ai_totrows." value=".$ls_uniadm." size=30 class=sin-borde style=text-align:center readonly >";
					$ao_object[$ai_totrows][3]="<input type=text name=txtnombre".$ai_totrows." class=sin-borde value='".$ls_nombre."' size=50  style=text-align:left readonly>";
					$ao_object[$ai_totrows][4]="<input type=text name=txtmoncon".$ai_totrows." onKeyPress=return(ue_formatonumero(this,'.',',',event)) onBlur=javascript:uf_mayor(this) value=".$ls_moncon." class=sin-borde size=20 style=text-align:right >";
			   
			   }
				
			}
			$this->io_sql->free_result($rs_data);
			$ai_totpag = ceil($li_total / $ai_registros); 
		}
		return $lb_valido;
	}// end function uf_load_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_constantepersonal($as_codper,$as_codcons,&$ai_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_constantepersonal
		//		   Access: public
		//	    Arguments: as_codper // código de personal
		//				   as_codcons // código de la constante
		//				   ai_valor // valor de la constante
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y concepto busca el concepto asociado al personal
		//				   esta función se llama desde la clase evaluadora		
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_constantepersonal.moncon ".
				"  FROM sno_constantepersonal, sno_constante ".
				" WHERE sno_constantepersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_constantepersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_constantepersonal.codcons='".$as_codcons."' ".
				"   AND sno_constantepersonal.codper='".$as_codper."' ".
				"   AND sno_constantepersonal.codemp=sno_constante.codemp ".
				"   AND sno_constantepersonal.codnom=sno_constante.codnom ".
				"   AND sno_constantepersonal.codcons=sno_constante.codcons ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		}
		else
		{
			$li_total=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_valor=$row["moncon"];
				$li_total=$li_total+1;
			}
			if($li_total==0)
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function update_const_personal($as_codcons,$as_moncon,$as_topcon,$as_codper)
	{
		////////////////////////////////////////////////////////////////
		//	     Function: update_const_personal
		//	    Arguments: as_codcons //  codigo de la constante
		//	    		   as_moncon  // monto de la constante
		//                 as_topcon  // tope de la constante
		//	    		   as_codper  // código de personal
		//	      Returns: lb_valido -> variable boolean
		//	  Description: selecciono el personal asigando a la nomina  
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_constantepersonal ".
				"   SET moncon=".$as_moncon.", ".
				"       montopcon=".$as_topcon." ".
	            " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND codcons='".$as_codcons."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->update_const_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		
		return $lb_valido;
	}// end function update_const_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_constante(&$as_existe,&$as_codcons,&$as_nomcon,&$as_unicon,&$ad_topcon,&$ad_valcon,&$as_reicon,&$as_conespseg,
	                           &$as_esttopmod,&$as_perenc)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_constante
		//	    Arguments: as_codcons // codigo de la constante
		//	    		   as_nomcon // nombre de la constante
		//	    		   as_unicon // unidad de la constante
		//	    		   as_topcon // tope de la constante
		//	    		   as_valcon // valor de la constante
		//	    		   as_reicon // si la constante se reinicializa
		//				   as_conespseg // Constante especial de seguridad
		//                 as_esttopmod // Tope modificable por personal
		//                 as_perenc // indica si la constante pertenece a encargaduría
		//	      Returns: lb_valido -> variable boolean
		//	  Description: selecciona los datos de la constante
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 01/10/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		 $ls_sql="SELECT codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon, conespseg,esttopmod,conperenc ".
		 		 "  FROM sno_constante ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codnom='".$this->ls_codnom."' ".
				 "   AND codcons='".$as_codcons."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_load_constante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";
				$as_codcons=$row["codcons"];
				$as_nomcon=$row["nomcon"];
				$as_unicon=$row["unicon"];
				$ad_topcon=number_format($row["topcon"],2,",",".");
				$ad_valcon=number_format($row["valcon"],2,",",".");
				$as_reicon=$row["reicon"];
				$as_conespseg=$row["conespseg"];
				$as_esttopmod=$row["esttopmod"];
				$as_perenc=$row["conperenc"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_constante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_aplicar_valor($as_codcons,$ai_moncon,$ai_topcon,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////
		//	     Function: uf_aplicar_valor
		//	    Arguments: as_codcons //  codigo de la constante
		//	    		   ai_moncon  // monto de la constante
		//                 ai_topcon  // tope de la constante
		//	    		   aa_seguridad // arreglo de la variable de seguridad
		//	      Returns: lb_valido -> variable boolean
		//	  Description: inserto la constanste a cada personal
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 30/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_moncon=str_replace(".","",$ai_moncon);
		$ai_moncon=str_replace(",",".",$ai_moncon);	
		$ai_topcon=str_replace(".","",$ai_topcon);
		$ai_topcon=str_replace(",",".",$ai_topcon);				
		$ls_sql="UPDATE sno_constantepersonal ".
				"   SET moncon=".$ai_moncon.", ".
				"       montopcon=".$ai_topcon." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcons='".$as_codcons."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_aplicar_valor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="actualizó la constantepersonal constante ".$as_codcons." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("La Constante fue aplicada.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_aplicar_valor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_aplicar_valor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_constantes_x_personal($as_codper,&$ai_totrows,&$aa_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_constantes_x_personal
		//		   Access: public (sigesp_sno_d_persxconst.php)
		//	    Arguments: as_codper  // Código de personañ
		//				   ai_totrows  // Total de Filas
		//				   aa_object  //  Arreglo de objectos que se van a imprimir
		//	      Returns: $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene el sueldo de un personal dado un ó sueldo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_constante.codcons, sno_constante.nomcon, sno_constantepersonal.moncon, ".
				"  sno_constante.conespseg, sno_constante.esttopmod,sno_constantepersonal.montopcon ".
				"  FROM sno_constante, sno_constantepersonal ".
				" WHERE sno_constante.codemp='".$this->ls_codemp."' ".
				"   AND sno_constante.codnom='".$this->ls_codnom."' ".
				"   AND sno_constantepersonal.codper='".$as_codper."' ".
				"   AND sno_constante.codemp = sno_constantepersonal.codemp ".
				"   AND sno_constante.codnom = sno_constantepersonal.codnom ".
				"   AND sno_constante.codcons = sno_constantepersonal.codcons ".
				" ORDER BY sno_constante.codcons  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_load_constantes_x_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$lb_especial=true;
				$ls_codcons=$row["codcons"];  
				$ls_nomcon=$row["nomcon"];
				$li_topcon=$this->io_fun_nomina->uf_formatonumerico($row["montopcon"]);
				$li_moncon=$this->io_fun_nomina->uf_formatonumerico($row["moncon"]);
				$ls_conespseg=$row["conespseg"];
				$ls_esttopmod=$row["esttopmod"];
				if($ls_conespseg=="1")
				{
					$lb_especial=$this->uf_check_seguridad($ls_codcons);
				}
				$aa_object[$ai_totrows][1]="<input type=text name=txtcod".$ai_totrows." value='".$ls_codcons."' class=sin-borde  size=20 readonly>";
				$aa_object[$ai_totrows][2]="<input type=text name=txtnom".$ai_totrows." value='".$ls_nomcon."' size=40 class=sin-borde readonly >";
				if ($lb_especial)
				{
					
					if ($ls_esttopmod=='1')
					{
						$aa_object[$ai_totrows][3]="<input type=text name=txttopcon".$ai_totrows." value='".$li_topcon."' class=sin-borde size=15 onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
					}
					else
					{
						$aa_object[$ai_totrows][3]="<input type=text name=txttopcon".$ai_totrows." value='".$li_topcon."' class=sin-borde size=15 readonly style=text-align:right>";
					}
					
					$aa_object[$ai_totrows][4]="<input type=text name=txtmon".$ai_totrows." value='".$li_moncon."' class=sin-borde size=15 onKeyPress=return(ue_formatonumero(this,'.',',',event)) onBlur=javascript:uf_mayor(".$ai_totrows.") style=text-align:right>";
				}
				else
				{
					$aa_object[$ai_totrows][3]="<input type=text name=txttopcon".$ai_totrows." value='".$li_topcon."' class=sin-borde size=15 readonly style=text-align:right>";
					$aa_object[$ai_totrows][4]="<input type=text name=txtmon".$ai_totrows." value='".$li_moncon."' class=sin-borde size=15 readonly style=text-align:right>";
				} 	
			   
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_constantes_x_personal	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_constantes_x_personal($as_codcons,$as_codper,$ai_moncon,$ai_topcon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_constantes_x_personal
		//		   Access: public (sigesp_sno_d_persxconst.php)
		//	    Arguments: as_codcons  // código de constante
		//				   as_codper  // código de personal
		//				   ai_moncon  // monto de la constante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//    Description: Funcion que actualiza en la tabla de constante por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_moncon=str_replace(".","",$ai_moncon);
		$ai_moncon=str_replace(",",".",$ai_moncon);
		$ai_topcon=str_replace(".","",$ai_topcon);
		$ai_topcon=str_replace(",",".",$ai_topcon);				
		$ls_sql="UPDATE sno_constantepersonal ".
				"   SET moncon = ".$ai_moncon.", ".
				"       montopcon = ".$ai_topcon." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"   AND codcons='".$as_codcons."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_update_constantes_x_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la constante ".$as_codcons." del personal ".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		
		}		
		return $lb_valido;
	}// end function uf_update_constantes_x_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_load_constantesencargaduria(&$ai_totrows,&$ao_object)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_constantesencargaduria
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que selecciona en lote los conceptos que pertenecen a las encargadurias
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
       	$ls_sql="SELECT codcons, nomcon, conperenc ".
				"  FROM sno_constante ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				" ORDER BY codcons";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Constante Nómina MÉTODO->uf_load_constantesencargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
       	}
       	else
       	{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codcon=$row["codcons"];
				$ls_nomcon=$row["nomcon"];			
				$li_aplcon=$row["conperenc"];
				if($li_aplcon=="1")
				{
					$ls_aplica="checked";
				}
				else
				{
					$ls_aplica="";
				}
				
				$ao_object[$ai_totrows][1]="<input name=txtcodcon".$ai_totrows." type=text id=txcodcon".$ai_totrows." value=".$ls_codcon." size=13 class=sin-borde readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnomcon".$ai_totrows." type=text id=txtxtnomcon".$ai_totrows." value='".$ls_nomcon."' size=67 class=sin-borde readonly>";				
				$ao_object[$ai_totrows][3]="<input name=chkaplcon".$ai_totrows." type=checkbox id=chkaplcon".$ai_totrows." value='1' ".$ls_aplica.">";
				
			}
			$this->io_sql->free_result($rs_data);
       	}
		return $lb_valido;    
	}// end function uf_load_constantesencargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_constantesencargaduria($as_codconc,$as_perenc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_constantesencargaduria
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto							
		//				   as_titcon  // Título										
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la constante seleccionada por encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_constante ".
				"   SET  conperenc='".$as_perenc."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcons='".$as_codconc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constante MÉTODO->uf_guardar_constantesencargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			
		}	
		return $lb_valido;
	}// end function uf_guardar_constantesencargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>