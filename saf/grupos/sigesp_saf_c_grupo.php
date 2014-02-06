<?
include("class_folder\class_sql.php");
//require_once("class_folder\class_datastore.php");
include("class_folder\class_funciones.php");
require_once("sigesp_include.php");
class sigesp_saf_c_grupo
{
	var $SQL;
	var $is_msg_error;
	var $datemp;
	var $con;
	var $siginc;	
	function sigesp_saf_c_grupo()
	{
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($this->con);
		$this->datemp=$_SESSION["la_empresa"];		
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_saf_select_grupo: función que devuelve si existe el codigo dentro de la tabla   ////////////////////////// 
///////  Parametros de Entrada: Codigo de entrada   										 	///////////////////////////////////////// 
///////  Parametros de Salida: Booleano indicando si fue encontrado o no en codigo				///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_saf_select_grupo($as_codigo)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM saf_grupo  ".
					" WHERE CodGru='".$as_codigo."'" ;
		
			
		$li_exec=$this->SQL->select($ls_sql);

		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($li_exec))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
		}
			
		$this->SQL->free_result($li_exec);
		return $lb_valido;

	}//fin de la function uf_saf_select_grupo()
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_sss_insert_grupo:función que inserta un registro en dentro de la tabla saf_causas     ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_saf_insert_grupo($as_codigo,$as_denominacion)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO saf_grupo (CodGru, DenGru) VALUES( '".$as_codigo."','".$as_denominacion."')" ;
		
			
			$li_exec=$this->SQL->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
			$this->SQL->rollback();

		}
		else
		{
				$lb_valido=true;
				$this->SQL->commit();
		}
		
		return $lb_valido;

	}//fin de la uf_saf_insert_grupo
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_sss_update_grupo:función que inserta un registro en dentro de la tabla saf_causas     ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_update_grupo($as_codigo,$as_denominacion) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE saf_grupo SET   DenGru='". $as_denominacion ."' WHERE CodGru='".$as_codigo."' ";

        $this->SQL->begin_transaction();
		$li_exec = $this->SQL->execute($ls_sql);
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
			$this->SQL->rollback();

		}
		else
		{
			$lb_valido=true;
			$this->SQL->commit();
		}

 
	  return $lb_valido;

	}// fin de la function uf_sss_update_movimientos
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_saf_check_relaciones:función que revisa si existen datos relacionados en una tabla hijo   ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_check_relaciones($as_tabla,$as_campo,$as_where)
	{
		$ls_cadena="SELECT ".$as_campo." FROM ".$as_tabla." WHERE ".$as_where;
		
		$lr_return=$this->SQL->select($ls_cadena);
		print $ls_cadena;
		if(($lr_return==false)&&($this->SQL->message!=""))
		{
				$lb_valido=false;
				$this->is_msg_error="No existe el codigo ".$fun->uf_convertirmsg($this->SQL->message);		
				print $this->is_msg_error;
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
				print $row["CodEstPro1"];
			}	
			else
			{
				$lb_valido=false;
			}
			$this->SQL->free_result($lr_return);
		}	
		
	return $lb_valido;
	
	
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_saf_delete_grupo:elimina un registro de la tabla saf_grupo		   ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	function uf_saf_delete_grupo($as_codigo)
	{
		$ls_codemp=$this->datemp["CodEmp"];
		$fun=new class_funciones();
		$lb_existe=$this->uf_saf_select_grupo($as_codigo);
		$lb_existe_relacion=$this->uf_saf_select_subgrupo($as_codigo,"");
		//$lb_relacion_otros=$this->uf_spg_check_relaciones("saf_grupo","*"," CodGru='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."'");
		$this->SQL->begin_transaction();
		if(($lb_existe)&&(!$lb_existe_relacion))
		{
			$ls_cadena=" DELETE FROM saf_grupo WHERE  Codgru='".$as_codigo."' ";
			$li_return=$this->SQL->execute($ls_cadena);

			if($li_return==false)
			{
				print $this->SQL->message;
				$lb_valido=false;
				$this->SQL->rollback();
	
			}
			else
			{
				$lb_valido=true;
				$this->SQL->commit();
			}
		}
		else
		{
			$lb_valido=false;
			$this->is_msg_error="Codigo no existe o posee relaciones";
		}
		
		return $lb_valido;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_saf_select_subgrupo:función que devuelve si existe el codigo dentro de la tabla             ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_select_subgrupo($as_codigo,$as_codsubgru)
	{
		$fun=new class_funciones();		
		$ls_cadena="SELECT * FROM saf_subgrupo WHERE CodGru='".$as_codigo."' AND CodSubGru like '%".$as_codsubgru."%'";
		
		$lr_return=$this->SQL->select($ls_cadena);
		if(($lr_return==false)&&($this->SQL->message!=""))				
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta ".$fun->uf_convertirmsg($this->SQL->message);		
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
			}	
			else
			{
				$lb_valido=false;
			}
			$this->SQL->free_result($lr_return);
		}
	return $lb_valido;
	}
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_sss_insert_subgrupo:función que inserta un registro en dentro de la tabla saf_subgrupo     ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_saf_insert_subgrupo($as_codgru,$as_codsubgru,$as_densubgru)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO saf_subgrupo (CodGru,CodSubGru, DenSubGru) VALUES( '".$as_codgru."','".$as_codsubgru."','".$as_densubgru."')" ;
		
			
			$li_exec=$this->SQL->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
			$this->SQL->rollback();

		}
		else
		{
				$lb_valido=true;
				$this->SQL->commit();
		}
		
		return $lb_valido;

	}//fin de la uf_saf_insert_grupo
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_sss_update_grupo:función que inserta un registro en dentro de la tabla saf_subgrupo     ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_update_subgrupo($as_codgru,$as_codsubgru,$as_denominacion) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE saf_subgrupo SET   DenSubGru='". $as_denominacion ."' WHERE CodGru='".$as_codgru."' AND CodSubGru='".$as_codsubgru."'";

        $this->SQL->begin_transaction();
		$li_exec = $this->SQL->execute($ls_sql);
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
			$this->SQL->rollback();

		}
		else
		{
			$lb_valido=true;
			$this->SQL->commit();
		}

 
	  return $lb_valido;

	}// fin de la function uf_sss_update_movimientos
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_saf_delete_subgrupo:elimina un registro de la tabla saf_subgrupo		   ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	function uf_saf_delete_subgrupo($as_codgru,$as_codsubgru)
	{
		$ls_codemp=$this->datemp["CodEmp"];
		$fun=new class_funciones();
		$lb_existe=$this->uf_saf_select_subgrupo($as_codgru,$as_codsubgru);
		$lb_existe_relacion=$this->uf_saf_select_seccion($as_codgru,$as_codsubgru,"");
		//$lb_relacion_otros=$this->uf_spg_check_relaciones("saf_grupo","*"," CodGru='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."'");
		$this->SQL->begin_transaction();
		if(($lb_existe)&&(!$lb_existe_relacion))
		{
			$ls_cadena=" DELETE FROM saf_subgrupo WHERE  Codgru='".$as_codgru."' AND CodSubGru='".$as_codsubgru."' ";
			$li_return=$this->SQL->execute($ls_cadena);

			if($li_return==false)
			{
				print $this->SQL->message;
				$lb_valido=false;
				$this->SQL->rollback();
	
			}
			else
			{
				$lb_valido=true;
				$this->SQL->commit();
			}
		}
		else
		{
			$lb_valido=false;
			$this->is_msg_error="Codigo no existe o posee relaciones";
		}
		
		return $lb_valido;
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////  Inicio function uf_saf_select_seccion:función que devuelve si existe el codigo dentro de la tabla             ///////////////// 
///////  Parametros de Entrada: Codigo de entrada,denominacion      			     		 	///////////////////////////////////////// 
///////  Parametros de Salida: Codigo de entrada    											///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_select_seccion($as_codgru,$as_codsubgru,$as_codsec)
	{
		$fun=new class_funciones();		
		$ls_cadena="SELECT * FROM saf_seccion WHERE CodGru='".$as_codgru."' AND CodSubGru ='".$as_codsubgru."' AND CodSec like '%".$as_codsec."%'";
		
		$lr_return=$this->SQL->select($ls_cadena);
		if($lr_return==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
		}
			
		//$this->SQL->free_result($lr_return);
		return $lb_valido;
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function uf_spg_insert_estprog2($as_codestprog1,$as_codestprog2,$as_denestprog2)
	{
	//Metodo de inserción del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$ls_codemp=$this->datemp["CodEmp"];
		$fun=new class_funciones();
		$lb_existe=$this->uf_spg_select_estprog2($as_codestprog1,$as_codestprog2);
		$this->SQL->begin_transaction();
		if(!$lb_existe)
		{
			$ls_cadena=" INSERT INTO spg_ep2(CodEmp,CodEstPro1,CodEstPro2,DenEstPro2) values('".$ls_codemp."','".$as_codestprog1."','".$as_codestprog2."','".$as_denestprog2."') ";
			$this->is_msg_error="Registro Incluido";

		}
		else
		{
			$ls_cadena=" UPDATE spg_ep2 SET DenEstPro2='".$as_denestprog2."' WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."'";
			$this->is_msg_error="Registro Actualizado";
		}
			$li_return=$this->SQL->execute($ls_cadena);
			if(($li_return==false)&&($this->SQL->message!=""))
			{
				$lb_valido=false;
				$this->SQL->rollback();
				$this->is_msg_error="Error al guardar Codigo programatico nivel".$fun->uf_convertirmsg($this->SQL->message);
				
			}
			else
			{
				if($li_return>0)
				{
					$lb_valido=true;
					$lb_valido=$this->uf_spg_insert_estprog3($as_codestprog1,$as_codestprog2,'000','Ninguno');
					
					if($lb_valido)
					{
						$this->SQL->commit();			
						$lb_valido=true;	
					}
					else
					{
						$lb_valido=false;
						$this->SQL->rollback();
						$this->is_msg_error="Error al guardar Codigo programatico nivel adicionales";
					}

				}

			}
	}
	
		
	function uf_spg_delete_estprog2($as_codestprog1,$as_codestprog2)
	{
	//Metodo de inserción del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$ls_codemp=$this->datemp["CodEmp"];
		$fun=new class_funciones();
		$lb_existe=$this->uf_spg_select_estprog2($as_codestprog1,$as_codestprog2);
		$lb_existe_relacion=$this->uf_spg_select_estprog3($as_codestprog1,$as_codestprog2,"");
		$lb_relacion_otros=$this->uf_spg_check_relaciones("spg_unidadadministrativa","*"," CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."'");
		$this->SQL->begin_transaction();
		//$lb_valido=$this->uf_delete_niveles_adicionales($as_codestprog1,$as_codestprog2);
		if(($lb_existe)&&(!$lb_existe_relacion)&&(!$lb_relacion_otros))
		{
			$ls_cadena=" DELETE FROM spg_ep2 WHERE  CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."'";
			$this->is_msg_error="Registro Eliminado";
			$li_return=$this->SQL->execute($ls_cadena);
			if(($li_return==false)&&($this->SQL->message!=""))				
			{
				$lb_valido=false;
				$this->is_msg_error="Error Eliminado".$fun->uf_convertirmsg($this->SQL->message);
			}
			else
			{
			
				if($li_return>0)
				{
					$lb_valido=true;
					$this->SQL->commit();				
				}	
				else
				{
					$lb_valido=true;
					$this->SQL->commit();
					$this->is_msg_error="No se eliminaron registros";
				}
			}
		}
		else
		{
			$lb_valido=false;
			$this->is_msg_error="Codigo no existe o posee relaciones";
		}
		
			
	}

	function uf_spg_delete_estprog3($as_codestprog1,$as_codestprog2,$as_codestprog3)
	{
	//Metodo de inserción del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$ls_codemp=$this->datemp["CodEmp"];
		$lb_valido=false;
		$lb_existe=$this->uf_spg_select_estprog3($as_codestprog1,$as_codestprog2,$as_codestprog3);
		$lb_relacion_otros=$this->uf_spg_check_relaciones("spg_unidadadministrativa","*"," CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."'" );
		$this->SQL->begin_transaction();
		if(!$lb_relacion_otros)
		{
			$lb_valido=$this->uf_delete_niveles_adicionales($as_codestprog1,$as_codestprog2,$as_codestprog3);
		}
		if(($lb_valido)&&($lb_existe)&&(!$lb_relacion_otros))
		{
			$ls_cadena=" DELETE FROM spg_ep3 WHERE  CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."'";
			$this->is_msg_error="Registro Eliminado";
			$li_return=$this->SQL->execute($ls_cadena);
			if(($li_return==false)&&($this->SQL->message!=""))
			{
				$lb_valido=false;
				$this->is_msg_error="Error Eliminando ".$fun->uf_convertirmsg($this->SQL->message);
			}
			else
			{
				if($li_return>0)
				{
					$lb_valido=true;
					$this->is_msg_error="Rergistro eliminado";
					$this->SQL->commit();				
				}	
				else
				{
					$lb_valido=true;
					$this->SQL->commit();
					$this->is_msg_error="NO se eliminaron registros";
				}
			}
		}
		
			
	}


	function uf_insert_niveles_adicionales($as_codestprog1,$as_codestprog2,$as_codestprog3)
	{
		$lb_valido=false;
		
		$as_codestprog4="00";
		$as_codestprog5="00";
		$as_denestadicionales="Ninguno";
			
				$lb_valido=$this->uf_spg_insert_estprog4($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_denestadicionales);
				if($lb_valido)
				{
					$lb_valido=$this->uf_spg_insert_estprog5($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_codestprog5,$as_denestadicionales);
				}
	return $lb_valido;
	}
	
	function uf_delete_niveles_adicionales($as_codestprog1,$as_codestprog2,$as_codestprog3)
	{
		$lb_valido=false;
		$ls_codemp=$this->datemp["CodEmp"];
		$lb_existe=$this->uf_spg_select_estprog3($as_codestprog1,$as_codestprog2,$as_codestprog3);
//		$this->SQL->begin_transaction();
		if($lb_existe)
		{
			
			$lb_valido=$this->uf_verificar_movimientos($as_codestprog1,$as_codestprog2,$as_codestprog3);
			$lb_relacion_otros=$this->uf_spg_check_relaciones("spg_unidadadministrativa","*"," CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='00' AND CodEstPro5='00'" );
			if((!$lb_valido)&&(!$lb_relacion_otros))
			{
				$ls_cadena=" DELETE FROM spg_ep5 WHERE  CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='00' AND CodEstPro5='00'";
				$this->is_msg_error="Registro Eliminado";
			
				$li_return=$this->SQL->execute($ls_cadena);
				$lb_relacion_otros=$this->uf_spg_check_relaciones("spg_unidadadministrativa","*"," CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='00' " );
				if(($li_return==false)&&($this->SQL->message!=""))
				{
					$lb_valido=false;
					$this->is_msg_error="Error Eliminando ".$fun->uf_convertirmsg($this->SQL->message);
				}
				else
				{
						if(!$lb_relacion_otros)
						{				
							$ls_cadena=" DELETE FROM spg_ep4 WHERE  CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='00'";
							$li_return=$this->SQL->execute($ls_cadena);
							if(($li_return==false)&&($this->SQL->message!=""))
							{
								$lb_valido=false;
								$this->is_msg_error="Error Eliminando ".$fun->uf_convertirmsg($this->SQL->message);
							}
							else
							{
								$lb_valido=true;						
							}
						}
				}	
				
			}
			else
			{
				$lb_valido=false;
				$this->is_msg_error="Error al eliminar Codigo programatico, hay movimientos relacionados ";
			
			}
		}
		return $lb_valido;
	}
	
	function uf_verificar_movimientos($as_codestprog1,$as_codestprog2,$as_codestprog3)
	{
		$ls_codemp=$this->datemp["CodEmp"];
		
		$ls_cadena="SELECT * FROM spg_dt_cmp WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3=".$as_codestprog3."' AND CodEstPro4='00' AND CodEstPro5='00'";
		
		$lr_return=$this->SQL->select($ls_cadena);
		if(($lr_return==false)&($this->SQL->message!=""))
		{
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
				$this->is_msg_error="El codigo programatico posee movimientos relacionados";
			}	
			else
			{
				$lb_valido=false;
			}
			$this->SQL->free_result($lr_return);
		}
		return $lb_valido;
	}
	
	function uf_spg_select_estprog3($as_codestprog1,$as_codestprog2,$as_codestprog3)
	{
	//Metodo de seleccion del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$fun=new class_funciones();
		$ls_codemp=$this->datemp["CodEmp"];
		
		$ls_cadena="SELECT * FROM spg_ep3 WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3 like '%".$as_codestprog3."%'";
		$lr_return=$this->SQL->select($ls_cadena);
		if(($lr_return==false)&&($this->SQL->message!=""))
		{
			$lb_valido=false;
			$this->is_msg_error="Error en Consulta".$fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
			}	
			else
			{
				$lb_valido=false;
				$this->is_msg_error="No existe el codigo programatico";
			}
			$this->SQL->free_result($lr_return);
		}
		return $lb_valido;
	}
	
	function uf_spg_insert_estprog3($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_denestprog3)
	{
	//Metodo de inserción del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$fun=new class_funciones();
		$lb_valido=false;
		$ls_codemp=$this->datemp["CodEmp"];
		$lb_existe=$this->uf_spg_select_estprog3($as_codestprog1,$as_codestprog2,$as_codestprog3);
		
//		$this->SQL->begin_transaction();
		if(!$lb_existe)
		{
			$ls_cadena=" INSERT INTO spg_ep3(CodEmp,CodEstPro1,CodEstPro2,CodEstPro3,DenEstPro3) values('".$ls_codemp."','".$as_codestprog1."','".$as_codestprog2."','".$as_codestprog3."','".$as_denestprog3."') ";
			$this->is_msg_error="Registro Incluido";
		}
		else
		{
			$ls_cadena=" UPDATE spg_ep3 SET DenEstPro3='".$as_denestprog3."' WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."'";
			$this->is_msg_error="Registro Actualizado";
		}
			$li_return=$this->SQL->execute($ls_cadena);
			if(($li_return==false)&&($this->SQL->message!=""))
			{
				$lb_valido=false;
				$this->is_msg_error="Error en Guardar ".$fun->uf_convertirmsg($this->SQL->message);
			}
			else
			{
				
					$lb_valido=true;
					
					if(!$lb_existe)
					{
						$lb_valido=$this->uf_insert_niveles_adicionales($as_codestprog1,$as_codestprog2,$as_codestprog3);
						if($lb_valido)
						{
							$this->SQL->commit();			
							$lb_valido=true;	
						}
						else
						{
							$lb_valido=false;
							$this->SQL->rollback();
							$this->is_msg_error="Error al guardar Codigo programatico nivel 3";
						}			
					}					
			}
			
			return $lb_valido;
	}
	

	function uf_spg_select_estprog4($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4)
	{
	//Metodo de seleccion del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$ls_codemp=$this->datemp["CodEmp"];
		
		$ls_cadena="SELECT * FROM spg_ep4 WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4 like '%".$as_codestprog4."%'";
		
		$lr_return=$this->SQL->select($ls_cadena);
		if(($lr_return==false)&&($this->SQL->message!=""))
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta ".$fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
			}	
			else
			{
				$lb_valido=false;
				$this->is_msg_error="No existe el codigo programatico";
			}
			$this->SQL->free_result($lr_return);			
		}
	return $lb_valido;
	}
	
	function uf_spg_insert_estprog4($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_denestprog4)
	{
	//Metodo de inserción del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$ls_codemp=$this->datemp["CodEmp"];
		$fun=new class_funciones();
		$lb_existe=$this->uf_spg_select_estprog4($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4);
		//$this->SQL->begin_transaction();
		if(!$lb_existe)
		{
			$ls_cadena=" INSERT INTO spg_ep4(CodEmp,CodEstPro1,CodEstPro2,CodEstPro3,CodEstPro4,DenEstPro4) values('".$ls_codemp."','".$as_codestprog1."','".$as_codestprog2."','".$as_codestprog3."','".$as_codestprog4."','".$as_denestprog4."') ";
			$this->is_msg_error="Registro Incluido";
		}
		else
		{
			$ls_cadena=" UPDATE spg_ep4 SET DenEstPro4='".$as_denestprog4."' WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='".$as_codestprog4."'";
			$this->is_msg_error="Registro Actualizado";
		}
			$li_return=$this->SQL->execute($ls_cadena);
			if(($li_return==false)&&($this->SQL->message!=""))
			{	
				$lb_valido=false;
				$this->is_msg_error="Error al guardar Codigo programatico nivel 4".$fun->uf_convertirmsg($this->SQL->message);
			}
			else
			{
				if($li_return>0)
				{
					$lb_valido=true;
	//				$this->SQL->commit();				
				}	
				else
				{
					$lb_valido=false;
			//		$this->SQL->rollback();
					$this->is_msg_error="Error al guardar Codigo programatico nivel 4";
				}
			}
			return $lb_valido;
	}
	
	function uf_spg_select_estprog5($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_codestprog5)
	{
	//Metodo de seleccion del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$fun=new class_funciones();
		$ls_codemp=$this->datemp["CodEmp"];
		
		$ls_cadena="SELECT * FROM spg_ep5 WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='".$as_codestprog4."' AND CodEstPro5 like '%".$as_codestprog5."%'";
		
		$lr_return=$this->SQL->select($ls_cadena);
		if(($lr_return==false)&&($this->SQL->message!=""))
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta ".$fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($lr_return))
			{
				$lb_valido=true;
			}	
			else
			{
				$lb_valido=false;
				$this->is_msg_error="No existe el codigo programatico";
			}
			$this->SQL->free_result($lr_return);			
		}
	return $lb_valido;
	}



	function uf_spg_insert_estprog5($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_codestprog5,$as_denestprog5)
	{
	//Metodo de inserción del primer nivel de la estructura programatica para el modulo de presupuesto de gasto.
		$fun=new class_funciones();
		$ls_codemp=$this->datemp["CodEmp"];
		$lb_existe=$this->uf_spg_select_estprog5($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_codestprog5 );
		//$this->SQL->begin_transaction();
		if(!$lb_existe)
		{
			$ls_cadena=" INSERT INTO spg_ep5(CodEmp,CodEstPro1,CodEstPro2,CodEstPro3,CodEstPro4,CodEstPro5,DenEstPro5) values('".$ls_codemp."','".$as_codestprog1."','".$as_codestprog2."','".$as_codestprog3."','".$as_codestprog4."','".$as_codestprog5."','".$as_denestprog5."') ";
			$this->is_msg_error="Registro Incluido";
		}
		else
		{
			$ls_cadena=" UPDATE spg_ep5 SET DenEstPro5='".$as_denestprog5."' WHERE CodEmp='".$ls_codemp."' AND CodEstPro1='".$as_codestprog1."' AND CodEstPro2='".$as_codestprog2."' AND CodEstPro3='".$as_codestprog3."' AND CodEstPro4='".$as_codestprog4."' AND CodEstPro5='".$as_codestprog5."'";
			$this->is_msg_error="Registro Actualizado";
		}
			$li_return=$this->SQL->execute($ls_cadena);
			if(($li_return==false)&&($this->SQL->message!=""))
			{
				$lb_valido=false;
				$this->is_msg_error="Error en guardar ".$fun->uf_convertirmsg($this->SQL->message);
			}
			else
			{
				if($li_return>0)
				{
					$lb_valido=true;
	//				$this->SQL->commit();				
				}	
				else
				{
					$lb_valido=false;
			//		$this->SQL->rollback();
					$this->is_msg_error="Error al guardar Codigo programatico nivel 5";
				}
			}
			return $lb_valido;
	}

	
	
	

}
?>
