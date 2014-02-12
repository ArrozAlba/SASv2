<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_antiguedad_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa
	   Descripción : Esta clase maneja el acceso de datos de la tabla antiguedad del sistema de presatciones sociales
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_pro_antiguedad_dao extends class_dao
{
   public function sps_pro_antiguedad_dao()
  {
   	$this->class_dao("sps_antiguedad");  //constructor de la clase
	$this->io_seguridad= new sigesp_c_seguridad();
    
	if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getRelationAntig
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un booleano para indicar si existe relacion con la tabla antiguedad
  //    Arguments : $as_codigo -> Parametro que indica el codigo que se buscará en la tabla para chequear la integridad relacional
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getRelationAntig($ps_orden="", &$pa_datos="")
  {
    $lb_valido = false;
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." ".$ps_orden;	
    $rs_data= $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getRelationAntig de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
	}
	else 
	{ 
		$lb_valido=false;
	}
	return $lb_valido;
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
 	$ls_sql = "SELECT fecingper FROM sno_personalnomina WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' ORDER BY fecingper ASC";			  
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
  //     Function : getArticulos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getArticulos( $ps_orden="", &$pa_datos="" )  
  {
  	$lb_valido = false;
	$ps_orden = "ORDER BY numart, conart ASC";
 	$ls_sql    = "SELECT numart,conart FROM sps_articulos ".$ps_orden;			  

   	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getArticulos en Antiguedad_dao " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_function_sb->message("Registro no encontrado "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getIncidencias
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna los dias de incidencias para calculo de antiguedad
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getIncidencias( $ps_codper, $ps_codnom, $pi_ano, &$pa_datos ) 
  {       
  	$lb_valido = false;
	$ps_orden = " ORDER BY f.diabonvacfid,f.diabonfinfid ASC ";
 	$ls_sql    = "SELECT f.diabonvacfid,f.diabonfinfid FROM  sno_personalnomina p, sno_fideiconfigurable f WHERE p.codemp='".$this->ls_codemp."' and p.codper='".$ps_codper."' and p.codnom='".$ps_codnom."' and f.anocurfid='".$pi_ano."' and p.codded=f.codded and p.codtipper=f.codtipper ".$ps_orden;			  
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getIncidencias " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $this->is_msg_error = "No existen registro de incidencia para el año ".$pi_ano ; }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getConfiguracion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna la configuracion de sueldos y estatus del bolovar
  //    Arguments : 
  //                
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getConfiguracion( &$pa_datos )  
  {       
  	$lb_valido = false;
 	$ls_sql    = "SELECT estsue FROM sps_configuracion WHERE id='1' ORDER BY estsue ASC ";			  
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getConfiguracion ");
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getConsultaArticulo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna la configuracion de sueldos y estatus del bolovar
  //    Arguments : 
  //                
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getConsultaArticulo( $ps_numart, $ps_fecvig, &$pa_datos )
  {       
  	$lb_valido = false;
	   
		$ld_fecha  = $this->io_function->uf_convertirdatetobd($ps_fecvig);
		$ls_sql    = "SELECT COUNT(numart) AS count FROM sps_articulos WHERE numart='".$ps_numart."' and fecvig='".$ld_fecha."' ";		
		$rs_data   = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_function_sb->message("Error en getConsultaArticulo ");
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_caant=$row["count"];
			if ($ls_caant==0)
			{ 	$lb_valido=true; $this->io_function_sb->message(" No existe Articulo Nº ".$ps_numart." con fecha vigente a ".$ps_fecvig ); }
			else
			{
				$ls_sql    = "SELECT numlitart,operador,canmes,tiempo,diasal,condicion,estacu,diaacu,numcon FROM sps_articulos WHERE numart='".$ps_numart."' and fecvig='".$ld_fecha."' ORDER BY operador,canmes,tiempo,diasal,condicion,estacu,diaacu,numcon DESC ";				  
				$rs_data   = $this->io_sql->select($ls_sql);
				if($rs_data==false)
				{
					$this->io_function_sb->message("Error en getConsultaArticulo ");
				}
				elseif($row=$this->io_sql->fetch_row($rs_data))
				{
					 $lb_valido=true;
					 $pa_datos =$this->io_sql->obtener_datos($rs_data);
					 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos); 
				}
					
			}
		} //elseif
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  }
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getSalarioBase
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna los salarios del empleado
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getSalarioBase( $ps_codper, $ps_codnom,$pd_periodo,&$pa_datos )  
  {       
  	$lb_valido = false;
	$ld_periodo= $this->io_function->uf_convertirdatetobd($pd_periodo);
 	$ls_sql    = "SELECT monsuebas FROM sps_sueldos WHERE codemp='".$this->ls_codemp."'  and codper='".$ps_codper."' and codnom='".$ps_codnom."' and fecincsue<='".$ld_periodo."' order by fecincsue desc ";
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getSalarioBase " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{   
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getSalarioIntegral
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna la fecha de Ingrso del empleado
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getSalarioIntegral( $ps_codper, $ps_codnom, $pd_periodo, &$pa_datos )   
  {       
  	$lb_valido = false;
	$ld_periodo= $this->io_function->uf_convertirdatetobd($pd_periodo);
    $ls_sql    = "SELECT monsueint FROM sps_sueldos WHERE codemp='".$this->ls_codemp."'  and codper='".$ps_codper."' and codnom='".$ps_codnom."' and fecincsue<='".$ld_periodo."' order by fecincsue desc ";
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getSalarioIntegral " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{   
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
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
  function getAnticipos( $ps_codper, $ps_codnom,&$pa_datos )   
  {       
  	$lb_valido = false;
	$ls_sql    = "SELECT fecantper, monant FROM sps_anticipos WHERE codemp='".$this->ls_codemp."'  and codper='".$ps_codper."' and codnom='".$ps_codnom."' order by fecantper, monant asc ";
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
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getTasaInteres
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna la tasa de interes segun el periodo
  //    Arguments : $pi_anotasint -> año tasa de interes
  //                $pi_mestasint -> mes de tasa de interes
  //      Retorna : valor de la tasa de interes
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getTasaInteres( $pi_anotasint, $pi_mestasint,&$pa_datos )   
  {       
  	$lb_valido = false;
	$ls_sql    = "SELECT valtas FROM sps_tasa_interes WHERE anotasint='".$pi_anotasint."'  and mestasint='".$pi_mestasint."' ORDER BY valtas ASC ";
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getTasaInteres " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{   
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : consultarAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de datos
  //    Arguments : $ps_codper -> Codigo del personal
  //      Retorna : $lb_valido:Boolean y $pa_datos: arreglo de registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function consultarAntiguedad($po_antig)
  {
    $lb_valido = false;
    $ls_fecper = $this->io_function->uf_convertirdatetobd($po_antig->fecincsue );
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$po_antig->codper."' and codnom= '".$po_antig->codnom."' AND fecant='".$ls_fecper."'  ";	
    $rs_data= $this->io_sql->select($ls_sql);                                      
	if($rs_data==false)
	{
		$this->io_msg->message("Error en consultarAntiguedad de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true; 
	}
	else                                   
	{ 
		$lb_valido=false;
	}
	return $lb_valido;
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
		$lb_existe = false;
		while (($li_registro<count($po_object->dt_antig))&&($lb_guardo))
		{
			$lb_existe = $this->consultarAntiguedad($po_object->dt_antig[$li_registro]);
			if (!$lb_existe)
			{ 
				$lb_guardo = $this->insertData($po_object->dt_antig[$li_registro]);
			}	
			$li_registro++;
		} //end del while
	} //end del else
	if ($lb_guardo)
	{
	   $this->io_sql->commit();
	      //////////////////////////////////         SEGURIDAD               /////////////////////////////
		$po_antig = $po_object->dt_antig[0];		
		$ls_evento="INSERT";
		$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." codper=".$po_antig->codper." codnom=".$po_antig->codnom;
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_antiguedad.html.php",$ls_descripcion);
	    /////////////////////////////////         SEGURIDAD               /////////////////////////////
	   $this->io_function_sb->message("Los datos fueron actualizados.");
	}
	else
	{
	   $this->io_sql->rollback();
	   $this->io_function_sb->message("No pudo actualizar los datos.");
	}	 
  } //end function guardarAntiguedad
  
  function uf_convertir_decimal_bd( $pd_decimal)
  {
  		$ld_decaux = str_replace(".", "", $pd_decimal );	
		$ld_dec    = str_replace(",", ".", $ld_decaux );	
  		return $ld_dec;
  }
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
	$ld_salbas    = $this->uf_convertir_decimal_bd( $po_antig->salbas );	
	$ld_incbonvac = $this->uf_convertir_decimal_bd( $po_antig->incbonvac );
	$ld_incbonnav = $this->uf_convertir_decimal_bd( $po_antig->incbonnav );
	$ld_salint    = ($ld_salbas+$ld_incbonvac+$ld_incbonnav);
	$ld_salint    = $this->uf_convertir_decimal_bd( $ld_salint );
	$ld_salintdia = $this->uf_convertir_decimal_bd( $po_antig->salintdia );
	$ld_monant    = $this->uf_convertir_decimal_bd( $po_antig->monant );
	$ld_monacuant = $this->uf_convertir_decimal_bd( $po_antig->monacuant );
	$ld_monantant = $this->uf_convertir_decimal_bd( $po_antig->monantant );
	$ld_salparant = $this->uf_convertir_decimal_bd( $po_antig->salparant );
	$ld_porint    = $this->uf_convertir_decimal_bd( $po_antig->porint );
	$ld_monint    = $this->uf_convertir_decimal_bd( $po_antig->monint ); 
	$ld_monacuint = $this->uf_convertir_decimal_bd( $po_antig->monacuint ); 
	$ld_saltotant = $this->uf_convertir_decimal_bd( $po_antig->saltotant ); 
	
	$ls_sql = " INSERT INTO ".$this->as_tabla." (codemp, codper, codnom, fecant, anoserant, messerant, diaserant, salbas, incbonvac, incbonnav, salint, salintdia, diabas, diacom, diaacu, monant, monacuant, monantant, salparant, porint, diaint, monint, monacuint, saltotant, estcapint, estant, liquidacion)
				VALUES ('".$this->ls_codemp."','".$po_antig->codper."','".$po_antig->codnom."','".$ls_fecincsue."','".$po_antig->anoserant."','".$po_antig->messerant."','".$po_antig->diaserant."','".$ld_salbas."','".$ld_incbonvac."','".$ld_incbonnav."','".$ld_salint."','".$ld_salintdia."','".$po_antig->diabas."','".$po_antig->diacom."','".$po_antig->diaacu."','".$ld_monant."','".$ld_monacuant."','".$ld_monantant."',
						'".$ld_salparant."','".$ld_porint."','".$po_antig->diaint."','".$ld_monint."','".$ld_monacuint."','".$ld_saltotant."','".$po_antig->estcapint."','".$ls_estant."', '".$li_liquidacion."' ) ";						
	$li_inserto = $this->io_sql->execute( $ls_sql );
	if ($li_inserto>0 )
	{ 
	 	$lb_inserto=true;
	}
	else { $lb_inserto=false; }
    return $lb_inserto;
  }
   
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetalleAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de datos
  //    Arguments : $ps_orden -> Parametro que indica el orden de los datos
  //                $ps_codper -> Codigo del personal
  //                $pd_fechainicio
  //                $pd_fechafin
  //      Retorna : $lb_valido:Boolean y $pa_datos: arreglo de registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDetalleAntiguedad($ps_orden="",$ps_codper,$pd_fechainicio,$pd_fechafin,&$pa_datos="")
  {
   	$lb_valido = false;
	$ps_orden = "ORDER BY fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant ASC";
	$ld_fecini  = $this->io_function->uf_convertirdatetobd($pd_fechainicio);
	$ld_fecfin  = $this->io_function->uf_convertirdatetobd($pd_fechafin);
 	$ls_sql    = "SELECT fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant FROM ".$this->as_tabla." WHERE codper='".$ps_codper."' and fecant between '".$ld_fecini."' and '".$ld_fecfin."' ".$ps_orden;	
	
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getDetalleAntiguedad de la tabla ".$this->as_tabla );
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
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : getPersonal
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información del personal que posee calculos de antiguedad
  //    Arguments : $ps_codper -> codigo de personal,$ps_nomper:nombre personal,$ps_cedper:cedula personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function getPersonal($ps_order,&$pa_datos="",$ps_codper,$ps_nomper,$ps_apeper)
   {
  		 $lb_valido = false;
		 //$ps_order  = "ORDER BY a.codper, p.nomper, p.apeper, a.codnom, n.desnom ASC ";
		 $ls_sql    = "SELECT DISTINCT a.codper,p.nomper,p.apeper,a.codnom,n.desnom 
		               FROM sps_antiguedad a, sno_personal p, sno_nomina n
					   WHERE a.codemp=p.codemp and a.codper=p.codper and a.codemp=n.codemp and a.codnom=n.codnom and a.codemp='".$this->ls_codemp."' ";
         if ($ps_codper != "")
	   	 $ls_sql .= " AND p.codper LIKE '$ps_codper%' ";
	     if ($ps_nomper != "")
	 	     $ls_sql .= " AND p.nomper LIKE '$ps_nomper%' ";
	 	 if ($ps_apeper != "")
	 	     $ls_sql .= " AND p.apeper LIKE '$ps_apeper%' ";
	 	 $ls_sql .= $ps_orden; 					   
	
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en getPersonal ".$this->as_tabla );
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
  //     Function : get_personal_antig
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información del personal que posee calculos de antiguedad
  //    Arguments : $ps_codper -> codigo de personal,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function get_personal_antig($ps_codper,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ps_order  = "ORDER BY a.codper, p.nomper, p.apeper, a.codnom, n.desnom ASC ";
		 $ls_codper = $ps_codper."%";
		 $ls_nomper = $ps_nomper."%"; 
		 $ls_apeper = $ps_apeper."%";
		 $ls_sql    = "SELECT DISTINCT a.codper, p.nomper, p.apeper, a.codnom, n.desnom 
		               FROM sps_antiguedad a, sno_personal p, sno_nomina n
					   WHERE a.codemp=p.codemp and a.codper=p.codper and a.codemp=n.codemp and a.codnom=n.codnom and a.codemp='".$this->ls_codemp."' and p.codper like '".$ls_codper."' and p.nomper like '".$ls_nomper."' and p.apeper like '".$ls_apeper."' ".$ps_order;
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en get_personal_antig ".$this->as_tabla );
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
  //     Function : getNominas
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getNominas( $ps_orden="", &$pa_datos="" )  
  {
  	$lb_valido = false;
 	$ls_sql    = "SELECT codnom, desnom FROM sno_nomina ORDER BY codnom, desnom ASC";			  

    	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getNominas " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_function_sb->message("Registro no encontrado "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getCabeceraReporteAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de datos
  //    Arguments : $ps_orden -> Parametro que indica el orden de los datos
  //                $ps_codper1 -> Codigo del personal DESDE
  //                $ps_codper2 -> Codigo del personal HASTA
  //      Retorna : $lb_valido:Boolean y $pa_datos: arreglo de registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getCabeceraReporteAntiguedad($ps_orden="",$ps_codper,&$pa_datos="")
  {
    	$lb_valido = false;
	$ps_orden = "ORDER BY p.cedper,p.nomper,p.apeper ASC ";
	$ls_sql    = "SELECT p.cedper,p.nomper,p.apeper FROM  sps_antiguedad s, sno_personal p WHERE s.codemp=p.codemp and s.codper=p.codper and s.codemp='".$this->ls_codemp."' and s.codper ='".$ps_codper."' ".$ps_orden;	
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getCabeceraReporteAntiguedad de la tabla ".$this->as_tabla );
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
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getCabeceraReporteDeuda
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_codper1 -> Codigo de personal 1
  //                $pa_codper2 -> Codigo de personal 2
  //                $pa_codnom  -> Codigo de nomina  
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getCabeceraReporteDeuda($ps_orden,$ps_codper1,$ps_codper2,$ps_codnom,&$pa_datos="")
  {       
  		$lb_valido = false;
		$ps_orden = "ORDER BY s.codper,p.cedper,p.nomper,p.apeper,n.desnom ASC";
		if ($ps_codnom=='all')
		{   
		    $ls_sql = "SELECT DISTINCT s.codper,p.cedper,p.nomper,p.apeper,n.desnom FROM sps_antiguedad s, sno_personal p,sno_nomina n WHERE s.codemp=p.codemp and s.codper=p.codper and s.codemp=n.codemp and s.codnom=n.codnom and s.codemp='".$this->ls_codemp."' and s.codper between '".$ps_codper1."' and '".$ps_codper2."' "; 
		}
		else
		{ 
			$ls_sql = "SELECT DISTINCT s.codper,p.cedper,p.nomper,p.apeper,n.desnom FROM sps_antiguedad s, sno_personal p,sno_nomina n WHERE s.codemp=p.codemp and s.codper=p.codper and s.codemp=n.codemp and s.codnom=n.codnom and s.codemp='".$this->ls_codemp."' and s.codper between '".$ps_codper1."' and '".$ps_codper2."' and s.codnom='".$ps_codnom."' "; 
		    $ls_sql.= $ps_orden; 
		}	
		
		$rs_data = $this->io_sql->select($ls_sql);
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			 $lb_valido=true;
			 $pa_data =$this->io_sql->obtener_datos($rs_data);
			 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		}
		elseif($rs_data==false)
		{
			$this->io_msg->message("Error en getCabeceraReporteDeuda de la tabla ".$this->as_tabla );
			$lb_valido=false;
		}
		return $lb_valido;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetalleDeuda
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_codper -> Codigo de personal 1
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getDetalleDeuda($ps_orden,$pa_codper,&$pa_datos="")
  {
		$lb_valido = false;  
		$ps_orden = "ORDER BY fecant,diaacu,monant,monantant,monint ASC";
		$ls_sql = "SELECT fecant,diaacu,monant,monantant,monint  FROM sps_antiguedad WHERE estant='R' and codper='".$pa_codper."' ".$ps_orden; 
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_msg->message("Error en getDetalleDeuda de la tabla ".$this->as_tabla );
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
	$ps_orden = " ORDER BY fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant ASC";
 	$ls_sql    = "SELECT fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom= '".$ps_codnom."' ".$ps_orden;	
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
			//////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." codper=".$ps_codper." codnom=".$ps_codnom;
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_antiguedad.html.php",$ls_descripcion);
	    /////////////////////////////////         SEGURIDAD               /////////////////////////////
		$this->io_function_sb->message("Los Datos fueron eliminados.");
      }
  	  else
	  {
		$this->io_sql->rollback();
		$this->io_function_sb->message("Los Datos no pueden ser eliminados.");
	  }
	 return $lb_valido;
  }	  

  
}// end class

?>
