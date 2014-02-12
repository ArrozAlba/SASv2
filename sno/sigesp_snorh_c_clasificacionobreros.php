<?php
class sigesp_snorh_c_clasificacionobreros
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personalnomina;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_clasificacionobreros()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_clasificacionobreros
		//		   Access: public (sigesp_snorh_d_clasificacionobreros)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
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
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personalnomina=new sigesp_sno_c_personalnomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_d_clasificacionobreros
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_clasificacionobreros)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_clasificacionobrero($as_grado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_clasificacionobrero
		//		   Access: private
		//	    Arguments: as_grado  // código del grado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la clasificación de obrero está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT grado ".
				"  FROM sno_clasificacionobrero ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND grado='".$as_grado."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_select_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_clasificacionobrero
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_clasificacionobrero($as_grado,$ai_suemin,$ai_suemax,$as_tipcla,$as_obscla,$as_anovig,$as_nrogac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasificacionobrero
		//		   Access: private
		//	    Arguments: as_grado  // Grado de la clasificación
		//				   ai_suemin  // Sueldo Mínimo
		//				   ai_suemax	// Sueldo Maximo
		//				   as_tipcla	// Tipo de clasificación
		//				   as_obscla	// Observación
		//				   as_anovig	// Año en vigencia
		//				   as_nrogac	// Número de Gaceta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_clasificacionobrero
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_clasificacionobrero(codemp,grado,suemin,suemax,tipcla,obscla,anovig,nrogac) ".
				" VALUES('".$this->ls_codemp."','".$as_grado."',".$ai_suemin.",".$ai_suemax.",'".$as_tipcla."','".$as_obscla."',".
				"'".$as_anovig."','".$as_nrogac."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_insert_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Clasificación de Obrero ".$as_grado;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Clasificación Obrero fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_insert_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_clasificacionobrero
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_clasificacionobrero($as_grado,$ai_suemin,$ai_suemax,$as_tipcla,$as_obscla,$as_anovig,$as_nrogac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_clasificacionobrero
		//		   Access: private
		//	    Arguments: as_grado  // Grado de la clasificación
		//				   ai_suemin  // Sueldo Mínimo
		//				   ai_suemax	// Sueldo Maximo
		//				   as_tipcla	// Tipo de clasificación
		//				   as_obscla	// Observación
		//				   as_anovig	// Año en vigencia
		//				   as_nrogac	// Número de Gaceta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_clasificacionobrero
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=$this->uf_buscar_nominas($rs_data);
		$ls_cadena="No se puede Modificar la Clasificación de Obreros, la(s) Nómina(s) ";
		$li_numrowtot=$this->io_sql->num_rows($rs_data);
		if (($lb_valido)&&($li_numrowtot>0))
		{
			while($row_n=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row_n["codnom"];
				$ls_desnom=trim($row_n["desnom"]);
				$ls_cadena=$ls_cadena.$ls_codnom."-".$ls_desnom." ";				
			}
			$ls_cadena=$ls_cadena." esta procesada(s) ";
			$this->io_mensajes->message($ls_cadena.$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_sql="UPDATE sno_clasificacionobrero ".
					"   SET suemin=".$ai_suemin.", ".
					"		suemax=".$ai_suemax.", ".
					"		tipcla='".$as_tipcla."', ".
					"		obscla='".$as_obscla."', ".
					"		anovig='".$as_anovig."', ".
					"		nrogac='".$as_nrogac."' ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND grado='".$as_grado."'";
				
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_update_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				////////////////////////////////         SEGURIDAD               //////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Clasificación de Obrero ".$as_grado;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Clasificación Obrero fue Actualizada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_update_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_update_clasificacionobrero	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_grado,$ai_suemin,$ai_suemax,$as_tipcla,$as_obscla,$as_anovig,$as_nrogac,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_clasificacionobreros)
		//	    Arguments: as_grado  // Grado de la clasificación
		//				   ai_suemin  // Sueldo Mínimo
		//				   ai_suemax	// Sueldo Maximo
		//				   as_tipcla	// Tipo de clasificación
		//				   as_obscla	// Observación
		//				   as_anovig	// Año en vigencia
		//				   as_nrogac	// Número de Gaceta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_clasificacionobrero
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_suemin=str_replace(".","",$ai_suemin);
		$ai_suemin=str_replace(",",".",$ai_suemin);
		$ai_suemax=str_replace(".","",$ai_suemax);
		$ai_suemax=str_replace(",",".",$ai_suemax);
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_clasificacionobrero($as_grado)))
				{
					$lb_valido=$this->uf_insert_clasificacionobrero($as_grado,$ai_suemin,$ai_suemax,$as_tipcla,$as_obscla,$as_anovig,$as_nrogac,
																	$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Clasificación Obrero ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_clasificacionobrero($as_grado)))
				{
					$lb_valido=$this->uf_update_clasificacionobrero($as_grado,$ai_suemin,$ai_suemax,$as_tipcla,$as_obscla,$as_anovig,$as_nrogac,
																	$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Clasificación Obrero no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_clasificacionobrero($as_grado,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_clasificacionobrero
		//		   Access: public (sigesp_snorh_d_clasificacionobreros)
		//	    Arguments: as_grado  // código de la escala Docente
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_clasificacionobrero
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=true;
		$lb_valido=$this->uf_buscar_nominas($rs_data);
		$ls_cadena="No se puede Eliminar la Clasificación de Obreros, la(s) Nómina(s) ";
		$li_numrowtot=$this->io_sql->num_rows($rs_data);
		if (($lb_valido)&&($li_numrowtot>0))
		{
			while($row_n=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row_n["codnom"];
				$ls_desnom=trim($row_n["desnom"]);
				$ls_cadena=$ls_cadena.$ls_codnom."-".$ls_desnom." ";				
			}
			$ls_cadena=$ls_cadena." esta procesada(s) ";
			$this->io_mensajes->message($ls_cadena.$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
        	if ($this->io_personalnomina->uf_select_personalnomina("grado",$as_grado,"0")===false)
			{
				$ls_sql="DELETE FROM sno_clasificacionobrero ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND grado='".$as_grado."'";
					
				$this->io_sql->begin_transaction();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_delete_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la Clasificación de Obrero ".$as_grado;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($lb_valido)
					{	
						$this->io_mensajes->message("La Clasificación Obrero fue Eliminada.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Clasificación Obrero MÉTODO->uf_delete_clasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$this->io_sql->rollback();
					}
				}
			} 
			else
			{
				$this->io_mensajes->message("No se puede eliminar la Clasificación Obrero. Hay personal relacionado con esta.");
				$lb_valido=false;
			}
		}       
		return $lb_valido;
    }// end function uf_delete_clasificacionobrero	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_nominas(&$rs_data)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_nominas
		//		   Access: public ()
		//	    Arguments: $rs_data  //				   	
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que busca las nomina tipo obreros fijos o contratados que esten calculadas y periodo abierto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 09/06/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT  DISTINCT (sno_salida.codnom) AS codnom, sno_nomina.desnom ".
                "   FROM sno_salida													".
                "   JOIN sno_nomina ON (sno_nomina.codemp=sno_salida.codemp			".
                "    AND sno_nomina.codnom=sno_salida.codnom)						".
                "  WHERE sno_nomina.tipnom BETWEEN '3' AND '4'                      ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Clasificación Obreros MÉTODO->uf_buscar_nominas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}		
		return $lb_valido;
	}// end function uf_buscar_nominas
	//------------------------------------------------------------------------------------------------------------------------------------
}
?>