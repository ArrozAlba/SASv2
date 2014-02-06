<?PHP 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../../shared/class_folder/class_sigesp_int_spg.php");	
require_once("../../shared/class_folder/class_sigesp_int_spi.php");	
require_once("sigesp_spg_funciones_reportes.php");	
/********************************************************************************************************************************/	
class sigesp_spg_reportes_class_bsf
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $dts_cab;
	var $io_dsreport;// datastore del trapaso
	var $dts_reporte_final;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	var $io_msg;
	var $io_fecha;
	var $sigesp_int_spg;
	var $sigesp_report_funciones;
	var $li_estmodest;
/********************************************************************************************************************************/	
    function  sigesp_spg_reportes_class_bsf()
    {
		$this->io_function=new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->dts_cab=new class_datastore();
		$this->dts_reporte_final=new class_datastore();
		$this->io_dsreport    = new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_spg_report_funciones=new sigesp_spg_funciones_reportes();
        $this->dts_empresa=$_SESSION["la_empresa"];
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->li_estmodest=$_SESSION["la_empresa"]["estmodest"];
    }
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " DISTRIBUCION MENSUAL DEL PRESUPUESTO"         //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_comparados_distribucion_mensual_presupuesto($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,
	                                                                     $as_codestpro4_ori,$as_codestpro5_ori,$as_codestpro1_des,
																	     $as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
																	     $as_codestpro5_des,$as_codfuefindes,$as_codfuefinhas,
																		 $as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_distribucion_mensual_presupuesto
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    07/09/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  
	   if($this->li_estmodest==2)
		{
			$ls_estructura_desde="";
			$ls_estructura_hasta="";

			if(($as_codestpro1_ori!="**")&&(!empty($as_codestpro1_ori)))
			{	
				$as_codestpro1_ori=$this->io_function->uf_cerosizquierda($as_codestpro1_ori,25);
				$ls_estructura_desde= $as_codestpro1_ori;	}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro1(&$as_codestpro1_ori);
				$ls_estructura_desde=$as_codestpro1_ori;
			}
			if(($as_codestpro2_ori!="**")&&(!empty($as_codestpro2_ori)))
			{
				$as_codestpro2_ori=$this->io_function->uf_cerosizquierda($as_codestpro2_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro2_ori;	}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro2($as_codestpro1_ori,&$as_codestpro2_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro2_ori;
			}
			if(($as_codestpro3_ori!="**")&&(!empty($as_codestpro3_ori)))
			{	
				$as_codestpro3_ori=$this->io_function->uf_cerosizquierda($as_codestpro3_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro3_ori;	}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro3($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro3_ori;
			}
			if(($as_codestpro4_ori!="**")&&(!empty($as_codestpro4_ori)))
			{	$ls_estructura_desde=$ls_estructura_desde.$as_codestpro4_ori;	}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro4($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro4_ori;
			}
			if(($as_codestpro5_ori!="**")&&(!empty($as_codestpro5_ori)))
			{	$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;	}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro5($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori);
			 	$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori.$as_estclades;
			}
			if(($as_codestpro1_des!="**")&&(!empty($as_codestpro1_des)))
			{	
				$as_codestpro1_des=	$this->io_function->uf_cerosizquierda($as_codestpro1_des,25);
				$ls_estructura_hasta=$as_codestpro1_des;	}
			else
			{	
				$this->uf_spg_reporte_select_max_codestpro1(&$as_codestpro1_des);
			 	$ls_estructura_hasta=$as_codestpro1_des;
			}
			if(($as_codestpro2_des!="**")&&(!empty($as_codestpro2_des)))
			{	
				$as_codestpro2_des=$this->io_function->uf_cerosizquierda($as_codestpro2_des,25);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro2_des;	}
			else
			{	
				$this->uf_spg_reporte_select_max_codestpro2($as_codestpro1_des,$as_codestpro2_des);
			 	$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro2_des;
			}
			if(($as_codestpro3_des!="**")&&(!empty($as_codestpro3_des)))
			{	
				$as_codestpro3_des=$this->io_function->uf_cerosizquierda($as_codestpro3_des,25);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro3_des;	}
			else
			{	
				$this->uf_spg_reporte_select_max_codestpro3($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des);
			 	$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro3_des;
			}
			if(($as_codestpro4_des!="**")&&(!empty($as_codestpro4_des)))
			{	$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro4_des;	}
			else
			{	
				$this->uf_spg_reporte_select_max_codestpro4($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des);
			 	$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro4_des;
			}
			if(($as_codestpro5_des!="**")&&(!empty($as_codestpro5_des)))
			{	$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_des.$as_estclahas;	}
			else
			{	
				$this->uf_spg_reporte_select_max_codestpro5($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des);
			 	$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_des;
			}			
		}
		else
		{
			$ls_estructura_desde=$this->io_function->uf_cerosizquierda($as_codestpro1_ori,25).$this->io_function->uf_cerosizquierda($as_codestpro2_ori,25).$this->io_function->uf_cerosizquierda($as_codestpro3_ori,25).$as_estclades/*.$as_codestpro4_ori.$as_codestpro5_ori*/;
			$ls_estructura_hasta=$this->io_function->uf_cerosizquierda($as_codestpro1_des,25).$this->io_function->uf_cerosizquierda($as_codestpro2_des,25).$this->io_function->uf_cerosizquierda($as_codestpro3_des,25).$as_estclahas/*.$as_codestpro4_des.$as_codestpro5_des*/;
		}	  
	  if (strtoupper($ls_gestor)=="MYSQL")
	  {
		   if($this->li_estmodest==1)
		   {
			   $ls_tabla="spg_ep3"; 
			   $ls_cadena="CONCAT(CT.codestpro1,CT.codestpro2,CT.codestpro3,CT.codestpro4,CT.codestpro5,CT.estcla)";
			   $ls_cadena_ct="CONCAT(CT.codestpro1,CT.codestpro2,CT.codestpro3,CT.estcla)";
			   $ls_cadena_e="CONCAT(E.codestpro1,E.codestpro2,E.codestpro3,E.estcla)";
		   }
		   elseif($this->li_estmodest==2)
		   {
			   $ls_tabla="spg_ep5";
			   $ls_cadena="CONCAT(CT.codestpro1,CT.codestpro2,CT.codestpro3,CT.codestpro4,CT.codestpro5,CT.estcla)";
			   $ls_cadena_ct="CONCAT(CT.codestpro1,CT.codestpro2,CT.codestpro3,CT.codestpro4,CT.codestpro5,CT.estcla)";
			   $ls_cadena_e="CONCAT(E.codestpro1,E.codestpro2,E.codestpro3,E.codestpro4,E.codestpro5,E.estcla)";
		   }
	  }
	  else
	  {
		   if($this->li_estmodest==1)
		   {
			   $ls_tabla="spg_ep3"; 
			   $ls_cadena="CT.codestpro1||CT.codestpro2||CT.codestpro3||CT.codestpro4||CT.codestpro5||CT.estcla";
			   $ls_cadena_ct="CT.codestpro1||CT.codestpro2||CT.codestpro3||CT.estcla";
			   $ls_cadena_e="E.codestpro1||E.codestpro2||E.codestpro3||E.estcla";
		   }
		   elseif($this->li_estmodest==2)
		   {
			   $ls_tabla="spg_ep5";
			   $ls_cadena="CT.codestpro1||CT.codestpro2||CT.codestpro3||CT.codestpro4||CT.codestpro5||CT.estcla";
			   $ls_cadena_ct="CT.codestpro1||CT.codestpro2||CT.codestpro3||CT.codestpro4||CT.codestpro5||CT.estcla";
			   $ls_cadena_e="E.codestpro1||E.codestpro2||E.codestpro3||E.codestpro4||E.codestpro5||E.estcla";
		   }
	  }
	 
	 $ls_sql=" SELECT CT.codestpro1 AS codestpro1, CT.codestpro2 AS codestpro2, CT.codestpro3 AS codestpro3, ".
	         "        CT.codestpro4 AS codestpro4, CT.codestpro5 AS codestpro5, CT.spg_cuenta AS spg_cuenta,".
			 "        CT.denominacion AS denominacion, CT.nivel AS nivel, ".
	         "        asignadoaux AS asignado, precomprometidoaux AS precomprometido, ".
	         "        comprometidoaux AS comprometido, causadoaux AS causado, pagadoaux AS pagado, ".
             "        aumentoaux AS aumento, disminucionaux AS disminucion, eneroaux AS enero, febreroaux AS febrero,".
			 "        marzoaux AS marzo, abrilaux AS abril, mayoaux AS mayo, junioaux AS junio, julioaux AS julio, ".
             "        agostoaux AS agosto, septiembreaux AS septiembre, octubreaux AS octubre, noviembreaux AS noviembre, ".
			 "        diciembreaux AS diciembre ".
             " FROM   spg_cuentas CT, ".$ls_tabla." E ".
             " WHERE  CT.codemp=E.codemp AND E.codemp='".$this->ls_codemp."' AND  ".
			 "        ".$ls_cadena_ct."=".$ls_cadena_e."  AND ". 
			 "        ".$ls_cadena_ct."  BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' AND ".
			 "        E.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' AND CT.codemp=E.codemp AND ".
			  "	     ".$ls_cadena_ct."= ".$ls_cadena_e."  AND CT.status='C' ".
             " ORDER BY CT.codestpro1,CT.codestpro2,CT.codestpro3,CT.codestpro4,CT.codestpro5,CT.estcla,CT.spg_cuenta ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_comparados_distribucion_mensual_presupuesto  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	}
    else
    {
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ls_denominacion=$row["denominacion"];
		   $li_nivel=$row["nivel"];
		   $ld_asignado=$row["asignado"];
		   $ld_comprometido=$row["comprometido"];
		   $ld_causado=$row["causado"];
		   $ld_pagado=$row["pagado"];
		   $ld_aumento=$row["aumento"];
		   $ld_disminucion=$row["disminucion"];
		   $ls_codestpro1=$row["codestpro1"];
		   $ls_codestpro2=$row["codestpro2"];
		   $ls_codestpro3=$row["codestpro3"];
		   $ls_codestpro4=$row["codestpro4"];
		   $ls_codestpro5=$row["codestpro5"];
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		   $ld_enero=$row["enero"];
		   $ld_febrero=$row["febrero"];
		   $ld_marzo=$row["marzo"];
		   $ld_abril=$row["abril"];
		   $ld_mayo=$row["mayo"];
		   $ld_junio=$row["junio"];
		   $ld_julio=$row["julio"];
		   $ld_agosto=$row["agosto"];
		   $ld_septiembre=$row["septiembre"];
		   $ld_octubre=$row["octubre"];
		   $ld_noviembre=$row["noviembre"];
		   $ld_diciembre=$row["diciembre"];
		   
		   $this->dts_reporte->insertRow("programatica",$ls_programatica);
		   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
		   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
		   $this->dts_reporte->insertRow("nivel",$li_nivel);
		   $this->dts_reporte->insertRow("enero",$ld_enero);
		   $this->dts_reporte->insertRow("febrero",$ld_febrero);
		   $this->dts_reporte->insertRow("marzo",$ld_marzo);
		   $this->dts_reporte->insertRow("abril",$ld_abril);
		   $this->dts_reporte->insertRow("mayo",$ld_mayo);
		   $this->dts_reporte->insertRow("junio",$ld_junio);
		   $this->dts_reporte->insertRow("julio",$ld_julio);
		   $this->dts_reporte->insertRow("agosto",$ld_agosto);
		   $this->dts_reporte->insertRow("septiembre",$ld_septiembre);
		   $this->dts_reporte->insertRow("octubre",$ld_octubre);
		   $this->dts_reporte->insertRow("noviembre",$ld_noviembre);
		   $this->dts_reporte->insertRow("diciembre",$ld_diciembre);
		   $lb_valido = true;
		  }//while 
	    $this->io_sql->free_result($rs_data);   
    }//else
     return  $lb_valido;
   }//fin uf_spg_reportes_comparados_distribucion_mensual_presupuesto
/********************************************************************************************************************************/	
	/////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  LISTADO  " UNIDADES EJECUTORAS "             //
	///////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_unidades_ejecutoras($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,
	                                             $as_codestpro4_ori,$as_codestpro5_ori,$as_coduniadm_des,$as_coduniadm_has,
												 $ai_estemireq,$as_ckq_unidad,$as_codfuefindes,$as_codfuefinhas,$as_estclades,
												 $as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_unidades_ejecutoras
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    07/09/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $this->dts_reporte->resetds("spg_cuenta");
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_aux="";	
	  if($this->li_estmodest==2)
		{
			$ls_estructura_desde="";
			$ls_estructura_hasta="";
			$ls_cadena="";	
			if(($as_codestpro1_ori!="**")&&(!empty($as_codestpro1_ori)))
			{	
				$as_codestpro1_ori= $this->io_function->uf_cerosizquierda($as_codestpro1_ori,25);
				$ls_estructura_desde=$as_codestpro1_ori;
				$ls_estructura_hasta=$as_codestpro1_ori;
				$as_codestpro1_des=$as_codestpro1_ori;
				$ls_aux= " AND codestpro1='".$as_codestpro1_ori."' ";				
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro1(&$as_codestpro1_ori);
				$ls_estructura_desde=$as_codestpro1_ori;
				$this->uf_spg_reporte_select_max_codestpro1(&$as_codestpro1_des);
				$ls_estructura_hasta=$as_codestpro1_des;
			}
			if(($as_codestpro2_ori!="**")&&(!empty($as_codestpro2_ori)))
			{	
				$as_codestpro2_ori=$this->io_function->uf_cerosizquierda($as_codestpro2_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro2_ori;
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro2_ori;
				$as_codestpro2_des=$as_codestpro2_ori;
				$ls_aux= $ls_aux." AND codestpro2='".$as_codestpro2_ori."' ";				
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro2($as_codestpro1_ori,&$as_codestpro2_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro2_ori;
				$this->uf_spg_reporte_select_max_codestpro2($as_codestpro1_des,$as_codestpro2_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro2_des;
			}

			if(($as_codestpro3_ori!="**")&&(!empty($as_codestpro3_ori)))
			{	
				$as_codestpro3_ori=$this->io_function->uf_cerosizquierda($as_codestpro3_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro3_ori;	
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro3_ori;
				$as_codestpro3_des=$as_codestpro3_ori;
				$ls_aux= $ls_aux." AND codestpro3='".$as_codestpro3_ori."' ";				
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro3($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro3_ori;
				$this->uf_spg_reporte_select_max_codestpro3($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro3_des;
			}			
			if(($as_codestpro4_ori!="**")&&(!empty($as_codestpro4_ori)))
			{	
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro4_ori;	
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro4_ori;
				$as_codestpro4_des=$as_codestpro4_ori;
				$ls_aux= $ls_aux." AND codestpro4='".$as_codestpro4_ori."' ";				
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro4($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro4_ori;
				$this->uf_spg_reporte_select_max_codestpro4($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro4_des;
			}			
			if(($as_codestpro5_ori!="**")&&(!empty($as_codestpro5_ori)))
			{	
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;	
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_ori;
				$as_codestpro5_des=$as_codestpro5_ori;
				$ls_aux= $ls_aux." AND codestpro5='".$as_codestpro5_ori."' ";								
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro5($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;
				$this->uf_spg_reporte_select_max_codestpro5($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_des.$as_estclades;
			}						
		}
		else
		{
			$ls_cadena="";
			if(!empty($as_codestpro1_ori))
			{	
				$as_codestpro1_ori= $this->io_function->uf_cerosizquierda($as_codestpro1_ori,25);
				$ls_estructura_desde=$as_codestpro1_ori;
				$ls_estructura_hasta=$as_codestpro1_ori;
				$as_codestpro1_des=$as_codestpro1_ori;					
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro1(&$as_codestpro1_ori);
				$ls_estructura_desde=$as_codestpro1_ori;
				$this->uf_spg_reporte_select_max_codestpro1($as_codestpro1_des);
				$ls_estructura_hasta=$as_codestpro1_des;
			}
			if(!empty($as_codestpro2_ori))
			{	
				$as_codestpro2_ori=$this->io_function->uf_cerosizquierda($as_codestpro2_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro2_ori;
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro2_ori;
				$as_codestpro2_des=$as_codestpro2_ori;				
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro2($as_codestpro1_ori,&$as_codestpro2_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro2_ori;
				$this->uf_spg_reporte_select_max_codestpro2($as_codestpro1_des,$as_codestpro2_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro2_des;
			}
			if(!empty($as_codestpro3_ori))
			{	
				$as_codestpro3_ori=$this->io_function->uf_cerosizquierda($as_codestpro3_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro3_ori;	
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro3_ori;
				$as_codestpro3_des=$as_codestpro3_ori;
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro3($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro3_ori;
				$this->uf_spg_reporte_select_max_codestpro3($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro3_des;
			}		
			if(!empty($as_codestpro4_ori))
			{	
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro4_ori;	
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro4_ori;
				$as_codestpro4_des=$as_codestpro4_ori;
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro4($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro4_ori;
				$this->uf_spg_reporte_select_max_codestpro4($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro4_des;
			}			
			if(!empty($as_codestpro5_ori))
			{	
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;	
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_ori;
				$as_codestpro5_des=$as_codestpro5_ori;
			}
			else
			{	
				$this->uf_spg_reporte_select_min_codestpro5($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;
				$this->uf_spg_reporte_select_max_codestpro5($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_des.$as_estclades;
			}		
		}		 
		if(($as_coduniadm_des=="")&&($as_coduniadm_has==""))
		{
			$lb_valido=$this->io_spg_report_funciones->uf_spg_min_coduniadm_sinprogramatica($as_coduniadm_des);
			$as_coduniadm_des=$as_coduniadm_des;
			if($lb_valido)
			{
			   $lb_valido=$this->io_spg_report_funciones->uf_spg_max_coduniadm_sinprogramatica($as_coduniadm_has);			
			} 
		}
		if(strtoupper($ls_gestor)=="MYSQL")
			{
			   $ls_concat=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla)";
			}
			else
			{
			   $ls_concat=" AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla)";
			}
	  if($ai_estemireq==1)
	  {
	    $ls_filtro=" AND estemireq=1 ";
	  }
	  else
	  {
	    $ls_filtro="";
	  }
	  if($as_ckq_unidad==0)
	  {
	     $ls_sql=" SELECT * ".
                 " FROM   spg_unidadadministrativa ".  
                 " WHERE  codemp='".$this->ls_codemp."'  ".$ls_concat."  BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."'  AND  coduniadm  BETWEEN '".$as_coduniadm_des."' AND  ".
				 "        '".$as_coduniadm_has."'  ".$ls_filtro."  ".$ls_aux.
                 " ORDER BY coduniadm ";
	  }
	  else
	  {
	    $ls_sql=" SELECT * ".
                " FROM   spg_unidadadministrativa ".
                " WHERE  codemp='".$this->ls_codemp."' AND  coduniadm BETWEEN '".$as_coduniadm_des."' AND  ".
				"        '".$as_coduniadm_des."'  ".$ls_filtro." ".$ls_aux.
                " ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,coduniadm ";
	  
	  }//print "Sentencia =>".$ls_sql;

      $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_unidades_ejecutoras  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
			$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   if($this->li_estmodest==2)
			   {
			   		$ls_programatica=substr($row["codestpro1"],-$ls_loncodestpro1)."-".substr($row["codestpro2"],-$ls_loncodestpro2)."-".substr($row["codestpro3"],-$ls_loncodestpro3)."-".substr($row["codestpro4"],-$ls_loncodestpro4)."-".substr($row["codestpro5"],-$ls_loncodestpro5);
			   }
			   else
			   {
			   		$ls_programatica=substr($row["codestpro1"],-$ls_loncodestpro1)."-".substr($row["codestpro2"],-$ls_loncodestpro2)."-".substr($row["codestpro3"],-$ls_loncodestpro3);
			   }
			   $ls_coduniadm=$row["coduniadm"];
			   $ls_denuniadm=$row["denuniadm"];
			   $this->dts_reporte->insertRow("coduniadm",$ls_coduniadm);
			   $this->dts_reporte->insertRow("denuniadm",$ls_denuniadm);
			   $this->dts_reporte->insertRow("programatica",$ls_programatica);
			   $lb_valido = true;
			  }//while 
	          $this->io_sql->free_result($rs_data);   
       }//else
       return  $lb_valido;
   }//fin uf_spg_reportes_comparados_distribucion_mensual_presupuesto
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " EJECUCION DE COMPROMISOS "                    //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_ejecucion_compromiso($as_procede,$as_comprobante,$adt_fecha,$adt_fecdes,$adt_fechas,$as_spg_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_ejecucion_compromiso
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido = true;
	 $this->dts_reporte->resetds("spg_cuenta");
	 $this->dts_reporte_final->resetds("spg_cuenta");
	 $ls_sql=" SELECT PMV.*, PMV.montoaux as monto, POP.asignar, POP.aumento, POP.disminucion, ".
	         "        POP.comprometer, POP.causar, POP.pagar ".
             " FROM   spg_dt_cmp PMV, spg_operaciones POP ".
             " WHERE  PMV.operacion=POP.operacion AND PMV.procede='".$as_procede."' AND ".
             "        PMV.comprobante='".$as_comprobante."' AND  PMV.fecha='".$adt_fecha."' AND ".
			 "        PMV.spg_cuenta='".$as_spg_cuenta."' ".
			 " ORDER BY PMV.spg_cuenta "; 
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_ejecucion_compromiso  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ls_procede=$row["procede"];
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"]; 
			   $ls_codestpro2=$row["codestpro2"]; 
			   $ls_codestpro3=$row["codestpro3"]; 
			   $ls_codestpro4=$row["codestpro4"]; 
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"]; 
			   $ls_operacion=$row["operacion"]; 
			   $ls_descripcion=$row["descripcion"]; 
			   $ld_monto=$row["monto"]; 
			   $li_orden=$row["orden"]; 
			   $li_asignar=$row["asignar"]; 
			   $li_aumento=$row["aumento"]; 
			   $li_disminucion=$row["disminucion"]; 
			   $li_comprometer=$row["comprometer"]; 
			   $li_causar=$row["causar"]; 
			   $li_pagar=$row["pagar"]; 
			   
			   if($li_comprometer==1)
			   {
			     $ld_comprometer=$ld_monto;
			   }
			   else
			   {
				 $ld_comprometer=0;
			   }
			   if($li_causar==1)
			   {
			     $ld_causado=$ld_monto;
			   }
			   else
			   {
				 $ld_causado=0;
			   }
			   if($li_pagar==1)
			   {
			     $ld_pagado=$ld_monto;
			   }
			   else
			   {
				 $ld_pagado=0;
			   }
			   $ls_cod_pro="";
			   $ls_ced_bene="";
			   $ls_nompro="";
			   $ls_nombene="";
			   $ls_tipo_destino="";
			   $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,
			                                                        $ls_ced_bene,$ls_nompro,$ls_nombene,$ls_tipo_destino);
			   if($lb_valido)
			   {
				   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				   $this->dts_reporte->insertRow("programatica",$ls_programatica);
				   $this->dts_reporte->insertRow("codestpro1",$ls_codestpro1);
				   $this->dts_reporte->insertRow("codestpro2",$ls_codestpro2);
				   $this->dts_reporte->insertRow("codestpro3",$ls_codestpro3);
				   $this->dts_reporte->insertRow("codestpro4",$ls_codestpro4);
				   $this->dts_reporte->insertRow("codestpro5",$ls_codestpro5);
				   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);	
				   $this->dts_reporte->insertRow("procede",$ls_procede);
				   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
				   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
				   $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
				   $this->dts_reporte->insertRow("causado",$ld_causado);					 
				   $this->dts_reporte->insertRow("pagado",$ld_pagado);
				   $this->dts_reporte->insertRow("nompro",$ls_nompro);
				   $this->dts_reporte->insertRow("nombene",$ls_nombene);
				   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
				   $this->dts_reporte->insertRow("operacion",$ls_operacion);
				   $lb_valido = true;
			  }//if	   
		  }//while 
		  $this->io_sql->free_result($rs_data);  
		  $lb_valido=$this->uf_spg_reportes_buscar_comprobante_generados($adt_fechas); 
		  /*if($lb_valido)
		  {
		  	$lb_valido=$this->uf_spg_reportes_buscar_comprobante_generados($adt_fechas); 
		  }*/
		  $li_total=$this->dts_reporte_final->getRowCount("comprobante");
		  for($li_i=1;$li_i<=$li_total;$li_i++)
		  {
			  $ls_comprobante=$this->dts_reporte_final->getValue("comprobante",$li_i);
			  $ls_procede=$this->dts_reporte_final->getValue("procede",$li_i);
			  $ldt_fecha=$this->dts_reporte_final->getValue("fecha",$li_i);
			  $ls_codestpro1=$this->dts_reporte_final->getValue("codestpro1",$li_i);
			  $ls_codestpro2=$this->dts_reporte_final->getValue("codestpro2",$li_i);
			  $ls_codestpro3=$this->dts_reporte_final->getValue("codestpro3",$li_i);
			  $ls_codestpro4=$this->dts_reporte_final->getValue("codestpro4",$li_i);
			  $ls_codestpro5=$this->dts_reporte_final->getValue("codestpro5",$li_i);
			  $ls_spg_cuenta=$this->dts_reporte_final->getValue("spg_cuenta",$li_i);
			  $ls_cod_pro="";
			  $ls_ced_bene="";
			  $ls_nompro="";
			  $ls_nombene="";
			  $ls_tipo_destino="";
			  $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,
			                                                       $ls_ced_bene,$ls_nompro,$ls_nombene,$ls_tipo_destino);
			  if($lb_valido)
			  {
			    $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
				$ls_sql=" SELECT PMV.*, PMV.montoaux AS monto, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
						" FROM   sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP ".
						" WHERE  PCM.codemp=PMV.codemp AND  PMV.codemp='".$this->ls_codemp."' AND PCM.procede=PMV.procede AND  ".
						" 	     PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha AND PMV.operacion=POP.operacion  AND  ".
						" 	     PMV.procede_doc='".$ls_procede."'    AND  PMV.documento='".$ls_comprobante."'  AND  ".
						"	     PMV.codestpro1='".$ls_codestpro1."'  AND  PMV.codestpro2='".$ls_codestpro2."'  AND  ".
						"	     PMV.codestpro3='".$ls_codestpro3."'  AND  PMV.codestpro4='".$ls_codestpro4."'  AND  ".
						"	     PMV.codestpro5='".$ls_codestpro5."'  AND  PMV.spg_cuenta='".$ls_spg_cuenta."'  AND  ".
						"	     (PMV.procede<>'".$ls_procede."' OR  PMV.Comprobante<>'".$ls_comprobante."')  AND ".
						"	     PCM.cod_pro='".$ls_cod_pro."'  AND  PCM.ced_bene='".$ls_ced_bene."' AND  ".
						"        PMV.Fecha<='".$adt_fechas."' ";
				$rs_data=$this->io_sql->select($ls_sql);
			    if($rs_data===false)
			    {   // error interno sql
				   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
										   MÉTODO->uf_spg_reportes_ejecucion_compromiso(segundo_select)  
										   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
					$lb_valido = false;
			    }
			    else
			    {
					while($row=$this->io_sql->fetch_row($rs_data))
					{
					   $ls_procede=$row["procede"];
					   $ls_comprobante=$row["comprobante"];
					   $ldt_fecha=$row["fecha"];
					   $ls_codestpro1=$row["codestpro1"]; 
					   $ls_codestpro2=$row["codestpro2"]; 
					   $ls_codestpro3=$row["codestpro3"]; 
					   $ls_codestpro4=$row["codestpro4"]; 
					   $ls_codestpro5=$row["codestpro5"];
					   $ls_spg_cuenta=$row["spg_cuenta"];

					   $ls_procede_doc=$row["procede_doc"];
					   $ls_documento=$row["documento"]; 
					   $ls_operacion=$row["operacion"]; 
					   $ls_descripcion=$row["descripcion"]; 
					   $ld_monto=$row["monto"]; 
					   $li_orden=$row["orden"]; 
					   $li_asignar=$row["asignar"]; 
					   $li_aumento=$row["aumento"]; 
					   $li_disminucion=$row["disminucion"]; 
					   $li_comprometer=$row["comprometer"]; 
					   $li_causar=$row["causar"]; 
					   $li_pagar=$row["pagar"]; 
			   
					   if($li_comprometer==1)
					   {
						 $ld_comprometer=$ld_monto;
					   }
					   else
					   {
						 $ld_comprometer=0;
					   }
					   if($li_causar==1)
					   {
						 $ld_causado=$ld_monto;
					   }
					   else
					   {
						 $ld_causado=0;
					   }
					   if($li_pagar==1)
					   {
						 $ld_pagado=$ld_monto;
					   }
					   else
					   {
						 $ld_pagado=0;
					   }
					   $ar_values["comprobante"]=$ls_comprobante; 
					   $ar_values["procede"]=$ls_procede;
					   $ar_values["fecha"]=$ldt_fecha; 
					   $ar_values["codestpro1"]=$ls_codestpro1; 
					   $ar_values["codestpro2"]=$ls_codestpro2; 
					   $ar_values["codestpro3"]=$ls_codestpro3; 
					   $ar_values["codestpro4"]=$ls_codestpro4; 
					   $ar_values["codestpro5"]=$ls_codestpro5; 
					   $ar_values["spg_cuenta"]=$ls_spg_cuenta; 
					   $ar_values["operacion"]=$ls_operacion; 
					   
					   $li_pos=$this->dts_reporte->findValues($ar_values,"comprobante");
					   if($li_pos<0)
					   {
						   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
						   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
						   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
						   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
						   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
						   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
						   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
						   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);	
						   $this->dts_reporte_final->insertRow("procede",$ls_procede);
						   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
						   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
						   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometer);
						   $this->dts_reporte_final->insertRow("causado",$ld_causado);					 
						   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);	
						   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
						   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
						   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);
						   $this->dts_reporte_final->insertRow("operacion",$ls_operacion);
						   $lb_valido = true;
					   }//if
					}//while
			    }//else
			  }//if
		  }//for
		 $li_total=$this->dts_reporte_final->getRowCount("comprobante");
		 for($li_i=1;$li_i<=$li_total;$li_i++)
		  {
			  $ls_comprobante=$this->dts_reporte_final->getValue("comprobante",$li_i);
			  $ls_procede=$this->dts_reporte_final->getValue("procede",$li_i);
			  $ldt_fecha=$this->dts_reporte_final->getValue("fecha",$li_i);
			  $ls_codestpro1=$this->dts_reporte_final->getValue("codestpro1",$li_i);
			  $ls_codestpro2=$this->dts_reporte_final->getValue("codestpro2",$li_i);
			  $ls_codestpro3=$this->dts_reporte_final->getValue("codestpro3",$li_i);
			  $ls_codestpro4=$this->dts_reporte_final->getValue("codestpro4",$li_i);
			  $ls_codestpro5=$this->dts_reporte_final->getValue("codestpro5",$li_i);
			  $ls_spg_cuenta=$this->dts_reporte_final->getValue("spg_cuenta",$li_i);
			  $ld_causado=$this->dts_reporte_final->getValue("causado",$li_i);
			  $ld_comprometer=$this->dts_reporte_final->getValue("compromiso",$li_i);
			  $ld_pagado=$this->dts_reporte_final->getValue("pagado",$li_i);
			  $ls_nompro=$this->dts_reporte_final->getValue("nompro",$li_i);
			  $ls_tipo_destino=$this->dts_reporte_final->getValue("tipo_destino",$li_i);
			  $ls_nombene=$this->dts_reporte_final->getValue("nombene",$li_i);
			  $ls_operacion=$this->dts_reporte_final->getValue("operacion",$li_i);
			  
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $this->dts_reporte->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);	
			   $this->dts_reporte->insertRow("procede",$ls_procede);
			   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
			   $this->dts_reporte->insertRow("causado",$ld_causado);					 
			   $this->dts_reporte->insertRow("pagado",$ld_pagado);
			   $this->dts_reporte->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte->insertRow("nombene",$ls_nombene);
			   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
			   $this->dts_reporte->insertRow("operacion",$ls_operacion);
			   $lb_valido = true;
		}//for	 
	   }//else
       return  $lb_valido;
   }//uf_spg_reportes_ejecucion_compromiso
/********************************************************************************************************************************/	
    function uf_spg_reportes_buscar_comprobante_generados($adt_fechas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_buscar_comprobante_generados
	 //         Access :	private
	 //     Argumentos :    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    04/10/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=false;
	  $li_total=$this->dts_reporte->getRowCount("spg_cuenta");
	  for($li_i=1;$li_i<=$li_total;$li_i++)
	  {
		  $ls_comprobante=$this->dts_reporte->getValue("comprobante",$li_i);
		  $ls_procede=$this->dts_reporte->getValue("procede",$li_i);
		  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
		  $ls_codestpro1=$this->dts_reporte->getValue("codestpro1",$li_i);
		  $ls_codestpro2=$this->dts_reporte->getValue("codestpro2",$li_i);
		  $ls_codestpro3=$this->dts_reporte->getValue("codestpro3",$li_i);
		  $ls_codestpro4=$this->dts_reporte->getValue("codestpro4",$li_i);
		  $ls_codestpro5=$this->dts_reporte->getValue("codestpro5",$li_i);
		  $ls_spg_cuenta=$this->dts_reporte->getValue("spg_cuenta",$li_i);
		  $ls_cod_pro="";
		  $ls_ced_bene="";
		  $ls_nompro="";
		  $ls_nombene="";
		  $ls_tipo_destino="";
		  $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,
															   $ls_ced_bene,$ls_nompro,$ls_nombene,$ls_tipo_destino);
		  if($lb_valido)
		  {
			$adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
			$ls_sql=" SELECT PMV.*, PMV.montoaux as monto, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
					" FROM   sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP ".
					" WHERE  PCM.codemp=PMV.codemp AND  PMV.codemp='".$this->ls_codemp."' AND PCM.procede=PMV.procede AND  ".
					" 	     PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha AND PMV.operacion=POP.operacion  AND  ".
					" 	     PMV.procede_doc='".$ls_procede."'    AND  PMV.documento='".$ls_comprobante."'  AND  ".
					"	     PMV.codestpro1='".$ls_codestpro1."'  AND  PMV.codestpro2='".$ls_codestpro2."'  AND  ".
					"	     PMV.codestpro3='".$ls_codestpro3."'  AND  PMV.codestpro4='".$ls_codestpro4."'  AND  ".
					"	     PMV.codestpro5='".$ls_codestpro5."'  AND  PMV.spg_cuenta='".$ls_spg_cuenta."'  AND  ".
					"	     (PMV.procede<>'".$ls_procede."' OR  PMV.comprobante<>'".$ls_comprobante."')  AND ".
					"	     PCM.cod_pro='".$ls_cod_pro."'  AND  PCM.ced_bene='".$ls_ced_bene."' AND  ".
					"        PMV.fecha<='".$adt_fechas."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   // error interno sql
			   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
									   MÉTODO->uf_spg_reportes_ejecucion_compromiso(segundo_select)  
									   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido = false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
				   $ls_procede=$row["procede"];
				   $ls_comprobante=$row["comprobante"];
				   $ldt_fecha=$row["fecha"];
				   $ls_codestpro1=$row["codestpro1"]; 
				   $ls_codestpro2=$row["codestpro2"]; 
				   $ls_codestpro3=$row["codestpro3"]; 
				   $ls_codestpro4=$row["codestpro4"]; 
				   $ls_codestpro5=$row["codestpro5"];
				   $ls_spg_cuenta=$row["spg_cuenta"];

				   $ls_procede_doc=$row["procede_doc"];
				   $ls_documento=$row["documento"]; 
				   $ls_operacion=$row["operacion"]; 
				   $ls_descripcion=$row["descripcion"]; 
				   $ld_monto=$row["monto"]; 
				   $li_orden=$row["orden"]; 
				   $li_asignar=$row["asignar"]; 
				   $li_aumento=$row["aumento"]; 
				   $li_disminucion=$row["disminucion"]; 
				   $li_comprometer=$row["comprometer"]; 
				   $li_causar=$row["causar"]; 
				   $li_pagar=$row["pagar"]; 
		   
				   if($li_comprometer==1)
				   {
					 $ld_comprometer=$ld_monto;
				   }
				   else
				   {
					 $ld_comprometer=0;
				   }
				   if($li_causar==1)
				   {
					 $ld_causado=$ld_monto;
				   }
				   else
				   {
					 $ld_causado=0;
				   }
				   if($li_pagar==1)
				   {
					 $ld_pagado=$ld_monto;
				   }
				   else
				   {
					 $ld_pagado=0;
				   }
				   $ar_values["comprobante"]=$ls_comprobante; 
				   $ar_values["procede"]=$ls_procede;
				   $ar_values["fecha"]=$ldt_fecha; 
				   $ar_values["codestpro1"]=$ls_codestpro1; 
				   $ar_values["codestpro2"]=$ls_codestpro2; 
				   $ar_values["codestpro3"]=$ls_codestpro3; 
				   $ar_values["codestpro4"]=$ls_codestpro4; 
				   $ar_values["codestpro5"]=$ls_codestpro5; 
				   $ar_values["spg_cuenta"]=$ls_spg_cuenta; 
				   $ar_values["operacion"]=$ls_operacion; 
				   $li_pos=$this->dts_reporte->findValues($ar_values,"comprobante");
				   if($li_pos<0)
				   {
					   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
					   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
					   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
					   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
					   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
					   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
					   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);	
					   $this->dts_reporte_final->insertRow("procede",$ls_procede);
					   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
					   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
					   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometer);
					   $this->dts_reporte_final->insertRow("causado",$ld_causado);					 
					   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);					 
					   $lb_valido = true;
				   }//if
				}//while
			}//else
		  }//if
	  }//for
	 return  $lb_valido;
	}//uf_spg_reportes_buscar_comprobante_generados
/********************************************************************************************************************************/	
    function uf_spg_reportes_select_comprobante($as_comprobante,$as_procede,$adt_fecha,&$as_cod_pro,&$as_ced_bene,&$as_nompro,
	                                            &$as_nombene,&$as_tipo_destino)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_select_comprobante
	 //         Access :	private
	 //     Argumentos :    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/09/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
	    $ls_cadena="CONCAT( rtrim(XBF.apebene),', ',XBF.nombene)";
	  }
	  else
	  {
	    $ls_cadena="rtrim(XBF.apebene)||', '||XBF.nombene";
	  }
	  $ls_sql=" SELECT CM.*, PR.nompro as nompro,".$ls_cadena."  as nombene ".
              " FROM   sigesp_cmp CM, rpc_proveedor PR, rpc_beneficiario XBF ".
              " WHERE  CM.codemp=PR.codemp   AND PR.codemp=XBF.codemp  AND XBF.codemp='".$this->ls_codemp."' AND ".
			  "        CM.cod_pro=PR.cod_pro AND CM.ced_bene=XBF.ced_bene AND ".
	          "        CM.procede='".$as_procede."'  AND CM.comprobante='".$as_comprobante."' AND  CM.fecha='".$adt_fecha."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_class 
		                          MÉTODO->uf_spg_reportes_select_comprobante  
		                          ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_cod_pro=$row["cod_pro"];
		   $as_ced_bene=$row["ced_bene"];
		   $as_nompro=$row["nompro"];
		   $as_nombene=$row["nombene"];
		   $as_tipo_destino=$row["tipo_destino"];
	
	       $lb_valido = true;
	    }
		$this->io_sql->free_result($rs_data);
      }//else
	 return $lb_valido;
   }//fin uf_scg_reporte_select_saldo_empresa
/********************************************************************************************************************************/	
    function uf_spg_select_reportes_ejecucion_compromiso($as_procede,$as_comprobante,$adt_fecha,$adt_fecdes,$adt_fechas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_select_reportes_ejecucion_compromiso
	 //     Argumentos :    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Compromisos
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido = true;
	 $adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	 $ls_sql=" SELECT PMV.*, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
             " FROM   spg_dt_cmp PMV, spg_operaciones POP ".
             " WHERE  PMV.operacion=POP.operacion AND PMV.procede='".$as_procede."' AND ".
             "        PMV.comprobante='".$as_comprobante."' AND  PMV.fecha='".$adt_fecha."' "; 
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_select_reportes_ejecucion_compromiso  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ls_procede=$row["procede"];
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"]; 
			   $ls_codestpro2=$row["codestpro2"]; 
			   $ls_codestpro3=$row["codestpro3"]; 
			   $ls_codestpro4=$row["codestpro4"]; 
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"]; 
			   $ls_operacion=$row["operacion"]; 
			   $ls_descripcion=$row["descripcion"]; 
			   $ld_monto=$row["monto"]; 
			   $li_orden=$row["orden"]; 
			   $li_asignar=$row["asignar"]; 
			   $li_aumento=$row["aumento"]; 
			   $li_disminucion=$row["disminucion"]; 
			   $li_comprometer=$row["comprometer"]; 
			   $li_causar=$row["causar"]; 
			   $li_pagar=$row["pagar"]; 
			   
			   if($li_comprometer==1)
			   {
			     $ld_comprometer=$ld_monto;
			   }
			   else
			   {
				 $ld_comprometer=0;
			   }
			   if($li_causar==1)
			   {
			     $ld_causado=$ld_monto;
			   }
			   else
			   {
				 $ld_causado=0;
			   }
			   if($li_pagar==1)
			   {
			     $ld_pagado=$ld_monto;
			   }
			   else
			   {
				 $ld_pagado=0;
			   }
			   $ls_cod_pro="";
			   $ls_ced_bene="";
			   $ls_nompro="";
			   $ls_nombene="";
			   $ls_tipo_destino="";
			   $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,$ls_ced_bene,                                                                    $ls_nompro,$ls_nombene,$ls_tipo_destino);
			   if($lb_valido)
			   {
				   //datastore de la cabezera
				   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				   $this->dts_cab->insertRow("programatica",$ls_programatica);
				   $this->dts_cab->insertRow("spg_cuenta",$ls_spg_cuenta);	
				   $this->dts_cab->insertRow("procede",$ls_procede);
				   $this->dts_cab->insertRow("comprobante",$ls_comprobante);
				   $this->dts_cab->insertRow("fecha",$ldt_fecha);
				   $this->dts_cab->insertRow("nompro",$ls_nompro);
				   $this->dts_cab->insertRow("nombene",$ls_nombene);
				   $this->dts_cab->insertRow("tipo_destino",$ls_tipo_destino);
				   $lb_valido = true;
			  }//if	   
		  }//while 
		  $this->io_sql->free_result($rs_data); 
	    }//else
	    return $lb_valido;
 }//uf_spg_select_reportes_ejecucion_compromiso
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " COMPROMISOS NO CAUSADOS  "                    //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_compromiso_no_causados($adt_fecdes,$adt_fechas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_compromiso_no_causados
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de los compromisos no causados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    22/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_valido = false;
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_sql=" SELECT  distinct PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total,      ".
	          "         PCM.tipo_destino,PCM.cod_pro, PCM.ced_bene, PMV.operacion                          ".
              "   FROM  sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP                                ".
              "  WHERE  POP.comprometer=1 AND POP.causar=0 AND POP.pagar=0                                 ".
			  "   AND   PMV.monto>=0 AND PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."'         ".
			  "	  AND	PCM.procede=PMV.procede AND PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha".
			  "   AND   PMV.operacion=POP.operacion                                                        ".
              " ORDER BY  PCM.fecha, PCM.procede, PCM.comprobante ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class.MÉTODO->uf_spg_reportes_compromiso_no_causados.ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	     }
      else
         {
		   while ($row=$this->io_sql->fetch_row($rs_data))
			     {
			       $ls_procede = $row["procede"];
			       $ls_numcmp  = $row["comprobante"];
			       $ldt_fecha  = $row["fecha"];
			   	   $lb_valido  = $this->uf_spg_reportes_comprobante_compromiso_no_causados($ls_procede,$ls_numcmp,$ldt_fecha,$adt_fecdes,$adt_fechas);
			       if ($lb_valido)
			          {
				        $li_total=$this->dts_reporte->getRowCount("spg_cuenta");
				        $ld_total_comprometer=0;  
				        $ld_total_causado=0;  
				        $ld_total_pagado=0;  
				        for ($li_i=1;$li_i<=$li_total;$li_i++)
				            {
							  $ls_comprobante=$this->dts_reporte->getValue("comprobante",$li_i);
							  $ls_procede=$this->dts_reporte->getValue("procede",$li_i);
							  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
							  $ls_codestpro1=$this->dts_reporte->getValue("codestpro1",$li_i);
							  $ls_codestpro2=$this->dts_reporte->getValue("codestpro2",$li_i);
							  $ls_codestpro3=$this->dts_reporte->getValue("codestpro3",$li_i);
							  $ls_codestpro4=$this->dts_reporte->getValue("codestpro4",$li_i);
							  $ls_codestpro5=$this->dts_reporte->getValue("codestpro5",$li_i);
							  $ls_spg_cuenta=$this->dts_reporte->getValue("spg_cuenta",$li_i);
							  $ld_comprometer=$this->dts_reporte->getValue("compromiso",$li_i);
							  $ld_causado=$this->dts_reporte->getValue("causado",$li_i);
							  $ld_pagado=$this->dts_reporte->getValue("pagado",$li_i);
							  $ls_nompro=$this->dts_reporte->getValue("nompro",$li_i);
							  $ls_nombene=$this->dts_reporte->getValue("nombene",$li_i);
							  $ls_cod_pro=$this->dts_reporte->getValue("cod_pro",$li_i);
							  $ls_ced_bene=$this->dts_reporte->getValue("ced_bene",$li_i);
							  $ls_tipo_destino=$this->dts_reporte->getValue("tipo_destino",$li_i);
							  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
					  
					  $ar_values["codestpro1"]=$ls_codestpro1; 
					  $ar_values["codestpro2"]=$ls_codestpro2; 
					  $ar_values["codestpro3"]=$ls_codestpro3; 
					  $ar_values["codestpro4"]=$ls_codestpro4; 
					  $ar_values["codestpro5"]=$ls_codestpro5; 
					  $ar_values["spg_cuenta"]=$ls_spg_cuenta; 
					  $li_pos=$this->dts_reporte->findValues($ar_values,"spg_cuenta");
					  if($li_pos>0)
					  {
					     $ld_total_comprometer=$ld_total_comprometer+$ld_comprometer;  
					     $ld_total_causado=$ld_total_causado+$ld_causado;  
					     $ld_total_pagado=$ld_total_pagado+$ld_pagado;  
					  }//if
				  }//for
				  if(($ld_total_comprometer<>0)&&($ld_total_causado==0)&&($ld_total_pagado==0))
				  {
					   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
					   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
					   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
					   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
					   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
					   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
					   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);	
					   $this->dts_reporte_final->insertRow("procede",$ls_procede);
					   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
					   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
					   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometer);
					   $this->dts_reporte_final->insertRow("causado",$ld_causado);					 
					   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
					   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
					   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
					   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
					   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
					   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);
					   $lb_valido = true;
				  }//if
				  else
				  {
				    $lb_valido = false;
				  }
			   }//if
			}//while   
		    $this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_compromiso_no_causados
/********************************************************************************************************************************/	
    function uf_spg_reportes_comprobante_compromiso_no_causados($as_procede,$as_comprobante,$adt_fecha,$adt_fecdes,$adt_fechas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_compromiso_no_causados
	 //     Argumentos :    $as_procede // procede
	 //                     $as_comprobante  //  nro comprobante
	 //                     $adt_fecha    //  fecha del comprobante 
	 //                     $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de los compromisos no causados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    21/09/2006          Fecha última Modificacion :      Hora :
  	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido = true;
	 $this->dts_reporte->resetds("spg_cuenta");
	 $ls_sql=" SELECT PMV.*, PMV.montoaux AS monto, POP.asignar, ".
	         "        POP.aumento, POP.disminucion, ".
	         "        POP.comprometer, POP.causar, POP.pagar ".
             "   FROM spg_dt_cmp PMV, spg_operaciones POP ".
             "  WHERE PMV.procede='".$as_procede."' ".
			 "    AND PMV.comprobante='".$as_comprobante."' ".
			 "    AND PMV.fecha='".$adt_fecha."'  ".
			 "    AND PMV.operacion=POP.operacion"; 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_comprobante_compromiso_no_causados  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	 }
     else
     {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ls_procede=$row["procede"];
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"]; 
			   $ls_codestpro2=$row["codestpro2"]; 
			   $ls_codestpro3=$row["codestpro3"]; 
			   $ls_codestpro4=$row["codestpro4"]; 
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"]; 
			   $ls_operacion=$row["operacion"]; 
			   $ls_descripcion=$row["descripcion"]; 
			   $ld_monto=$row["monto"]; 
			   $li_orden=$row["orden"]; 
			   $li_asignar=$row["asignar"]; 
			   $li_aumento=$row["aumento"]; 
			   $li_disminucion=$row["disminucion"]; 
			   $li_comprometer=$row["comprometer"]; 
			   $li_causar=$row["causar"]; 
			   $li_pagar=$row["pagar"]; 
			   if($li_comprometer==1)
			   {
			     $ld_comprometer=$ld_monto;
			   }
			   else
			   {
				 $ld_comprometer=0;
			   }
			   if($li_causar==1)
			   {
			     $ld_causado=$ld_monto;
			   }
			   else
			   {
				 $ld_causado=0;
			   }
			   if($li_pagar==1)
			   {
			     $ld_pagado=$ld_monto;
			   }
			   else
			   {
				 $ld_pagado=0;
			   }
			   $ls_cod_pro="";
			   $ls_ced_bene="";
			   $ls_nompro="";
			   $ls_nombene="";
			   $ls_tipo_destino="";
			   $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,$ls_ced_bene,$ls_nompro,$ls_nombene,$ls_tipo_destino);
			   if($lb_valido)
			   {
				   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				   $this->dts_reporte->insertRow("programatica",$ls_programatica);
				   $this->dts_reporte->insertRow("codestpro1",$ls_codestpro1);
				   $this->dts_reporte->insertRow("codestpro2",$ls_codestpro2);
				   $this->dts_reporte->insertRow("codestpro3",$ls_codestpro3);
				   $this->dts_reporte->insertRow("codestpro4",$ls_codestpro4);
				   $this->dts_reporte->insertRow("codestpro5",$ls_codestpro5);
				   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);	
				   $this->dts_reporte->insertRow("procede",$ls_procede);
				   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
				   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
				   $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
				   $this->dts_reporte->insertRow("causado",$ld_causado);					 
				   $this->dts_reporte->insertRow("pagado",$ld_pagado);
				   $this->dts_reporte->insertRow("nompro",$ls_nompro);
				   $this->dts_reporte->insertRow("nombene",$ls_nombene);
				   $this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
				   $this->dts_reporte->insertRow("ced_bene",$ls_ced_bene);
				   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
				   $this->dts_reporte->insertRow("operacion",$ls_operacion);
				   $lb_valido = true;
			  }//if	   
		  }//while 
		  $this->io_sql->free_result($rs_data);   
		  $li_total=$this->dts_reporte->getRowCount("spg_cuenta");
		  for($li_i=1;$li_i<=$li_total;$li_i++)
		  {
			  $ls_comprobante=$this->dts_reporte->getValue("comprobante",$li_i);
			  $ls_procede=$this->dts_reporte->getValue("procede",$li_i);
			  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
			  $ls_codestpro1=$this->dts_reporte->getValue("codestpro1",$li_i);
			  $ls_codestpro2=$this->dts_reporte->getValue("codestpro2",$li_i);
			  $ls_codestpro3=$this->dts_reporte->getValue("codestpro3",$li_i);
			  $ls_codestpro4=$this->dts_reporte->getValue("codestpro4",$li_i);
			  $ls_codestpro5=$this->dts_reporte->getValue("codestpro5",$li_i);
			  $ls_spg_cuenta=$this->dts_reporte->getValue("spg_cuenta",$li_i);
			  $ls_cod_pro="";
			  $ls_ced_bene="";
			  $ls_nompro="";
			  $ls_nombene="";
			  $ls_tipo_destino="";
			  $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,
			                                                       $ls_ced_bene,$ls_nompro,$ls_nombene,$ls_tipo_destino);
			  if($lb_valido)
			  {
			  
			    $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
				$ls_sql=" SELECT PMV.*, PMV.montoaux AS monto, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
						" FROM   sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP ".
						" WHERE  PMV.procede_doc='".$ls_procede."'    AND PMV.documento='".$ls_comprobante."'     AND ".
						"	     PMV.codestpro1='".$ls_codestpro1."'  AND PMV.codestpro2='".$ls_codestpro2."'     AND ".
						"	     PMV.codestpro3='".$ls_codestpro3."'  AND PMV.codestpro4='".$ls_codestpro4."'     AND ".
						"	     PMV.codestpro5='".$ls_codestpro5."'  AND PMV.spg_cuenta='".$ls_spg_cuenta."'     AND ".
						"	     (PMV.procede<>'".$ls_procede."'      OR  PMV.comprobante<>'".$ls_comprobante."') AND ".
						"	     PCM.cod_pro='".$ls_cod_pro."'        AND PCM.ced_bene='".$ls_ced_bene."'         AND ".
						"        PMV.Fecha<='".$adt_fechas."'         AND PCM.codemp=PMV.codemp                   AND ".
						"        PMV.codemp='".$this->ls_codemp."'    AND PCM.procede=PMV.procede                 AND ".
						" 	     PCM.comprobante=PMV.comprobante      AND PCM.fecha=PMV.fecha                     AND ".
						"        PMV.operacion=POP.operacion                                                          ";
				$rs_data=$this->io_sql->select($ls_sql);
			    if($rs_data===false)
			    {   // error interno sql
				   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
										   MÉTODO->uf_spg_reportes_comprobante_compromiso_no_causados(segundo_select)  
										   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
					$lb_valido = false;
			    }
			    else
			    {
					while($row=$this->io_sql->fetch_row($rs_data))
					{
					   $ls_procede=$row["procede"];
					   $ls_comprobante=$row["comprobante"];
					   $ldt_fecha=$row["fecha"];
					   $ls_codestpro1=$row["codestpro1"]; 
					   $ls_codestpro2=$row["codestpro2"]; 
					   $ls_codestpro3=$row["codestpro3"]; 
					   $ls_codestpro4=$row["codestpro4"]; 
					   $ls_codestpro5=$row["codestpro5"];
					   $ls_spg_cuenta=$row["spg_cuenta"];

					   $ls_procede_doc=$row["procede_doc"];
					   $ls_documento=$row["documento"]; 
					   $ls_operacion=$row["operacion"]; 
					   $ls_descripcion=$row["descripcion"]; 
					   $ld_monto=$row["monto"]; 
					   $li_orden=$row["orden"]; 
					   $li_asignar=$row["asignar"]; 
					   $li_aumento=$row["aumento"]; 
					   $li_disminucion=$row["disminucion"]; 
					   $li_comprometer=$row["comprometer"]; 
					   $li_causar=$row["causar"]; 
					   $li_pagar=$row["pagar"]; 
					   if($li_comprometer==1)
					   {
						 $ld_comprometer=$ld_monto;
					   }
					   else
					   {
						 $ld_comprometer=0;
					   }
					   if($li_causar==1)
					   {
						 $ld_causado=$ld_monto;
					   }
					   else
					   {
						 $ld_causado=0;
					   }
					   if($li_pagar==1)
					   {
						 $ld_pagado=$ld_monto;
					   }
					   else
					   {
						 $ld_pagado=0;
					   }
					   $ar_values["comprobante"]=$ls_comprobante; 
					   $ar_values["procede"]=$ls_procede; 
					   $ar_values["fecha"]=$ldt_fecha; 
					   $ar_values["codestpro1"]=$ls_codestpro1; 
					   $ar_values["codestpro2"]=$ls_codestpro2; 
					   $ar_values["codestpro3"]=$ls_codestpro3; 
					   $ar_values["codestpro4"]=$ls_codestpro4; 
					   $ar_values["codestpro5"]=$ls_codestpro5; 
					   $ar_values["spg_cuenta"]=$ls_spg_cuenta; 
					   //$ar_values["operacion"]=$ls_operacion; 
					   $li_pos=$this->dts_reporte->findValues($ar_values,"comprobante");
					   if($li_pos<0)
					   {
						   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
						   $this->dts_reporte->insertRow("programatica",$ls_programatica);
						   $this->dts_reporte->insertRow("codestpro1",$ls_codestpro1);
						   $this->dts_reporte->insertRow("codestpro2",$ls_codestpro2);
						   $this->dts_reporte->insertRow("codestpro3",$ls_codestpro3);
						   $this->dts_reporte->insertRow("codestpro4",$ls_codestpro4);
						   $this->dts_reporte->insertRow("codestpro5",$ls_codestpro5);
						   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);	
						   $this->dts_reporte->insertRow("procede",$ls_procede);
						   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
						   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
						   $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
						   $this->dts_reporte->insertRow("causado",$ld_causado);					 
						   $this->dts_reporte->insertRow("pagado",$ld_pagado);					 
						   $lb_valido = true;
					   }//if
					}//while
			    }//else
			  }//if
		  }//for
	   }//else
       return  $lb_valido;
   }//uf_spg_reportes_comprobante_compromiso_no_causados
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " COMPROMISOS CAUSADOS PARCIALMENTE  "          //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_compromiso_causados_parcialmente($adt_fecdes,$adt_fechas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_compromiso_causados_parcialmente
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de los compromisos  causados parcialmente 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    22/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_sql=" SELECT distinct PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total, PCM.tipo_destino, ".
              "        PCM.cod_pro, PCM.ced_bene, PMV.operacion  ".
              " FROM   sigesp_cmp PCM, rpc_proveedor PRV, rpc_beneficiario XBF, spg_dt_cmp PMV, spg_operaciones POP ".
              " WHERE  PCM.procede=PMV.procede AND PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha AND ".
      	      "        PMV.operacion=POP.operacion AND POP.comprometer=1 AND POP.causar=0 AND POP.pagar=0  AND  ".
	          "        PMV.monto>=0 AND  PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."' ".
              " ORDER  BY PCM.fecha, PCM.procede, PCM.comprobante ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_compromiso_causados_parcialmente  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ls_procede=$row["procede"];
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   
			   $lb_valido=$this->uf_spg_reportes_comprobante_compromiso_no_causados($ls_procede,$ls_comprobante,$ldt_fecha,$adt_fecdes,$adt_fechas);
			   if($lb_valido)
			   {
				  $li_total=$this->dts_reporte->getRowCount("spg_cuenta");
				  $ld_total_comprometer=0;  
				  $ld_total_causado=0;  
				  $ld_total_pagado=0;  
				  for($li_i=1;$li_i<=$li_total;$li_i++)
				  {
					  $ls_comprobante=$this->dts_reporte->getValue("comprobante",$li_i);
					  $ls_procede=$this->dts_reporte->getValue("procede",$li_i);
					  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
					  $ls_codestpro1=$this->dts_reporte->getValue("codestpro1",$li_i);
					  $ls_codestpro2=$this->dts_reporte->getValue("codestpro2",$li_i);
					  $ls_codestpro3=$this->dts_reporte->getValue("codestpro3",$li_i);
					  $ls_codestpro4=$this->dts_reporte->getValue("codestpro4",$li_i);
					  $ls_codestpro5=$this->dts_reporte->getValue("codestpro5",$li_i);
					  $ls_spg_cuenta=$this->dts_reporte->getValue("spg_cuenta",$li_i);
					  $ld_comprometer=$this->dts_reporte->getValue("compromiso",$li_i);
					  $ld_causado=$this->dts_reporte->getValue("causado",$li_i);
					  $ld_pagado=$this->dts_reporte->getValue("pagado",$li_i);
					  $ls_nompro=$this->dts_reporte->getValue("nompro",$li_i);
					  $ls_nombene=$this->dts_reporte->getValue("nombene",$li_i);
					  $ls_cod_pro=$this->dts_reporte->getValue("cod_pro",$li_i);
					  $ls_ced_bene=$this->dts_reporte->getValue("ced_bene",$li_i);
					  $ls_tipo_destino=$this->dts_reporte->getValue("tipo_destino",$li_i);
					  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
					  
					  $ar_values["codestpro1"]=$ls_codestpro1; 
					  $ar_values["codestpro2"]=$ls_codestpro2; 
					  $ar_values["codestpro3"]=$ls_codestpro3; 
					  $ar_values["codestpro4"]=$ls_codestpro4; 
					  $ar_values["codestpro5"]=$ls_codestpro5; 
					  $ar_values["spg_cuenta"]=$ls_spg_cuenta; 
					  $li_pos=$this->dts_reporte->findValues($ar_values,"spg_cuenta");
					  if($li_pos>0)
					  {
					     $ld_total_comprometer=$ld_total_comprometer+$ld_comprometer;  
					     $ld_total_causado=$ld_total_causado+$ld_causado;  
					     $ld_total_pagado=$ld_total_pagado+$ld_pagado;  
					  }//if
				  }//for  
				  if( ($ld_total_comprometer<>0) && ( ($ld_total_causado<$ld_total_comprometer) && ($ld_total_causado>0) ) )
				  {
					   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
					   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
					   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
					   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
					   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
					   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
					   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);	
					   $this->dts_reporte_final->insertRow("procede",$ls_procede);
					   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
					   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
					   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometer);
					   $this->dts_reporte_final->insertRow("causado",$ld_causado);					 
					   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
					   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
					   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
					   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
					   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
					   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);
					   $lb_valido = true;
				  }//if
				  else
				  {
				    $lb_valido = false;
				  }
			   }//if
			}//while   
		    $this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_compromiso_causados_parcialmente
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " COMPROMISOS CAUSADOS NO PAGADOS  "            //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_compromiso_causados_no_pagados($adt_fecdes,$adt_fechas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_compromiso_causados_no_pagados
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de los compromisos  causados parcialmente 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    22/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
	    $ls_cadena="CONCAT(rtrim(XBF.apebene),', ',XBF.nombene)";
	  }
	  else
	  {
	    $ls_cadena="rtrim(XBF.apebene)||', '||XBF.nombene";
	  }
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_sql=" SELECT distinct PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total, PCM.tipo_destino, ".
              "        PCM.cod_pro,PRV.nompro as nompro, PCM.ced_bene, ".$ls_cadena."  as nombene, PMV.operacion       ".
              " FROM   sigesp_cmp PCM, rpc_proveedor PRV, rpc_beneficiario XBF, spg_dt_cmp PMV, spg_operaciones POP    ".
              " WHERE  PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."'                                       ".
			  "   AND (POP.comprometer=1 AND POP.causar=1 AND POP.pagar=0) AND  PMV.monto>=0                           ".
			  "   AND (PCM.cod_pro=PRV.cod_pro) AND (PCM.ced_bene=XBF.ced_bene) AND (PCM.procede=PMV.procede AND       ".
      	      "        PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha) AND (PMV.operacion=POP.operacion)      ".
              " ORDER  BY PCM.cod_pro, PCM.ced_bene, PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, ".
			  "       PCM.total, PCM.tipo_destino, nompro,nombene, PMV.operacion  ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_compromiso_causados_no_pagados  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ls_procede=$row["procede"];
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   
			   $lb_valido=$this->uf_spg_reportes_comprobante_compromiso_no_causados($ls_procede,$ls_comprobante,$ldt_fecha,                                                                                    $adt_fecdes,$adt_fechas);
			   if($lb_valido)
			   {
				  $li_total=$this->dts_reporte->getRowCount("spg_cuenta");
				  $ld_total_comprometer=0;  
				  $ld_total_causado=0;  
				  $ld_total_pagado=0;  
				  for($li_i=1;$li_i<=$li_total;$li_i++)
				  {
					  
					  $ls_comprobante=$this->dts_reporte->getValue("comprobante",$li_i);
					  $ls_procede=$this->dts_reporte->getValue("procede",$li_i);
					  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
					  $ls_codestpro1=$this->dts_reporte->getValue("codestpro1",$li_i);
					  $ls_codestpro2=$this->dts_reporte->getValue("codestpro2",$li_i);
					  $ls_codestpro3=$this->dts_reporte->getValue("codestpro3",$li_i);
					  $ls_codestpro4=$this->dts_reporte->getValue("codestpro4",$li_i);
					  $ls_codestpro5=$this->dts_reporte->getValue("codestpro5",$li_i);
					  $ls_spg_cuenta=$this->dts_reporte->getValue("spg_cuenta",$li_i);
					  $ld_comprometer=$this->dts_reporte->getValue("compromiso",$li_i);
					  $ld_causado=$this->dts_reporte->getValue("causado",$li_i);
					  $ld_pagado=$this->dts_reporte->getValue("pagado",$li_i);
					  $ls_nompro=$this->dts_reporte->getValue("nompro",$li_i);
					  $ls_nombene=$this->dts_reporte->getValue("nombene",$li_i);
					  $ls_cod_pro=$this->dts_reporte->getValue("cod_pro",$li_i);
					  $ls_ced_bene=$this->dts_reporte->getValue("ced_bene",$li_i);
					  $ls_tipo_destino=$this->dts_reporte->getValue("tipo_destino",$li_i);
					  $ldt_fecha=$this->dts_reporte->getValue("fecha",$li_i);
					
					  
					  $ar_values["codestpro1"]=$ls_codestpro1; 
					  $ar_values["codestpro2"]=$ls_codestpro2; 
					  $ar_values["codestpro3"]=$ls_codestpro3; 
					  $ar_values["codestpro4"]=$ls_codestpro4; 
					  $ar_values["codestpro5"]=$ls_codestpro5; 
					  $ar_values["spg_cuenta"]=$ls_spg_cuenta; 
					  $li_pos=$this->dts_reporte->findValues($ar_values,"spg_cuenta");
					  if($li_pos>0)
					  {
					     $ld_total_comprometer=$ld_total_comprometer+$ld_comprometer;  
					     $ld_total_causado=$ld_total_causado+$ld_causado;  
					     $ld_total_pagado=$ld_total_pagado+$ld_pagado;  
					  }//if
				  }//for  
				  if(($ld_total_comprometer<>0)&&($ld_total_causado<>0)&&($ld_total_pagado==0))
				  {
					   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
					   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
					   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
					   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
					   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
					   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
					   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);	
					   $this->dts_reporte_final->insertRow("procede",$ls_procede);
					   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
					   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
					   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometer);
					   $this->dts_reporte_final->insertRow("causado",$ld_causado);					 
					   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
					   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
					   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
					   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
					   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
					   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);
					   $lb_valido = true;
				  }//if
				  else
				  {
				    $lb_valido = false;
				  }
			   }//if
			}//while   
		    $this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_compromiso_causados_no_pagados
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " OPERACION POR ESPECIFICA  "                   //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_operacion_por_especifica($adt_fecdes,$adt_fechas,$as_spg_cuenta_desde,$as_spg_cuenta_hasta,$ai_est_pres)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_operacion_por_especifica
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
	 //                     $ls_spg_cuenta_desde  //  cuenta desde
	 //                     $ls_spg_cuenta_hasta   // cuenta  hasta
	 //                     $li_est_pres  // estado presupuestario
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida las operaciones por especificas 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
	    $ls_cadena="CONCAT( rtrim(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene)";
		$ls_cadena_programatica="CONCAT(spg_dt_cmp.codestpro1,spg_dt_cmp.codestpro2,spg_dt_cmp.codestpro3,
		                                spg_dt_cmp.codestpro4,spg_dt_cmp.codestpro5)";
	  }
	  else
	  {
	    $ls_cadena="rtrim(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene";
		$ls_cadena_programatica="(spg_dt_cmp.codestpro1||spg_dt_cmp.codestpro2||spg_dt_cmp.codestpro3||
		                                spg_dt_cmp.codestpro4||spg_dt_cmp.codestpro5)";
	  }
	  if($ai_est_pres=="CP")
	  {
	    $ls_estado_presupuestaria="spg_operaciones.comprometer = 1";
	  }
	  if($ai_est_pres=="CS")
	  {
	    $ls_estado_presupuestaria="spg_operaciones.causar  = 1";
	  }
	  if($ai_est_pres=="PG")
	  {
	    $ls_estado_presupuestaria="spg_operaciones.pagar  = 1";
	  }
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_sql=" SELECT  spg_dt_cmp.spg_cuenta,sigesp_cmp.tipo_destino,rpc_beneficiario.apebene,sigesp_cmp.cod_pro, ".
	          "         rpc_beneficiario.nombene,rpc_proveedor.nompro,rpc_beneficiario.ced_bene, ".
	          "         spg_cuentas.spg_cuenta,spg_cuentas.denominacion as den_spg_cta, spg_dt_cmp.montoaux AS monto,   ".
			  "         spg_dt_cmp.procede AS procede,spg_dt_cmp.comprobante AS comprobante,spg_dt_cmp.fecha AS fecha,  ".
			  "         spg_dt_cmp.codestpro1 AS codestpro1,spg_dt_cmp.codestpro2 AS codestpro2,                        ".
			  "         spg_dt_cmp.codestpro3 AS codestpro3,spg_dt_cmp.codestpro4 AS codestpro4,                        ".
			  "         spg_dt_cmp.codestpro5 AS codestpro5,spg_dt_cmp.procede_doc AS procede_doc,                      ".
			  "         spg_dt_cmp.procede_doc AS procede_doc,spg_dt_cmp.documento AS documento,                        ".
			  "         spg_dt_cmp.operacion AS operacion,spg_dt_cmp.descripcion AS descripcion,                        ".
			  "         spg_dt_cmp.orden AS orden, ".$ls_cadena_programatica." as programatica,                         ".
              "         case when (substr(sigesp_cmp.cod_pro,1,1)='-' and substr(sigesp_cmp.ced_bene,1,1)='-')          ".
			  "                    then spg_dt_cmp.descripcion                                                          ".
	  	      "              when (substr(sigesp_cmp.ced_bene,1,1)='-' and substr(sigesp_cmp.cod_pro,1,1)!='-')         ".
			  "                    then rpc_proveedor.nompro                                                            ".
	  	      "              when (substr(sigesp_cmp.cod_pro,1,1)='-' and substr(sigesp_cmp.ced_bene,1,1)!='-')         ".
			  "                    then ".$ls_cadena."  end  AS nom_benef                                               ".
              " FROM   spg_dt_cmp,spg_operaciones,sigesp_cmp,rpc_beneficiario ,rpc_proveedor ,spg_cuentas               ".
              " WHERE  spg_cuentas.codemp='".$this->ls_codemp."'                                                        ".
			  "   AND  spg_dt_cmp.spg_cuenta>='".$as_spg_cuenta_desde."'                                                ".
			  "   AND  spg_dt_cmp.spg_cuenta<='".$as_spg_cuenta_hasta."'                                                ".
			  "   AND  spg_dt_cmp.fecha>='".$adt_fecdes."'                                                              ".
			  "   AND  spg_dt_cmp.fecha<='".$adt_fechas."'                                                              ".
			  "   AND  ".$ls_estado_presupuestaria."                                                                    ".
			  "   AND  spg_dt_cmp.codemp=sigesp_cmp.codemp                          									".
			  "   AND  sigesp_cmp.codemp=rpc_beneficiario.codemp       													".
			  "   AND  rpc_proveedor.codemp=spg_cuentas.codemp 															".
			  "   AND  spg_dt_cmp.operacion=spg_operaciones.operacion       											".
			  "   AND  sigesp_cmp.procede=spg_dt_cmp.procede   															".
			  "   AND  sigesp_cmp.comprobante=spg_dt_cmp.comprobante   													".
			  "   AND  sigesp_cmp.fecha=spg_dt_cmp.fecha																".
			  "   AND  sigesp_cmp.cod_pro=rpc_proveedor.cod_pro        													".
			  "   AND  sigesp_cmp.ced_bene=rpc_beneficiario.ced_bene                                              		".
			  "   AND  spg_dt_cmp.codestpro1=spg_cuentas.codestpro1                                                		".
			  "   AND  spg_dt_cmp.codestpro2=spg_cuentas.codestpro2                                                		".
			  "   AND  spg_dt_cmp.codestpro3=spg_cuentas.codestpro3     												".
			  "   AND  spg_dt_cmp.codestpro4=spg_cuentas.codestpro4 													".
			  "   AND  spg_dt_cmp.codestpro5=spg_cuentas.codestpro5     												".
			  "   AND  spg_dt_cmp.spg_cuenta=spg_cuentas.spg_cuenta    													".
              " ORDER  BY spg_dt_cmp.spg_cuenta, programatica,spg_dt_cmp.fecha                                          ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_operacion_por_especifica  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ls_procede=$row["procede"];
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"]; 
			   $ls_codestpro2=$row["codestpro2"]; 
			   $ls_codestpro3=$row["codestpro3"]; 
			   $ls_codestpro4=$row["codestpro4"]; 
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"]; 
			   $ls_operacion=$row["operacion"]; 
			   $ls_descripcion=$row["descripcion"]; 
			   $ld_monto=$row["monto"]; 
			   $li_orden=$row["orden"]; 
			   $ls_apebene=$row["apebene"]; 
			   $ls_nombene=$row["nombene"]; 
			   $ls_cod_pro=$row["cod_pro"]; 
			   $ls_ced_bene=$row["ced_bene"]; 
			   $ls_den_spg_cta=$row["den_spg_cta"]; 
			   $ls_nom_benef=$row["nom_benef"]; 
			   $ls_tipo_destino=$row["tipo_destino"]; 
			   
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_final->insertRow("den_spg_cta",$ls_den_spg_cta);
			   $this->dts_reporte_final->insertRow("procede",$ls_procede);
			   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte_final->insertRow("monto",$ld_monto);					 
			   $this->dts_reporte_final->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte_final->insertRow("nom_benef",$ls_nom_benef);
			   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);
			   $lb_valido = true;
			}//while   
		    $this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_operacion_por_especifica
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " EJECUTADO POR PARTIDA  "                      //
	/////////////////////////////////////////////////////////////////////////////////////
	function uf_obtener_asignado($as_spg_cuenta_desde,$as_spg_cuenta_hasta)
	{
        $ls_periodo=$_SESSION["la_empresa"]["periodo"];
	    $ldt_fecdes=$this->io_function->uf_convertirdatetobd($ls_periodo);
		$ad_asignado=0;
		$ls_sql=" SELECT spg_cuenta, descripcion, codestpro1, codestpro2, codestpro3, codestpro4, ".
	            "        codestpro5, operacion, sum(montoaux) as monto ".
                " FROM   spg_dt_cmp ".
                " WHERE  codemp='".$this->ls_codemp."' AND spg_cuenta between '".$as_spg_cuenta_desde."' AND ".
			    "        '".$as_spg_cuenta_hasta."' AND fecha ='".$ldt_fecdes."' ".
                " GROUP BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion, monto ".
                " ORDER BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion, monto ";
		  $rs_data=$this->io_sql->select($ls_sql);
		  if($rs_data===false)
		  {   // error interno sql
			   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
									   MÉTODO->uf_spg_reportes_ejecutado_por_partida(uf_obtener_asignado)  
									   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido = false;
		  }
		  else
		  {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			  $ls_operacion=$row["operacion"];
			  $ld_monto=$row["monto"];
			  $ls_mensaje = $this->sigesp_int_spg->uf_operacion_codigo_mensaje($ls_operacion);
			  $ls_mensaje=strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
			  //I-Asignacion
			  $li_pos_i=strpos($ls_mensaje,"I"); 
			  if (!($li_pos_i===false)) 
			  { 
				$ad_asignado=$ad_asignado+$ld_monto; 
			  }
			}
		 }
		 return $ad_asignado;
	}
/********************************************************************************************************************************/	
    function uf_spg_reportes_ejecutado_por_partida($adt_fecdes,$adt_fechas,$as_spg_cuenta_desde,$as_spg_cuenta_hasta)                                                  
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_ejecutado_por_partida
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
	 //                     $ls_spg_cuenta_desde  //  cuenta desde
	 //                     $ls_spg_cuenta_hasta   // cuenta  hasta
	 //                     $li_est_pres  // estado presupuestario
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida las operaciones por especificas 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  $this->dts_cab->resetds("spg_cuenta");
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_sql=" SELECT spg_cuenta, descripcion, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion,        ".
	          "        sum(montoaux) as monto                                                                                 ".
              " FROM   spg_dt_cmp                                                                                             ".
              " WHERE  codemp='".$this->ls_codemp."' AND fecha between '".$adt_fecdes."' AND '".$adt_fechas."'                ".
			  "   AND spg_cuenta between '".$as_spg_cuenta_desde."' AND '".$as_spg_cuenta_hasta."'                            ".
              " GROUP BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion       ".
              " ORDER BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion, monto";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_ejecutado_por_partida  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $datos=$this->io_sql->obtener_datos($rs_data);
		  $this->dts_cab->data=$datos;	
		  $lb_valido = true;
		}
		else
		{
		   $lb_valido = false;
		}
		if($lb_valido)
		{
          $ld_asignado=0;
		  $ld_aumento=0;
          $ld_disminucion=0;
		  $ld_pre_comprometido=0;
          $ld_comprometido=0;
		  $ld_causado=0;
		  $ld_pagado=0;
		  $li_total=$this->dts_cab->getRowCount("spg_cuenta");
		  for($li_i=1;$li_i<=$li_total;$li_i++)
		  {	  
			$li_tmp=($li_i+1);
		    $ls_spg_cuenta=$this->dts_cab->getValue("spg_cuenta",$li_i);
		    $ls_codestpro1=$this->dts_cab->getValue("codestpro1",$li_i);
		    $ls_codestpro2=$this->dts_cab->getValue("codestpro2",$li_i);
		    $ls_codestpro3=$this->dts_cab->getValue("codestpro3",$li_i);
		    $ls_codestpro4=$this->dts_cab->getValue("codestpro4",$li_i);
		    $ls_codestpro5=$this->dts_cab->getValue("codestpro5",$li_i);
		    $ls_operacion=$this->dts_cab->getValue("operacion",$li_i);
		    $ld_monto=$this->dts_cab->getValue("monto",$li_i);
		    $ld_descripcion=$this->dts_cab->getValue("descripcion",$li_i);
		    $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		    if ($li_i<$li_total)
		    {
				$ls_spg_cuenta_next=$this->dts_cab->getValue("spg_cuenta",$li_tmp); 
				$ls_codestpro1_next=$this->dts_cab->getValue("codestpro1",$li_tmp);
				$ls_codestpro2_next=$this->dts_cab->getValue("codestpro2",$li_tmp);
				$ls_codestpro3_next=$this->dts_cab->getValue("codestpro3",$li_tmp);
				$ls_codestpro4_next=$this->dts_cab->getValue("codestpro4",$li_tmp);
				$ls_codestpro5_next=$this->dts_cab->getValue("codestpro5",$li_tmp);
		        $ls_programatica_next=$ls_codestpro1_next.$ls_codestpro2_next.$ls_codestpro3_next.$ls_codestpro4_next.                                      $ls_codestpro5_next;
		    }
		    elseif($li_i=$li_total)
		    {
			   //$ls_spg_cuenta_next=$ls_spg_cuenta;
			   //$ls_programatica_next=$ls_programatica;
			   $ls_spg_cuenta_next="no_next";
			   $ls_programatica_next="no_next";
		    }
	        if(($ls_spg_cuenta==$ls_spg_cuenta_next)&&($ls_programatica==$ls_programatica_next))
			{
  			   $lb_valido=$this->uf_sigesp_reportes_calcular_monto_operacion($ls_operacion,$ld_monto,$ld_asignado,$ld_aumento,
			                                                                 $ld_disminucion,$ld_pre_comprometido,
																			 $ld_comprometido,$ld_causado,$ld_pagado);
			}
			else
			{
  			   $lb_valido=$this->uf_sigesp_reportes_calcular_monto_operacion($ls_operacion,$ld_monto,$ld_asignado,$ld_aumento,
			                                                                 $ld_disminucion,$ld_pre_comprometido,
																			 $ld_comprometido,$ld_causado,$ld_pagado);
			}
			//print "Asignado Antes =>".$ld_asignado.'<br>';
			//$ld_asignado=$this->uf_obtener_asignado($ls_spg_cuenta,$ls_spg_cuenta);
	        //print "Asignado Despues =>".$ld_asignado.'<br>';
			if(($ls_spg_cuenta!=$ls_spg_cuenta_next)&&($ls_programatica!=$ls_programatica_next)&&($lb_valido))
			{
			   $ld_porc_comprometido=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_pre_comprometido-$ld_comprometido;
			   $ld_porc_causado=($ld_pre_comprometido+$ld_comprometido)-$ld_causado;
			   $ld_porc_pagado=$ld_causado-$ld_pagado;
		       $ls_denominacion="";
			   $lb_valido=$this->io_spg_report_funciones->uf_spg_reportes_select_denominacion($ls_spg_cuenta,$ls_denominacion);
			   
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_final->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_final->insertRow("descripcion",$ld_descripcion);
			   $this->dts_reporte_final->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_final->insertRow("aumento",$ld_aumento);
			   $this->dts_reporte_final->insertRow("disminucion",$ld_disminucion);
			   $this->dts_reporte_final->insertRow("precompromiso",$ld_pre_comprometido);					 
			   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometido);					 
			   $this->dts_reporte_final->insertRow("causado",$ld_causado);
			   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
			   $this->dts_reporte_final->insertRow("porc_compromiso",$ld_porc_comprometido);
			   $this->dts_reporte_final->insertRow("porc_causado",$ld_porc_causado);
			   $this->dts_reporte_final->insertRow("porc_pagado",$ld_porc_pagado);
			   $lb_valido = true;
			   $ld_asignado=0;
			   $ld_aumento=0;
			   $ld_disminucion=0;
			   $ld_pre_comprometido=0;
			   $ld_comprometido=0;
			   $ld_causado=0;
			   $ld_pagado=0;
			}//if 
			else
			{
			   $ld_porc_comprometido=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_pre_comprometido-$ld_comprometido;
			   $ld_porc_causado=($ld_pre_comprometido+$ld_comprometido)-$ld_causado;
			   $ld_porc_pagado=$ld_causado-$ld_pagado;
		       $ls_denominacion="";
			   $lb_valido=$this->io_spg_report_funciones->uf_spg_reportes_select_denominacion($ls_spg_cuenta,$ls_denominacion);
			   
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_final->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_final->insertRow("descripcion",$ld_descripcion);
			   $this->dts_reporte_final->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_final->insertRow("aumento",$ld_aumento);
			   $this->dts_reporte_final->insertRow("disminucion",$ld_disminucion);
			   $this->dts_reporte_final->insertRow("precompromiso",$ld_pre_comprometido);					 
			   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometido);					 
			   $this->dts_reporte_final->insertRow("causado",$ld_causado);
			   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
			   $this->dts_reporte_final->insertRow("porc_compromiso",$ld_porc_comprometido);
			   $this->dts_reporte_final->insertRow("porc_causado",$ld_porc_causado);
			   $this->dts_reporte_final->insertRow("porc_pagado",$ld_porc_pagado);
			   $lb_valido = true;
			   $ld_asignado=0;
			   $ld_aumento=0;
			   $ld_disminucion=0;
			   $ld_pre_comprometido=0;
			   $ld_comprometido=0;
			   $ld_causado=0;
			   $ld_pagado=0;
			} 
		  }//for
		}//if 
		$this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_ejecutado_por_partida
/********************************************************************************************************************************/	
    function uf_sigesp_reportes_calcular_monto_operacion($as_operacion,$ad_monto,&$ad_asignado,&$ad_aumento,&$ad_disminucion,
	                                                     &$ad_pre_comprometido,&$ad_comprometido,&$ad_causado,&$ad_pagado)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_sigesp_reportes_calcular_monto_operacion
	 //     Argumentos :    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida las operaciones por especificas 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_mensaje = $this->sigesp_int_spg->uf_operacion_codigo_mensaje($as_operacion);
	  $ls_mensaje=strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
	  //I-Asignacion
	  $li_pos_i=strpos($ls_mensaje,"I"); 
	  if (!($li_pos_i===false)) 
	  { 
		$ad_asignado=$ad_asignado+$ad_monto; 
	  }
	  //A-Aumento 
	  $li_pos_a=strpos($ls_mensaje,"A"); 
	  if (!($li_pos_a===false)) 
	  { 
		 $ad_aumento=$ad_aumento+$ad_monto;
	  }
	  //D-Disminucion
	  $li_pos_d=strpos($ls_mensaje,"D"); 
	  if (!($li_pos_d===false))
	  {	
		$ad_disminucion = $ad_disminucion+$ad_monto; 
	  }
	  //R-PreComprometer
	  $li_pos_r=strpos($ls_mensaje,"R"); 
	  if (!($li_pos_r===false))
	  {	
		$ad_pre_comprometido = $ad_pre_comprometido+$ad_monto; 
	  }
      //O-Comprometer
	  $li_pos_o=strpos($ls_mensaje,"O"); 
	  if (!($li_pos_o===false))
	  {	
		$ad_comprometido = $ad_comprometido+$ad_monto; 

	  }
      //C-Causar
	  $li_pos_c=strpos($ls_mensaje,"C"); 
	  if (!($li_pos_c===false)) 
	  {	
		 $ad_causado=$ad_causado+$ad_monto;
	  }
      //P-Pagar
	  $li_pos_p=strpos($ls_mensaje,"P"); 
	  if (!($li_pos_p===false)) 
	  {	
		 $ad_pagado=$ad_pagado+$ad_monto;
	  }
	return $lb_valido;
   }//fin  uf_sigesp_reportes_calcular_monto_operacion
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " OPERACION POR BANCO  "                        //
	/////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_reportes_operacion_por_banco($adt_fecdes,$adt_fechas,$as_spg_cuenta_desde,$as_spg_cuenta_hasta,
	                                             $as_codban,$as_ctaban,$as_ckbfec,$as_ckbpro,$as_ckbdoc,$as_ckbbene,&$lb_valido)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	        Function : uf_spg_reportes_operacion_por_banco
	//        Argumentos : $adt_fecdes   //   fecha desde
	//                     $adt_fechas   //   fecha hasta 
	//                     $ls_spg_cuenta_desde  //  cuenta desde
	//                     $ls_spg_cuenta_hasta   // cuenta  hasta
	//                     $as_codban  // codigo del banco
	//                     $as_ctaban  // cuenta del banco
	//	         Returns : Retorna el resulset cargado con toda la data obtenida en la consulta.
	//	     Description : Reporte que genera salida las operaciones por banco 
	//        Creado por : Ing. Yozelin Barragán.
	//    Fecha Creación : 26/09/2006
	//     Modificado Por: Ing. Néstor Falcón.  
	//Fecha Modificacion : 02/04/2007.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  if($as_ckbfec==1) { $ls_cadena1=",scb_movbco.fecha"; }
	  else{ $ls_cadena1="";}
	  if($as_ckbpro==1) { $ls_cadena2=",scb_movbco.procede"; }
	  else{ $ls_cadena2="";}
	  if($as_ckbdoc==1) { $ls_cadena3=",scb_movbco.comprobante"; }
	  else{ $ls_cadena3="";}
	  if($as_ckbbene==1) { $ls_cadena4=",scb_movbco.nomproben"; }
	  else{ $ls_cadena4="";}
	  $ls_gestor = strtoupper($_SESSION["ls_gestor"]);	 
	  $ls_cadaux="";
	  switch ($ls_gestor) {
		case 'MYSQL':
			$ls_cadaux = " AND spg_cuentas.spg_cuenta=scb_movbco_spg.spg_cuenta                              ".
						 " AND CONCAT(spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,  ".
						 "            spg_cuentas.codestpro4,spg_cuentas.codestpro5)=scb_movbco_spg.codestpro";
			break;
		case 'POSTGRE':
			$ls_cadaux = " AND spg_cuentas.spg_cuenta=scb_movbco_spg.spg_cuenta".
						 " AND spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||".
						 "     spg_cuentas.codestpro4||spg_cuentas.codestpro5=scb_movbco_spg.codestpro";
			break;
	  }
	
	  $ls_cadena=$ls_cadena1.$ls_cadena2.$ls_cadena3.$ls_cadena4;
	  $ls_sql=" SELECT scb_movbco_spg.spg_cuenta,scb_movbco.comprobante,scb_movbco.codban, 			".
			  "        scb_movbco.ctaban,scb_banco.nomban,scb_movbco.fecha,scb_movbco_spg.codestpro,".
			  "        scb_movbco_spg.desmov,scb_movbco.cod_pro,scb_movbco.ced_bene, 				".
			  "        scb_movbco.nomproben,scb_movbco.tipo_destino,scb_movbco.procede,				".
			  "        scb_movbco_spg.montoaux AS monto,spg_cuentas.denominacion as dencuenta       ".
			  "   FROM scb_movbco ,scb_movbco_spg ,scb_banco,spg_cuentas    			            ".
			  "  WHERE scb_banco.codemp='".$this->ls_codemp."'  									".
			  "    AND scb_movbco.codban='".$as_codban."'  											".
			  "    AND scb_movbco.ctaban='".$as_ctaban."'  											".
			  "    AND scb_movbco.estmov='C' 														".
			  "    AND scb_movbco_spg.spg_cuenta between '".$as_spg_cuenta_desde."' 				".
			  "    AND '".$as_spg_cuenta_hasta."' 													".
			  "    AND scb_movbco.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."' 			".
			  "    AND scb_movbco_spg.codemp=scb_banco.codemp 										".
			  "    AND scb_movbco.codemp=scb_movbco_spg.codemp  									".
			  "    AND scb_movbco.codban=scb_movbco_spg.codban 										".
			  "    AND scb_movbco.ctaban=scb_movbco_spg.ctaban 										".
			  "    AND scb_movbco.numdoc=scb_movbco_spg.numdoc 										".
			  "    AND scb_movbco.codope=scb_movbco_spg.codope 										".
			  "    AND scb_movbco.estmov=scb_movbco_spg.estmov 										".
			  "    AND scb_movbco.codban=scb_banco.codban  											".
			  "    $ls_cadaux                                                                       ".
			  "  ORDER BY  scb_movbco_spg.spg_cuenta ".$ls_cadena." 								";
	 // print $ls_sql;
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
		 {
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class.MÉTODO->uf_spg_reportes_operacion_por_banco.ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		 }
	  return  $rs_data;
	}//fin uf_spg_reportes_operacion_por_banco
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  " RESUMEN DE PROVEEDOR,BENEFICIARIO, CONTRATISTAS  "       //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_resumen_provee_bene_contrat_detalle($adt_fecdes,$adt_fechas,$as_provbenedes,$as_provbenehas,                                                                 $ls_rbtipo)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_resumen_provee_bene_contrat_detalle
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de los compromisos  causados parcialmente 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    22/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  $this->dts_cab->resetds("spg_cuenta");
	  if(($ls_rbtipo=="PC")||($ls_rbtipo=="C"))
	  {
	     $ls_cadena_tipo=" PCM.cod_pro BETWEEN '".$as_provbenedes."' AND '".$as_provbenehas."' ";
		 if($ls_rbtipo=="PC")
		 {
		   $ls_cadena_estatus=" PCM.tipo_destino='P' AND PRV.estpro=1 ";
		 }
		 elseif($ls_rbtipo=="C")
		 {
		   $ls_cadena_estatus=" PCM.tipo_destino='P' AND PRV.estcon=1 ";
		 }
	  }
	  elseif($ls_rbtipo=="B")
	  {
	     $ls_cadena_tipo=" PCM.ced_bene BETWEEN '".$as_provbenedes."' AND '".$as_provbenehas."' ";
         $ls_cadena_estatus=" PCM.tipo_destino='B' ";
	  }
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
	    $ls_cadena="CONCAT(rtrim(XBF.apebene),', ',XBF.nombene)";
	  }
	  else
	  {
	    $ls_cadena="MAX(rtrim(XBF.apebene)||', '||XBF.nombene)";
	  }
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_codemp = $_SESSION["la_empresa"]["codemp"];	  
	  $ls_sql = "SELECT PCM.cod_pro as cod_pro,
					    PCM.procede,
					    PCM.comprobante,
					    PCM.fecha,
					    PCM.descripcion,
					    PCM.total,
					    PCM.tipo_destino,
					    MAX(PRV.nompro) as nompro,
					    PCM.ced_bene,
					    $ls_cadena as nombene,
					    PMV.operacion,PMV.montoaux AS monto
				   FROM sigesp_cmp PCM, rpc_proveedor PRV, rpc_beneficiario XBF, spg_dt_cmp PMV, spg_operaciones POP
				  WHERE PCM.codemp='".$ls_codemp."'
				    AND $ls_cadena_tipo
				    AND (POP.comprometer=1 OR POP.causar=1 OR POP.pagar=1)
				    AND PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."'
				    AND $ls_cadena_estatus
				    AND PMV.monto>=0
				    AND PRV.estpro=1
				    AND PCM.codemp=PMV.codemp
				    AND PCM.comprobante=PMV.comprobante
				    AND PCM.fecha=PMV.fecha
				    AND PCM.codban=PMV.codban
				    AND PCM.ctaban=PMV.ctaban
				    AND PCM.codemp=PRV.codemp
				    AND PCM.cod_pro=PRV.cod_pro
				    AND PCM.codemp=PRV.codemp
				    AND PCM.ced_bene=XBF.ced_bene
				    AND PCM.procede=PMV.procede
				    AND PMV.operacion=POP.operacion
				  GROUP BY PCM.cod_pro, PCM.ced_bene, PCM.fecha, PCM.procede, PCM.comprobante, PCM.descripcion, 
                           PCM.tipo_destino, PMV.operacion, PMV.monto, PCM.total";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_resumen_provee_bene_contrat_detalle  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $datos=$this->io_sql->obtener_datos($rs_data);
		  $this->dts_cab->data=$datos;	
		  $lb_valido = true;
		}
		else
		{
		   $lb_valido = false;
		}
		if($lb_valido)
		{
          $ld_asignado=0;
		  $ld_aumento=0;
          $ld_disminucion=0;
		  $ld_pre_comprometido=0;
          $ld_comprometido=0;
		  $ld_causado=0;
		  $ld_pagado=0;
		  $li_total=$this->dts_cab->getRowCount("cod_pro");
		  for($li_i=1;$li_i<=$li_total;$li_i++)
		  {	 
			$li_tmp=($li_i+1);
		    $ls_spg_cuenta=$this->dts_cab->getValue("spg_cuenta",$li_i);
		    $ls_cod_pro=$this->dts_cab->getValue("cod_pro",$li_i);
		    $ls_procede=$this->dts_cab->getValue("procede",$li_i);
		    $ls_comprobante=$this->dts_cab->getValue("comprobante",$li_i);
		    $ldt_fecha=$this->dts_cab->getValue("fecha",$li_i);
		    $ls_descripcion=$this->dts_cab->getValue("descripcion",$li_i);
		    $ld_total=$this->dts_cab->getValue("total",$li_i);
		    $ls_tipo_destino=$this->dts_cab->getValue("tipo_destino",$li_i);
		    $ls_nompro=$this->dts_cab->getValue("nompro",$li_i);
		    $ls_ced_bene=$this->dts_cab->getValue("ced_bene",$li_i);
		    $ls_nombene=$this->dts_cab->getValue("nombene",$li_i);
		    $ls_operacion=$this->dts_cab->getValue("operacion",$li_i);
		    $ld_monto=$this->dts_cab->getValue("monto",$li_i);
		    if ($li_i<$li_total)
		    {
				$ls_cod_pro_next=$this->dts_cab->getValue("cod_pro",$li_tmp); 
		    }
		    elseif($li_i==$li_total)
		    {
			   $ls_cod_pro_next="no_next";
		    }
	        if($ls_cod_pro==$ls_cod_pro_next)
			{
  			   $lb_valido=$this->uf_sigesp_reportes_calcular_monto_operacion($ls_operacion,$ld_monto,$ld_asignado,$ld_aumento,
			                                                                 $ld_disminucion,$ld_pre_comprometido,
																			 $ld_comprometido,$ld_causado,$ld_pagado);
			}
		    else
			{
  			   $lb_valido=$this->uf_sigesp_reportes_calcular_monto_operacion($ls_operacion,$ld_monto,$ld_asignado,$ld_aumento,
			                                                                 $ld_disminucion,$ld_pre_comprometido,
																			 $ld_comprometido,$ld_causado,$ld_pagado);
			}
	        if(($ls_cod_pro==$ls_cod_pro_next)&&($lb_valido))
			{
		       $ls_denominacion="";
			   $lb_valido=$this->io_spg_report_funciones->uf_spg_reportes_select_denominacion($ls_spg_cuenta,$ls_denominacion);
			   
			   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte_final->insertRow("procede",$ls_procede);
			   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte_final->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);					 
			   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
			   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometido);					 
			   $this->dts_reporte_final->insertRow("causado",$ld_causado);
			   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
			   $lb_valido = true;
			   $ld_asignado=0;
			   $ld_aumento=0;
			   $ld_disminucion=0;
			   $ld_pre_comprometido=0;
			   $ld_comprometido=0;
			   $ld_causado=0;
			   $ld_pagado=0;
			}//if 
		    else
			{
		       $ls_denominacion="";
			   $lb_valido=$this->io_spg_report_funciones->uf_spg_reportes_select_denominacion($ls_spg_cuenta,$ls_denominacion);
			   
			   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte_final->insertRow("procede",$ls_procede);
			   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte_final->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);					 
			   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
			   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometido);					 
			   $this->dts_reporte_final->insertRow("causado",$ld_causado);
			   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
			   $lb_valido = true;
			   $ld_asignado=0;
			   $ld_aumento=0;
			   $ld_disminucion=0;
			   $ld_pre_comprometido=0;
			   $ld_comprometido=0;
			   $ld_causado=0;
			   $ld_pagado=0;
			}
		  }//for
		}//if 
		$this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_resumen_provee_bene_contrat_detalle
/********************************************************************************************************************************/	
    function uf_spg_reportes_resumen_provee_bene_contrat_listado($adt_fecdes,$adt_fechas,$as_provbenedes,$as_provbenehas,$ls_rbtipo)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_resumen_provee_bene_contrat_listado
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de los compromisos  causados parcialmente 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    22/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  $this->dts_cab->resetds("spg_cuenta");
	  if(($ls_rbtipo=="PC")||($ls_rbtipo=="C"))
	  {
	     $ls_cadena_tipo=" PCM.cod_pro BETWEEN '".$as_provbenedes."' AND '".$as_provbenehas."' ";
		 if($ls_rbtipo=="PC")
		 {
		   $ls_cadena_estatus=" PCM.tipo_destino='P' AND PRV.estpro=1 ";
		 }
		 elseif($ls_rbtipo=="C")
		 {
		   $ls_cadena_estatus=" PCM.tipo_destino='P' AND PRV.estcon=1 ";
		 }
	  }
	  elseif($ls_rbtipo=="B")
	  {
	     $ls_cadena_tipo=" PCM.ced_bene BETWEEN '".$as_provbenedes."' AND '".$as_provbenehas."' ";
         $ls_cadena_estatus=" PCM.tipo_destino='B' ";
	  }
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
	    $ls_cadena="CONCAT(rtrim(XBF.apebene),', ',XBF.nombene)";
	  }
	  else
	  {
	    $ls_cadena="rtrim(XBF.apebene)||', '||XBF.nombene";
	  }
	  $adt_fecdes = $this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas = $this->io_function->uf_convertirdatetobd($adt_fechas);
	  $ls_codemp  = $_SESSION["la_empresa"]["codemp"];
	  $ls_sql = "SELECT MAX(PCM.cod_pro) as cod_pro,
					    PCM.procede,
					    PCM.comprobante,
					    PCM.fecha,
					    PCM.descripcion,
					    PCM.total,
					    PCM.tipo_destino,
					    MAX(PRV.nompro) as nompro,
					    PCM.ced_bene,
					    MAX(".$ls_cadena.") as nombene,
					    PMV.operacion, PMV.montoaux AS monto
				   FROM sigesp_cmp PCM, rpc_proveedor PRV, rpc_beneficiario XBF, spg_dt_cmp PMV, spg_operaciones POP
				  WHERE PCM.codemp='".$ls_codemp."'
				    AND $ls_cadena_tipo
				    AND (POP.comprometer=1 OR POP.causar=1 OR POP.pagar=1)
				    AND PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."'
				    AND $ls_cadena_estatus
				    AND PMV.monto>=0
				    AND PRV.estpro=1
				    AND PCM.codemp=PMV.codemp
				    AND PCM.comprobante=PMV.comprobante
				    AND PCM.fecha=PMV.fecha
				    AND PCM.codban=PMV.codban
				    AND PCM.ctaban=PMV.ctaban
				    AND PCM.codemp=PRV.codemp
				    AND PCM.cod_pro=PRV.cod_pro
				    AND PCM.codemp=XBF.codemp
				    AND PCM.ced_bene=XBF.ced_bene
				    AND PCM.procede=PMV.procede
				    AND PMV.operacion=POP.operacion
				  GROUP BY  PRV.cod_pro, PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total, 
                            PCM.tipo_destino,  PCM.ced_bene, XBF.nombene, PMV.operacion, PMV.montoaux 
				  ORDER BY PRV.cod_pro,PCM.ced_bene,PCM.fecha, PCM.procede, PCM.comprobante";
	  $rs_data=$this->io_sql->select($ls_sql);
      if ($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  
		                           MÉTODO->uf_spg_reportes_resumen_provee_bene_contrat_listado  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
      else
      {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $datos=$this->io_sql->obtener_datos($rs_data);
		  $this->dts_cab->data=$datos;	
		  $lb_valido = true;
		}
		else
		{
		   $lb_valido = false;
		}
		if($lb_valido)
		{
          $ld_asignado=0;
		  $ld_aumento=0;
          $ld_disminucion=0;
		  $ld_pre_comprometido=0;
          $ld_comprometido=0;
		  $ld_causado=0;
		  $ld_pagado=0;
		  $li_total=$this->dts_cab->getRowCount("cod_pro");
		  for ($li_i=1;$li_i<=$li_total;$li_i++)
		      {	 
		 	    $li_tmp=($li_i+1);
		        $ls_spg_cuenta	 = $this->dts_cab->getValue("spg_cuenta",$li_i);
		        $ls_cod_pro		 = $this->dts_cab->getValue("cod_pro",$li_i);
		        $ls_procede		 = $this->dts_cab->getValue("procede",$li_i);
		    	$ls_comprobante  = $this->dts_cab->getValue("comprobante",$li_i);
		    	$ldt_fecha		 = $this->dts_cab->getValue("fecha",$li_i);
		    	$ls_descripcion  = $this->dts_cab->getValue("descripcion",$li_i);
		    	$ld_total		 = $this->dts_cab->getValue("total",$li_i);
		    	$ls_tipo_destino = $this->dts_cab->getValue("tipo_destino",$li_i);
		    	$ls_nompro		 = $this->dts_cab->getValue("nompro",$li_i);
		    	$ls_ced_bene	 = $this->dts_cab->getValue("ced_bene",$li_i);
		    	$ls_nombene      = $this->dts_cab->getValue("nombene",$li_i);
		    	$ls_operacion    = $this->dts_cab->getValue("operacion",$li_i);
		    	$ld_monto		 = $this->dts_cab->getValue("monto",$li_i);
		    if ($li_i<$li_total)
		    {
				$ls_cod_pro_next=$this->dts_cab->getValue("cod_pro",$li_tmp); 
		    }
		    elseif($li_i==$li_total)
		    {
			   $ls_cod_pro_next="no_next";
		    }
	        if($ls_cod_pro==$ls_cod_pro_next)
			{
  			   $lb_valido=$this->uf_sigesp_reportes_calcular_monto_operacion($ls_operacion,$ld_monto,$ld_asignado,$ld_aumento,
			                                                                 $ld_disminucion,$ld_pre_comprometido,
																			 $ld_comprometido,$ld_causado,$ld_pagado);
			}
			else
			{
  			   $lb_valido=$this->uf_sigesp_reportes_calcular_monto_operacion($ls_operacion,$ld_monto,$ld_asignado,$ld_aumento,
			                                                                 $ld_disminucion,$ld_pre_comprometido,
																			 $ld_comprometido,$ld_causado,$ld_pagado);
			}
	        if(($ls_cod_pro!=$ls_cod_pro_next)&&($lb_valido))
			{
		       $ls_denominacion="";
			   $lb_valido=$this->io_spg_report_funciones->uf_spg_reportes_select_denominacion($ls_spg_cuenta,$ls_denominacion);
			   
			   $this->dts_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_final->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte_final->insertRow("procede",$ls_procede);
			   $this->dts_reporte_final->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte_final->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte_final->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);					 
			   $this->dts_reporte_final->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte_final->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte_final->insertRow("nombene",$ls_nombene);
			   $this->dts_reporte_final->insertRow("compromiso",$ld_comprometido);					 
			   $this->dts_reporte_final->insertRow("causado",$ld_causado);
			   $this->dts_reporte_final->insertRow("pagado",$ld_pagado);
			   $lb_valido = true;
			   $ld_asignado=0;
			   $ld_aumento=0;
			   $ld_disminucion=0;
			   $ld_pre_comprometido=0;
			   $ld_comprometido=0;
			   $ld_causado=0;
			   $ld_pagado=0;
			}//if 
		  }//for
		}//if 
		$this->io_sql->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_resumen_provee_bene_contrat_listado
/********************************************************************************************************************************/	
	function uf_spg_reporte_select_min_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro1) as codestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro1
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2  // codigo de estructura programatica 2 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro2 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro2
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro3 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro3
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_codestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro4) as codestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro4 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro4=$row["codestpro4"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro4
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_codestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro5) as codestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp = '".$ls_codemp."'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro5 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro5=$row["codestpro5"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro5
/********************************************************************************************************************************/	
   function uf_spg_reporte_select_max_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql="SELECT * FROM spg_ep1 WHERE codemp='".$ls_codemp."' ORDER BY codestpro1  desc limit 1 ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro1 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro1
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2  // codigo de estructura programatica 2 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$ls_codemp."'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro2 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro2
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
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
	 $ls_sql=" SELECT max(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$ls_codemp."'   ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro3 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro3
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_codestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro4
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
	 $ls_sql=" SELECT max(codestpro4) as codestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro3 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro4=$row["codestpro4"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro4
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_codestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro5
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
	 $ls_sql=" SELECT max(codestpro5) as codestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro3 ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro5=$row["codestpro5"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro5
/********************************************************************************************************************************/
	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  " MODIFICACIONES PRESUPUESTARIAS  "                        //
	/////////////////////////////////////////////////////////////////////////////////////
	function uf_select_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$ad_fecha,&$ai_numrows,&$rs_data)
	{
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 //	      Function :	uf_select_dt_comprobante
		 //         Access :	private
		 //     Argumentos :    $as_spg_cuenta_ori  // cuenta origen
		 //                     $as_spg_cuenta_des  // cuenta destino
		 //                     $adt_fecini  // fecha  desde 
		 //              	    $adt_fecfin  // fecha hasta 
		 //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
		 //	   Description :	Reporte que genera salida  del Comprobante Formato 2  
		 //     Creado por :    Ing. Yozelin Barragán.
		 // Fecha Creación :    19/04/2006          Fecha última Modificacion :      Hora :
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		 $lb_valido = true;
		 $ld_fecha  = $this->io_function->uf_convertirdatetobd($ad_fecha);
		 $ls_sql = "SELECT spg_dtmp_cmp.codestpro1,
						   spg_dtmp_cmp.codestpro2,
						   spg_dtmp_cmp.codestpro3,
						   spg_dtmp_cmp.codestpro4,
						   spg_dtmp_cmp.codestpro5,
						   spg_dtmp_cmp.spg_cuenta,
						   spg_dtmp_cmp.operacion,
						   spg_dtmp_cmp.descripcion,
						   spg_dtmp_cmp.monto,
						   sigesp_fuentefinanciamiento.denfuefin,
						   spg_ministerio_ua.denuac
					  FROM sigesp_cmp_md, spg_dtmp_cmp, sigesp_fuentefinanciamiento, spg_ministerio_ua
					 WHERE sigesp_cmp_md.codemp='".$as_codemp."'
					   AND sigesp_cmp_md.procede='".$as_procede."'
					   AND sigesp_cmp_md.comprobante='".$as_comprobante."'
					   AND sigesp_cmp_md.fecha='".$ld_fecha."'
					   AND sigesp_cmp_md.codemp=spg_dtmp_cmp.codemp
					   AND sigesp_cmp_md.procede=spg_dtmp_cmp.procede
					   AND sigesp_cmp_md.comprobante=spg_dtmp_cmp.comprobante
					   AND sigesp_cmp_md.fecha=spg_dtmp_cmp.fecha
					   AND sigesp_cmp_md.codemp=sigesp_fuentefinanciamiento.codemp
					   AND sigesp_cmp_md.codfuefin=sigesp_fuentefinanciamiento.codfuefin
					   AND sigesp_cmp_md.codemp=spg_ministerio_ua.codemp
					   AND sigesp_cmp_md.coduac=spg_ministerio_ua.coduac
				  ORDER BY spg_dtmp_cmp.operacion DESC,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,spg_dtmp_cmp.codestpro4,
						   spg_dtmp_cmp.codestpro5,spg_dtmp_cmp.spg_cuenta";//print $ls_sql.'<br>';
		 $rs_data = $this->io_sql->select($ls_sql);
		 if ($rs_data===false)
			{
			  $this->io_msg->message("Error en consulta metodo->uf_select_dt_comprobante ".$this->fun->uf_convertirmsg($this->io_sql->message));
			  $lb_valido = false;  
			}
		 else
			{
			  $ai_numrows = $this->io_sql->num_rows($rs_data);
			
			  
			}
		 return $lb_valido;
	}
/********************************************************************************************************************************/
     function uf_init_niveles(&$ia_niveles_scg,&$li_posicion)
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funcion,$ia_niveles_scg;
		
		$ls_formato  = ""; $li_posicion=0; $li_indice=0;
		$ls_formato  = trim($_SESSION["la_empresa"]["formpre"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funcion->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funcion->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
		
	}// end function uf_init_niveles
/********************************************************************************************************************************/
	 function uf_select_denctaspg($as_codemp,$as_codestpro1,$as_codestpro2,$as_spgcta)
	 {
		global $io_sql;
		$ls_denctaspg="";
		$ls_sql = "SELECT denominacion 
					 FROM spg_cuentas 
					WHERE codemp='".$as_codemp."' 
					  AND codestpro1='".$as_codestpro1."'
					  AND codestpro2='".$as_codestpro2."'
					  AND codestpro3='000'
					  AND spg_cuenta='".$as_spgcta."'   ";
		$rs_datos = $io_sql->select($ls_sql);
		if ($rs_datos===false)
		   {
			 $lb_valido = false;
			 print $io_sql->message;
		   }
		else
		   {
			 if ($row=$io_sql->fetch_row($rs_datos))
				{
				  $ls_denctaspg = $row["denominacion"];
				}
		   }
		return $ls_denctaspg;
	  }
/********************************************************************************************************************************/
	function uf_select_data($ls_sql,$ls_campo1,$ls_campo2,&$ls_variable1,&$ls_variable2)
	{
		  global $io_sql;
		  $lb_valido = true;
		  $rs_data   = $io_sql->select($ls_sql);//print "Sentencia =>".$ls_sql.'<br>';
		  if ($rs_data===false)
			 {
			   $lb_valido = false;
			   print $io_sql->message;
			 } 
		  else
			 {
				if ($row=$io_sql->fetch_row($rs_data))
				{
				  
				  if (!empty($ls_campo1))
					 {
					   $ls_variable1 = $row["$ls_campo1"];
					 }
				  if (!empty($ls_campo2))
					 {
					   $ls_variable2 = $row["$ls_campo2"]; 
					 }
				  $io_sql->free_result($rs_data);
				}
			 }
		  return $lb_valido;
		}
/********************************************************************************************************************************/
	function uf_select_dt_comprobante_r($as_codemp,$as_procede,$as_comprobante,$ad_fecha, $ai_total ,
										&$la_data,$ia_niveles_scg,$li_posicion,$li_numrows)
	{
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 //	      Function :	uf_select_dt_comprobante_r
		 //         Access :	private
		 //     Argumentos :    $as_codemp  // 
		 //                     $as_procede  // 
		 //                     $as_comprobante  //  
		 //              	    $ad_fecha  // 
		 //                     $ai_total  // 
		 //                     $la_data  // 
		 //                     $ia_niveles_scg  //  
		 //              	    $li_posicion  //
		 //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
		 //	   Description :	Reporte que genera salida de los Reportes de las Modificaciones Presupetaria
		 //                     Insubsistenacias, Traspasos,ReCtificaciones, Credito Adicional  
		 //     Creado por :    Ing. Yozelin Barragán.
		 // Fecha Creación :    19/09/2007          Fecha última Modificacion :      Hora :
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=$this->uf_select_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$ad_fecha,&$ai_numrows,&$rs_dat);
		 
		 $ls_codemp=$this->ls_codemp;
		 $ls_procede=$as_procede;
		 $ls_comprobante=$as_comprobante;
		 $ld_fecha=$ad_fecha;
		 $li_total=$ai_total;
		 if($lb_valido)
		 {
			if ($ai_numrows)
			{
				  $li_pos = 0;
				 $lb_impreso = false;
				 $ld_totced  = 0;
				 $ld_totrec  = 0; 
				 $li_filas   = 0;
				 while ($row=$this->io_sql->fetch_row($rs_dat))
				 {
						 $li_pos++;
						 $ls_codestpro1   = $row["codestpro1"];
						 $ls_denestpro1   = "";
						 $ld_monniv       = 0;
						 $ls_codestpro2   = $row["codestpro2"];
						 $ls_codestpro3   = $row["codestpro3"];
						 $ls_codestpro4   = $row["codestpro4"];
						 $ls_codestpro5   = $row["codestpro5"];
						 $ls_spg_cuenta   = $row["spg_cuenta"];
						 $ls_denominacion = $row["descripcion"];
						 $ls_operacion    = $row["operacion"];
						 $ld_monto        = $row["monto"];
				
						 if ($ls_operacion=='DI')
							{
							  $ld_totced = ($ld_totced+$ld_monto);
							} 
						 elseif($ls_operacion=='AU')
							{
							  $ld_totrec = ($ld_totrec+$ld_monto);
							}
						 
						 $ld_monto        = number_format($ld_monto,2,',','.');
						 $ld_fecha        =$this->io_function->uf_convertirdatetobd($ld_fecha); 
						 $this->uf_select_data("SELECT spg_ep1.denestpro1, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_ep1, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_ep1.codemp=spg_dtmp_cmp.codemp
											AND spg_ep1.codestpro1=spg_dtmp_cmp.codestpro1
										  GROUP BY denestpro1",'denestpro1','monto',$ls_denestpro1,$ld_monniv);
						 
						  $la_estpro1 = str_split($ls_codestpro1,1);
						 for ($li=0;$li<20;$li++)
							 {
							   if($la_estpro1[$li]>0)
								{							
								  break;
								}
							}					
							if($li==19)
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li-1,(strlen($ls_codestpro1)-($li-1)));
							}
							else
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li,(strlen($ls_codestpro1)-$li));
							}
						
						 $la_estpro2 = str_split($ls_codestpro2,1);//print_r ($la_estpro1); 
						 for ($li=0;$li<6;$li++)
							 {
							   if($la_estpro2[$li]>0)
								{							
								  break;
								}
							}					
							if($li==5)
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li-1,(strlen($ls_codestpro2)-($li-1)));
							}
							else
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li,(strlen($ls_codestpro2)-$li));
							}
							 if ($li_pos=='1'){
						 $this->io_dsreport->insertRow("proyecto","");
						 $this->io_dsreport->insertRow("accion","");
						 $this->io_dsreport->insertRow("ejecutora","");
						 $this->io_dsreport->insertRow("partida","");
						 $this->io_dsreport->insertRow("generica","");
						 $this->io_dsreport->insertRow("especifica","");
						 $this->io_dsreport->insertRow("subespecifica","");
						 $this->io_dsreport->insertRow("proyecto2","");
						 $this->io_dsreport->insertRow("accion2","");
						 $this->io_dsreport->insertRow("ejecutora2","");
						 $this->io_dsreport->insertRow("partida2","");
						 $this->io_dsreport->insertRow("generica2","");
						 $this->io_dsreport->insertRow("especifica2","");
						 $this->io_dsreport->insertRow("subespecifica2","");
						 $this->io_dsreport->insertRow("denominacion",'<b><c:uline>PARTIDAS CEDENTES--->:</c:uline></b>');
						 $this->io_dsreport->insertRow("monto","");
						 $this->io_dsreport->insertRow("operacion",$ls_operacion);
						 }
						 
						 if ($ls_operacion=='AU' && !$lb_impreso)
							{
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'');
							  $this->io_dsreport->insertRow("monto","");
							  $this->io_dsreport->insertRow("operacion",$ls_operacion);
	
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'<b><c:uline>TOTAL CEDENTES:</c:uline></b>');
							  $this->io_dsreport->insertRow("monto",$ld_totced);
							  $this->io_dsreport->insertRow("operacion",$ls_operacion);
							  
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'');
							  $this->io_dsreport->insertRow("monto","");
							  $this->io_dsreport->insertRow("operacion",$ls_operacion);
							 
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'<b><c:uline>PARTIDAS RECEPTORAS:</c:uline></b>');
							  $this->io_dsreport->insertRow("monto","");
							  $this->io_dsreport->insertRow("operacion",$ls_operacion);
							  $lb_impreso = true;
							}
						 
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'operacion'=>$ls_operacion),'proyecto');
						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora","");
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2","");
							 $this->io_dsreport->insertRow("ejecutora2","");
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro1);
							 $this->io_dsreport->insertRow("monto",$ld_monniv);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							 
							}
						 $this->uf_select_data("SELECT spg_ep2.denestpro2, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_ep2, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_ep2.codemp=spg_dtmp_cmp.codemp
											AND spg_ep2.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_ep2.codestpro2=spg_dtmp_cmp.codestpro2
										  GROUP BY denestpro2",'denestpro2','monto',$ls_denestpro2,$ld_monniv);					 
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';
						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora","");
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2","");
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro2);
							 $this->io_dsreport->insertRow("monto",$ld_monniv);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}				 
							
						 $li_totfil       = 0;
						 $as_cuenta       = "";
						 for ($li=$li_total;$li>1;$li--)
							 {
							   $li_ant    = $ia_niveles_scg[$li-1];
							   $li_act    = $ia_niveles_scg[$li];
							   $li_fila   = $li_act-$li_ant;
							   $li_len    = strlen($ls_spg_cuenta);
							   $li_totfil = $li_totfil+$li_fila;
							   $li_inicio = $li_len-$li_totfil;
							   if ($li==$li_total)
								  {
									$as_cuenta=substr($ls_spg_cuenta,$li_inicio,$li_fila);
								  }
							   else
								  {
									$as_cuenta=substr($ls_spg_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
								  }
							 }
						$li_fila      = $ia_niveles_scg[1]+1;
						$as_cuenta    = substr($ls_spg_cuenta,0,$li_fila)."-".$as_cuenta;
						$la_cuenta    = split("-",$as_cuenta);
						$ls_spgcta    = str_pad($la_cuenta[0],$li_len,0);
						$ls_denctaspg = $this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
						$ls_campo1="";
						$ls_variable1="";
						$this->uf_select_data("SELECT sum(spg_dtmp_cmp.monto) as monto
										  FROM spg_cuentas, spg_dtmp_cmp
										 WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
										   AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
										   AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
										   AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
										   AND spg_dtmp_cmp.procede = '".$ls_procede."'
										   AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
										   AND spg_dtmp_cmp.operacion='".$ls_operacion."'
										   AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0]."%'
										   AND spg_cuentas.status='C'
										   AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
										   AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
										   AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
										   AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
										   AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
										   AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
										   AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
										 GROUP BY spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
												  spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5 ORDER BY spg_cuentas.spg_cuenta ASC",$ls_campo1,'monto',$ls_variable1,$ld_monspg);					 
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'partida2'=>$la_cuenta[0],'operacion'=>$ls_operacion),'proyecto');
						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora","");
							 $this->io_dsreport->insertRow("partida",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2","");
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $ls_denctaspg = strtoupper($ls_denctaspg);
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							} 
						 $ls_campo1="";
						 $ls_variable1="";
						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1],$li_len,0);
						 $ls_denctaspg =$this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
	
						 $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1]."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5",$ls_campo1,'monto',$ls_variable1,$ld_monspg);					 
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'operacion'=>$ls_operacion),'proyecto');
						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora","");
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2","");
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 
						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1].$la_cuenta[2],$li_len,0);
						 $ls_denctaspg = $this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
						 
						 $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1].$la_cuenta[2]."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5",$ls_campo1,'monto',$ls_variable1,$ld_monspg);					 
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'operacion'=>$ls_operacion),'proyecto');
						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora","");
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica",$la_cuenta[2]);
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2","");
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
							 $this->io_dsreport->insertRow("subespecifica2",'');
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}					
						 $ls_subespaux = $la_cuenta["3"];
						 if ($ls_subespaux!='00')
							{
							   $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
								FROM spg_cuentas, spg_dtmp_cmp
							   WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
								 AND spg_dtmp_cmp.procede = '".$ls_procede."'
								 AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
								 AND spg_dtmp_cmp.operacion='".$ls_operacion."'
								 AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1].$la_cuenta[2].$la_cuenta[3]."%'
								 AND spg_cuentas.status='C'
								 AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
								 AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
								 AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
								 AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
								 AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
								 AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
								 AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
							   GROUP BY spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
										 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5",$ls_campo1,'monto',$ls_variable1,$ld_monspg);					 
							   $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'subespecifica2'=>$la_cuenta[3],'operacion'=>$ls_operacion),'proyecto');
							   if ($li_encontrado<=0)
								  {
									 $this->io_dsreport->insertRow("proyecto","");
									 $this->io_dsreport->insertRow("accion","");
									 $this->io_dsreport->insertRow("ejecutora","");
									 $this->io_dsreport->insertRow("partida","");
									 $this->io_dsreport->insertRow("generica","");
									 $this->io_dsreport->insertRow("especifica","");
									 $this->io_dsreport->insertRow("subespecifica",$la_cuenta[3]);
									 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
									 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
									 $this->io_dsreport->insertRow("ejecutora2","");
									 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
									 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
									 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
									 $this->io_dsreport->insertRow("subespecifica2",$la_cuenta[3]);
									 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
									 $this->io_dsreport->insertRow("monto",$ld_monspg);
									 $this->io_dsreport->insertRow("operacion",$ls_operacion);
								  }	
							}
						 
						 if ($li_pos==$li_numrows)
							{
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'');
							  $this->io_dsreport->insertRow("monto","");
							  $this->io_dsreport->insertRow("operacion",'');
													  
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'<b><c:uline>TOTAL RECEPTORAS:</c:uline></b>');
							  $this->io_dsreport->insertRow("monto",$ld_totrec);
							  $this->io_dsreport->insertRow("operacion",'');
	
							  $this->io_dsreport->insertRow("proyecto","");
							  $this->io_dsreport->insertRow("accion","");
							  $this->io_dsreport->insertRow("ejecutora","");
							  $this->io_dsreport->insertRow("partida","");
							  $this->io_dsreport->insertRow("generica","");
							  $this->io_dsreport->insertRow("especifica","");
							  $this->io_dsreport->insertRow("subespecifica","");
							  $this->io_dsreport->insertRow("proyecto2","");
							  $this->io_dsreport->insertRow("accion2","");
							  $this->io_dsreport->insertRow("ejecutora2","");
							  $this->io_dsreport->insertRow("partida2","");
							  $this->io_dsreport->insertRow("generica2","");
							  $this->io_dsreport->insertRow("especifica2","");
							  $this->io_dsreport->insertRow("subespecifica2","");
							  $this->io_dsreport->insertRow("denominacion",'');
							  $this->io_dsreport->insertRow("monto","");
							  $this->io_dsreport->insertRow("operacion",'');
							}
						 $li_totrow = $this->io_dsreport->getRowCount("proyecto");
						 for ($i=1;$i<=$li_totrow;$i++) 
							 {
							   $ls_proyecto     = $this->io_dsreport->getValue("proyecto",$i);
							   $ls_accion       = $this->io_dsreport->getValue("accion",$i);
							   $ls_ejecutora    = $this->io_dsreport->getValue("ejecutora",$i);
							   $ls_partida      = $this->io_dsreport->getValue("partida",$i);
							   $ls_generica     = $this->io_dsreport->getValue("generica",$i);
							   $ls_especifica   = $this->io_dsreport->getValue("especifica",$i);
							   $ls_subespecifica= $this->io_dsreport->getValue("subespecifica",$i);
							   $ls_denominacion = $this->io_dsreport->getValue("denominacion",$i);
							   $ld_monto        = $this->io_dsreport->getValue("monto",$i);
							   if (!empty($ld_monto))
								  {
									$ld_monto = number_format($ld_monto,2,',','.');
								  }
							   $la_data[$i] = array('proyecto'=>$ls_proyecto,
											  'accion'=>$ls_accion,
											  'ejecutora'=>$ls_ejecutora,
											  'partida'=>$ls_partida,
											  'generica'=>$ls_generica,
											  'especifica'=>$ls_especifica,
											  'subespecifica'=>$ls_subespecifica,
											  'denominacion'=>$ls_denominacion,
											  'monto'=>$ld_monto);
							 }
				
				}
		}
		}
	
		 return $lb_valido;
	}
	///////////////////////////////////////////////////////////////////////////////////////
	//  FIN DE  CLASE REPORTES SPG  " MODIFICACIONES PRESUPUESTARIAS  "       //
	/////////////////////////////////////////////////////////////////////////////////////
/********************************************************************************************************************************/
	function uf_select_denominacionspg($as_cuenta,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denominacion ".
				 " FROM   spg_cuentas ".
				 " WHERE  codemp='".$this->ls_codemp."'  AND  spg_cuenta='".$as_cuenta."' ";       
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_denominacionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denominacion=$row["denominacion"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
/********************************************************************************************************************************/
   function uf_spg_reporte_select_resumen_fideicomiso($as_codfuefindes)
   { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reporte_select_resumen_fideicomiso
	 //         Access : private
	 //     Argumentos : $as_codfuefindes  ---> codigo fuente de financiamiento desde 
	 //                  $as_codfuefinhas  ---> codigo fuente de financiamiento hasta 
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : devuelve un datastore  con la informacion del reporte
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 22/09/2007             Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=false;
	 $this->dts_reporte->resetds("spg_cuenta");
	 //ASIENTOS PRESUPUESTARIOS DE LA SEP 
	 $ls_sql=" SELECT MAX(sep_solicitud.codfuefin) AS codfuefin, MAX(sep_cuentagasto.codemp) AS codemp,   ".
	         "        MAX(sep_cuentagasto.numsol) AS numsol, sep_cuentagasto.codestpro1, ".
       		 "        sep_cuentagasto.codestpro2, sep_cuentagasto.codestpro3, ".
       		 " 		  sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, sep_cuentagasto.spg_cuenta, ".
             "        SUM(sep_cuentagasto.monto) AS monto ".
			 " FROM   sep_solicitud, sep_cuentagasto, sep_tiposolicitud ".
             " WHERE  sep_solicitud.codemp='".$this->ls_codemp."' AND ".
             "        sep_solicitud.codfuefin='".$as_codfuefindes."' AND ".
             "        sep_solicitud.estsol='C' AND ".
       		 "   	  sep_tiposolicitud.estope='O' AND ".
             "  	  sep_solicitud.codemp=sep_cuentagasto.codemp AND ".
             "        sep_solicitud.numsol=sep_cuentagasto.numsol AND ".
             "        sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol ".
			 " GROUP BY codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_spg_reporte_select_resumen_fideicomiso SEP ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	 }
     else
     {
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		   $ls_codfuefin=$row["codfuefin"];
		   $ls_codemp=$row["codemp"];
		   $ls_numsol=$row["numsol"];
		   $ls_codestpro1=$row["codestpro1"];
		   $ls_codestpro2=$row["codestpro2"];
		   $ls_codestpro3=$row["codestpro3"];
		   $ls_codestpro4=$row["codestpro4"];
		   $ls_codestpro5=$row["codestpro5"];
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ld_monto=$row["monto"];
           $ls_denominacion="";
	       $lb_valido=$this->uf_select_denominacionspg($ls_spg_cuenta,&$ls_denominacion);		   
		   
		   $this->dts_reporte->insertRow("codfuefin",$ls_codfuefin);
		  // $this->dts_reporte->insertRow("codemp",$ls_codemp);
		   //$this->dts_reporte->insertRow("numsol",$ls_numsol);
		   $this->dts_reporte->insertRow("programatica",$ls_programatica);
		   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
		   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
		   $this->dts_reporte->insertRow("monto",$ld_monto);
		   $lb_valido = true;
		  }//while 
     }//else
	 //ASIENTOS PRESUPUESTARIOS DE SOC 
	 $ls_sql=" SELECT MAX(soc_ordencompra.numordcom) AS numordcom, MAX(soc_ordencompra.estcondat) AS estcondat, ".
	 		 "        MAX(soc_ordencompra.codfuefin) AS codfuefin, soc_cuentagasto.codestpro1, ".
             "        soc_cuentagasto.codestpro2, soc_cuentagasto.codestpro3,  ".
             "        soc_cuentagasto.codestpro4, soc_cuentagasto.codestpro5, soc_cuentagasto.spg_cuenta, ".
             "        SUM(soc_cuentagasto.monto) AS monto ".
             " FROM   soc_ordencompra, soc_cuentagasto ".
			 " WHERE  soc_ordencompra.codemp='".$this->ls_codemp."' AND ".
             "        soc_ordencompra.codfuefin='".$as_codfuefindes."' AND  ".
       		 "		  soc_ordencompra.estcom='2' AND ".
             "        soc_ordencompra.codemp=soc_cuentagasto.codemp AND ".
		     "        soc_ordencompra.numordcom=soc_cuentagasto.numordcom AND ".
             "        soc_ordencompra.estcondat=soc_cuentagasto.estcondat ".
             " GROUP BY codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta";
	 $rs_data_soc=$this->io_sql->select($ls_sql);
	 if($rs_data_soc===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_spg_reporte_select_resumen_fideicomiso SOC ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	 }
     else
     {
		while($row=$this->io_sql->fetch_row($rs_data_soc))
		{
		   $ls_numordcom=$row["numordcom"];
		   $ls_estcondat=$row["estcondat"];
		   $ls_codfuefin=$row["codfuefin"];
		   $ls_codestpro1=$row["codestpro1"];
		   $ls_codestpro2=$row["codestpro2"];
		   $ls_codestpro3=$row["codestpro3"];
		   $ls_codestpro4=$row["codestpro4"];
		   $ls_codestpro5=$row["codestpro5"];
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ld_monto=$row["monto"];
           $ls_denominacion="";
	       $lb_valido=$this->uf_select_denominacionspg($ls_spg_cuenta,&$ls_denominacion);		   
		   
		   //$this->dts_reporte->insertRow("numordcom",$ls_numordcom);
		   //$this->dts_reporte->insertRow("estcondat",$ls_estcondat);
		   $this->dts_reporte->insertRow("codfuefin",$ls_codfuefin);
		   $this->dts_reporte->insertRow("programatica",$ls_programatica);
		   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
		   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
		   $this->dts_reporte->insertRow("monto",$ld_monto);
		   $lb_valido = true;
		  }//while 
     }//else
	 //ASIENTOS PRESUPUESTARIOS DE CXP 
	 $ls_sql=" SELECT MAX(cxp_solicitudes.numsol) AS numsol, MAX(cxp_solicitudes.codfuefin) AS codfuefin, ".
	 		 "        MAX(cxp_dt_solicitudes.numrecdoc) AS numrecdoc, MAX(cxp_dt_solicitudes.codtipdoc) AS codtipdoc,".
             "        cxp_rd_spg.codestpro, cxp_rd_spg.spg_cuenta, SUM(cxp_rd_spg.monto) AS monto ".
             " FROM   cxp_solicitudes, cxp_dt_solicitudes, cxp_documento, cxp_rd_spg ".
             " WHERE  cxp_solicitudes.codemp='".$this->ls_codemp."'  AND ".
             "        cxp_solicitudes.codfuefin='".$as_codfuefindes."' AND ".
       		 "		  cxp_solicitudes.estprosol='C'  AND ".
       		 "        cxp_documento.estcon='1'  AND   ".
             "        cxp_documento.estpre='2'  AND ".
      		 "        cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol AND ".
       		 "    	  cxp_dt_solicitudes.codtipdoc=cxp_documento.codtipdoc AND ".
       		 "        cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ".
             " GROUP BY cxp_rd_spg.codestpro, cxp_rd_spg.spg_cuenta";
	 $rs_data_cxp=$this->io_sql->select($ls_sql);
	 if($rs_data_cxp===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_spg_reporte_select_resumen_fideicomiso CXP ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	 }
     else
     {
		while($row=$this->io_sql->fetch_row($rs_data_cxp))
		{
		   $ls_numsol=$row["numsol"];
		   $ls_codfuefin=$row["codfuefin"];
		   $ls_numrecdoc=$row["numrecdoc"];
		   $ls_codtipdoc=$row["codtipdoc"];
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ld_monto=$row["monto"];
		   $ls_codestpro=$row["codestpro"];
		   $ls_codestpro1=substr($ls_codestpro,0,20);
		   $ls_codestpro2=substr($ls_codestpro,20,6);
		   $ls_codestpro3=substr($ls_codestpro,26,3);
		   $ls_codestpro4=substr($ls_codestpro,29,2);
	  	   $ls_codestpro5=substr($ls_codestpro,31,2);
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
           $ls_denominacion="";
	       $lb_valido=$this->uf_select_denominacionspg($ls_spg_cuenta,&$ls_denominacion);		   
		   
		   //$this->dts_reporte->insertRow("numordcom",$ls_numordcom);
		   //$this->dts_reporte->insertRow("estcondat",$ls_estcondat);
		   $this->dts_reporte->insertRow("codfuefin",$ls_codfuefin);
		   $this->dts_reporte->insertRow("programatica",$ls_programatica);
		   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
		   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
		   $this->dts_reporte->insertRow("monto",$ld_monto);
		   $lb_valido = true;
		  }//while 
	    $this->io_sql->free_result($rs_data);   
	    $this->io_sql->free_result($rs_data_soc);   
	    $this->io_sql->free_result($rs_data_cxp);   
     }//else*/
	 //$this->dts_reporte->group_by(array('0'=>'programatica','1'=>'spg_cuenta'),array('0'=>'monto'),'monto');
	 $this->dts_reporte->group_by(array('0'=>'spg_cuenta'),array('0'=>'monto'),'monto');
	 $li_rows=$this->dts_reporte->getRowCount('codfuefin');	
	 for($li=1;$li<=$li_rows;$li++)
	 {
	   $ls_spg_cuenta=$this->dts_reporte->getValue('spg_cuenta',$li);
	   $ls_denominacion=$this->dts_reporte->getValue('denominacion',$li);
	   $ld_monto=$this->dts_reporte->getValue('monto',$li);
	 }
	 //print_r($this->dts_reporte);
     return  $lb_valido;
    }// fin uf_spg_reporte_select_resumen_fideicomiso
/********************************************************************************************************************************/

/********************************************************************************************************************************/
	function uf_select_denominacion_fuentefideicomiso($as_codfuefin,&$as_denfuefin)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_denominacion_fuentefideicomiso
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT *                                      ".
			 	 " FROM    sigesp_fuentefinanciamiento           ".		
				 " WHERE   codemp = '".$this->ls_codemp."'  AND  ".
				 "          codfuefin='".$as_codfuefin."'        ".		
				 " ORDER  BY codfuefin ASC                       ";
		 $rs_data=$this->io_sql->select($ls_sql);
		 if ($rs_data===false)
		 {
			$lb_valido=false;
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_select_denominacion_fuentefideicomiso  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs_data))
			 { 		   
				$as_denfuefin=$row["denfuefin"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacion_fuentefideicomiso
/********************************************************************************************************************************/
}//fin de la clase
?>