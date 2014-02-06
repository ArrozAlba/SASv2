<?php
class sigesp_rpc_c_beneficiario
 {
var $ls_sql="";
var $la_emp;
var $is_msg_error;
 	
	function sigesp_rpc_c_beneficiario()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
        require_once("../shared/class_folder/sigesp_c_seguridad.php");
	    require_once("../shared/class_folder/class_funciones.php");
		$this->io_funcion   = new class_funciones();
		$this->seguridad    = new sigesp_c_seguridad();			
        $io_conect          = new sigesp_include();
		$conn               = $io_conect->uf_conectar();
		$this->la_emp       = $_SESSION["la_empresa"];
		$this->io_sql       = new class_sql($conn); //Instanciando  la clase sql
		$this->gestor       = $_SESSION["ls_gestor"];
		$this->io_msg       = new class_mensajes();
	}

	
function uf_insert_beneficiario($as_codemp,$ar_datos,$aa_seguridad)
{  
 //////////////////////////////////////////////////////////////////////////////
 //	Metodo       uf_insert_beneficiario
 //	Access       public
 //	Arguments    $as_codemp,$ar_datos,$aa_seguridad
 //	Returns	     $lb_valido. Retorna una variable booleana    
 //	Description  Funcion que se encarga de insertar en la tabla de Beneficiarios
 //////////////////////////////////////////////////////////////////////////////
   
  $ls_cedula       = trim($ar_datos["cedula"]);
  $ls_nombre       = $ar_datos["nombre"];
  $ls_apellido     = $ar_datos["apellido"];
  $ls_direccion    = $ar_datos["direccion"];
  $ls_telefono     = $ar_datos["telefono"];
  $ls_celular      = $ar_datos["celular"];
  $ls_email        = $ar_datos["email"];
  $ls_contable     = $ar_datos["contable"];
  $ls_contablerecdoc     = $ar_datos["contablerecdoc"];
  $ls_cuenta       = $ar_datos["cuenta"];
  $ls_banco        = $ar_datos["banco"];  
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  $ls_tipocuenta   = $ar_datos["cmbtipcue"];
  $ls_rifben       = trim($ar_datos["tipperrif"].'-'.$ar_datos["numpririf"].'-'.$ar_datos["numterrif"]);
  $ls_codbansig    = $ar_datos["codbancof"];
  $ls_fecregben    = $ar_datos["fecregben"];
  $ls_fecregben    = $this->io_funcion->uf_convertirdatetobd($ls_fecregben);
  $ls_nacben       = $ar_datos["nacionalidad"];
  $ls_numpasben    = $ar_datos["numpasben"];
  $ls_tipconben    = $ar_datos["tipconben"];
  if ($ls_tipconben=='-'){$ls_tipconben='O';}
  
  $ls_sql=" INSERT INTO rpc_beneficiario(codemp,ced_bene,nombene,apebene,dirbene,telbene,celbene,email,sc_cuenta,ctaban,                  ".
		  "  codban,codtipcta,codpai,codest,codmun,codpar,rifben,codbansig,fecregben,nacben,numpasben,tipconben,sc_cuentarecdoc)                          ". 
		  "  VALUES('".$as_codemp."','".$ls_cedula."','".$ls_nombre."','".$ls_apellido."','".$ls_direccion."','".$ls_telefono."',         ".
		  " '".$ls_celular."','".$ls_email."','".$ls_contable."','".$ls_cuenta."','".$ls_banco."','".$ls_tipocuenta."',                   ".
		  " '".$ls_pais."','".$ls_estado."','".$ls_municipio."','".$ls_parroquia."','".$ls_rifben."','".$ls_codbansig."','".$ls_fecregben."',".
		  " '".$ls_nacben."','".$ls_numpasben."','".$ls_tipconben."','".$ls_contablerecdoc."')                                                                     ";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
   {
	 $this->io_sql->rollback();
	 $this->is_msg_error="CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_insert_beneficiario; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 $lb_valido=false;
   }
  else
   {   
	 $this->io_sql->commit();
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	 $ls_evento="INSERT";
	 $ls_sql = str_replace("'",'`',$ls_sql);
	 $ls_descripcion ="Insertó en RPC al Beneficiario ".$ls_cedula." con ".$ls_sql;
	 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	 $aa_seguridad["ventanas"],$ls_descripcion);
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////   			  
	 $lb_valido=true;   
   }	  	
return $lb_valido;	
}
	
function uf_update_beneficiario($as_codemp,$ar_datos,$aa_seguridad)
{  
//////////////////////////////////////////////////////////////////////////////
//	Metodo       uf_update_beneficiario
//	Access       public
//	Arguments    $as_codemp,$ar_datos,$aa_seguridad
//	Returns	     $lb_valido.  Retorna una variable booleana    
//	Description  Funcion que se encarga de actualizar en la tabla de Beneficiarios
//////////////////////////////////////////////////////////////////////////////
	   
  $ls_cedula       = trim($ar_datos["cedula"]);
  $ls_nombre       = $ar_datos["nombre"];
  $ls_apellido     = $ar_datos["apellido"];
  $ls_direccion    = $ar_datos["direccion"];
  $ls_telefono     = $ar_datos["telefono"];
  $ls_celular      = $ar_datos["celular"];
  $ls_email        = $ar_datos["email"];
  $ls_contable     = $ar_datos["contable"];
  $ls_contablerecdoc     = $ar_datos["contablerecdoc"];
  $ls_cuenta       = $ar_datos["cuenta"];
  $ls_banco        = $ar_datos["banco"];
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  $ls_tipocuenta   = $ar_datos["cmbtipcue"];
  $ls_rifben       = trim($ar_datos["tipperrif"].'-'.$ar_datos["numpririf"].'-'.$ar_datos["numterrif"]);
  $ls_codbansig    = $ar_datos["codbancof"];
  $ls_nacben       = $ar_datos["nacionalidad"];
  $ls_numpasben    = $ar_datos["numpasben"];
  $ls_tipconben    = $ar_datos["tipconben"];
  if ($ls_tipconben=='-'){$ls_tipconben='O';}
  
  $ls_sql=" UPDATE rpc_beneficiario ". 
		  " SET    nombene='".$ls_nombre."',     apebene='".$ls_apellido."',    ".
		  "        dirbene='".$ls_direccion."',  telbene='".$ls_telefono."',    ". 
		  "        celbene='".$ls_celular."',    email='".$ls_email."',         ". 
		  "        sc_cuenta='".$ls_contable."', ctaBan='".$ls_cuenta."',       ". 
		  "        codban='".$ls_banco."',       codtipcta='".$ls_tipocuenta."',".
		  "        codpai='".$ls_pais."',        codest='".$ls_estado."',       ". 
		  "        codmun='".$ls_municipio."',   codpar='".$ls_parroquia."',    ". 
		  "        rifben='".$ls_rifben."',      codbansig='".$ls_codbansig."', ".
		  "        nacben='".$ls_nacben."',      numpasben='".$ls_numpasben."', ".
 		  "        tipconben='".$ls_tipconben."', sc_cuentarecdoc='".$ls_contablerecdoc."'                                ". 
		  " WHERE  codemp='".$as_codemp."'  AND  ced_bene='".$ls_cedula."'      ";
 $this->io_sql->begin_transaction();             
 $rs_data=$this->io_sql->execute($ls_sql);
 if ($rs_data===false)
   {                              
	 $this->io_sql->rollback();
	 $this->is_msg_error="CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_update_beneficiario; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 $lb_valido=false;
   }
 else
   {   
	 $this->io_sql->commit();
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	 $ls_evento="UPDATE";
     $ls_sql = str_replace("'",'`',$ls_sql);
	 $ls_descripcion ="Actualizó en RPC al Beneficiario".$ls_cedula." con ".$ls_sql;
	 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	 $aa_seguridad["ventanas"],$ls_descripcion);
	 /////////////////////////////////         SEGURIDAD               /////////////////////////// 			     
	 $lb_valido=true;
   }	  	
return $lb_valido;
}

function uf_select_beneficiario($as_codemp,$as_cedben)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_beneficiario
//	Access       public     
//	Arguments    $as_codemp,$as_cedben
//	Returns	     $lb_valido. Retorna una variable booleana
//	Description  Busca un registro dentro de la tabla rpc_beneficiario en 
//              la base de datos y retorna una variable booleana de que existe 
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = "SELECT ced_bene FROM rpc_beneficiario WHERE codemp='".$as_codemp."' AND ced_bene='" .$as_cedben."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_msg_message("CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_update_beneficiario; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {			
		 $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {   
		      $lb_valido=true;                            
              $this->io_sql->free_result($rs_data);	 	
		    }  
	 }
return $lb_valido;
}

function uf_delete_beneficiario($as_codemp,$as_cedben,$aa_seguridad)
{   
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_beneficiario
//	          Access:  public
//	       Arguments: 
//        $as_codemp:
//        $as_cedben:
//     $aa_seguridad: 
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar los datos de una modalidad en la tabla soc_modalidadclausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

	$lb_valido   = false;
	$lb_existe   = $this->uf_select_beneficiario($as_codemp,$as_cedben);
	$lb_relacion = $this->uf_check_relaciones($as_codemp,$as_cedben);
	if(($lb_existe)&&(!$lb_relacion))
	  {
	    $ls_sql=" DELETE FROM rpc_beneficiario WHERE codemp='".$as_codemp."' AND ced_bene='".$as_cedben."'";
 	    $this->io_sql->begin_transaction();
	    $rs_data=$this->io_sql->execute($ls_sql);
	    if ($rs_data===false)
	       { 
		     $this->io_sql->rollback();
		     $this->is_msg_error="CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_delete_beneficiario; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		     $lb_valido=false;
	       }
	    else
	       {
		     $lb_valido = true;
		     $this->io_sql->commit();
		     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="DELETE";
			 $ls_descripcion ="Eliminó en RPC al Beneficiario ".$as_cedben;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////// 	  	     
	       }
	}	   
return $lb_valido;
}	                         

function uf_select_banco($as_codemp)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_banco
 //	Access       public
 //	Arguments    $as_codemp
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los bancos registrados para dicho 
 //              codigo de empresa.
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM scb_banco WHERE codemp='".$as_codemp."'ORDER BY nomban ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	  {
		$lb_valido=true;
	  }
   else
	 {
	   $lb_valido=false;
	   if ($this->io_sql->message!="")
		  {                              
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }           
	 }	
   if ($lb_valido)
	  {
		return $rs_data;         
	  }
}

function uf_select_tipo_cuenta()
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_tipo_cuenta
 //	Access       public
 //	Description  Devuelve un resulset con todos los tipos de 
 //              cuentas 
 //////////////////////////////////////////////////////////////////////////////

	$ls_sql=" SELECT * FROM scb_tipocuenta ORDER BY codtipcta ";
	$rs_data=$this->io_sql->select($ls_sql);
	$li_numrows=$this->io_sql->num_rows($rs_data);	   
	if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
	else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
	if($lb_valido)
	{
	  return $rs_data;         
	}
}
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////           UBICACIÓN GEOGRÁFICA          ////////////////////////////////////////////////////   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_select_pais()
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_pais
 //	Access       public
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los paises de la tabla sigesp_pais.*/	
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM sigesp_pais ORDER BY despai ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}

function uf_select_estado($as_codpai)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_estado
 //	Access       public
 //	Arguments    $as_codpai
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los estados asociados a un pais 
 //              en específico de la tabla sigesp_estados.	
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM sigesp_estados WHERE codpai='".$as_codpai."' ORDER BY desest ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}

function uf_select_municipio($as_codpai,$as_codest)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion      uf_select_municipio
 //	Access       public
 //	Arguments    $as_codpai,$as_codest
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los municipios asociados a 
 //              un pais y un estado en específico de la tabla sigesp_municipio.	
 //
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM sigesp_municipio WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' ORDER BY denmun";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}

function uf_select_parroquia($as_codpai,$as_codest,$as_codmun)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion      uf_select_parroquia
 //	Access       public
 //	Arguments    $as_codpai,$as_codest,$as_codmun
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todas las parroquias asociadas a un pais,
 //              estado,municipio en específico de la tabla sigesp_parroquia.
 //
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * ".
		   " FROM sigesp_parroquia ". 
		   " WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' ".
		   " AND codmun='".$as_codmun."' ". 
		   " ORDER BY codpar ";

   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}
	
function uf_check_relaciones($as_codemp,$as_cedben)
{
	$ls_sql  = "SELECT numsol FROM sep_solicitud WHERE codemp='".$as_codemp."' AND ced_bene='".$as_cedben."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
	     if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_valido=true;
			  $this->is_msg_error="El Beneficiario no puede ser eliminado, posee registros asociados a otras tablas !!!";
		    }
		 else
		    {
			  $ls_sql  = "SELECT ced_bene FROM cxp_rd WHERE codemp='".$as_codemp."' AND ced_bene='".$as_cedben."'";
	          $rs_data = $this->io_sql->select($ls_sql);
			  if ($rs_data===false)
	             {
				   $lb_valido=false;
				   $this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
			     }
			  else
			     {
	               if ($row=$this->io_sql->fetch_row($rs_data))
		              {
					    $lb_valido=true;
					    $this->is_msg_error="El Beneficiario no puede ser eliminado, posee registros asociados a otras tablas !!!";
				  	  }
				   else
					  {
				  	    $lb_valido = false;
					    $this->is_msg_error="Registro no encontrado !!!";
					  }
	             }
	        }
	   }
	return $lb_valido;	
}	

function uf_load_personal($as_codemp,$as_cedula1,$as_cedula2,$as_orden,&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_load_personal
//	          Access:  public
//	       Arguments:  $lb_valido = Variable booleana que retorna valido=true si la sentencia sql fue ejecuta con exito,
//                     en caso contrario $lb_valido=false.
//	         Returns:  rs_data. Retorna una resulset con el personal a transferir.
//	     Description:  Devuelve un resulset con todas las personas que se encuentran activas en la tabla sno_personal,
//                     para luego ser transferidas al modulo de Proveedores y beneficiarios en la tabla de rpc_beneficiario.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  02/02/2007       Fecha Última Actualización:02/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT cedper, nomper, apeper ".
				"  FROM sno_personal ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND estper='1' ".
				"   AND cedper >= '".$as_cedula1."'".
				"   AND cedper <= '".$as_cedula2."'".
				"   AND cedper NOT IN (SELECT ced_bene FROM rpc_beneficiario WHERE codemp = '".$as_codemp."')".
				" ORDER BY cedper ASC ";


	 $rs_data = $this->io_sql->select($ls_sql);
     if ($rs_data===false)
	    {
		  $lb_valido = false;
		  $this->io_msg->message("CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_load_personal; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
     else
	    {
		  $li_numrows = $this->io_sql->num_rows($rs_data);
		  if ($li_numrows<=0)
		     {
			   $lb_valido = false;
			 }
		}
     return $rs_data;
 }
 
function uf_load_datos_personal($as_codemp,$as_cedula)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_load_datos_personal
//	          Access:  public
//	       Arguments:  $as_codemp = Código de la empresa.
//                     $as_cedula = Cédula o Código del personal a encontrar su informacion.
//	         Returns:  rs_data. Retorna una resulset con el personal a transferir.
//	     Description:  Función que carga toda la información de un personal en una estructura de datos tipo resulset.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  02/02/2007       Fecha Última Actualización:02/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql    = "SELECT codemp,cedper,codpai,codest,codmun,codpar,coreleper,nacper,nomper,apeper,dirper,telhabper,telmovper, ".
  			   "	   (SELECT MAX(codban) ".
			   "          FROM sno_personalnomina ".
			   "         WHERE sno_personalnomina.codemp = sno_personal.codemp ".
			   "		   AND sno_personalnomina.codper = sno_personal.codper ".
			   "		 GROUP BY sno_personalnomina.codper) AS codban, ".
  			   "	   (SELECT MAX(codcueban) ".
			   "          FROM sno_personalnomina ".
			   "         WHERE sno_personalnomina.codemp = sno_personal.codemp ".
			   "		   AND sno_personalnomina.codper = sno_personal.codper ".
			   "		 GROUP BY sno_personalnomina.codper) AS ctaban ".
               "  FROM sno_personal ".
			   " WHERE codemp='".$as_codemp."' ".
			   "   AND cedper='".$as_cedula."' ";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->is_msg_error="CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_load_datos_personal; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 }
  return $rs_data;
}

function uf_insert_personal($as_codemp,$ar_datos,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_insert_personal
//	          Access:  public
//	       Arguments:  $as_codemp   : Código de la empresa.
//                     $ar_datos    : Arreglo cargado con la información de interés del personal a insertar como beneficiario.
//                     $aa_seguridad: Arreglo cargado con la información de nombre de la pantalla, nonmbre del usuario,etc.
//	         Returns:  $lb_valido = Variable booleana que retorna valido=true si la sentencia sql fue ejecuta con exito,
//                     en caso contrario $lb_valido=false.
//	     Description:  Función que se encarga de realizar la insercion del personal encontrado en sno_personal que no está
//                     registrado como beneficiario en la tabla rpc_beneficiario.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  05/02/2007       Fecha Última Actualización:05/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $ls_cedula       = $ar_datos["cedula"];
  $ls_nombre       = $ar_datos["nombre"];
  $ls_apellido     = $ar_datos["apellido"];
  $ls_direccion    = $ar_datos["direccion"];
  $ls_telefono     = $ar_datos["telefono"];
  $ls_celular      = $ar_datos["celular"];
  $ls_email        = $ar_datos["email"];
  $ls_contable     = $ar_datos["contable"];
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  $ls_nacben       = $ar_datos["nacionalidad"];
  $ls_tipconben    = $ar_datos["tipconben"];
  $ls_bansigcof    = '---';
  $ls_codban       = trim($ar_datos["codban"]); 
  if($ls_codban=="")
  {
  	  $ls_codban       = '---';
  }
  $ls_ctaban       = $ar_datos["ctaban"];				     				   
  
  
  if ($ls_tipconben=='-'){$ls_tipconben='F';}
  $this->uf_load_and_insert_codbansig();
  $ls_sql=" INSERT INTO rpc_beneficiario(codemp,ced_bene,nombene,apebene,dirbene,telbene,celbene,email,sc_cuenta,                ".
          "             codpai,codest,codmun,codpar,nacben,tipconben,codbansig,codban,ctaban)                                                            ". 
		  "  VALUES('".$as_codemp."','".$ls_cedula."','".$ls_nombre."','".$ls_apellido."','".$ls_direccion."','".$ls_telefono."',".
		  " '".$ls_celular."','".$ls_email."','".$ls_contable."','".$ls_pais."','".$ls_estado."','".$ls_municipio."',            ".
		  "'".$ls_parroquia."','".$ls_nacben."','".$ls_tipconben."','".$ls_bansigcof."','".$ls_codban."','".$ls_ctaban."')                                                             ";
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
	   $this->is_msg_error="CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_insert_personal; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	   $lb_valido=false;
     }
  else
     {   
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "INSERT";
	   $ls_sql         = str_replace("'",'`',$ls_sql);
	   $ls_descripcion = "Insertó en RPC al Personal ".$ls_cedula." como beneficiario ".$ls_sql;
	   $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////   			  
	  $lb_valido=true;   
     }	  	
  return $lb_valido;	
}

function uf_load_and_insert_codbansig()
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_load_and_insert_codbansig
//	          Access:  public
//	         Returns:  $lb_valido = Variable booleana que retorna valido=true si la sentencia sql fue ejecuta con exito,
//                     en caso contrario $lb_valido=false.
//	     Description:  Función que se encarga de realizar una consulta para verificar que exista el Codigo del Banco SIGECOF por defecto,
//                     si no es encontrado el registro, la función realiza la inserción del mismo.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  05/02/2007       Fecha Última Actualización:05/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql  = "SELECT codbansig FROM sigesp_banco_sigecof WHERE codbansig='---'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_load_and_insert_codbansig(SELECT); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
     {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if ($li_numrows<=0)
	      {
		    $ls_sql   = "INSERT INTO sigesp_banco_sigecof (codbansig, denbansig) VALUES ('---','---seleccione---')";
		    $rs_datos = $this->io_sql->execute($ls_sql);
			if ($rs_datos===false)
			   {
	             $this->io_msg->message("CLASE->SIGESP_RPC_C_BENEFICIARIO; METODO->uf_load_and_insert_codbansig(INSERT); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	             $lb_valido=false;
			   }
		  }
	 } 
  return $lb_valido;
} 
//------------------------------------------------------------------------------------------------------------------------------------

    function uf_buscar_beneficiario(&$as_valor,$ai_inicio,$ai_registros,&$ai_totpag, &$as_ced_benedes, &$as_ced_benehas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_beneficiario
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: 
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/11/2008								Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_pag="";			
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;			
		}			
		$ls_sql=" SELECT rpc_beneficiario.ced_bene,   ".
				"      (SELECT count (rpc_beneficiario.ced_bene) FROM rpc_beneficiario WHERE ced_bene <> '----------') as valor1 ".
			    "  FROM rpc_beneficiario		    ".
				"  WHERE ced_bene <> '----------' ".
			    "  ORDER BY rpc_beneficiario.ced_bene ".$ls_pag;	    
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numero=$this->io_sql->num_rows($rs_data);
		$li=1;	
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$as_valor=$row["valor1"]; 
			if ($li==1)
			{
				$as_ced_benedes=$row["ced_bene"];
				$li=0;
			}			
			$li_numero=$li_numero-1;
			if ($li_numero==0)	
			{
				$as_ced_benehas=$row["ced_bene"]; 
			}
											
		}				
		$ai_totpag = ceil($as_valor / $ai_registros); 
		return $lb_valido;
	}// fin uf_buscar_beneficiario
//-----------------------------------------------------------------------------------------------------------------------------------    
   
}
?>