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
class sigesp_spg_reportes_class
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
    function  sigesp_spg_reportes_class()
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
																		 $as_estclades,$as_estclahas,$as_cuentades,$as_cuentahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_distribucion_mensual_presupuesto
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    07/09/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//para tener el arreglo de niveles
	  $ls_formato = trim($_SESSION["la_empresa"]["formpre"])."-";
	  $li_posicion = 1;
	  $li_indice   = 1;
	  $li_posicion = $this->io_function->uf_posocurrencia($ls_formato,"-" , $li_indice ) - $li_indice;
	  do
	  {
	   $this->ia_niveles_spg[$li_indice] = $li_posicion ;
	   $li_indice = $li_indice + 1;
	   $li_posicion = $this->io_function->uf_posocurrencia($ls_formato,"-" , $li_indice ) - $li_indice;
	  } while ($li_posicion>=0);
		///////////////////////////
	    $lb_valido = false;
		$ls_seguridad="";
	 	$this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PCT',$ls_seguridad);
		if($this->li_estmodest==1)
		{
	      $ls_tabla="spg_ep3";
		  $ls_rel_tablas = " PCT.codestpro1 = E.codestpro1 AND ".
		                   " PCT.codestpro2 = E.codestpro2 AND ".
						   " PCT.codestpro3 = E.codestpro3 AND ".
						   " PCT.estcla     = E.estcla ";
		}
		elseif($this->li_estmodest==2)
		{
	      $ls_tabla="spg_ep5";
		  $ls_rel_tablas = " PCT.codestpro1 = E.codestpro1 AND ".
		                   " PCT.codestpro2 = E.codestpro2 AND ".
						   " PCT.codestpro3 = E.codestpro3 AND ".
						   " PCT.codestpro4 = E.codestpro4 AND ".
						   " PCT.codestpro5 = E.codestpro5 AND ".
						   " PCT.estcla     = E.estcla ";
		}
        $ls_str_sql_where="";
		$ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori.$as_estclades;
		$ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des.$as_estclahas;
		$this->uf_obtener_rango_programatica($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
											 $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
											 $ls_Sql_Where,$ls_str_estructura_from,$ls_str_estructura_to,$as_estclades,$as_estclahas);

		$ls_Sql_Where = trim($ls_Sql_Where);
		if ( !empty($ls_Sql_Where) )
		{
		   $ls_str_sql_where=$ls_Sql_Where." AND ";
		}
		else
		{
		   $ls_str_sql_where="";
		}
		$ls_str_sql_where_e=str_replace("PCT","E",$ls_str_sql_where);
		$ls_str_sql_where_b=strstr($ls_str_sql_where_e,"between");
		$ls_str_sql_where_e=str_replace($ls_str_sql_where_b,"",$ls_str_sql_where_e);

		$ls_str_sql_where_p=strstr($ls_str_sql_where,"between");
		$ls_str_sql_where_p=str_replace($ls_str_sql_where_p,"",$ls_str_sql_where);

		/*$ls_sql=" SELECT PCT.* ".
				  " FROM  spg_cuentas PCT, ".$ls_tabla." E  ".
				  " WHERE PCT.codemp='".$this->ls_codemp."' AND ".$ls_str_sql_where." ".
				  "	     E.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' AND ".
				  "      PCT.codemp=E.codemp  AND  PCT.status='C' AND ".
				  "      ".$ls_str_sql_where_p."=".$ls_str_sql_where_e." ".
				  " ORDER BY PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,".
				  "          PCT.codestpro4,PCT.codestpro5,PCT.estcla,PCT.spg_cuenta ";*/
		$ls_sql=" SELECT PCT.* ".
				  " FROM  spg_cuentas PCT, ".$ls_tabla." E  ".
				  " WHERE PCT.codemp='".$this->ls_codemp."' AND ".$ls_str_sql_where." ".
				  "	     E.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' AND ".
				  "      PCT.codemp=E.codemp  ".
				  "      AND (PCT.status='C' or PCT.status='S')  ".
				  "      AND ".$ls_rel_tablas." ".
				  " AND PCT.spg_cuenta between '".$as_cuentades."' AND '".$as_cuentahas."'".$ls_seguridad.
				  " ORDER BY PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,".
				  "          PCT.codestpro4,PCT.codestpro5,PCT.estcla,PCT.spg_cuenta ";	//print $ls_sql;
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
			   $ls_estcla=$row["estcla"];
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
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
			   $ls_status=$row["status"];
			   $ls_estmodprog=	$_SESSION["la_empresa"]["estmodprog"];
			   if($ls_estmodprog==1)
			   {
				   $li_aumenero=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","enero",$ls_status,$li_nivel);
				   $li_aumfebrero=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","febrero",$ls_status,$li_nivel);
				   $li_aummarzo=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","marzo",$ls_status,$li_nivel);
				   $li_aumabril=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","abril",$ls_status,$li_nivel);
				   $li_aummayo=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","mayo",$ls_status,$li_nivel);
				   $li_aumjunio=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","junio",$ls_status,$li_nivel);
				   $li_aumjulio=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","julio",$ls_status,$li_nivel);
				   $li_aumagosto=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","agosto",$ls_status,$li_nivel);
				   $li_aumseptiembre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","septiembre",$ls_status,$li_nivel);
				   $li_aumoctubre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","octubre",$ls_status,$li_nivel);
				   $li_aumnoviembre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","noviembre",$ls_status,$li_nivel);
				   $li_aumdiciembre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"AU","diciembre",$ls_status,$li_nivel);

				   $li_disenero=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","enero",$ls_status,$li_nivel);
				   $li_disfebrero=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","febrero",$ls_status,$li_nivel);
				   $li_dismarzo=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","marzo",$ls_status,$li_nivel);
				   $li_disabril=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","abril",$ls_status,$li_nivel);
				   $li_dismayo=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","mayo",$ls_status,$li_nivel);
				   $li_disjunio=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","junio",$ls_status,$li_nivel);
				   $li_disjulio=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","julio",$ls_status,$li_nivel);
				   $li_disagosto=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","agosto",$ls_status,$li_nivel);
				   $li_disseptiembre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","septiembre",$ls_status,$li_nivel);
				   $li_disoctubre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","octubre",$ls_status,$li_nivel);
				   $li_disnoviembre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","noviembre",$ls_status,$li_nivel);
				   $li_disdiciembre=$this->uf_obtener_modificacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,"DI","diciembre",$ls_status,$li_nivel);

				   $ld_enero=number_format($ld_enero + ($li_aumenero-$li_disenero),2,'.',"");
				   $ld_febrero=number_format($ld_febrero + ($li_aumfebrero-$li_disfebrero),2,'.',"");
				   $ld_marzo=number_format($ld_marzo + ($li_aummarzo-$li_dismarzo),2,'.',"");
				   $ld_abril=number_format($ld_abril + ($li_aumabril-$li_disabril),2,'.',"");
				   $ld_mayo=number_format($ld_mayo + ($li_aummayo-$li_dismayo),2,'.',"");
				   $ld_junio=number_format($ld_junio + ($li_aumjunio-$li_disjunio),2,'.',"");
				   $ld_julio=number_format($ld_julio + ($li_aumjulio-$li_disjulio),2,'.',"");
				   $ld_agosto=number_format($ld_agosto + ($li_aumagosto-$li_disagosto),2,'.',"");
				   $ld_septiembre=number_format($ld_septiembre + ($li_aumseptiembre-$li_disseptiembre),2,'.',"");
				   $ld_octubre=number_format($ld_octubre + ($li_aumoctubre-$li_disoctubre),2,'.',"");
				   $ld_noviembre=number_format($ld_noviembre + ($li_aumnoviembre-$li_disnoviembre),2,'.',"");
				   $ld_diciembre=number_format($ld_diciembre + ($li_aumdiciembre-$li_disdiciembre),2,'.',"");
			   }

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
			   $this->dts_reporte->insertRow("status",$ls_status);
			   $lb_valido = true;
			  }//while
			$this->io_sql->free_result($rs_data);
		}//else
     return  $lb_valido;
   }//fin uf_spg_reportes_comparados_distribucion_mensual_presupuesto
/********************************************************************************************************************************/

/********************************************************************************************************************************/
	function uf_obtener_modificacion($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
									 $as_spg_cuenta,$as_operacion,$as_mes,$as_status,$ai_nivel)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function :	uf_obtener_aumento
		//      Argumentos :    $as_codestpro1 // Estructura nivel 1
		//						$as_codestpro2 // Estructura nivel 2
		//						$as_codestpro3 // Estructura nivel 3
		//						$as_codestpro4 // Estructura nivel 4
		//						$as_codestpro5 // Estructura nivel 5
		//						$as_estcla	   // Estatus de clasificacion
		//						$as_spg_cuenta // Cuenta Presupuestaria
		//						$as_mes		   // Mes por el cual se desea filtrar
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
		//	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
		//     Creado por :     Ing. Luis Anibal Lang
		// Fecha Creación :     19/05/2009
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_status=="C")
		{
			$ls_where="   AND spg_dtmp_mensual.spg_cuenta='".$as_spg_cuenta."' ";
		}
		else
		{
			$li_anterior=$this->ia_niveles_spg[$ai_nivel];
		    $as_spg_cuenta=substr($as_spg_cuenta,0,$li_anterior+1);  // ojo pilas al hacer  las prueba
			$ls_where="   AND spg_dtmp_mensual.spg_cuenta like '".$as_spg_cuenta."%' ";
		}
		$ls_sql="SELECT SUM(".$as_mes.") AS total".
				"  FROM spg_dtmp_mensual,sigesp_cmp_md".
				" WHERE spg_dtmp_mensual.codemp='".$this->ls_codemp."' ".
				"   AND spg_dtmp_mensual.codestpro1='".$as_codestpro1."' ".
				"   AND spg_dtmp_mensual.codestpro2='".$as_codestpro2."' ".
				"   AND spg_dtmp_mensual.codestpro3='".$as_codestpro3."' ".
				"   AND spg_dtmp_mensual.codestpro4='".$as_codestpro4."' ".
				"   AND spg_dtmp_mensual.codestpro5='".$as_codestpro5."' ".
				"   AND spg_dtmp_mensual.estcla='".$as_estcla."' ".
				$ls_where.
				"   AND spg_dtmp_mensual.operacion='".$as_operacion."' ".
				"   AND sigesp_cmp_md.estapro='1'".
				"   AND spg_dtmp_mensual.codemp=sigesp_cmp_md.codemp".
				"   AND spg_dtmp_mensual.comprobante=sigesp_cmp_md.comprobante".
				"   AND spg_dtmp_mensual.procede=sigesp_cmp_md.procede".
				"   AND spg_dtmp_mensual.fecha=sigesp_cmp_md.fecha";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			   $this->io_msg->message("CLASE->sigesp_spg_reporte_class
									   MÉTODO->uf_obtener_aumento
									   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				return false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$li_monto=$rs_data->fields["total"];
			}
		}
		return $li_monto;
	}
/********************************************************************************************************************************/

/********************************************************************************************************************************/
    function uf_obtener_rango_programatica($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
                                           $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
                                           $as_codestpro4_des,$as_codestpro5_des,&$as_Sql_Where,&$as_str_estructura_from,
                                           &$as_str_estructura_to,$as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_obtener_rango_programatica -> proviene de uf_spg_reporte_acumulado_cuentas
	 //       Access :	private
	 //   Argumentos :  as_codestpro1_ori ... as_estprepro5_ori,as_codestpro1_des ... as_estprepro5_des
	 //                 as_estclades   // estatus desde de clasificaicones de la estructura presupuestaria IPSFA
	 //                 as_estclahas   // estatus hasta de clasificaicones de la estructura presupuestaria IPSFA
     //	    Returns :	Retorna estructuras ordenadas para la consulta sql
	 //	Description :	Método que determina y ordena el minimo por niveles de la estructuras presupuestarias
     //                 para luego concatenar en una variables de origen y una de destino
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $ls_gestor = $_SESSION["ls_gestor"];
		 if(strtoupper($ls_gestor)=="MYSQLT")
		 {
		   $ls_concat="(CONCAT";
		   $ls_cadena=",";
		   $ls_parentesis=")";
		 }
		 else
		 {
		   $ls_concat="";
		   $ls_cadena="||";
		   $ls_parentesis="";
		 }
		 $ls_CodEstPro1_desde = $as_codestpro1_ori;
		 $ls_CodEstPro1_hasta = $as_codestpro1_des;
		 $ls_CodEstPro2_desde = $as_codestpro2_ori;
		 $ls_CodEstPro2_hasta = $as_codestpro2_des;
		 $ls_CodEstPro3_desde = $as_codestpro3_ori;
		 $ls_CodEstPro3_hasta = $as_codestpro3_des;
		 $ls_CodEstPro4_desde = $as_codestpro4_ori;
		 $ls_CodEstPro4_hasta = $as_codestpro4_des;
		 $ls_CodEstPro5_desde = $as_codestpro5_ori;
		 $ls_CodEstPro5_hasta = $as_codestpro5_des;

         // Nivel 1
		 if (($ls_CodEstPro1_desde!="0000000000000000000000000") and ($ls_CodEstPro1_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w1  = " ".$ls_concat."(PCT.codestpro1 ";
			$ls_str_w1f = $ls_CodEstPro1_desde;
			$ls_str_w1t = $ls_CodEstPro1_hasta;
		 }
		 else
		 {
			$ls_str_w1  = "";
			$ls_str_w1f = "";
			$ls_str_w1t = "";
		 }
         // Nivel 2
		 if (($ls_CodEstPro2_desde!="0000000000000000000000000") and ($ls_CodEstPro2_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w2  = "".$ls_cadena."PCT.codestpro2";
			$ls_str_w2f = $ls_CodEstPro2_desde;
			$ls_str_w2t = $ls_CodEstPro2_hasta;
		 }
		 else
		 {
			$ls_str_w2  = "";
			$ls_str_w2f = "";
			$ls_str_w2t = "";
		 }
         // Nivel 3
		 if (($ls_CodEstPro3_desde!="0000000000000000000000000") and ($ls_CodEstPro3_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w3  = "".$ls_cadena."PCT.codestpro3";
			$ls_str_w3f = $ls_CodEstPro3_desde;
			$ls_str_w3t = $ls_CodEstPro3_hasta;
		 }
		 else
		 {
			$ls_str_w3  = "";
			$ls_str_w3f = "";
			$ls_str_w3t = "";
		 }
         // Nivel 4
		 if (($ls_CodEstPro4_desde!="0000000000000000000000000") and ($ls_CodEstPro4_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w4  = "".$ls_cadena."PCT.codestpro4";
			$ls_str_w4f = $ls_CodEstPro4_desde;
			$ls_str_w4t = $ls_CodEstPro4_hasta;
		 }
		 else
		 {
			$ls_str_w4  = "";
			$ls_str_w4f = "";
			$ls_str_w4t = "";
		 }
         // Nivel 5
		 if (($ls_CodEstPro5_desde!="0000000000000000000000000") and ($ls_CodEstPro5_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w5  = "".$ls_cadena."PCT.codestpro5";
			$ls_str_w5f = $ls_CodEstPro5_desde;
			$ls_str_w5t = $ls_CodEstPro5_hasta;
		 }
		 else
		 {
			$ls_str_w5  = "";
			$ls_str_w5f = "";
			$ls_str_w5t = "";
		 }
		 //estatus de clasificacion
		 if (($as_estclades!='') and ($as_estclahas!=''))
		 {
			$ls_str_estcla  = "".$ls_cadena."PCT.estcla".$ls_parentesis;
			$ls_str_estclaf = $as_estclades;
			$ls_str_estclat = $as_estclahas;
		 }
		 else
		 {
			$ls_str_estcla  = "";
			$ls_str_estclaf = "";
			$ls_str_estclat = "";
		 }

         if (!(empty($ls_str_w1) and empty($ls_str_w2) and empty($ls_str_w3) and empty($ls_str_w4) and empty($ls_str_w5) and empty($ls_str_estcla)))
         {
			 $ls_str_estructura = $ls_str_w1.$ls_str_w2.$ls_str_w3.$ls_str_w4.$ls_str_w5.$ls_str_estcla;
             $li_lent= strlen($ls_str_estructura);
             $ls_str_estructura = substr( $ls_str_estructura ,0,$li_lent);
             $as_str_estructura_from = $ls_str_w1f.$ls_str_w2f.$ls_str_w3f.$ls_str_w4f.$ls_str_w5f.$ls_str_estclaf;
             $as_str_estructura_to = $ls_str_w1t.$ls_str_w2t.$ls_str_w3t.$ls_str_w4t.$ls_str_w5t.$ls_str_estclat;
             $as_Sql_Where=$ls_str_estructura." between '".$as_str_estructura_from."' AND '".$as_str_estructura_to."') ";
         }
         else
		 {
             $as_Sql_Where="";
             $as_str_estructura_to="";
             $as_str_estructura_from="";
		 }// print "<br>".$as_Sql_Where."<br>";
    } // fin function uf_obtener_rango_programatica
/********************************************************************************************************************************/

	///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  " RESUMEN DE EJECUCION FINANCIERA DE PRESUPUESTO DE GASTO" //
	/////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_resumen_ejecucion_financiera_presupuesto($as_codestpro1,$as_codestpro2,$as_codestpro3,
	                                                                   $as_codestpro4,$as_codestpro5,$as_fecha,
																	   $as_estcla, $as_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_resumen_ejecucion_financiera_presupuesto
	 //     Argumentos :    as_codestpro1 ... $as_codestpro5 //rango nivel estructura presupuestaria
	 //                     as_estcla // Estatus de la clasificacion
	 //                     as_fecha // Fecha tope para el reporte
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán
	 //                     Ing. Arnaldo Suárez
	 // Fecha Creación :    14/02/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('spg_cuentas',$ls_seguridad);
	  if ($as_cuenta <> "")
	  {
	   $ls_cuenta = " and spg_cuentas.spg_cuenta like '".$as_cuenta."%' ";
	  }
	  else
	  {
	   $ls_cuenta = " ";
	  }

	  if ($as_fecha=="")
	  {
	    $as_fecha = date("d/m/Y");
	  }

	  $ls_estructura="";
	  if($this->li_estmodest==1)
	  {
	   $ls_tabla="spg_ep3";
	  }
	  elseif($this->li_estmodest==2)
	  {
	   $ls_tabla="spg_ep5";
	  }

	  if($this->li_estmodest==2)
	  {

			if(($as_codestpro1!="**")&&(!empty($as_codestpro1))&&($as_codestpro5 !="0000000000000000000000000"))
			{
				 $as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,25);
				 $ls_estructura=" AND spg_cuentas.codestpro1 = '".$as_codestpro1."' ";
				 $ls_estructura_select="spg_cuentas.codestpro1,";
		    }
			else
			{
				 $ls_estructura = "";
				 $ls_estructura_select="";
			}
			if(($as_codestpro2!="**")&&(!empty($as_codestpro2))&&($as_codestpro5 !="0000000000000000000000000"))
			{
				$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,25);
				$ls_estructura=$ls_estructura." AND spg_cuentas.codestpro2 = '".$as_codestpro2."' ";
				$ls_estructura_select=$ls_estructura_select."spg_cuentas.codestpro2,";
	        }
			else
			{
				$ls_estructura=$ls_estructura;
			}
			if(($as_codestpro3!="**")&&(!empty($as_codestpro3))&&($as_codestpro5 !="0000000000000000000000000"))
			{
				$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,25);
				$ls_estructura=$ls_estructura." AND spg_cuentas.codestpro3 = '".$as_codestpro3."' ";
				$ls_estructura_select=$ls_estructura_select."spg_cuentas.codestpro3,";
	        }
			else
			{
			    $ls_estructura=$ls_estructura;
			}

			if(($as_codestpro4!="**")&&(!empty($as_codestpro4))&&($as_codestpro5 !="0000000000000000000000000"))
			{
			    $as_codestpro4=$this->io_function->uf_cerosizquierda($as_codestpro4,25);
			    $ls_estructura=$ls_estructura." AND spg_cuentas.codestpro4 = '".$as_codestpro4."' ";
				$ls_estructura_select=$ls_estructura_select."spg_cuentas.codestpro4,";
			}
			else
			{
				$ls_estructura=$ls_estructura;
			}

			if(($as_codestpro5!="**")&&(!empty($as_codestpro5))&&($as_codestpro5 !="0000000000000000000000000"))
			{
			  $as_codestpro5=$this->io_function->uf_cerosizquierda($as_codestpro5,25);
			  $ls_estructura=$ls_estructura." AND spg_cuentas.codestpro5 = '".$as_codestpro5."' ";
			  $ls_estructura_select=$ls_estructura_select."spg_cuentas.codestpro5,";
			}
			else
			{
				$ls_estructura=$ls_estructura;
			}

		}
	  else
	  {
			if(($as_codestpro1!="**")&&(!empty($as_codestpro1))&&($as_codestpro1 !="0000000000000000000000000"))
			{
				$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,25);
				$ls_estructura=" AND spg_cuentas.codestpro1 = '".$as_codestpro1."' ";
				$ls_estructura_select="spg_cuentas.codestpro1,";
		    }
			else
			{
			    $ls_estructura = "";
				$ls_estructura_select="";
			}
			if(($as_codestpro2!="**")&&(!empty($as_codestpro2))&&($as_codestpro2 !="0000000000000000000000000"))
			{
				$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,25);
				$ls_estructura=$ls_estructura." AND spg_cuentas.codestpro2 = '".$as_codestpro2."' ";
				$ls_estructura_select=$ls_estructura_select."spg_cuentas.codestpro2,";
			}
			else
			{
				$ls_estructura=$ls_estructura;
			}
			if(($as_codestpro3!="**")&&(!empty($as_codestpro3))&&($as_codestpro3 !="0000000000000000000000000"))
			{
				$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,25);
				$ls_estructura=$ls_estructura." AND spg_cuentas.codestpro3 = '".$as_codestpro3."' ";
				$ls_estructura_select=$ls_estructura_select."spg_cuentas.codestpro3,";
			}
			else
			{
			    $ls_estructura=$ls_estructura;
			}
	  }

	  if(!empty($ls_estructura))
	  {
	   $ls_estructura = $ls_estructura." AND spg_cuentas.estcla = '".$as_estcla."' ";
	  }

	  $as_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);

	  $ls_sql = "  Select distinct spg_cuentas.spg_cuenta,spg_cuentas.denominacion,".
	            "         spg_cuentas.asignado as asignado,".$ls_estructura_select." ".
				"	    (spg_cuentas.enero+spg_cuentas.febrero+spg_cuentas.marzo) as trimestre_i, ".
                "        (spg_cuentas.abril+spg_cuentas.mayo+spg_cuentas.junio) as trimestre_ii,  ".
                "        (spg_cuentas.julio+spg_cuentas.agosto+spg_cuentas.septiembre) as trimestre_iii, ".
                "        (spg_cuentas.octubre+spg_cuentas.noviembre+spg_cuentas.diciembre) as trimestre_iv, spg_cuentas.estcla, ".
			    "			 spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,spg_cuentas.codestpro4,spg_cuentas.codestpro5  ".
                " from  spg_cuentas ".
                " where spg_cuentas.codemp='".$this->ls_codemp."' ".
				$ls_seguridad." ".
				" ".$ls_estructura.
				" ".$ls_cuenta." ".
				" order by spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3, ".
				"         spg_cuentas.codestpro4,spg_cuentas.codestpro5 ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class
		                           MÉTODO->uf_spg_reportes_resumen_ejecucion_financiera_presupuesto
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	}
    else
    {
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ls_denominacion=$row["denominacion"];
		   $ld_asignado=$row["asignado"];
		   $ld_trimestre_i=$row["trimestre_i"];
		   $ld_trimestre_ii=$row["trimestre_ii"];
		   $ld_trimestre_iii=$row["trimestre_iii"];
		   $ld_trimestre_iv=$row["trimestre_iv"];
		   $ld_causado= 0;
		   $ld_comprometido=0;
		   $ld_precomprometido=0;
		   $ld_ajustes=0;
		   $ld_pagado=0;
		   // Enero
		   $ld_aumdis_ene=0;
		   $ld_precompromiso_ene=0;
		   $ld_compromiso_ene=0;
		   $ld_causado_ene=0;
		   $ld_pagado_ene=0;
		   $ld_libprecompromiso_ene=0;
		   $ld_libcompromiso_ene=0;

		   //Febrero
		   $ld_aumdis_feb=0;
		   $ld_precompromiso_feb=0;
		   $ld_compromiso_feb=0;
		   $ld_causado_feb=0;
		   $ld_pagado_feb=0;
		   $ld_libprecompromiso_feb=0;
		   $ld_libcompromiso_feb=0;

		   //Marzo
		   $ld_aumdis_mar=0;
		   $ld_precompromiso_mar=0;
		   $ld_compromiso_mar=0;
		   $ld_causado_mar=0;
		   $ld_pagado_mar=0;
		   $ld_libprecompromiso_mar=0;
		   $ld_libcompromiso_mar=0;

		   // Abril
		   $ld_aumdis_abr=0;
		   $ld_precompromiso_abr=0;
		   $ld_compromiso_abr=0;
		   $ld_causado_abr=0;
		   $ld_pagado_abr=0;
		   $ld_libprecompromiso_abr=0;
		   $ld_libcompromiso_abr=0;

		   // Mayo
		   $ld_aumdis_may=0;
		   $ld_precompromiso_may=0;
		   $ld_compromiso_may=0;
		   $ld_causado_may=0;
		   $ld_pagado_may=0;
		   $ld_libprecompromiso_may=0;
		   $ld_libcompromiso_may=0;

		   // Junio
		   $ld_aumdis_jun=0;
		   $ld_precompromiso_jun=0;
		   $ld_compromiso_jun=0;
		   $ld_causado_jun=0;
		   $ld_pagado_jun=0;
		   $ld_libprecompromiso_jun=0;
		   $ld_libcompromiso_jun=0;

		   // Julio
		   $ld_aumdis_jul=0;
		   $ld_precompromiso_jul=0;
		   $ld_compromiso_jul=0;
		   $ld_causado_jul=0;
		   $ld_pagado_jul=0;
		   $ld_libprecompromiso_jul=0;
		   $ld_libcompromiso_jul=0;

		   // Agosto
		   $ld_aumdis_ago=0;
		   $ld_precompromiso_ago=0;
		   $ld_compromiso_ago=0;
		   $ld_causado_ago=0;
		   $ld_pagado_ago=0;
		   $ld_libprecompromiso_ago=0;
		   $ld_libcompromiso_ago=0;

		   // Septiembre
		   $ld_aumdis_sep=0;
		   $ld_precompromiso_sep=0;
		   $ld_compromiso_sep=0;
		   $ld_causado_sep=0;
		   $ld_pagado_sep=0;
		   $ld_libprecompromiso_sep=0;
		   $ld_libcompromiso_sep=0;

		   // Octubre
		   $ld_aumdis_oct=0;
		   $ld_precompromiso_oct=0;
		   $ld_compromiso_oct=0;
		   $ld_causado_oct=0;
		   $ld_pagado_oct=0;
		   $ld_libprecompromiso_oct=0;
		   $ld_libcompromiso_oct=0;

		   // Noviembre
		   $ld_aumdis_nov=0;
		   $ld_precompromiso_nov=0;
		   $ld_compromiso_nov=0;
		   $ld_causado_nov=0;
		   $ld_pagado_nov=0;
		   $ld_libprecompromiso_nov=0;
		   $ld_libcompromiso_nov=0;

		   // Diciembre
		   $ld_aumdis_dic=0;
		   $ld_precompromiso_dic=0;
		   $ld_compromiso_dic=0;
		   $ld_causado_dic=0;
		   $ld_pagado_dic=0;
		   $ld_libprecompromiso_dic=0;
		   $ld_libcompromiso_dic=0;

		   $ls_codestpro1=$row["codestpro1"];
		   $ls_codestpro2=$row["codestpro2"];
		   $ls_codestpro3=$row["codestpro3"];
		   $ls_codestpro4=$row["codestpro4"];
		   $ls_codestpro5=$row["codestpro5"];
		   $ls_estcla=$row["estcla"];
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		   $lb_valido = $this->uf_detalle_ejecucion_financiera($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla, $ls_spg_cuenta,
									   $as_fecha,$ld_ajustes,$ld_precomprometido,$ld_comprometido,$ld_causado,$ld_pagado,
									   $ld_aumdis_ene,$ld_precompromiso_ene,$ld_compromiso_ene,$ld_causado_ene,$ld_pagado_ene,$ld_libprecompromiso_ene,$ld_libcompromiso_ene,
									   $ld_aumdis_feb,$ld_precompromiso_feb,$ld_compromiso_feb,$ld_causado_feb,$ld_pagado_feb,$ld_libprecompromiso_feb,$ld_libcompromiso_feb,
									   $ld_aumdis_mar,$ld_precompromiso_mar,$ld_compromiso_mar,$ld_causado_mar,$ld_pagado_mar,$ld_libprecompromiso_mar,$ld_libcompromiso_mar,
									   $ld_aumdis_abr,$ld_precompromiso_abr,$ld_compromiso_abr,$ld_causado_abr,$ld_pagado_abr,$ld_libprecompromiso_abr,$ld_libcompromiso_abr,
									   $ld_aumdis_may,$ld_precompromiso_may,$ld_compromiso_may,$ld_causado_may,$ld_pagado_may,$ld_libprecompromiso_may,$ld_libcompromiso_may,
									   $ld_aumdis_jun,$ld_precompromiso_jun,$ld_compromiso_jun,$ld_causado_jun,$ld_pagado_jun,$ld_libprecompromiso_jun,$ld_libcompromiso_jun,
									   $ld_aumdis_jul,$ld_precompromiso_jul,$ld_compromiso_jul,$ld_causado_jul,$ld_pagado_jul,$ld_libprecompromiso_jul,$ld_libcompromiso_jul,
									   $ld_aumdis_ago,$ld_precompromiso_ago,$ld_compromiso_ago,$ld_causado_ago,$ld_pagado_ago,$ld_libprecompromiso_ago,$ld_libcompromiso_ago,
									   $ld_aumdis_sep,$ld_precompromiso_sep,$ld_compromiso_sep,$ld_causado_sep,$ld_pagado_sep,$ld_libprecompromiso_sep,$ld_libcompromiso_sep,
									   $ld_aumdis_oct,$ld_precompromiso_oct,$ld_compromiso_oct,$ld_causado_oct,$ld_pagado_oct,$ld_libprecompromiso_oct,$ld_libcompromiso_oct,
									   $ld_aumdis_nov,$ld_precompromiso_nov,$ld_compromiso_nov,$ld_causado_nov,$ld_pagado_nov,$ld_libprecompromiso_nov,$ld_libcompromiso_nov,
									   $ld_aumdis_dic,$ld_precompromiso_dic,$ld_compromiso_dic,$ld_causado_dic,$ld_pagado_dic,$ld_libprecompromiso_dic,$ld_libcompromiso_dic);
		   if ($lb_valido)
		   {

			   $ld_ajustes = $ld_aumdis_ene + $ld_aumdis_feb + $ld_aumdis_mar + $ld_aumdis_abr
			                 + $ld_aumdis_may + $ld_aumdis_jun + $ld_aumdis_jul + $ld_aumdis_ago
							 + $ld_aumdis_sep + $ld_aumdis_oct + $ld_aumdis_nov + $ld_aumdis_dic;
			   $this->dts_reporte->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte->insertRow("estcla",$ls_estcla);
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte->insertRow("trimestre_i",$ld_compromiso_ene+$ld_compromiso_feb+$ld_compromiso_mar);
			   $this->dts_reporte->insertRow("trimestre_ii",$ld_compromiso_abr+$ld_compromiso_may+$ld_compromiso_jun);
			   $this->dts_reporte->insertRow("trimestre_iii",$ld_compromiso_jul+$ld_compromiso_ago+$ld_compromiso_sep);
			   $this->dts_reporte->insertRow("trimestre_iv",$ld_compromiso_oct+$ld_compromiso_nov+$ld_compromiso_dic);
			   $this->dts_reporte->insertRow("causado",$ld_causado);
			   $this->dts_reporte->insertRow("comprometido",$ld_comprometido);
			   $this->dts_reporte->insertRow("precomprometido",$ld_precompromiso_ene + $ld_precompromiso_feb + $ld_precompromiso_mar +
			                                                   $ld_precompromiso_abr + $ld_precompromiso_may + $ld_precompromiso_jun +
															   $ld_precompromiso_jul + $ld_precompromiso_ago + $ld_precompromiso_sep +
															   $ld_precompromiso_oct + $ld_precompromiso_nov + $ld_precompromiso_dic);
			   $this->dts_reporte->insertRow("libprecomprometido",$ld_libprecompromiso_ene + $ld_libprecompromiso_feb + $ld_libprecompromiso_mar +
			                                                   $ld_libprecompromiso_abr + $ld_libprecompromiso_may + $ld_libprecompromiso_jun +
															   $ld_libprecompromiso_jul + $ld_libprecompromiso_ago + $ld_libprecompromiso_sep +
															   $ld_libprecompromiso_oct + $ld_libprecompromiso_nov + $ld_libprecompromiso_dic);

			   $this->dts_reporte->insertRow("libcomprometido",$ld_libcompromiso_ene + $ld_libcompromiso_feb + $ld_libcompromiso_mar +
			                                                   $ld_libcompromiso_abr + $ld_libcompromiso_may + $ld_libcompromiso_jun +
															   $ld_libcompromiso_jul + $ld_libcompromiso_ago + $ld_libcompromiso_sep +
															   $ld_libcompromiso_oct + $ld_libcompromiso_nov + $ld_libcompromiso_dic);
			   $this->dts_reporte->insertRow("ajustes",$ld_ajustes);
		   }
		   $lb_valido = true;
		  }//while
	    $this->io_sql->free_result($rs_data);
    }//else
     return  $lb_valido;
   }//fin uf_spg_reportes_resumen_ejecucion_presupuesto
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('spg_dt_unidadadministrativa',$ls_seguridad);
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
				$ls_aux= " AND spg_dt_unidadadministrativa.codestpro1='".$as_codestpro1_ori."' ";
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
				$ls_aux= $ls_aux." AND spg_dt_unidadadministrativa.codestpro2='".$as_codestpro2_ori."' ";
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
				$ls_aux= $ls_aux." AND spg_dt_unidadadministrativa.codestpro3='".$as_codestpro3_ori."' ";
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
				$ls_aux= $ls_aux." AND spg_dt_unidadadministrativa.codestpro4='".$as_codestpro4_ori."' ";
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
				$ls_aux= $ls_aux." AND spg_dt_unidadadministrativa.codestpro5='".$as_codestpro5_ori."' ";
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
				$as_codestpro4_ori=$this->io_function->uf_cerosizquierda($as_codestpro4_ori,25);
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
				$as_codestpro5_ori=$this->io_function->uf_cerosizquierda($as_codestpro5_ori,25);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_ori;
				$as_codestpro5_des=$as_codestpro5_ori;
			}
			else
			{
				$this->uf_spg_reporte_select_min_codestpro5($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori);
				$ls_estructura_desde=$ls_estructura_desde.$as_codestpro5_ori;
				$this->uf_spg_reporte_select_max_codestpro5($as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des);
				$ls_estructura_hasta=$ls_estructura_hasta.$as_codestpro5_des;
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
		if(strtoupper($ls_gestor)=="MYSQLT")
			{
			   $ls_concat=" AND CONCAT(spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2,spg_dt_unidadadministrativa.codestpro3,spg_dt_unidadadministrativa.codestpro4,spg_dt_unidadadministrativa.codestpro5,spg_dt_unidadadministrativa.estcla)";
			}
			else
			{
			   $ls_concat=" AND (spg_dt_unidadadministrativa.codestpro1||spg_dt_unidadadministrativa.codestpro2||spg_dt_unidadadministrativa.codestpro3||spg_dt_unidadadministrativa.codestpro4||spg_dt_unidadadministrativa.codestpro5||spg_dt_unidadadministrativa.estcla)";
			}
	  if($ai_estemireq==1)
	  {
	    $ls_filtro=" AND spg_unidadadministrativa.estemireq=1 ";
	  }
	  else
	  {
	    $ls_filtro="";
	  }
	  if ($ls_estructura_desde > $ls_estructura_hasta)
	  {
	   $ls_estructutura_aux = $ls_estructura_desde;
	   $ls_estructura_desde = $ls_estructura_hasta;
	   $ls_estructura_hasta = $ls_estructutura_aux;
	  }
	  if($as_ckq_unidad==0)
	  {
	     /*$ls_sql=" SELECT spg_unidadadministrativa.coduniadm, spg_unidadadministrativa.denuniadm,spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2, ".
		         " spg_dt_unidadadministrativa.codestpro3, spg_dt_unidadadministrativa.codestpro4,spg_dt_unidadadministrativa.codestpro5, ".
				 " spg_dt_unidadadministrativa.estcla ".
                 " FROM   spg_unidadadministrativa, spg_dt_unidadadministrativa  ".
                 " WHERE  spg_unidadadministrativa.codemp='".$this->ls_codemp."'  ".$ls_concat."  BETWEEN '".$ls_estructura_desde.$as_estclades."' AND '".$ls_estructura_hasta.$as_estclahas."'  AND  spg_unidadadministrativa.coduniadm  BETWEEN '".$as_coduniadm_des."' AND  ".
				 "        '".$as_coduniadm_has."' AND spg_unidadadministrativa.codemp =  spg_dt_unidadadministrativa.codemp AND spg_unidadadministrativa.coduniadm =  spg_dt_unidadadministrativa.coduniadm ".$ls_filtro."  ".$ls_aux.
                 " ORDER BY spg_unidadadministrativa.coduniadm ";*/

			$ls_sql = "SELECT spg_unidadadministrativa.coduniadm, spg_unidadadministrativa.denuniadm, ".
					  "	  	  spg_dt_unidadadministrativa.codestpro1,	spg_ep1.denestpro1, ".
					  "		  spg_dt_unidadadministrativa.codestpro2,	spg_ep2.denestpro2, ".
					  "		  spg_dt_unidadadministrativa.codestpro3,   spg_ep3.denestpro3, ".
					  "		  spg_dt_unidadadministrativa.codestpro4,   spg_ep4.denestpro4, ".
					  "		  spg_dt_unidadadministrativa.codestpro5,   spg_ep5.denestpro5, ".
					  "		  spg_dt_unidadadministrativa.estcla FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5 ".
					  " WHERE  spg_unidadadministrativa.codemp='".$this->ls_codemp."'  ".$ls_concat."  BETWEEN '".$ls_estructura_desde.$as_estclades."' AND '".$ls_estructura_hasta.$as_estclahas."'  AND  spg_unidadadministrativa.coduniadm  BETWEEN '".$as_coduniadm_des."' AND  ".
				      "        '".$as_coduniadm_has."' AND spg_unidadadministrativa.codemp =  spg_dt_unidadadministrativa.codemp AND spg_unidadadministrativa.coduniadm =  spg_dt_unidadadministrativa.coduniadm ".$ls_filtro."  ".$ls_aux." ".
					  " AND spg_unidadadministrativa.codemp = spg_dt_unidadadministrativa.codemp ".
					  " AND spg_unidadadministrativa.coduniadm = spg_dt_unidadadministrativa.coduniadm ".
					  "	AND spg_ep1.codemp = spg_dt_unidadadministrativa.codemp ".
					  "	AND spg_ep1.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
					  "	AND spg_ep1.estcla = spg_dt_unidadadministrativa.estcla ".
					  "	AND spg_ep2.codemp = spg_dt_unidadadministrativa.codemp ".
					  "	AND spg_ep2.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
					  "	AND spg_ep2.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
					  "	AND spg_ep2.estcla = spg_dt_unidadadministrativa.estcla ".
					  "	AND spg_ep3.codemp = spg_dt_unidadadministrativa.codemp ".
					  "	AND spg_ep3.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
					  "	AND spg_ep3.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
					  "	AND spg_ep3.codestpro3 = spg_dt_unidadadministrativa.codestpro3 ".
					  "	AND spg_ep3.estcla = spg_dt_unidadadministrativa.estcla ".
					  "	AND spg_ep4.codemp = spg_dt_unidadadministrativa.codemp ".
					  "	AND spg_ep4.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
					  "	AND spg_ep4.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
					  "	AND spg_ep4.codestpro3 = spg_dt_unidadadministrativa.codestpro3 ".
					  "	AND spg_ep4.codestpro4 = spg_dt_unidadadministrativa.codestpro4 ".
					  "	AND spg_ep4.estcla = spg_dt_unidadadministrativa.estcla ".
					  "	AND spg_ep5.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
					  "	AND spg_ep5.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
					  "	AND spg_ep5.codestpro3 = spg_dt_unidadadministrativa.codestpro3 ".
					  "	AND spg_ep5.codestpro4 = spg_dt_unidadadministrativa.codestpro4 ".
					  "	AND spg_ep5.codestpro5 = spg_dt_unidadadministrativa.codestpro5 ".
					  "	AND spg_ep5.estcla = spg_dt_unidadadministrativa.estcla ".
					  "	ORDER BY spg_unidadadministrativa.coduniadm";
	  }
	  else
	  {
		$ls_sql= "SELECT spg_unidadadministrativa.coduniadm, spg_unidadadministrativa.denuniadm, ".
				 "	  	  spg_dt_unidadadministrativa.codestpro1,	spg_ep1.denestpro1, ".
				 "		  spg_dt_unidadadministrativa.codestpro2,	spg_ep2.denestpro2, ".
				 "		  spg_dt_unidadadministrativa.codestpro3,   spg_ep3.denestpro3, ".
				 "		  spg_dt_unidadadministrativa.codestpro4,   spg_ep4.denestpro4, ".
				 "		  spg_dt_unidadadministrativa.codestpro5,   spg_ep5.denestpro5, ".
				 "		  spg_dt_unidadadministrativa.estcla FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5 ".
				 " WHERE  spg_unidadadministrativa.codemp='".$this->ls_codemp."' AND  spg_dt_unidadadministrativa.coduniadm BETWEEN '".$as_coduniadm_des."' AND  ".
				 "        '".$as_coduniadm_has."' ".
				 "  AND spg_unidadadministrativa.codemp = spg_dt_unidadadministrativa.codemp ".
				 "  AND spg_unidadadministrativa.coduniadm = spg_dt_unidadadministrativa.coduniadm ".
				 "  AND spg_ep1.codemp = spg_dt_unidadadministrativa.codemp ".
				 "  AND spg_ep1.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
				 "	AND spg_ep1.estcla = spg_dt_unidadadministrativa.estcla ".
				 "	AND spg_ep2.codemp = spg_dt_unidadadministrativa.codemp ".
				 "	AND spg_ep2.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
				 "	AND spg_ep2.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
				 "	AND spg_ep2.estcla = spg_dt_unidadadministrativa.estcla ".
				 "	AND spg_ep3.codemp = spg_dt_unidadadministrativa.codemp ".
				 "	AND spg_ep3.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
				 "	AND spg_ep3.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
				 "	AND spg_ep3.codestpro3 = spg_dt_unidadadministrativa.codestpro3 ".
				 "	AND spg_ep3.estcla = spg_dt_unidadadministrativa.estcla ".
				 "	AND spg_ep4.codemp = spg_dt_unidadadministrativa.codemp ".
				 "	AND spg_ep4.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
				 "	AND spg_ep4.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
				 "	AND spg_ep4.codestpro3 = spg_dt_unidadadministrativa.codestpro3 ".
				 "	AND spg_ep4.estcla = spg_dt_unidadadministrativa.estcla ".
				 "	AND spg_ep5.codestpro1 = spg_dt_unidadadministrativa.codestpro1 ".
				 "	AND spg_ep5.codestpro2 = spg_dt_unidadadministrativa.codestpro2 ".
				 "	AND spg_ep5.codestpro3 = spg_dt_unidadadministrativa.codestpro3 ".
				 "	AND spg_ep5.codestpro4 = spg_dt_unidadadministrativa.codestpro4 ".
				 "	AND spg_ep5.codestpro5 = spg_dt_unidadadministrativa.codestpro5 ".
				 "	AND spg_ep5.estcla = spg_dt_unidadadministrativa.estcla ".
				   $ls_filtro." ".$ls_aux.
				 " ORDER BY spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2,spg_dt_unidadadministrativa.codestpro3,spg_dt_unidadadministrativa.codestpro4,spg_dt_unidadadministrativa.codestpro5,spg_dt_unidadadministrativa.coduniadm ";

	  }
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
			   $ls_coduniadm  =$row["coduniadm"];
			   $ls_denuniadm  =$row["denuniadm"];
			   $ls_denestpro1 =$row["denestpro1"];
			   $ls_denestpro2 =$row["denestpro2"];
			   $ls_denestpro3 =$row["denestpro3"];
			   $this->dts_reporte->insertRow("coduniadm",$ls_coduniadm);
			   $this->dts_reporte->insertRow("denuniadm",$ls_denuniadm);
			   $this->dts_reporte->insertRow("codestpro1",substr($row["codestpro1"],-$ls_loncodestpro1));
			   $this->dts_reporte->insertRow("codestpro2",substr($row["codestpro2"],-$ls_loncodestpro2));
			   $this->dts_reporte->insertRow("codestpro3",substr($row["codestpro3"],-$ls_loncodestpro3));
			   $this->dts_reporte->insertRow("denestpro1",$ls_denestpro1);
			   $this->dts_reporte->insertRow("denestpro2",$ls_denestpro2);
			   $this->dts_reporte->insertRow("denestpro3",$ls_denestpro3);
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
    function uf_spg_reportes_ejecucion_compromiso($as_procede,$as_comprobante,$adt_fecha,$adt_fecdes,$adt_fechas,$as_spg_cuenta,$as_codban='---',
												  $as_ctaban='-------------------------',$as_programatica='')
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
	 $as_criterio='';
	 if ($as_programatica!='')
	 {
	 	 $as_criterio= " AND PMV.codestpro1 = '".substr($as_programatica,0,25)."'".
		 			   " AND PMV.codestpro2 = '".substr($as_programatica,25,25)."'".
		 			   " AND PMV.codestpro3 = '".substr($as_programatica,50,25)."'".
		 			   " AND PMV.codestpro4 = '".substr($as_programatica,75,25)."'".
		 			   " AND PMV.codestpro5 = '".substr($as_programatica,100,25)."'".
		 			   " AND PMV.estcla = '".substr($as_programatica,-1)."'";
	 }
	 $ls_sql=" SELECT PMV.*, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
             " FROM   spg_dt_cmp PMV, spg_operaciones POP ".
             " WHERE  PMV.operacion=POP.operacion AND PMV.procede='".$as_procede."' AND ".
             "        PMV.comprobante='".$as_comprobante."' AND  PMV.fecha='".$adt_fecha."' AND ".
			 "        PMV.spg_cuenta='".$as_spg_cuenta."' AND PMV.codban='".$as_codban."'  AND PMV.ctaban='".$as_ctaban."' ".$as_criterio.
			 " ORDER BY PMV.spg_cuenta ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class
		                           MÉTODO->uf_spg_reportes_ejecucion_compromiso
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
								   print $this->io_sql->message;
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
			   $ls_estcla=$row["estcla"];
			   $ls_codban=$row["codban"];
			   $ls_ctaban=$row["ctaban"];
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
			   $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,$ls_ced_bene,
			                                                        $ls_nompro,$ls_nombene,$ls_tipo_destino,$ls_codban,$ls_ctaban);
			   if($lb_valido)
			   {
				   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
				   $this->dts_reporte->insertRow("programatica",$ls_programatica);
				   $this->dts_reporte->insertRow("codestpro1",$ls_codestpro1);
				   $this->dts_reporte->insertRow("codestpro2",$ls_codestpro2);
				   $this->dts_reporte->insertRow("codestpro3",$ls_codestpro3);
				   $this->dts_reporte->insertRow("codestpro4",$ls_codestpro4);
				   $this->dts_reporte->insertRow("codestpro5",$ls_codestpro5);
				   $this->dts_reporte->insertRow("estcla",$ls_estcla);
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
				$ls_sql=" SELECT PMV.*, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
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
			$ls_sql=" SELECT PMV.*, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
					" FROM   sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP ".
					" WHERE  PCM.codemp=PMV.codemp AND  PMV.codemp='".$this->ls_codemp."' AND PCM.procede=PMV.procede AND  ".
					" 	     PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha AND PMV.operacion=POP.operacion  AND  ".
					" 	     PMV.procede_doc='".$ls_procede."'    AND  PMV.documento='".$ls_comprobante."'  AND  ".
					"	     PMV.codestpro1='".$ls_codestpro1."'  AND  PMV.codestpro2='".$ls_codestpro2."'  AND  ".
					"	     PMV.codestpro3='".$ls_codestpro3."'  AND  PMV.codestpro4='".$ls_codestpro4."'  AND  ".
					"	     PMV.codestpro5='".$ls_codestpro5."'  AND  PMV.spg_cuenta='".$ls_spg_cuenta."'  AND  ".
					"	     (PMV.procede<>'".$ls_procede."' OR  PMV.comprobante<>'".$ls_comprobante."')  AND ".
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
	                                            &$as_nombene,&$as_tipo_destino,$as_codban='---',$as_ctaban='-------------------------')
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
	          "        CM.procede='".$as_procede."'  AND CM.comprobante='".$as_comprobante."' AND  CM.fecha='".$adt_fecha."' AND ".
	          "        CM.codban='".$as_codban."'  AND CM.ctaban='".$as_ctaban."' ";
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
			   $ls_estcla=$row["estcla"];
			   $ls_codban=$row["codban"];
			   $ls_ctaban=$row["ctaban"];
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
			   $lb_valido=$this->uf_spg_reportes_select_comprobante($ls_comprobante,$ls_procede,$ldt_fecha,$ls_cod_pro,$ls_ced_bene,$ls_nompro,
			   														$ls_nombene,$ls_tipo_destino,$ls_codban,$ls_ctaban);

			   if($lb_valido)
			   {
				   //datastore de la cabezera
				   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
				   $this->dts_cab->insertRow("programatica",$ls_programatica);
				   $this->dts_cab->insertRow("spg_cuenta",$ls_spg_cuenta);
				   $this->dts_cab->insertRow("procede",$ls_procede);
				   $this->dts_cab->insertRow("comprobante",$ls_comprobante);
				   $this->dts_cab->insertRow("fecha",$ldt_fecha);
				   $this->dts_cab->insertRow("codban",$ls_codban);
				   $this->dts_cab->insertRow("ctaban",$ls_ctaban);
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PMV',$ls_seguridad);
	  $ls_sql=" SELECT  distinct PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total,      ".
	          "         PCM.tipo_destino,PCM.cod_pro, PCM.ced_bene, PMV.operacion                          ".
              "   FROM  sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP                                ".
              "  WHERE  POP.comprometer=1 AND POP.causar=0 AND POP.pagar=0                                 ".
			  "    AND  (PMV.codemp, PMV.procede, PMV.comprobante, PMV.fecha, PMV.codban, PMV.ctaban) = (PCM.codemp, PCM.procede, PCM.comprobante, PCM.fecha, PCM.codban, PCM.ctaban)  ".
			  "    AND  PMV.monto>=0 AND PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."'         ".
			  "	   AND	PCM.procede=PMV.procede AND PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha".
			  "    AND  PMV.operacion=POP.operacion                                                        ".$ls_seguridad.
              " ORDER BY  PCM.fecha, PCM.procede, PCM.comprobante ";//print $ls_sql;
			  
			  
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
						$li_j=0;
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
							  $ls_estcla=$this->dts_reporte->getValue("estcla",$li_i);
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
							  $ar_values["estcla"]=$ls_estcla;
							  $li_pos=$this->dts_reporte->findValues($ar_values,"spg_cuenta");
							  if($li_pos>0)
							  {
								 $li_j++;
								 $ld_total_comprometer=$ld_total_comprometer+$ld_comprometer;
								 $ld_total_causado=$ld_total_causado+$ld_causado;
								 $ld_total_pagado=$ld_total_pagado+$ld_pagado;
								 $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
								 $la_datosvalidos["programatica"][$li_j]=$ls_programatica;
								 $la_datosvalidos["codestpro1"][$li_j]=$ls_codestpro1;
								 $la_datosvalidos["codestpro2"][$li_j]=$ls_codestpro2;
								 $la_datosvalidos["codestpro3"][$li_j]=$ls_codestpro3;
								 $la_datosvalidos["codestpro4"][$li_j]=$ls_codestpro4;
								 $la_datosvalidos["codestpro5"][$li_j]=$ls_codestpro5;
								 $la_datosvalidos["estcla"][$li_j]=$ls_estcla;
								 $la_datosvalidos["spg_cuenta"][$li_j]=$ls_spg_cuenta;
								 $la_datosvalidos["procede"][$li_j]=$ls_procede;
								 $la_datosvalidos["comprobante"][$li_j]=$ls_comprobante;
								 $la_datosvalidos["fecha"][$li_j]=$ld_causado;
								 $la_datosvalidos["compromiso"][$li_j]=$ld_comprometer;
								 $la_datosvalidos["causado"][$li_j]=$ld_causado;
								 $la_datosvalidos["pagado"][$li_j]=$ld_pagado;
								 $la_datosvalidos["nompro"][$li_j]=$ls_nompro;
								 $la_datosvalidos["nombene"][$li_j]=$ls_nombene;
								 $la_datosvalidos["cod_pro"][$li_j]=$ls_cod_pro;
								 $la_datosvalidos["ced_bene"][$li_j]=$ls_ced_bene;
								 $la_datosvalidos["tipo_destino"][$li_j]=$ls_tipo_destino;

							  }//if
					  //print "1->".$ls_comprobante." comprometido->".$ld_total_comprometer." causado->".$ld_total_causado." pagado->".$ld_total_pagado."<br><br>";
						  }//for
						  if(($ld_total_comprometer<>0)&&($ld_total_causado==0)&&($ld_total_pagado==0))
						  {

							  for($j=1;$j<=$li_j;$j++)
							  {
								   $this->dts_reporte_final->insertRow("programatica",$la_datosvalidos["programatica"][$j]);
								   $this->dts_reporte_final->insertRow("codestpro1",$la_datosvalidos["codestpro1"][$j]);
								   $this->dts_reporte_final->insertRow("codestpro2",$la_datosvalidos["codestpro2"][$j]);
								   $this->dts_reporte_final->insertRow("codestpro3",$la_datosvalidos["codestpro3"][$j]);
								   $this->dts_reporte_final->insertRow("codestpro4",$la_datosvalidos["codestpro4"][$j]);
								   $this->dts_reporte_final->insertRow("codestpro5",$la_datosvalidos["codestpro5"][$j]);
								   $this->dts_reporte_final->insertRow("spg_cuenta",$la_datosvalidos["spg_cuenta"][$j]);
								   $this->dts_reporte_final->insertRow("procede",$la_datosvalidos["procede"][$j]);
								   $this->dts_reporte_final->insertRow("comprobante",$la_datosvalidos["comprobante"][$j]);
								   $this->dts_reporte_final->insertRow("fecha",$la_datosvalidos["fecha"][$j]);
								   $this->dts_reporte_final->insertRow("compromiso",$la_datosvalidos["compromiso"][$j]);
								   $this->dts_reporte_final->insertRow("causado",$la_datosvalidos["causado"][$j]);
								   $this->dts_reporte_final->insertRow("pagado",$la_datosvalidos["pagado"][$j]);
								   $this->dts_reporte_final->insertRow("nompro",$la_datosvalidos["nompro"][$j]);
								   $this->dts_reporte_final->insertRow("nombene",$la_datosvalidos["nombene"][$j]);
								   $this->dts_reporte_final->insertRow("cod_pro",$la_datosvalidos["cod_pro"][$j]);
								   $this->dts_reporte_final->insertRow("ced_bene",$la_datosvalidos["ced_bene"][$j]);
								   $this->dts_reporte_final->insertRow("tipo_destino",$la_datosvalidos["tipo_destino"][$j]);
							  }
							  //print "2->".$ls_comprobante." comprometido->".$ld_total_comprometer." causado->".$ld_total_causado." pagado->".$ld_total_pagado."<br><br>";
							   $lb_valido = true;
						  }//if
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
	 $ls_sql=" SELECT PMV.*, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
             "   FROM spg_dt_cmp PMV, spg_operaciones POP                                                      ".
             "  WHERE PMV.procede='".$as_procede."'                                                            ".
			 "    AND PMV.comprobante='".$as_comprobante."'                                                    ".
			 "    AND PMV.fecha='".$adt_fecha."'                                                               ".
			 "    AND PMV.operacion=POP.operacion";// print $ls_sql."<br>";
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
			   $ls_estcla=$row["estcla"];
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
				   $this->dts_reporte->insertRow("estcla",$ls_estcla);
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
			  $ls_estcla=$this->dts_reporte->getValue("estcla",$li_i);
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
				$ls_sql=" SELECT PMV.*, POP.asignar, POP.aumento, POP.disminucion, POP.comprometer, POP.causar, POP.pagar ".
						" FROM   sigesp_cmp PCM, spg_dt_cmp PMV, spg_operaciones POP ".
						" WHERE  PMV.procede_doc='".$ls_procede."'    AND PMV.documento='".$ls_comprobante."'     AND ".
						"	     PMV.codestpro1='".$ls_codestpro1."'  AND PMV.codestpro2='".$ls_codestpro2."'     AND ".
						"	     PMV.codestpro3='".$ls_codestpro3."'  AND PMV.codestpro4='".$ls_codestpro4."'     AND ".
						"	     PMV.codestpro5='".$ls_codestpro5."'  AND PMV.estcla='".$ls_estcla."'  AND PMV.spg_cuenta='".$ls_spg_cuenta."'     AND ".
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
					   $ls_estcla=$row["estcla"];
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
					   $ar_values["estcla"]=$ls_estcla;
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
						   $this->dts_reporte->insertRow("estcla",$ls_estcla);
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
    function uf_spg_reportes_comprobante_compromiso_no_causados_II($as_procede,$as_comprobante,$adt_fecha,$adt_fecdes,$adt_fechas)
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
	 $ls_sql=" SELECT spg_dt_cmp.*, spg_operaciones.asignar, spg_operaciones.aumento, spg_operaciones.disminucion, POP.comprometer, POP.causar, POP.pagar ".
             "   FROM spg_dt_cmp, spg_operaciones POP                                                      ".
             "  WHERE spg_dt_cmp.procede='".$as_procede."'                                                            ".
			 "    AND spg_dt_cmp.comprobante='".$as_comprobante."'                                                    ".
			 "    AND spg_dt_cmp.fecha='".$adt_fecha."'                                                               ".
			 "    AND spg_dt_cmp.operacion=POP.operacion";// print $ls_sql."<br>";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class
		                           MÉTODO->uf_spg_reportes_comprobante_compromiso_no_causados
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	 }
       return  $rs_data;
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PMV',$ls_seguridad);
	  $ls_sql=" SELECT distinct PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total, PCM.tipo_destino, ".
              "        PCM.cod_pro, PCM.ced_bene, PMV.operacion  ".
              " FROM   sigesp_cmp PCM, rpc_proveedor PRV, rpc_beneficiario XBF, spg_dt_cmp PMV, spg_operaciones POP ".
              " WHERE  (PCM.codemp, PCM.cod_pro) = (PRV.codemp, PRV.cod_pro)
				  AND  (PCM.codemp, PCM.ced_bene) = (XBF.codemp, XBF.ced_bene)
				  AND  (PMV.codemp, PMV.procede, PMV.comprobante, PMV.fecha, PMV.codban, PMV.ctaban) = 
				       (PCM.codemp, PCM.procede, PCM.comprobante, PCM.fecha, PCM.codban, PCM.ctaban)  
			      AND   PCM.procede=PMV.procede AND PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha  ".
      	      "   AND   PMV.operacion=POP.operacion AND POP.comprometer=1 AND POP.causar=0 AND POP.pagar=0    ".
	          "   AND   PMV.monto>=0 AND  PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."' ".$ls_seguridad.
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
				  //else
				  //{
				  //  $lb_valido = false;
				  //}
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PMV',$ls_seguridad);
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
              " WHERE  (PCM.codemp, PCM.cod_pro) = (PRV.codemp, PRV.cod_pro)
				  AND  (PCM.codemp, PCM.ced_bene) = (XBF.codemp, XBF.ced_bene)
				  AND  (PMV.codemp, PMV.procede, PMV.comprobante, PMV.fecha, PMV.codban, PMV.ctaban) = 
				       (PCM.codemp, PCM.procede, PCM.comprobante, PCM.fecha, PCM.codban, PCM.ctaban)  
			      AND  PCM.fecha BETWEEN '".$adt_fecdes."' AND '".$adt_fechas."'                                       ".
			  "   AND (POP.comprometer=1 AND POP.causar=1 AND POP.pagar=0) AND  PMV.monto>=0                           ".
			  "   AND (PCM.cod_pro=PRV.cod_pro) AND (PCM.ced_bene=XBF.ced_bene) AND (PCM.procede=PMV.procede AND       ".
      	      "        PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha) AND (PMV.operacion=POP.operacion)      ".$ls_seguridad.
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
    function uf_spg_reportes_operacion_por_especifica($adt_fecdes,$adt_fechas,$as_spg_cuenta_desde,$as_spg_cuenta_hasta,$ai_est_pres,
	                                                  $as_prvbendes,$as_prvbenhas,$as_tipoprvben,$ad_montodes,$ad_montohas,$as_concepto)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_operacion_por_especifica
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta
	 //                     $ls_spg_cuenta_desde  //  cuenta desde
	 //                     $ls_spg_cuenta_hasta   // cuenta  hasta
	 //                     $li_est_pres  // estado presupuestario
	 //                     $as_prvbendes // Codigo del Proveedor o Beneficiario
	 //                     $as_prvbenhas // Codigo del Proveedor o Beneficiario
	 //                     $as_tipoprvben // Tipo de Codigo "P"->Proveedor ó "B"->Beneficiario
	 //                     $ad_montodes   // Monto del Movimiento Inicial
	 //                     $ad_montohas   // Monto del Movimiento Final
	 //                     $as_concepto   // Descripcion del Comprobante de Gasto
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida las operaciones por especificas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Modificaco por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    25/09/2006          Fecha última Modificacion :  15/08/2008    Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('spg_dt_cmp',$ls_seguridad);
	  $this->dts_reporte_final->resetds("spg_cuenta");
	  if(strtoupper($ls_gestor)=="MYSQLT")
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
	  if($ai_est_pres=="PC")
	  {
	    $ls_estado_presupuestaria="spg_operaciones.precomprometer = 1";
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
	  $ls_sql_int = "";
	  if (($as_prvbendes!="")&&($as_prvbenhas != "")&&($as_tipoprvben != ""))
	  {
	   switch($as_tipoprvben)
	   {
	    case "B": $ls_sql_int = $ls_sql_int." AND sigesp_cmp.ced_bene between '".$as_prvbendes."' AND '".$as_prvbenhas."' ";
		          break;

		case "P": $ls_sql_int = $ls_sql_int." AND sigesp_cmp.cod_pro between '".$as_prvbendes."' AND '".$as_prvbenhas."' ";
		          break;
	   }
	  }

	  if(($ad_montodes != "")&&($ad_montohas != ""))
	  {
	   $ls_sql_int = $ls_sql_int." AND spg_dt_cmp.monto between ".$ad_montodes." AND ".$ad_montohas." ";
	  }

	  if($as_concepto != "")
	  {
	   $ls_sql_int = $ls_sql_int." AND upper(sigesp_cmp.descripcion) like '%".strtoupper($as_concepto)."%' ";
	  }
	  $adt_fecdes=$this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->io_function->uf_convertirdatetobd($adt_fechas);
	 /* $ls_sql=" SELECT  spg_dt_cmp.spg_cuenta,sigesp_cmp.tipo_destino,spg_dt_cmp.*,rpc_beneficiario.apebene,sigesp_cmp.cod_pro, ".
	          "         rpc_beneficiario.nombene,rpc_proveedor.nompro,rpc_beneficiario.ced_bene, ".
	          "         spg_cuentas.spg_cuenta, spg_cuentas.denominacion as den_spg_cta, ".
              "         ".$ls_cadena_programatica." as programatica,                                                    ".
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
			  "   AND  spg_dt_cmp.estcla=spg_cuentas.estcla          												    ".
			  "   AND  spg_dt_cmp.spg_cuenta=spg_cuentas.spg_cuenta    													".
			  $ls_seguridad." ".$ls_sql_int.
              " ORDER  BY spg_dt_cmp.spg_cuenta, programatica,spg_dt_cmp.fecha                                          ";*/

	   $ls_sql=" SELECT  spg_dt_cmp.spg_cuenta,sigesp_cmp.tipo_destino,spg_dt_cmp.codestpro1,spg_dt_cmp.codestpro2,spg_dt_cmp.codestpro3,".
	          "          spg_dt_cmp.codestpro4,spg_dt_cmp.codestpro5,spg_dt_cmp.estcla,spg_dt_cmp.comprobante,spg_dt_cmp.procede_doc,spg_dt_cmp.documento,rpc_beneficiario.apebene,sigesp_cmp.cod_pro, ".
			  "          spg_dt_cmp.operacion,spg_dt_cmp.monto, spg_dt_cmp.orden, spg_dt_cmp.procede, spg_dt_cmp.descripcion, spg_dt_cmp.fecha,".
	          "         rpc_beneficiario.nombene,rpc_proveedor.nompro,rpc_beneficiario.ced_bene, ".
	          "         spg_cuentas.spg_cuenta, spg_cuentas.denominacion as den_spg_cta, ".
              "         ".$ls_cadena_programatica." as programatica,                                                    ".
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
			  "   AND  spg_dt_cmp.estcla=spg_cuentas.estcla          												    ".
			  "   AND  spg_dt_cmp.spg_cuenta=spg_cuentas.spg_cuenta    													".
			  $ls_seguridad." ".$ls_sql_int.
              " ORDER  BY spg_dt_cmp.spg_cuenta, programatica,spg_dt_cmp.fecha                                          ";
	  //print $ls_sql."<br><br><br>";	return true;
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
			   $ls_estcla=$row["estcla"];
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

			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
			   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte_final->insertRow("estcla",$ls_estcla);
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
		$ls_sql=" SELECT spg_cuenta, descripcion, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, ".
	          "        sum(monto) as monto ".
              " FROM   spg_dt_cmp ".
              " WHERE  codemp='".$this->ls_codemp."' AND spg_cuenta between '".$as_spg_cuenta_desde."' AND ".
			  "        '".$as_spg_cuenta_hasta."' AND fecha ='".$ldt_fecdes."' ".
              " GROUP BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion, monto ".
              " ORDER BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion, monto ";
		  $rs_data=$this->io_sql->select($ls_sql);
		  if($rs_data===false)
		  {   // error interno sql
			   $this->io_msg->message("CLASE->sigesp_spg_reporte_class
									   MÉTODO->uf_obtener_asignado
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('spg_dt_cmp',$ls_seguridad);
	  $ls_sql=" SELECT spg_cuenta, descripcion, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion,        ".
	          "        sum(monto) as monto, estcla                                                                            ".
              " FROM   spg_dt_cmp                                                                                             ".
              " WHERE  codemp='".$this->ls_codemp."' AND fecha between '".$adt_fecdes."' AND '".$adt_fechas."'                ".
			  "   AND spg_cuenta between '".$as_spg_cuenta_desde."' AND '".$as_spg_cuenta_hasta."'                            ".
			  $ls_seguridad.
              " GROUP BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, operacion, descripcion, estcla".
              " ORDER BY spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, operacion,            ".
			  "           descripcion, monto ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  /* $this->io_msg->message("CLASE->sigesp_spg_reporte_class
		                           MÉTODO->uf_spg_reportes_ejecutado_por_partida
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));*/
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
		    $ls_estcla=$this->dts_cab->getValue("estcla",$li_i);
		    $ls_operacion=$this->dts_cab->getValue("operacion",$li_i);
		    $ld_monto=$this->dts_cab->getValue("monto",$li_i);
		    $ld_descripcion=$this->dts_cab->getValue("descripcion",$li_i);
		    $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
		    if ($li_i<$li_total)
		    {
				$ls_spg_cuenta_next=$this->dts_cab->getValue("spg_cuenta",$li_tmp);
				$ls_codestpro1_next=$this->dts_cab->getValue("codestpro1",$li_tmp);
				$ls_codestpro2_next=$this->dts_cab->getValue("codestpro2",$li_tmp);
				$ls_codestpro3_next=$this->dts_cab->getValue("codestpro3",$li_tmp);
				$ls_codestpro4_next=$this->dts_cab->getValue("codestpro4",$li_tmp);
				$ls_codestpro5_next=$this->dts_cab->getValue("codestpro5",$li_tmp);
				$ls_estcla_next=$this->dts_cab->getValue("estcla",$li_tmp);
		        $ls_programatica_next=$ls_codestpro1_next.$ls_codestpro2_next.$ls_codestpro3_next.$ls_codestpro4_next.$ls_codestpro5_next.$ls_estcla_next;
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

			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
			   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte_final->insertRow("estcla",$ls_estcla);
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

			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
			   $this->dts_reporte_final->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte_final->insertRow("codestpro1",$ls_codestpro1);
			   $this->dts_reporte_final->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte_final->insertRow("codestpro3",$ls_codestpro3);
			   $this->dts_reporte_final->insertRow("codestpro4",$ls_codestpro4);
			   $this->dts_reporte_final->insertRow("codestpro5",$ls_codestpro5);
			   $this->dts_reporte_final->insertRow("estcla",$ls_estcla);
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
	function uf_spg_reportes_operacion_por_banco($adt_fecdes,$adt_fechas,$as_spg_cuenta_desde,$as_spg_cuenta_hasta,$as_codban,$as_ctaban,$as_ckbfec,$as_ckbpro,$as_ckbdoc,$as_ckbbene,&$lb_valido)
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('spg_cuentas',$ls_seguridad);
	  switch ($ls_gestor) {
		case 'MYSQLT':
			$ls_cadaux = " AND spg_cuentas.spg_cuenta=scb_movbco_spg.spg_cuenta                              ".
						 " AND CONCAT(spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,  ".
						 "            spg_cuentas.codestpro4,spg_cuentas.codestpro5)=scb_movbco_spg.codestpro";
			break;

		case 'POSTGRES':
			$ls_cadaux = " AND spg_cuentas.spg_cuenta=scb_movbco_spg.spg_cuenta".
						 " AND spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||".
						 "     spg_cuentas.codestpro4||spg_cuentas.codestpro5=scb_movbco_spg.codestpro";
			break;

	   case 'INFORMIX':
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
			  "        scb_movbco_spg.monto,spg_cuentas.denominacion as dencuenta,spg_cuentas.estcla".
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
			  " ".$ls_seguridad." ".
			  "  ORDER BY  scb_movbco_spg.spg_cuenta ".$ls_cadena." 								";
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PMV',$ls_seguridad);
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
	  if(strtoupper($ls_gestor)=="MYSQLT")
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
					    PMV.operacion,PMV.monto
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
				    AND PMV.operacion=POP.operacion ".$ls_seguridad." ".
				  "GROUP BY PCM.cod_pro, PCM.ced_bene, PCM.fecha, PCM.procede, PCM.comprobante, PCM.descripcion,
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
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PMV',$ls_seguridad);
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
	  if(strtoupper($ls_gestor)=="MYSQLT")
	  {
	    $ls_cadena="CONCAT(rtrim(XBF.apebene),', ',XBF.nombene)";
	  }
	  else
	  {
	    $ls_cadena="rtrim(XBF.apebene)||', '||XBF.nombene";
	  }
	  $adt_fecdes = $this->io_function->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas = $this->io_function->uf_convertirdatetobd($adt_fechas);
	  //print_r ($_SESSION);
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
					    PMV.operacion,PMV.monto
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
				    AND PMV.operacion=POP.operacion ".$ls_seguridad." ".
				  " GROUP BY  PRV.cod_pro, PCM.procede, PCM.comprobante, PCM.fecha, PCM.descripcion, PCM.total,
                            PCM.tipo_destino,  PCM.ced_bene, XBF.nombene, PMV.operacion, PMV.monto
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
		/* $ls_sql = "SELECT spg_dtmp_cmp.codestpro1,
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
						   spg_dtmp_cmp.codestpro5,spg_dtmp_cmp.spg_cuenta";*/
				//print $ls_sql.'<br>';
		 
		 $ls_sql = " SELECT b.codestpro1, b.codestpro2,
	                        b.codestpro3, b.codestpro4,
	                        b.codestpro5, b.spg_cuenta,
	                        b.operacion,MAX(b.descripcion) as descripcion,
	                        SUM(b.monto) as monto,
							MAX(d.denfuefin) as denfuefin, CASE MAX(e.denuac) WHEN NULL THEN
                                                  ''
                                                  ELSE
                                                  MAX(e.denuac)
                                                  END as denuac
                     FROM sigesp_cmp_md a
                     JOIN spg_dtmp_cmp b on a.codemp= b.codemp
                                         AND a.procede=b.procede
                                         AND a.comprobante=b.comprobante
                                         AND a.fecha=b.fecha
                     JOIN sigesp_fuentefinanciamiento d on a.codemp=d.codemp
                                                        AND a.codfuefin=d.codfuefin
                     LEFT OUTER JOIN spg_ministerio_ua e on a.codemp=e.codemp
					                                     AND a.coduac=e.coduac
					 WHERE a.codemp='".$as_codemp."'
					       AND a.procede='".$as_procede."'
					       AND a.comprobante='".$as_comprobante."'
					       AND a.fecha='".$ld_fecha."'
					 GROUP BY 	b.codestpro1,b.codestpro2,b.codestpro3,
				                b.codestpro4,b.codestpro5,b.operacion,b.spg_cuenta
				     ORDER BY b.operacion DESC,b.codestpro1,b.codestpro2,b.codestpro3,b.codestpro4,
						      b.codestpro5,b.spg_cuenta";

		 $rs_data = $this->io_sql->select($ls_sql);
		 if ($rs_data===false)
		 {
			  $this->io_msg->message("Error en consulta metodo->uf_select_dt_comprobante ".$this->io_function->uf_convertirmsg($this->io_sql->message));
			  $lb_valido = false;
		 }
		 else
		 {
			  $ai_numrows = $this->io_sql->num_rows($rs_data);
		 }
		 return $lb_valido;
	}


		///////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  " MODIFICACIONES PRESUPUESTARIAS  "                        //
	/////////////////////////////////////////////////////////////////////////////////////
	function uf_select_dt_comprobante2($as_codemp,$as_procede,$as_comprobante,$ad_fecha,&$ai_numrows,&$rs_data)
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
		 $ls_sql = " SELECT b.codestpro1, b.codestpro2,
	                        b.codestpro3, b.codestpro4,
	                        b.codestpro5, b.spg_cuenta,
	                        b.operacion,MAX(b.descripcion) as descripcion,
	                        SUM(b.monto) as monto,
							MAX(d.denfuefin) as denfuefin, CASE MAX(e.denuac) WHEN NULL THEN
                                                  ''
                                                  ELSE
                                                  MAX(e.denuac)
                                                  END as denuac
                     FROM sigesp_cmp_md a
                     JOIN spg_dtmp_cmp b on a.codemp= b.codemp
                                         AND a.procede=b.procede
                                         AND a.comprobante=b.comprobante
                                         AND a.fecha=b.fecha
                     JOIN sigesp_fuentefinanciamiento d on a.codemp=d.codemp
                                                        AND a.codfuefin=d.codfuefin
                     LEFT OUTER JOIN spg_ministerio_ua e on a.codemp=e.codemp
					                                     AND a.coduac=e.coduac
					 WHERE a.codemp='".$as_codemp."'
					       AND a.procede='".$as_procede."'
					       AND a.comprobante='".$as_comprobante."'
					       AND a.fecha='".$ld_fecha."'
		 				   and operacion='DI'
					 GROUP BY 	b.codestpro1,b.codestpro2,b.codestpro3,
				                b.codestpro4,b.codestpro5,b.operacion,b.spg_cuenta
				     ORDER BY b.operacion DESC,b.codestpro1,b.codestpro2,b.codestpro3,b.codestpro4,
						      b.codestpro5,b.spg_cuenta";
		 $rs_data = $this->io_sql->select($ls_sql);
		 if ($rs_data===false)
		 {
			  $this->io_msg->message("Error en consulta metodo->uf_select_dt_comprobante ".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
		$ls_sql = "SELECT distinct denominacion
					 FROM spg_cuentas
					WHERE codemp='".$as_codemp."'
					  AND codestpro1='".$as_codestpro1."'
					  AND codestpro2='".$as_codestpro2."'
					  AND codestpro3 like '%%'
					  AND spg_cuenta='".$as_spgcta."'   ";
		$rs_datos = $io_sql->select($ls_sql);
		if ($rs_datos===false)
		   {
			 $lb_valido = false;
			 //print $io_sql->message;
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
		  $rs_data   = $io_sql->select($ls_sql);//print "SENTENCIA =>".$ls_sql.'<br><br><br><br><br>';
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
	
		
	function uf_select_fuente_fin($comprobante)
	{
		global $io_sql;
		$lb_valido = true;
		$sql="select denfuefin,sigesp_cmp_md.descripcion from sigesp_cmp_md inner join 
			 sigesp_fuentefinanciamiento on  sigesp_cmp_md.codfuefin=
			 sigesp_fuentefinanciamiento.codfuefin where 
			 sigesp_cmp_md.comprobante='{$comprobante}'";
			 //echo $sql;
			 //die();
		  $rs_data   = $io_sql->select($sql);	 
		  if ($rs_data===false)
		  {
			   return false;
		  }
		  else
		  {
		  		if ($row=$io_sql->fetch_row($rs_data))
				{
					//$nombrefuefin = $row["denfuefin"];
					return $row;
				}
				else
				{
					return false;
				}
		  }
	}	 
			 		
		
	/********************************************************************************************************************************/
	function uf_select_data_fila($ls_sql,&$fila)
	{
		  global $io_sql;
		  //echo $ls_sql;
		  //die();
		  $lb_valido = true;
		  $rs_data   = $io_sql->select($ls_sql);//print "SENTENCIA =>".$ls_sql.'<br><br><br><br><br>';
		  if ($rs_data===false)
			 {
			   $lb_valido = false;
			   print $io_sql->message;
			 }
		  else
			 {
				if ($row=$io_sql->fetch_row($rs_data))
				{

				  $fila = $row;
				  $io_sql->free_result($rs_data);
				}
			 }
		  return $lb_valido;
	 }
/********************************************************************************************************************************/
	 
	 
function uf_select_dt_comprobante_rdis($as_codemp,$as_procede,$as_comprobante,$ad_fecha, $ai_total ,
										$ia_niveles_scg,$li_posicion,$li_numrows)
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
		
		 $lb_valido=$this->uf_select_dt_comprobante2($as_codemp,$as_procede,$as_comprobante,$ad_fecha,&$ai_numrows2,&$rs_dat2);
		 $ls_codemp=$this->ls_codemp;
		 $ls_procede=$as_procede;
		 $ls_comprobante=$as_comprobante;
		 $ld_fecha=$ad_fecha;
		 $li_total=$ai_total;
		 if($lb_valido)
		 {
			if ($ai_numrows2)
			{
				 $li_pos = 0;
				 $lb_impreso = false;
				 $ld_totced  = 0;
				 $ld_totrec  = 0;
				 $li_filas   = 0;
				 while ($row=$this->io_sql->fetch_row($rs_dat2))
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
						  //print $ls_codestpro1;
						  $la_estpro1 = str_split($ls_codestpro1,1);
						 //for ($li=0;$li<20;$li++)
						 for ($li=0;$li<25;$li++)
						  {
							   if($la_estpro1[$li]>0)
								{
								  break;
								}
						}
							//if($li==19)
							if($li==24)
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li-1,(strlen($ls_codestpro1)-($li-1)));
							}
							else
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li,(strlen($ls_codestpro1)-$li));
							}

						 $la_estpro2 = str_split($ls_codestpro2,1);//print_r ($la_estpro1);
						 //for ($li=0;$li<6;$li++)
						   for ($li=0;$li<25;$li++)
							 {
							   if($la_estpro2[$li]>0)
								{
								  break;
								}
							}
							//if($li==5)
							if($li==24)
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li-1,(strlen($ls_codestpro2)-($li-1)));
							}
							else
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li,(strlen($ls_codestpro2)-$li));
							}

							$la_estpro3 = str_split($ls_codestpro3,1);//print_r ($la_estpro1);
						 //for ($li=0;$li<6;$li++)
						   for ($li=0;$li<25;$li++)
							 {
							   if($la_estpro3[$li]>0)
								{
								  break;
								}
							}
							if($li==24)
							{
								$ls_estpro3_aux=substr($ls_codestpro3,$li-1,(strlen($ls_codestpro3)-($li-1)));
							}
							else
							{
								$ls_estpro3_aux=substr($ls_codestpro3,$li,(strlen($ls_codestpro3)-$li));
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
						 $this->io_dsreport->insertRow("proyecto3","");
						 $this->io_dsreport->insertRow("accion3","");
						 $this->io_dsreport->insertRow("ejecutora3","");
						 $this->io_dsreport->insertRow("partida3","");
						 $this->io_dsreport->insertRow("generica3","");
						 $this->io_dsreport->insertRow("especifica3","");
						 $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							 $this->io_dsreport->insertRow("proyecto3","");
					    	 $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
                             $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro1);
							 $this->io_dsreport->insertRow("monto",$ld_monniv);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);

							}
						 $this->uf_select_data("SELECT spg_ep2.denestpro2, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_ep2, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_ep2.codemp=spg_dtmp_cmp.codemp
											AND spg_ep2.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_ep2.codestpro2=spg_dtmp_cmp.codestpro2
										  GROUP BY denestpro2",'denestpro2','monto',$ls_denestpro2,$ld_monniv);
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';

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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro2);
							 $this->io_dsreport->insertRow("monto",$ld_monniv);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
					     $this->uf_select_data("SELECT spg_ep3.denestpro3, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_ep3, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_ep3.codemp=spg_dtmp_cmp.codemp
											AND spg_ep3.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_ep3.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_ep3.codestpro3=spg_dtmp_cmp.codestpro3
										  GROUP BY denestpro3",'denestpro3','monto',$ls_denestpro3,$ld_monniv);
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto3'=>$ls_estpro1_aux,'accion3'=>$ls_estpro2_aux,'ejecutora3'=>$ls_estpro3_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';

						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro3);
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
							   $li_len    = strlen(trim($ls_spg_cuenta));
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
						if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
						{
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
												  spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta ORDER BY spg_cuentas.spg_cuenta ASC",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						  $this->uf_select_data("SELECT spg_cuentas.spg_cuenta, sum(spg_dtmp_cmp.monto) as monto
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
												  spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta ORDER BY spg_cuentas.spg_cuenta ASC",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $ls_denctaspg = strtoupper($ls_denctaspg);
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 $ls_campo1="";
						 $ls_variable1="";
						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1],$li_len,0);
						 $ls_denctaspg =$this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
	                     if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
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
											AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1]."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,denctaspg",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_dtmp_cmp.spg_cuenta like '".trim($la_cuenta[0].$la_cuenta[1])."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta,spg_cuentas.denominacion",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}

						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1].$la_cuenta[2],$li_len,0);
						 $ls_denctaspg = $this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
						 if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
						 {
						  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
											GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,denctaspg",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						   $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta,spg_cuentas.denominacion",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
							 $this->io_dsreport->insertRow("subespecifica2",'');
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 $ls_subespaux = $la_cuenta["3"];
						 if ($ls_subespaux!='00')
							{
							 if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
						     {
							   $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
								FROM spg_cuentas, spg_dtmp_cmp
							   WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
							   GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
										 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
							 }
							 else
							 {
							  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
								FROM spg_cuentas, spg_dtmp_cmp
							   WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
										 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta,spg_cuentas.denominacion",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
							 }
							   $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'subespecifica2'=>$la_cuenta[3],'operacion'=>$ls_operacion),'proyecto');
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
									 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
									 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
									 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
									 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
									 $this->io_dsreport->insertRow("subespecifica2",$la_cuenta[3]);
									 $this->io_dsreport->insertRow("proyecto3","");
									 $this->io_dsreport->insertRow("accion3","");
									 $this->io_dsreport->insertRow("ejecutora3","");
									 $this->io_dsreport->insertRow("partida3","");
									 $this->io_dsreport->insertRow("generica3","");
									 $this->io_dsreport->insertRow("especifica3","");
									 $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
						      $this->io_dsreport->insertRow("accion3","");
                              $this->io_dsreport->insertRow("ejecutora3","");
						      $this->io_dsreport->insertRow("partida3","");
						      $this->io_dsreport->insertRow("generica3","");
                              $this->io_dsreport->insertRow("especifica3","");
						      $this->io_dsreport->insertRow("subespecifica3","");
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
							   $la_data2[$i] = array('proyecto'=>$ls_proyecto,
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
			
		 return  $la_data2;
	}
	 
	 
	 
	 
	 
	 
	 
function uf_select_totalcedentes($comprobante)
{
		global $io_sql;
		$lb_valido = true;
		$sql="select sum(monto) as total from spg_dtmp_cmp  where 
			 spg_dtmp_cmp.comprobante='{$comprobante}'
			 and operacion='DI'";
		  $rs_data   = $io_sql->select($sql);	 
		  if ($rs_data===false)
		  {
			   return false;
		  }
		  else
		  {
		  		if ($row=$io_sql->fetch_row($rs_data))
				{
					$nombrefuefin = $row["total"];
					return $nombrefuefin;
				}
				else
				{
					return false;
				}
		  }
}

function uf_select_totalreceptoras($comprobante)
{
		global $io_sql;
		$lb_valido = true;
		$sql="select sum(monto) as total from spg_dtmp_cmp  where 
			 spg_dtmp_cmp.comprobante='{$comprobante}'
			 and operacion='AU'";
			 //echo $sql;
			 //die();
		  $rs_data   = $io_sql->select($sql);	 
		  if ($rs_data===false)
		  {
			   return false;
		  }
		  else
		  {
		  		if ($row=$io_sql->fetch_row($rs_data))
				{
					$nombrefuefin = $row["total"];
					return $nombrefuefin;
				}
				else
				{
					return false;
				}
		  }
}


	
function uf_select_dt_comprobante_r($as_codemp,$as_procede,$as_comprobante,$ad_fecha, $ai_total ,
										&$la_data,$ia_niveles_scg,$li_posicion,$li_numrows,$tipo="")
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
		 $totalcedentes = $this->uf_select_totalcedentes($ls_comprobante);
		 $totalrecep = $this->uf_select_totalreceptoras($ls_comprobante);
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
						  //print $ls_codestpro1;
						  $la_estpro1 = str_split($ls_codestpro1,1);
						 //for ($li=0;$li<20;$li++)
						 for ($li=0;$li<25;$li++)
						  {
							   if($la_estpro1[$li]>0)
								{
								  break;
								}
						  }
							//if($li==19)
							if($li==24)
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li-1,(strlen($ls_codestpro1)-($li-1)));
							}
							else
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li,(strlen($ls_codestpro1)-$li));
							}

						 $la_estpro2 = str_split($ls_codestpro2,1);//print_r ($la_estpro1);
						 //for ($li=0;$li<6;$li++)
						   for ($li=0;$li<25;$li++)
							 {
							   if($la_estpro2[$li]>0)
								{
								  break;
								}
							}
							//if($li==5)
							if($li==24)
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li-1,(strlen($ls_codestpro2)-($li-1)));
							}
							else
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li,(strlen($ls_codestpro2)-$li));
							}

							$la_estpro3 = str_split($ls_codestpro3,1);//print_r ($la_estpro1);
						 //for ($li=0;$li<6;$li++)
						   for ($li=0;$li<25;$li++)
							{
							   if($la_estpro3[$li]>0)
								{
								  break;
								}
							}
							if($li==24)
							{
								$ls_estpro3_aux=substr($ls_codestpro3,$li-1,(strlen($ls_codestpro3)-($li-1)));
							}
							else
							{
								$ls_estpro3_aux=substr($ls_codestpro3,$li,(strlen($ls_codestpro3)-$li));
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
							 $this->io_dsreport->insertRow("proyecto3","");
							 $this->io_dsreport->insertRow("accion3","");
							 $this->io_dsreport->insertRow("ejecutora3","");
							 $this->io_dsreport->insertRow("partida3","");
							 $this->io_dsreport->insertRow("generica3","");
							 $this->io_dsreport->insertRow("especifica3","");
							 $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",'<b><c:uline>PARTIDAS CEDENTES--->:</c:uline></b>');
							 $this->io_dsreport->insertRow("monto",$totalcedentes);
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
							  $this->io_dsreport->insertRow("denominacion",'<b><c:uline>TOTAL CEDENTES:</c:uline></b>');
							  $this->io_dsreport->insertRow("monto",$totalcedentes);
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
							  $this->io_dsreport->insertRow("denominacion",'<b><c:uline>PARTIDAS RECEPTORAS:</c:uline></b>');
							  $this->io_dsreport->insertRow("monto",$totalrecep);
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
							 $this->io_dsreport->insertRow("proyecto3","");
					    	 $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
                             $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro1);
							 $this->io_dsreport->insertRow("monto",$ld_monniv);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);

						 }
						 $this->uf_select_data("SELECT spg_ep2.denestpro2, sum(spg_dtmp_cmp.monto) as monto
										    FROM spg_ep2, spg_dtmp_cmp
										    WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_ep2.codemp=spg_dtmp_cmp.codemp
											AND spg_ep2.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_ep2.codestpro2=spg_dtmp_cmp.codestpro2
										    GROUP BY denestpro2",'denestpro2','monto',$ls_denestpro2,$ld_monniv);
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';

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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro2);
							 $this->io_dsreport->insertRow("monto",$ld_monniv);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
					     $this->uf_select_data("SELECT spg_ep3.denestpro3, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_ep3, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_ep3.codemp=spg_dtmp_cmp.codemp
											AND spg_ep3.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_ep3.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_ep3.codestpro3=spg_dtmp_cmp.codestpro3
										  GROUP BY denestpro3",'denestpro3','monto',$ls_denestpro3,$ld_monniv);
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto3'=>$ls_estpro1_aux,'accion3'=>$ls_estpro2_aux,'ejecutora3'=>$ls_estpro3_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';

						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denestpro3);
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
							   $li_len    = strlen(trim($ls_spg_cuenta));
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
						if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
						{
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
												  spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta ORDER BY spg_cuentas.spg_cuenta ASC",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						  $this->uf_select_data("SELECT spg_cuentas.spg_cuenta, sum(spg_dtmp_cmp.monto) as monto
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
												  spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta ORDER BY spg_cuentas.spg_cuenta ASC",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $ls_denctaspg = strtoupper($ls_denctaspg);
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 $ls_campo1="";
						 $ls_variable1="";
						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1],$li_len,0);
						 $ls_denctaspg =$this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
	                     if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
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
											AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1]."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,denctaspg",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
											AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
											AND spg_dtmp_cmp.procede = '".$ls_procede."'
											AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
											AND spg_dtmp_cmp.operacion='".$ls_operacion."'
											AND spg_dtmp_cmp.spg_cuenta like '".trim($la_cuenta[0].$la_cuenta[1])."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta,spg_cuentas.denominacion",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}

						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1].$la_cuenta[2],$li_len,0);
						 $ls_denctaspg = $this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
						 if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
						 {
						  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
											GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,denctaspg",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						   $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										   FROM spg_cuentas, spg_dtmp_cmp
										  WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta,spg_cuentas.denominacion",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
							 $this->io_dsreport->insertRow("subespecifica2",'');
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							 $this->io_dsreport->insertRow("monto",$ld_monspg);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 $ls_subespaux = $la_cuenta["3"];
						 if ($ls_subespaux!='00')
							{
							 if (strtoupper($_SESSION["ls_gestor"]) <> "INFORMIX")
						     {
							   $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
								FROM spg_cuentas, spg_dtmp_cmp
							   WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
							   GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
										 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
							 }
							 else
							 {
							  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
								FROM spg_cuentas, spg_dtmp_cmp
							   WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
										 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta,spg_cuentas.denominacion",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
							 }
							   $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'subespecifica2'=>$la_cuenta[3],'operacion'=>$ls_operacion),'proyecto');
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
									 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
									 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
									 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
									 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
									 $this->io_dsreport->insertRow("subespecifica2",$la_cuenta[3]);
									 $this->io_dsreport->insertRow("proyecto3","");
									 $this->io_dsreport->insertRow("accion3","");
									 $this->io_dsreport->insertRow("ejecutora3","");
									 $this->io_dsreport->insertRow("partida3","");
									 $this->io_dsreport->insertRow("generica3","");
									 $this->io_dsreport->insertRow("especifica3","");
									 $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
						      $this->io_dsreport->insertRow("accion3","");
						      $this->io_dsreport->insertRow("ejecutora3","");
						      $this->io_dsreport->insertRow("partida3","");
						      $this->io_dsreport->insertRow("generica3","");
						      $this->io_dsreport->insertRow("especifica3","");
                              $this->io_dsreport->insertRow("subespecifica3","");
							  $this->io_dsreport->insertRow("denominacion",'<b><c:uline>TOTAL RECEPTORAS:</c:uline></b>');
							  $this->io_dsreport->insertRow("monto",$totalrecep);
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
							  $this->io_dsreport->insertRow("proyecto3","");
						      $this->io_dsreport->insertRow("accion3","");
                              $this->io_dsreport->insertRow("ejecutora3","");
						      $this->io_dsreport->insertRow("partida3","");
						      $this->io_dsreport->insertRow("generica3","");
                              $this->io_dsreport->insertRow("especifica3","");
						      $this->io_dsreport->insertRow("subespecifica3","");
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

	
	function uf_select_dt_comprobante_r2($as_codemp,$as_procede,$as_comprobante,$ad_fecha, $ai_total ,
										&$la_data,$ia_niveles_scg,$li_posicion,$li_numrows,$tipo="")
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
		if($tipo=="")
		{
			$Auxtipo1="";
		}
		else
		{
			$Auxtipo1="DI"; 	
		}
		
		$lb_valido=$this->uf_select_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$ad_fecha,&$ai_numrows,&$rs_dat,$Auxtipo1);

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
						 $this->uf_select_data_fila("SELECT spg_ep1.denestpro1,
						 				    sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 				    sum(spg_cuentas.disminucion) as disminucion,(
						 				    				SELECT  sum(spg_dtmp_cmp.monto) 
															FROM spg_ep2, spg_dtmp_cmp
															WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
															AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
															AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
															AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
															AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
															AND spg_dtmp_cmp.procede = '".$ls_procede."'
															AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
															AND spg_dtmp_cmp.operacion='".$ls_operacion."'
															AND spg_ep2.codemp=spg_dtmp_cmp.codemp
															AND spg_ep2.codestpro1=spg_dtmp_cmp.codestpro1
															AND spg_ep2.codestpro2=spg_dtmp_cmp.codestpro2
															GROUP BY denestpro2
															) as modificado,
						 				   (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				    sum(spg_cuentas.comprometido) as comprometido
										    FROM spg_ep1,spg_cuentas 
										    WHERE spg_cuentas.codemp='".$ls_codemp."'
											AND spg_cuentas.codestpro1='".$ls_codestpro1."'
											AND spg_ep1.codestpro1=spg_cuentas.codestpro1
										    GROUP BY denestpro1",$fila);
						  //print $ls_codestpro1;
						  $la_estpro1 = str_split($ls_codestpro1,1);
						 //for ($li=0;$li<20;$li++)
						 for ($li=0;$li<25;$li++)
						  {
							   if($la_estpro1[$li]>0)
								{
								  break;
								}
						  }
							//if($li==19)
							if($li==24)
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li-1,(strlen($ls_codestpro1)-($li-1)));
							}
							else
							{
								$ls_estpro1_aux=substr($ls_codestpro1,$li,(strlen($ls_codestpro1)-$li));
							}

						 $la_estpro2 = str_split($ls_codestpro2,1);//print_r ($la_estpro1);
						 //for ($li=0;$li<6;$li++)
						   for ($li=0;$li<25;$li++)
							 {
							   if($la_estpro2[$li]>0)
								{
								  break;
								}
							}
							//if($li==5)
							if($li==24)
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li-1,(strlen($ls_codestpro2)-($li-1)));
							}
							else
							{
								$ls_estpro2_aux=substr($ls_codestpro2,$li,(strlen($ls_codestpro2)-$li));
							}

							$la_estpro3 = str_split($ls_codestpro3,1);//print_r ($la_estpro1);
						 //for ($li=0;$li<6;$li++)
						   for ($li=0;$li<25;$li++)
							 {
							   if($la_estpro3[$li]>0)
								{
								  break;
								}
							}
							if($li==24)
							{
								$ls_estpro3_aux=substr($ls_codestpro3,$li-1,(strlen($ls_codestpro3)-($li-1)));
							}
							else
							{
								$ls_estpro3_aux=substr($ls_codestpro3,$li,(strlen($ls_codestpro3)-$li));
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
						 $this->io_dsreport->insertRow("asignado","");
						 $this->io_dsreport->insertRow("accion2","");
						 $this->io_dsreport->insertRow("ejecutora2","");
						 $this->io_dsreport->insertRow("partida2","");
						 $this->io_dsreport->insertRow("generica2","");
						 $this->io_dsreport->insertRow("especifica2","");
						 $this->io_dsreport->insertRow("subespecifica2","");
						 $this->io_dsreport->insertRow("proyecto3","");
						 $this->io_dsreport->insertRow("accion3","");
						 $this->io_dsreport->insertRow("ejecutora3","");
						 $this->io_dsreport->insertRow("partida3","");
						 $this->io_dsreport->insertRow("generica3","");
						 $this->io_dsreport->insertRow("especifica3","");
						 $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							 $this->io_dsreport->insertRow("proyecto3","");
					    	 $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
                             $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$fila["denestpro1"]);
							 //$this->io_dsreport->insertRow("monto",$fila["monto"]);
							 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
							 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
							 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
							 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
							 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
							 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							 
							}
						 $this->uf_select_data_fila("SELECT spg_ep2.denestpro2,
						 					sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 				    sum(spg_cuentas.disminucion) as disminucion,(
														SELECT  sum(spg_dtmp_cmp.monto) 
														FROM spg_ep2, spg_dtmp_cmp
														WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
														AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
														AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
														AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
														AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
														AND spg_dtmp_cmp.procede = '".$ls_procede."'
														AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
														AND spg_dtmp_cmp.operacion='".$ls_operacion."'
														AND spg_ep2.codemp=spg_dtmp_cmp.codemp
														AND spg_ep2.codestpro1=spg_dtmp_cmp.codestpro1
														AND spg_ep2.codestpro2=spg_dtmp_cmp.codestpro2
														GROUP BY denestpro2 
						 				    ) as modificado,
						 				    (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				    sum(spg_cuentas.disminucion) as comprometido
										    FROM spg_ep2,spg_cuentas 
										    WHERE spg_cuentas.codemp='".$ls_codemp."'
											AND spg_ep2.codemp = spg_cuentas.codemp
											AND spg_ep2.codestpro1 = spg_cuentas.codestpro1
											AND spg_ep2.codestpro2 = spg_cuentas.codestpro2
											AND spg_cuentas.codestpro1='".$ls_codestpro1."'
											AND spg_cuentas.codestpro2='".$ls_codestpro2."'						
										   GROUP BY denestpro2",$fila);
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';

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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$fila["denestpro2"]);
							// $this->io_dsreport->insertRow("monto",$fila["monto"]);
							 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
							 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
							 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
							 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
							 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
							 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
					     					$this->uf_select_data_fila("SELECT spg_ep3.denestpro3,
					     					sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 				    sum(spg_cuentas.disminucion) as disminucion,(
								 				    	SELECT  sum(spg_dtmp_cmp.monto) 
														FROM spg_ep3, spg_dtmp_cmp
														WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
														AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
														AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
														AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
														AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
														AND spg_dtmp_cmp.procede = '".$ls_procede."'
														AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
														AND spg_dtmp_cmp.operacion='".$ls_operacion."'
														AND spg_ep3.codemp=spg_dtmp_cmp.codemp
														AND spg_ep3.codestpro1=spg_dtmp_cmp.codestpro1
														AND spg_ep3.codestpro2=spg_dtmp_cmp.codestpro2
														AND spg_ep3.codestpro3=spg_dtmp_cmp.codestpro3
														GROUP BY denestpro3
						 				    ) as modificado,
						 				    (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				    sum(spg_cuentas.disminucion) as comprometido
										    FROM spg_ep3,spg_cuentas
										    WHERE spg_cuentas.codemp='".$ls_codemp."'
											AND spg_cuentas.codestpro1='".$ls_codestpro1."'
											AND spg_cuentas.codestpro2='".$ls_codestpro2."'
											AND spg_cuentas.codestpro3='".$ls_codestpro3."'
											AND spg_ep3.codemp=spg_cuentas.codemp
											AND spg_ep3.codestpro1=spg_cuentas.codestpro1
											AND spg_ep3.codestpro2=spg_cuentas.codestpro2
											AND spg_ep3.codestpro3=spg_cuentas.codestpro3
										  GROUP BY denestpro3",$fila);
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto3'=>$ls_estpro1_aux,'accion3'=>$ls_estpro2_aux,'ejecutora3'=>$ls_estpro3_aux,'operacion'=>$ls_operacion),'proyecto');//print "Encontrado => ".$li_encontrado.'<br>';

						 if ($li_encontrado<=0)
							{
							 $this->io_dsreport->insertRow("proyecto","");
							 $this->io_dsreport->insertRow("accion","");
							 $this->io_dsreport->insertRow("ejecutora",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida","");
							 $this->io_dsreport->insertRow("generica","");
							 $this->io_dsreport->insertRow("especifica","");
							 $this->io_dsreport->insertRow("subespecifica","");
							 $this->io_dsreport->insertRow("proyecto2",$ls_estpro1_aux);
							 $this->io_dsreport->insertRow("accion2",$ls_estpro2_aux);
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2","");
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
					 		 $this->io_dsreport->insertRow("denominacion",$fila["denestpro3"]);
							// $this->io_dsreport->insertRow("monto",$fila["monto"]);
							 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
							 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
							 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
							 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
							 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
							 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);	
							}

						 $li_totfil       = 0;
						 $as_cuenta       = "";
						 for ($li=$li_total;$li>1;$li--)
							 {
							   $li_ant    = $ia_niveles_scg[$li-1];
							   $li_act    = $ia_niveles_scg[$li];
							   $li_fila   = $li_act-$li_ant;
							   $li_len    = strlen(trim($ls_spg_cuenta));
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
						if (strtoupper($_SESSION["ls_gestor"]) == "INFORMIX")
						{
						 $this->uf_select_data_fila("SELECT spg_cuentas.spg_cuenta,
						 				   sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 				   sum(spg_cuentas.disminucion) as disminucion,(
								 				   SELECT  sum(spg_dtmp_cmp.monto) 
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
												   GROUP BY spg_cuentas.spg_cuenta 
												   ORDER BY spg_cuentas.spg_cuenta ASC  
						 				   ) as modificado,
						 				   (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				   sum(spg_cuentas.disminucion) as comprometido
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
												  spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,spg_cuentas.spg_cuenta ORDER BY spg_cuentas.spg_cuenta ASC",$fila);
						 }
						 else
						 {
						  $this->uf_select_data_fila("SELECT 
								  				   sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
								 				   sum(spg_cuentas.disminucion) as disminucion,(
								 				   SELECT  sum(spg_dtmp_cmp.monto) 
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
						 				   ) as modificado,
						 				   (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				   sum(spg_cuentas.disminucion) as comprometido
						 				   FROM spg_cuentas
										   WHERE spg_cuentas.codemp='".$ls_codemp."'
										   AND spg_cuentas.codestpro1='".$ls_codestpro1."'
										   AND spg_cuentas.codestpro2='".$ls_codestpro2."'
										   AND spg_cuentas.spg_cuenta like '".$la_cuenta[0]."%'
										   AND spg_cuentas.status='C'",$fila);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2","");
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $ls_denctaspg = strtoupper($ls_denctaspg);
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							// $this->io_dsreport->insertRow("monto",$fila["monto"]);
							 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
							 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
							 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
							 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
							 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
							 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 $ls_campo1="";
						 $ls_variable1="";
						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1],$li_len,0);
						 $ls_denctaspg =$this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
	                     if (strtoupper($_SESSION["ls_gestor"]) == "INFORMIX")
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
											AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1]."%'
											AND spg_cuentas.status='C'
											AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
											AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
											AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
											AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
											AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
											AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
											AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
											GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,denctaspg",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						  $this->uf_select_data_fila("SELECT 
						  					sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 				    sum(spg_cuentas.disminucion) as disminucion,(
										 				    SELECT sum(spg_dtmp_cmp.monto)
														   	FROM spg_cuentas, spg_dtmp_cmp
														  	WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
															AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
															AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
															AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
															AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
															AND spg_dtmp_cmp.procede = '".$ls_procede."'
															AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
															AND spg_dtmp_cmp.operacion='".$ls_operacion."'
															AND spg_dtmp_cmp.spg_cuenta like '".trim($la_cuenta[0].$la_cuenta[1])."%'
															AND spg_cuentas.status='C'
															AND spg_cuentas.codemp=spg_dtmp_cmp.codemp
															AND spg_cuentas.codestpro1=spg_dtmp_cmp.codestpro1
															AND spg_cuentas.codestpro2=spg_dtmp_cmp.codestpro2
															AND spg_cuentas.codestpro3=spg_dtmp_cmp.codestpro3
															AND spg_cuentas.codestpro4=spg_dtmp_cmp.codestpro4
															AND spg_cuentas.codestpro5=spg_dtmp_cmp.codestpro5
															AND spg_cuentas.spg_cuenta=spg_dtmp_cmp.spg_cuenta
						 				    ) as modificado,
						 				    (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				    sum(spg_cuentas.disminucion) as comprometido
										    FROM spg_cuentas
										    WHERE spg_cuentas.codemp='".$ls_codemp."'
											AND spg_cuentas.codestpro1='".$ls_codestpro1."'
											AND spg_cuentas.codestpro2='".$ls_codestpro2."'
											AND spg_cuentas.codestpro3='".$ls_codestpro3."'
											AND spg_cuentas.spg_cuenta like '".trim($la_cuenta[0].$la_cuenta[1])."%'
											AND spg_cuentas.status='C'
											",$fila);
						 }
						
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2","");
							 $this->io_dsreport->insertRow("subespecifica2","");
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							// $this->io_dsreport->insertRow("monto",$fila["monto"]);
							 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
							 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
							 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
							 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
							 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
							 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}

						 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1].$la_cuenta[2],$li_len,0);
						 $ls_denctaspg = $this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);
						 if (strtoupper($_SESSION["ls_gestor"]) == "INFORMIX")
						 {
						  $this->uf_select_data("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
										    FROM spg_cuentas, spg_dtmp_cmp
										    WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
											AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
											AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
											AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
											GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
													 spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5,denctaspg",$ls_campo1,'monto',$ls_variable1,$ld_monspg);
						 }
						 else
						 {
						   $this->uf_select_data_fila("SELECT 
						   					sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 				    sum(spg_cuentas.disminucion) as disminucion,(
									 				    SELECT  sum(spg_dtmp_cmp.monto)
													   	FROM spg_cuentas, spg_dtmp_cmp
													  	WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
														AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
														AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
														AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
														
									 				    ) as modificado,
						 				    (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 				    sum(spg_cuentas.disminucion) as comprometido
										    FROM spg_cuentas
										    WHERE spg_cuentas.codemp='".$ls_codemp."'
											AND spg_cuentas.codestpro1='".$ls_codestpro1."'
											AND spg_cuentas.codestpro2='".$ls_codestpro2."'
											AND spg_cuentas.codestpro3='".$ls_codestpro3."'
											AND spg_cuentas.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1].$la_cuenta[2]."%'
											AND spg_cuentas.status='C'
											",$fila);
						 }
						 $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'operacion'=>$ls_operacion),'proyecto');
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
							 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
							 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
							 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
							 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
							 $this->io_dsreport->insertRow("subespecifica2",'');
							 $this->io_dsreport->insertRow("proyecto3","");
						     $this->io_dsreport->insertRow("accion3","");
						     $this->io_dsreport->insertRow("ejecutora3","");
						     $this->io_dsreport->insertRow("partida3","");
						     $this->io_dsreport->insertRow("generica3","");
						     $this->io_dsreport->insertRow("especifica3","");
						     $this->io_dsreport->insertRow("subespecifica3","");
							 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
							// $this->io_dsreport->insertRow("monto",$fila["monto"]);
							 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
							 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
							 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
							 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
							 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
							 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
							 $this->io_dsreport->insertRow("operacion",$ls_operacion);
							}
						 $ls_subespaux = $la_cuenta["3"];
						 if ($ls_subespaux!='00')
							{
								
							 $ls_spgcta    = str_pad($la_cuenta[0].$la_cuenta[1].$la_cuenta[2].$la_cuenta[3],$li_len,0);
						 	 $ls_denctaspg = $this->uf_select_denctaspg($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_spgcta);		
							 if (strtoupper($_SESSION["ls_gestor"]) == "INFORMIX")
						     {
							   $this->uf_select_data_fila("SELECT spg_cuentas.denominacion as denctaspg, sum(spg_dtmp_cmp.monto) as monto
								FROM spg_cuentas, spg_dtmp_cmp
							    WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
							    GROUP BY spg_cuentas.denominacion,spg_dtmp_cmp.codestpro1,spg_dtmp_cmp.codestpro2,spg_dtmp_cmp.codestpro3,
								spg_dtmp_cmp.codestpro4,spg_dtmp_cmp.codestpro5",$fila);
							 }
							 else
							 {
							  $this->uf_select_data_fila("SELECT sum(spg_dtmp_cmp.monto) as monto,
							  	sum(spg_cuentas.asignado) as asignado,sum(spg_cuentas.aumento) as aumento,
						 		sum(spg_cuentas.disminucion) as disminucion,(
										 		SELECT  sum(spg_dtmp_cmp.monto)
												 FROM spg_cuentas, spg_dtmp_cmp
											   	 WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
												 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
												 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
												 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
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
						 		) as modificado,
						 		 (sum(spg_cuentas.asignado)+sum(spg_cuentas.aumento)-sum(spg_cuentas.disminucion)) as disponible,
						 		 sum(spg_cuentas.disminucion) as comprometido 
								 FROM spg_cuentas, spg_dtmp_cmp
							     WHERE spg_dtmp_cmp.codemp='".$ls_codemp."'
								 AND spg_dtmp_cmp.codestpro1='".$ls_codestpro1."'
								 AND spg_dtmp_cmp.codestpro2='".$ls_codestpro2."'
								 AND spg_dtmp_cmp.codestpro3='".$ls_codestpro3."'
								 AND spg_dtmp_cmp.comprobante = '".$ls_comprobante."'
								 AND spg_dtmp_cmp.procede = '".$ls_procede."'
								 AND spg_dtmp_cmp.fecha = '".$ld_fecha."'
								 AND spg_dtmp_cmp.operacion='".$ls_operacion."'
								 AND spg_dtmp_cmp.spg_cuenta like '".$la_cuenta[0].$la_cuenta[1].$la_cuenta[2].$la_cuenta[3]."%'
								 AND spg_cuentas.status='C'",$fila);
							 }
							   $li_encontrado = $this->io_dsreport->findValues(array('proyecto2'=>$ls_estpro1_aux,'accion2'=>$ls_estpro2_aux,'ejecutora2'=>$ls_estpro3_aux,'partida2'=>$la_cuenta[0],'generica2'=>$la_cuenta[1],'especifica2'=>$la_cuenta[2],'subespecifica2'=>$la_cuenta[3],'operacion'=>$ls_operacion),'proyecto');
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
									 $this->io_dsreport->insertRow("ejecutora2",$ls_estpro3_aux);
									 $this->io_dsreport->insertRow("partida2",$la_cuenta[0]);
									 $this->io_dsreport->insertRow("generica2",$la_cuenta[1]);
									 $this->io_dsreport->insertRow("especifica2",$la_cuenta[2]);
									 $this->io_dsreport->insertRow("subespecifica2",$la_cuenta[3]);
									 $this->io_dsreport->insertRow("proyecto3","");
									 $this->io_dsreport->insertRow("accion3","");
									 $this->io_dsreport->insertRow("ejecutora3","");
									 $this->io_dsreport->insertRow("partida3","");
									 $this->io_dsreport->insertRow("generica3","");
									 $this->io_dsreport->insertRow("especifica3","");
									 $this->io_dsreport->insertRow("subespecifica3","");
									 $this->io_dsreport->insertRow("denominacion",$ls_denctaspg);
									 $this->io_dsreport->insertRow("monto",$fila["monto"]);
									 $this->io_dsreport->insertRow("asignado",$fila["asignado"]);
									 $this->io_dsreport->insertRow("aumento",$fila["aumento"]);
									 $this->io_dsreport->insertRow("disminucion",$fila["disminucion"]);
									 $this->io_dsreport->insertRow("modificado",$fila["modificado"]);
									 $this->io_dsreport->insertRow("disponible",$fila["disponible"]);
									 $this->io_dsreport->insertRow("comprometido",$fila["comprometido"]);
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
							  $this->io_dsreport->insertRow("proyecto3","");
							  $this->io_dsreport->insertRow("accion3","");
							  $this->io_dsreport->insertRow("ejecutora3","");
							  $this->io_dsreport->insertRow("partida3","");
							  $this->io_dsreport->insertRow("generica3","");
							  $this->io_dsreport->insertRow("especifica3","");
							  $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
						      $this->io_dsreport->insertRow("accion3","");
						      $this->io_dsreport->insertRow("ejecutora3","");
						      $this->io_dsreport->insertRow("partida3","");
						      $this->io_dsreport->insertRow("generica3","");
						      $this->io_dsreport->insertRow("especifica3","");
                              $this->io_dsreport->insertRow("subespecifica3","");
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
							  $this->io_dsreport->insertRow("proyecto3","");
						      $this->io_dsreport->insertRow("accion3","");
                              $this->io_dsreport->insertRow("ejecutora3","");
						      $this->io_dsreport->insertRow("partida3","");
						      $this->io_dsreport->insertRow("generica3","");
                              $this->io_dsreport->insertRow("especifica3","");
						      $this->io_dsreport->insertRow("subespecifica3","");
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
							   $ld_asignado     = $this->io_dsreport->getValue("asignado",$i);
							   $ld_aumento      = $this->io_dsreport->getValue("aumento",$i);
							   $ld_disminucion  = $this->io_dsreport->getValue("disminucion",$i);
							   $ld_modificado   = $this->io_dsreport->getValue("modificado",$i);
							   $ld_comprometido = $this->io_dsreport->getValue("comprometido",$i);
							   $ld_disponible   = $this->io_dsreport->getValue("disponible",$i);
							   
							  if (!empty($ld_monto))
							  {
									$ld_monto = number_format($ld_monto,2,',','.');
							  }
							  if (!empty($ld_asignado))
							  {
									$ld_asignado = number_format($ld_asignado,2,',','.');
							  }
							  if (!empty($ld_aumento))
							  {
									$ld_aumento = number_format($ld_aumento,2,',','.');
							  }
							  if (!empty($ld_disminucion))
							  {
									 $ld_disminucion = number_format($ld_disminucion,2,',','.');
							  }
							  if (!empty( $ld_modificado))
							  {
									 $ld_modificado = number_format($ld_modificado,2,',','.');
							  }
							  if (!empty($ld_comprometido))
							  {
									$ld_comprometido = number_format($ld_comprometido,2,',','.');
							  }
							 if (!empty($ld_disponible))
							 {
									$ld_disponible = number_format($ld_disponible,2,',','.');
							 }
							 $la_data[$i] = array('proyecto'=>$ls_proyecto,
											  'accion'=>$ls_accion,
											  'ejecutora'=>$ls_ejecutora,
											  'partida'=>$ls_partida,
											  'generica'=>$ls_generica,
											  'especifica'=>$ls_especifica,
											  'subespecifica'=>$ls_subespecifica,
											  'denominacion'=>$ls_denominacion,
											  'monto'=>$ld_monto,
							   				  'asignado'=>$ld_asignado,
											  'aumento'=>$ld_aumento,
											  'disminucion'=>$ld_disminucion,
							   				  'modificado'=>$ld_modificado,
											  'comprometido'=>$ld_comprometido,
											  'disponible'=>$ld_disponible
							   			);
							 }

				}
		}
		}
		
		 return $lb_valido;
	}
	
	
	
	
	
	
	



/********************************************************************************************************************************/
	function uf_select_distmensual($as_codemp,$as_procede,$as_comprobante,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private
		//	    Arguments: as_codemp //codigo de empresa
		//	   			   as_procede // Procedencia del documento
		//	   			   as_comprobante // Comprobante
		//	   			   ad_fecha // Fecha del comprobante
		//    Description: Function que devuelve la distribucion mensual
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 19/05/09
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$rs_data="";
		$ld_fecha= $this->io_function->uf_convertirdatetobd($ad_fecha);
		$ls_sql="SELECT * FROM spg_dtmp_mensual".
				" WHERE codemp='".$as_codemp."'".
				"   AND procede='".$as_procede."'".
				"   AND comprobante='".$as_comprobante."'".
				"   AND fecha='".$ld_fecha."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_denominacionspg ERROR->".
									    $this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		return $rs_data;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_loadmodalidad(&$ai_len1,&$ai_len2,&$ai_len3,&$ai_len4,&$ai_len5,&$as_titulo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_loadmodalidad
		//		   Access: public
		//	  Description: Función que obtiene que tipo de modalidad y da las longitudes por accion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación :
		//////////////////////////////////////////////////////////////////////////////
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ai_len1=$_SESSION["la_empresa"]["loncodestpro1"];
		$ai_len2=$_SESSION["la_empresa"]["loncodestpro2"];
		$ai_len3=$_SESSION["la_empresa"]["loncodestpro3"];
		$ai_len4=$_SESSION["la_empresa"]["loncodestpro4"];
		$ai_len5=$_SESSION["la_empresa"]["loncodestpro5"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$as_titulo="Estructura Presupuestaria";
				break;

			case "2": // Modalidad por Programatica
				$as_titulo="Estructura Programatica";
				break;
		}
   	}// end function uf_loadmodalidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatoprogramatica($as_codpro,&$as_programatica)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formatoprogramatica
		//		   Access: public
		//	  Description: Función que obtiene que de acuerdo a la modalidad imprime la programatica
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación :
		//////////////////////////////////////////////////////////////////////////////
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$li_len1=0;
		$li_len2=0;
		$li_len3=0;
		$li_len4=0;
		$li_len5=0;
		$ls_titulo="";
		$this->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_codest1=substr($as_codpro,0,25);
		$ls_codest2=substr($as_codpro,25,25);
		$ls_codest3=substr($as_codpro,50,25);
		$ls_codest4=substr($as_codpro,75,25);
		$ls_codest5=substr($as_codpro,100,25);
		$ls_codest1=substr($ls_codest1,(25-$li_len1),$li_len1);
		$ls_codest2=substr($ls_codest2,(25-$li_len2),$li_len2);
		$ls_codest3=substr($ls_codest3,(25-$li_len3),$li_len3);
		$ls_codest4=substr($ls_codest4,(25-$li_len4),$li_len4);
		$ls_codest5=substr($ls_codest5,(25-$li_len5),$li_len5);
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$as_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3;
				break;

			case "2": // Modalidad por Programa
				$as_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3."-".$ls_codest4."-".$ls_codest5;
				break;
		}
   	}// end function uf_obtenertipo
	//-----------------------------------------------------------------------------------------------------------------------------------
/********************************************************************************************************************************/
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
       		 " 		  sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, ".
			 "        sep_cuentagasto.estcla, sep_cuentagasto.spg_cuenta, ".
             "        SUM(sep_cuentagasto.monto) AS monto ".
			 " FROM   sep_solicitud, sep_cuentagasto, sep_tiposolicitud ".
             " WHERE  sep_solicitud.codemp='".$this->ls_codemp."' AND ".
             "        sep_solicitud.codfuefin='".$as_codfuefindes."' AND ".
             "        sep_solicitud.estsol='C' AND ".
       		 "   	  sep_tiposolicitud.estope='O' AND ".
             "  	  sep_solicitud.codemp=sep_cuentagasto.codemp AND ".
             "        sep_solicitud.numsol=sep_cuentagasto.numsol AND ".
             "        sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol ".
			 " GROUP BY sep_cuentagasto.codestpro1, sep_cuentagasto.codestpro2, sep_cuentagasto.codestpro3, ".
			 "          sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, sep_cuentagasto.estcla, ".
			 "          sep_cuentagasto.spg_cuenta";
	// print $ls_sql;
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
		   $ls_estcla=$row["estcla"];
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ld_monto=$row["monto"];
           $ls_denominacion="";
	       $lb_valido=$this->uf_select_denominacionspg($ls_spg_cuenta,&$ls_denominacion);

		   $this->dts_reporte->insertRow("codfuefin",$ls_codfuefin);
		   //$this->dts_reporte->insertRow("codemp",$ls_codemp);
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
             "        soc_cuentagasto.codestpro2, soc_cuentagasto.codestpro3, soc_cuentagasto.codestpro4, ".
             "        soc_cuentagasto.codestpro5, soc_cuentagasto.estcla, soc_cuentagasto.spg_cuenta, ".
             "        SUM(soc_cuentagasto.monto) AS monto ".
             " FROM   soc_ordencompra, soc_cuentagasto ".
			 " WHERE  soc_ordencompra.codemp='".$this->ls_codemp."' AND ".
             "        soc_ordencompra.codfuefin='".$as_codfuefindes."' AND  ".
       		 "		  soc_ordencompra.estcom='2' AND ".
             "        soc_ordencompra.codemp=soc_cuentagasto.codemp AND ".
		     "        soc_ordencompra.numordcom=soc_cuentagasto.numordcom AND ".
             "        soc_ordencompra.estcondat=soc_cuentagasto.estcondat ".
             " GROUP BY soc_cuentagasto.codestpro1, soc_cuentagasto.codestpro2, soc_cuentagasto.codestpro3, ".
			 "          soc_cuentagasto.codestpro4, soc_cuentagasto.codestpro5, soc_cuentagasto.estcla, ".
			 "          soc_cuentagasto.spg_cuenta ";
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
		   $ls_estcla=$row["estcla"];
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
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
             "        cxp_rd_spg.codestpro, cxp_rd_spg.estcla, cxp_rd_spg.spg_cuenta, SUM(cxp_rd_spg.monto) AS monto ".
             " FROM   cxp_solicitudes, cxp_dt_solicitudes, cxp_documento, cxp_rd_spg ".
             " WHERE  cxp_solicitudes.codemp='".$this->ls_codemp."'  AND ".
             "        cxp_solicitudes.codfuefin='".$as_codfuefindes."' AND ".
       		 "		  cxp_solicitudes.estprosol='C'  AND ".
       		 "        cxp_documento.estcon='1'  AND   ".
             "        cxp_documento.estpre='2'  AND ".
      		 "        cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol AND ".
       		 "    	  cxp_dt_solicitudes.codtipdoc=cxp_documento.codtipdoc AND ".
       		 "        cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ".
             " GROUP BY cxp_rd_spg.codestpro, cxp_rd_spg.estcla, cxp_rd_spg.spg_cuenta";
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
		   $ls_estcla=$row["estcla"];
		   $ls_codestpro1=substr($ls_codestpro,0,25);
		   $ls_codestpro2=substr($ls_codestpro,25,25);
		   $ls_codestpro3=substr($ls_codestpro,50,25);
		   $ls_codestpro4=substr($ls_codestpro,75,25);
	  	   $ls_codestpro5=substr($ls_codestpro,100,25);
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
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
     }//else*/
	 //ASIENTOS PRESUPUESTARIOS DE SCB
	 $ls_sql=" SELECT MAX(scb_movbco.numdoc) AS numdoc, MAX(scb_movbco.codfuefin) AS codfuefin, ".
             "       scb_movbco_spg.codestpro, scb_movbco_spg.estcla, scb_movbco_spg.spg_cuenta, ".
			 "       SUM(scb_movbco.monto) AS monto ".
             " FROM  scb_movbco, scb_movbco_spg ".
             " WHERE scb_movbco.codemp='".$this->ls_codemp."' AND ".
			 "       scb_movbco.codfuefin='".$as_codfuefindes."' AND ".
			 "       scb_movbco.estmov='C' AND  ".
			 "       scb_movbco.codemp=scb_movbco_spg.codemp AND ".
			 "       scb_movbco.codban=scb_movbco_spg.codban AND ".
             "       scb_movbco.ctaban=scb_movbco_spg.ctaban AND ".
			 "       scb_movbco.numdoc=scb_movbco_spg.numdoc AND ".
             "       scb_movbco.codope=scb_movbco_spg.codope AND ".
			 "       scb_movbco.estmov=scb_movbco_spg.estmov     ".
             " GROUP BY scb_movbco_spg.codestpro, scb_movbco_spg.estcla, scb_movbco_spg.spg_cuenta";
	 //print $ls_sql."<br>";
	 $rs_data_scb=$this->io_sql->select($ls_sql);
	 if($rs_data_cxp===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_spg_reporte_select_resumen_fideicomiso CXP ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	 }
     else
     {
		while($row=$this->io_sql->fetch_row($rs_data_scb))
		{
		   $ls_numdoc=$row["numdoc"];
		   $ls_codfuefin=$row["codfuefin"];
		   $ls_spg_cuenta=$row["spg_cuenta"];
		   $ld_monto=$row["monto"];
		   $ls_codestpro=$row["codestpro"];
		   $ls_estcla=$row["estcla"];
		   $ls_codestpro1=substr($ls_codestpro,0,25);
		   $ls_codestpro2=substr($ls_codestpro,25,25);
		   $ls_codestpro3=substr($ls_codestpro,50,25);
		   $ls_codestpro4=substr($ls_codestpro,75,25);
	  	   $ls_codestpro5=substr($ls_codestpro,100,25);
		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
           $ls_denominacion="";
	       $lb_valido=$this->uf_select_denominacionspg($ls_spg_cuenta,&$ls_denominacion);

		   //$this->dts_reporte->insertRow("numdoc",$ls_numdoc);
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
	    $this->io_sql->free_result($rs_data_scb);
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
				"          codfuefin='".$as_codfuefin."'         ".
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

function uf_detalle_ejecucion_financiera($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla, $as_spg_cuenta,
                                         $ad_fecha,&$adec_aumdis,&$adec_precompromiso,&$adec_compromiso,&$adec_causado,&$adec_pagado,
										 &$adec_aumdis01,&$adec_precompromiso01,&$adec_compromiso01,&$adec_causado01,&$adec_pagado01,&$adec_libprecompromiso01,&$adec_libcompromiso01,
										 &$adec_aumdis02,&$adec_precompromiso02,&$adec_compromiso02,&$adec_causado02,&$adec_pagado02,&$adec_libprecompromiso02,&$adec_libcompromiso02,
										 &$adec_aumdis03,&$adec_precompromiso03,&$adec_compromiso03,&$adec_causado03,&$adec_pagado03,&$adec_libprecompromiso03,&$adec_libcompromiso03,
										 &$adec_aumdis04,&$adec_precompromiso04,&$adec_compromiso04,&$adec_causado04,&$adec_pagado04,&$adec_libprecompromiso04,&$adec_libcompromiso04,
										 &$adec_aumdis05,&$adec_precompromiso05,&$adec_compromiso05,&$adec_causado05,&$adec_pagado05,&$adec_libprecompromiso05,&$adec_libcompromiso05,
										 &$adec_aumdis06,&$adec_precompromiso06,&$adec_compromiso06,&$adec_causado06,&$adec_pagado06,&$adec_libprecompromiso06,&$adec_libcompromiso06,
										 &$adec_aumdis07,&$adec_precompromiso07,&$adec_compromiso07,&$adec_causado07,&$adec_pagado07,&$adec_libprecompromiso07,&$adec_libcompromiso07,
										 &$adec_aumdis08,&$adec_precompromiso08,&$adec_compromiso08,&$adec_causado08,&$adec_pagado08,&$adec_libprecompromiso08,&$adec_libcompromiso08,
										 &$adec_aumdis09,&$adec_precompromiso09,&$adec_compromiso09,&$adec_causado09,&$adec_pagado09,&$adec_libprecompromiso09,&$adec_libcompromiso09,
										 &$adec_aumdis10,&$adec_precompromiso10,&$adec_compromiso10,&$adec_causado10,&$adec_pagado10,&$adec_libprecompromiso10,&$adec_libcompromiso10,
										 &$adec_aumdis11,&$adec_precompromiso11,&$adec_compromiso11,&$adec_causado11,&$adec_pagado11,&$adec_libprecompromiso11,&$adec_libcompromiso11,
										 &$adec_aumdis12,&$adec_precompromiso12,&$adec_compromiso12,&$adec_causado12,&$adec_pagado12,&$adec_libprecompromiso12,&$adec_libcompromiso12)
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_detalle_ejecucion_financiera
	//		   Access: private
	//	    Arguments: $as_codestpro1       // Codigo de la Estructura de Presupuesto de Gasto Nivel 1
    //	               $as_codestpro2       // Codigo de la Estructura de Presupuesto de Gasto Nivel 2
	//				   $as_codestpro3       // Codigo de la Estructura de Presupuesto de Gasto Nivel 3
	//				   $as_codestpro4       // Codigo de la Estructura de Presupuesto de Gasto Nivel 4
	//				   $as_codestpro5       // Codigo de la Estructura de Presupuesto de Gasto Nivel 5
	//                 $as_spg_cuenta       // Cuenta de Gasto
	//				   $as_estcla           // Estatus de la Clasificacion
    //                 $ad_fecha            // Fecha tope para los movimientos
	//				   $adec_precompromiso  // Total de Precompromisos
	//				   $adec_compromiso     // Total de Compromisos
	//				   $adec_causado        // Total de Causado
	//				   $adec_pagado         // Total de Pagado
	//    Description: Function que devuelve los saldos totales de los movimientos de gasto par el reporte de Ejecucion Financiera
	//	   Creado Por: Ing. Arnaldo Suárez.
	// Fecha Creación: 09/09/2008
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_spg_cuenta = $this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	if($this->li_estmodest==1)
	{
	 $ls_filtro_estructura =  "	MV.codestpro1 = '".$as_codestpro1."' AND ".
							  "	MV.codestpro2 = '".$as_codestpro2."' AND ".
							  "	MV.codestpro3 = '".$as_codestpro3."' AND ".
			                  "	MV.estcla = '".$as_estcla."' AND 		 ";
	}
	elseif($this->li_estmodest==2)
	{
	 $ls_filtro_estructura =  "	MV.codestpro1 = '".$as_codestpro1."' AND ".
							  "	MV.codestpro2 = '".$as_codestpro2."' AND ".
							  "	MV.codestpro3 = '".$as_codestpro3."' AND ".
							  "	MV.codestpro4 = '".$as_codestpro4."' AND ".
							  "	MV.codestpro5 = '".$as_codestpro5."' AND ".
			                  "	MV.estcla = '".$as_estcla."' AND 		 ";
	}
	$ls_sql=" SELECT  MV.monto, Extract(month from MV.fecha) as mes, OPE.asignar, OPE.aumento, OPE.disminucion,  OPE.precomprometer,  ".
	        "          OPE.comprometer, OPE.causar, OPE.pagar 									 ".
			"     FROM spg_dt_cmp MV,spg_operaciones OPE,spg_cuentas C  						 ".
			"     WHERE  MV.codemp='".$this->ls_codemp."' AND (MV.operacion=OPE.operacion) AND   ".
			"        MV.codestpro1=C.codestpro1 AND MV.codestpro2=C.codestpro2 AND  			 ".
			"        MV.codestpro3=C.codestpro3 AND MV.codestpro4=C.codestpro4 AND  			 ".
			"        MV.codestpro5=C.codestpro5 AND MV.spg_cuenta=C.spg_cuenta AND     			 ".
		    "		 MV.estcla=C.estcla AND 													 ".
			$ls_filtro_estructura.
			"    	 MV.spg_cuenta like '".$ls_spg_cuenta."' AND 									 ".
			"        MV.fecha <= '".$ad_fecha."'													 ";
			//print $ls_sql."<br><br>";
		 $rs_data=$this->io_sql->select($ls_sql);
		 if ($rs_data===false)
		 {
			$lb_valido=false;
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_detalle_ejecucion_financiera  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 }
		 else
		 {
			 while(!$rs_data->EOF)
			 {
				$ldec_monto=$rs_data->fields["monto"];
				$li_asignar=$rs_data->fields["asignar"];
				$li_aumento=$rs_data->fields["aumento"];
				$li_disminucion=$rs_data->fields["disminucion"];
				$li_precomprometer=$rs_data->fields["precomprometer"];
				$li_comprometer=$rs_data->fields["comprometer"];
				$li_causar=$rs_data->fields["causar"];
				$li_pagar=$rs_data->fields["pagar"];
				$li_mes=$rs_data->fields["mes"];
				switch($li_mes)
				{
				 case 1:
								if($li_aumento==1)
								{
								 $adec_aumdis01 = $adec_aumdis01 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis01 = $adec_aumdis01 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso01 = $adec_precompromiso01 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso01 = $adec_libprecompromiso01 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso01 = $adec_compromiso01 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso01 = $adec_libcompromiso01 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado01 = $adec_causado01 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado01 = $adec_pagado01 + $ldec_monto;
								}
								break;

				 case 2:
				 				if($li_aumento==1)
								{
								 $adec_aumdis02 = $adec_aumdis02 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis02 = $adec_aumdis02 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso02 = $adec_precompromiso02 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso02 = $adec_libprecompromiso02 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso02 = $adec_compromiso02 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso02 = $adec_libcompromiso02 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado02 = $adec_causado02 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado02 = $adec_pagado02 + $ldec_monto;
								}
								break;

				 case 3:

				 				if($li_aumento==1)
								{
								 $adec_aumdis03 = $adec_aumdis03 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis03 = $adec_aumdis03 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso03 = $adec_precompromiso03 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso03 = $adec_libprecompromiso03 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								if ($ldec_monto >= 0)
								 {
								  $adec_compromiso03 = $adec_compromiso03 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso03 = $adec_libcompromiso03 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado03 = $adec_causado03 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado03 = $adec_pagado03 + $ldec_monto;
								}
								break;

				 case 4:

				 				if($li_aumento==1)
								{
								 $adec_aumdis04 = $adec_aumdis04 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis04 = $adec_aumdis04 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso04 = $adec_precompromiso04 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso04 = $adec_libprecompromiso04 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso04 = $adec_compromiso04 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso04 = $adec_libcompromiso04 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado04 = $adec_causado04 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado04 = $adec_pagado04 + $ldec_monto;
								}
								break;

				 case 5:
				 				if($li_aumento==1)
								{
								 $adec_aumdis05 = $adec_aumdis05 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis05 = $adec_aumdis05 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso05 = $adec_precompromiso05 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso05 = $adec_libprecompromiso05 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso05 = $adec_compromiso05 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso05 = $adec_libcompromiso05 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado05 = $adec_causado05 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado05 = $adec_pagado05 + $ldec_monto;
								}
								break;

				 case 6:

				 				if($li_aumento==1)
								{
								 $adec_aumdis06 = $adec_aumdis06 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis06 = $adec_aumdis06 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso06 = $adec_precompromiso06 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso06 = $adec_libprecompromiso06 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso06 = $adec_compromiso06 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso06 = $adec_libcompromiso06 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado06 = $adec_causado06 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado06 = $adec_pagado06 + $ldec_monto;
								}
								break;

				 case 7:

				 				if($li_aumento==1)
								{
								 $adec_aumdis07 = $adec_aumdis07 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis07 = $adec_aumdis07 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso07 = $adec_precompromiso07 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso07 = $adec_libprecompromiso07 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso07 = $adec_compromiso07 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso07 = $adec_libcompromiso07 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado07 = $adec_causado07 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado07 = $adec_pagado07 + $ldec_monto;
								}
								break;

				 case 8:

				 				if($li_aumento==1)
								{
								 $adec_aumdis08 = $adec_aumdis08 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis08 = $adec_aumdis08 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso08 = $adec_precompromiso08 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso08 = $adec_libprecompromiso08 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso08 = $adec_compromiso08 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso08 = $adec_libcompromiso08 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado08 = $adec_causado08 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado08 = $adec_pagado08 + $ldec_monto;
								}
								break;

				 case 9:

				 				if($li_aumento==1)
								{
								 $adec_aumdis09 = $adec_aumdis09 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis09 = $adec_aumdis09 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso09 = $adec_precompromiso09 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso09 = $adec_libprecompromiso09 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso09 = $adec_compromiso09 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso09 = $adec_libcompromiso09 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado09 = $adec_causado09 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado09 = $adec_pagado09 + $ldec_monto;
								}
								break;

				 case 10:

				 				if($li_aumento==1)
								{
								 $adec_aumdis10 = $adec_aumdis10 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis10 = $adec_aumdis10 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso10 = $adec_precompromiso10 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso10 = $adec_libprecompromiso10 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso10 = $adec_compromiso10 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso10 = $adec_libcompromiso10 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado10 = $adec_causado10 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado10 = $adec_pagado10+ $ldec_monto;
								}
								break;

				 case 11:

				 				if($li_aumento==1)
								{
								 $adec_aumdis11 = $adec_aumdis11 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis11 = $adec_aumdis11 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso11 = $adec_precompromiso11 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso11 = $adec_libprecompromiso11 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso11 = $adec_compromiso11 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso11 = $adec_libcompromiso11 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado11 = $adec_causado11 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado11 = $adec_pagado11 + $ldec_monto;
								}
								break;

				 case 12:

				 				if($li_aumento==1)
								{
								 $adec_aumdis12 = $adec_aumdis12 + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $adec_aumdis12 = $adec_aumdis12 - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_precompromiso12 = $adec_precompromiso12 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libprecompromiso12 = $adec_libprecompromiso12 + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $adec_compromiso12 = $adec_compromiso12 + $ldec_monto;
								 }
								 else
								 {
								  $adec_libcompromiso12 = $adec_libcompromiso12 + $ldec_monto;
								 }
								}
								if($li_causar==1)
								{
								 $adec_causado12 = $adec_causado12 + $ldec_monto;
								}
								if($li_pagar==1)
								{
								 $adec_pagado12 = $adec_pagado12 + $ldec_monto;
								}
								break;



				}

				/*if($li_aumento==1)
				{
				 $adec_aumdis = $adec_aumdis + $ldec_monto;
				}
				if($li_disminucion==1)
				{
				 $adec_aumdis = $adec_aumdis - $ldec_monto;
				}
				if($li_precomprometer==1)
				{
				 $adec_precompromiso = $adec_precompromiso + $ldec_monto;
				}
				if($li_comprometer==1)
				{
				 $adec_compromiso = $adec_compromiso + $ldec_monto;
				}
				if($li_causar==1)
				{
				 $adec_causado = $adec_causado+ $ldec_monto;
				}
				if($li_pagar==1)
				{
				 $adec_pagado = $adec_pagado+ $ldec_monto;
				}   */
				$lb_valido=true;
				$rs_data->MoveNext();
			 }
		 }
		 return $lb_valido;
}

function uf_spg_reportes_ejecucion_financiera_presupuesto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
	                                                      $as_codestpro5,$as_estclades,$as_codestpro1h,$as_codestpro2h,
	                                                      $as_codestpro3h,$as_codestpro4h,$as_codestpro5h,$as_estclahas,
														  $ad_per01,$ad_per02,$ad_per03,$as_cuentades,$as_cuentahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_ejecucion_financiera_presupuesto
	 //     Argumentos :    as_codestpro1 ... $as_codestpro5 //rango nivel estructura presupuestaria
	 //                     as_estcla // Estatus de la clasificacion
	 //                     as_fecha // Fecha tope para el reporte
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán
	 //                     Ing. Arnaldo Suárez
	 // Fecha Creación :    14/02/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_seguridad="";
	  $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('spg_cuentas',$ls_seguridad);
	  if (($as_cuentades != "")&&($as_cuentahas != ""))
	  {
	   $ls_cuenta = " AND spg_cuentas.spg_cuenta between '".$as_cuentades."' AND '".$as_cuentahas."'";
	  }
	  else
	  {
	   $ls_cuenta = " ";
	  }

	  $ls_estructura="";

	  $ls_aux ="";
	  if($this->li_estmodest==1)
	  {

			if((($as_codestpro1!="**")&&(!empty($as_codestpro1))&&($as_codestpro1 !="0000000000000000000000000"))&&
			   (($as_codestpro1h!="**")&&(!empty($as_codestpro1h))&&($as_codestpro1h!="0000000000000000000000000")))
			{
				 $as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,25);
				 $as_codestpro1h=$this->io_function->uf_cerosizquierda($as_codestpro1h,25);

				 if($as_codestpro1h < $as_codestpro1)
				 {
				  $ls_aux = "";
				  $ls_aux = $as_codestpro1;
				  $as_codestpro1 = $as_codestpro1h;
				  $as_codestpro1h =  $ls_aux;
				  $ls_aux = "";
				  $ls_aux = $as_estclades;
				  $as_estclades = $as_estclahas;
				  $as_estclahas =  $ls_aux;
				 }

				  switch(trim(strtoupper($ls_gestor)))
				  {
				   case "MYSQLT":
				                 $ls_estructura=" AND CONCAT(spg_cuentas.codestpro1,spg_cuentas.estcla) between '".$as_codestpro1.$as_estclades."' AND '".$as_codestpro1h.$as_estclahas."'";
				                 break;

				   case "POSTGRES":
				                   $ls_estructura=" AND spg_cuentas.codestpro1||spg_cuentas.estcla between '".$as_codestpro1.$as_estclades."' AND '".$as_codestpro1h.$as_estclahas."'";
				                   break;
				  }

		    }
			else
			{
				 $ls_estructura = "";
				 $ls_estructura_select="";
			}
			if((($as_codestpro2!="**")&&(!empty($as_codestpro2))&&($as_codestpro2 !="0000000000000000000000000"))&&
			   (($as_codestpro2h!="**")&&(!empty($as_codestpro2h))&&($as_codestpro2h!="0000000000000000000000000")))
			{
				$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,25);
				$as_codestpro2h=$this->io_function->uf_cerosizquierda($as_codestpro2h,25);
				if($as_codestpro2h < $as_codestpro2)
				 {
				  $ls_aux = "";
				  $ls_aux = $as_codestpro2;
				  $as_codestpro2 = $as_codestpro2h;
				  $as_codestpro2h =  $ls_aux;
				 }
				$ls_estructura=$ls_estructura." AND spg_cuentas.codestpro2 between '".$as_codestpro2."' AND '".$as_codestpro2h."'";
	        }
			else
			{
				$ls_estructura=$ls_estructura;
			}
			if((($as_codestpro3!="**")&&(!empty($as_codestpro3))&&($as_codestpro3 !="0000000000000000000000000"))&&
			   (($as_codestpro3h!="**")&&(!empty($as_codestpro3h))&&($as_codestpro3h!="0000000000000000000000000")))
			{
				$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,25);
				$as_codestpro3h=$this->io_function->uf_cerosizquierda($as_codestpro3h,25);
				if($as_codestpro3h < $as_codestpro3)
				 {
				  $ls_aux = "";
				  $ls_aux = $as_codestpro3;
				  $as_codestpro3 = $as_codestpro3h;
				  $as_codestpro3h =  $ls_aux;
				 }
				$ls_estructura=$ls_estructura." AND spg_cuentas.codestpro3 between '".$as_codestpro3."' AND '".$as_codestpro3h."'";
	        }
			else
			{
			    $ls_estructura=$ls_estructura;
			}

	  }
	  else
	  {
			if((($as_codestpro4!="**")&&(!empty($as_codestpro4))&&($as_codestpro4 !="0000000000000000000000000"))&&
			   (($as_codestpro4h!="**")&&(!empty($as_codestpro3h))&&($as_codestpro4h!="0000000000000000000000000")))
			{
			    $as_codestpro4=$this->io_function->uf_cerosizquierda($as_codestpro4,25);
				$as_codestpro4h=$this->io_function->uf_cerosizquierda($as_codestpro4h,25);
				if($as_codestpro4h < $as_codestpro4)
				 {
				  $ls_aux = "";
				  $ls_aux = $as_codestpro4;
				  $as_codestpro4 = $as_codestpro4h;
				  $as_codestpro4h =  $ls_aux;
				 }
			    $ls_estructura=$ls_estructura." AND spg_cuentas.codestpro4 between '".$as_codestpro4."' AND '".$as_codestpro4h."' ";
			}
			else
			{
				$ls_estructura=$ls_estructura;
			}

			if((($as_codestpro5!="**")&&(!empty($as_codestpro5))&&($as_codestpro5 !="0000000000000000000000000"))&&
			   (($as_codestpro5h!="**")&&(!empty($as_codestpro5h))&&($as_codestpro5h!="0000000000000000000000000")))
			{
			  $as_codestpro5=$this->io_function->uf_cerosizquierda($as_codestpro5,25);
			  $as_codestpro5h=$this->io_function->uf_cerosizquierda($as_codestpro5h,25);
			  if($as_codestpro5h < $as_codestpro5)
				 {
				  $ls_aux = "";
				  $ls_aux = $as_codestpro5;
				  $as_codestpro5 = $as_codestpro5h;
				  $as_codestpro5h =  $ls_aux;
				 }
			  $ls_estructura=$ls_estructura." AND spg_cuentas.codestpro5 between '".$as_codestpro5."' AND '".$as_codestpro5h."'";
			}
			else
			{
				$ls_estructura=$ls_estructura;
			}

	  }

	  $ls_sql = "  Select distinct spg_cuentas.spg_cuenta,spg_cuentas.denominacion,spg_cuentas.status, ".
	            "         spg_cuentas.asignado as asignado, ".
				"  spg_cuentas.estcla, ".
			    "			 spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,spg_cuentas.codestpro4,spg_cuentas.codestpro5  ".
                " from  spg_cuentas ".
                " where spg_cuentas.codemp='".$this->ls_codemp."' ".$ls_estructura." ".
				$ls_seguridad." ".
				" ".$ls_cuenta." ".
				" order by spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3, ".
				"         spg_cuentas.codestpro4,spg_cuentas.codestpro5,spg_cuentas.spg_cuenta ";

	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class
		                           MÉTODO->uf_spg_reportes_resumen_ejecucion_financiera_presupuesto
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	}
    else
    {
		while(!$rs_data->EOF)
		{
		   $ls_spg_cuenta=$rs_data->fields["spg_cuenta"];
		   $ls_denominacion=$rs_data->fields["denominacion"];
		   $ld_asignado=$rs_data->fields["asignado"];
		   $ls_codestpro1=$rs_data->fields["codestpro1"];
		   $ls_codestpro2=$rs_data->fields["codestpro2"];
		   $ls_codestpro3=$rs_data->fields["codestpro3"];
		   $ls_codestpro4=$rs_data->fields["codestpro4"];
		   $ls_codestpro5=$rs_data->fields["codestpro5"];
		   $ls_estcla=$rs_data->fields["estcla"];
		   $ls_status=$rs_data->fields["status"];
		   $ld_comper01= 0;
		   $ld_comper02= 0;
		   $ld_comper03= 0;
		   $ld_precomprometido=0;
		   $ld_aumdis=0;
		   $ld_libprecompromiso=0;
		   $ld_libcompromiso=0;
		   $ld_acumcomant = 0;
		   $ld_acumaumdisant = 0;

		   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		   $lb_valido = $this->uf_detalle_ejecucion_financiera_gasto($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
																	 $ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ad_per01,$ad_per02,$ad_per03,
																	 $ld_acumaumdisant,$ld_acumcomant,$ld_comper01,$ld_comper02,$ld_comper03,$ld_precomprometido,
																	 $ld_aumdis,$ld_libprecompromiso,$ld_libcompromiso);
		   if ($lb_valido)
		   {
			   $ld_dispant = $ld_asignado + $ld_acumaumdisant - $ld_acumcomant;

			   $ld_dispact = $ld_asignado + $ld_aumdis -($ld_precomprometido+$ld_libcompromiso);

			   $this->dts_reporte->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte->insertRow("estcla",$ls_estcla);
			   $this->dts_reporte->insertRow("status",$ls_status);
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("dispant",$ld_dispant);
			   $this->dts_reporte->insertRow("dispact",$ld_dispact);
			   $this->dts_reporte->insertRow("periodo01",$ld_comper01);
			   $this->dts_reporte->insertRow("periodo02",$ld_comper02);
			   $this->dts_reporte->insertRow("periodo03",$ld_comper03);
			   $this->dts_reporte->insertRow("modpres",$ld_aumdis);
			   $this->dts_reporte->insertRow("precomprometido",$ld_precomprometido);
			   $this->dts_reporte->insertRow("libprecomprometido",$ld_libprecompromiso);
			   $this->dts_reporte->insertRow("libcomprometido",$ld_libcompromiso);
		    }
		   $lb_valido = true;
		   $rs_data->MoveNext();
		  }//while
	    $this->io_sql->free_result($rs_data);
    }//else
     return  $lb_valido;
   }//fin uf_spg_reportes_comparados_distribucion_mensual_presupuesto

function uf_detalle_ejecucion_financiera_gasto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
											   $as_codestpro5,$as_estcla,$as_spg_cuenta,$as_per01,$as_per02,$as_per03,
											   &$ad_acumaumdisant,&$ad_acumcomant,&$ad_comper01,&$ad_comper02,&$ad_comper03,&$ad_precomprometido,
											   &$ad_aumdis,&$ad_libprecompromiso,&$ad_libcompromiso)
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_detalle_ejecucion_financiera
	//		   Access: private
	//	    Arguments: $as_codestpro1       // Codigo de la Estructura de Presupuesto de Gasto Nivel 1
    //	               $as_codestpro2       // Codigo de la Estructura de Presupuesto de Gasto Nivel 2
	//				   $as_codestpro3       // Codigo de la Estructura de Presupuesto de Gasto Nivel 3
	//				   $as_codestpro4       // Codigo de la Estructura de Presupuesto de Gasto Nivel 4
	//				   $as_codestpro5       // Codigo de la Estructura de Presupuesto de Gasto Nivel 5
	//                 $as_spg_cuenta       // Cuenta de Gasto
	//				   $as_estcla           // Estatus de la Clasificacion
    //                 $ad_fecha            // Fecha tope para los movimientos
	//				   $adec_precompromiso  // Total de Precompromisos
	//				   $adec_compromiso     // Total de Compromisos
	//				   $adec_causado        // Total de Causado
	//				   $adec_pagado         // Total de Pagado
	//    Description: Function que devuelve los saldos totales de los movimientos de gasto par el reporte de Ejecucion Financiera
	//	   Creado Por: Ing. Arnaldo Suárez.
	// Fecha Creación: 09/09/2008
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_spg_cuenta = $this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	if($this->li_estmodest==1)
	{
	 $ls_filtro_estructura =  "	MV.codestpro1 = '".$as_codestpro1."' AND ".
							  "	MV.codestpro2 = '".$as_codestpro2."' AND ".
							  "	MV.codestpro3 = '".$as_codestpro3."' AND ".
			                  "	MV.estcla = '".$as_estcla."' AND 		 ";
	}
	elseif($this->li_estmodest==2)
	{
	 $ls_filtro_estructura =  "	MV.codestpro1 = '".$as_codestpro1."' AND ".
							  "	MV.codestpro2 = '".$as_codestpro2."' AND ".
							  "	MV.codestpro3 = '".$as_codestpro3."' AND ".
							  "	MV.codestpro4 = '".$as_codestpro4."' AND ".
							  "	MV.codestpro5 = '".$as_codestpro5."' AND ".
			                  "	MV.estcla = '".$as_estcla."' AND 		 ";
	}
    if((empty($as_per02))&&(empty($as_per03)))
	{
	 $ad_fecha = $as_per01;
	}
	else
	{
	 if($as_per03 > $as_per02)
	 {
	  $ad_fecha = $as_per03;
	 }
	 else
	 {
	  $ad_fecha = $as_per02;
	 }
	}

	$ls_sql=" SELECT  MV.monto, Extract(month from MV.fecha) as mes, OPE.asignar, OPE.aumento, OPE.disminucion,  OPE.precomprometer,  ".
	        "          OPE.comprometer, OPE.causar, OPE.pagar 									 ".
			"     FROM spg_dt_cmp MV,spg_operaciones OPE,spg_cuentas C  						 ".
			"     WHERE  MV.codemp='".$this->ls_codemp."' AND (MV.operacion=OPE.operacion) AND   ".
			"        MV.codestpro1=C.codestpro1 AND MV.codestpro2=C.codestpro2 AND  			 ".
			"        MV.codestpro3=C.codestpro3 AND MV.codestpro4=C.codestpro4 AND  			 ".
			"        MV.codestpro5=C.codestpro5 AND MV.spg_cuenta=C.spg_cuenta AND     			 ".
		    "		 MV.estcla=C.estcla AND 													 ".
			$ls_filtro_estructura.
			"    	 MV.spg_cuenta like '".$ls_spg_cuenta."' AND 							      ".
			"        Extract(month from MV.fecha) <= ".intval($ad_fecha)." ";
		 $rs_data=$this->io_sql->select($ls_sql);
		 if ($rs_data===false)
		 {
			$lb_valido=false;
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_class  MÉTODO->uf_detalle_ejecucion_financiera  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 }
		 else
		 {
			 while(!$rs_data->EOF)
			 {
				$ldec_monto=$rs_data->fields["monto"];
				$li_asignar=$rs_data->fields["asignar"];
				$li_aumento=$rs_data->fields["aumento"];
				$li_disminucion=$rs_data->fields["disminucion"];
				$li_precomprometer=$rs_data->fields["precomprometer"];
				$li_comprometer=$rs_data->fields["comprometer"];
				$li_mes=$rs_data->fields["mes"];
				switch($li_mes)
				{
				 case $as_per01:
								if($li_aumento==1)
								{
								 $ad_aumdis = $ad_aumdis + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $ad_aumdis = $ad_aumdis - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $ad_precomprometido = $ad_precomprometido + $ldec_monto;
								 }
								 else
								 {
								  $ad_libprecompromiso = $ad_libprecompromiso + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $ad_comper01 = $ad_comper01 + $ldec_monto;
								 }
								 else
								 {
								  $ad_libcompromiso = $ad_libcompromiso + $ldec_monto;
								 }
								}
								break;

				 case $as_per02:
				 				if($li_aumento==1)
								{
								 $ad_aumdis = $ad_aumdis + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $ad_aumdis = $ad_aumdis - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $ad_precomprometido = $ad_precomprometido + $ldec_monto;
								 }
								 else
								 {
								  $ad_libprecompromiso = $ad_libprecompromiso + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $ad_comper02 = $ad_comper02 + $ldec_monto;
								 }
								 else
								 {
								  $ad_libcompromiso = $ad_libcompromiso + $ldec_monto;
								 }
								}
								break;

				 case $as_per03:

				 				if($li_aumento==1)
								{
								 $ad_aumdis = $ad_aumdis + $ldec_monto;
								}
								if($li_disminucion==1)
								{
								 $ad_aumdis = $ad_aumdis - $ldec_monto;
								}
								if($li_precomprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $ad_precomprometido = $ad_precomprometido + $ldec_monto;
								 }
								 else
								 {
								  $ad_libprecompromiso = $ad_libprecompromiso + $ldec_monto;
								 }
								}
								if($li_comprometer==1)
								{
								 if ($ldec_monto >= 0)
								 {
								  $ad_comper03 = $ad_comper03 + $ldec_monto;
								 }
								 else
								 {
								  $ad_libcompromiso = $ad_libcompromiso + $ldec_monto;
								 }
								}
								break;
				}// switch

				if($li_mes < $as_per01)
				{
				 	if($li_aumento==1)
					{
					 $ad_acumaumdisant = $ad_acumaumdisant + $ldec_monto;
					}
					if($li_disminucion==1)
					{
					 $ad_acumaumdisant = $ad_acumaumdisant - $ldec_monto;
					}
					if(($li_precomprometer==1)||($li_comprometer==1))
					{
					 if ($ldec_monto >= 0)
					 {
					  $ad_acumcomant = $ad_acumcomant + $ldec_monto;
					 }
					 else
					 {
					  $ad_acumcomant = $ad_acumcomant - $ldec_monto;
					 }
					}
				}

				$lb_valido=true;
				$rs_data->MoveNext();
			 }// while
		 }
		 return $lb_valido;
}

}//fin de la clase
?>