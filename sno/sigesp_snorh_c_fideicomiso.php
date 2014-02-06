<?php
class sigesp_snorh_c_fideicomiso
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_fideiconfigurable;
	var $io_personal;
	var $io_sno;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_fideicomiso()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_diaferiado
		//		   Access: public (sigesp_snorh_d_fideicomiso)
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
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_snorh_c_fideiconfigurable.php");
		$this->io_fideiconfigurable=new sigesp_snorh_c_fideiconfigurable();
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal=new sigesp_snorh_c_personal();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		$this->DS=new class_datastore();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_snorh_c_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_fideicomiso)
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
		unset($this->io_fideiconfigurable);
		unset($this->io_personal);
		unset($this->io_sno);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fideicomiso($as_codper,&$as_codfid,&$as_ficfid,&$as_ubifid,&$as_cuefid,&$ad_fecingfid,&$as_capfid,&$as_capantcom,&$ad_fecconpreant,&$as_conpreant, &$as_porpintcap)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fideicomiso
		//		   Access: public (sigesp_snorh_d_fideicomiso)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codfid  // código fideicomiso
		//				   as_ficfid  // Ficha
		//				   as_ubifid  // Ubicación
		//				   as_cuefid  // Cuenta
		//				   ad_fecingfid  // Fecha de ingreso
		//				   as_capfid  // Si capitaliza
		//				   as_capantcom // Si capitaliza la antiguedad complementaria
		//				   ad_fecconpreant // Fecha de antiguedad de las prestación antguedad
		//                 as_conpreant // Si toma en cuenta la fecha de antiguedad de las prestación antguedad
		//                 as_porpintcap
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que busca el fideicomiso si está definido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codfid, ficfid, ubifid, cuefid, fecingfid, capfid, capantcom, fecconpreant , conpreant, porintcap ".
				"  FROM sno_fideicomiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_load_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$as_codfid=$rs_data->fields["codfid"];
				$as_ficfid=$rs_data->fields["ficfid"];
				$as_ubifid=$rs_data->fields["ubifid"];
				$as_cuefid=$rs_data->fields["cuefid"];
				$ad_fecingfid=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingfid"]);
				$as_capfid=$rs_data->fields["capfid"];
				$as_capantcom=$rs_data->fields["capantcom"];
				$ad_fecconpreant=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecconpreant"]);
				$as_conpreant=$rs_data->fields["conpreant"];
				$as_porpintcap=$rs_data->fields["porintcap"];
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_load_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideicomiso($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideicomiso
		//		   Access: private
		//   	Arguments: as_codper  // Código del Personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el fideicomiso está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_fideicomiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_select_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fideicomiso($as_codper,$as_codfid,$as_ficfid,$as_ubifid,$as_cuefid,$ad_fecingfid,$as_capfid,$as_capantcom,$ad_fecconpreant,$as_conpreant,$as_porintcap, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fideicomiso
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codfid  // código fideicomiso
		//				   as_ficfid  // Ficha
		//				   as_ubifid  // Ubicación
		//				   as_cuefid  // Cuenta
		//				   ad_fecingfid  // Fecha de ingreso
		//				   as_capfid  // Si capitaliza
		//				   as_capantcom   // Capitaliza la antiguedad complementaria
		//				   ad_fecconpreant // Fecha de antiguedad de las prestación antguedad
		//                 as_conpreant // Si toma en cuenta la fecha de antiguedad de las prestación antguedad
		//                 as_porintcap // procentaje de interes a capitalizar o abonar
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de Fideicomiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_porintcap=str_replace(",",".",$as_porintcap);	
		$ls_sql="INSERT INTO sno_fideicomiso".
				"(codemp,codper,codfid,ficfid,ubifid,cuefid,fecingfid,capfid,capantcom,fecconpreant,conpreant,porintcap)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$as_codfid."','".$as_ficfid."',".
				"'".$as_ubifid."','".$as_cuefid."','".$ad_fecingfid."','".$as_capfid."','".$as_capantcom."', ".
				"  '".$ad_fecconpreant."','".$as_conpreant."',".$as_porintcap.")";
			
				
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_insert_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Fideicomiso ".$as_codfid." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Fideicomiso fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_insert_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fideicomiso($as_codper,$as_codfid,$as_ficfid,$as_ubifid,$as_cuefid,$ad_fecingfid,$as_capfid,$as_capantcom,$ad_fecconpreant,$as_conpreant,$as_porintcap,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fideicomiso
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codfid  // código fideicomiso
		//				   as_ficfid  // Ficha
		//				   as_ubifid  // Ubicación
		//				   as_cuefid  // Cuenta
		//				   ad_fecingfid  // Fecha de ingreso
		//				   as_capfid  // Si capitaliza
		//				   as_capantcom   // Capitaliza la antiguedad complementaria
		//				   ad_fecconpreant // Fecha de antiguedad de las prestación antguedad
		//                 as_conpreant // Si toma en cuenta la fecha de antiguedad de las prestación antguedad
		//				   as_porintcap // porcentaje de interes a capitalizar o abonar
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de Fideicomiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_porintcap=str_replace(",",".",$as_porintcap);		
		$ls_sql="UPDATE sno_fideicomiso ".
				"   SET codfid='".$as_codfid."', ".
				"		ficfid='".$as_ficfid."', ".
				"		ubifid='".$as_ubifid."', ".
				"		cuefid='".$as_cuefid."', ".
				"		fecingfid='".$ad_fecingfid."', ".
				"		capfid='".$as_capfid."', ".
				"		capantcom='".$as_capantcom."', ".				
				"		fecconpreant='".$ad_fecconpreant."', ".
				"		conpreant='".$as_conpreant."', ".
				"       porintcap=".$as_porintcap.
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'"; 
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_update_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Fideicomiso asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Fideicomiso fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_update_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_codper,$as_codfid,$as_ficfid,$as_ubifid,$as_cuefid,$ad_fecingfid,$as_capfid,$as_capantcom,$ad_fecconpreant,$as_conpreant,$as_porintcap,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_fideicomiso)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codfid  // código fideicomiso
		//				   as_ficfid  // Ficha
		//				   as_ubifid  // Ubicación
		//				   as_cuefid  // Cuenta
		//				   ad_fecingfid  // Fecha de ingreso
		//				   as_capfid  // Si capitaliza
		//				   as_capantcom   // Capitaliza la antiguedad complementaria
		//				   ad_fecconpreant // Fecha de antiguedad de las prestación antguedad
		//                 as_conpreant // Si toma en cuenta la fecha de antiguedad de las prestación antguedad
		//                 $as_porintcap // porcentaje de interes para capitalizar
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el grabar ó False si hubo error en el grabar
		//	  Description: Funcion que graba en la tabla de Fideicomiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ad_fecingfid=$this->io_funciones->uf_convertirdatetobd($ad_fecingfid);
		$ad_fecconpreant=$this->io_funciones->uf_convertirdatetobd($ad_fecconpreant);
		
		if ($ad_fecconpreant=="")
		{
			$ad_fecconpreant='1900-01-01';
		}
		
		if($this->uf_select_fideicomiso($as_codper)===false)
		{
			$lb_valido=$this->uf_insert_fideicomiso($as_codper,$as_codfid,$as_ficfid,$as_ubifid,$as_cuefid,$ad_fecingfid,$as_capfid,$as_capantcom,$ad_fecconpreant,$as_conpreant,$as_porintcap, $aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_update_fideicomiso($as_codper,$as_codfid,$as_ficfid,$as_ubifid,$as_cuefid,$ad_fecingfid,$as_capfid,$as_capantcom,$ad_fecconpreant,$as_conpreant,$as_porintcap, $aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideicomiso_periodo($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideicomiso_periodo
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_existe True si existe el fideicomiso ó False si hubo error en el select ó no existe
		//	  Description: Funcion que elimina en la tabla de fideiperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_load_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
    }// end function uf_select_fideicomiso_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fideicomiso($as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fideicomiso
		//		   Access: public (sigesp_snorh_d_fideicomiso)
		//	    Arguments: as_codper  // Código del Personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de Fideicomiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->uf_select_fideicomiso_periodo($as_codper)===false)
		{
			$ls_sql="DELETE ".
					"  FROM sno_fideicomiso ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
					
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Fideicomiso asociado al personal ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Fideicomiso fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_fideicomiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		else
		{
			$this->io_mensajes->message("El fideicomiso no se puede eliminar. Ya se han generado períodos de fideicomiso"); 
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_delete_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_nomina(&$aa_nominas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_nomina
		//		   Access: public (sigesp_snorh_p_fideicomiso.php)
		//	    Arguments: aa_nominas  // arreglo de Nóminas 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene las nóminas creadas en el sistema
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codnom, desnom ".
				"  FROM sno_nomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND espnom = '0'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while(!$rs_data->EOF)
			{
				$aa_nominas["codnom"][$li_i]=$rs_data->fields["codnom"];
				$aa_nominas["desnom"][$li_i]=$rs_data->fields["desnom"];
				$li_i=$li_i+1;
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fideiperiodo($ai_anocurper,$as_mescurper,$aa_nominas,&$ai_totrows,&$ao_object,$as_sueint)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fideiperiodo
		//		   Access: public (sigesp_snorh_d_tablavacacion)
		//	    Arguments: ai_anocurper  // código de la tabla de vacacion
		//				   as_mescurper  // total de filas del detalle
		//				   aa_nominas  // objetos del detalle
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//                 as_sueint  // denominación del sueldo integral
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene el fideicomiso del período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_sueint=="")
		{
			$as_sueint='SUELDO INTEGRAL';
		}
		else
		{
			$as_sueint=strtoupper($as_sueint);
		}
		
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((sno_fideiperiodo.codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (sno_fideiperiodo.codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		$ls_sql="SELECT sno_fideiperiodo.codemp, sno_fideiperiodo.codnom, sno_fideiperiodo.codper, sno_fideiperiodo.anocurper, ".
				"	    sno_fideiperiodo.mescurper, sno_fideiperiodo.bonvacper, sno_fideiperiodo.bonfinper, sno_fideiperiodo.sueintper, ".
				"		sno_fideiperiodo.apoper, sno_fideiperiodo.bonextper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_fideiperiodo, sno_personal ".
				" WHERE sno_fideiperiodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_fideiperiodo.anocurper='".$ai_anocurper."'".
				"   AND sno_fideiperiodo.mescurper=".$as_mescurper." ".
				$ls_codnom.
				"   AND sno_fideiperiodo.codemp=sno_personal.codemp ".
				"   AND sno_fideiperiodo.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_load_fideiperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while(!$rs_data->EOF)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codper=$rs_data->fields["codper"];
				$ls_cedper=$rs_data->fields["cedper"];
				$ls_nomper=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];
				$li_sueintper=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["sueintper"]);
				$li_bonvacper=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["bonvacper"]);
				$li_bonfinper=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["bonfinper"]);
				$li_apoper=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["apoper"]);
				$li_bonexpter=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["bonextper"]);

				$ao_object[$ai_totrows][1]="<div align='center'>".$ls_codper."</div>";
				$ao_object[$ai_totrows][2]="<div align='center'>".$ls_cedper."</div>";
				$ao_object[$ai_totrows][3]="<div align='left'>".$ls_nomper."</div>";
				$ao_object[$ai_totrows][4]="<div align='right'>".$li_sueintper."</div>";
				$ao_object[$ai_totrows][5]="<div align='right'>".$li_bonexpter."</div>";
				$ao_object[$ai_totrows][6]="<div align='right'>".$li_bonvacper."</div>";
				$ao_object[$ai_totrows][7]="<div align='right'>".$li_bonfinper."</div>";
				$ao_object[$ai_totrows][8]="<div align='right'>".$li_apoper."</div>";
				$ao_object[$ai_totrows][9]="<a href=javascript:ue_mostrar_sueldo('".$ls_codper."');    align=center><img src=../shared/imagebank/tools15/buscar.gif width=15 height=15 border=0 align=center title='".$as_sueint."'></a>";
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_fideiperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fideicomiso_periodo($ai_anocurper,$as_mescurper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fideicomiso_periodo
		//		   Access: private
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de fideiperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurper='".$ai_anocurper."'".
				"   AND mescurper=".$as_mescurper."";
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_fideicomiso_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Fideicomiso asociado al Año ".$ai_anocurper." Mes ".$as_mescurper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Fideicomiso fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_fideicomiso_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_fideicomiso_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_fideicomiso_version2($ai_anocurper,$as_mescurper,$aa_nominas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_fideicomiso_version2
		//		   Access: public (sigesp_snorh_p_fideicomiso.php)
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_nominas  // arreglo de Nóminas seleccionadas
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el proceso ó False si hubo error en el proceso
		//	  Description: Función que obtiene el fideicomiso de version 02
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
       	$this->io_sql->begin_transaction();
		$ld_fecgen=""; //Fecha de Generar el fideicomiso
		$lb_valido=$this->uf_load_fecha_gen($ai_anocurper,$as_mescurper,$ld_fecgen);
		if($lb_valido)
		{
			$lb_valido=$this->uf_select_fideiconfigurable($ai_anocurper);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_fideiperiodo($ai_anocurper,$as_mescurper,$aa_nominas);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_personal_version2($ai_anocurper,$as_mescurper,$aa_nominas,$ld_fecgen);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_generar_data_contabilizacion($ai_anocurper,$as_mescurper,$aa_nominas,$ld_fecgen);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Procesó el Fideicomiso asociado al Año ".$ai_anocurper." Mes ".$as_mescurper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Fideicomiso fue procesado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al procesar el fideicomiso."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_procesar_fideicomiso_version2
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_fideicomiso_version_consejo($ai_anocurper,$as_mescurper,$aa_nominas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_fideicomiso_version_consejo
		//		   Access: public (sigesp_snorh_p_fideicomiso.php)
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_nominas  // arreglo de Nóminas seleccionadas
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el proceso ó False si hubo error en el proceso
		//	  Description: Función que obtiene el fideicomiso de version 02
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
       	$this->io_sql->begin_transaction();
		$ld_fecgen=""; //Fecha de Generar el fideicomiso
		$lb_valido=$this->uf_load_fecha_gen($ai_anocurper,$as_mescurper,$ld_fecgen);
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_fideiperiodo($ai_anocurper,$as_mescurper,$aa_nominas);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_personal_version_consejo($ai_anocurper,$as_mescurper,$aa_nominas,$ld_fecgen);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_generar_data_contabilizacion($ai_anocurper,$as_mescurper,$aa_nominas,$ld_fecgen);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Procesó el Fideicomiso asociado al Año ".$ai_anocurper." Mes ".$as_mescurper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Fideicomiso fue procesado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al procesar el fideicomiso."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_procesar_fideicomiso_version_consejo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fecha_gen($ai_anocurper,$as_mescurper,&$ad_fecgen)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fecha_gen
		//		   Access: private
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   ad_fecgen  // fecha de generación
		//	      Returns: lb_valido True si se ejecuto el proceso ó False si hubo error en el proceso
		//	  Description: Función que obtiene la fecha en que se genera el fideicomiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($as_mescurper)
		{
			case "01": // Enero
				$ls_dia="31";
				break;
			case "02": // Febrero
				$ls_dia="28";
				break;
			case "03": // Marzo
				$ls_dia="31";
				break;
			case "04": // Abril
				$ls_dia="30";
				break;
			case "05": // Mayo
				$ls_dia="31";
				break;
			case "06": // Junio
				$ls_dia="30";
				break;
			case "07": // Julio
				$ls_dia="31";
				break;
			case "08": // Agosto
				$ls_dia="31";
				break;
			case "09": // Septiembre
				$ls_dia="30";
				break;
			case "10": // Octubre
				$ls_dia="31";
				break;
			case "11": // Noviembre
				$ls_dia="30";
				break;
			case "12": // Diciembre
				$ls_dia="31";
				break;
		}
		$ad_fecgen=$ai_anocurper."-".$as_mescurper."-".$ls_dia;
		return $lb_valido;
	}// end function uf_load_fecha_gen
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiconfigurable($ai_anocurfid)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiconfigurable
		//		   Access: private
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el fideiconfigurable existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT anocurfid ".
				"  FROM sno_fideiconfigurable ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurfid='".$ai_anocurfid."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_select_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
	        	$this->io_mensajes->message("No hay datos en la configuración de fideicomiso del año ".$ai_anocurfid); 
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiperiodo($as_codnom,$as_codper,$as_anocurper,$ai_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiperiodo
		//		   Access: private
		//	    Arguments: as_codnom  // Código de Nómina
		//	    		   as_codper  // Código de Personal
		//	    		   as_anocurper  // año en curso fideicomiso
		//	    		   ai_mescurper  // mes en curso fideicomiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el fideiperiodo existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND anocurper='".$as_anocurper."' ".
				"   AND mescurper=".$ai_mescurper." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_select_fideiperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_fideiperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fideiperiodo($ai_anocurper,$as_mescurper,$aa_nominas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fideiperiodo
		//		   Access: private
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_nominas  // arreglo de Nóminas seleccionadas
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de fideiperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		$ls_sql="DELETE ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurper='".$ai_anocurper."'".
				"   AND mescurper=".$as_mescurper." ".
				$ls_codnom;
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_fideiperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
    }// end function uf_delete_fideiperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_personal_version2($ai_anocurper,$as_mescurper,$aa_nominas,$ad_fecgen)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_personal_version2
		//		   Access: public (sigesp_snorh_p_fideicomiso.php)
		//	    Arguments: ai_anocurper // año en curso del periodo
		//	    		   as_mescurper // mes en curso del período
		//	    		   aa_nominas // arreglo de Nóminas 
		//	    		   ad_fecgen // fecha a generar el fideicomiso
		//	      Returns: lb_valido True si se ejecuto el proceso de fideicomiso ó False si hubo error en el proceso
		//	  Description: Función que procesa el fideicomiso a todas las personas que están en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((sno_hpersonalnomina.codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (sno_hpersonalnomina.codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		$ls_sql="SELECT sno_hpersonalnomina.codper, sno_hpersonalnomina.codnom, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper, ".
				"		SUM(sno_hpersonalnomina.sueintper) AS sueintper, ".
				"		SUM((SELECT SUM(abs(sno_hsalida.valsal))  ".
				"          FROM sno_hsalida, sno_hconcepto ".
				"         WHERE sno_hconcepto.asifidper ='1' ".
				"			AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='R' OR  sno_hsalida.tipsal='P1')".
				"			AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
				"           AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
				"           AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
				"           AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
				"           AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
				"           AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"           AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"           AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"           AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"           AND sno_hsalida.codconc = sno_hconcepto.codconc )) AS asifidper, ".
				"		SUM((SELECT SUM(abs(sno_hsalida.valsal))  ".
				"          FROM sno_hsalida, sno_hconcepto ".
				"         WHERE sno_hconcepto.asifidpat ='1' ".
				"			AND sno_hsalida.tipsal='P2' ".
				"			AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
				"           AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
				"           AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
				"           AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
				"           AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
				"           AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"           AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"           AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"           AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"           AND sno_hsalida.codconc = sno_hconcepto.codconc)) AS asifidpat, ".
				"		(SELECT sno_fideicomiso.capantcom  ".
				"          FROM sno_fideicomiso ".
				"         WHERE sno_fideicomiso.codemp = sno_hpersonalnomina.codemp ".
				"           AND sno_fideicomiso.codper = sno_hpersonalnomina.codper ".
				"         GROUP BY sno_hpersonalnomina.codemp, sno_hpersonalnomina.codper, sno_fideicomiso.capantcom ) AS capantcom ".
				"  FROM sno_hpersonalnomina, sno_hnomina, sno_hperiodo ".
				" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."' ".
				$ls_codnom.
				"	AND sno_hpersonalnomina.anocur = '".$ai_anocurper."' ".
				"	AND (sno_hpersonalnomina.staper = '1' OR sno_hpersonalnomina.staper = '2') ".
				"	AND substr(sno_hperiodo.fecdesper,6,2) = '".str_pad($as_mescurper,2,"0",0)."' ".
				"	AND substr(sno_hperiodo.fecdesper,1,4) = '".$ai_anocurper."' ".
				"	AND sno_hnomina.espnom = 0 ".
				"   AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"	AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				" GROUP BY sno_hpersonalnomina.codemp, sno_hpersonalnomina.codper, sno_hpersonalnomina.codnom, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ".
				" ORDER BY sno_hpersonalnomina.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_procesar_personal_version2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_nprocesados=0;
			$li_integrar=trim($this->io_sno->uf_select_config("SNO","NOMINA","INT_ASIG_EXTRA","0","I"));
			$ls_fraccion=trim($this->io_sno->uf_select_config("SNO","NOMINA","FRACCION ALICUOTA","0","I"));
			$ls_incvacagui=trim($this->io_sno->uf_select_config("SNO","NOMINA","INC_VACACIONES_AGUINALDO","0","I"));
			$ls_complemento=trim($this->io_sno->uf_select_config("SNO","NOMINA","COMPLEMENTO ANTIGUEDAD","0","I"));
			$ls_fps=trim($this->io_sno->uf_select_config("SNO","FPS VENEZUELA","COD PLAN"," ","C"));			
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$li_nprocesados=$li_nprocesados+1;
				$li_diaagui=0; // Días de Aguinaldo
				$li_diainc=0; // Días de Incidencia
				$li_diaadic=0; // Días Adicinales
				$li_diainc_agui=0; // Días de Incidencia Aguinaldo
				$li_diainc_vac=0; // Días de Incidencia Vacaciones
				$li_diacal=30; // Días de Cálculo
				$li_mescal=12; // Mes de Cálculo
				$li_diabonvac=0; // Días de bono vacacional
				$li_diaagui=0; // Dias de Aguinaldo
				$li_diafide=0; // Dias de Fideicomiso
				$lb_calcular=false; // si se debe calcular el fideicomiso para el personal
				$li_monto_vaca=0; // Monto de la alicuota de Vacaciones
				$li_monto_agui=0; // Monto de la alicuota de Aguinaldo
				$li_monto_aporte=0; // Monto del Aporte
				$ls_codper=$rs_data->fields["codper"];
				$ls_codnom=$rs_data->fields["codnom"];
				$ls_codded=$rs_data->fields["codded"];
				$ls_codtipper=$rs_data->fields["codtipper"];
				$li_sueintper=$rs_data->fields["sueintper"];
				$li_bonextper=round(($rs_data->fields["asifidper"]+$rs_data->fields["asifidpat"])/$li_diacal,2);
				$li_capantcom=trim($rs_data->fields["capantcom"]);
				if($li_capantcom=="")
				{
					$li_capantcom="0";
				}
				$li_suediaper=round((($li_sueintper)/$li_diacal),2);
				if($li_integrar=='1')
				{
					$li_suediaper=round($li_suediaper+$li_bonextper,2);
				}
				$lb_valido=$this->io_fideiconfigurable->uf_load_dias_vacaagui($ai_anocurper,$ls_codded,$ls_codtipper,$li_diabonvac,
																			  $li_diaagui);
																			  
				if($lb_valido)
				{
					$lb_valido=$this->uf_verificar_personal_version2($ls_codper,$ad_fecgen,$li_diabonvac,$li_diaagui,$lb_calcular,$li_diainc_vac,
															$li_diainc_agui,$li_diaadic,$li_diafide);
														
				}
				if(($lb_valido)&&($lb_calcular))
				{
					if($ls_fraccion=="1")
					{
						if(($li_diainc_vac!=0)||($li_diainc_agui!=0))
						{
							$li_diabonvac=$li_diainc_vac;
							$li_diaagui=$li_diainc_agui;
						}
					}
					$li_monto_vaca=((($li_suediaper*$li_diabonvac)/12)/$li_diacal);
					$li_monto_agui=((($li_suediaper*$li_diaagui)/12)/$li_diacal);
					if ($ls_incvacagui=='1') // Se incluye la alicuota de Vacaciones en los Aguinaldos
					{
						$li_monto_agui=(((($li_suediaper+$li_monto_vaca)*$li_diaagui)/12)/$li_diacal);
					}				
					if($li_integrar=='0')
					{
						$li_monto_aporte=(($li_monto_vaca+$li_monto_agui+$li_suediaper+$li_bonextper)*$li_diafide);
					}
					else
					{
						$li_monto_aporte=(($li_monto_vaca+$li_monto_agui+$li_suediaper)*$li_diafide);
					}
					if($ls_complemento=="1")
					{
						if(($li_diaadic!=0)&&($li_capantcom=="1"))
						{
							if($li_integrar=='0')
							{
								$li_monto_aporte=($li_monto_aporte+(($li_monto_vaca+$li_monto_agui+$li_suediaper+$li_bonextper)*$li_diaadic));
							}
							else
							{
								$li_monto_aporte=($li_monto_aporte+(($li_monto_vaca+$li_monto_agui+$li_suediaper)*$li_diaadic));
							}
						}
					}
					$li_monto_aporte=round($li_monto_aporte,2);
					if($this->uf_select_fideicomiso($ls_codper)==false)
					{
						$ld_fecha=$ad_fecgen;
						$ls_sql="INSERT INTO sno_fideicomiso(codemp,codper,codfid,ficfid,ubifid,cuefid,fecingfid,capfid,capantcom)VALUES".
								"('".$this->ls_codemp."','".$ls_codper."','".$ls_fps."','0000000000','0000000000',' ','".$ld_fecha."','S','0')";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_procesar_personal_version2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						}
					}
					if($lb_valido)
					{
						if($this->uf_select_fideiperiodo($ls_codnom,$ls_codper,$ai_anocurper,$as_mescurper)==false)
						{
							$ls_sql="INSERT INTO sno_fideiperiodo ".
									"(codemp,codnom,codper,anocurper,mescurper,bonvacper,bonfinper,sueintper,apoper,bonextper,diafid,diaadi)VALUES ".
									"('".$this->ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ai_anocurper."',".$as_mescurper.",".
									"".$li_monto_vaca.",".$li_monto_agui.",".$li_sueintper.",".$li_monto_aporte.",".$li_bonextper.",".$li_diafide.",".$li_diaadic.")";
							$li_row=$this->io_sql->execute($ls_sql);
							if($li_row===false)
							{
								$lb_valido=false;
								$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_procesar_personal_version2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							}
						}
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_procesar_personal_version2
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_personal_version2($as_codper,$ad_fecgen,$ai_diabonvac,$ai_diaagui,&$ab_calcular,&$ai_diainc_vac,
											&$ai_diainc_agui,&$ai_diaadic,&$ai_diafide)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_personal_version2
		//		   Access: private
		//	    Arguments: as_codper  // Código de personal
		//	    		   ad_fecgen  // Fecha de generar
		//	    		   ai_diabonvac  // Día de Bono Vacacional
		//	    		   ai_diaagui  // Día de Aguinaldo
		//	    		   ab_calcular  // si se debe calcular el fideicomiso del personal
		//	    		   ai_diainc_vac  // Días de Incidencia Vacaciones
		//	    		   ai_diainc_agui  // Días de Incidencia de Aguinaldo
		//	    		   ai_diaadic  // Días Adicinales
		//	    		   ai_diafide  // Días de Fideicomiso
		//	      Returns: lb_valido True si se ejecuto el proceso  ó False si hubo error en el proceso 
		//	  Description: Funcion que verifica que el personal se le debe calcular el fideicomiso 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecingper="";
		$li_meses=0;
		$lb_valido=$this->io_personal->uf_load_fechaingreso($as_codper,$ld_fecingper);
		if($lb_valido)
		{
			//------------------------------Calculamos los Meses en la Institución---------------------------------------
			$li_diap=intval(substr($ad_fecgen,8,2));
			$li_diai=intval(substr($ld_fecingper,8,2));
			$li_mesp=intval(substr($ad_fecgen,5,2));
			$li_mesi=intval(substr($ld_fecingper,5,2));
			$li_anop=intval(substr($ad_fecgen,0,4));
			$li_anoi=intval(substr($ld_fecingper,0,4));
			if($li_anoi==$li_anop)
			{
				if($li_mesi==$li_mesp)
				{
					$li_meses=1;
				}
				else
				{
					//$li_meses=(($li_mesp-$li_mesi)+1); SE QUITO EL +1 YA QUE LO ESTABA GENERANDO AL 3 ER MES Y NO AL CUARTO
					$li_meses=($li_mesp-$li_mesi);
				}
			}
			else
			{
				if($li_mesi<$li_mesp)
				{
					$li_meses=((12*($li_anop-$li_anoi))+(($li_mesp-$li_mesi)+1));
				}
				elseif($li_mesi==$li_mesp)
				{
					$li_meses=(12*($li_anop-$li_anoi));
				}
				elseif($li_mesi>$li_mesp)
				{			 
					$li_meses=((12*($li_anop-$li_anoi))+($li_mesp-$li_mesi)+1);
				}
			}
			//---------------------------------------------------------------------------------------------------------------
			if($li_meses==4)
			{
				$ab_calcular=true;
				$ai_diafide=round((5/30)*(30-$li_diai+1),0);
				$ai_diainc_vac=round((($li_meses*$ai_diabonvac)/12),1);
				$ai_diainc_agui=round((($li_meses*$ai_diaagui)/12),1);
			}
			elseif($li_meses>4)
			{
				$ab_calcular=true;
				$ai_diafide=5;
				if($li_meses<12)
				{
					$ai_diainc_vac=round((($li_meses*$ai_diabonvac)/12),1);
					$ai_diainc_agui=round((($li_meses*$ai_diaagui)/12),1);
				}
				else
				{
					$lb_valido=$this->uf_load_dias_adicionales($li_meses,$ai_diaadic);
				}
			}
			elseif($li_meses<4)
			{
				$ab_calcular=false;
			}
		}		
		return $lb_valido;
    }// end function uf_verificar_personal_version2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_personal_version_consejo($ai_anocurper,$as_mescurper,$aa_nominas,$ad_fecgen)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_personal_version_consejo
		//		   Access: public (sigesp_snorh_p_fideicomiso.php)
		//	    Arguments: ai_anocurper // año en curso del periodo
		//	    		   as_mescurper // mes en curso del período
		//	    		   aa_nominas // arreglo de Nóminas 
		//	    		   ad_fecgen // fecha a generar el fideicomiso
		//	      Returns: lb_valido True si se ejecuto el proceso de fideicomiso ó False si hubo error en el proceso
		//	  Description: Función que procesa el fideicomiso a todas las personas que están en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((sno_hpersonalnomina.codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (sno_hpersonalnomina.codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		$ls_sql="SELECT sno_hpersonalnomina.codper, sno_hpersonalnomina.codnom, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper, ".
				"		 SUM(sno_hresumen.asires) AS asires, SUM(sno_hpersonalnomina.sueintper) AS sueintper, ".
				"		SUM((SELECT SUM(abs(sno_hsalida.valsal))  ".
				"          FROM sno_hsalida, sno_hconcepto ".
				"         WHERE sno_hconcepto.asifidper ='1' ".
				"			AND (sno_hsalida.tipsal='A' OR  sno_hsalida.tipsal='P1')".
				"			AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
				"           AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
				"           AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
				"           AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
				"           AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
				"           AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"           AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"           AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"           AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"           AND sno_hsalida.codconc = sno_hconcepto.codconc )) AS asifidper, ".
				"		SUM((SELECT SUM(abs(sno_hsalida.valsal))  ".
				"          FROM sno_hsalida, sno_hconcepto ".
				"         WHERE sno_hconcepto.asifidpat ='1' ".
				"			AND sno_hsalida.tipsal='P2' ".
				"			AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
				"           AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
				"           AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
				"           AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
				"           AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
				"           AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"           AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"           AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"           AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"           AND sno_hsalida.codconc = sno_hconcepto.codconc)) AS asifidpat, ".
				"		(SELECT sno_fideicomiso.capantcom  ".
				"          FROM sno_fideicomiso ".
				"         WHERE sno_fideicomiso.codemp = sno_hpersonalnomina.codemp ".
				"           AND sno_fideicomiso.codper = sno_hpersonalnomina.codper ".
				"         GROUP BY sno_hpersonalnomina.codemp, sno_hpersonalnomina.codper, sno_fideicomiso.capantcom ) AS capantcom ".
				"  FROM sno_hpersonalnomina, sno_hnomina, sno_hperiodo, sno_hresumen ".
				" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."' ".
				$ls_codnom.
				"	AND sno_hpersonalnomina.anocur = '".$ai_anocurper."' ".
				"	AND (sno_hpersonalnomina.staper = '1' OR sno_hpersonalnomina.staper = '2') ".
				"	AND substr(sno_hperiodo.fecdesper,6,2) = '".str_pad($as_mescurper,2,"0",0)."' ".
				"	AND substr(sno_hperiodo.fecdesper,1,4) = '".$ai_anocurper."' ".
				"	AND sno_hnomina.espnom = 0 ".
				"   AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"	AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				"   AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"	AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				" GROUP BY sno_hpersonalnomina.codemp, sno_hpersonalnomina.codper, sno_hpersonalnomina.codnom, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ".
				" ORDER BY sno_hpersonalnomina.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_procesar_personal_version_consejo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_nprocesados=0;
			$li_integrar=trim($this->io_sno->uf_select_config("SNO","NOMINA","INT_ASIG_EXTRA","0","I"));
			$ls_complemento=$this->io_sno->uf_select_config("SNO","NOMINA","COMPLEMENTO ANTIGUEDAD","0","I");
			$ls_fps=$this->io_sno->uf_select_config("SNO","FPS VENEZUELA","COD PLAN"," ","C");
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$li_nprocesados=$li_nprocesados+1;
				$li_diaadic=0; // Días Adicinales
				$li_otrasi=0; // Otras Asignaciones
				$li_diacal=30; // Días de Cálculo
				$li_mescal=12; // Mes de Cálculo
				$li_diafide=0; // Dias de Fideicomiso
				$lb_calcular=false; // si se debe calcular el fideicomiso para el personal
				$li_monto_vaca=0; // Monto de la alicuota de Vacaciones
				$li_monto_agui=0; // Monto de la alicuota de Aguinaldo
				$li_monto_aporte=0; // Monto del Aporte
				$ls_codper=$rs_data->fields["codper"];
				$ls_codnom=$rs_data->fields["codnom"];
				$ls_codded=$rs_data->fields["codded"];
				$ls_codtipper=$rs_data->fields["codtipper"];
				$lb_valido=$this->uf_load_asignaciones($ls_codnom,$ls_codper,$ai_anocurper,$as_mescurper,$li_otrasi);
				$li_bonextper=round(($rs_data->fields["asifidper"]+$rs_data->fields["asifidpat"])/$li_diacal,2);
				$li_sueintper=$rs_data->fields["sueintper"] + $li_otrasi;
				$li_suediaper=round((($li_sueintper)/$li_diacal),2);
				if($li_integrar=='1')
				{
					$li_suediaper=round($li_suediaper+$li_bonextper,2);
				}
				$li_capantcom=trim($rs_data->fields["capantcom"]);
				if($li_capantcom=="")
				{
					$li_capantcom="0";
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_verificar_personal_version_consejo($ls_codper,$ad_fecgen,$lb_calcular,$li_diaadic,$li_diafide);
				}
				if(($lb_valido)&&($lb_calcular))
				{
					if($li_integrar=='0')
					{
						$li_monto_aporte=(($li_suediaperr+$li_bonextper)*$li_diaadic);
					}
					else
					{
						$li_monto_aporte=($li_suediaper*$li_diafide);
					}
					if($ls_complemento=="1")
					{
						if(($li_diaadic!=0)&&($li_capantcom=="1"))
						{
							if($li_integrar=='0')
							{
								$li_monto_aporte=($li_monto_aporte+(($li_suediaperr+$li_bonextper)*$li_diaadic));
							}
							else
							{
								$li_monto_aporte=($li_monto_aporte+($li_suediaper*$li_diaadic));
							}
						}
					}
					$li_monto_aporte=round($li_monto_aporte,2);
					if($this->uf_select_fideicomiso($ls_codper)==false)
					{
						$ld_fecha=$ad_fecgen;
						$ls_sql="INSERT INTO sno_fideicomiso(codemp,codper,codfid,ficfid,ubifid,cuefid,fecingfid,capfid,capantcom)VALUES".
								"('".$this->ls_codemp."','".$ls_codper."','".$ls_fps."','0000000000','0000000000',' ','".$ld_fecha."','S','0')";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_procesar_personal_version_consejo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						}
					}
					if($lb_valido)
					{
						if($this->uf_select_fideiperiodo($ls_codnom,$ls_codper,$ai_anocurper,$as_mescurper)==false)
						{
							$ls_sql="INSERT INTO sno_fideiperiodo ".
									"(codemp,codnom,codper,anocurper,mescurper,bonvacper,bonfinper,sueintper,apoper,bonextper,diafid,diaadi)VALUES ".
									"('".$this->ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ai_anocurper."',".$as_mescurper.",".
									"".$li_monto_vaca.",".$li_monto_agui.",".$li_sueintper.",".$li_monto_aporte.",".$li_bonextper.",".$li_diafide.",".$li_diaadic.")";
							$li_row=$this->io_sql->execute($ls_sql);
							if($li_row===false)
							{
								$lb_valido=false;
								$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_procesar_personal_version_consejo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							}
														///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////													
						}
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_procesar_personal_version_consejo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_personal_version_consejo($as_codper,$ad_fecgen,&$ab_calcular,&$ai_diaadic,&$ai_diafide)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_personal_version_consejo
		//		   Access: private
		//	    Arguments: as_codper  // Código de personal
		//	    		   ad_fecgen  // Fecha de generar
		//	    		   ab_calcular  // si se debe calcular el fideicomiso del personal
		//	    		   ai_diaadic  // Días Adicinales
		//	    		   ai_diafide  // Días de Fideicomiso
		//	      Returns: lb_valido True si se ejecuto el proceso  ó False si hubo error en el proceso 
		//	  Description: Funcion que verifica que el personal se le debe calcular el fideicomiso 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecingper="";
		$li_meses=0;
		$lb_valido=$this->io_personal->uf_load_fechaingreso($as_codper,$ld_fecingper);
		if($lb_valido)
		{
			//------------------------------Calculamos los Meses en la Institución---------------------------------------
			$li_diap=intval(substr($ad_fecgen,8,2));
			$li_diai=intval(substr($ld_fecingper,8,2));
			$li_mesp=intval(substr($ad_fecgen,5,2));
			$li_mesi=intval(substr($ld_fecingper,5,2));
			$li_anop=intval(substr($ad_fecgen,0,4));
			$li_anoi=intval(substr($ld_fecingper,0,4));
			if($li_anoi==$li_anop)
			{
				if($li_mesi==$li_mesp)
				{
					$li_meses=1;
				}
				else
				{
					//$li_meses=(($li_mesp-$li_mesi)+1); SE QUITO EL +1 YA QUE LO ESTABA GENERANDO AL 3 ER MES Y NO AL CUARTO
					$li_meses=($li_mesp-$li_mesi);
				}
			}
			else
			{
				if($li_mesi<$li_mesp)
				{
					$li_meses=((12*($li_anop-$li_anoi))+(($li_mesp-$li_mesi)+1));
				}
				elseif($li_mesi==$li_mesp)
				{
					$li_meses=(12*($li_anop-$li_anoi));
				}
				elseif($li_mesi>$li_mesp)
				{
					$li_meses=((12*($li_anop-$li_anoi))+($li_mesp-$li_mesi)+1);
				}
			}
			//---------------------------------------------------------------------------------------------------------------
			if($li_meses==4)
			{
				$ab_calcular=true;
				$ai_diafide=5;
			}
			elseif($li_meses>4)
			{
				$ab_calcular=true;
				$ai_diafide=5;
				if($li_meses>12)
				{
					$lb_valido=$this->uf_load_dias_adicionales($li_meses,$ai_diaadic);
				}
			}
			elseif($li_meses<4)
			{
				$ab_calcular=false;
			}
		}
		return $lb_valido;
    }// end function uf_verificar_personal_version_consejo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_asignaciones($as_codnom,$as_codper,$as_anocurper,$ai_mescurper,&$ai_asi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_asignaciones
		//		   Access: private
		//	    Arguments: as_codnom  // Código de Nómina
		//	    		   as_codper  // Código de Personal
		//	    		   as_anocurper  // año en curso fideicomiso
		//	    		   ai_mescurper  // mes en curso fideicomiso
		//	    		   ai_asi  // Monto de asignaciones
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que obtiene las asignaciones de otras nóminas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT COALESCE(SUM(sno_hsalida.valsal),0) as  valor ".
				"  FROM sno_hsalida, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom<>'".$as_codnom."' ".
				"   AND sno_hsalida.codper='".$as_codper."' ".
				"   AND sno_hsalida.tipsal='A' ".
				"   AND sno_hsalida.valsal<>0 ".
				"   AND sno_hsalida.anocur='".$as_anocurper."' ".
				"	AND substr(sno_hperiodo.fecdesper,6,2)='".str_pad($ai_mescurper,2,"0",0)."' ".
				"	AND substr(sno_hperiodo.fecdesper,1,4)='".$as_anocurper."' ".
				"   AND sno_hsalida.codemp=sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom=sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur=sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi=sno_hperiodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_load_asignaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$ai_asi=$rs_data->fields["valor"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_asignaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_dias_adicionales($ai_meses,&$ai_diaadic)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dias_adicionales
		//		   Access: private
		//	    Arguments: ai_meses  // Meses en la Institución
		//	    		   ai_diaadic  // Días Adicinales que le corresponden
		//	      Returns: lb_valido True si no ocurrio algún error False si hubo errores
		//	  Description: Funcion que obtiene los días adicinales de acuerdo a los meses laborados
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($ai_meses)
		{
			case 24:
				$ai_diaadic=2;
				break;
			case 36:
				$ai_diaadic=4;
				break;
			case 48:
				$ai_diaadic=6;
				break;
			case 60:
				$ai_diaadic=8;
				break;
			case 72:
				$ai_diaadic=10;
				break;
			case 84:
				$ai_diaadic=12;
				break;
			case 96:
				$ai_diaadic=14;
				break;
			case 108:
				$ai_diaadic=16;
				break;
			case 120:
				$ai_diaadic=18;
				break;
			case 132:
				$ai_diaadic=20;
				break;
			case 144:
				$ai_diaadic=22;
				break;
			case 156:
				$ai_diaadic=24;
				break;
			case 168:
				$ai_diaadic=26;
				break;
			case 180:
				$ai_diaadic=28;
				break;
			case 192:
				$ai_diaadic=30;
				break;
		}
		if($ai_meses>192)
		{
			$li_resto = $ai_meses%12; 
			if($li_resto!=0)
			{
				$ai_diaadic=0;
			}
			else
			{
				$ai_diaadic=30;
			}
		}
		return $lb_valido;
	}// end function uf_load_dias_adicionales
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generar_data_contabilizacion($ai_anocurper,$as_mescurper,$aa_nominas,$ad_fecgen)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_data_contabilizacion 
		//	    Arguments: ai_anocurper  //  Año en curso
		//	    		   as_mescurper  //  Mes 
		//	    		   aa_nominas  //  Arreglo de Nóminas
		//	    		   ad_fecgen  //  Fecha en que se genro el fideicomiso
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de generar la data para la contabilización del fideicomiso
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 31/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;($li_i<$li_totnom)&&($lb_valido);$li_i++)
		{
			$ls_codnom=$aa_nominas[$li_i];
			$ls_cuentapasivo="";
			$ls_operacion="";
			$ls_tipodestino="B";
			$ls_codpro="----------";
			$ls_codben="";
			$li_genrecdoc="";
			$li_tipdoc="";
			$ls_anocurnom="";
			$ls_desnom="";
			$lb_valido=$this->load_nomina($ls_codnom,$ai_anocurper,$as_mescurper,&$ls_desnom,&$ls_anocurnom,&$ls_operacion,&$li_genrecdoc,&$li_tipdoc,&$ls_codben,&$ls_cuentapasivo);
			$ls_comprobante=$ls_anocurnom."-".$ls_codnom."-".str_pad($as_mescurper,3,"0",0)."-P"; // Comprobante de Fideicomiso
			$ls_descripcion=$ls_desnom." PRESTACIÓN ANTIGUEDAD - MES ".$as_mescurper." del Año ".$ai_anocurper; // Descripción de Conceptos
			// Obtenemos la configuración de la contabilización del Fideicomiso
			$lb_valido=$this->uf_load_configuracion_contabilizacion($ls_cuentapasivo,$ls_operacion,$ls_codben,$li_genrecdoc,$li_tipdoc);
			if($lb_valido)
			{	// eliminamos la contabilización anterior 
				$lb_valido=$this->uf_delete_contabilizacion($ls_comprobante);
				
			}														
			if($lb_valido)
			{ // insertamos la contabilización de presupuesto de conceptos
				$lb_valido=$this->uf_contabilizar_conceptos_spg($ls_codnom,$as_mescurper,$ls_comprobante,$ls_operacion,$ls_codpro,$ls_codben,$ls_tipodestino,
																$ls_descripcion,$li_genrecdoc,$li_tipdoc,$ls_anocurnom);
			}
			if($lb_valido)
			{// insertamos la contabilización de contabilidad de conceptos
				if($ls_operacion!="O")// Si es compromete no genero detalles contables
				{
					$lb_valido=$this->uf_contabilizar_conceptos_scg($ls_codnom,$as_mescurper,$ls_comprobante,$ls_operacion,$ls_codpro,$ls_codben,$ls_tipodestino,
																	$ls_descripcion,$li_genrecdoc,$li_tipdoc,$ls_cuentapasivo,$ls_anocurnom);
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_verificar_contabilizacion($ls_comprobante); // Nómina
			}
		}
		// Se coloca en true por que a pesar de que esta data no se genere de debe crear el fideicomiso
	   	$lb_valido=true;
		return  $lb_valido;    
	}// end function uf_generar_data_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_configuracion_contabilizacion(&$as_cuentapasivo,&$as_modo,&$as_codben,&$ai_genrecdoc,&$ai_tipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_configuracion_contabilizacion 
		//	    Arguments: as_cuentapasivo  //  cuenta pasivo a la que va la nómina
		//	    		   as_modo  //  modo de contabilización de la nómina
		//	    		   as_destino  //  destino de la contabilización
		//	    		   as_codpro  //  código de proveedor
		//	    		   as_codben  // código de beneficiario
		//	    		   ai_genrecdoc  // generar recepción de documento
		//	    		   ai_tipdoc  // Tipo de documento
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que busca los datos de la configuración de la contabilización de la Prestación Antiguedad
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 07/08/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$li_parametros=$this->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ai_genrecdoc=$this->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO","0" ,"I");
				$ai_tipdoc=$this->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO","","C");
				$as_modo=$this->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION FIDEICOMISO","OCP","C");
				$as_cuentapasivo=trim($this->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO","XXXXXXXXXXXXX","C"));
				$as_codben=trim($this->io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));
		}
		if(trim($as_codben)=="")
		{
			$lb_valido=false;
			$this->io_mensajes->message("ERROR-> Debe Seleccionar un Beneficiario. ");
		}
		if(trim($as_modo)=="")
		{
			$lb_valido=false;
			$this->io_mensajes->message("ERROR-> Debe Seleccionar un modo de Contabilización para el Fideicomiso. ");
		}
		if($ai_genrecdoc=="1") // Genera recepción de Documento de la Nómina.
		{
			if(trim($ai_tipdoc)=="")
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Debe Seleccionar un Tipo de Documento,Para la Recepción de Documento. ");
			}
		}
		else
		{
			if(trim($as_cuentapasivo)=="")
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Debe Seleccionar una Cuenta Contable para el Fideicomiso. ");
			}
		}
		return  $lb_valido;    
	}// end function uf_load_configuracion_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function load_nomina($as_codnom,$ai_anocurper,$as_mes,&$as_desnom,&$as_anocurnom,&$as_confidnom,&$as_recdocfid,&$as_tipdocfid,&$as_codbenfid,&$as_cueconfid)
	{
		/////////////////////////////////////////////////////////////////////////////////
		//	Function:   load_nomina
		//	Arguments:  as_codnom// codigo de la nomina
		//              ai_anocurper // Año en curso del fideicomiso
		//              as_mes // Mes en curso del fideicomiso
		//              as_desnom // denominacion de la nomina
		//              as_anocurnom  //  año en curso
		//	  			as_confidnom  //  Modo de Contabilización
		//	    		as_recdocfid  //  Generar Recepción de Documentos
		//	    		as_tipdocfid  //  Tipo de Documentos
		//	    		as_codbenfid  //  Código del Beneficiario
		//	    		as_cueconfid  //  Cuenta Contale
		//	Returns:	True si hizo el select correctamente o False en caso contrario
		//	Description:  Funcion que me devuelve los datos de  la nomina
		//////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT desnom, anocurnom, confidnom, recdocfid, tipdocfid, codbenfid, cueconfid ".
			    "  FROM sno_hnomina ".
				"  INNER JOIN sno_hperiodo ".
				"    ON sno_hnomina.codemp = sno_hperiodo.codemp ".
				"	AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"	AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
			    " WHERE sno_hnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hnomina.codnom='".$as_codnom."' ".
				"   AND sno_hnomina.anocurnom='".$ai_anocurper."' ".
				"   AND SUBSTR(fecdesper,6,2)='".$as_mes."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   if($rs_data===false)
	   {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Nómina MÉTODO->load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	   }
	   else
	   {
		   if(!$rs_data->EOF)
		   {
				$as_desnom=$rs_data->fields["desnom"];
				$as_anocurnom=$rs_data->fields["anocurnom"];
				$as_confidnom=$rs_data->fields["confidnom"];
				$as_recdocfid=$rs_data->fields["recdocfid"];
				$as_tipdocfid=$rs_data->fields["tipdocfid"];
				$as_codbenfid=$rs_data->fields["codbenfid"];
				$as_cueconfid=$rs_data->fields["cueconfid"];
		   }
	   }
	   return $lb_valido;   
	}// end function load_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	 

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_contabilizacion($as_comprobante)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_contabilizacion
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la contabilización de la Prestación Antiguedad
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 07/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_dt_spg ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcom='".$as_comprobante."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_contabilizacion_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM sno_dt_scg ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcom='".$as_comprobante."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_contabilizacion_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}		
		}
		return $lb_valido;
    }// end function uf_delete_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_conceptos_spg($as_codnom,$as_mescurper,$as_comprobante,$as_operacion,$as_codpro,$as_codben,$as_tipodestino,
										   $as_descripcion,$ai_genrecdoc,$ai_tipdoc,$as_anocurnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_conceptos_spg 
		//	    Arguments: as_codnom  //  Código de Nómina
		//	    		   as_mescurper  //  Mes
		//	    		   as_comprobante  //  Código de Comprobante
		//	    		   as_operacion  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización del Fideicomiso
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 07/05/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_tipnom="P"; // tipo de contabilización
		$ls_sql="SELECT MAX(sno_fideiperiodo.apoper) AS total, MAX(spg_cuentas.spg_cuenta) AS spg_cuenta, ".
				"       MAX(sno_hunidadadmin.codprouniadm) AS programatica, MAX(sno_hunidadadmin.estcla) AS estcla, ".
				" 		SUBSTR(sno_hunidadadmin.codprouniadm,1,25) AS codestpro1,SUBSTR(sno_hunidadadmin.codprouniadm,25,25) AS codestpro2,SUBSTR(sno_hunidadadmin.codprouniadm,50,25) AS codestpro3,".
				" 		SUBSTR(sno_hunidadadmin.codprouniadm,75,25) AS codestpro4,SUBSTR(sno_hunidadadmin.codprouniadm,100,25) AS codestpro5 ".
				"  FROM sno_fideiperiodo  ".
				" INNER JOIN (sno_hpersonalnomina ".
				" 		INNER JOIN sno_hperiodo ".
				"    		ON sno_hpersonalnomina.codemp=sno_hperiodo.codemp ".
				"   	   AND sno_hpersonalnomina.codnom=sno_hperiodo.codnom ".
				"   	   AND sno_hpersonalnomina.anocur=sno_hperiodo.anocur ".
				"   	   AND sno_hpersonalnomina.codperi=sno_hperiodo.codperi) ".
				"    ON sno_fideiperiodo.codemp=sno_hpersonalnomina.codemp ".
				"   AND sno_fideiperiodo.codnom=sno_hpersonalnomina.codnom ".
				"   AND sno_fideiperiodo.anocurper=sno_hpersonalnomina.anocur ".
				"   AND sno_fideiperiodo.codper=sno_hpersonalnomina.codper ".
				" INNER JOIN sno_fideiconfigurable ".
				"    ON sno_hpersonalnomina.codemp=sno_fideiconfigurable.codemp ".
				"   AND sno_hpersonalnomina.anocur=sno_fideiconfigurable.anocurfid ". 
				"   AND sno_hpersonalnomina.codded=sno_fideiconfigurable.codded ". 
				"   AND sno_hpersonalnomina.codtipper=sno_fideiconfigurable.codtipper ".
				" INNER JOIN sno_hunidadadmin ".
				"    ON sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				" INNER JOIN spg_cuentas ".
				"    ON sno_fideiconfigurable.codemp=spg_cuentas.codemp ".
				"   AND sno_fideiconfigurable.cueprefid=spg_cuentas.spg_cuenta ".
				" WHERE sno_fideiperiodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_fideiperiodo.codnom='".$as_codnom."' ".
				"   AND sno_fideiperiodo.anocurper='".$as_anocurnom."' ".
				"   AND sno_fideiperiodo.mescurper='".$as_mescurper."' ".
				"   AND sno_fideiperiodo.apoper>0 ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mescurper."' ".
				" GROUP BY sno_fideiperiodo.codper,sno_hunidadadmin.codprouniadm  ". 
				" ORDER BY sno_hunidadadmin.codprouniadm";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_contabilizar_conceptos_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$li_totrow=$this->DS->getRowCount("spg_cuenta");
				$this->DS->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4','4'=>'codestpro5','5'=>'estcla','6'=>'spg_cuenta'),array('0'=>'total'),'programatica');
				$li_totrow=$this->DS->getRowCount("spg_cuenta");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_programatica=$this->DS->data["programatica"][$li_i];
					$ls_estcla=$this->DS->data["estcla"][$li_i];
					$ls_cueprecon=$this->DS->data["spg_cuenta"][$li_i];
					$li_total=round($this->DS->data["total"][$li_i],2);
					$ls_codconc="0000000001";
					$ls_codcomapo="0000000001";
					$lb_valido=$this->uf_insert_contabilizacion_spg($as_comprobante,$as_operacion,$as_codpro,$as_codben,$as_tipodestino,
																	$as_descripcion,$ls_programatica,$ls_estcla,
																	$ls_cueprecon,$li_total,$ls_tipnom,$ls_codconc,$ai_genrecdoc,
																	$ai_tipdoc,0,0,$ls_codcomapo,$as_codnom,$as_mescurper);
				}
				$this->DS->resetds("spg_cuenta");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contabilizar_conceptos_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_contabilizacion_spg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
									 	   $as_programatica,$as_estcla,$as_cueprecon,$ai_monto,$as_tipnom,$as_codconc,$ai_genrecdoc,$ai_tipdoc,
										   $ai_gennotdeb,$ai_genvou,$as_codcomapo,$as_codnom,$as_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_contabilizacion_spg
		//		   Access: private
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_programatica  //  Programática
		//	    		   as_cueprecon  //  cuenta presupuestaria
		//	    		   ai_monto  //  monto total
		//	    		   as_tipnom  //  Tipo de contabilizacion si es de nómina ó de aporte
		//			       as_codconc // código del concepto
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	    		   as_codcomapo  //  Código del comprobante de aporte
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el total des las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_estatus=0; // No contabilizado
		$ls_codestpro1=substr($as_programatica,0,25);
		$ls_codestpro2=substr($as_programatica,25,25);
		$ls_codestpro3=substr($as_programatica,50,25);
		$ls_codestpro4=substr($as_programatica,75,25);
		$ls_codestpro5=substr($as_programatica,100,25);
		$ls_sql="INSERT INTO sno_dt_spg(codemp,codnom,codperi,codcom,tipnom,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				"spg_cuenta,operacion,codconc,cod_pro,ced_bene,tipo_destino,descripcion,monto,estatus,estrd,codtipdoc,estnumvou,".
				"estnotdeb,codcomapo,estcla,codfuefin) VALUES ('".$this->ls_codemp."','".$as_codnom."','".str_pad($as_mescurper,3,"0",0)."','".$as_codcom."',".
				"'".$as_tipnom."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."',".
				"'".$as_cueprecon."','".$as_operacionnomina."','".$as_codconc."','".$as_codpro."','".$as_codben."','".$as_tipodestino."',".
				"'".$as_descripcion."',".$ai_monto.",".$li_estatus.",".$ai_genrecdoc.",'".$ai_tipdoc."',".$ai_genvou.",".$ai_gennotdeb.",".
				"'".$as_codcomapo."','".$as_estcla."','--')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_insert_contabilizacion_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_contabilizacion_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_conceptos_scg($as_codnom,$as_mescurper,$as_comprobante,$as_operacion,$as_codpro,$as_codben,$as_tipodestino,
										   $as_descripcion,$ai_genrecdoc,$ai_tipdoc,$as_cuentapasivo,$as_anocurnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_conceptos_scg 
		//	    Arguments: as_codnom  //  Código de Nómina
		//	    		   as_mescurper  //  Mes
		//	    		   as_comprobante  //  Código de Comprobante
		//	    		   as_operacion  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 09/05/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_tipnom="P";
		$ls_sql="SELECT sno_fideiperiodo.codper, MAX(sno_fideiperiodo.apoper) AS total, MAX(scg_cuentas.sc_cuenta) AS sc_cuenta, CAST('D' AS char(1)) as operacion ".
				"  FROM sno_fideiperiodo  ".
				" INNER JOIN (sno_hpersonalnomina ".
				" 		INNER JOIN sno_hperiodo ".
				"    		ON sno_hpersonalnomina.codemp=sno_hperiodo.codemp ".
				"   	   AND sno_hpersonalnomina.codnom=sno_hperiodo.codnom ".
				"   	   AND sno_hpersonalnomina.anocur=sno_hperiodo.anocur ".
				"   	   AND sno_hpersonalnomina.codperi=sno_hperiodo.codperi) ".
				"    ON sno_fideiperiodo.codemp=sno_hpersonalnomina.codemp ".
				"   AND sno_fideiperiodo.codnom=sno_hpersonalnomina.codnom ".
				"   AND sno_fideiperiodo.anocurper=sno_hpersonalnomina.anocur ".
				"   AND sno_fideiperiodo.codper=sno_hpersonalnomina.codper ".
				" INNER JOIN sno_fideiconfigurable ".
				"    ON sno_hpersonalnomina.codemp=sno_fideiconfigurable.codemp ".
				"   AND sno_hpersonalnomina.anocur=sno_fideiconfigurable.anocurfid ". 
				"   AND sno_hpersonalnomina.codded=sno_fideiconfigurable.codded ". 
				"   AND sno_hpersonalnomina.codtipper=sno_fideiconfigurable.codtipper ".
				" INNER JOIN sno_hunidadadmin ".
				"    ON sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				" INNER JOIN spg_cuentas ".
				"    ON sno_fideiconfigurable.codemp=spg_cuentas.codemp ".
				"   AND sno_fideiconfigurable.cueprefid=spg_cuentas.spg_cuenta ".
				" INNER JOIN scg_cuentas ".
				"    ON spg_cuentas.codemp=scg_cuentas.codemp ".
				"   AND spg_cuentas.sc_cuenta=scg_cuentas.sc_cuenta ".
				" WHERE sno_fideiperiodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_fideiperiodo.codnom='".$as_codnom."' ".
				"   AND sno_fideiperiodo.anocurper='".$as_anocurnom."' ".
				"   AND sno_fideiperiodo.mescurper='".$as_mescurper."' ".
				"   AND sno_fideiperiodo.apoper>0 ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mescurper."' ".
				" GROUP BY sno_fideiperiodo.codper ".
				" UNION ".
				"SELECT sno_fideiperiodo.codper, MAX(sno_fideiperiodo.apoper) AS total, MAX(rpc_beneficiario.sc_cuenta) AS sc_cuenta, CAST('H' AS char(1)) as operacion ".
				"  FROM sno_fideiperiodo  ".
				" INNER JOIN sno_hperiodo ".
				"    ON sno_hperiodo.codemp=sno_fideiperiodo.codemp ".
				"   AND sno_hperiodo.codnom=sno_fideiperiodo.codnom ".
				" INNER JOIN sno_hnomina ".
				"    ON sno_hperiodo.codemp=sno_hnomina.codemp ".
				"   AND sno_hperiodo.codnom=sno_hnomina.codnom ".
				"   AND sno_hperiodo.anocur=sno_hnomina.anocurnom ".
				"   AND sno_hperiodo.codperi=sno_hnomina.peractnom ".
				" INNER JOIN rpc_beneficiario ".
				"    ON sno_hnomina.codemp=rpc_beneficiario.codemp ".
				"   AND sno_hnomina.codbenfid=rpc_beneficiario.ced_bene ".
				" WHERE sno_fideiperiodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_fideiperiodo.codnom='".$as_codnom."' ".
				"   AND sno_fideiperiodo.anocurper='".$as_anocurnom."' ".
				"   AND sno_fideiperiodo.mescurper='".$as_mescurper."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mescurper."' ".
				"   AND sno_fideiperiodo.apoper>0 ".
				"   AND sno_hnomina.recdocfid='1' ".
				" GROUP BY sno_fideiperiodo.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_contabilizar_conceptos_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'sc_cuenta','1'=>'operacion'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("sc_cuenta");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_cuenta=$this->DS->data["sc_cuenta"][$li_i];
					$ls_operacion=$this->DS->data["operacion"][$li_i];
					$li_total=abs(round($this->DS->data["total"][$li_i],2));
					$ls_codconc="0000000001";
					$ls_codcomapo="0000000001";
					$lb_valido=$this->uf_insert_contabilizacion_scg($as_comprobante,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
																	$ls_cuenta,$ls_operacion,$li_total,$ls_tipnom,$ls_codconc,
																	$ai_genrecdoc,$ai_tipdoc,0,0,$ls_codcomapo,$as_codnom,$as_mescurper);
				}
				$this->DS->resetds("sc_cuenta");
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;	  
	}// end function uf_contabilizar_conceptos_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_contabilizacion_scg($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,$as_cuenta,$as_operacion,
									 	   $ai_monto,$as_tipnom,$as_codconc,$ai_genrecdoc,$ai_tipdoc,$ai_gennotdeb,$ai_genvou,$as_codcomapo,
										   $as_codnom,$as_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_contabilizacion_scg
		//		   Access: private
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_programatica  //  Programática
		//	    		   as_cueprecon  //  cuenta presupuestaria
		//	    		   ai_monto  //  monto total
		//	    		   as_tipnom  //  Tipo de contabilización es aporte ó de conceptos
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   as_codconc  //  Código de concepto
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	    		   as_codcomapo  //  Código del comprobante de aporte
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el total des las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_estatus=0; // No contabilizado
		$ls_sql="INSERT INTO sno_dt_scg(codemp,codnom,codperi,codcom,tipnom,sc_cuenta,debhab,codconc,cod_pro,ced_bene,tipo_destino,".
				"descripcion,monto,estatus,estrd,codtipdoc,estnumvou,estnotdeb,codcomapo) VALUES ('".$this->ls_codemp."','".$as_codnom."',".
				"'".str_pad($as_mescurper,3,"0",0)."','".$as_codcom."','".$as_tipnom."','".$as_cuenta."','".$as_operacion."','".$as_codconc."',".
				"'".$as_codpro."','".$as_codben."','".$as_tipodestino."','".$as_descripcion."',".$ai_monto.",".$li_estatus.",".
				"'".$ai_genrecdoc."','".$ai_tipdoc."','".$ai_genvou."','".$ai_gennotdeb."','".$as_codcomapo."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_insert_contabilizacion_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_contabilizacion_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_contabilizacion($as_codcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_contabilizacion 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de verificar que lo mismo que esta por el debe tambien este por el haber en contabilidad
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 29/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_sql="SELECT debhab, sum(monto) as total ".
				"  FROM sno_dt_scg ".
				" WHERE codcom = '".$as_codcom."' ".
				" GROUP BY debhab ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cierre Periodo 4 MÉTODO->uf_verificar_contabilizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$li_debe=0;
			$li_haber=0;
			while(!$rs_data->EOF)
			{
				$li_operacion=$rs_data->fields["debhab"];
				if($li_operacion=="D")
				{
					$li_debe=number_format($rs_data->fields["total"],2,".","");
				}
				else
				{
					$li_haber=number_format($rs_data->fields["total"],2,".","");
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
			if($li_debe!=$li_haber)
			{
				$lb_valido=false;
				$this->io_mensajes->message("Los Monto en la Contabilización de Prestación Antiguedad no cuadran. Debe=".$this->io_fun_nomina->uf_formatonumerico($li_debe)." Haber ".$this->io_fun_nomina->uf_formatonumerico($li_haber).". Verifique la información ");
			}
		}		
		return  $lb_valido;    
	}// end function uf_verificar_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
