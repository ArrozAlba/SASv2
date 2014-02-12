<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_antig_nomina_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa
	   Descripción : Esta clase maneja el acceso de datos de la tabla antiguedad-nomina del sistema de presatciones sociales
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_pro_antig_nomina_dao extends class_dao
{
  public function sps_pro_antig_nomina_dao()  //constructor de la clase
  {
    $this->class_dao("sps_antiguedad"); 
    $this->io_seguridad= new sigesp_c_seguridad();
    
	if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getFechaIngreso
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna la fecha de Ingrso del empleado
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getFechaIngreso( $ps_codper, $ps_codnom, &$pa_datos )  
  {       
  	$lb_valido = false;
 	$ls_sql    = "SELECT fecingper FROM sno_personalnomina WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."'  ";			  
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getFechaIngeso ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getAnticipos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna el anticipo si el empleado tiene
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getAnticipos( $ps_codper, $ps_codnom,$pd_fecdes,$pd_fechas,&$pa_datos )   
  {       
  	$lb_valido = false;
  	$ld_fecdesde = $this->io_function->uf_convertirdatetobd($pd_fecdes);
  	$ld_fechasta = $this->io_function->uf_convertirdatetobd($pd_fechas);
	$ls_sql    = "SELECT fecantper, monant FROM sps_anticipos WHERE codemp='".$this->ls_codemp."'  and codper='".$ps_codper."' and codnom='".$ps_codnom."' and fecantper between '".$ld_fecdesde."' and '".$ld_fechasta."'  order by  fecantper, monant  asc ";
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getAnticipos " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{   
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
		
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getAntigNomina
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información de fideicomiso generada en nomina  
  //    Arguments : $ps_codper -> Parametro que indica el codigo de personal
  //              : $ps_codnom -> Parametro que indica el codigo de nomina
  //              : $ps_fecdes -> Parametro que indica fecha desde
  //              : $ps_fechas -> Parametro que indica fecha hasta
  //      Retorna : Arreglo $la_datos
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  function getAntigNomina($ps_codper,$ps_codnom,$pd_fecdes,$pd_fechas,&$pa_datos)
  {
	$lb_valido=false;
	$li_anodes = substr($pd_fecdes,6,4);
	$li_mesdes = substr($pd_fecdes,3,2);
	$li_anohas = substr($pd_fechas,6,4);
	$li_meshas = substr($pd_fechas,3,2);
	$ps_orden  = "ORDER BY anocurper,mescurper,bonvacper,bonfinper,sueintper,apoper,diafid,diaadi ASC";	
	$ls_sql = "SELECT anocurper,mescurper,bonvacper,bonfinper,sueintper,apoper,diafid,diaadi
	           FROM sno_fideiperiodo WHERE codper='".$ps_codper."' AND codnom='".$ps_codnom."' 
			   AND anocurper between '".$li_anodes."' and '".$li_anohas."'  AND mescurper between '".$li_mesdes."' and '".$li_meshas."' ".$ps_orden;
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getAntigNomina ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="PROCESS";
		 $ls_descripcion =" Extrajo información de la tabla sno_fideiperiodo de codper=".$ps_codper." codnom=".$ps_codnom." ";
		 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_antig_nomina.html.php",$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	}
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : consultarTasaInteres
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que consulta en BD las tasas de interes 
  //    Arguments : $ps_fecincsue -> Parametro de fecha del periodo del fideicomiso
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function consultarTasaInteres($ps_fecha)
  {
	 $ls_ano = substr($ps_fecha,0,4);
	 $ls_mes = substr($ps_fecha,5,2);	
	 $ls_sql = "SELECT valtas FROM sps_tasa_interes WHERE anotasint='".$ls_ano."' AND mestasint='".$ls_mes."' "; 	
	 $rs_data = $this->io_sql->select($ls_sql);
	 if($rs_data==false)
	 {
		$this->io_function_sb->message("Error en consultarTasaInteres " );
	 }
	 elseif($row=$this->io_sql->fetch_row($rs_data))
	 {
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $ls_tasa=$pa_datos["valtas"][1];
	 }
	 return $ls_tasa;
   }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : calcularInteres
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que calcula los intereses de fideicomisos generados en el modulo de nomina 
  //    Arguments : $ps_object -> Parametro de datos de fideicomiso
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function calcularInteres($po_object,&$pa_datos)
  {
	$li_registro = 0;
	$lb_calculado=true;
	$ld_intacu   = 0;
	$ld_saltotant= 0; 
	while (($li_registro<count($po_object->dt_antig))&&($lb_calculado))
	{
		$lb_calculado = $this->generarInteres($po_object->dt_antig[$li_registro],$pa_interes);
		if ($lb_calculado)
		{
			$ls_periodo   = $pa_interes["periodo"];
			$ld_salbas    = $pa_interes["salbas"];	
			$ld_incbonvac = $pa_interes["incbonvac"];
	 		$ld_incbonnav = $pa_interes["incbonnav"];
	 		$ld_salint    = $pa_interes["salint"];
	 		$ld_diabas    = $pa_interes["diabas"];
	 		$ld_diacom    = $pa_interes["diacom"];
	 		$ld_monant    = $pa_interes["monant"];
	 		$ld_monacuant = $pa_interes["monacuant"];
	 		$ld_monantant = $pa_interes["monantant"];
	 		$ld_salparant = $pa_interes["salparant"];
			$ld_taspor    = $pa_interes["taspor"];
	 		$li_diaint    = $pa_interes["diaint"];
	 		$ld_intper    = ($pa_interes["intper"]);
	 		$ld_intacu    = (floatval($ld_intacu) + floatval($ld_intper));
	 		$ld_saltotant = (floatval($ld_salparant) + floatval($ld_intacu));
	 	
			$pa_datos["periodo"][$li_registro]   = $ls_periodo;
			$pa_datos["salbas"][$li_registro]    = $this->io_function_sb->uf_cton($ld_salbas);
			$pa_datos["incbonvac"][$li_registro] = $this->io_function_sb->uf_cton($ld_incbonvac);
			$pa_datos["incbonnav"][$li_registro] = $this->io_function_sb->uf_cton($ld_incbonnav);
			$pa_datos["salint"][$li_registro]    = $this->io_function_sb->uf_cton($ld_salint);
			$pa_datos["diabas"][$li_registro]    = $this->io_function_sb->uf_cton($ld_diabas);
			$pa_datos["diacom"][$li_registro]    = $this->io_function_sb->uf_cton($ld_diacom);
			$pa_datos["monant"][$li_registro]    = $this->io_function_sb->uf_cton($ld_monant);
			$pa_datos["monacuant"][$li_registro] = $this->io_function_sb->uf_cton($ld_monacuant);
			$pa_datos["monantant"][$li_registro] = $this->io_function_sb->uf_cton($ld_monantant);
			$pa_datos["salparant"][$li_registro] = $ld_salparant;
			$pa_datos["taspor"][$li_registro]    = $ld_taspor;
			$pa_datos["diaint"][$li_registro]    = $li_diaint;
			$pa_datos["intper"][$li_registro]    = $ld_intper;
			$pa_datos["intacu"][$li_registro]    = $ld_intacu;
			$pa_datos["saltotant"][$li_registro] = $ld_saltotant;
		
		}
		$li_registro++;	
		$lb_valido=true;	
	} //end del while	
	//print_r($pa_datos);
	return $lb_valido;
  }   
  
  public function generarInteres($po_antig,&$pa_interes)
  {
	 $lb_calculado = true;
	 $ls_periodo   = $this->io_function->uf_convertirdatetobd($po_antig->fecincsue);
	 $ld_salparant = $this->io_function_sb->uf_cton( $po_antig->salparant );
	 
	 $ld_tasa   = $this->consultarTasaInteres($ls_periodo);
	 $li_diaint = 30;
	 $ld_intper = ($ld_salparant*($ld_tasa/100));
	 $ld_intper = ($ld_intper/365*$li_diaint);

	 $pa_interes = array('periodo'=>$ls_periodo,'salbas'=>$po_antig->salbas,'incbonvac'=>$po_antig->incbonvac,'incbonnav'=>$po_antig->incbonnav,'salint'=>$po_antig->salint,'diabas'=>$po_antig->diabas,'diacom'=>$po_antig->diacom,'monant'=>$po_antig->monant,'monacuant'=>$po_antig->monacuant,'monantant'=>$po_antig->monantant,'salparant'=>$ld_salparant,'taspor'=>$ld_tasa,'diaint'=>$li_diaint,'intper'=>$ld_intper,'intacu'=>"",'saltotant'=>"");
	
	return $lb_calculado;
  }
   
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarAntiguedad($po_object, $ps_operacion="insertar" )
  {	
  	$lb_guardo   = true;
	$li_registro = 0;
	if ($ps_operacion=="modificar")
	{
		/*while (($li_registro<count($po_object->dt_antig))&&($lb_guardo))
		{
			$lb_guardo = $this->updateData($po_object->dt_antig[$li_registro]);
			$li_registro++;
		} //end del while*/
	}
	else
	{
		while (($li_registro<count($po_object->dt_antig))&&($lb_guardo))
		{
			$lb_guardo = $this->insertData($po_object->dt_antig[$li_registro]);
			$li_registro++;
		} //end del while
	} //end del else
	if ($lb_guardo)
	{
	   $this->io_sql->commit();
	   $this->io_function_sb->message("Los datos fueron actualizados.");
	}
	else
	{
	   $this->io_sql->rollback();
	   $this->io_function_sb->message("No pudo actualizar los datos.");
	}	 
  } //end function guardarAntiguedad
  

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : insertData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function insertData( $po_antig ) 
  {	 
  	$lb_inserto = false;
	$ls_estant ='R';
	$li_liquidacion='0';
	$this->io_sql->begin_transaction();
	
	$ls_fecincsue = $this->io_function->uf_convertirdatetobd($po_antig->fecincsue );
	$ld_salbas    = $this->io_function_sb->uf_cton( $po_antig->salbas );	
	$ld_incbonvac = $this->io_function_sb->uf_cton( $po_antig->incbonvac );
	$ld_incbonnav = $this->io_function_sb->uf_cton( $po_antig->incbonnav );
	$ld_salint    = $this->io_function_sb->uf_cton( $po_antig->salint );
	$ld_salintdia = $this->io_function_sb->uf_cton(0);
	$ld_diabas    = intval( $po_antig->diabas );
	$ld_diacom    = intval( $po_antig->diacom );
	$ld_monant    = $this->io_function_sb->uf_cton( $po_antig->monant );
	
	$ld_monacuant = $this->io_function_sb->uf_cton( $po_antig->monacuant );
	$ld_monantant = $this->io_function_sb->uf_cton( $po_antig->monantant );
	$ld_salparant = $this->io_function_sb->uf_cton( $po_antig->salparant );
	$ld_diaint    = intval( $po_antig->diaint );
	$ld_porint    = $this->io_function_sb->uf_cton( $po_antig->porint );
	$ld_monint    = $this->io_function_sb->uf_cton( $po_antig->monint ); 
	$ld_monacuint = $this->io_function_sb->uf_cton( $po_antig->monacuint ); 
	$ld_saltotant = $this->io_function_sb->uf_cton( $po_antig->saltotant ); 
	if ($ld_monint!=0)
	{ $ls_estcapint="S"; }
	else
	{ $ls_estcapint="N"; } 
	$ls_sql = " INSERT INTO ".$this->as_tabla." (codemp, codper, codnom, fecant, anoserant, messerant, diaserant, salbas, incbonvac, incbonnav, salint, salintdia, diabas, diacom, diaacu, monant, monacuant, monantant, salparant, porint, diaint, monint, monacuint, saltotant, estcapint, estant, liquidacion)
				VALUES ('".$this->ls_codemp."','".$po_antig->codper."','".$po_antig->codnom."','".$ls_fecincsue."','".$po_antig->anoserant."','".$po_antig->messerant."','".$po_antig->diaserant."','".$ld_salbas."','".$ld_incbonvac."','".$ld_incbonnav."','".$ld_salint."','".$ld_salintdia."','".$ld_diabas."','".$ld_diacom."','".$po_antig->diaacu."','".$ld_monant."','".$ld_monacuant."','".$ld_monantant."',
						'".$ld_salparant."','".$ld_porint."','".$ld_diaint."','".$ld_monint."','".$ld_monacuint."','".$ld_saltotant."','".$ls_estcapint."','".$ls_estant."', '".$li_liquidacion."' ) ";											
	$li_inserto = $this->io_sql->execute( $ls_sql );
	if ($li_inserto>0 )
	{ 
	 	$lb_inserto=true;
	 	/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." codper=".$po_antig->codper." codnom=".$po_antig->codnom." ";
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_antig_nomina.html.php",$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	}
	else { $lb_inserto=false; }
    return $lb_inserto;
  }

   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : eliminarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que elimina un registro de dato
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $ps_codper, $ps_codnom)
  {
      $lb_valido  =  false;  
      $this->io_sql->begin_transaction();
	  $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and estant='R' and liquidacion='0' ";
	  $lb_valido  = $this->io_sql->execute( $ls_sql );	
	  
	  if ($lb_valido)
	  {
		$this->io_sql->commit();
		$this->io_function_sb->message("Los Datos fueron eliminados.");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." codper=".$ps_codper." codnom=".$ps_codnom." ";
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_antig_nomina.html.php",$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
      }
  	  else
	  {
		$this->io_sql->rollback();
		$this->io_function_sb->message("Los Datos no pueden ser eliminados.");
	  }
	 return $lb_valido;
  }	  
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de datos
  //    Arguments : $ps_codper -> Codigo del personal
  //      Retorna : $lb_valido:Boolean y $pa_datos: arreglo de registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getAntiguedad($ps_codper,$ps_codnom,&$pa_datos="")
  {
    	$lb_valido = false;
 	$ls_sql    = "SELECT  fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom= '".$ps_codnom."' ORDER BY fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant ASC  ";	
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getAntiguedad de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	}
	else                                   
	{ 
		$lb_valido=false;
	}
	return $lb_valido;
  }  

  
}// end class

?>
