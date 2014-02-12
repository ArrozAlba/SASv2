<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_anticipos_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa 
	   Descripción : Esta clase maneja el acceso de archivo TXT para convertirlos a la BD
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
		

class sps_anticipos_dao extends class_dao
{
  
  public function sps_anticipos_dao()
  {
    $this->class_dao("sps_anticipos");  //constructor de la clase
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
    
    $arreglo = "";	
    $nombre_archivo="../../txt/".$ps_archivo; 
    $li_count=0;
    $li_count2=0;
    //Chequea si existe el archivo.
	if (file_exists("$nombre_archivo"))
	{
		 $this->io_sql->begin_transaction();
		 set_time_limit(90000);
		 $archivo = file("$nombre_archivo");
		 $numlineas = count($archivo);
		 for($i=0; $i<$numlineas; $i++)
		 { 
		 	$linea = $archivo[$i];
		 	$arr_campos=explode("\t",$linea);
		 	$cedula = $arr_campos[0];
		 	$fecha  = $this->io_function_sb->uf_ctod($arr_campos[1]);
		 	$monto  = $this->io_function_sb->uf_cton($arr_campos[2]);

		   	$lb_inserto=$this->insertData($cedula,$fecha,$monto);
	    	
	    	if (!$lb_inserto) { $li_count2++; }
	    	else { $li_count++; }
	     } //fin del for
	     $this->io_function_sb->message("Proceso Concluido. Registros Incluidos: ".$li_count."   Registros No Incluidos: ".$li_count2);
	}
	else { $this->io_function_sb->message("No existe el archivo. ");}
	
  }
  
  public function insertData($cedula,$fecha,$monto)
  {	
  	//str_pad(trim($arr_campos[0]), 10, '0', STR_PAD_LEFT);
	
	$lb_inserto = false;
  	$lb_existe  = false;
  	$lb_valido  = false;
	$this->io_sql->begin_transaction();		
	$lb_existe = $this->selectPersonalNomina($cedula,&$pa_pnomina);
	if ($lb_existe)
	{
		$lb_inserto=$this->insertAnticipos($pa_pnomina,$cedula,$fecha,$monto);
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
	$ls_sql ="SELECT codemp,codnom,codper FROM sno_personalnomina WHERE codper='".$codper."'";
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
	else 
	{ 
		$mensaje = "Error al seleccionar Personal. La persona  ".$cedula." No se encuentra en sno_personalnomina";
		$this->uf_crearTxtError($mensaje.'   '.$this->io_sql->message);
		$lb_valido=false; 
	}	
	return $lb_valido;
  }
  
  public function selectAnticipos($ps_codemp,$ps_codnom,$ps_codper,$pd_fecha)
  {
  	
	$ls_sql ="SELECT * FROM sps_anticipos WHERE codemp='".$ps_codemp."' AND codnom='".$ps_codnom."' AND codper='".$ps_codper."' AND fecantper='".$pd_fecha."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en selectAnticipos" );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
	}
	else { $lb_valido=false; }	
	return $lb_valido;
  }
  
  public function insertAnticipos($pa_pnomina,$cedula,$fecha,$monto)
  {
  	$lb_existe = false;
  	$ls_codemp=trim($pa_pnomina["codemp"][1]);
  	$ls_codnom=trim($pa_pnomina["codnom"][1]);
	$ls_codper=trim($pa_pnomina["codper"][1]);
  	   	  
  	$monto = trim($monto); 
	
	$lb_existe = $this->selectAnticipos($ls_codemp,$ls_codnom,$ls_codper,$fecha);
	if(!$lb_existe)
	{		
		$ls_sql ="INSERT INTO sps_anticipos(codemp, codper, codnom, fecantper, anoserper, messerper, diaserper, motant, mondeulab, monporant, monant, estant, obsant) VALUES ('".$ls_codemp."', '".$ls_codper."', '".$ls_codnom."', '".$fecha."', 0, 0, 0, '', 0.00, 0.00, '".$monto."', '1', 'Datos transferidos mediante convertidor.')";                         	   	
		$li_inserto = $this->io_sql->execute( $ls_sql );
		if ($li_inserto)
		{ 
		 	$lb_inserto=true;	
		}
		else
		{ 
			$lb_inserto=false;
			$mensaje = "Error en insertAnticipos en sps_anticipos_dao - Personal: ".$ls_codper;
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
	 $ls_archivo = "../../txt/error_anticipos_".$hoy.".txt";
	 //Chequea si existe el archivo.
	 $ls_creararchivo = @fopen("$ls_archivo","a+");
		
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
