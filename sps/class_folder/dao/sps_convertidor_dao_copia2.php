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
		 $this->io_sql->begin_transaction();
		 $lb_valido = $this->crearNomina(); 
		 if ($lb_valido)
		 {
			$lb_valido = $this->crearSubNomina();
			$lb_valido = $this->crearCargo();
			$lb_valido = $this->crearTabulador();
			if ($lb_valido) $lb_valido = $this->crearGrado();
			$lb_existe = $this->selectUnidadAdmin();
			if (!$lb_existe)
			{ $lb_valido = $this->crearUnidadAdmin();}
			$lb_valido = $this->crearAsignacionCargo();
		 }
		 
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
	    
		 } //fin del for
	     print "registro ".$contador;
	}
	
  }
  
  public function insertData($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres)
  {	
  	$lb_inserto = false;
  	$lb_existe  = false;
  	$lb_valido  = false;
	$codper = str_pad(trim($cedula), 10, '0', STR_PAD_LEFT);	
	$lb_existe = $this->selectPersonal($cedula,&$pa_personal);
	
	if ($lb_existe)
	{
		$lb_existe = $this->selectPersonalNomina($codper,&$pa_pnomina);
		if ($lb_existe)
		{
			$lb_inserto=$this->insertAntiguedad($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres);
		}
		else
		{
			$lb_inserto = $this->insertPersonalNomina($codper,$fecha_d,$fecha_h,$sueldo);
			if ($lb_inserto)
			{   
				$lb_inserto=$this->insertAntiguedad($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres);
			}			
		}		
	}
	else
	{
		$lb_inserto = $this->insertPersonal($cedula,$fecha_d);
		if ($lb_inserto)
		{  
			$lb_inserto = $this->insertPersonalNomina($codper,$fecha_d,$fecha_h,$sueldo);
			if ($lb_inserto)
			{   
				$lb_inserto=$this->insertAntiguedad($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres);
			}						
		}
			
	}
	return $lb_inserto;
  }
  
  public function crearNomina()
  {
	$ls_sql ="INSERT INTO sno_nomina(codemp,codnom,desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom,tipnom,subnom,racnom,adenom,espnom,ctnom,ctmetnom,diabonvacnom,diareivacnom,diainivacnom,diatopvacnom,diaincvacnom,consulnom,descomnom,codpronom,codbennom,conaponom,cueconnom,notdebnom,numvounom,recdocnom,tipdocnom,recdocapo,tipdocapo,perresnom,conpernom,conpronom,titrepnom,codorgcestic)
           VALUES ('".$this->ls_codemp."', '0999', 'NOMINA HISTORICA DE PRESTACIONES', '2', 'MENSUAL', '2008', '2001-11-15 00:00:00', '011', 12, 0, '0', '0', '0', '0', '0', '', 0, 0, 0, 0, 0, '', 'B', '----------', '----------', '', '', '0', '0', '0', '', '0', '', '000', '0', '', '', '')";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function crearSubNomina()
  { 
	$ls_sql ="INSERT INTO sno_subnomina(codemp, codnom, codsubnom, dessubnom)
           VALUES ('".$this->ls_codemp."', '0999', '0000000000', 'Sin Subnomina')";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function crearCargo()
  { 
	$ls_sql ="INSERT INTO sno_cargo (codemp, codnom, codcar, descar)
           VALUES ('".$this->ls_codemp."', '0999', '0000000000', 'Sin Cargo')";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function crearTabulador()
  { 
	$ls_sql ="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab,maxpasgra)
           VALUES ('".$this->ls_codemp."', '0999', '00000000000000000000','Sin Tabulador',0)";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function crearGrado()
  { 
	$ls_sql ="INSERT INTO sno_grado (codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra, moncomgraaux, monsalgraaux)
           VALUES ('".$this->ls_codemp."', '0999', '00000000000000000000', '00', '00', 0.00, 0.00, 0.00, 0.00)";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function crearAsignacionCargo()
  { 
	$ls_sql ="INSERT INTO sno_asignacioncargo (codemp, codnom, codasicar, denasicar, claasicar, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, codtab, codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar)
           VALUES ('".$this->ls_codemp."', VALUES ('0001', '0999', '0000000', 'Sin Asignación de Cargo', '0', '0000', '00', '00', '00', '00', '00000000000000000000', '00', '00', '000', '0000', 0, 0, '000000000000000000000000000000000')";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function crearUnidadAdmin()
  { 
	$ls_sql ="INSERT INTO sno_unidadadmin (codemp, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, desuniadm, codprouniadm, codproviauniadm)
           VALUES ('".$this->ls_codemp."','0000', '00', '00', '00', '00', 'SIN UNIDAD ADMINISTRATIVA', '000000000000000000020000000000000', '')";
 	   	
	$li_inserto = $this->io_sql->execute( $ls_sql );

	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else { $lb_inserto=false; 
	}
    return $lb_inserto;
  }
  public function selectUnidadAdmin()
  {
	$ls_sql ="SELECT * FROM sno_unidadadmin WHERE codemp='".$this->ls_codemp."' AND minorguniadm='0000' AND ofiuniadm='00' AND uniuniadm='00' AND depuniadm='00' AND prouniadm='00' ";
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en selectUnidadAdmin" );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
	}
	else { $lb_valido=false; }	
	return $lb_valido;
  }
  public function selectPersonal($ps_cedula,&$pa_datos)
  {
  	print "-selectPersonal-:".$this->ls_codemp;
	$ls_sql ="SELECT * FROM sno_personal WHERE codemp='".$this->ls_codemp."' AND cedper='".$ps_cedula."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en selectPersonal" );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $lb_valido=false; }	
	return $lb_valido;
  }
  public function insertPersonal($cedula,$fecha_d)
  {
   	$codper = str_pad(trim($cedula), 10, '0', STR_PAD_LEFT);
  
	$ls_sql ="INSERT INTO sno_personal(codemp,codper,cedper,nomper,apeper,dirper,fecnacper,edocivper,telhabper,telmovper,sexper,estaper,pesper,codpro,nivacaper,catper,cajahoper,numhijper,contraper,tipvivper,tenvivper,monpagvivper,ingbrumen,cuecajahoper, cuelphper, cuefidper, fecingadmpubper, vacper, porisrper, fecingper, anoservpreper,cedbenper,fecegrper,estper,fotper,codpai,codest,codmun,codpar,obsper,cauegrper,obsegrper,nacper,coreleper,cenmedper,turper,horper,hcmper,tipsanper,monpagvivperaux,ingbrumenaux,codcom,codran,numexpper,codpainac,codestnac)
			VALUES('".$this->ls_codemp."', '".$codper."', '".$cedula."', 'SIN NOMBRE', 'SIN APELLIDO', '', '1900-01-01 00:00:00', 'S', '', '', 'M', 0.00, 0.00, '01', '0', '---', '0', 0, '', 0, '---', 0.00, 0.00, '', '', '', '".$fecha_d."', '', 0.00, '".$fecha_d."', 0, '', '1900-01-01 00:00:00', '2', '', '058', '001', '001', '001', '', '', '', 'V', '', '', '', '', '', '', 0.00, 0.00, '', '', '', '---', '---')";
	 
	$li_inserto = $this->io_sql->execute( $ls_sql );
	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else
	{ 
		$lb_inserto=false;
		$this->io_function_sb->message("Error en insertPersonal, Cédula: ".$cedula );   
	}
    return $lb_inserto;
  }
  public function selectPersonalNomina($ps_codper,&$pa_datos)
  {
  	print "-selectPersonalNomina-:";
  	
	$ls_sql ="SELECT * FROM sno_personalnomina WHERE codemp='".$this->ls_codemp."' AND codnom='0999' AND codper='".$ps_codper."'";
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
	else { $lb_valido=false; }	
	return $lb_valido;
  }
  public function insertPersonalNomina($codper,$fecha_d,$fecha_h,$sueldo)
  {
  
  	$ls_sql ="INSERT INTO sno_personalnomina(codemp, codnom, codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, sueperaux, sueintperaux, sueproperaux, codunirac, pagtaqper, fecascper, grado)
           VALUES ('".$this->ls_codemp."', '0999', '".$codper."', '0000000000', '0000000', '00000000000000000000', '00', '00', '".$sueldo."', 0.00, '0000', '00', '00', '00', '00', 0, '', '', '', '0000000000', '".$fecha_d."', '0', '', '1900-01-01 00:00:00', '300', '0303', '0', '01', '".$sueldo."', 0, 0.00, '', '1900-01-01 00:00:00', '1900-01-01 00:00:00', '', '0000', '0000', '0000', 'TA', '0000', '000', '', 0.00, 0.00, 0.00, '', 0, '1900-01-01', '0000')";
    print $ls_sql;            
	$li_inserto = $this->io_sql->execute( $ls_sql );
	if ($li_inserto)
	{ 
	 	$lb_inserto=true;	
	}
	else
	{ 
		$lb_inserto=false;
		$this->io_function_sb->message("Error en insertPersonalNomina, Personal: ".$codper ); 
	}
    return $lb_inserto;
  }
  
  public function insertAntiguedad($cedula,$fecha_d,$fecha_h,$porcentaje,$dias,$interes,$antiguedad,$sueldo,$adelanto,$f_adelanto,$periodo,$totinteres)
  {
  	
  	$codper = str_pad($cedula, 10, '0', STR_PAD_LEFT);
  	$suedia = $sueldo/30;
	$ls_sql ="INSERT INTO sps_antiguedad(codemp, codper, codnom, fecant, anoserant, messerant, diaserant, salbas, incbonvac, incbonnav, salint, salintdia, diabas, diacom, diaacu, monant, monacuant, monantant, salparant, porint, diaint, monint, monacuint, saltotant, estcapint, estant, liquidacion)
           VALUES ('".$this->ls_codemp."', '".$codper."', '0999', '".$fecha_h."', 0, 0, 0, 0.00, 0.00, 0.00, '".$sueldo."', '".$suedia."', 5, 0, 0,'".$antiguedad."', 0.00, '".$adelanto."', 0.00, '".$porcentaje."', '".$dias."', '".$interes."', 0.00, 0.00, 'N', 'R', '0')";	
	$li_inserto = $this->io_sql->execute( $ls_sql );
	if ($li_inserto>0 )
	{ 
	 	$lb_inserto=true;	
	}
	else
	{ 
		$lb_inserto=false;
		$this->io_function_sb->message("Error en insertAntiguedad, Personal: ".$codper ); 
	}
    return $lb_inserto;
  }
  
  //public function crearTablaTemp()
//  {
//      $ls_sql = "	create temporary table tmp_antiguedad (
//					   cedula            char(12)              not null,
//					   fecha_d           timestamp               null,
//					   fecha_h           timestamp               null,
//					   porcentaje           float8               null,
//					   dias                 int                  null, 
//					   interes              float8               null,
//					   antiguedad           float8               null,
//					   sueldo               float8               null,
//					   adelanto             float8               null,
//					   f_adelanto          timestamp             null,
//					   periodo             varchar(10)           null,
//					   totinteres           float8               null,
//					   constraint pk_tmp_antiguedad primary key (cedula) ) ";
//	 $li_actualizo = $this->io_sql->execute( $ls_sql );	
//	 print 	"actualizo".$li_actualizo;
//	 print  "mensaje".$this->message;		   
//	 if ($li_actualizo > 0)
//	 {
//		$lb_crear = true;
//		print "listo";
//	 }
//	 else
//	 { 	$lb_crear = false;
//	 	print "no listo";
//	  }		 
//	return $lb_crear;
//  }
  
  
     

}
?>
