<?php
class sigesp_cfg_c_unidad_tributaria
 {
  	var $ls_sql="";
	var $io_msg_error;
	
	
function sigesp_cfg_c_unidad_tributaria()//Constructor de la Clase.
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
        require_once("../shared/class_folder/class_funciones.php");
		$this->seguridad  = new sigesp_c_seguridad();		  
      	$this->fun        = new class_funciones();
		$io_conect        = new sigesp_include();
		$conn             = $io_conect->uf_conectar();
		$this->la_emp     = $_SESSION["la_empresa"];
		$this->io_sql     = new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg     = new class_mensajes();
		
	}

function uf_select_unidad_tributaria($as_codigo)
{	
	$ls_cadena = "SELECT * FROM sigesp_unidad_tributaria WHERE codunitri='".$as_codigo."' ";
	$rs_data   = $this->io_sql->select($ls_cadena);
	if ($rs_data===false)
	   {
		$this->io_msg->message("CLASE->sigesp_cfg_c_unidad_tributaria; METODO->uf_select_unidad_tributaria;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
    	// $this->is_msg_error="Error en select".$this->fun->uf_convertirmsg($this->io_sql->message);
		 $lb_valido=false;
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_valido=true;
		      $this->io_sql->free_result($rs_data);
			}
	 	 else
		    {
		 	  $lb_valido=false;
			  //$this->io_msg->message("No encontro registro");
	  	    }
	   }
	return $lb_valido;
}

function uf_validacion_fechas($ad_fecentvig,$ad_fecpubgac,$ad_fecdec)
{
  if(date($ad_fecpubgac)>date($ad_fecentvig))
	{
      $this->io_msg->message("Fecha de publicación debe ser menor o igual que la fecha de entrada en vigencia");
	  return false;	
	}
	if(date($ad_fecdec)>date($ad_fecpubgac))
	  {
	   $this->io_msg->message("Fecha del decreto debe ser menor o igual que la fecha de publicación");
	   return false;						
	  }
	else
	  {
	   return true;
	  }
}


function uf_guardar_unidad_tributaria($as_codigo,$as_anno,$ad_fecentvig,$as_gacofi,$ad_fecpubgac,$as_decnro,$ad_fecdec,$as_valunitri,$aa_seguridad)
{  	   
  $lb_val_fecha=$this->uf_validacion_fechas($ad_fecentvig,$ad_fecpubgac,$ad_fecdec); 
  if ($lb_val_fecha)
   {
	$lb_existe=$this->uf_select_unidad_tributaria($as_codigo);
	$lb_valido=false;
    $ld_fecentvig=$this->fun->uf_convertirdatetobd($ad_fecentvig);
	$ld_fecpubgac=$this->fun->uf_convertirdatetobd($ad_fecpubgac);
	$ld_fecdec=$this->fun->uf_convertirdatetobd($ad_fecdec);
	$as_valunitri=str_replace(".","",$as_valunitri);
	$as_valunitri=str_replace(",",".",$as_valunitri);
   	if(!$lb_existe)
		{
			$ls_cadena= " INSERT INTO sigesp_unidad_tributaria(codunitri,anno,fecentvig,gacofi,fecpubgac,decnro,fecdec,valunitri) VALUES('".$as_codigo."', '".$as_anno."' ,'".$ld_fecentvig."','".$as_gacofi."','".$ld_fecpubgac."','".$as_decnro."','".$ld_fecdec."','".$as_valunitri."') ";
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la unidad tributaria ".$as_codigo." ";
			$this->io_msg->message("Registro Incluido !!!");
		}
		else
		{  
		  // if($ls_status=='C')
			//{
			$ls_cadena= " UPDATE sigesp_unidad_tributaria SET anno='".$as_anno."',fecentvig='".$ld_fecentvig."',gacofi='".$as_gacofi."',fecpubgac='".$ld_fecpubgac."',decnro='".$as_decnro."',fecdec='".$ld_fecdec."',valunitri='".$as_valunitri."' WHERE  codunitri='".$as_codigo."'";
		    $this->io_msg->message("Registro Actualizado !!!");
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo la unidad tributaria ".$as_codigo." ";
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		    $aa_seguridad["ventanas"],$ls_descripcion);
		/*	}
			else
			{
			 $this->io_msg->message("Registro ya existe introduzca un nuevo codigo");
			 return false;
			}*/
			
		}
		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if($li_numrows===false)
		{
			$lb_valido=false;
		    $this->io_msg->message("CLASE->sigesp_cfg_c_unidad_tributaria; METODO->uf_guardar_unidad_tributaria;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
			$this->io_sql->rollback();
			print $this->io_sql->message;

		}
		else
		{				
		     $ls_evento="INSERT";
			 $ls_descripcion ="Insertó en CFG Nueva Unidad ".$as_codigo;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////
			 $this->io_sql->commit();
			 $lb_valido=true;
		//	 $this->io_msg->message("Registro Incluido !!!");
		}
	return $lb_valido;	
 //$this->io_sql->close();
  }
 }
 
function uf_delete_unidad_tributaria($as_codigo,$aa_seguridad)
{
	$lb_valido = false;
	$ls_ultimo= "select max(codunitri) from sigesp_unidad_tributaria ";
	$rs_datap   = $this->io_sql->select($ls_ultimo);
	$rowp=$this->io_sql->fetch_row($rs_datap);
	if ($as_codigo!=$rowp["max"])
	 {
	  $this->io_msg->message("Registro no se puede eliminar !!!");
	 }
	 else
	{
	$ls_sql    = "DELETE FROM sigesp_unidad_tributaria WHERE codunitri='".$as_codigo."' ";
	$this->io_sql->begin_transaction();
	$rs_data = $this->io_sql->execute($ls_sql);
	//$this->$io_msg->message("Registro Eliminado !!!");
	if ($rs_data===false)
	   {
		 $lb_valido=false;
	     $this->io_msg_error->message("CLASE->SIGESP_CFG_C_UNIDAD_TRIBUTARIA; METODO->uf_select_unidad_tributaria;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	   	 $ls_evento="DELETE";
	     $ls_descripcion ="Eliminó en CFG la Unidad ".$as_codigo;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $lb_valido = true;
	   }
    return $lb_valido;
   }	
}
	

function AgregarUno($cod="")
 {
  if ($cod=="")
   {
    $cad="0001";
   }
  else
   {
	if(substr($cod,0,1)<>'0')
		{
		  $cad = $cod + 1;
		}
		elseif(substr($cod,1,1)<>'0' and substr($cod,1,3)<999)
		{
			$suma = substr($cod,1,3)+1;
			$cad = "0".$suma;

		}elseif(substr($cod,1,1)<>'0' and substr($cod,1,3)==999)
		{
			$cad = $cod + 1;
			
		}
		elseif(substr($cod,2,1)<>'0' and substr($cod,2,2)<99)
		{
			$suma = substr($cod,2,2)+1;
			$cad = "00".$suma;
		}
		elseif(substr($cod,2,1)<>'0' and substr($cod,2,2)==99)
		{
			$suma = substr($cod,2,2)+1;
			$cad = "0".$suma;
		}
		elseif(substr($cod,3,1)<>'0' and substr($cod,3,1)<9)
		{
			$suma = substr($cod,3,2)+1;
			$cad = "000".$suma;
		}
		elseif(substr($cod,3,1)<>'0' and substr($cod,3,1)==9)
		{
			$suma = substr($cod,3,2)+1;
			$cad = "00".$suma;
		}
	  }
	 return  $cad;
    }

  function retonar_ultimo()
	{
	 $ls_sql= "select max(codunitri) from sigesp_unidad_tributaria "; 
	/* if ($ls_sql==="")
	  {
	   $codigo='0001'
	  }
	 else
	  {*/
	   $rs_data   = $this->io_sql->select($ls_sql);
	   $row=$this->io_sql->fetch_row($rs_data);
	   $codigo=$this->AgregarUno($row["max"]);
	  //}
	 return $codigo;
	 }
	
}//Fin de la Clase.
?>