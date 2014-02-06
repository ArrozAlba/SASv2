<?php
class sigesp_rpc_c_proxdoc
 {
    var $ls_sql="";
	var $la_emp;

	function sigesp_rpc_c_proxdoc()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
	    $this->seguridad = new sigesp_c_seguridad();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funcion = new class_funciones();
		$io_conect=new sigesp_include();
		$conn=$io_conect->uf_conectar();
		$this->la_emp=$_SESSION["la_empresa"];
		$this->io_sql=new class_sql($conn);
		$_SESSION["gestor"]="MYSQL";
		$this->gestor=$_SESSION["gestor"];
		$this->io_msg= new class_mensajes();
	}

  
function ue_guardar($as_codemp,$as_codprov,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
 //	Funcion : ue_guardar
 //	Access:  public
 //	Arguments:  $as_codemp,$as_codprov,$ar_datos,$aa_seguridad
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 ////////////////////////////////////////////////////////////////////////////// 
  
  $ls_coddoc=$ar_datos["codigo"];
  $ls_fecrec=$ar_datos["fecrec"];
  $ls_fecven=$ar_datos["fecven"];
  $ls_estdoc=$ar_datos["documento"];
  $ls_estori=$ar_datos["original"];
  if($this->uf_existe_proxdoc($as_codemp,$ls_coddoc,$as_codprov))
  {
	$ls_sql=" UPDATE rpc_docxprov ".
			" SET fecrecdoc='".$ls_fecrec."',fecvendoc='".$ls_fecven."',estdoc='".$ls_estdoc."',".
			" estorig='".$ls_estori."' ".
			" WHERE codemp='".$as_codemp."' AND  coddoc='".$ls_coddoc."' AND  cod_pro='".$as_codprov."'";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		 $this->io_sql->rollback();
		 $this->io_msg->message('Error en Actualización !!!');
		 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 $this->io_sql->commit();
		 $this->io_msg->message("Registro Actualizado !!!"); 
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
   	     $ls_evento="UPDATE";
	     $ls_descripcion ="Actualizó Documentos por Proveedor ".$ls_coddoc." al Proveedor ".$as_codprov;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	   }
  }
  else
  {
	$ls_sql=" INSERT INTO rpc_docxprov ".
			" (codemp,coddoc,cod_pro,fecrecdoc,fecvendoc,estdoc,estorig)".
			" VALUES".
			" ('".$as_codemp."','".$ls_coddoc."','".$as_codprov."','".$ls_fecrec."','".$ls_fecven."','".$ls_estdoc."','".$ls_estori."')";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		 $this->io_sql->rollback();
		 $this->io_msg->message('Error en Inclusión !!!');
		 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 $this->io_sql->commit();
		 $this->io_msg->message('Registro Incluido !!!'); 
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
   	     $ls_evento="INSERT";
	     $ls_descripcion ="Insertó Documentos por Proveedor ".$ls_coddoc." al Proveedor ".$as_codprov;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	   }	  	
   }	
$this->io_sql->close();
}


function uf_existe_proxdoc($as_codemp,$ls_coddoc,$as_codprov)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion : uf_existe_proxdoc
 //	Access:  public
 //	Arguments:  $as_codemp,$ls_coddoc,$as_codprov
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 ////////////////////////////////////////////////////////////////////////////// 

	$ls_sql=" SELECT * ".
			" FROM rpc_docxprov ".
			" WHERE codemp='".$as_codemp."' AND coddoc='" .$ls_coddoc."' AND cod_pro='" .$as_codprov."'";
	$rs_proxdoc=$this->io_sql->select($ls_sql);
    if ($rs_proxdoc===false)
		{
		   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   $lb_valido=false;
		}
		else
		{
		   $li_numrows=$this->io_sql->num_rows($rs_proxdoc);
		   if($li_numrows>0)
			 {
			   $lb_valido=true;
			   $this->io_sql->free_result($rs_proxdoc);			
			 }
			 else
			 {
			   $lb_valido=false;
			 }
		}
return $lb_valido;
}


function ue_eliminar($as_codemp,$ls_coddoc,$as_codprov,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion : ue_eliminar
 //
 //	Access:  public
 //
 //	Arguments:  $as_codemp,$ls_coddoc,$as_codprov,$aa_seguridad
 //
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //           
 //
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 //
 ////////////////////////////////////////////////////////////////////////////// 

	$ls_sql="";
	$lb_valido=true;
	$ls_sql=" DELETE ".
			" FROM rpc_docxprov ".
			" WHERE codemp='".$as_codemp."' AND coddoc='".$ls_coddoc."' AND cod_pro='".$as_codprov."'";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   { 
		 $this->io_sql->rollback();
		 $this->io_msg->message('Error en Eliminación !!!');
		 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	  else
	  {
		 $this->io_sql->commit();
		 $this->io_msg->message('Registro Eliminado !!!'); 
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
   	     $ls_evento="DELETE";
	     $ls_descripcion ="Eliminó Documentos por Proveedor ".$ls_coddoc." al Proveedor ".$as_codprov;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	  }
$this->io_sql->close();
return $lb_valido;
}	 


function uf_existe_proveedor($as_codemp,$as_codpro)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion : uf_existe_proveedor
 //
 //	Access:  public
 //
 //	Arguments:  $as_codemp,$as_codpro
 //
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //           
 //
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 //
 //////////////////////////////////////////////////////////////////////////////

	$ls_sql="";
	$lb_valido=false;
	$ls_sql=" SELECT * ".
			" FROM rpc_proveedor ".
			" WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";

	$rs_proveedor=$this->io_sql->select($ls_sql);
	$li_numrows=$this->io_sql->num_rows($rs_proveedor);
	if ($li_numrows===false)
	   {
		  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	else
	   {
			if($li_numrows>0)
			{
			  $lb_valido=true;
			  	$this->io_sql->free_result($rs_proveedor);
			}
			else
			{
			  $lb_valido=false;
			}
	   }
return $lb_valido;
}
}//Fin de la Clase...
?>