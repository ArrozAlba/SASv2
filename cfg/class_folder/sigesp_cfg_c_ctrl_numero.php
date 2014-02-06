<?php
class sigesp_cfg_c_ctrl_numero
 {
    var $ls_sql="";
	var $io_msg_error;
	
	function sigesp_cfg_c_ctrl_numero()//Constructor de la Clase.
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
        require_once("../shared/class_folder/class_funciones.php");
		$this->seguridad  = new sigesp_c_seguridad();		  
        $this->io_funcion = new class_funciones();
		$io_conect        = new sigesp_include();
		$conn             = $io_conect->uf_conectar();
		$this->la_emp     = $_SESSION["la_empresa"];
		$this->codemp     = $_SESSION["la_empresa"]["codemp"];
		$this->ls_usuario = $_SESSION["la_logusr"];
		$this->io_sql     = new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg     = new class_mensajes();
	}

function uf_guardar_ctrl_numero($ar_datos,$as_codusu,$as_codsis,$aa_seguridad)
{  	   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_guardar_procedencia
//	Access:  public
//	Arguments: $ar_datos,$aa_seguridad,$aa_codusu
//   ar_datos=  Arreglo Cargado con la información proveniente de la Interfaz de Procedencias
//	Returns:	$lb_valido= Variable que devuelve true si la operación 
//                          fue exitosa de lo contrario devuelve false 
//	Description:Este método se encarga de realizar la inserción del registro si este existe con los 
//              datos,de lo contrario realiza una actualización con los datos cargados en el arreglo 
//              $ar_datos                  
/////////////////////////////////////////////////////////////////////////////////////////////////////////
  $as_longprefijo="";
  $as_codigo  = $ar_datos["codigo"];
  $as_codproc = $ar_datos["codsis"];
  $as_maxlon  = $ar_datos["maxlon"];
  $as_prefijo = $ar_datos["prefijo"]; 
  $as_numini  = $ar_datos["numini"];
  $as_numfin  = $ar_datos["numfin"];
  $as_numact  = $ar_datos["nunact"];
  $as_estcomscg = $ar_datos["estcompscg"]; 
  $as_longprefijo= strlen($as_prefijo); 
  if($as_longprefijo==4)
  {
    $as_prefijo="00".$as_prefijo;
  }
 
	$ls_sql="  INSERT INTO sigesp_ctrl_numero ".
			" (codemp,codsis,procede,codusu,id,prefijo,nro_inicial,nro_final,maxlen,nro_actual,estact,estcompscg)".
			" VALUES ('".$this->codemp."','".$as_codsis."','".$as_codproc."','".$as_codusu."','".$as_codigo."','".$as_prefijo."','".$as_numini."','".$as_numfin."','".$as_maxlon."','".$as_numact."',1,'".$as_estcomscg."')";
	//print $ls_sql."<br><br>";
	$this->io_sql->begin_transaction();             
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_sql->rollback();
		 $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_guardar_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		 $lb_valido=false;
	   }
	else
	   {   
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en CFG un Nuevo control número  ".$as_codigo." para el sistema".$as_codsis." y Procede ".$as_codproc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion); 
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }	  	
	return $lb_valido;
	$this->io_sql->close();
 }

function uf_actualizar_ctrl_numero($ar_datos,$as_codusu,$as_codsis,$aa_seguridad)
{  	   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_actualizar_ctrl_numero
//	Access:  public
//	Arguments: $ar_datos,$aa_seguridad,$aa_codusu
//   ar_datos=  Arreglo Cargado con la información proveniente de la Interfaz de Procedencias
//	Returns:	$lb_valido= Variable que devuelve true si la operación 
//                          fue exitosa de lo contrario devuelve false 
//	Description:Este método se encarga de actualizar el registro si este existe con los 
//              datos,de lo contrario realiza una actualización con los datos cargados en el arreglo 
//              $ar_datos                  
/////////////////////////////////////////////////////////////////////////////////////////////////////////
  $as_codigo  = $ar_datos["codigo"];
  $as_codproc = $ar_datos["codsis"];
  $as_maxlon  = $ar_datos["maxlon"];
  $as_prefijo = $ar_datos["prefijo"]; 
  $as_numini  = $ar_datos["numini"];
  $as_numfin  = $ar_datos["numfin"];
  $as_numact  = $ar_datos["nunact"];
  $as_estcomscg = $ar_datos["estcompscg"]; 
 
    $lb_valido=true;
	$ls_sql=" UPDATE sigesp_ctrl_numero ".
		  " SET  prefijo      = '".$as_prefijo."', ".
		  "      nro_inicial  = '".$as_numini."',  ".
		  "      nro_final    = '".$as_numfin."',  ".
		  "      maxlen       = '".$as_maxlon."',  ".
		  "      nro_actual   = '".$as_numact."',  ".
		  "      estact       = 1                  ".
		  " WHERE codemp='".$this->codemp."' AND codsis='".$as_codsis."' ".
		  " AND procede='".$as_codproc."' AND codusu='".$as_codusu."'";
	//print $ls_sql."<br><br>";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_sql->rollback();
		 $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_actualizar_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		 $lb_valido=false;
	   }
	else
	   {   
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en CFG control número  ".$as_codigo." para el sistema".$as_codsis." y Procede ".$as_codproc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }	  	
	return $lb_valido;	
  $this->io_sql->close();
 }	

function uf_select_ctrl_numero($as_codemp, $as_codsis,$as_procede, $as_codusu)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql  = " SELECT * FROM sigesp_ctrl_numero  ".
	           " WHERE codemp  ='".$as_codemp."'".
	           " AND   codsis  ='".$as_codsis."'".
	           " AND   procede ='".$as_procede."'".
	           " AND   codusu  ='".$as_codusu."'"; //print $ls_sql."<br><br>";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_select_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		      $lb_valido=true;
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return $lb_valido;
}



function uf_buscar_campo($as_tabla,$as_campo,$as_criterio)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_buscar_campo
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$as_cosis="";
	$ls_sql  = " SELECT ".$as_campo." FROM ".$as_tabla." ".
	           " WHERE ".$as_criterio." ";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_buscar_campo;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
        {  
	      $row=$this->io_sql->fetch_row($rs_data);
	      $as_cosis=$row["$as_campo"];
          $this->io_sql->free_result($rs_data);
		}
     	else
        {
  	      $lb_valido=false;
        }	 
      }
	  
return array($lb_valido,$as_cosis);
}

function uf_delete_ctrl_numero($ar_datos,$aa_seguridad)
{   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_delete_procedencia
//	Access:  public
//	Arguments:
// $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	  Returns:	$lb_valido= Variable que devuelve true si encontro el registro 
//                          de lo contrario devuelve false. 
//	Description: Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$as_codigo  = $ar_datos["codigo"];
	$as_codproc = $ar_datos["codsis"];
	$as_maxlon  = $ar_datos["maxlon"];
	$as_prefijo = $ar_datos["prefijo"];
	$as_numini  = $ar_datos["numini"];
	$as_numfin  = $ar_datos["numfin"];
	$as_numact  = $ar_datos["nunact"];
	$as_estcomscg = $ar_datos["estcompscg"];
	
	
			list($lb_valido,$as_codsis)=$this->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$as_codproc."'");
			$lb_valido = false;
			$ls_sql    = " DELETE FROM sigesp_ctrl_numero WHERE codemp='".$this->codemp."' AND codsis='".$as_codsis."' AND procede='".$as_codproc."' AND id='".$as_codigo."'";
			$this->io_sql->begin_transaction();
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			   { 
			          $this->io_msg_error="CLASE->SIGESP_CFG_C_PROCEDENCIAS; METODO->uf_delete_procedencia;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);   
			   }
		    else
			   {
			     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			     $ls_evento="DELETE";
			     $ls_descripcion ="Eliminó en CFG el Control Numerico ".$as_codigo." del Sistema ".$as_codsis." y usuario ".$this->ls_usuario;
			     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			     $aa_seguridad["ventanas"],$ls_descripcion);
			     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
				 $lb_valido = true;
			   }
	return $lb_valido;
}	  
                 


function uf_verificar_procede($as_codemp, $as_prefijo,$as_procede, $as_codusu)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql  = "SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero WHERE codemp='".$as_codemp."' AND prefijo='".$as_prefijo."' AND procede='".$as_procede."' AND  codusu<>'".$as_codusu."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_select_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		     $row=$this->io_sql->fetch_row($rs_data);
	     	 $as_existe=$row["existe"];
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return array($lb_valido,$as_existe);
}


function uf_verificar_eliminacion($as_procede)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	//$ls_sql  = "SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero WHERE codemp='".$as_codemp."' AND prefijo='".$as_prefijo."' AND procede='".$as_procede."' AND  codusu<>'".$as_codusu."'";
	if($as_procede=="SEPSPC")
	{
	$ls_sql="SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero,sep_solicitud
			 WHERE sep_solicitud.codemp=sigesp_ctrl_numero.codemp AND
			      SUBSTR(sep_solicitud.numsol,1,6)=sigesp_ctrl_numero.prefijo AND
			      sigesp_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'"; 
	}
	elseif($as_procede=="CXPSOP") 
	{
		$ls_sql="SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero,cxp_solicitudes
			 WHERE cxp_solicitudes.codemp=sigesp_ctrl_numero.codemp AND
			      SUBSTR(cxp_solicitudes.numsol,1,6)=sigesp_ctrl_numero.prefijo AND
			      sigesp_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'"; 
	}
	elseif($as_procede=="SOCCOC")
	{
	 $ls_sql="SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero,soc_ordencompra
			 WHERE soc_ordencompra.codemp=sigesp_ctrl_numero.codemp AND
			      SUBSTR(soc_ordencompra.numordcom,1,6)=sigesp_ctrl_numero.prefijo AND
			      sigesp_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'"; 
	}
	elseif($as_procede=="SOCCOS")
	{
	 $ls_sql="SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero,sigesp_cmp,sigesp_
			 WHERE sigesp_cmp.codemp=sigesp_ctrl_numero.codemp AND
			      SUBSTR(sigesp_cmp.numordcom,1,6)=sigesp_ctrl_numero.prefijo AND
			      sigesp_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'"; 
	}
	elseif($as_procede=="SCGCMP") 
	{
		$ls_sql="SELECT COUNT(PREFIJO) AS existe FROM sigesp_ctrl_numero,sigesp_cmp,scg_dt_cmp
			 WHERE sigesp_cmp.codemp=sigesp_ctrl_numero.codemp 
			      AND scg_dt_cmp.procede=sigesp_ctrl_numero.procede  
			      AND sigesp_cmp.procede=sigesp_ctrl_numero.prefijo
				  AND sigesp_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'";
	}
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_select_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		     $row=$this->io_sql->fetch_row($rs_data);
	     	 $as_existe=$row["existe"];
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return array($lb_valido,$as_existe);
}
/*
function uf_verificar_id($as_codigo,$as_tabla,$as_campo)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_buscar_campo
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$as_cosis="";
	$ls_sql  = " SELECT ".$as_campo." FROM ".$as_tabla." ".
	           " WHERE ".$as_criterio." ";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero; METODO->uf_buscar_campo;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
        {  
	      $row=$this->io_sql->fetch_row($rs_data);
	      $as_cosis=$row["codsis"];
          $this->io_sql->free_result($rs_data);
		}
     	else
        {
  	      $lb_valido=false;
        }	 
      }
return array($lb_valido,$as_cosis);
}

*/

function  uf_sss_load_usuarios_disponibles($as_empresa,$as_codsis,$as_prefijo,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios_disponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$as_codsis."'");
	
		$ls_sql="Select  sss_usuarios.codusu,sss_usuarios.cedusu,sss_usuarios.nomusu,sss_usuarios.apeusu".
				" from sss_usuarios where sss_usuarios.codusu not in".
				"   (select codusu from sigesp_ctrl_numero where sigesp_ctrl_numero.codemp='".$as_empresa."' ".
				"    and sigesp_ctrl_numero.codsis='".$as_codproc."' and sigesp_ctrl_numero.procede='".$as_codsis."') ".
				" ORDER BY codusu"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero MÉTODO->uf_sss_load_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles[$li_pos]["codusu"]=$row["codusu"];
				$aa_disponibles[$li_pos]["nomusu"]=$row["nomusu"];  
				$aa_disponibles[$li_pos]["apeusu"]=$row["apeusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios
	
	function  uf_sss_load_usuarios_asignados($as_codemp,$as_prefijo,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios_asignados
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_prefijo    // prefijo 
		//                 $aa_disponibles    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :28/08/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT sigesp_ctrl_numero.codsis,sigesp_ctrl_numero.procede,sigesp_ctrl_numero.codusu,sigesp_ctrl_numero.prefijo,".
		        " (SELECT nomusu from sss_usuarios where codemp ='".$as_codemp."' and sigesp_ctrl_numero.codusu=sss_usuarios.codusu) as nomusu,".
			    " (SELECT apeusu from sss_usuarios where codemp ='".$as_codemp."' and sigesp_ctrl_numero.codusu=sss_usuarios.codusu) as apeusu,".
				" (SELECT cedusu from sss_usuarios where codemp ='".$as_codemp."' and sigesp_ctrl_numero.codusu=sss_usuarios.codusu) as cedusu".
				" FROM sigesp_ctrl_numero,sss_usuarios".
				" WHERE sigesp_ctrl_numero.codemp ='".$as_codemp."' ".
				" AND  sigesp_ctrl_numero.codemp=sss_usuarios.codemp AND sigesp_ctrl_numero.codusu=sss_usuarios.codusu".
				" AND sigesp_ctrl_numero.prefijo='".$as_prefijo."'".
				" ORDER BY nomusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero MÉTODO->uf_sss_load_usuarios_asignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;  
				$aa_asignados[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios_asignados
	
   function  uf_buscar_usuarios_disponibles($as_empresa,$as_codsis,$as_prefijo,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_usuarios_disponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="Select  sss_usuarios.codusu,sss_usuarios.cedusu,sss_usuarios.nomusu,sss_usuarios.apeusu".
				" from sss_usuarios where sss_usuarios.codusu not in".
				"   (select codusu from sigesp_ctrl_numero where sigesp_ctrl_numero.codemp='".$as_empresa."' ".
				"    and sigesp_ctrl_numero.codsis='".$as_codproc."' and sigesp_ctrl_numero.procede='".$as_codsis."') ".
				//"    and sigesp_ctrl_numero.prefijo='".$as_prefijo."') ".
				" ORDER BY codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero MÉTODO->uf_buscar_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_buscar_usuarios_disponibles
    
	function  uf_buscar_usuarios_asignados($as_empresa,$as_codsis,$as_prefijo,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_usuarios_asignados
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_prefijo    // prefijo 
		//                 $aa_disponibles    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :28/08/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="SELECT codusu from sigesp_ctrl_numero ".
				" WHERE sigesp_ctrl_numero.codemp ='".$as_empresa."' ".
				" AND  sigesp_ctrl_numero.procede='".$as_codsis."'".
				" AND sigesp_ctrl_numero.codsis='".$as_codproc."'".
				" AND sigesp_ctrl_numero.prefijo='".$as_prefijo."'".
				" ORDER BY codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero MÉTODO->uf_buscar_usuarios_asignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;  
				$aa_asignados[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
				$lb_valido1=$li_pos;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_buscar_usuarios_asignados 
	
	function  uf_verificar_comprobante($as_empresa,$as_prefijo,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_comprobante
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="SELECT * FROM sigesp_ctrl_numero".
				" WHERE sigesp_ctrl_numero.codemp ='".$as_empresa."' ".
				" and sigesp_ctrl_numero.codsis='".$as_codproc."'".
				" and sigesp_ctrl_numero.procede='".$as_codsis."'".
				" ORDER BY codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_cfg_c_ctrl_numero MÉTODO->uf_verificar_comprobante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_verificar_comprobante 
	
function uf_delete_usuarioasignados($as_empresa,$as_codsis,$as_codproc,$as_prefijo,$aa_asignados,$as_codusu,$aa_seguridad)
{   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_sss_delete_usuarioasignados
//	Access:  public
//	Arguments:
// $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	  Returns:	$lb_valido= Variable que devuelve true si encontro el registro 
//                          de lo contrario devuelve false. 
//	Description: Este método que se encarga de borrar los usuarios asinados a un prefijo.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " DELETE FROM sigesp_ctrl_numero ".
	              " WHERE sigesp_ctrl_numero.codemp ='".$as_empresa."' ".
				  " AND  sigesp_ctrl_numero.procede='".$as_codproc."'".
				  " AND sigesp_ctrl_numero.codsis='".$as_codsis."'".
				  " AND sigesp_ctrl_numero.prefijo='".$as_prefijo."'".
				  " AND sigesp_ctrl_numero.codusu='".$as_codusu."'"; 
    $this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   { 
	          $this->io_msg_error="CLASE->SIGESP_CFG_C_PROCEDENCIAS; METODO->uf_sss_delete_usuarioasignados;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);   
	   }
    else
	   {
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="DELETE";
	     $ls_descripcion ="Eliminó en CFG en la tabla siges_ctrl_numero, el codigo de usuario ".$as_codusu;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $lb_valido = true;
	   }
	return $lb_valido;
}	  

function  uf_verificar_prefijo($as_empresa,&$as_codsis,&$as_prefi,&$li_sal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_prefijo
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si ya exite un documento con ese prefijo
		//	   Creado Por: 
		// Fecha Creación: 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_vali=false;
		$li_sal=0;
		$as_longprefijo= strlen($as_prefi); 
	    if($as_longprefijo==4)
	    {
		  $as_prefi="00".$as_prefi;
	    } 
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$as_codsis."'");
	
		$ls_sql="Select  sigesp_ctrl_numero.codsis,sigesp_ctrl_numero.prefijo".
				" FROM sigesp_ctrl_numero ".
	              " WHERE sigesp_ctrl_numero.codemp ='".$as_empresa."' ".
				  " AND  sigesp_ctrl_numero.codsis='".$as_codproc."'".
				  " AND sigesp_ctrl_numero.procede='".$as_codsis."'".
				  " AND sigesp_ctrl_numero.prefijo='".$as_prefi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->uf_verificar_prefijo MÉTODO->uf_sss_load_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{ 
			  $li_numrows=$this->io_sql->num_rows($rs_data);       
			  if ($li_numrows>=1)
			  {
				 $lb_vali=true;
				 $li_sal=1;				
				 $this->io_sql->free_result($rs_data);  
			  }
		 } 
		return $lb_vali;
	}  // end  function  uf_verificar_prefijo
    
}//Fin de la Clase.
?>