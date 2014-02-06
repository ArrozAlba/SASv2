<?php
class sigesp_soc_c_anulacion_orden_compra
{
  function sigesp_soc_c_anulacion_orden_compra($as_path)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_anulacion_orden_compra
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 09/06/2007 								Fecha Última Modificación : 03/06/2007 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/sigesp_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$io_include			= new sigesp_include();
		$io_conexion		= $io_include->uf_conectar();
		$this->io_sql       = new class_sql($io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();	
		$this->io_seguridad = new sigesp_c_seguridad();
		$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
  }

function uf_load_ordenes_compra($as_numordcom,$ad_fecdes,$ad_fechas,$as_codpro)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_load_ordenes_compra
//         Access: public
//      Argumento: 
//   $as_numordcom //Número de la Orden de Compra (Bien o Servicio.)
//      $ad_fecdes //Fecha desde el cual buscaremos las Ordenes de Compra.
//      $ad_fechas //Fecha hasta el cual buscaremos las Ordenes de Compra.
//      $as_codpro //Código del Proveedor asociado a la Orden de Compra.
//	      Returns: Retorna un resulset
//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulación. 
//	   Creado Por: Ing. Néstor Falcón.
// Fecha Creación: 06/03/2007							Fecha Última Modificación : 09/06/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
  $ls_straux = "";
  if (!empty($ad_fecdes) && !empty($ad_fechas))
     {
	   $ld_fecdes = $this->io_funciones->uf_convertirdatetobd($ad_fecdes);
       $ld_fechas = $this->io_funciones->uf_convertirdatetobd($ad_fechas);
	   $ls_straux = " AND soc_ordencompra.fecordcom BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."'"; 
	 }
  $ls_sql = "SELECT soc_ordencompra.numordcom,soc_ordencompra.cod_pro,	   					".
            "       soc_ordencompra.fecordcom,soc_ordencompra.obscom,						".
			"       COALESCE(soc_ordencompra.numanacot,'-') as numanacot,					".
            "       soc_ordencompra.estcondat,soc_ordencompra.montot,rpc_proveedor.nompro   ".			
		    "  FROM soc_ordencompra , rpc_proveedor											".
			" WHERE soc_ordencompra.codemp='".$this->ls_codemp."'							".
			"   AND soc_ordencompra.numordcom like '%".$as_numordcom."%' 					".
			"   AND soc_ordencompra.cod_pro like '%".$as_codpro."%' 						".
			"   AND soc_ordencompra.estcom='1' 										        ".
			"   AND soc_ordencompra.estapro = '1'											".
			"       $ls_straux																".
			"   AND soc_ordencompra.numordcom <> '000000000000000'							".
			"   AND soc_ordencompra.codemp=rpc_proveedor.codemp             				".
  	  	    "   AND soc_ordencompra.cod_pro=rpc_proveedor.cod_pro           				".
			"ORDER BY soc_ordencompra.numordcom ASC	 			            				";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->sigesp_soc_c_anulacion_orden_compra.MÉTODO->uf_load_ordenes_compra.ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_data;
} // end  function uf_load_ordenes_compra

function uf_update_estatus_orden_compra($ai_totrows,$aa_seguridad)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_update_estatus_orden_compra
//         Access: public
//      Argumento: $ai_totrows = Total de filas dispuestas para su anulación.
//                 $aa_seguridad = Arreglo cargado con la informacion de la pantalla, usuario, entre otros.
//     $as_totrows //Total de Ordenes de Compra.
//	      Returns: Retorna un resulset
//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulación. 
//	   Creado Por: Ing. Néstor Falcón.
// Fecha Creación: 06/03/2007							Fecha Última Modificación : 09/06/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  if ($ai_totrows>0)
     {
	   $this->io_sql->begin_transaction();
	   for ($i=1;$i<=$ai_totrows;$i++)
	       {
			 if (array_key_exists("chk".$i,$_POST))
			    {
			      $ls_numordcom = str_pad($_POST["txtnumord".$i],15,0,0);
                  $ls_codpro    = str_pad($_POST["hidcodpro".$i],10,0,0);
			      $ls_tipordcom = $_POST["txttipordcom".$i];
			      if ($ls_tipordcom=='Bienes')
			         { 
				       $ls_tipordcom = 'B';
			      	 }
			      elseif($ls_tipordcom=='Servicios')
			         {
				       $ls_tipordcom = 'S';
				     }
			      $ld_fecordcom = $_POST["txtfecordcom".$i];
		          $ld_fecordcom = $this->io_funciones->uf_convertirdatetobd($ld_fecordcom);
				  $ls_numanacot = trim($_POST["hidnumanacot".$i]);
				  
			      $ls_sql       = "UPDATE soc_ordencompra 			     ".
			                      "   SET estcom='3'      			     ".
			                      " WHERE codemp='".$this->ls_codemp."'  ".
							      "   AND numordcom='".$ls_numordcom."'  ".
					              "   AND cod_pro='".$ls_codpro."'       ".
								  "   AND estcondat ='".$ls_tipordcom."' ".
							      "   AND fecordcom='".$ld_fecordcom."'  ";
				  $rs_data = $this->io_sql->execute($ls_sql);
				  if ($rs_data===false)
					 {
					   $lb_valido = false;
					   $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_update_estatus_orden_compra.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					   break;
					 }
				  else
					 {
						/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////		
						$ls_descripcion ="Anuló la Orden de Compra Nro. $ls_numordcom de tipo $ls_tipordcom asociada a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],"UPDATE",$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////				   
					   $lb_valido = $this->uf_delete_enlace_sep($ls_numordcom,$ls_codpro,$ls_tipordcom,$ls_numanacot,$aa_seguridad);
					 }
				}
		   }
	    if ($lb_valido)
	       {
		     $this->io_sql->commit();
			 $this->io_mensajes->message("Operación realizada con éxito !!!");
		     $this->io_sql->close();

		   } 
	    else
		   {
		     $this->io_sql->rollback();
			 $this->io_mensajes->message("Error  en Operación !!!");
		     $this->io_sql->close();
		   }
	 }
  return $lb_valido;
}

function uf_delete_enlace_sep($as_numordcom,$as_codpro,$as_tipordcom,$as_numanacot,$aa_seguridad)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_delete_enlace_sep
//         Access: public
//      Argumento: 
//   $as_numordcom //Número de la Orden de Compra (Bien o Servicio.)
//      $as_codpro //Código del Proveedor asociado a la Orden de Compra.
//   $as_tipordcom //Tipo de Orden de Compra (Bien o Servicio).
//	      Returns: Retorna un resulset
//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulación. 
//	   Creado Por: Ing. Néstor Falcón.
// Fecha Creación: 06/06/2007							Fecha Última Modificación : 09/06/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql    = " SELECT numsol ".
               "   FROM soc_enlace_sep ".
		       "  WHERE codemp='".$this->ls_codemp."' AND numordcom='".$as_numordcom."' AND estcondat='".$as_tipordcom."'";
  $rs_datos = $this->io_sql->select($ls_sql);
  if ($rs_datos===false)
     {
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_delete_enlace_sep.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	   echo $this->io_sql->message;
	 }		    
  else
     {
	   while ($row=$this->io_sql->fetch_row($rs_datos))
	         {
			   $ls_numsol = str_pad($row["numsol"],15,0,0);
			   if ($as_numanacot=='-')//Indica que la Orden de Compra no proviene de un Análisis de Cotización.
			      {
				    $lb_valido = $this->uf_update_estatus_incorporacion_item($ls_numsol,$as_numordcom,$as_tipordcom);
				    if ($lb_valido)
					   {
						  if ($as_tipordcom=='B')
							 {
							   $ls_tabla = "sep_dt_articulos";
							 } 
						  elseif($as_tipordcom=='S')
							 {
							   $ls_tabla = "sep_dt_servicio";
							 } 
						 $ls_sql = "SELECT sep_solicitud.numsol 
									  FROM sep_solicitud, $ls_tabla 
									 WHERE sep_solicitud.codemp='".$this->ls_codemp."' 
									   AND sep_solicitud.numsol='".$ls_numsol."'
									   AND $ls_tabla.estincite<>'NI'
							           AND sep_solicitud.codemp=$ls_tabla.codemp
							           AND sep_solicitud.numsol=$ls_tabla.numsol";
						 $rs_data = $this->io_sql->select($ls_sql);
						 if ($rs_data===false)
						    {
						      $lb_valido = false;
						      $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_delete_enlace_sep.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							  echo $this->io_sql->message;
							}					   
					     else
						    {
							  $li_totrows = $this->io_sql->num_rows($rs_data);
							  if ($li_totrows<=0)
							     {
								   $ls_sql  = "UPDATE sep_solicitud SET estsol='C' WHERE codemp='".$this->ls_codemp."' AND numsol='".$ls_numsol."'";		                 
								   $rs_dato = $this->io_sql->execute($ls_sql);
								   if ($rs_dato===false)
									  {
									    $lb_valido = false;
									    $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_delete_enlace_sep.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
										echo $this->io_sql->message;
									  }
								   else
									  {
										/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////		
										$ls_descripcion ="Actualizó la SEP Nro. $ls_numsol, con el  estatus C = Contabilizada, asociada a la empresa ".$this->ls_codemp;
										$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
																		$aa_seguridad["sistema"],"UPDATE",$aa_seguridad["logusr"],
																		$aa_seguridad["ventanas"],$ls_descripcion);
										/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////				   
									  }
								 }
							} 
					   }
				  }			     
	           $ls_sql = " DELETE
					         FROM soc_enlace_sep
					        WHERE codemp='".$this->ls_codemp."'
					          AND numordcom='".$as_numordcom."'
					          AND estcondat='".$as_tipordcom."' 
							  AND numsol='".$ls_numsol."'"; 
			   $rs_dato = $this->io_sql->execute($ls_sql);
			   if ($rs_dato===false)
				  {
				    $lb_valido = false;
				    $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_delete_enlace_sep.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				  }
			   else
			      {
					/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////		
					$ls_descripcion ="Eliminó el Enlace de la SEP Nro. $ls_numsol, con la Orden de Compra Nro. $as_numordcom de tipo $as_tipordcom asociada a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],"DELETE",$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////				   
				  }
			 }
	 }
  return $lb_valido;
}

function uf_update_estatus_incorporacion_item($as_numsol,$as_numordcom,$as_tipordcom)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_update_estatus_incorporacion_item
//         Access: public
//      Argumento: 
//   $as_numordcom //Número de la Orden de Compra (Bien o Servicio.)
//      $as_codpro //Código del Proveedor asociado a la Orden de Compra.
//   $as_tipordcom //Tipo de Orden de Compra (Bien o Servicio).
//	      Returns: Retorna un resulset
//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulación. 
//	   Creado Por: Ing. Néstor Falcón.
// Fecha Creación: 06/06/2007							Fecha Última Modificación : 09/06/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  if ($as_tipordcom=='B')
     {
	   $ls_tabla = "sep_dt_articulos";
	   $ls_campo = "codart";
	 } 
  elseif($as_tipordcom=='S')
     {
	   $ls_tabla = "sep_dt_servicio";
	   $ls_campo = "codser";
	 } 
  $ls_sql    = "SELECT $ls_campo FROM $ls_tabla WHERE codemp='".$this->ls_codemp."' AND numsol='".$as_numsol."' AND estincite='OC' AND numdocdes='".$as_numordcom."'";//print $ls_sql;  
  $rs_result = $this->io_sql->select($ls_sql);
  if ($rs_result===false)
     {
	   $lb_valido= false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_update_estatus_incorporacion_item.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }
  else
     {
	   while ($row=$this->io_sql->fetch_row($rs_result))
	         {
			   $ls_row = $row["$ls_campo"];
			   $ls_sql = "UPDATE $ls_tabla SET estincite='NI', numdocdes='' ".
						 " WHERE codemp='".$this->ls_codemp."'".
						 "   AND numsol='".$as_numsol."'      ".
						 "   AND $ls_campo='".$ls_row."'      ".
						 "   AND numdocdes='".$as_numordcom."'".
						 "   AND estincite='OC'				  ";
			 
			   $rs_dato = $this->io_sql->execute($ls_sql);
			   if ($rs_dato===false)
			      {
				    $lb_valido = false;
	                $this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->MÉTODO->uf_update_estatus_incorporacion_item.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				  } 
			 }
	 }
  return $lb_valido; 
}
}
?>
