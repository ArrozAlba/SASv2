<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_anticipo_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa
	   Descripción : Esta clase maneja el acceso de datos de la tabla de anticipos del sistema de presatciones sociales
 *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_pro_anticipo_dao extends class_dao
{
  public function sps_pro_anticipo_dao()  //constructor de la clase
  {
  	$this->class_dao("sps_anticipos"); 
	$this->io_seguridad= new sigesp_c_seguridad();
  	if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
   }
 
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getPorcentajeAnticipo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene el valor porcentual para el calculo de anticipos
  //    Arguments : 
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getPorcentajeAnticipo()
  {
    $ls_sql    = "SELECT porant FROM sps_configuracion WHERE id='1'";	
    $rs_data   = $this->io_sql->select($ls_sql);
	$ls_porc = ""; 
	if($rs_data==false)
	{
		$this->io_msg->message("Error en Anticipo - Configuración." );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $pa_data=$this->io_sql->obtener_datos($rs_data); 
		 $ls_porc=$pa_data["porant"][1];
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
	return $ls_porc;
  } 
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getData($ps_orden="", &$pa_datos="")
  {
    $lb_valido = false;
 	$ls_sql  = "SELECT a.*, p.nomper, p.apeper, n.desnom FROM sps_anticipos a, sno_personal p, sno_nomina n
				WHERE a.codemp=p.codemp and a.codper=p.codper and a.codemp=n.codemp and a.codnom=n.codnom ";
	 
    $rs_data = $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getData de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }
	
	return $lb_valido;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : getAnticipos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información de los anticipos pagados al personal de la institucion
  //    Arguments : $ps_codper -> codigo de personal,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function getAnticipos($ps_cedper,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_sql    = "SELECT a.codper,p.nomper,p.apeper,a.fecantper,a.codnom,n.desnom,a.anoserper,a.messerper,a.diaserper,a.mondeulab,a.monporant,a.monant,a.motant,a.obsant,a.estant FROM sps_anticipos a, sno_personal p,sno_nomina n WHERE a.codemp=p.codemp AND a.codper=p.codper AND a.codemp=n.codemp AND a.codnom=n.codnom ";
      	 if ($ps_cedper!="")
		    $ls_sql .= " AND p.cedper LIKE '%$ps_cedper%'";
		 if ($ps_nomper != "")
		 {
		    $ls_nomper= strtoupper($ps_nomper);
 	  	 	$ls_sql .= " AND p.nomper LIKE '%$ls_nomper%' ";
		 }		   
 	  	 if ($ps_apeper != "")
 	  	 {
 	  	 	$ls_apeper= strtoupper($ps_apeper);
 	  	 	$ls_sql .= " AND p.apeper LIKE '%$ls_apeper%' ";
		 }		 
		 $ls_sql .= $ps_orden;
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en getAnticipos ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarData( $po_object , $ps_operacion="insertar" )
  {	
   
    	$lb_guardo    = false;  
		$ld_mondeulab = str_replace(".", "", $po_object->mondeulab);
		$ld_mondeulab = str_replace(",", ".", $ld_mondeulab);
		$ld_monporant = str_replace(".", "", $po_object->monporant);
		$ld_monporant = str_replace(",", ".", $ld_monporant);
		$ld_monant    = str_replace(".", "", $po_object->monant);
		$ld_monant    = str_replace(",", ".", $ld_monant);
			
		$ls_fecantper = $this->io_function->uf_convertirdatetobd($po_object->fecantper );
		if ($ps_operacion=="modificar")
		{
			$ls_sql = " UPDATE ".$this->as_tabla." SET motant='".$po_object->motant."', monant='".$ld_monant."' 
			            WHERE codemp='".$this->ls_codemp."' AND codper='".$po_object->codper."' AND codnom='".$po_object->codnom."' AND fecantper='".$ls_fecantper."'";
			$li_guardo = $this->io_sql->execute( $ls_sql );
			
			if ($li_guardo > 0)
			{
			    $this->io_sql->commit();
			    $this->io_function_sb->message("Los datos fueron actualizados.");
			    $lb_guardo=true;
			   	/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." codper=".$po_object->codper." codnom=".$po_object->codnom." fecha=".$ls_fecantper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_anticipos.html.php",$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			else
			{
			   $this->io_sql->rollback();
			   $this->io_function_sb->message("No puede actualizar los datos.");
			   $lb_guardo=false;
			}				
		}	
		else
		{
			$ls_sql    = "INSERT INTO ".$this->as_tabla." (codemp,codper,codnom,fecantper,anoserper,messerper,diaserper,motant,mondeulab,monporant,monant,estant,obsant) VALUES 
			              ( '".$this->ls_codemp."','" .$po_object->codper."','".$po_object->codnom."','".$ls_fecantper."','".$po_object->anoserper."','".$po_object->messerper."','".$po_object->diaserper."',
						    '".$po_object->motant."','".$ld_mondeulab."','".$ld_monporant."','".$ld_monant."','".$po_object->estant."','".$po_object->obsant."' ) ";			              
			$li_guardo = $this->io_sql->execute( $ls_sql );
			if ($li_guardo > 0)
			{
				$this->io_sql->commit();
				$this->io_function_sb->message("Los datos fueron registrados.");
				$lb_guardo=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." codper=".$po_object->codper." codnom=".$po_object->codnom." fecha=".$ls_fecantper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_anticipos.html.php",$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_function_sb->message("Los datos no puden ser registrados.");
				$lb_guardo=false;
			}
		} 
	return $lb_guardo;
	
}  //function guardarData
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getEliminable
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que chequea si el registro se puede eliminar o no
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : boolean
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function getEliminable( $as_codper, $as_codnom, $adt_fecantper ) 
  {
  	$lb_valido = false;
	$ldt_fecantper = $this->io_function->uf_convertirdatetobd($adt_fecantper);
 	$ls_sql  = "SELECT estant FROM sps_anticipos WHERE codemp='".$this->ls_codemp."' AND codper='".$as_codper."' AND codnom='".$as_codnom."' AND fecantper='".$ldt_fecantper."'";
    $rs_data = $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getEliminable de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		$pa_datos=$this->io_sql->obtener_datos($rs_data); 
		$ls_estant=$pa_datos["estant"][1];
		if ($ls_estant=='0'){$lb_valido=true;}
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }
	
	return $lb_valido;
  }
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : eliminarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que elimina un registro de dato
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $as_codper, $as_codnom, $adt_fecantper ) 
  {
   // //Se chequea si el registro se puede eliminar ( Integridad relacional de la Tabla u Objeto asociado inmediato)
 	$lb_eliminable = ($this->getEliminable($as_codper, $as_codnom, $adt_fecantper));
	$lb_borro      =  false;
	if (!$lb_eliminable)
	{
	  $this->io_function_sb->message("No se puede eliminar este registro, esta relacionado con movimientos dentro del sistema.");
	}
	else
	{
	  $ldt_fecantper = $this->io_function->uf_convertirdatetobd($adt_fecantper);
	  $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."'and codper='".$as_codper."' and codnom='".$as_codnom."' and fecantper='".$ldt_fecantper."'";
	  $li_elimino=$this->io_sql->execute( $ls_sql );
	  if ($li_elimino > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron eliminados.");
			$lb_borro = true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." codper=".$as_codper." codnom=".$as_codnom." fecha=".$ldt_fecantper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_anticipos.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser eliminados.");
		}
	}
	return $lb_borro;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : updateAnticipo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que actualiza la información sobre la aprob/rechazo del anticipo 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function updateAnticipo( $po_object , $ps_operacion="modificar" )
  {	
	$lb_actilizo  = false;  
	$ld_monant    = str_replace(".", "", $po_object->monant);
	$ld_monant    = str_replace(",", ".", $ld_monant);
	$ls_fecantper = $this->io_function->uf_convertirdatetobd($po_object->fecantper );
	
	if ($ps_operacion=="modificar")
	{
		$ls_sql = " UPDATE ".$this->as_tabla." SET monant='".$ld_monant."', estant='".$po_object->estant."', obsant='".$po_object->obsant."' 
					WHERE codemp='".$this->ls_codemp."' AND codper='".$po_object->codper."' AND codnom='".$po_object->codnom."' AND fecantper='".$ls_fecantper."'";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
		   $this->io_sql->commit();
		   $this->io_function_sb->message("Los datos fueron actualizados.");
		   $lb_guardo=true;
		   //genero los datos para el asiento de contabilizacion si el anticipo es aprobado
		   if ($po_object->estant==1)
		   {
		   		$lb_valido = $this->uf_contabilizar_anticipos_spg($po_object->codnom,$po_object->codper,$po_object->fecantper);
				if ($lb_valido) { $this->uf_contabilizar_anticipos_scg($po_object->codnom,$po_object->codper,$po_object->fecantper); }
		   }	
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." codper=".$po_object->codper." codnom=".$po_object->codnom." fecha=".$ls_fecantper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionanticipos.html.php",$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
		   $this->io_sql->rollback();
		   $this->io_function_sb->message("No pudo actualizar la información.");
		   $lb_guardo=false;
		}				
	}	
	
	return $lb_actilizo;
  }  //function updateAnticipo
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : buscarAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_codper -> Parametro del codigo de personal
  //              : $ps_codnom -> Parametro del codigo de nomina
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function buscarAntiguedad($ps_codper,$ps_codnom, &$la_data="")
  { 
    $lb_valido = false;
	$ld_monantacu=0;
	$ld_monantantacu=0;
	$ld_monintacu=0;
	$ld_mondeulab=0;
	$ld_monporant=0;
    $ls_sql = "SELECT anoserant, messerant,diaserant, monant,monantant,monint FROM sps_antiguedad WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and estant='R' ";
	 
	$rs_data = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en buscarAntiguedad de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data); 
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 $index   = array_keys($pa_datos);
		 $fila = count($pa_datos["anoserant"]);
		 for ($j=0; $j<$fila; $j++)
		 {
				$li_ano = $pa_datos["anoserant"][$j];
				$li_mes = $pa_datos["messerant"][$j];
				$li_dia = $pa_datos["diaserant"][$j];
				$ld_monant = $pa_datos["monant"][$j];
				$ld_monantacu = ($ld_monantacu+$ld_monant);
				$ld_monantant = $pa_datos["monantant"][$j];
				$ld_monantantacu = ($ld_monantantacu+$ld_monantant);
				$ld_monint = $pa_datos["monint"][$j];
				$ld_monintacu = ($ld_monintacu+$ld_monint); 
		 }
		 $ld_mondeulab = (($ld_monantacu+$ld_monintacu)-$ld_monantantacu);
		 $ld_porant=$this->getPorcentajeAnticipo();
		 $ld_monporant=(($ld_mondeulab*$ld_porant)/100);
		 $la_data["ano"][0]=$li_ano;
		 $la_data["mes"][0]=$li_mes;
		 $la_data["dia"][0]=$li_dia;
		 $la_data["mondeulab"][0]=$ld_mondeulab;
		 $la_data["monporant"][0]=$ld_monporant;
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDataAprobacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDataAprobacion($ps_orden="", &$pa_datos="")
  {
        $lb_valido = false;
        $ps_orden = "ORDER BY a.codper,p.nomper,p.apeper,a.codnom,n.desnom,a.fecantper,a.mondeulab,a.monporant,a.monant,a.estant,a.obsant ASC ";
	$ls_sql  = "SELECT a.codper,p.nomper,p.apeper,a.codnom,n.desnom,a.fecantper,a.mondeulab,a.monporant,a.monant,a.estant,a.obsant
				FROM   sps_anticipos a, sno_personal p, sno_nomina n
				WHERE  a.codemp=p.codemp and a.codper=p.codper and a.codemp=n.codemp and a.codnom=n.codnom ".$ps_orden;
	$rs_data = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getDataAprobacion de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }

	return $lb_valido;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : getReporteAnticipo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información del anticipo aprobado al trabajaor
  //    Arguments : $ps_codper -> codigo de personal
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function getReporteAnticipo($ps_codper,&$pa_datos="")    
   {
  		 $lb_valido = false;
		 $ls_sql    = "SELECT a.monant,p.cedper,p.nomper,p.apeper,pn.fecingper,c.descar,a.codnom,n.desnom,u.desuniadm  
		               FROM sps_anticipos a,sno_personalnomina pn,sno_cargo c,sno_nomina n,sno_personal p,sno_unidadadmin u 
					   WHERE a.codemp=pn.codemp and a.codnom=pn.codnom and a.codper=pn.codper and pn.codemp=c.codemp and pn.codnom=c.codnom and pn.codcar=c.codcar and 
					         pn.codemp=n.codemp and pn.codnom=n.codnom and pn.codemp=p.codemp and pn.codper=p.codper and pn.codemp=u.codemp and 
							 pn.minorguniadm=u.minorguniadm and pn.ofiuniadm=u.ofiuniadm and pn.uniuniadm=u.uniuniadm and pn.depuniadm=u.depuniadm and pn.prouniadm=u.prouniadm and a.codper='".$ps_codper."'";
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en getReporteAnticipo ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : get_personal_anticipo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información del personal que posee anticipos en el modulo de sps
  //    Arguments : $ps_codper -> codigo de personal,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function get_personal_anticipo($ps_codper,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_codper = $ps_codper."%";
		 $ls_nomper = $ps_nomper."%"; 
		 $ls_apeper = $ps_apeper."%";
		 $ps_order  = "ORDER BY a.codper, p.nomper, p.apeper ASC";
		 $ls_sql    = "SELECT DISTINCT a.codper, p.nomper, p.apeper  
		               FROM sps_anticipos a, sno_personal p
					   WHERE a.codemp=p.codemp and a.codper=p.codper and a.codemp='".$this->ls_codemp."' and p.codper like '".$ls_codper."' and p.nomper like '".$ls_nomper."' and p.apeper like '".$ls_apeper."' and estant=1 ".$ps_order;
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en get_personal_anticipo ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }
     
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : get_sc_cuenta
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripción : Función que obtiene el valor de la cuenta contable de configuracion
	  //    Arguments : 
	  //      Retorna : Obtener los registros.
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function uf_get_sc_cuenta()
	  {
	    $ls_sql    = "SELECT sc_cuenta_ps FROM sps_configuracion WHERE id=1";	
	    $rs_data   = $this->io_sql->select($ls_sql);
		$ls_sc_cuenta = ""; 
		if($rs_data==false)
		{
			$this->io_msg->message("Error en get_sc_cuenta - Anticipo." );
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $pa_data=$this->io_sql->obtener_datos($rs_data); 
			 $ls_sc_cuenta=$pa_data["sc_cuenta_ps"][1];
		}
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		return $ls_sc_cuenta;
	  }  
      	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : uf_get_sc_cuenta_beneficiario
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripción : Función que obtiene el valor de la cuenta contable de beneficiario
	  //    Arguments : 
	  //      Retorna : Obtener los registros.
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function uf_get_sc_cuenta_beneficiario($ps_codper)
	  {
	    $ls_sql    = "select sc_cuenta from rpc_beneficiario where codemp='".$this->ls_codemp."' AND ced_bene IN
                      (select cedper from sno_personal where codemp='".$this->ls_codemp."' AND codper='".$ps_codper."')";	
	    $rs_data   = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_msg->message("Error en get_sc_cuenta_beneficiario - Anticipo." );
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $pa_data=$this->io_sql->obtener_datos($rs_data); 
			 $ls_sc_cuenta=$pa_data["sc_cuenta"][1];
		}
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		return $ls_sc_cuenta;
	  }  
 	//-----------------------------------------------------------------------------------------------------------------------------------
   	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_contabilizar_anticipos_spg 
	//	    Arguments: as_codnom      //  Código de Nómina
	//	    		   as_codper       //  codigo del personal
	//	    		   $adt_fecantper  //  Fecha de Anticipo Personal
	//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
	//	  Description: Función que se encarga de procesar la data para la contabilización del Anticipo
        //     Creado por: Ing. Maria A Roa
	//////////////////////////////////////////////////////////////////////////////////////////////
	function uf_contabilizar_anticipos_spg($as_codnom,$as_codper,$adt_fecantper)
	{
		$lb_valido=true;
		$ls_dia =  substr($adt_fecantper, 0, 2);
		$ls_mes =  substr($adt_fecantper, 3, 2);
		$ls_anoantper  = substr($adt_fecantper, 8, 2);              //10/04/2008  => 100408
		$ls_comprobante=$ls_dia.$ls_mes.$ls_anoantper.substr($as_codper, 2, 8); // Comprobante
		$ls_ano = substr($adt_fecantper, 6, 4);
		$li_tipo="A"; 
		$ls_operacion="OC";
		$li_genrecdoc="1";
		$li_estatus = 0;                                            //No Contabilizado 
		$ls_tipdoc  = "";
		$ls_descripcion=" Anticipo de Prestaciones Sociales. ";      // Descripción
		$ls_fecantper = $this->io_function->uf_convertirdatetobd($adt_fecantper );
		$ls_sql = " SELECT sps_anticipos.monant, sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_fideiconfigurable.cueprefid ".
				  "	  FROM sps_anticipos, sno_personalnomina, sno_unidadadmin, sno_fideiconfigurable".
				  "	 WHERE sps_anticipos.codemp=sno_personalnomina.codemp AND sps_anticipos.codnom=sno_personalnomina.codnom AND sps_anticipos.codper=sno_personalnomina.codper".
				  "	   AND sno_personalnomina.codemp = sno_unidadadmin.codemp AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm".
				  "	   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm".
				  "	   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm".
				  "	   AND sno_personalnomina.codemp = sno_fideiconfigurable.codemp AND sno_personalnomina.codded = sno_fideiconfigurable.codded".
				  "	   AND sno_personalnomina.codtipper = sno_fideiconfigurable.codtipper AND sno_fideiconfigurable.anocurfid = '".$ls_ano."' ".
				  "    AND sps_anticipos.codper='".$as_codper."' AND sps_anticipos.codnom='".$as_codnom."' AND sps_anticipos.fecantper='".$ls_fecantper."' ".
				  "  ORDER BY sps_anticipos.monant, sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_fideiconfigurable.cueprefid   ";
	   	 
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en uf_contabilizar_anticipos_spg.  " );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data);
			$ld_monant = $pa_datos["monant"][0];
			$ls_programatica = $pa_datos["codprouniadm"][0];
			$ls_estcla = $pa_datos["estcla"][0];
			$ls_cueprefid = $pa_datos["cueprefid"][0]; 
			$lb_valido = $this->uf_insert_contabilizacion_spg($as_codnom,$ls_comprobante,$li_tipo,$ls_programatica,$ls_estcla,$ls_cueprefid,$ls_operacion,
			                                                 $as_codper,$ls_descripcion,$ld_monant,$li_estatus,$li_genrecdoc,$ls_tipdoc);
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
		 $this->io_sql->free_result($rs_data);	 
		 return $lb_valido; 		
		    
	}// end function uf_contabilizar_anyicipos_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : uf_insert_contabilizacion_spg
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información para la contabilizacion 
  //    Arguments : $as_codnom: codigo de nomina
  //                $ls_comprobante: codigo de comprobante
  //				$li_tipo: tipo de transaccion (A:anticipo, L:liquidacion)
  //				$ls_programatica: Nº de programatica
  //				$ls_cueprefid: cuenta presupuestaria
  //				$ls_operacion: tipo de operacion (compromete y causa)
  //  				$as_codper: codigo de personal
  //                $ls_descripcion: 
  //				$ld_monant: monto del anticipo
  //				$li_estatus: estatus de contabilizacion (0:no; 1:si) 
  //				$li_genrecdoc: generar rec de documento
  //				$ls_tipdoc:  tipo de documento     
  //      Retorna : 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function uf_insert_contabilizacion_spg( $ps_codnom,$ps_comprobante,$pi_tipo,$ps_programatica,$ps_estcla,$ps_cueprefid,$ps_operacion,
			                                     $ps_codper,$ps_descripcion,$pd_monant,$pi_estatus,$pi_genrecdoc,$ps_tipdoc )
  {	
   
	    	$lb_guardo    = false;  
		$ldt_fecha = "1900-01-01"; //fecha por defecto para fecha de contabilizacion y anulacion
		$ls_codestpro1=substr($ps_programatica,0,25);
		$ls_codestpro2=substr($ps_programatica,25,25);
		$ls_codestpro3=substr($ps_programatica,50,25);
		$ls_codestpro4=substr($ps_programatica,75,25);
		$ls_codestpro5=substr($ps_programatica,100,25);

		$ls_sql    = "INSERT INTO sps_dt_spg(codemp, codnom, codcom, tipo, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, spg_cuenta, operacion, ced_bene, descripcion, monto, estatus, estrd, codtipdoc, fechaconta, fechaanula) VALUES 
		              ( '".$this->ls_codemp."','" .$ps_codnom."','".$ps_comprobante."','".$pi_tipo."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."',
					    '".$ps_estcla."','".$ps_cueprefid."','".$ps_operacion."','".$ps_codper."','".$ps_descripcion."','".$pd_monant."','".$pi_estatus."','".$pi_genrecdoc."','".$ps_tipdoc."','".$ldt_fecha."','".$ldt_fecha."' ) ";
				              
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$this->io_sql->commit();
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla sps_dt_spg codper=".$ps_codper." codnom=".$ps_codnom." comprobante=".$ps_comprobante;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionanticipos.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no puden ser registrados.");
			$lb_guardo=false;
		}
		 
	return $lb_guardo;
	
}  //function uf_insert_contabilizacion_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_anticipos_scg($as_codnom,$as_codper,$adt_fecantper)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_contabilizar_anticipos_scg 
	//	    Arguments: as_codnom       //  Código de Nómina
	//	    		   as_codper       //  codigo del personal
	//	    		   $adt_fecantper  //  Fecha de Anticipo Personal
	//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
	//	  Description: Función que se encarga de procesar la data para la contabilización del Anticipo
        //     Creado por: Ing. Maria A Roa
	//////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_dia = substr($adt_fecantper,0,2);
		$ls_mes = substr($adt_fecantper,3,2);
		$ls_anoantper = substr($adt_fecantper, 8, 2);                           //10/04/2008=>100408
		$ls_comprobante=$ls_dia.$ls_mes.$ls_anoantper.substr($as_codper, 2, 8); // Comprobante
		$ls_cedper  = 
		$li_tipo="A"; 
		$li_genrecdoc="1";
		$li_estatus = 0;   //No Contabilizado 
		$ls_tipdoc  = "";
		$ls_descripcion=" Anticipo de Prestaciones Sociales. ";                 // Descripción
		$ls_debe = "D";
		$ls_haber= "H";		
		$ls_fecantper = $this->io_function->uf_convertirdatetobd($adt_fecantper);
		$ls_sc_cuenta = $this->uf_get_sc_cuenta();
		$ls_sql = " SELECT sps_anticipos.monant ".
				  "	  FROM sps_anticipos, sno_personalnomina".
				  "	 WHERE sps_anticipos.codemp=sno_personalnomina.codemp AND sps_anticipos.codnom=sno_personalnomina.codnom AND sps_anticipos.codper=sno_personalnomina.codper".
				  "    AND sps_anticipos.codper='".$as_codper."' AND sps_anticipos.codnom='".$as_codnom."' AND sps_anticipos.fecantper='".$ls_fecantper."' ".
				  "  ORDER BY sps_anticipos.monant   ";
	   	 
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en uf_contabilizar_anticipos_scg.  " );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data);
			$ld_monant = $pa_datos["monant"][0];
			
			$lb_valido = $this->uf_insert_contabilizacion_scg($as_codnom,$ls_comprobante,$li_tipo,$ls_sc_cuenta,$ls_debe,$as_codper,
			                                           $ls_descripcion,$ld_monant,$li_estatus,$li_genrecdoc,$ls_tipdoc);
			if ($lb_valido)
			{ 
				$ls_sccuenta_bene = $this->uf_get_sc_cuenta_beneficiario($as_codper);
				$lb_valido = $this->uf_insert_contabilizacion_scg($as_codnom,$ls_comprobante,$li_tipo,$ls_sccuenta_bene,$ls_haber,$as_codper,
			                                             $ls_descripcion,$ld_monant,$li_estatus,$li_genrecdoc,$ls_tipdoc);
			} 			                                           
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
		 $this->io_sql->free_result($rs_data);	 
		 return $lb_valido;	  
	}// end function uf_contabilizar_conceptos_scg
	//-----------------------------------------------------------------------------------------------------------------------------------
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : uf_insert_contabilizacion_scg
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información para la contabilizacion 
  //    Arguments : $as_codnom: codigo de nomina
  //                $ls_comprobante: codigo de comprobante
  //				$li_tipo: tipo de transaccion (A:anticipo, L:liquidacion)
  //                $ls_sc_cuenta: cuenta contable de prestaciones sociales
  //				$ls_programatica: Nº de programatica
  //  				$as_codper: codigo de personal
  //                $ls_descripcion: 
  //				$ld_monant: monto del anticipo
  //				$li_estatus: estatus de contabilizacion (0:no; 1:si) 
  //				$li_genrecdoc: generar rec de documento
  //				$ls_tipdoc:  tipo de documento     
  //      Retorna : 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function uf_insert_contabilizacion_scg( $ps_codnom,$ps_comprobante,$pi_tipo,$ps_sc_cuenta,$ps_debhab,$ps_codper,
                                                 $ps_descripcion,$pd_monant,$pi_estatus,$pi_genrecdoc,$ps_tipdoc )
     {	
       		$lb_guardo = false;  
		$ldt_fecha = "1900-01-01";          //fecha por defecto para fecha de contabilizacion y anulacion
		$ls_sql    = "INSERT INTO sps_dt_scg(codemp, codnom, codcom, tipo, sc_cuenta, debhab, ced_bene, descripcion, monto, estatus, estrd, codtipdoc, fechaconta, fechaanula) VALUES 
		              ( '".$this->ls_codemp."','" .$ps_codnom."','".$ps_comprobante."','".$pi_tipo."','".$ps_sc_cuenta."','".$ps_debhab."',
					    '".$ps_codper."','".$ps_descripcion."','".$pd_monant."','".$pi_estatus."','".$pi_genrecdoc."','".$ps_tipdoc."','".$ldt_fecha."','".$ldt_fecha."' ) ";			              
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$this->io_sql->commit();
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla sps_dt_scg codper=".$ps_codper." codnom=".$ps_codnom." comprobante=".$ps_comprobante;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionanticipos.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no puden ser registrados.");
			$lb_guardo=false;
		}
		 
		return $lb_guardo;
	
	}  //function uf_insert_contabilizacion_spg
}
?>
