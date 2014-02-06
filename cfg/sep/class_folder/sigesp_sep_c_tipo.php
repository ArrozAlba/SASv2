<?php
class sigesp_sep_c_tipo
{
var $ls_sql;
	
	function sigesp_sep_c_tipo($conn)
	{
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  $this->seguridad = new sigesp_c_seguridad();
	  $this->io_sql       = new class_sql($conn);
	  $this->io_msg       = new class_mensajes();		
	  $this->io_funcion   = new class_funciones();
	}

function uf_insert_tiposep($as_codtip,$as_dentip,$as_afepre,$as_esttip,$as_estayu,$aa_seguridad) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function     : uf_insert_tiposep
	//	Access       : public
	//	Arguments    :
	//  as_codtip    = Código del Tipo de SEP.
	//  as_dentip    = Denominación del Tipo de SEP.
	//  aa_seguridad = Arreglo cargado con la información de usuario, ventanas, sistema etc.
	//	Description  : Este método se encarga de insertar un nuevo tipo de SEP en la Tabla 
	//                 sep_tiposolicitud en la base de datos seleccionada .
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $lb_valido=false;	  
	$ls_sql=" INSERT INTO sep_tiposolicitud ". 
			" (codtipsol, dentipsol, estope, modsep,estayueco) ". 
			" VALUES ('".$as_codtip."','".$as_dentip."','".$as_afepre."','".$as_esttip."','".$as_estayu."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_SEP_C_TIPO; METODO->uf_insert_tiposep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
	else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó Tipo de SEP ".$as_codtip;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	   $lb_valido=true;
	 }   
     return $lb_valido;
}

function uf_validar_insert($as_modsep,$as_afepre)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function      : uf_validar_insert
	//	Access        : public
	//	Arguments     : as_modsep     Código del Tipo de SEP.  
	//  Description   : Este método se encarga de verificar si existe otro registro con modalidad Bienes o 
	//                  Servicios. Ya que solo puede existir uno solo registro con modalidad Bienes y un solo
	//                  Registro con modalidad de Servicios, esto se realiza para evitar que haya un conflicto
	//                  en el modulo de compras, especialmente en la busqueda de la SEP (en el modulo de compras)                     
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////          		 
    $lb_valido=false;
    $ls_sql=" SELECT * FROM sep_tiposolicitud WHERE modsep='".$as_modsep."' AND estope='".$as_afepre."'";		     
    $rs=$this->io_sql->select($ls_sql);
    if ($rs===false)
    {
	    $lb_valido=false;
    } 
    else
    {
      $li_numrows=$this->io_sql->num_rows($rs);          
	  if ($li_numrows>=1)
      {
	     $lb_valido=true;				
		 $this->io_sql->free_result($rs);  
      }
   }  
   return $lb_valido;
}

function uf_validar_codigo($as_modsep,$as_codigo)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function      : uf_validar_codigo
//	Access        : public
//	Arguments     :
//  as_modsep     = Código del Tipo de SEP.  
//  Description   : Este metodo se encarga de validar que el tipo de sep de Bienes o Servicios
//                  sea el mismo codigo a actualizar, ya que no puede haber mas de un sep de 
//                  Bienes o Servicios.                     
/////////////////////////////////////////////////////////////////////////////////////////////////////////          		 
   $lb_valido = false;
   $ls_sql    = "SELECT codtipsol FROM sep_tiposolicitud WHERE modsep='".$as_modsep."'";		     
   $rs_data   = $this->io_sql->select($ls_sql);
   if ($rs_data===false)
	  {
	    $lb_valido=false;
        $this->io_msg->message("CLASE->SIGESP_SEP_C_TIPO; METODO->uf_validar_codigo; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  } 
    else
	  {
	    if ($row=$this->io_sql->fetch_row($rs_data))
		   { 		   
		     $ls_codigo=$row["codtipsol"];              
			 if ($ls_codigo==$as_codigo)
				{
				  $lb_valido = true;
				}
		   }			   
	 }  
  return $lb_valido;
}

function uf_update_tiposep($as_codtip,$as_dentip,$as_afepre,$as_esttip,$as_estayu,$aa_seguridad) 
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function     : uf_update_tiposep
//	Access       : public
//	Arguments    :  $as_codtip,$as_dentip,$aa_seguridad
//  as_codtip    = Código del Tipo de SEP.
//  as_dentip    = Denominación del Tipo de SEP.
//  aa_seguridad = Arreglo cargado con la información de usuario, ventanas, sistema etc. 
//	Description  : Este método se encarga de actualizar un registro ya existente de la Tabla 
//                 SEP_TipoSolicitud en la base de datos.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $ls_sql=" UPDATE  sep_tiposolicitud                                                            ". 
			  " SET     dentipsol='".$as_dentip."', estope='".$as_afepre."', modsep='".$as_esttip."', estayueco='".$as_estayu."' ". 
			  " WHERE   codtipsol='" .$as_codtip. "'";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false;
           $this->io_msg->message("CLASE->SIGESP_SEP_C_TIPO; METODO->uf_update_tiposep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion ="Actualizó Tipo de SEP ".$as_codtip;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		   $lb_valido=true;
		 }
return $lb_valido;
} 

function uf_delete_tiposep($as_codtip,$aa_seguridad)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	    Function:  uf_delete_tiposep
//	      Access:  public
//	   Arguments:  $as_codtip,$aa_seguridad
//     as_codtip:  Código del Tipo de SEP.  
// $aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc.
//   Description:  Este método se encarga de eliminar un registro ya existente de la Tabla sep_tiposolicitud en la base de datos.
/////////////////////////////////////////////////////////////////////////////////////////////////////////       

	$lb_valido = false;  
	$ls_sql    = " DELETE FROM sep_tiposolicitud WHERE codtipsol='".$as_codtip."'";	
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data==false)
	   {
	     $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_SEP_C_TIPO; METODO->uf_delete_tiposep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="DELETE";
		 $ls_descripcion ="Eliminó Tipo de SEP ".$as_codtip;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////
		 $lb_valido=true;
	   } 		 
	return $lb_valido;
}

function uf_select_tiposep($as_codtip) 
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//     Function:  uf_select_tiposep
//	     Access:  public
// 	  Arguments:  $as_codtip
//    as_codtip:  Código del tipo de SEP.
//	Description:  Este método se encarga de localizar un registro en la Tabla SEP_TipoSolicitud.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
  $lb_valido = false;
  $ls_sql    = "SELECT * FROM sep_tiposolicitud WHERE codtipsol='".$as_codtip."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	 }
  else
	 {
       $li_numrows = $this->io_sql->num_rows($rs_data);            
	   if ($li_numrows>0)
	      {
		    $lb_valido=true;
		    $this->io_sql->free_result($rs_data);
	      }
	 }
  return $lb_valido;
}
}// Fin de la Clase sigesp_sep_c_tipo.
?> 