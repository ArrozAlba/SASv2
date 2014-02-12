<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_convertidor_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa 
	   Descripción : Esta clase maneja el acceso de archivo TXT para convertirlos a la BD
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
		

class sps_convertidor_dao extends class_dao
{
  
  var $contador=0;
  
  public function sps_convertidor_dao()
  {
    $this->class_dao("sps_antiguedad");  //constructor de la clase
    $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];  
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : convertirData
  //      Alcance : Publico
  //         Tipo : String 
  //  Descripción : Función que lee data de un archivo TXT para almacenar en la BD
  //    Arguments :
  //      Retorna : 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function convertirData($ps_archivo)
  {
    $nombre_archivo="../../txt/".$ps_archivo; 
    //Chequea si existe el archivo.
	if (file_exists("$nombre_archivo"))
	{
		 $li_count=0;
    	 $li_count2=0;
		 $this->io_sql->begin_transaction();
		 set_time_limit(180000);
		 $archivo = file("$nombre_archivo");
		 $numlineas = count($archivo);
		 
		 for($i=0; $i<$numlineas; $i++)
		 { 
		 	$linea = $archivo[$i];
		 	$len = strlen($linea);  //tamaño de caracteres de la linea
		    $pos = strpos($linea,'|');
		    $cedula = substr($linea,0,$pos);
		    
		    $pos = strpos($cedula,'.');
		    $cedula = substr($cedula,0,$pos);
		    
		    $cadena1 = substr(strstr($linea,'|'),1);
		    $pos = strpos($cadena1,'|');
		    $fecha_d = substr($cadena1,0,$pos);
		    		    
		    $cadena2 = substr(strstr($cadena1,'|'),1);
		    $pos = strpos($cadena2,'|');
		    $fecha_h = substr($cadena2,0,$pos);
		    
		    $cadena3 = substr(strstr($cadena2,'|'),1);
		    $pos = strpos($cadena3,'|');
		    $porcentaje = substr($cadena3,0,$pos);
		    
		    $cadena4 = substr(strstr($cadena3,'|'),1);
		    $pos = strpos($cadena4,'|');
		    $dias = substr($cadena4,0,$pos);
		    
		    $cadena5 = substr(strstr($cadena4,'|'),1);
		    $pos = strpos($cadena5,'|');
		    $interes = substr($cadena5,0,$pos);
		    
		    $cadena6 = substr(strstr($cadena5,'|'),1);
		    $pos = strpos($cadena6,'|');
		    $antiguedad = substr($cadena6,0,$pos);
		    		    
		    $cadena7 = substr(strstr($cadena6,'|'),1);
		    $pos = strpos($cadena7,'|');
		    $sueldo = substr($cadena7,0,$pos);
		    
		    $cadena8 = substr(strstr($cadena7,'|'),1);
		    $pos = strpos($cadena8,'|');
		    $adelanto = substr($cadena8,0,$pos);
			
			$cadena9 = substr(strstr($cadena8,'|'),1);
		    $pos = strpos($cadena9,'|');
		    $f_adelanto = substr($cadena9,0,$pos);
			
			$cadena10 = substr(strstr($cadena9,'|'),1);
		    $pos = strpos($cadena10,'|');
		    $periodo = substr($cadena10,0,$pos);
			
			$cadena11 = substr(strstr($cadena10,'|'),1);
		    $pos = strpos($cadena11,'|');
		    $totinteres = substr($cadena11,0,$pos);
			
			$lb_inserto=$this->insertData($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres);
	    	if (!$lb_inserto) { $li_count2++; }
	    	else { $li_count++; }
	    	
		 } //fin del for
	     $this->io_function_sb->message("Proceso Concluido. Registros Incluidos: ".$li_count."   Registros No Incluidos: ".$li_count2);
	}
	else { $this->io_function_sb->message("No existe el archivo. ");}
  }
  
  public function insertData($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres)
  {	
  	$lb_inserto = false;
  	$lb_existe  = false;
    $this->io_sql->begin_transaction();
	$lb_existe = $this->selectPersonalNomina($cedula,&$pa_pnomina);
	if ($lb_existe)
	{
		$lb_inserto=$this->insertAntiguedad($pa_pnomina,$cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres);
	}
	if ($lb_inserto)
	{ $this->io_sql->commit(); }
	else
	{ $this->io_sql->rollback(); }
	return $lb_inserto;
  }
 
  public function selectPersonalNomina($cedula,&$pa_datos)
  {
  	$codper = str_pad(trim($cedula), 10, '0', STR_PAD_LEFT);
	$ls_sql ="SELECT codemp,codper,codnom FROM sno_personalnomina WHERE codemp='".$this->ls_codemp."' AND codper='".$codper."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en selectPersonalNomina" );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { 
	
	$mensaje = "Error al seleccionar Personal. La persona  ".$cedula." No se encuentra en sno_personalnomina";
	$this->uf_crearTxtError($mensaje.'   '.$this->io_sql->message);
	$lb_valido=false; 
	
	
	}	
	return $lb_valido;
  }

  public function selectAntiguedad($ps_codemp,$ps_codnom,$ps_codper,$pd_fecha)
  {
  	
	$ls_sql ="SELECT * FROM sps_antiguedad WHERE codemp='".$ps_codemp."' AND codnom='".$ps_codnom."' AND codper='".$ps_codper."' AND fecant='".$pd_fecha."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en selectAntiguedad" );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
	}
	else { $lb_valido=false; }	
	return $lb_valido;
  }
  
  public function insertAntiguedad($pa_pnomina,$cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres)
  {
  	$lb_existe = false;
	$ls_codemp=trim($pa_pnomina["codemp"][1]);
  	$ls_codnom=trim($pa_pnomina["codnom"][1]);
	$ls_codper=trim($pa_pnomina["codper"][1]);
  	
  	$lb_existe = $this->selectAntiguedad($ls_codemp,$ls_codnom,$ls_codper,$fecha_h);
  	if(!$lb_existe)
	{	
	  	$suedia = ($sueldo/30);
	  	if ($adelanto=="")   $adelanto=0.00; 
	  	if ($porcentaje=="") $porcentaje=0.00;
	  	if ($dias=="")       $dias=0;
	  	if ($interes=="")    $interes=0.00; 
	  	if ($totinteres=="") $totinteres=0.00;
		   //ojo
		$sueldo=number_format($sueldo,2,'.','');
		$suedia=number_format($suedia,2,'.',''); 
		$antiguedad=number_format($antiguedad,2,'.',''); 
		$adelanto=number_format($adelanto,2,'.','');
		$porcentaje=number_format($porcentaje,2,'.','');
		$interes=number_format($interes,2,'.','');
		$totinteres=number_format($totinteres,2,'.','');
		  
		$ls_sql ="INSERT INTO sps_antiguedad(codemp, codper, codnom, fecant, anoserant, messerant, diaserant, salbas, incbonvac, incbonnav, salint, salintdia, diabas, diacom, diaacu, monant, monacuant, monantant, salparant, porint, diaint, monint, monacuint, saltotant, estcapint, estant, liquidacion)
	              VALUES ('".$ls_codemp."', '".$ls_codper."', '".$ls_codnom."', '".$fecha_h."', 0, 0, 0, 0.00, 0.00, 0.00, '".$sueldo."', '".$suedia."', 5, 0, 0,'".$antiguedad."', 0.00, '".$adelanto."', 0.00, '".$porcentaje."', ".intval($dias).", ".$interes.", ".$totinteres.", 0.00, 'N', 'R', '0')";
	                             	   	
		$li_inserto = $this->io_sql->execute( $ls_sql );
		if ($li_inserto)
		{ 
		 	$lb_inserto=true;	
		}
		else
		{ 
			$lb_inserto=false;
			//$this->io_function->uf_convertirmsg($this->io_sql->message);
			$mensaje = "Error en insertAntiguedad en sps_convertidor_dao - Personal: ".$ls_codper;
			$lb_valido = $this->uf_crearTxtError($mensaje.'   '.$this->io_sql->message);
		
		}
	}
	else { $lb_inserto=false; }	
    return $lb_inserto;
  }
  
  function uf_crearTxtError($as_message)
  {
  	 $lb_valido= true;
	 $hoy = date('Ymd');
	 $ls_archivo = "../../txt/error_antig_".$hoy.".txt";
	 $ls_creararchivo = @fopen("$ls_archivo","a+"); //creamos y abrimos el archivo para escritura
	 
	  
	if ($ls_creararchivo)  //Chequea que el archivo este abierto				
	{
		$ls_cadena = $as_message."\r\n";
		if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
		{
			$this->io_function_sb->message("No se puede escribir el archivo ".$ls_archivo);
			$lb_valido = false;
		}
	}
	else
	{
		$this->io_function_sb->message("Error al abrir el archivo ".$ls_archivo);
		$lb_valido = false;
	} 
	if ($lb_valido)
	{  @fclose($ls_creararchivo);  } //cerramos la conexión y liberamos la memoria 
		 
	return   $lb_valido;
   
 }
}
?>