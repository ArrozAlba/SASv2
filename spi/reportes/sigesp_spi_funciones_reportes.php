<?PHP 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
/*********************************************************************************************************************************/	
class sigesp_spi_funciones_reportes
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	var $io_msg;
	var $io_fecha;
	var $sigesp_int_spi;
/**********************************************************************************************************************************/	
    function  sigesp_spi_funciones_reportes()
    {
		$this->io_function=new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
    }
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_min_coduniadm($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                             &$as_coduniadm)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_min_coduniadm
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro5  // codigo de estructura programatica 5  (referencia)
	 //                     $coduniadm     //  codigo de la unidad administrativas
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_estructura=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	 if (strtoupper($ls_gestor)=="MYSQLT")
	 {
		   if($ls_estructura!="")
		   {
		      $ls_cadena=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)=";
		   }
		   else
		   {
		      $ls_cadena="";
		   }
	 }
	 else
	 {
		   if($ls_estructura!="")
		   {
		       $ls_cadena=" AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5=";
		   }
		   else
		   {
		       $ls_cadena="";
		   }
	 }
	 $ls_sql=" SELECT min(coduniadm) as coduniadm ".
			 " FROM   spi_unidadadministrativa ".
             " WHERE  codemp='".$this->ls_codemp."'  ".
			 "        ".$ls_cadena." '".$ls_estructura."' ".
             " ORDER BY coduniadm ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_min_coduniadm  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_coduniadm=$row["coduniadm"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_codestpro3
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_coduniadm($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                             &$as_coduniadm)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_min_coduniadm
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro5  // codigo de estructura programatica 5  (referencia)
	 //                     $coduniadm     //  codigo de la unidad administrativas
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_estructura=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	 if (strtoupper($ls_gestor)=="MYSQLT")
	 {
		   if($ls_estructura!="")
		   {
		      $ls_cadena=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)=";
		   }
		   else
		   {
		      $ls_cadena="";
		   }
	 }
	 else
	 {
		   if($ls_estructura!="")
		   {
		       $ls_cadena=" AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5=";
		   }
		   else
		   {
		       $ls_cadena="";
		   }
	 }
	 $ls_sql=" SELECT max(coduniadm) as coduniadm ".
			 " FROM   spi_unidadadministrativa ".
             " WHERE  codemp='".$this->ls_codemp."'  ".
			 "        ".$ls_cadena." '".$ls_estructura."' ".
             " ORDER BY coduniadm ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_max_coduniadm  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_coduniadm=$row["coduniadm"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_coduniadm
/********************************************************************************************************************************/
    function uf_spi_reporte_select_min_cuenta(&$as_spi_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_min_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spi_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(spi_cuenta) as spi_cuenta ".
             " FROM spi_cuentas ".
             " WHERE codemp = '".$this->ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_min_cuenta  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_spi_cuenta=$row["spi_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_min_cuenta
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_cuenta(&$as_spi_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spi_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta ".
             " FROM spi_cuentas ".
             " WHERE codemp = '".$this->ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_max_cuenta  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_spi_cuenta=$row["spi_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_cuenta
/*********************************************************************************************************************************/
    function uf_spi_reportes_select_denominacion($as_spi_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spi_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion FROM spi_cuentas WHERE codemp='".$this->ls_codemp."' AND spi_cuenta='".$as_spi_cuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reportes_select_denominacion  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		   if($row=$this->io_sql->fetch_row($rs_data))
		   {
			  $as_denominacion=$row["denominacion"];
		   }
		   $this->io_sql->free_result($rs_data);
		}
		return  $lb_valido;
   }//fin uf_spi_reportes_llenar_datastore_cuentas()
/**********************************************************************************************************************************/

	function uf_get_spi_cuenta($as_spi_cuenta,&$as_spi_ramo,&$as_spi_subramo,&$as_spi_especifica,&$as_spi_subesp)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function : uf_get_spi_cuenta
	  //        Argumentos :  
      //	       Returns : Cuenta separada
	  //	   Description : Envía por referencia una cuenta por ramo, subramo, especifica y sub-específica
	  //        Creado por : Ing. Arnaldo Suárez
	  //    Fecha Creación : 25/05/2008                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_spi_ramo = substr($as_spi_cuenta,0,3);
		$as_spi_subramo = substr($as_spi_cuenta,3,2);
		$as_spi_especifica = substr($as_spi_cuenta,5,2);
		$as_spi_subesp = substr($as_spi_cuenta,7,2);
		
		return  $lb_valido;
   }//fin uf_get_spi_cuenta()
   
   function uf_load_seguridad_reporte($as_sistema,$as_ventanas,$as_descripcion)
   {
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_seguridad_reporte
		//		   Access: public (en todas las clases que usen seguridad)
		//	    Arguments: as_sistema // Sistema del que se desea verificar la seguridad
		//				   as_ventanas // Ventana del que se desea verificar la seguridad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 					Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		$io_seguridad= new sigesp_c_seguridad();
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_logusr=$_SESSION["la_logusr"];
		$la_seguridad["empresa"]=$ls_empresa;
		$la_seguridad["logusr"]=$ls_logusr;
		$la_seguridad["sistema"]=$as_sistema;
		$la_seguridad["ventanas"]=$as_ventanas;
		$ls_evento="REPORT";
		$lb_valido= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$as_descripcion);
		unset($io_seguridad);
		return $lb_valido;
   }// end function uf_load_seguridad
   
    function uf_spi_reporte_select_max_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,
	                                                &$as_codestpro5,&$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_programatica
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1..as_codestpro5  // codigo de la estructura (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(($as_codestpro1=='') || ($as_codestpro1=='0000000000000000000000000'))
	 { 
		 $lb_valido=$this->uf_spi_reporte_select_max_codestpro1($as_codestpro1,&$as_estclahas);
	 }
	 if(($as_codestpro2=='') || ($as_codestpro2=='0000000000000000000000000'))
	 { 
		 $lb_valido=$this->uf_spi_reporte_select_max_codestpro2($as_codestpro1,$as_codestpro2,&$as_estclahas);
	 }
	 if(($as_codestpro3=='') || ($as_codestpro3=='0000000000000000000000000'))
	 { 
		 $lb_valido=$this->uf_spi_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_estclahas);
	 }
	 if(($as_codestpro4=='') || ($as_codestpro4=='0000000000000000000000000'))
	 { 
		 $lb_valido=$this->uf_spi_reporte_select_max_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
		                                                        &$as_estclahas);
	 }
	 if(($as_codestpro5=='') || ($as_codestpro5=='0000000000000000000000000'))
	 { 
		 $lb_valido=$this->uf_spi_reporte_select_max_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
		                                                        $as_codestpro5,&$as_estclahas);
	 }
	return $lb_valido;
  }//uf_spi_reporte_select_max_programatica

/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_codestpro1(&$as_codestpro1,&$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	  if (trim(strtoupper($_SESSION["ls_gestor"])) <> "INFORMIX")
	 {
	  $ls_sql="SELECT * FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' ORDER BY codestpro1  desc  limit 1 ";
	 }
	 else
	 {
	  $ls_sql="SELECT first 1 * FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' ORDER BY codestpro1  desc";
	 } 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
		                           MÉTODO->uf_spi_reporte_select_max_codestpro1  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_estclahas=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_codestpro1
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2,&$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 1 (referencia)          
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro2) as codestpro2         ".
             " FROM   spg_ep2                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        estcla='".$as_estclahas."'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
		                           MÉTODO->uf_spi_reporte_select_max_codestpro2  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		   //$as_estclahas=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_codestpro2

/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3,&$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro3) as codestpro3 ".
             " FROM   spg_ep3                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        codestpro2='".$as_codestpro2."' AND   ".
			 "        estcla='".$as_estclahas."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_max_codestpro3  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		   //$as_estclahas=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_codestpro3
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_codestpro4,&$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_codestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  (referencia)         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro4) as codestpro4 ".
             " FROM   spg_ep4                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        codestpro2='".$as_codestpro2."' AND   ".
			 "        codestpro3='".$as_codestpro3."' AND   ".
			 "        estcla='".$as_estclahas."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_max_codestpro4  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro4=$row["codestpro4"];
		   //$as_estclahas=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_codestpro4
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_codestpro5,
	                                              &$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_codestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro4  // codigo de estructura programatica 5  (referencia) 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spi_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro5) as codestpro5 ".
             " FROM   spg_ep5                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        codestpro2='".$as_codestpro2."' AND   ".
			 "        codestpro3='".$as_codestpro3."' AND   ".
			 "        codestpro4='".$as_codestpro4."' AND   ".
			 "        estcla='".$as_estclahas."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_max_codestpro5  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro5=$row["codestpro5"];
		   //$as_estclahas=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_codestpro3
/**********************************************************************************************************************************/
   function uf_spi_reporte_select_min_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,
	                                                &$as_codestpro5,&$as_estclades)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_min_programatica
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1..as_codestpro5  // estructura presupuestaria (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	 if(($as_codestpro1=='') || ($as_codestpro1=='0000000000000000000000000'))
	 { 
		 $lb_valido=$this->uf_spi_reporte_select_min_codestpro1($as_codestpro1,$as_estclades);
	 }
	 if(($as_codestpro2=='') || ($as_codestpro2=='0000000000000000000000000'))
	 {
		 $lb_valido=$this->uf_spi_reporte_select_min_codestpro2($as_codestpro1,$as_codestpro2,$as_estclades);
	 }
	 if(($as_codestpro3=='') || ($as_codestpro3=='0000000000000000000000000'))
	 {
		 $lb_valido=$this->uf_spi_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_estclades);
	 }
	  if(($as_codestpro4=='') || ($as_codestpro4=='0000000000000000000000000'))
	 {
		 $lb_valido=$this->uf_spi_reporte_select_min_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_estclades);
	 }
	 if(($as_codestpro5=='') || ($as_codestpro5=='0000000000000000000000000'))
	 {
		 $lb_valido=$this->uf_spi_reporte_select_min_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estclades);
	 }
	return $lb_valido;
  }//uf_spi_reporte_select_min_programatica
  /**********************************************************************************************************************************/
 function uf_spi_reporte_select_min_codestpro1(&$as_codestpro1,&$as_estclades)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 if (trim(strtoupper($_SESSION["ls_gestor"])) <> "INFORMIX")
	 {
	  $ls_sql="SELECT * FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' AND codestpro1<>'-------------------------' ORDER BY codestpro1  asc  limit 1 ";
	 }
	 else
	 {
	  $ls_sql="SELECT first 1 * FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' AND codestpro1<>'-------------------------' ORDER BY codestpro1  asc";
	 }
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_min_codestpro1  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
           $as_estclades=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro1
/**********************************************************************************************************************************/
	function uf_spi_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2,&$as_estclades)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 1 (referencia)          
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND estcla='".$as_estclades."' ".
			 " AND codestpro1<>'-------------------------' AND codestpro2<>'-------------------------'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_min_codestpro2  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		   //$as_estclades=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro2
/**********************************************************************************************************************************/
 function uf_spi_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3,&$as_estclades)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro3) as codestpro3 ".
             " FROM   spg_ep3                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        codestpro2='".$as_codestpro2."' AND   ".
			 "        estcla='".$as_estclades."'  		    ".
			 " AND codestpro1<>'-------------------------'  ".
			 " AND codestpro2<>'-------------------------'  ".
			 " AND codestpro3<>'-------------------------'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_codestpro3  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		   //$as_estclades=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro3
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_min_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_codestpro4,&$as_estclades)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  (referencia)         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro4) as codestpro4 ".
             " FROM   spg_ep4                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        codestpro2='".$as_codestpro2."' AND   ".
			 "        codestpro3='".$as_codestpro3."' AND   ".
			 "        estcla='".$as_estclades."'  ".
			 " AND codestpro1<>'-------------------------'  ".
			 " AND codestpro2<>'-------------------------'  ".
			 " AND codestpro3<>'-------------------------'  ".
			 " AND codestpro4<>'-------------------------'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_codestpro4  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro4=$row["codestpro4"];
		   //$as_estclades=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro4
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_min_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
	                                              &$as_codestpro5,&$as_estclades)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro4  // codigo de estructura programatica 5  (referencia) 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro5) as codestpro5 ".
             " FROM   spg_ep5                               ".
             " WHERE  codemp = '".$this->ls_codemp."' AND   ".
			 "        codestpro1='".$as_codestpro1."' AND   ".
			 "        codestpro2='".$as_codestpro2."' AND   ".
			 "        codestpro3='".$as_codestpro3."' AND   ".
			 "        codestpro4='".$as_codestpro4."' AND   ".
			 "        estcla='".$as_estclades."'  ".
			 " AND codestpro1<>'-------------------------'  ".
			 " AND codestpro2<>'-------------------------'  ".
			 " AND codestpro3<>'-------------------------'  ".
			 " AND codestpro4<>'-------------------------'  ".
			 " AND codestpro5<>'-------------------------'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->sigesp_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_codestpro5  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro5=$row["codestpro5"];
		   //$as_estclades=$row["estcla"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro5
    function uf_spg_reporte_select_denestpro1($as_codestpro1,&$as_denestpro1,$as_estcla)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp='".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND estcla='".$as_estcla."' ";	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro1 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro1=$row["denestpro1"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_denestpro2($as_codestpro1,$as_codestpro2,&$as_denestpro2,$as_estcla)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2 // codigo
	 //                     $as_denestpro2  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  ".
			 "        codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND ".
			 "        estcla='".$as_estcla."' ";	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro2 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro2=$row["denestpro2"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_denestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_denestpro3,$as_estcla)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  ".
			 "        codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND ".
			 "        estcla='".$as_estcla."' ";	
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro3 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro3=$row["denestpro3"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro3
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_denestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_denestpro4,$as_estcla)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro4 // codigo
	 //                     $as_denestpro4  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 4
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    31/10/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  ".
			 "        codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND ".
			 "        codestpro4='".$as_codestpro4."' AND ".
			 "        estcla='".$as_estcla."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro4 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro4=$row["denestpro4"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro4
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_denestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                          &$as_denestpro5,$as_estcla)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro5 // codigo
	 //                     $as_denestpro5  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 5
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    31/10/2006         Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  ".
			 "        codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND ".
			 "        codestpro4='".$as_codestpro4."' AND ".
			 "        codestpro5='".$as_codestpro5."' AND ".
			 "        estcla='".$as_estcla."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro5 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro5=$row["denestpro5"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro5
/********************************************************************************************************************************/	

}//fin de la clase
?>