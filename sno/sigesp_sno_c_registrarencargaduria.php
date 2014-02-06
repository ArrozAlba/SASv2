<?php
class sigesp_sno_c_registrarencargaduria
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_fecha;
	var $io_sno;
	var $in_cuota;	
	var $ls_codemp;
	var $ls_codnom;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_registrarencargaduria()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_registrarencargaduria
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
        	$this->ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
        	$this->ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		}
		else
		{
			$this->ls_codnom="0000";
        	$this->ld_fecdesper="1900-01-01";
        	$this->ld_fechasper="1900-01-01";
		}
		
	}// end function sigesp_sno_c_registrarencargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_prestamo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
		unset($this->io_fecha);
		unset($this->io_sno);
		unset($this->io_cuota);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_cargarnomina($as_codnom)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargarnomina
		//		   Access: private
		//	  Description: Función que obtiene todas las nóminas y las carga en un 
		//				   combo para seleccionarlas
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		
		switch($as_codnom)
		{
			case "":
				$ls_selected="selected";
				$ls_disabled="";
				break;
			default:
				$ls_selected="";
				$ls_disabled="disabled";
				break;
		}
		
		$ls_sql="SELECT sno_nomina.codnom, sno_nomina.desnom ".
				"  FROM sno_nomina, sss_permisos_internos ".
				" WHERE sno_nomina.codemp='".$this->ls_codemp."'".
				"   AND sss_permisos_internos.codsis='SNO'".
				"   AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."'".
				"   AND sno_nomina.codemp = sss_permisos_internos.codemp ".
				"   AND sno_nomina.codnom = sss_permisos_internos.codintper ".
				"   AND sno_nomina.espnom=0 ".
				" GROUP BY sno_nomina.codnom, sno_nomina.desnom ".
				" ORDER BY sno_nomina.codnom, sno_nomina.desnom ";
				
		$rs_data=$this->io_sql->select($ls_sql);
       	print "<select name='cmbnomina' id='cmbnomina' style='width:380px' ".$ls_disabled." onChange='javascript: ue_cambio_nomina();'>";
        print " <option value='' ".$ls_selected.">--Seleccione Una--</option>";
		if($rs_data===false)
		{
        	$io_mensajes->message("Clase->Registro Encargaduria Método->uf_cargarnomina Error->".$io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
				$ls_selected="";
				if($as_codnom==$ls_codnom)
				{
					$ls_selected="selected";
				}
            	print "<option value='".$ls_codnom."' ".$ls_selected.">".$ls_codnom."-".$ls_desnom."</option>";				
			}
			$this->io_sql->free_result($rs_data);
		}
       	print "</select>";
		print "<input name='txtcodnom' type='hidden' id='txtcodnom' value='".$as_codnom."'>";
   }
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_generar_codigo_encargaduria()
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_codigo_encargaduria
		//         Access: public (sigesp_srh_p_accidentes)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un registro de un accidente de personal
		//    Description: Funcion que genera un código de registro de un accidente de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:17/01/2008							Fecha Última Modificación:17/01/2008 Prueba
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codenc=0;
	  
		$ls_sql = " SELECT MAX(codenc) AS codigo ".
				  " FROM sno_encargaduria ".
				  " WHERE codemp='".$this->ls_codemp."' ".
				  "   AND codnom='".$this->ls_codnom."' ";
		$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if ($lb_hay)
		{
			$ls_codenc = $la_datos["codigo"][0]+1;
		}
		
		$ls_codenc= str_pad ($ls_codenc,10,"0","left");
		
		return $ls_codenc;
  }
  //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_encargaduria($as_codenc,$as_tipenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc, $as_codper,$as_codnom, $as_codnomenc, $as_codperenc,$as_susper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_encargaduria
		//		   Access: private
		//	    Arguments: as_codenc // código de la encargaduría
		//                 ad_fecinienc // fecha de inicio de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//                 as_obsenc // observación de la encargaduría
		//                 as_codper // código de personal a quién se le va a hacer la encargaduría
		//                 as_codnomenc // código de la nomina del personal encargado
		//                 as_codperenc // código del personal encargado
		//                 as_susper  // indica si la persona se suspende de la nómina
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el Registro de Encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_encargaduria (codemp, codenc, tipenc, fecinienc, fecfinenc, codper, codnom, codperenc, codnomperenc, estenc ,obsenc, estsuspernom)VALUES".
				"('".$this->ls_codemp."','".$as_codenc."','".$as_tipenc."','".$ad_fecinienc."','".$ad_fecfinenc."','".$as_codper."','".$this->ls_codnom."','".$as_codperenc."','".$as_codnomenc."', '1', '".$as_obsenc."', '".$as_susper."')";

		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el  Registro de Encargaduría ".$as_codenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_mensajes->message("La Encargaduría Fue Registrada");
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_encargaduriao ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_mensajes->message("Ocurrió un error al Registrar la Encargaduría");
				
			}
		}
		return $lb_valido;
	}// end function uf_insert_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_update_encargaduria($as_codenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc,$as_susper, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_encargaduria
		//		   Access: private
		//	    Arguments: as_codenc // código de la encargaduría
		//                 ad_fecinienc // fecha de inicio de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//                 as_obsenc // observación de la encargaduría       
		//                 as_susper  // indica si la persona se suspende de la nómina
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el registro de la encargaduria
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_encargaduria ".
				"   SET fecinienc='".$ad_fecinienc."', ".
				"       fecfinenc='".$ad_fecfinenc."', ".
				"       obsenc='".$as_obsenc."', ".
				"       estsuspernom = '".$as_susper."' ".							
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codenc='".$as_codenc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_update_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el  Registro de Encargaduria ".$as_codenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_mensajes->message("La Encargaduría Fue Actualizada");
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_update_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_mensajes->message("Ocurrió un error al Actualizar la Encargaduría"); 
				
			}
		}
		return $lb_valido;
	}// end function uf_update_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------
	   
 function uf_guardar($as_existe,$as_codenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc, $as_codper, $as_codnomenc, $as_codperenc,$as_susper, $aa_seguridad)
 {
 
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: private
		//	    Arguments: as_existe // variable que indica si la encargaduria se encuentra registrada
		//                 as_codenc // código de la encargaduría
		//                 ad_fecinienc // fecha de inicio de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//                 as_obsenc // observación de la encargaduría
		//                 as_codper // código de personal a quién se le va a hacer la encargaduría
		//                 as_codnomenc // código de la nomina del personal encargado
		//                 as_codperenc // código del personal encargado
		//                 as_susper    // indica si la persona se suspende de la nómina
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda el registro de la encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_codnom=$this->ls_codnom;
		if ($ls_codnom!=$as_codnomenc)
		{
			$as_tipenc='2';
		}
		else
		{
			$as_tipenc='1';
		}
		if (($ad_fecinienc=="dd/mm/aaaa")||($ad_fecinienc==""))
		{
			$ad_fecinienc='01/01/1900';
		}
		if (($ad_fecfinenc=="dd/mm/aaaa")||($ad_fecfinenc==""))
		{
			$ad_fecfinenc='01/01/1900';
		}	
		if (trim($as_obsenc)=="")
		{
			$as_obsenc='SIN OBSERVACION';
		}	
		$ad_fecinienc=$this->io_funciones->uf_convertirdatetobd($ad_fecinienc);
		$ad_fecfinenc=$this->io_funciones->uf_convertirdatetobd($ad_fecfinenc);
		
		$this->io_sql->begin_transaction();
		switch ($as_existe)
		{
			case "FALSE":	
			
				if ($as_tipenc=='2') // cuando la encargaduria es en nóminas diferentes
				{			
					$lb_valido=$this->uf_suspender_personal_nomina($as_codnomenc, $as_codperenc,$ad_fecinienc);					
					
					if ($lb_valido)
					{
						$lb_existe=$this->uf_chequear_personal_nomina($as_codperenc);
						if($lb_existe)
						{								
							$lb_valido=$this->uf_update_personal_encargado_nomina($as_codper,$as_codperenc,$ad_fecinienc,$aa_seguridad);								
						}
					    else
						{
							$lb_valido=$this->uf_insert_personal_encargado_nomina($as_codper,$as_codperenc,$ad_fecinienc,$aa_seguridad);
						}
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_actualizar_datos_nomina_personal_encargado($as_codnomenc,$as_codperenc,$aa_seguridad);				
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_delete_conceptos_encargado_nomina($as_codperenc,$aa_seguridad);				
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_delete_constantes_encargado_nomina($as_codperenc,$aa_seguridad);				
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_insert_conceptos_personal_encargado($as_codper,$as_codperenc,$aa_seguridad);				
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_insert_constantes_personal_encargado($as_codper,$as_codperenc,$aa_seguridad);				
					}
					if ($lb_valido)
					{
						$lb_valido=$this->uf_actualizar_estatus_personal_encargaduria($as_codper,'1');
					}	
					if ($lb_valido)
					{
						if ($as_susper=='1')
						{
							$lb_valido=$this->uf_suspender_personal_nomina($ls_codnom, $as_codper,$ad_fecinienc);
						}
						else
						{
							$lb_valido=$this->uf_activar_personal_nomina($ls_codnom, $as_codper,$ad_fecinienc);
						}
					}					
					if ($lb_valido)
					{
						$lb_valido=$this->uf_insert_encargaduria($as_codenc,$as_tipenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc, $as_codper,$as_codnom, $as_codnomenc, $as_codperenc,$as_susper,$aa_seguridad);				
					}
				}
				else  // cuando la encargaduria es dentro de la misma nómina
				{
					$lb_valido=$this->uf_actualizar_estatus_personal_encargaduria($as_codper,'1');
					if ($lb_valido)
					{
						if ($as_susper=='1')
						{
							$lb_valido=$lb_valido=$this->uf_suspender_personal_nomina($ls_codnom, $as_codper,$ad_fecinienc);
						}
						else
						{
							$lb_valido=$this->uf_activar_personal_nomina($ls_codnom, $as_codper,$ad_fecinienc);
						}
					}	
					if ($lb_valido)
					{
						$lb_valido=$this->uf_insert_encargaduria($as_codenc,$as_tipenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc, $as_codper,$as_codnom, $as_codnomenc, $as_codperenc,$as_susper,$aa_seguridad);	
					}					
										
				}
				break;

			case "TRUE": // Para actualizar la encargaduria
				$lb_valido=$this->uf_actualizar_fecha_ingreso_personal_encargado($as_codperenc,$ad_fecinienc);
				if ($lb_valido)
				{
					if ($as_susper=='1')
					{
						$lb_valido=$lb_valido=$this->uf_suspender_personal_nomina($ls_codnom, $as_codper,$ad_fecinienc);
					}
					else
					{
						$lb_valido=$this->uf_activar_personal_nomina($ls_codnom, $as_codper,$ad_fecinienc);
					}
				}	
				if ($lb_valido)
				{
					$lb_valido=$this->uf_update_encargaduria($as_codenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc,$as_susper,$aa_seguridad);				
				}
				break;
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 						
		}
		else
		{
			$this->io_sql->rollback();						
		}
		return $lb_valido;
	}// end function uf_guardar
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_load_datos_nomina_personal_encargado($as_codnomenc,$as_codperenc,&$as_coduniracenc,&$as_codsubnomenc,&$as_dessubnomenc,&$as_codasicarenc,&$as_denasicarenc,&$as_codtabenc,&$as_destabenc,&$as_codpasenc,&$as_codgraenc,&$as_codcarenc,&$as_descarenc,&$as_coduniadmenc,&$as_desuniadmenc,&$as_gradoenc,&$as_coddepenc,&$as_dendepenc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_datos_nomina_personal_encargado
		//		   Access: private
		//	    Arguments: as_codnomenc // código de nómina del personal encargado
		//                 as_codperenc // código del personal encargado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca la información de la nómina del personal encargado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT  sno_personalnomina.codsubnom, sno_personalnomina.codasicar, sno_personalnomina.codtab, ".
				"		sno_personalnomina.codgra, sno_personalnomina.codpas, sno_personalnomina.codunirac,  ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, sno_subnomina.dessubnom,sno_unidadadmin.desuniadm,".
				"		sno_personalnomina.prouniadm,  sno_personalnomina.codcar,  sno_personalnomina.coddep, ".			
				"       (SELECT srh_departamento.coddep FROM srh_departamento                 ".
				"         WHERE srh_departamento.codemp=sno_personalnomina.codemp             ".
				"           AND srh_departamento.coddep=sno_personalnomina.coddep) AS dendep, ".
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT destab FROM sno_tabulador ".
				"		   WHERE sno_tabulador.codemp = sno_personalnomina.codemp ".
				"			 AND sno_tabulador.codnom = sno_personalnomina.codnom ".
				"			 AND sno_tabulador.codtab = sno_personalnomina.codtab) as destab ".				
				"  FROM sno_personalnomina, sno_unidadadmin,sno_subnomina ".			
				" 	WHERE sno_personalnomina.codemp = '".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom = '".$as_codnomenc."' ".
				"   AND sno_personalnomina.codper =  '".$as_codperenc."' ".		
				"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".
				"	AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_load_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_coduniracenc=$row["codunirac"];
				$as_codsubnomenc=$row["codsubnom"];
				$as_dessubnomenc=$row["dessubnom"];
				$as_codasicarenc=$row["codasicar"];
				$as_denasicarenc=$row["denasicar"];
				$as_codcarenc=$row["codcar"];
				$as_descarenc=$row["descar"];
				$as_codtabenc=$row["codtab"];
				$as_destabenc=$row["destab"];
				$as_codgraenc=$row["codgra"];
				$as_codpasenc=$row["codpas"];								
				$as_coduniadmenc=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];			
				$as_desuniadmenc=$row["desuniadm"];				
				$as_coddepenc=$row["coddep"];
				$as_dendepenc=$row["dendep"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_datos_nomina_personal_encargado
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_actualizar_estatus_personal_encargaduria($as_codper,$as_estatus)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estatus_personal_encargaduria
		//		   Access: private
		//	    Arguments: as_codper // código de personal 	
		//                 as_estatus // estatus de encargaduria del personal (1 si esta en encargadurio - 0 en caso contrario)
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estatus de personal en encargaduria
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET estencper='".$as_estatus."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_estatus_personal_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_estatus_personal_encargaduria

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_actualizar_fecha_ingreso_personal_encargado($as_codper,$ad_fecinienc)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_fecha_ingreso_personal_encargado
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado	
		//                 ad_fecinienc // fecha de inicio de la encargaduria
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la fecha de ingreso a la nómina del personal encargado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET fecingper='".$ad_fecinienc."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_fecha_ingreso_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_fecha_ingreso_personal_encargado
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_suspender_personal_nomina($as_codnom, $as_codper,$ad_fecinienc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suspender_personal_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado
		//                 as_codnom // código de la nomina del personal encargado
		//                 ad_fecinienc // fecha de inicio de la encargaduria
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estatus del personal encargado en su nomina original
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
				
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET staper='4', ".
				"       fecsusper='".$ad_fecinienc."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_suspender_personal_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_suspender_personal_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personal_encargado_nomina($as_codper,$as_codperenc,$ad_fecinienc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personal_encargado_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal a quién se le va a hacer la encargaduría		
		//                 as_codperenc // código del personal encargado
		//                 ad_fecinienc // fecha de inicio de la encargaduria

		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el Personal encargado en la nomina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_personalnomina (codemp, codnom,codper, codsubnom,codasicar, codtab, codgra , codpas, ".
		        " sueper, horper, minorguniadm, ofiuniadm ,uniuniadm,depuniadm,prouniadm, codcar, fecingper,staper,codded , ".
				" codtipper, codtabvac,sueintper,  sueproper, codescdoc,codcladoc,  codubifis, grado, fecculcontr,".
				" descasicar,coddep, salnorper,fecegrper,fecsusper,pagbanper,pagefeper,estencper) ".
				" (SELECT '".$this->ls_codemp."','".$this->ls_codnom."','".$as_codperenc."',codsubnom,codasicar, codtab,codgra, ".
				" codpas,sueper, horper, minorguniadm, ofiuniadm ,uniuniadm,depuniadm,prouniadm, codcar, '".$ad_fecinienc."', ".
				" '1',codded , codtipper, codtabvac,0, 0, codescdoc,codcladoc,  codubifis, grado,'1900-01-01', ".
				" descasicar,coddep, 0,'1900-01-01','1900-01-01',0,0,'0' ".
			    " FROM sno_personalnomina ".
				" WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' )";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_sql->message;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_personal_encargado_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el  Personal Encargado ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_personal_encargado_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				

			}
		}
		return $lb_valido;
	}// end function uf_insert_personal_encargado_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_actualizar_datos_nomina_personal_encargado($as_codnomenc,$as_codperenc,$aa_seguridad)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_datos_nomina_personal_encargado
		//		   Access: private
		//	    Arguments: as_codnomenc // código de nómina original del personal encargado
		//                 as_codperenc // código del personal encargado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la información de pago del personal en la nómina nueva
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT pagbanper,codban, codcueban,tipcuebanper, cueaboper,pagefeper,pagtaqper,tipcestic,codage ".
				"  FROM sno_personalnomina ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnomenc."' ".
				"   AND codper='".$as_codperenc."' ";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			
				$ls_pagbanper=$row["pagbanper"];
				$ls_codban=$row["codban"]; 
				$ls_codcueban=$row["codcueban"];
				$ls_tipcuebanper=$row["tipcuebanper"];
				$ls_cueaboper=$row["cueaboper"];
				$ls_pagefeper=$row["pagefeper"];
				$ls_pagtaqper=$row["pagtaqper"];
				$ls_tipcestic=$row["tipcestic"];
				$ls_codage=$row["codage"];
				
				$ls_sql="UPDATE sno_personalnomina  ".
				        " SET pagbanper= '".$ls_pagbanper."', ".
						" codban= '".$ls_codban."', ".
						" codcueban= '".$ls_codcueban."', ".
						" tipcuebanper= '".$ls_tipcuebanper."', ".
						" cueaboper= '".$ls_cueaboper."', ".
						" pagefeper= '".$ls_pagefeper."', ".
						" pagtaqper= '".$ls_pagtaqper."', ".
						" tipcestic= '".$ls_tipcestic."', ".
						" codage= '".$ls_codage."' ".
						" WHERE codemp ='".$this->ls_codemp."' ".
						"   AND codnom='".$this->ls_codnom."' ".
						"   AND codper='".$as_codperenc."' ";
				$this->io_sql->begin_transaction();				
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó el  Personal Encargado ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					if(!$lb_valido)
					{	
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
								
					}
				}
			
			}// fin del while
		}// fin del else
		return $lb_valido;

}// end function uf_actualizar_datos_nomina_personal_encargado
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_conceptos_personal_encargado($as_codper,$as_codperenc,$aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos_personal_encargado
		//		   Access: private
		//	    Arguments: as_codper // código de personal a quién se le va a hacer la encargaduría		
		//                 as_codperenc // código del personal encargado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los conceptos de nomina del personal encargado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptopersonal (codemp,codnom,codper,codconc ,aplcon,valcon , acuemp,acuiniemp , ".
		        "  acupat , acuinipat)  ".
				" (SELECT '".$this->ls_codemp."','".$this->ls_codnom."','".$as_codperenc."',sno_conceptopersonal.codconc ,".
				" sno_conceptopersonal.aplcon, sno_conceptopersonal.valcon ,0,0,0,0 ".				
			    " FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp ='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codper='".$as_codper."' ".
				"   AND sno_conceptopersonal.aplcon= '1' ".
				"   AND sno_concepto.codemp = sno_conceptopersonal.codemp ".
				"   AND sno_concepto.codnom = sno_conceptopersonal.codnom ".
				"   AND sno_concepto.codconc = sno_conceptopersonal.codconc ".
				"   AND sno_concepto.conperenc = '1' )";

		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_conceptos_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó los conceptos de nómina al Personal Encargado ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_conceptos_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				

			}
		}
		return $lb_valido;
	}// end function uf_insert_conceptos_personal_encargado
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_constantes_personal_encargado($as_codper,$as_codperenc,$aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantes_personal_encargado
		//		   Access: private
		//	    Arguments: as_codper // código de personal a quién se le va a hacer la encargaduría		
		//                 as_codperenc // código del personal encargado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las constantes de nomina del personal encargado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_constantepersonal (codemp,codnom,codper,codcons,moncon,montopcon ) ".
				" (SELECT '".$this->ls_codemp."','".$this->ls_codnom."','".$as_codperenc."',sno_constantepersonal.codcons, ".
				"  sno_constantepersonal.moncon, sno_constantepersonal.montopcon ".				
			    " FROM sno_constantepersonal,sno_constante ".
				" WHERE sno_constantepersonal.codemp ='".$this->ls_codemp."' ".
				"   AND sno_constantepersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_constantepersonal.codper='".$as_codper."' ".
			    "   AND sno_constante.codemp = sno_constantepersonal.codemp ".
				"   AND sno_constante.codnom = sno_constantepersonal.codnom ".
				"   AND sno_constante.codcons= sno_constantepersonal.codcons ".
				"   AND sno_constante.conperenc = '1' )";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_constantes_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó las constantes de nómina al Personal Encargado ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_constantes_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				

			}
		}
		return $lb_valido;
	}// end function uf_insert_constantes_personal_encargado
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_delete_conceptos_encargado_nomina($as_codperenc, $aa_seguridad) 
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptos_encargado_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado	
		//                 ad_fecfinenc // fecha de finalización de la encargaduria
		//                 as_estatus // estatus del personal
		//                 aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que elimina los conceptos del personal encargado  
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="DELETE FROM sno_conceptopersonal ".								
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codperenc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_delete_conceptos_encargado_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó los conceptos de nómina al Personal Encargado ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_constantes_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				

			}
		}
		
		return $lb_valido;
}// end function uf_delete_conceptos_encargado_nomina
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_delete_constantes_encargado_nomina($as_codperenc, $aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constantes_encargado_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado	
		//                 ad_fecfinenc // fecha de finalización de la encargaduria
		//                 as_estatus // estatus del personal
		//                 aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que elimina las constantes del personal encargado  
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="DELETE FROM sno_constantepersonal ".								
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codperenc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_delete_constantes_encargado_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las constantes de nómina al Personal Encargado ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_insert_constantes_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				

			}
		}
		return $lb_valido;
}// end function uf_delete_constantes_encargado_nomina
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_chequear_personal_encargaduria(&$as_codenc,$as_codenc,$as_codper,$ad_fecinienc,$ad_fecfinenc,&$as_codnom)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_chequear_personal_encargaduria
		//		   Access: private
		//	    Arguments: as_codper // código del personal
		//                 ad_fecinienc // fecha de inicio de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que verifica que un personal no tenga una encargaduria activa.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ad_fecinienc=$this->io_funciones->uf_convertirdatetobd($ad_fecinienc);		
		if (($ad_fecfinenc!="dd/mm/aaaa")&&($ad_fecfinenc!="")&&($ad_fecfinenc!="01/01/1900"))
		{
			$ad_fecfinenc=$this->io_funciones->uf_convertirdatetobd($ad_fecfinenc);
			$ls_criterio="   AND (('".$ad_fecinienc."' BETWEEN fecinienc AND fecfinenc) OR ".
				         "         ('".$ad_fecfinenc."' BETWEEN fecinienc AND fecfinenc)) ";
		}
		else
		{
			$ls_criterio="   AND '".$ad_fecinienc."' BETWEEN fecinienc AND fecfinenc ";
		}	
		$as_codenc="";
		$as_codnom="";
		$ls_sql="  SELECT codenc, codper, fecinienc, fecfinenc, codnom ".
				"  FROM sno_encargaduria ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codenc <> '".$as_codenc."' ".		
				"   AND (codperenc='".$as_codper."' OR codper='".$as_codper."') ".
				"   AND estenc='1'   ".$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_chequear_personal_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$as_codenc=$row["codenc"];
				$as_codnom=$row["codnom"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_chequear_personal_encargaduria
//-----------------------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------------------
function uf_chequear_personal_nomina($as_codper)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_chequear_personal_nomina
		//		   Access: private
		//	    Arguments: as_codper // código del personal
		//                 ad_fecinienc // fecha de inicio de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que verifica que un personal no este activo dentro de la nomina actual
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 14/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;		
		$ls_sql="  SELECT codper ".
				"  FROM sno_personalnomina ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_chequear_personal_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_chequear_personal_nomina
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------         
function uf_update_personal_encargado_nomina($as_codper,$as_codperenc,$ad_fecinienc,$aa_seguridad)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personal_encargado_nomina
		//		   Access: private
		//	    Arguments: as_codnomenc // código de nómina original del personal encargado
		//                 as_codperenc // código del personal encargado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la información de pago del personal en la nómina nueva
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT codsubnom,codasicar, codtab, codgra , codpas, ".
		        " sueper, horper, minorguniadm, ofiuniadm ,uniuniadm,depuniadm,prouniadm, codcar, staper,codded , ".
				" codtipper, codtabvac,sueintper,  sueproper, codescdoc,codcladoc,  codubifis, grado, fecculcontr,".
				" descasicar,coddep, salnorper,fecegrper,fecsusper,pagbanper,pagefeper,estencper ".
				"  FROM sno_personalnomina ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_update_personal_encargado_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			
		  		$ls_codsubnom=$row["codsubnom"];
				$ls_codasicar=$row["codasicar"];
				$ls_codtab=$row["codtab"]; 
				$ls_codgra=$row["codgra"]; 
				$ls_codpas=$row["codpas"];
		        $ls_sueper=$row["sueper"];
				$ls_horper=$row["horper"];
				$ls_minorguniadm=$row["minorguniadm"];
				$ls_ofiuniadm=$row["ofiuniadm"];
				$ls_uniuniadm=$row["uniuniadm"];
				$ls_depuniadm=$row["depuniadm"];
				$ls_prouniadm=$row["prouniadm"];
				$ls_codcar=$row["codcar"];
				$ls_fecingper=$ad_fecinienc;
				$ls_staper='1';
				$ls_codded=$row["codded"]; 
				$ls_codtipper=$row["codtipper"]; 
				$ls_codtabvac=$row["codtabvac"]; 
				$ls_sueintper=0;   
				$ls_sueproper=0; 
				$ls_codescdoc=$row["codescdoc"];
				$ls_codcladoc=$row["codcladoc"]; 
				$ls_codubifis=$row["codubifis"]; 
				$ls_grado=$row["grado"];  
				$ls_fecculcontr='1900-01-01';
				$ls_descasicar=$row["descasicar"];
				$ls_coddep=$row["coddep"];
				$ls_salnorper=$row["salnorper"];
				$ls_fecegrper='1900-01-01';
				$ls_fecsusper='1900-01-01';
				$ls_pagbanper=$row["pagbanper"];
				$ls_pagefeper=$row["pagefeper"];
				$ls_estencper='0';		
				
				$ls_sql="UPDATE sno_personalnomina  ".
				        " SET codsubnom='".$ls_codsubnom."', ".
						"	  codasicar='".$ls_codasicar."', ".
						"	  codtab='".$ls_codtab."', ". 
						"     codgra ='".$ls_codgra."', ". 
						"     codpas='".$ls_codpas."', ". 
		                "     sueper='".$ls_sueper."', ". 
				        "     horper='".$ls_horper."', ". 
				        "     minorguniadm='".$ls_minorguniadm."', ". 
				        "     ofiuniadm ='".$ls_ofiuniadm."', ".
				        "     uniuniadm='".$ls_uniuniadm."', ".
						"	  depuniadm='".$ls_depuniadm."', ".
						"	  prouniadm='".$ls_prouniadm."', ". 
						"	  codcar='".$ls_codcar."', ". 
						"	  fecingper='".$ls_fecingper."', ".
						"	  staper='".$ls_staper."', ".
						"	  codded ='".$ls_codded."', ". 
						"	  codtipper='".$ls_codtipper."', ". 
						"	  codtabvac='".$ls_codtabvac."', ".
						"	  sueintper=".$ls_sueintper.", ".  
						"	  sueproper=".$ls_sueproper.", ". 
						"	  codescdoc='".$ls_codescdoc."', ".
						"	  codcladoc='".$ls_codcladoc."', ".  
						"	  codubifis='".$ls_codubifis."', ". 
						"	  grado='".$ls_grado."', ". 
						"	  fecculcontr='".$ls_fecculcontr."', ".
						"	  descasicar='".$ls_descasicar."', ".
						"	  coddep='".$ls_coddep."', ". 
						"	  salnorper='".$ls_salnorper."', ".
						"	  fecegrper='".$ls_fecegrper."', ".
						"	  fecsusper='".$ls_fecsusper."', ".
						"	  pagbanper='".$ls_pagbanper."', ".
						"	  pagefeper='".$ls_pagefeper."', ".
						"	  estencper='".$ls_estencper."' ".
						" WHERE codemp ='".$this->ls_codemp."' ".
						"   AND codnom='".$this->ls_codnom."' ".
						"   AND codper='".$as_codperenc."' ";
				$this->io_sql->begin_transaction();				
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó el  Personal ".$as_codperenc." asociado a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					if(!$lb_valido)
					{	
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
								
					}
				}
			
			}// fin del while
		}// fin del else
		return $lb_valido;

}// end function uf_update_personal_encargado_nomina
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------

function uf_activar_personal_nomina($as_codnom, $as_codper,$ad_fecinienc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_activar_personal_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado
		//                 as_codnom // código de la nomina del personal encargado
		//                 ad_fecinienc // fecha de inicio de la encargaduria
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estatus del personal encargado en su nomina original
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
				
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET staper='1', ".
				"       fecsusper='".$ad_fecinienc."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_activar_personal_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_activar_personal_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_calcular_dias_encargaduria($as_codenc,$as_codnomenc,&$as_dias)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_dias_encargaduria
		//		   Access: private
		//	    Arguments: as_codenc // código de la encargaduría
		//                 as_codnomenc // código de la nómina 	
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que calcula el número de días de la encargadria
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 16/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_dias=0;
		$ls_sql="  SELECT fecinienc, fecfinenc ".
				"  FROM sno_encargaduria ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codenc = '".$as_codenc."' ".		
				"   AND codnom='".$as_codnomenc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Calculo Encargaduria MÉTODO->uf_calcular_dias_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecinienc=$row["fecinienc"];
				$ld_fecfinenc=$row["fecfinenc"];
				
				if ($ld_fecfinenc=='1900-01-01')
				{
					$lb_valido=false;
				}
				else
				{
					$as_dias=$this->io_fecha->uf_restar_fechas($ld_fecinienc,$ld_fecfinenc);
					$as_dias=$as_dias+1;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
}//end function uf_calcular_dias_encargaduria
//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_calcular_diferencia_dias_encargaduria($as_codenc,$as_codnomenc,$ad_fecha,&$as_dias)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_diferencia_dias_encargaduria
		//		   Access: private
		//	    Arguments: as_codenc // código de la encargaduría
		//                 as_codnomenc // código de la nómina 
		//                 ad_fecha // fecha para comparar	
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que calcula el número de días de diferenca ente la fecha final de la encargadria
		//                 y la fecha pasada como parámetro $ad_fecha
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 16/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_dias=0;
		$ls_sql="  SELECT fecinienc, fecfinenc ".
				"  FROM sno_encargaduria ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codenc = '".$as_codenc."' ".		
				"   AND codnom='".$as_codnomenc."' ";				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Calculo Encargaduria MÉTODO->uf_calcular_diferencia_dias_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecinienc=$row["fecinienc"];
				$ld_fecfinenc=$row["fecfinenc"];
				
				if ($ld_fecfinenc=='1900-01-01')
				{
					$lb_valido=false;
				}
				else
				{					
					$as_dias=$this->io_fecha->uf_restar_fechas($ld_fecfinenc,$ad_fecha);			
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
}//end function uf_calcular_diferencia_dias_encargaduria
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_verficar_encargado($as_codper,$ad_fecdes,$ad_fechas)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verficar_encargado
		//		   Access: private
		//	    Arguments: as_codper // código del personal
		//                 ad_fecdes // fecha de inicio de la busqueda
		//                 ad_fechas // fecha de finalización de la busqueda
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que verifica que un personal haya finalizado una encargaduria en un periodo.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 17/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=0; //false;
		
		$ls_sql="  SELECT codper ".
				"  FROM sno_encargaduria ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codperenc = '".$as_codper."' ".			
				"   AND estenc='1'   ".
				"   AND fecfinenc  BETWEEN '".$ad_fecdes."' AND '".$ad_fechas."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_verficar_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=1; //true;				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_verficar_encargado
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_verficar_encargaduria($as_codper,$ad_fecdes,$ad_fechas)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verficar_encargaduria
		//		   Access: private
		//	    Arguments: as_codper // código del personal
		//                 ad_fecdes // fecha de inicio de la busqueda
		//                 ad_fechas // fecha de finalización de la busqueda
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que verifica que un personal haya finalizado una encargaduria en un periodo.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 17/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=0;// false
		
		$ls_sql="  SELECT codper ".
				"  FROM sno_encargaduria ".
			    " WHERE codemp ='".$this->ls_codemp."' ".
				"   AND codper = '".$as_codper."' ".			
				"   AND estenc='1'   ".
				"   AND fecfinenc  BETWEEN '".$ad_fecdes."' AND '".$ad_fechas."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_verficar_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
		    if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=1; // true;				
			}
			$this->io_sql->free_result($rs_data);	
		}		
		return $lb_existe;
}//end function uf_verficar_encargaduria
//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>