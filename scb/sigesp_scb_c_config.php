<?php
class sigesp_scb_c_config
{
	var $io_sql;
	var $io_function;
	var $io_msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	
	function sigesp_scb_c_config($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		$this->io_seguridad = new sigesp_c_seguridad();
		$sig_inc            = new sigesp_include();
		$con                = $sig_inc->uf_conectar();
		$this->io_sql       = new class_sql($con);
		$this->io_function  = new class_funciones();
		$this->io_msg       = new class_mensajes();
		$this->dat          = $_SESSION["la_empresa"];
		$this->is_empresa   = $aa_security["empresa"];
		$this->is_sistema   = $aa_security["sistema"];
		$this->is_logusr    = $aa_security["logusr"];	
		$this->is_ventana   = $aa_security["ventanas"];
	}//Fin del constructor

	function uf_cargar_config()
	{
		//////////////////////////////////////////////////////////////////////////////
		//
		//	io_functionction:	 uf_cargar_config
		// Access:		 public
		//	Returns:	 Boolean Retorna si encontro o no errores en la consulta
		//	Description: Metodo que retorna los	parametros de configuracion de bancos               
		//////////////////////////////////////////////////////////////////////////////
	
		$li_row_total=0;$li_dw_row=0;$li_x=0;$li_row=0;
		$arr_config=array();
		$ls_sql="SELECT * 
				 FROM   scb_config ";			
					
		$rs_config=$this->io_sql->select($ls_sql);
		
		if($rs_config===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_cargar_config".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$data="";
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_config))
			{
				$ls_numordpag=$row["numordpag"];
				$arr_config=array('numordpag'=>$ls_numordpag);
			}
		}
		return $arr_config;
	}//Fin uf_cargar_documentos
	
	function uf_select_config($ls_id,$ls_numordpag)
	{
		$ls_sql="SELECT * 
				 FROM scb_config 
				 WHERE id='".$ls_id."' ";
	
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($this->io_sql->fetch_row($rs_data))
			{return true;}
			else
			{return false;}
		}
	}
	
	function uf_guardar_config($ls_id,$ls_numordpag)
	{
		$lb_existe=$this->uf_select_config($ls_id,$ls_numordpag);
			
		if(!$lb_existe)
		{
			$ls_sql="INSERT INTO scb_config(id,numordpag) VALUES('".$ls_id."','".$ls_numordpag."')";
		}
		else
		{
			$ls_sql="UPDATE scb_config SET numordpag='".$ls_numordpag."' WHERE id='".$ls_id."'"	;
		}		
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="Error en metodo uf_guardar_config ";		
			return false;
		}
		else
		{
			$this->is_msg_error="Registro Guardado";		
			return true;
		}		
	}
	
	function uf_select_formatos_cartaorden()
	{
		/*----------------------------------------------------------------
		Function: uf_select_formatos_cartaorden
		Descripción: funcion que se encarga de retornar los tipo de formato de
		carta orden creados. Se utiliza para llenar el combo
		Autor: Ing. Laura Cabré
		Fecha: 19/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT *
				FROM scb_cartaorden where codemp='$ls_codemp'";				
		
		$rs_formatos=$this->io_sql->select($ls_sql);
		
		if($rs_formatos==false)
		{
			print "Error".$this->io_sql->message;
			print "<select name=cmbformatos >";
		    print "<option value=ERROR</option>";
			print "</select>";
		}
		else
		{
			print "<select name=cmbformatos >";
			while($row=$this->io_sql->fetch_row($rs_formatos))
			{
				 $ls_codigo=$row["codigo"];
				 $ls_formato=$row["nombre"];
				 $ls_status=$row["status"];

				 if($ls_status==1)
				 {
					 print "<option value=".$ls_codigo." selected>".$ls_formato."</option>";
				 }
				 else
				 {
					 print "<option value=".$ls_codigo.">".$ls_formato."</option>";
				 }
					
			}
			print "</select>";
			$this->io_sql->free_result($rs_formatos);
		}
		
	}
	function uf_guardar_formato_cartaorden($as_codigo)
	{
		/*----------------------------------------------------------------
		Function: uf_guardar_formato_cartaorden
		Descripción: funcion que se encarga de actualizar cual modelo de carta orden
					es el que se desea utilizar
		Autor: Ing. Laura Cabré
		Fecha: 19/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		$ls_codigo="";
		$ls_sql="SELECT codigo
				FROM scb_cartaorden 
				WHERE codemp='$ls_codemp' AND status=1";
		$rs_formatos=$this->io_sql->select($ls_sql);
		if($rs_formatos===false)
		{
			print "Error".$this->io_sql->message;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_formatos))
			{
				$ls_codigo=$row["codigo"];
			}
		}
		if($as_codigo!=$ls_codigo)
		{
			$ls_sql="UPDATE scb_cartaorden SET status=0 WHERE codemp='$ls_codemp' AND codigo='$ls_codigo'";
			$li_result=$this->io_sql->execute($ls_sql);
			if($li_result===false)
			{
				$this->is_msg_error="Error 1 en metodo uf_guardar_formato_cartaorden ";
				return false;		
			}
			else
			{
									
			
				$ls_sql="UPDATE scb_cartaorden SET status=1 WHERE codemp='$ls_codemp' AND codigo='$as_codigo'";
				$li_result=$this->io_sql->execute($ls_sql);
				if($li_result===false)
				{
					$this->is_msg_error="Error 2 en metodo uf_guardar_formato_cartaorden ";
					return false;		
				}
				else
				{
					$this->is_msg_error="Registro Actualizado";
					return true;					
				}
			}				
		}
		else
		{
			$this->is_msg_error="Registro Actualizado";
			return true;
		}			
				
	}
	function uf_generar_codigo()
	{
	   /*----------------------------------------------------------------
		Function: uf_generar_codigo
		Descripción: funcion que genera el codigo del nuevo formato de carta orden
		Autor: Ing. Laura Cabré
		Fecha: 19/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT codigo 
		 		  FROM scb_cartaorden
				  WHERE codemp='".$ls_codemp."' ORDER BY codigo DESC";		
		  $rs_funciondb=$this->io_sql->select($ls_sql);
		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $codigo=$row["codigo"];
			  settype($codigo,'int');                             // Asigna el tipo a la variable.
			  $codigo = $codigo + 1;                              // Le sumo uno al entero.
			  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,3);
		  }
		  else
		  {
			  $codigo="1";
			  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,3);
		  }
		return $ls_codigo;
	}
	function uf_select_cartaorden($as_codigo)
	{
	    /*----------------------------------------------------------------
		Function: uf_select_cartaorden
		Descripción: funcion que retorna true si existe la carta orden
		Autor: Ing. Laura Cabré
		Fecha: 19/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT *
				FROM scb_cartaorden where codemp='$ls_codemp' and codigo='$as_codigo'";			
		$rs_formatos=$this->io_sql->select($ls_sql);
		if($rs_formatos==false)
		{
			print "Error".$this->io_sql->message;
			return false;			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_formatos))
			{								
				return true;		
			}
			else
			{
				return false;
			}			
			$this->io_sql->free_result($rs_formatos);
		}
	}
	function uf_guardar_cartaorden($as_codigo,$as_nombre,$as_encabezado,$as_cuerpo,$as_pie,$as_archrtf)
	{
	    /*----------------------------------------------------------------
		Function: uf_guardar_cartaorden
		Descripción: funcion que guarda o actualiza el formato de una carta orden
		Autor: Ing. Laura Cabré
		Fecha: 19/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		if(!$this->uf_select_cartaorden($as_codigo))
		{
			$ls_sql="INSERT INTO scb_cartaorden (codemp,codigo,nombre,encabezado,cuerpo,pie,status,archrtf)
					VALUES ('$ls_codemp','$as_codigo','$as_nombre','$as_encabezado','$as_cuerpo','$as_pie',0,'$as_archrtf')";
			$this->is_msg_error="Registro Incluido";
		}
		else
		{
			$ls_sqlarc="";
			if($as_archrtf!="")
			{
				$ls_sqlarc=", archrtf='".$as_archrtf."' ";
			}

			$ls_sql="UPDATE scb_cartaorden SET nombre='$as_nombre',encabezado='$as_encabezado',cuerpo='$as_cuerpo', ".
					" pie='$as_pie' ".$ls_sqlarc.
					"WHERE codemp='$ls_codemp' AND codigo='$as_codigo'";
			$this->is_msg_error="Registro Actualizado";
		}
			$li_result=$this->io_sql->execute($ls_sql);
			if($li_result===false)
			{
				$this->is_msg_error="Error en metodo uf_guardar_cartaorden ";
				return false;		
			}
			else
			{			    
				return true;
			}	
	 } 
	 function uf_eliminar_cartaorden($as_codigo)
	{
	    /*----------------------------------------------------------------
		Function: uf_eliminar_cartaorden
		Descripción: funcion que elimina un formato de una carta orden
		Autor: Ing. Laura Cabré
		Fecha: 20/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		if($this->uf_select_cartaorden($as_codigo))
		{
			$ls_sql="DELETE FROM scb_cartaorden WHERE codemp='$ls_codemp' AND codigo='$as_codigo'";		
			$li_result=$this->io_sql->execute($ls_sql);
			if($li_result===false)
			{
				$this->is_msg_error="Error en metodo uf_eliminar_cartaorden ";
				return false;		
			}
			else
			{			    
				$this->is_msg_error="Registro Eliminado";
				return true;
			}
		}
		else
		{
		   $this->is_msg_error="Registro No existe";
		   return true; 		
		}				
	  }	
	function uf_buscar_seleccionado()
	{
	    /*----------------------------------------------------------------
		Function: uf_select_cartaorden
		Descripción: funcion que retorna true si existe la carta orden
		Autor: Ing. Laura Cabré
		Fecha: 19/12/2006
		------------------------------------------------------------------*/
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT codigo
				FROM scb_cartaorden 
				where codemp='$ls_codemp' and status=1";
		$rs_formatos=$this->io_sql->select($ls_sql);
		if($rs_formatos==false)
		{
			print "Error".$this->io_sql->message;
			return false;			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_formatos))
			{								
				
				return $row["codigo"];		
			}
			else
			{
				return false;
			}			
			$this->io_sql->free_result($rs_formatos);
		}
	}
	
	function uf_select_fuente()
	{
		/*---------------------------------------------------
		Funcion: uf_select_fuente()
		Descripcion: funcion que determina la configuracion de la elaboracion de
					los comprobantes de retencion.
		return: valor de la configuracion
		Autor: Ing. Laura Cabré
		Fecha: 04/02/2007		
		----------------------------------------------------*/	
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT modageret
				 FROM sigesp_empresa
				 WHERE codemp='".$ls_codemp."'";	
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_select_fuente, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				return $row["modageret"];
			}
			else
			{
				return false;
			}
		}	
	}	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nombre,$as_tipo,$as_tamano,$as_nombretemporal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_scb_p_conf_cartaorden)
		//	    Arguments: as_nombre  // Nombre 
		//				   as_tipo  // Tipo 
		//				   as_tamano  // Tamaño 
		//				   as_nombretemporal  // Nombre Temporal
		//	      Returns: as_nombre sale vacio si da un error y con el mismo valor si se subio correctamente
		//	  Description: Funcion que sube un archivo al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_nombre!="")
		{
			$ls_ruta="cartaorden/original";
			@mkdir($ls_ruta,0755);
			$ls_ruta="cartaorden/copia";
			@mkdir($ls_ruta,0755);
			if (!((strpos($as_tipo, "word")||strpos($as_tipo, "rtf")) && ($as_tamano < 1000000))) 
			{ 
				$as_nombre="";
				$this->io_msg->message("El archivo no es válido, es muy grande o no es de Extención RTF.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nombretemporal, "cartaorden/original/".$as_nombre))))
				{
					$as_nombre="";
		        	$this->io_msg->message("CLASE->Configuracion MÉTODO->uf_upload ERROR-> No tiene Permiso para copiar en la carpeta Contacte con el administrador del sistema."); 
				}
				else
				{
					@chmod("cartaorden/original/".$as_nombre,0755);
				}
			}
		}
		return $as_nombre;	
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>