<?php
require_once("class_sql.php");
require_once("sigesp_include.php");
require_once("class_mensajes.php");

	class sigesp_c_seguridad
	{
		function sigesp_c_seguridad()
		{
			$io_msg=new class_mensajes();
			$in=new sigesp_include();
			$this->con=$in->uf_conectar();
			$this->SQL=new class_sql($this->con);
		}	
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////             Inicio function select_eventos()         ///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		function uf_sss_select_eventos($as_evento,&$ls_descripcion)
		{
				
			$li_exec=-1;
			$lb_valido=true;
			$ls_sql="";
			$ls_enabled= 1;
			$io_msg=new class_mensajes();
		
			$ls_sql="SELECT * FROM sss_eventos WHERE evento='".$as_evento."' ";
			$li_exec=$this->SQL->select($ls_sql);
			//print($ls_sql);
			
			if($row=$this->SQL->fetch_row($li_exec))
			{
				$ls_descripcion=$row["DesEve"];
			}
			else
			{
				$ls_descripcion="";
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
								
			return $lb_valido;
			$this->SQL->free_result($li_exec);
		
		}
		



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////     Inicio function uf_sss_insert_eventos_ventana     ///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_sss_insert_eventos_ventana($as_empresa,$as_sistema,$as_evento,$as_usuario,$as_ventana)
		{
		
			$lb_valido=true;
			$ls_sql="";
			$li_exec=-1;
		
			$this->is_msg_error = "";
			$ls_fecha = date("Y/m/d h:i");
			$ls_host=@gethostbyaddr($ip);
			$li_largo= strpos($ls_host,".");
			$ls_maquina = substr($ls_host,0,$li_largo);
			$io_msg=new class_mensajes();
			$ls_descripcion="";
			
			$this->uf_sss_select_eventos($as_evento,$ls_descripcion);
			
			$ls_sql="SELECT NumEve from sss_registro_eventos";
			$result=$this->SQL->select($ls_sql);
			$li_numeve=1;
			while($row=$this->SQL->fetch_row($result))
			{
				$li_numeve=$li_numeve+1;
			}
			
			$io_msg->message($li_numeve);
			$ls_sisope="cualquiera";
			print("DESCRIPCION->".$ls_descripcion);
			$ls_sql= "INSERT INTO sss_registro_eventos (CodEmp, NumEve, CodUsu, CodSis, Evento, NomVen, FecEveTra, EquEveTra, DesEveTra, UsuSisOper) VALUES ('".$as_empresa."','".$li_numeve."','".$as_usuario."','".$as_sistema."','".$as_evento."','".$as_ventana."','".$ls_fecha."','".$ls_maquina."','".$ls_descripcion."','".$ls_sisope."')" ;
		
			$li_exec=$this->SQL->execute($ls_sql);
			//print($ls_sql);
			if($li_exec<=0)
			{
				$io_msg->message("NO EXITOSO!!!!!!!!!");
				$this->is_msg_error = "Error en método uf_sss_insert_derecho_usuario  ";
				$lb_valido=false;
				
			}
			else
			{
				$io_msg->message("EXITOSO!!!!!!!!!");				
			}
		
		return $lb_valido;
		
		}		
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////             Inicio function select_permisos()         ///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		function uf_sss_select_permisos($as_empresa,$as_usuario,$as_sistema,$as_ventana)
		{
				
			$li_exec=-1;
			$lb_valido=true;
			$ls_sql="";
			$ls_enabled= 1;
			$io_msg=new class_mensajes();
		
			$ls_sql="SELECT * FROM sss_derechos_usuarios WHERE codemp='".$as_empresa."' AND codusu='".$as_usuario."' AND codsis='".$as_sistema."' AND nomven='".$as_ventana."' AND enabled=".$ls_enabled." ";
			$li_exec=$this->SQL->select($ls_sql);
			//print($ls_sql);
			
			if($row=$this->SQL->fetch_row($li_exec))
			{
				//$io_msg->message("TIENE PERMISO");				
			}
			else
			{
				$io_msg->message("UD. no tiene permiso para accesar a esta opción de menu");
				$lb_valido=false;
				$this->is_msg_error = "Error en método uf_sss_select_derecho_usuario ";
			}
								
			return $lb_valido;
			$this->SQL->free_result($li_exec);
		
		}
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////        Fin class sigesp_c_seguridad        /////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>