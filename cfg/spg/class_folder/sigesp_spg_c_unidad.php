<?php

class sigesp_spg_c_unidad
{
	 var $io_sql;
	 var $siginc;
	 var $datemp;
	 var $is_msg_error;
	 var $io_seguridad;
	 var $is_empresa;
	 var $is_sistema;
	 var $is_logusr;
	 var $is_ventanas;
	function sigesp_spg_c_unidad($arr_seguridad)
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		$this->siginc=new sigesp_include();
		$con=$this->siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->datemp=$_SESSION["la_empresa"];
		$this->is_empresa = $arr_seguridad[1];
		$this->is_sistema = $arr_seguridad[2];
		$this->is_logusr  = $arr_seguridad[3];	
		$this->is_ventana = $arr_seguridad[4];
		$this->io_seguridad= new sigesp_c_seguridad();	
	}

		
function uf_select_unidad($as_codemp,$ls_codunieje)
{
  $ls_sql    = "SELECT * FROM spg_unidadadministrativa WHERE codemp='".$as_codemp."' AND coduniadm='".$ls_codunieje."'";
 
  $rs_unidad = $this->io_sql->select($ls_sql);
  if ($row=$this->io_sql->fetch_row($rs_unidad))
	 {
	   $lb_valido=true;
	 }
  else
	 {
	   $lb_valido=false;
	 }
  return $lb_valido;
}
	
	function uf_guardar_unidad_adm($as_codemp,$as_coduniadm,$as_denuniadm,$as_estreq,$as_coduniadmsig,$ls_status)
	{
		$lb_valido=$this->uf_select_unidad($as_codemp,$as_coduniadm);
		if(!$lb_valido)
		{
			$ls_cadena= " INSERT INTO spg_unidadadministrativa(codemp,coduniadm,denuniadm,estemireq,coduniadmsig) VALUES('".$as_codemp."','".$as_coduniadm."','".$as_denuniadm."',".$as_estreq.",'".$as_coduniadmsig."') ";
			$this->is_msg_error="Registro Incluido !!!";		
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la unidad administrativa con el codigo ".$as_coduniadm." asociado a la estructura programatica ";		
		}
		else
		{
			
			if($ls_status=='C')
			{
				$ls_cadena= " UPDATE spg_unidadadministrativa SET denuniadm='".$as_denuniadm."',estemireq=".$as_estreq.",coduniadmsig='".$as_coduniadmsig."' WHERE codemp='".$as_codemp."' AND coduniadm='".$as_coduniadm."'";
		
				$this->is_msg_error="Registro Actualizado !!!";
				$ls_evento="UPDATE";
				$ls_descripcion="Actualizo la unidad administrativa con el codigo ".$as_coduniadm." asociado a la estructura programatica ";
			}
			else
			{
				$this->is_msg_error="Registro ya existe introduzca un nuevo codigo !!!";
				return false;
			}
		}
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if($li_numrows===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_insert_unidad_adm".$this->io_sql->message;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			//$this->io_sql->commit();
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);

		}
		return $lb_valido;
	}


	function uf_delete_unidad_adm($ls_codigo,$ls_denominacion,$ls_estreq,$ls_status)
	{
		$ls_codemp = $this->datemp["codemp"];
		$lb_valido = $this->uf_select_unidad($ls_codemp,$ls_codigo);
		$lb_tiene  = $this->uf_check_relaciones($ls_codemp,$ls_codigo);
		if(($lb_valido) && (!$lb_tiene))
		{
			$ls_cadena= " DELETE FROM spg_unidadadministrativa WHERE codemp='".$ls_codemp."' AND coduniadm='".$ls_codigo."'";
			$ls_cadena2=" DELETE FROM spg_dt_unidadadministrativa WHERE  codemp='".$ls_codemp."' AND coduniadm='".$ls_codigo."'";
			
			$this->is_msg_error="Registro Eliminado !!!";		
			$this->io_sql->begin_transaction();
			
			$li_numrows=$this->io_sql->execute($ls_cadena2);
			if ($li_numrows===false)
			   {
				 $lb_valido=false;
				 $this->io_sql->rollback();
				 $this->is_msg_error="Error en metodo uf_delete_unidad_adm".$this->io_sql->message;
			   }
			else
			   {
					$li_numrows=$this->io_sql->execute($ls_cadena);
					if ($li_numrows===false)
				    {
						 $lb_valido=false;
						 $this->io_sql->rollback();
						 $this->is_msg_error="Error en metodo uf_delete_unidad_adm".$this->io_sql->message;
				    }else
					{
					
						$lb_valido=true;
						$this->io_sql->commit();
						$ls_evento="DELETE";
						$ls_descripcion="Elimino la unidad administrativa codigo ".$ls_codigo;
						$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
					}
				}
		}
		return $lb_valido;
	}

  function uf_check_relaciones($as_codemp,$as_codunieje)
  {
	$lb_tiene = true;
	$ls_sql   = "SELECT coduniadm FROM sep_solicitud WHERE codemp='".$as_codemp."' AND coduniadm='".$as_codunieje."'";
    $rs_data  = $this->io_sql->select($ls_sql);
    if ($rs_data===false)
	   {
 	     $lb_valido=false;
 	     $this->io_msg->message("CLASE->SIGESP_SPG_C_UNIDAD; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
	     if ($li_numrows>0)
		    {
		      $lb_tiene  = true;
			  $lb_valido = true;
		   	  $this->is_msg_error="La Unidad Ejecutora no puede ser eliminada, posee registros asociados a otras tablas !!!";
			}
	     else
		    {
			  $ls_sql = "SELECT coduniadm FROM soc_ordencompra WHERE codemp='".$as_codemp."' AND coduniadm ='".$as_codunieje."'"; 
			  $rs_data  = $this->io_sql->select($ls_sql);
              if ($rs_data===false)
	             {
 	               $lb_valido=false;
 	               $this->io_msg->message("CLASE->SIGESP_SPG_C_UNIDAD; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	             }
              else
	             {
				   $li_numrows=$this->io_sql->num_rows($rs_data);
				   if ($li_numrows>0)
					  {  
					    $lb_tiene  = true;
					    $lb_valido = true;
					    $this->is_msg_error="La Unidad Ejecutora no puede ser eliminada, posee registros asociados a otras tablas !!!";
					  }
				   else
					  {
					    $ls_sql  = "SELECT coduniadm FROM saf_dta WHERE codemp='".$as_codemp."' AND coduniadm ='".$as_codunieje."'"; 
					    $rs_data = $this->io_sql->select($ls_sql);
					    if ($rs_data===false)
						   {
							 $lb_valido=false;
						     $this->io_msg->message("CLASE->SIGESP_SPG_C_UNIDAD; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						   }
					    else
						   {
						     $li_numrows=$this->io_sql->num_rows($rs_data);
						     if ($li_numrows>0)
							    {  
					              $lb_tiene  = true;
					              $lb_valido = true;
					              $this->is_msg_error="La Unidad Ejecutora no puede ser eliminada, posee registros asociados a otras tablas !!!";
					            }
				             else
					            {
                                  $lb_tiene = false;
					            }
				           }
	                  }
                 }
		    }	 
       }
  return $lb_tiene;
  }

function uf_load_nombre_estructura($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$ai_nivel)
{
  $ls_str_aux   = "";
  $ls_nomestpro = "";
  switch ($ai_nivel) {
   case 1:
       $ls_campo = 'denestpro1';
	   $ls_tabla = 'spg_ep1';
	   $ls_str_aux = " AND codestpro1='".$as_codestpro1."' AND estcla='".$as_estcla."'";
       break;
   case 2:
       $ls_campo = 'denestpro2';
	   $ls_tabla = 'spg_ep2';
	   $ls_str_aux = " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND estcla='".$as_estcla."'";
       break;
   case 3:
       $ls_campo = 'denestpro3';
	   $ls_tabla = 'spg_ep3';
	   $ls_str_aux = " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND estcla='".$as_estcla."'";
       break;
   case 4:
       $ls_campo = 'denestpro4';
	   $ls_tabla = 'spg_ep4';
	   $ls_str_aux = " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND estcla='".$as_estcla."'";
       break;
   case 5:
       $ls_campo = 'denestpro5';
	   $ls_tabla = 'spg_ep5';
	   $ls_str_aux = " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."' AND estcla='".$as_estcla."'";
       break;
   }
  $ls_sql  = "SELECT $ls_campo AS denestpro FROM $ls_tabla WHERE codemp='".$as_codemp."' $ls_str_aux"; 
  $rs_data = $this->io_sql->select($ls_sql);
  if ($row=$this->io_sql->fetch_row($rs_data))
	 {
       $ls_nomestpro = $row["denestpro"];
	   $this->io_sql->free_result($rs_data);
	 }
return $ls_nomestpro;
} 

	
//------------------------------------------------------------------------------------------------------------------------------------------
	 function uf_guardar_dt_unidad_adm($as_codemp,$ls_codunieje,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_status)
	{
		$lb_valido=$this->uf_select_unidad($as_codemp,$ls_codunieje);
		if($lb_valido)
		{
			$ls_cadena= " INSERT INTO spg_dt_unidadadministrativa(codemp,coduniadm,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) VALUES('".$as_codemp."','".$ls_codunieje."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."') ";
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la unidad administrativa con el codigo ".$ls_codunieje." asociado a la estructura programatica ";
		}
		else
		{
			if($ls_status=='C')
			{
				$ls_cadena= "INSERT INTO spg_dt_unidadadministrativa(codemp,coduniadm,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) VALUES('".$as_codemp."','".$ls_codunieje."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";
				$ls_evento="UPDATE";
				$ls_descripcion="Actualizo la unidad administrativa con el codigo ".$ls_codigo." asociado a la estructura programatica ";
			}
			else
			{
				$this->is_msg_error="Registro ya existe introduzca un nuevo codigo !!!";
				return false;
			}
		}
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if($li_numrows===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_guardar_dt_unidad_adm".$this->io_sql->message;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);

		}
		return $lb_valido;
	}
	
	
//------------------------------------------------------------------------------------------------------------------------------------------
	 function uf_delete_dt_unidad_adm($as_codemp,$ls_codunieje)
	{
		$lb_valido=$this->uf_select_unidad($as_codemp,$ls_codunieje);
		if($lb_valido)
		{
			$ls_cadena= " DELETE FROM spg_dt_unidadadministrativa WHERE codemp='".$as_codemp."' AND coduniadm='".$ls_codunieje."'";
		}
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if($li_numrows===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_delete_dt_unidad_adm".$this->io_sql->message;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;
	}// fin uf_delete_dt_unidad_adm


	
}
?>