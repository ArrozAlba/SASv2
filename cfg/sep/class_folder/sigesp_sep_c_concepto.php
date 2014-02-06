<?php
class sigesp_sep_c_concepto
{
var $ls_sql;
var $is_msg_error;	
		
	function sigesp_sep_c_concepto($conn)
	{
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	  $this->io_sql       = new class_sql($conn);
	  $this->io_funcion   = new class_funciones();
	  $this->io_msg       = new class_mensajes();
	  $this->seguridad    = new sigesp_c_seguridad();	
	  $this->io_rcbsf     = new sigesp_c_reconvertir_monedabsf();
	  $this->li_candeccon = $_SESSION["la_empresa"]["candeccon"];
	  $this->li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
	  $this->li_redconmon = $_SESSION["la_empresa"]["redconmon"];
	}

function uf_insert_concepto($as_codemp,$as_codconsep,$as_denconsep,$as_monconsepe,$as_obsconesp,$as_spg_cuenta,$ar_grid,$as_totfil,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_concepto
//	          Access:  public
//	        Arguments 
//        $as_codemp:  Código de la Empresa.
//     $as_codconsep:  Código del Concepto de la SEP.
//     $as_denconsep:  Denominación del Código del Concepto de la SEP.
//    $as_monconsepe:  Monto del Concepto de la SEP.
//     $as_obsconesp:  Observación del concepto.
//    $as_spg_cuenta:  Cuenta presupuestaria asociada al concepto.
//          $ar_grid:  Arreglo cargado con el grid.
//        $as_totfil:  Total de filas del grid.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de insertar un nuevo concepto a la Tabla sep_conceptos.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:27/02/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido=false;
  $as_monconsepe=str_replace('.','',$as_monconsepe);
  $as_monconsepe=str_replace(',','.',$as_monconsepe);
  $ls_sql = " INSERT INTO sep_conceptos ".
			" (codconsep,denconsep,monconsepe,obsconesp,spg_cuenta) ".
			" VALUES ('".$as_codconsep."','".$as_denconsep."','".$as_monconsepe."','".$as_obsconesp."','".$as_spg_cuenta."')";
  $this->io_sql->begin_transaction();
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_insert_concepto; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_insert_dtcargos($as_codemp,$as_codconsep,$ar_grid,$as_totfil,$aa_seguridad))
		   {
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó Concepto ".$as_codconsep;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
		    $lb_valido=true;
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","monconsepeaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$as_monconsepe);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconsep");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codconsep);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_conceptos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
		     */
		  }
	 }
return $lb_valido;
}

function uf_update_concepto($as_codemp,$as_codconsep,$as_denconsep,$as_monconsepe,$as_obsconesp,$as_spg_cuenta,$ar_grid,$as_totfil,$aa_seguridad)                     
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_concepto
//	          Access:  public
//	        Arguments  
//        $as_codemp:  Código de la Empresa.
//     $as_codconsep:  Código del Concepto de la SEP.
//     $as_denconsep:  Denominación del Código del Concepto de la SEP.
//    $as_monconsepe:  Monto del Concepto de la SEP.
//     $as_obsconesp:  Observación del concepto.
//    $as_spg_cuenta:  Cuenta presupuestaria asociada al concepto.
//          $ar_grid:  Arreglo cargado con el grid.
//        $as_totfil:  Total de filas del grid.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de insertar un nuevo concepto a la Tabla sep_conceptos.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:21/02/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $as_monconsepe=str_replace('.','',$as_monconsepe);
  $as_monconsepe=str_replace(',','.',$as_monconsepe);
  $ls_sql=" UPDATE sep_conceptos ".
		  " SET    denconsep='".$as_denconsep."', monconsepe='".$as_monconsepe."',".
		  "        obsconesp='".$as_obsconesp."', spg_cuenta='".$as_spg_cuenta."' ".
		  " WHERE  codconsep='".$as_codconsep."'";
  $this->io_sql->begin_transaction();
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_sql->rollback();
   	   $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_update_concepto;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	 if ($this->uf_delete_cargosxconcepto($as_codemp,$as_codconsep,$aa_seguridad))
		{                  
		  if ($this-> uf_insert_dtcargos($as_codemp,$as_codconsep,$ar_grid,$as_totfil,$aa_seguridad))
		     {                        
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $ls_evento="UPDATE";
			   $ls_descripcion ="Actualizó Concepto".$as_codconsep;
			   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												 $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		        $lb_valido=true;
				/*$this->io_rcbsf->io_ds_datos->insertRow("campo","monconsepeaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$as_monconsepe);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconsep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codconsep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_conceptos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
		        */
			 }
		  $lb_valido=true;
		}
	 }
return $lb_valido;
} 

function uf_delete_concepto($as_codemp,$as_codconsep,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_concepto
//	          Access:  public
//	        Arguments  
//        $as_codemp:  Código de la Empresa.
//     $as_codconsep:  Código del Concepto de la SEP.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de Eliminar los datos de un
//                     concepto en la tabla sep_conceptos.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:21/02/2006.	 
//////////////////////////////////////////////////////////////////////////////           		 
   $lb_valido=false;
   if($this->uf_delete_cargosxconcepto($as_codemp,$as_codconsep,$aa_seguridad))
     {
	   $ls_sql = " DELETE FROM sep_conceptos WHERE codconsep='".$as_codconsep."'";	           
	   $this->io_sql->begin_transaction();
	   $rs_data=$this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
	      {
			$lb_valido=false;
	   	    $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_delete_concepto;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
	   else
	      {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó Concepto ".$as_codconsep;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
            $lb_valido=true;
		  } 		 
	}
return $lb_valido;
}

function uf_delete_cargosxconcepto($as_codemp,$as_codconsep,$aa_seguridad)
{
/////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_cargosxconcepto
//	          Access:  public
//	        Arguments  
//     $as_codconsep:  Código del Concepto de la SEP.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de eliminar los cargos asociados a un concepto.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:21/02/2006.	 
/////////////////////////////////////////////////////////////////////////////////////           		 
  $lb_valido=false;        
  $ls_sql = " DELETE FROM sep_conceptocargos WHERE codconsep='".$as_codconsep."'";	    
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
		 $lb_valido=false;
   	     $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_delete_cargosxconcepto;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó los Cargos asociados a los conceptos ".$as_codconsep;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////*/            
	   $lb_valido=true;
	 } 		 
   return $lb_valido;
}

function uf_select_concepto($as_codconsep) 
{
/////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_concepto
//	          Access:  public
//	        Arguments  
//     $as_codconsep:  Código del Concepto de la SEP.
//	     Description:  Función que se encarga de Insertar los datos de la cabecera
//                     de una solicitud de cotización en la tabla soc_sol_cotización.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:21/02/2006.	 
///////////////////////////////////////////////////////////////////////////////////// 
  $lb_valido=false;
  $ls_sql=" SELECT * FROM sep_conceptos WHERE codconsep='".$as_codconsep."'";
  $rs_data=$this->io_sql->select($ls_sql);		 
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_select_concepto;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

function uf_llenarcombo_unidad()
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_llenarcombo_unidad
//	          Access:  public
//	       Arguments: 
//	     Description:  Función que se encarga de Insertar los datos de la cabecera
//                     de una solicitud de cotización en la tabla soc_sol_cotización.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:21/02/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	$lb_valido = false;
	$ls_sql    = "SELECT * FROM soc_unidad ORDER BY coduni ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_llenarcombo_unidad;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }
	   }
	return $rs_data;         
}

function uf_insert_dtcargos($as_codemp,$as_codcon,$ar_grid,$as_totfil,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_solicitud_cotizacion
//	          Access:  public
//	        Arguments  
//        $as_codemp:  Código de la Empresa.
//        $as_codcon:  Código del Concepto.
//          $ar_grid:  Arreglo cargado con el grid.
//        $as_totfil:  Total de filas del grid.
//         $ar_datos:  Arreglo cargado con los valores de las variables provenientes 
//                     de la cabecera del formulario de Solicitud de Cotización.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de Insertar los detalles de cargos para un concepto.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/02/2006       Fecha Última Actualización:21/02/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	 $lb_valido=true;        
	 for($i=1;$i<=$as_totfil;$i++)
	   {
		  $ls_codcar=$ar_grid["cargo"][$i];               
		  if($ls_codcar!="")
		  {
			  if($lb_valido)
			  {
				 $ls_sql  = "INSERT INTO sep_conceptocargos (codemp,codcar,codconsep) VALUES ('".$as_codemp."','".$ls_codcar."','".$as_codcon."')";                                                       
				 $rs_data = $this->io_sql->execute($ls_sql);              
				 if ($rs_data===false)
					{				 
					  $lb_valido=false;  
					  $this->io_sql->rollback();
   	                  $this->io_msg->message("CLASE->CFG->SIGESP_SEP_C_CONCEPTO; METODO->uf_insert_dtcargos;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					}
				else
					{				 
					  $lb_valido=true;  		                    
					  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					  $ls_evento      ="INSERT";
					  $ls_descripcion =" Insertó cargos asociados a los conceptos de la SEP  ".$as_codcon;
					  $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					  $aa_seguridad["ventanas"],$ls_descripcion);
					  /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
				  }  				
			   }
		   }
	  } 
return $lb_valido;
}
}
?> 