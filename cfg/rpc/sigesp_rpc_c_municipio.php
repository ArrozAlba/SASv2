<?php
class sigesp_rpc_c_municipio
{

var $ls_sql;
	
		function sigesp_rpc_c_municipio($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		  require_once("../../shared/class_folder/class_funciones.php");
  		  require_once("../../shared/class_folder/class_mensajes.php");
	      $this->seguridad = new sigesp_c_seguridad();          
		  $this->io_funcion = new class_funciones();
		  $this->io_sql= new class_sql($conn);		
		  $this->io_msg= new class_mensajes(); 
		}
 

function uf_insert_municipio($as_codpais,$as_codest,$as_codmun,$as_denmun,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_insertmunicipio
//	    Access:  public
//	 Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de insertar un municipio para un estado en específico. 
//////////////////////////////////////////////////////////////////////////////

		  $ls_sql = " INSERT INTO sigesp_municipio (codpai,codest,codmun,denmun) ".
                    " VALUES ('".$as_codpais."','".$as_codest."','".$as_codmun."','".$as_denmun."')";
		  $this->io_sql->begin_transaction();
		  $li_numrows=$this->io_sql->execute($ls_sql);
		  if ($li_numrows===false)
		     {
               $lb_valido=false;
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
			   $ls_descripcion ="Insertó en RPC el Municipio ".$as_denmun." con código ".$as_codmun.
			                    " asociado al Estado ".$as_codest;
			   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			   $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               ///////////////////////////
               $lb_valido=true;
			 }
return $lb_valido;
}


function uf_update_municipio($as_codpais,$as_codest,$as_codmun,$as_denmun,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_update_municipio
//	    Access:  public
//	 Arguments:  $as_codpais,$as_codest,$as_codmun,$as_denmun,$aa_seguridad
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de actualizar los datos de un municipio en la tabla sigesp_municipio . 
//////////////////////////////////////////////////////////////////////////////

	  $ls_sql=" UPDATE sigesp_municipio ".
			  " SET  denmun='".$as_denmun."' ".
			  " WHERE codpai='".$as_codpais."' AND codest='".$as_codest."' ".
			  " AND codmun='".$as_codmun."'";

	  $this->io_sql->begin_transaction();
	  $li_numrows=$this->io_sql->execute($ls_sql);
	  if ($li_numrows===false)
		 {
		   $lb_valido=false;
		   $this->io_sql->rollback();
		   $this->io_msg->message('Error en Actualización !!!');
		   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   $this->io_sql->commit();
		   $this->io_msg->message('Registro Actualizado !!!');
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion ="Actualizó en RPC el Municipio ".$as_denmun." con código ".$as_codmun.
							" asociado al Estado ".$as_codest;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////
		   $lb_valido=true;
		 }
return $lb_valido;
} 




function uf_delete_municipio($as_codpais,$as_codest,$as_codmun,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_delete_municipio
//	    Access:  public
//	 Arguments:  $as_codpais,$as_codest,$as_codmun,$aa_seguridad
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de eliminar un municipio en la tabla sigesp_municipio . 
//////////////////////////////////////////////////////////////////////////////

	   $ls_sql=" DELETE ".
			   " FROM sigesp_municipio ".
			   " WHERE codpai='".$as_codpais."' AND codest='".$as_codest."' AND".
			   "       codmun='".$as_codmun."'";
	  $this->io_sql->begin_transaction();
	  $li_numrows=$this->io_sql->execute($ls_sql);
	  if ($li_numrows===false)
		 {
		   $lb_valido=false;
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
		   $ls_descripcion ="Eliminó en RPC el Municipio con código ".$as_codmun.
							" asociado al Estado ".$as_codest;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   								$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   								$aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////
		   $lb_valido=false;
		 } 		 
return $lb_valido;
}


function uf_select_municipio($as_codpais,$as_codest,$as_codmun) 
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_select_municipio
//	    Access:  public
//	 Arguments:  $as_codpais,$as_codest,$as_codmun
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de verificar si existe o no un municipio para un estado específico
//               la funcion devuelve true en caso de que exista, caso contrario devuelve false. 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql=" SELECT * ".
			" FROM sigesp_municipio ".
			" WHERE codpai='".$as_codpais."' AND codest='".$as_codest."' AND codmun='".$as_codmun."'" ;
	$rs_municipio=$this->io_sql->select($ls_sql);
	if ($rs_municipio===false)
	   {
	     $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
		$li_numrows=$this->io_sql->num_rows($rs_municipio);
		if($li_numrows>0)
			{					 
			  $lb_valido=true;
			}
			else
			{					 
			  $lb_valido=false;
			}
	   }
	return $lb_valido;
 }


  
function uf_llenarcombo_pais()
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_llenarcombo_pais
//	    Access:  public
//	   Returns:	 $lb_valido.	
// Description:  Funcion que se encarga de extraer todos aquellos registro de la tabla sigesp_pais
//               para ser cargados en un objeto de tipo combobox/list menu. 
//////////////////////////////////////////////////////////////////////////////

		$ls_sql=" SELECT * ".
                " FROM sigesp_pais ".
                " ORDER BY CodPai ASC";
		$rs=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs);	   
        if ($li_numrows>0)
        {
	       $lb_valido=true;
        }
        else
        {  
  	       $lb_valido=false;
           if($this->io_sql->message!="")
           {                              
               $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
           }           
	    }	
        if($lb_valido)
        {
          return $rs;         
        }
	}


function uf_llenarcombo_estado($as_codpais)
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_llenarcombo_estado
//	    Access:  public
//	   Returns:	 $lb_valido.	
// Description:  Funcion que se encarga de extraer todos aquellos registro de la tabla sigesp_estado
//               para ser cargados en un objeto de tipo combobox/list menu. 
//////////////////////////////////////////////////////////////////////////////

		$ls_sql=" SELECT * ".
                " FROM sigesp_estados ".
                " WHERE codpai='".$as_codpais."' ".
                " ORDER BY desest ASC ";
		$rs=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs);	   
        if ($li_numrows>0)
        {
	       $lb_valido=true;
        }
        else
        {
  	       if($this->io_sql->message!="")
           { 
               $lb_valido=false;                             
               $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
           }           
           else
           {
             $lb_valido=false;
           }    
	    }	
        if ($lb_valido)
           {
             return $rs;         
           } 
	}

//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_generar_codigo
//	Access:  public
//	Arguments:
// ab_empresa   // Si usara el campo empresa como filtro      
// as_codemp    // codigo de la empresa
// as_tabla     // Nombre de la tabla 
// as_campo     // nombre del campo que desea incrementar
// ai_length    // longitud del campo
//	Returns:		ls_codigo   // representa el codigo incrementado o generado
//	Description:  Este método genera el numero consecutivo del código de
//                cualquier tabla deseada
//////////////////////////////////////////////////////////////////////////////
function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna,$as_columna2,$as_columna3)
{ 
 	$lb_existe=$this->existe_tabla($as_tabla);
	if ($lb_existe)
	   {
	      $lb_existe=$this->existe_columna($as_tabla,$as_columna);
		  if ($lb_existe)
		  {
			   $li_longitud=$this->longitud_campo($as_tabla,$as_columna) ;
			   if ($ab_empresa)
			   {	
					  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE CodEmp='".$as_codemp."' ORDER BY '".$as_columna."' DESC";		
					  $rs_funciondb=$this->io_sql->select($ls_sql);
					  if ($row=$this->io_sql->fetch_row($rs_funciondb))
					  { 
						  $codigo=$row[$as_columna];
						  settype($codigo,'int');                             // Asigna el tipo a la variable.
						  $codigo = $codigo + 1;                              // Le sumo uno al entero.
						  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
						  $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
					  }
					  else
					  {
						  $codigo="1";
						  $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
					  }
				}	
				else
				{
					  $ls_sql=" SELECT ".$as_columna." FROM ".$as_tabla." WHERE codpai='".$as_columna2."' AND ".
					          " codest='".$as_columna3."' ".
							  " ORDER BY '".$as_columna."' DESC";		
					  $rs_funciondb=$this->io_sql->select($ls_sql);
					  if ($row=$this->io_sql->fetch_row($rs_funciondb))
					  { 
						   $codigo=$row[$as_columna];
						   settype($codigo,'int');                                          // Asigna el tipo a la variable.
						   $codigo = $codigo + 1;                                           // Le sumo uno al entero.
						   settype($codigo,'string');                                       // Lo convierto a varchar nuevamente.
						   $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud); 
					   }   
					   else
					   {
						   $codigo="1";
						   $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
					   }
				}// SI NO TIENE CODIGO DE EMPRESA
			}
			else
			{
				$ls_codigo="";
				$this->is_msg_error="No existe el campo" ;
			}
	 }
	 else
	{
		$ls_codigo="";
		$this->is_msg_error="No existe la tabla	" ;
	}
  return $ls_codigo;
}


/*DETERMINAR LONGITUD DE CAMPOS TABLA*/
function longitud_campo($as_tabla,$as_columna)
{
	$ls_sql="SELECT character_maximum_length AS width FROM information_schema.columns WHERE UPPER(table_name)=UPPER('".$as_tabla."') 
			 AND UPPER(column_name)=UPPER('".$as_columna."')";
	$rs_funciondb=$this->io_sql->select($ls_sql);
	if ($row=$this->io_sql->fetch_row($rs_funciondb))
	    {
		  $longitud=$row["width"];
		} 
	return $longitud;
}

/*CHEQUEA SI EXISTE UNA TABLA*/
function existe_tabla($as_tabla)
{
  $ls_sql="SELECT * FROM  INFORMATION_SCHEMA.TABLES WHERE (UPPER(TABLE_NAME)=UPPER('".$as_tabla."'))";
  $rs_funciondb=$this->io_sql->select($ls_sql);
  if ($row=$this->io_sql->fetch_row($rs_funciondb))
	 {
	   $lb_existe=true;
	 } 
	 else
	 {
	   $lb_existe=false;
	 }
	return $lb_existe;
}

/*CHEQUEA SI EXISTE LA COLUMNA*/
	function existe_columna($as_tabla,$as_columna)
	{
	  $ls_sql="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE UPPER(TABLE_NAME)=UPPER('".$as_tabla."') 
	           AND UPPER(COLUMN_NAME)=UPPER('".$as_columna."')";
	  $rs_funciondb=$this->io_sql->select($ls_sql);
      if ($row=$this->io_sql->fetch_row($rs_funciondb))
	     {
	       $lb_existe=true;
	     } 
	  return $lb_existe;
	}
}//Fin de la Clase...
?>