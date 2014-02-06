<?php
require_once("../shared/class_folder/class_sql.php");
include ("../shared/class_folder/class_datastore.php");
//include("class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

class class_sigesp_int_sss
{
	var $obj="";
	var $SQL;
	var $siginc;
	var $con;

	function class_sigesp_int_sss()
	{
		$this->lds_cuentas=new class_datastore();
		$this->lds_detalle_cmp=new class_datastore();
		$this->lds_cmp_cierre=new class_datastore();
		//$this->int_fecha=new class_funciones();
		$msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->SQL=new class_sql($this->con);
	}


	function  uf_sss_verificar_evento($as_evento)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_eventos  ".
					" WHERE evento='".$as_evento."'" ;
		
			
		$li_exec=$this->SQL->select($ls_sql);
		if($row=$this->SQL->fetch_row($li_exec))
		{
			$lb_existe=true;
		}
		else
		{
			$lb_existe=false;
		}
						
		if($li_exec<=0)
		{
			$this->is_msg_error = "Error en método uf_sss_verificar_evento  ";
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
			
		return $lb_existe;

	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_buscar_evento     //////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_buscar_evento($as_evento,$as_descripcion)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_eventos  ".
					" WHERE evento='".$as_evento."'" ;
		
			
		$li_exec=$this->SQL->select($ls_sql);
		if($row=$this->SQL->fetch_row($li_exec))
		{
			$lb_existe=true;
			$as_descripcion=$row["deseve"];
			
		}
		else
		{
			$lb_existe=false;
		}
						
		if($li_exec<=0)
		{
			$this->is_msg_error = "Error en método uf_sss_verificar_evento  ";
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
			
		return $lb_existe;

	}
			
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_insert_evento     //////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_evento($as_evento, $as_descripcion )
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO sss_eventos ( evento,deseve ) ".
					" VALUES('".$as_evento."','".$as_descripcion."')" ;
		
			
			$li_exec=$this->SQL->execute($ls_sql);
			
			
			if($li_exec<=0)
			{
				$this->is_msg_error = "Error en método uf_sss_insert_evento  ";
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
			}
		
		return $lb_valido;

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_update_evento       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_update_evento($as_evento,$as_descripcion) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE sss_eventos SET deseve='". $as_descripcion ."'".
	 		   " WHERE evento='" .$as_evento ."' ";
	 
        $this->SQL->begin_transaction();
		$li_exce = $this->SQL->execute($ls_sql);
		//print($ls_sql);	
		//print($this->SQL->message);	
		if ($li_exce<=0)
		{
		  
			$this->is_msg_error = "Error en método uf_sss_update_evento ";
			$lb_valido=false;
			$this->SQL->rollback();		  
		}
		else
		{
			$this->is_msg_error = "Registro actualizado ";
			$lb_valido=true;
			$this->SQL->commit();
		}
	  
	  	 
	  return $lb_valido;

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_delete_evento       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_delete_evento($as_evento)
	{
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		$ls_sql = " DELETE FROM sss_eventos".
					 " WHERE evento= '".$as_evento. "' "; 
		$this->SQL->begin_transaction();	
		$li_exec=$this->SQL->execute($ls_sql);
			
			if($li_exec<=0)
			{
				$this->is_msg_error = "Error en método uf_sss_delete_evento ";
				$ib_db_error = true;
				$lb_valido=false;
				$this->SQL->rollback();
				
			}
			else
			{
				$lb_valido=true;
				$this->is_msg_error = "Registro eliminado satisfactoriamente";
				$ib_db_error = false;
				$this->SQL->commit();
			}
		 		
		return $lb_valido;
	
	} //fin de uf_scg_delete_cuenta
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////         Inicio function uf_sss_select_sistema      //////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_sistema($as_codigo)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_sistemas  ".
					" WHERE codsis='".$as_codigo."'" ;
		
			
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

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_insert_sistema     //////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_sistema($as_codigo, $as_nombre )
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO sss_sistemas ( codsis,nomsis ) ".
					" VALUES('".$as_codigo."','".$as_nombre."')" ;
		
			
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

	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_delete_sistema       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_delete_sistema($as_codigo)
	{
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		$ls_sql = " DELETE FROM sss_sistemas".
					 " WHERE codsis= '".$as_codigo. "' "; 
		$this->SQL->begin_transaction();	
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
	
	} //fin de uf_scg_delete_cuenta
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_update_sistema       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_update_sistema($as_codigo,$as_nombre) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exec=-1;
	
	 $ls_sql = "UPDATE sss_sistemas SET nomsis='". $as_nombre ."'".
	 		   " WHERE codsis='" .$as_codigo ."' ";
	 
        $this->SQL->begin_transaction();
		$li_exec = $this->SQL->execute($ls_sql);
		//print($ls_sql);	
		//print($this->SQL->message);	

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

	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////         Inicio function uf_sss_select_grupos      //////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_grupos($as_nombre)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_grupos  ".
					" WHERE nomgru='".$as_nombre."'" ;
		
			
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

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_insert_grupo     ///////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_grupo($as_empresa, $as_nombre, $as_nota )
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;

		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO sss_grupos ( codemp,nomgru,nota ) ".
					" VALUES('".$as_empresa."','".$as_nombre."','".$as_nota."')" ;
		
			
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

	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_delete_grupo       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_delete_grupo($as_empresa,$as_nombre)
	{
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		$ls_sql = " DELETE FROM sss_grupos".
				  " WHERE codemp= '".$as_empresa. "'".
				  " AND nomgru= '".$as_nombre."'"; 
	
		$this->SQL->begin_transaction();	
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
	
	} //fin de uf_scg_delete_cuenta
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_update_grupo       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_update_grupo($as_empresa,$as_nombre,$as_nota) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE sss_grupos SET   nota='". $as_nota ."'".
	 		   " WHERE codemp='" . $as_empresa ."'".
			   " AND nomgru='" . $as_nombre ."'";
	 
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

	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////       Inicio function uf_sss_select_usuarios      //////////////////////////////////////////// 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_usuarios($as_empresa,$as_codigo)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_usuarios  ".
				  " WHERE codemp='".$as_empresa."'".
				  " AND codusu='".$as_codigo."'" ;
		
			
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

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_insert_usuario   ///////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_usuario($as_fecha,$as_empresa,$as_codigo,$as_nombre,$as_apellido,$as_cedula,$as_password,$as_telefono,$as_nota,$as_foto )
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		$ls_fechasql="";
		$this->is_msg_error = "";
		
		//$ls_fechasql=$this->int_fecha->uf_convertirdatetobd($as_fecha);	
		
		$ls_sql = "INSERT INTO sss_usuarios (codemp, codusu, cedusu, nomusu, apeusu, pwdusu, telusu, nota, ultingusu, fotousu ) ".
					" VALUES('".$as_empresa."','".$as_codigo."','".$as_cedula."','".$as_nombre."','".$as_apellido."','".$as_password."','".$as_telefono."','".$as_nota."','".$as_fecha."','".$as_foto."')" ;
		
			
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

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_delete_usuario      ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_delete_usuario($as_empresa,$as_codigo)
	{
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		
		$ls_sql = " DELETE FROM sss_usuarios".
					 " WHERE codemp= '".$as_empresa. "' AND codusu= '".$as_codigo."'"; 
		
		$this->SQL->begin_transaction();	
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
	
	} //fin de uf_scg_delete_usuario
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sss_update_usuario      ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_update_usuario($as_empresa,$as_codigo,$as_cedula,$as_nombre,$as_apellido,$as_telefono,$as_nota) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE sss_usuarios SET  cedusu='". $as_cedula ."',nomusu='". $as_nombre ."',apeusu='". $as_apellido ."',".
	 		  " telusu='". $as_telefono ."', nota='". $as_nota ."'".
			  " WHERE codemp='" .$as_empresa ."'".
			  " AND codusu='" .$as_codigo ."'";
	 
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

	}
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_insert_ventana   ///////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_ventana($as_sistema, $as_ventana, $as_titulo, $as_descripcion )
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		$this->is_msg_error = "";
		
		
		$ls_sql = "INSERT INTO sss_sistemas_ventanas (codsis, nomven, titven, desven) ".
					" VALUES('".$as_sistema."','".$as_ventana."','".$as_titulo."','".$as_descripcion."')" ;
		
			
			$li_exec=$this->SQL->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////      Inicio function uf_sss_select_ventana              /////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_ventana($as_sistema,$as_ventana)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_sistemas_ventanas".
				  " WHERE codsis = '".$as_sistema."'".
				  " AND nomven ='".$as_ventana."'" ;
		
			
		$li_exec=$this->SQL->select($ls_sql);
		if($row=$this->SQL->fetch_row($li_exec))
		{
			$lb_existe=true;
			$this->SQL->free_result($li_exec);
		}
		else
		{
			$lb_existe=false;
			$this->is_msg_error = "Error en método uf_sss_select_ventana  ";
		}
								
		return $lb_existe;

	}


//////////////////////////////
//FALTA DELETE  UPDATE
//////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////        Inicio function uf_sss_select_usuario_grupo      /////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_usuario_grupo($as_temporal,$as_empresa,$as_grupo)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT codusu FROM sss_usuarios_en_grupos".
				  " WHERE codusu = '".$as_temporal."'".
				  " AND codemp ='".$as_empresa."'".
				  " AND nomgru ='".$as_grupo."'" ;
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

	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////     Inicio function uf_sss_insert_usuario_grupo     /////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_usuario_grupo($as_empresa, $as_grupo, $as_usuario )
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO sss_usuarios_en_grupos (codemp, nomgru, codusu ) ".
					" VALUES('".$as_empresa."','".$as_grupo."','".$as_usuario."')" ;
		
			//print($ls_sql);
			$li_exec=$this->SQL->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
		}
			
		return $lb_valido;

	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////      Inicio Function uf_sss_delete_usuario_grupo     ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_delete_usuario_grupo($as_empresa,$as_grupo,$as_codigo)
	{
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		$ls_sql = " DELETE FROM sss_usuarios_en_grupos".
					 " WHERE codemp= '".$as_empresa. "'".
					 " AND nomgru= '".$as_grupo. "'".
					 " AND codusu= '".$as_codigo."'"; 
		$this->SQL->begin_transaction();	
		$li_exec=$this->SQL->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
		}

		 		
		return $lb_valido;
	
	} //fin de uf_scg_delete_usuario


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////      Inicio function uf_sss_select_derecho_usuario      /////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_derecho_usuario($as_empresa,$as_usuario,$as_sistema,$as_nombrefisico)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_derechos_usuarios ".
				" WHERE codemp = '".$as_empresa."'".
				" AND codusu ='".$as_usuario."'".
				" AND codsis ='".$as_sistema."'".
				" AND nomven ='".$as_nombrefisico."'" ;
		
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

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////     Inicio function uf_sss_insert_derecho_usuario     /////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_insert_derecho_usuario($as_empresa,$as_usuario,$as_sistema,$as_pantalla,$ai_visible,$ai_habilitada,$ai_leer,$ai_incluir,$ai_modificar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,$ai_ejecutar)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
					"eliminar,imprimir,administrativo,anular,ejecutar) ".
					" VALUES('".$as_empresa."','".$as_usuario."','".$as_sistema."','".$as_pantalla."',".$ai_visible.",".$ai_habilitada.",".
					" ".$ai_leer.",".$ai_incluir.",".$ai_modificar.",".$ai_eliminar.",".$ai_imprimir.",".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.")" ;
		
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

	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Inicio Function uf_sss_update_derecho_usuario      /////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sss_update_derecho_usuario($as_empresa,$as_usuario,$as_sistema,$as_pantalla,$ai_visible,$ai_habilitada,$ai_leer,$ai_incluir,$ai_modificar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,$ai_ejecutar) 
	{
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE sss_derechos_usuarios SET  visible='". $ai_visible ."', enabled='". $ai_habilitada ."', leer='". $ai_leer ."',".
	 		   " incluir='". $ai_incluir ."', cambiar='". $ai_modificar ."', eliminar='". $ai_eliminar ."',".
			   " imprimir='". $ai_imprimir ."', administrativo='". $ai_administrador ."', anular='". $ai_anular ."',".
			   " ejecutar='". $ai_ejecutar ."' ".
	 		   " WHERE codemp='" .$as_empresa ."'".
			   " AND codusu='" .$as_usuario ."'".
			   " AND codsis='" .$as_sistema ."'".
			   " AND nomven='" .$as_pantalla ."'";
	 
        $this->SQL->begin_transaction();
		$li_exce = $this->SQL->execute($ls_sql);
		//print($ls_sql);	
		//print($this->SQL->message);	
		if ($li_exce<=0)
		{
		  
			$this->is_msg_error = "Error en método uf_sss_update_usuarios ";
			$lb_valido=false;
			$this->SQL->rollback();		  
		}
		else
		{
			$this->is_msg_error = "Registro actualizado ";
			$lb_valido=true;
			$this->SQL->commit();
		}
	  
	  	 
	  return $lb_valido;

	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////      Inicio function uf_sss_select_regisro_eventos      /////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_sss_select_registro_eventos($as_empresa,$as_usuario,$as_sistema,$as_nombrefisico)
	{
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_registro_eventos ".
				" WHERE codemp = '".$as_empresa."'".
				" AND CodUsu LIKE '".$as_usuario."'".
				" AND CodSis LIKE'".$as_sistema."'".
				" AND Evento LIKE '".$as_nombrefisico."'".
				" AND '".$as_datedesde."'< FecEveTra <'".$as_datehasta."'" ;
		
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

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio Function uf_sql_transaction       ////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_sql_transaction($lb_valido)
	{
		if($lb_valido)
		{
			$this->SQL->commit();
			 $lb_valido=true;
			 //print "commit";
		}
		else
		{
			$this->SQL->rollback();
			 $lb_valido=false;
 			 //print "rollback";
		}	
		return $lb_valido;
	}


}
?>