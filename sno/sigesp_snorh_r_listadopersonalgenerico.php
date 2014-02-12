<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_listadopersonalgenerico.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	unset($io_sno);
	$ld_fecdes="01/01/".date('Y');
	$ld_fechas=date('d/m/Y');
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_filtros(&$ls_criterio,&$ls_criterio2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_filtros
		//		   Access: private 
		//	    Arguments: $ls_criterio // variable que va a cargar los filtros
		//    Description: función que carga los filtros del reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina, $io_funciones;
		$ls_criterio2="0";
		// filtro del código de personal
		$ls_codperdes=$io_fun_nomina->uf_obtenervalor("txtcodperdes","");
		$ls_codperhas=$io_fun_nomina->uf_obtenervalor("txtcodperhas","");
		if(!empty($ls_codperdes))
		{
			$ls_criterio=$ls_criterio." AND sno_personal.codper>='".$ls_codperdes."'";
			if(!empty($ls_codperhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$ls_codperhas."'";
			}
		}
		// filtro de la edad del personal
		$li_edaddesde=$io_fun_nomina->uf_obtenervalor("txtedaddesde","");
		$li_edadhasta=$io_fun_nomina->uf_obtenervalor("txtedadhasta","");
		if(!empty($li_edadhasta))
		{
			if($li_edaddesde==$li_edadhasta)
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$li_resta=$li_edadhasta+1;
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$li_resta year"));
				$ls_criterio= $ls_criterio." AND sno_personal.fecnacper>='".$ld_fecha."' ";
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$li_edaddesde year"));
				$ls_criterio= $ls_criterio." AND sno_personal.fecnacper<'".$ld_fecha."' ";
			}
			else
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$li_edadhasta year"));
				$ls_criterio= $ls_criterio." AND sno_personal.fecnacper>='".$ld_fecha."' ";
			}
		}
		if(!empty($li_edaddesde))
		{
			if($li_edaddesde!=$li_edadhasta)
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$li_edaddesde year"));
				$ls_criterio= $ls_criterio." AND sno_personal.fecnacper<='".$ld_fecha."' ";
			}
		}
		// filtro de la fecha de ingreso a la inttitución del personal
		$ld_fecinginsdesde=$io_fun_nomina->uf_obtenervalor("txtfecinginsdesde","");
		$ld_fecinginshasta=$io_fun_nomina->uf_obtenervalor("txtfecinginshasta","");
		if((!empty($ld_fecinginsdesde))&&($ld_fecinginsdesde!="1900-01-01"))
		{
			$ls_criterio=$ls_criterio." AND sno_personal.fecingper>='".$io_funciones->uf_convertirdatetobd($ld_fecinginsdesde)."'";
			if((!empty($ld_fecinginshasta))&&($ld_fecinginshasta!="1900-01-01"))
			{
				$ls_criterio= $ls_criterio." AND sno_personal.fecingper<='".$io_funciones->uf_convertirdatetobd($ld_fecinginshasta)."'";
			}
		}
		// filtro de los estátus del personal
		$ls_activo=$io_fun_nomina->uf_obtenervalor("chkactivo","");
		$ls_egresado=$io_fun_nomina->uf_obtenervalor("chkegresado","");
		$ls_cauegrper=$io_fun_nomina->uf_obtenervalor("cmbcauegrper","");
		$lb_ok=false;
		if(!empty($ls_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($ls_egresado))
		{
			$ls_causaegreso="";
			if(!empty($ls_cauegrper))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$ls_cauegrper."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		// filtro del sexo del personal
		$ls_masculino=$io_fun_nomina->uf_obtenervalor("chkmasculino","");
		$ls_femenino=$io_fun_nomina->uf_obtenervalor("chkfemenino","");
		$lb_ok=false;
		if(!empty($ls_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_ok=true;
		}
		if(!empty($ls_femenino))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		// filtro de la nacionalidad del personal
		$ls_venezolano=$io_fun_nomina->uf_obtenervalor("chkvenezolano","");
		$ls_extranjero=$io_fun_nomina->uf_obtenervalor("chkextranjero","");
		$lb_ok=false;
		if(!empty($ls_venezolano))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.nacper='V' ";
			$lb_ok=true;
		}
		if(!empty($ls_extranjero))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.nacper='E' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.nacper='E' ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		// filtro del estado civil del personal
		$ls_soltero=$io_fun_nomina->uf_obtenervalor("chksoltero","");
		$ls_casado=$io_fun_nomina->uf_obtenervalor("chkcasado","");
		$ls_divorciado=$io_fun_nomina->uf_obtenervalor("chkdivorciado","");
		$ls_viudo=$io_fun_nomina->uf_obtenervalor("chkviudo","");
		$ls_concubino=$io_fun_nomina->uf_obtenervalor("chkconcubino","");
		$lb_ok=false;
		if(!empty($ls_soltero))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.edocivper='S' ";
			$lb_ok=true;
		}
		if(!empty($ls_casado))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.edocivper='C' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.edocivper='C' ";
				$lb_ok=true;
			}
		}
		if(!empty($ls_divorciado))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.edocivper='D' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.edocivper='D' ";
				$lb_ok=true;
			}
		}
		if(!empty($ls_viudo))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.edocivper='V' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.edocivper='V' ";
				$lb_ok=true;
			}
		}
		if(!empty($ls_concubino))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.edocivper='K' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.edocivper='K' ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		// filtro del código de nómina de personal
		$ls_codnomdes=$io_fun_nomina->uf_obtenervalor("txtcodnomdes","");
		$ls_codnomhas=$io_fun_nomina->uf_obtenervalor("txtcodnomhas","");
		if(!empty($ls_codnomdes))
		{
			$ls_criterio=$ls_criterio." AND sno_personalnomina.codnom>='".$ls_codnomdes."'";
			if(!empty($ls_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$ls_codnomhas."'";
			}		
			// filtro de la fecha de ingreso a la nómina del personal
			$ld_fecingnomdesde=$io_fun_nomina->uf_obtenervalor("txtfecingnomdesde","");
			$ld_fecingnomhasta=$io_fun_nomina->uf_obtenervalor("txtfecingnomhasta","");
			if((!empty($ld_fecingnomdesde))&&($ld_fecingnomdesde!="1900-01-01"))
			{
				$ls_criterio=$ls_criterio." AND sno_personalnomina.fecingper>='".$io_funciones->uf_convertirdatetobd($ld_fecingnomdesde)."'";
				if((!empty($ld_fecingnomhasta))&&($ld_fecingnomhasta!="1900-01-01"))
				{
					$ls_criterio= $ls_criterio." AND sno_personalnomina.fecingper<='".$io_funciones->uf_convertirdatetobd($ld_fecingnomhasta)."'";
				}
			}
			// filtro de culminación de contrato del personal
			$ld_fecculcontrdes=$io_fun_nomina->uf_obtenervalor("txtfecculcontrdes","");
			$ld_fecculcontrhas=$io_fun_nomina->uf_obtenervalor("txtfecculcontrhas","");
			if((!empty($ld_fecculcontrdes))&&($ld_fecculcontrdes!="1900-01-01"))
			{
				$ls_criterio=$ls_criterio." AND sno_personalnomina.fecculcontr>='".$io_funciones->uf_convertirdatetobd($ld_fecculcontrdes)."'";
				if((!empty($ld_fecculcontrhas))&&($ld_fecculcontrhas!="1900-01-01"))
				{
					$ls_criterio= $ls_criterio." AND sno_personalnomina.fecculcontr<='".$io_funciones->uf_convertirdatetobd($ld_fecculcontrhas)."'";
				}
			}
			// filtro de estatus del personal en la nómina
			$ls_activono=$io_fun_nomina->uf_obtenervalor("chkactivono","");
			$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor("chkvacacionesno","");
			$ls_egresadono=$io_fun_nomina->uf_obtenervalor("chkegresadono","");
			$ls_suspendidono=$io_fun_nomina->uf_obtenervalor("chksuspendidono","");
			$lb_ok=false;
			if(!empty($ls_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($ls_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($ls_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($ls_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}			
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
			}
		}	
		else
		{
			$ls_criterio2="1";
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_campos(&$li_total,&$ls_campo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_campos
		//		   Access: private 
		//	    Arguments: li_total // Total de campos
		//				   ls_campo // string de campos 
		//    Description: función que carga los campos que se van a mostrar en el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina, $li_tope;
		
		$la_campos=$io_fun_nomina->uf_obtenervalor("lstcampossele","");
		$li_total=0;			
		$ls_codnomdes=$io_fun_nomina->uf_obtenervalor("txtcodnomdes","");
		$ls_codnomhas=$io_fun_nomina->uf_obtenervalor("txtcodnomhas","");
		
		if(!empty($la_campos))
		{
			$li_total=count($la_campos);
		}
		for($li_i=0;($li_i<$li_total);$li_i++) 
		{ 
		   if ((empty($ls_codnomdes))&&(empty($ls_codnomhas)))
		   {  
		    	$var1="sno_personalnomina";	
				$var2=substr($la_campos[$li_i],0,18);
				$var3="sno_nomina";	
				$var4=substr($la_campos[$li_i],0,10);
				$var5="sno_dedicacion";	
				$var6=substr($la_campos[$li_i],0,14);
				$var7="sno_cargo";	
				$var8=substr($la_campos[$li_i],0,9);
				$var9="sno_asignacioncargo";	
				$var10=substr($la_campos[$li_i],0,19);
				$var11="sno_tipopersonal";	
				$var12=substr($la_campos[$li_i],0,16);
				$var13="sno_ubicacionfisica";	
				$var14=substr($la_campos[$li_i],0,19);
				$var15="sno_unidadadmin";	
				$var16=substr($la_campos[$li_i],0,15);
				$var17="srh_gerencia";	
				$var18=substr($la_campos[$li_i],0,15);
				$var19="scb_banco";	
				$var20=substr($la_campos[$li_i],0,9);
				if (($var1!=$var2)&&($var3!=$var4)&&($var5!=$var6)&&($var7!=$var8)&&($var9!=$var10)&&($var11!=$var12)&&($var13!=$var14)&&($var15!=$var16)&&($var17!=$var18)&&($var19!=$var20))
				{	    	
					$ls_campo=$ls_campo." (".$la_campos[$li_i].") AS campo".$li_i.",";
				}
				else
				{
					$ls_campo=$ls_campo." ('') AS campo".$li_i.",";
				}				          
		   }
		   else
		   {   
				$ls_campo=$ls_campo." (".$la_campos[$li_i].") AS campo".$li_i.",";
		   }		  	 		 		 		 
		}
		for($li_i=$li_total;($li_i<$li_tope);$li_i++) 
		{
			$ls_campo=$ls_campo." '' AS campo".$li_i.",";
		}			
		$ls_campo=substr($ls_campo,0,strlen($ls_campo)-1);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_configuracion_campos(&$la_titulos)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_campos
		//		   Access: private 
		//	    Arguments: li_total // Total de campos
		//				   ls_campo // string de campos 
		//    Description: función que carga los campos que se van a mostrar en el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina, $li_tope;
		
		$la_campos=$io_fun_nomina->uf_obtenervalor("lstcampossele","");
		$li_total=0;
		if(!empty($la_campos))
		{
			$li_total=count($la_campos);
		}
		for($li_i=0;($li_i<$li_total);$li_i++) 
		{
			switch($la_campos[$li_i])
			{
				case "sno_personal.codper":
					$la_titulos[$li_i]["campo"]="sno_personal.codper";
					$la_titulos[$li_i]["titulo"]="Código";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_personal.cedper":
					$la_titulos[$li_i]["campo"]="sno_personal.cedper";
					$la_titulos[$li_i]["titulo"]="Cédula";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_personal.nomper":
					$la_titulos[$li_i]["campo"]="sno_personal.nomper";
					$la_titulos[$li_i]["titulo"]="Nombres";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				case "sno_personal.apeper":
					$la_titulos[$li_i]["campo"]="sno_personal.apeper";
					$la_titulos[$li_i]["titulo"]="Apellido";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				case "sno_personal.fecnacper":
					$la_titulos[$li_i]["campo"]="sno_personal.fecnacper";
					$la_titulos[$li_i]["titulo"]="Fecha de Nacimiento";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="date";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_personal.nacper":
					$la_titulos[$li_i]["campo"]="sno_personal.nacper";
					$la_titulos[$li_i]["titulo"]="Nacionalidad";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.sexper":
					$la_titulos[$li_i]["campo"]="sno_personal.sexper";
					$la_titulos[$li_i]["titulo"]="Género";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_personal.edocivper":
					$la_titulos[$li_i]["campo"]="sno_personal.edocivper";
					$la_titulos[$li_i]["titulo"]="Estado Civil";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_nomina.desnom":
					$la_titulos[$li_i]["campo"]="sno_nomina.desnom";
					$la_titulos[$li_i]["titulo"]="Nómina";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				case "sno_personalnomina.sueper":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.sueper";
					$la_titulos[$li_i]["titulo"]="Sueldo Bs.";
					$la_titulos[$li_i]["alineacion"]="right";
					$la_titulos[$li_i]["tipo"]="double";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personalnomina.sueintper":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.sueintper";
					if ($ls_sueint=="")
					{
						$tiutlo="Sueldo Integral Bs.";
					}
					else
					{
						$tiutlo=$ls_sueint." Bs.";
					}				
					$la_titulos[$li_i]["titulo"]=$tiutlo;
					$la_titulos[$li_i]["alineacion"]="right";
					$la_titulos[$li_i]["tipo"]="double";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personalnomina.sueproper":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.sueproper";
					$la_titulos[$li_i]["titulo"]="Sueldo Promedio Bs.";
					$la_titulos[$li_i]["alineacion"]="right";
					$la_titulos[$li_i]["tipo"]="double";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.nivacaper":
					$la_titulos[$li_i]["campo"]="sno_personal.nivacaper";
					$la_titulos[$li_i]["titulo"]="Nivel Académico";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.dirper":
					$la_titulos[$li_i]["campo"]="sno_personal.dirper";
					$la_titulos[$li_i]["titulo"]="Dirección";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				case "sno_personal.telhabper":
					$la_titulos[$li_i]["campo"]="sno_personal.telhabper";
					$la_titulos[$li_i]["titulo"]="Teléfono de Habitación";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.telmovper":
					$la_titulos[$li_i]["campo"]="sno_personal.telmovper";
					$la_titulos[$li_i]["titulo"]="Teléfono Móvil";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_profesion.despro":
					$la_titulos[$li_i]["campo"]="sno_profesion.despro";
					$la_titulos[$li_i]["titulo"]="Profesión";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				case "sno_personal.numhijper":
					$la_titulos[$li_i]["campo"]="sno_personal.numhijper";
					$la_titulos[$li_i]["titulo"]="Nro de Hijos";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="integer";
					$la_titulos[$li_i]["ancho"]=10;
					break;
				case "sno_personal.fecingadmpubper":
					$la_titulos[$li_i]["campo"]="sno_personal.fecingadmpubper";
					$la_titulos[$li_i]["titulo"]="Fecha de Ingreso a la Administración Pública";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="date";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.fecingper":
					$la_titulos[$li_i]["campo"]="sno_personal.fecingper";
					$la_titulos[$li_i]["titulo"]="Fecha de Ingreso a la Institución";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="date";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.anoservpreper":
					$la_titulos[$li_i]["campo"]="sno_personal.anoservpreper";
					$la_titulos[$li_i]["titulo"]="Años de Servicio Previo";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="integer";
					$la_titulos[$li_i]["ancho"]=10;
					break;
				case "sno_personal.fecegrper":
					$la_titulos[$li_i]["campo"]="sno_personal.fecegrper";
					$la_titulos[$li_i]["titulo"]="Fecha de Egreso";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="date";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_personal.cauegrper":
					$la_titulos[$li_i]["campo"]="sno_personal.cauegrper";
					$la_titulos[$li_i]["titulo"]="Causa de Egreso";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.estper":
					$la_titulos[$li_i]["campo"]="sno_personal.estper";
					$la_titulos[$li_i]["titulo"]="Estatus del Personal";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personal.coreleper":
					$la_titulos[$li_i]["campo"]="sno_personal.coreleper";
					$la_titulos[$li_i]["titulo"]="Correo Electrónico";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=30;
					break;
				case "sno_cargo.descar":
					$la_titulos[$li_i]["campo"]="sno_cargo.descar";
					$la_titulos[$li_i]["titulo"]="Cargo";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=30;
					break;
				case "sno_asignacioncargo.denasicar":
					$la_titulos[$li_i]["campo"]="sno_asignacioncargo.denasicar";
					$la_titulos[$li_i]["titulo"]="Asignación de Cargo";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=30;
					break;
				case "sno_personalnomina.staper":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.staper";
					$la_titulos[$li_i]["titulo"]="Estatus del Personal en Nómina";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=20;
					break;
				case "sno_personalnomina.fecculcontr":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.fecculcontr";
					$la_titulos[$li_i]["titulo"]="Fecha de Culminación de Contrato";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="date";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_dedicacion.desded":
					$la_titulos[$li_i]["campo"]="sno_dedicacion.desded";
					$la_titulos[$li_i]["titulo"]="Dedicación";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=30;
					break;
				case "sno_tipopersonal.destipper":
					$la_titulos[$li_i]["campo"]="sno_tipopersonal.destipper";
					$la_titulos[$li_i]["titulo"]="Tipo de Personal (Dedicación)";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=30;
					break;
					
				case "seguridad.dentippersss":
					$la_titulos[$li_i]["campo"]="seguridad.dentippersss";
					$la_titulos[$li_i]["titulo"]="Tipo de Personal (Seguridad)";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=30;
					break;
					
				case "sno_ubicacionfisica.desubifis":
					$la_titulos[$li_i]["campo"]="sno_ubicacionfisica.desubifis";
					$la_titulos[$li_i]["titulo"]="Ubicación Física";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;				
				case "sno_personalnomina.codcueban":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.codcueban";
					$la_titulos[$li_i]["titulo"]="Cuenta Bancaria";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=25;
					break;
				case "sno_unidadadmin.desuniadm":
					$la_titulos[$li_i]["campo"]="sno_unidadadmin.desuniadm";
					$la_titulos[$li_i]["titulo"]="Unidad Administrativa";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				case "sno_personalnomina.codunirac":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.codunirac";
					$la_titulos[$li_i]["titulo"]="Código Único RAC";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
					
				case "sno_personalnomina.codgra":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.codgrad";
					$la_titulos[$li_i]["titulo"]="Grado";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
				case "sno_personalnomina.codpas":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.codpas";
					$la_titulos[$li_i]["titulo"]="Paso";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=15;
					break;
					
				case "sno_personal.codorg":
					$la_titulos[$li_i]["campo"]="sno_personal.codorg";
					$la_titulos[$li_i]["titulo"]="Ubicación Física Organigrama";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=35;
					break;
					
				case "srh_gerencia.denger":
					$la_titulos[$li_i]["campo"]="srh_gerencia.denger";
					$la_titulos[$li_i]["titulo"]="Gerencia";
					$la_titulos[$li_i]["alineacion"]="left";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;
				
				case "sno_personalnomina.tipcuebanper":
					$la_titulos[$li_i]["campo"]="sno_personalnomina.tipcuebanper";
					$la_titulos[$li_i]["titulo"]="Tipo de Cuenta";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=10;
					break;
				
				case "scb_banco.nomban":
					$la_titulos[$li_i]["campo"]="scb_banco.nomban";
					$la_titulos[$li_i]["titulo"]="Banco";
					$la_titulos[$li_i]["alineacion"]="center";
					$la_titulos[$li_i]["tipo"]="string";
					$la_titulos[$li_i]["ancho"]=40;
					break;			
			}
		}
		for($li_i=$li_total;($li_i<$li_tope);$li_i++) 
		{
			$la_titulos[$li_i]["campo"]="";
			$la_titulos[$li_i]["titulo"]="";
			$la_titulos[$li_i]["alineacion"]="";
			$la_titulos[$li_i]["tipo"]="";
			$la_titulos[$li_i]["ancho"]=30;
		}			
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Reporte Listado de Personal Gen&eacute;rico</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	switch ($ls_operacion) 
	{
		case "REPORTAR":
			$_SESSION["ls_titulo"]=$io_fun_nomina->uf_obtenervalor("txttituloreporte","LISTADO DE PERSONAL");
			$ls_criterio="";
			$ls_criterio2="0";
			$li_tope=$_POST["tope"];
			$ls_reporte=$_POST["reporte"];
			uf_load_filtros(&$ls_criterio,$ls_criterio2);
			$_SESSION["ls_criterio"]=$ls_criterio;
			$_SESSION["ls_criterio2"]=$ls_criterio2;
			$ls_campo="";
			uf_load_campos(&$li_total,&$ls_campo);
			$_SESSION["li_total"]=$li_total-1;
			$_SESSION["ls_campo"]=$ls_campo;
			$la_titulos="";
			uf_load_configuracion_campos($la_titulos);
			$_SESSION["la_titulos"]=$la_titulos;
			switch($li_total)
			{
				case ($li_total<=4):
					$_SESSION["ls_pagina"]='LETTER';
					$_SESSION["ls_orientacion"]='portrait';
					$_SESSION["ls_tiporeporte"]='1';
					break;
			
				case (($li_total>4)&&(($li_total<=7))):
					$_SESSION["ls_pagina"]='LETTER';
					$_SESSION["ls_orientacion"]='landscape';
					$_SESSION["ls_tiporeporte"]='2';
					break;

				case ($li_total>7):
					$_SESSION["ls_pagina"]='LEGAL';
					$_SESSION["ls_orientacion"]='landscape';
					$_SESSION["ls_tiporeporte"]='3';
					break;
			}
			switch($ls_reporte)
			{
				case "PDF":
					?>
						<script language="javascript">
							pagina="reportes/sigesp_snorh_rpp_listadopersonalgenerico.php";
							window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
						</script>
					<?
					break;
				case "EXCEL":
					?>
						<script language="javascript">
							pagina="reportes/sigesp_snorh_rpp_listadopersonalgenerico_excel.php";
							window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
						</script>
					<?
					break;
			}
			break;
	}
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>	 </td>
  </tr>
  <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	   print ('<tr>');
	   print ('<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>' );
	   print ('</tr>');
	}
	
	
  ?>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_openexcel();"></a><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
     <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	    print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	   
	}
	else
	{
	 print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: close();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	
	}
	
  ?>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="5" class="titulo-ventana">Reporte Listado de Personal Gen&eacute;rico </td>
        </tr>
        <tr>
          <td height="20"><div align="right">T&iacute;tulo del Reporte </div></td>
          <td height="20" colspan="4">
            <input name="txttituloreporte" type="text" id="txttituloreporte" size="60" maxlength="100">          </td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="5">Datos del Personal </td>
          </tr>
        <tr>
          <td height="20" colspan="5" class="titulo-celdanew">Rango del Personal </td>
          </tr>
		<tr>
          <td width="141" height="22"><div align="right"> Desde </div></td>
          <td width="119"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="104"><div align="right">Hasta </div></td>
          <td colspan="2"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
		<tr>
		  <td height="22" colspan="5" class="titulo-celdanew">Rango de Edad del Personal </td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Desde</div></td>
		  <td><div align="left">
		    <input name="txtedaddesde" type="text" id="txtedaddesde" size="10" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
		    </div></td>
		  <td><div align="right">Hasta</div></td>
		  <td colspan="2"><input name="txtedadhasta" type="text" id="txtedadhasta" size="10" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);"></td>
		  </tr>
		<tr>
		  <td height="22" colspan="5" class="titulo-celdanew">Rango de Fecha de Ingreso a la Instituci&oacute;n </td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Desde</div></td>
		  <td><div align="left">
		    <input name="txtfecinginsdesde" type="text" id="txtfecinginsdesde" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true">
		  </div></td>
		  <td><div align="right">Hasta</div></td>
		  <td colspan="2"><input name="txtfecinginshasta" type="text" id="txtfecinginshasta" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true"></td>
		  </tr>
		<tr>
          <td height="22" colspan="5" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Estatus  </div></td>
          <td><div align="right">Activo
              <input name="chkactivo" type="checkbox" class="sin-borde" id="chkactivo" value="1" checked>
          </div></td>
          <td>
            <div align="right">Egresado
              <input name="chkegresado" type="checkbox" class="sin-borde" id="chkegresado" value="1">
            </div></td>
          <td colspan="2"><div align="left">
            <select name="cmbcauegrper" id="select">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="N">Ninguno</option>
              <option value="D">Despido</option>
              <option value="P">Pensionado</option>
              <option value="R">Renuncia</option>
              <option value="T">Traslado</option>
              <option value="J">Jubilado</option>
              <option value="F">Fallecido</option>
            </select>
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">G&eacute;nero</div></td>
          <td><label>
            <div align="right">Masculino
              <input name="chkmasculino" type="checkbox" class="sin-borde" id="chkmasculino" value="1" checked>
            </div>
            </label></td>
          <td><label>
            <div align="right">Femenino
              <input name="chkfemenino" type="checkbox" class="sin-borde" id="chkfemenino" value="1" checked>
            </div>
            </label></td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nacionalidad</div></td>
          <td><label>
            <div align="right">Venezolano
              <input name="chkvenezolano" type="checkbox" class="sin-borde" id="chkvenezolano" value="V" checked>
            </div>
            </label></td>
          <td><div align="right">Extranjero
            <label>
            <input name="chkextranjero" type="checkbox" class="sin-borde" id="chkextranjero" value="E" checked>
            </label>
</div></td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Civil </div></td>
          <td><label>
            <div align="right">Soltero
              <input name="chksoltero" type="checkbox" class="sin-borde" id="chksoltero" value="S" checked>
            </div>
            </label></td>
          <td><div align="right">Casado
            <input name="chkcasado" type="checkbox" class="sin-borde" id="chkcasado" value="C" checked>
          </div></td>
          <td><div align="right">Divociado 
            <input name="chkdivorciado" type="checkbox" class="sin-borde" id="chkdivorciado" value="D" checked>
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><div align="right">Viudo 
            <input name="chkviudo" type="checkbox" class="sin-borde" id="chkviudo" value="V" checked>
          </div></td>
          <td><div align="right">Concubino
            <input name="chkconcubino" type="checkbox" class="sin-borde" id="chkconcubino" value="K" checked>
          </div></td>
          <td colspan="2">&nbsp;</td>
        </tr>
		  <tr>
		    <td height="20">&nbsp;</td>
		    <td height="20" colspan="4">&nbsp;</td>
	      </tr>
		  <tr>
		    <td height="20" colspan="5" class="titulo-ventana">Datos del Personal en N&oacute;mina </td>
	      </tr>
        <tr>
          <td height="20" colspan="5" class="titulo-celdanew">Intervalo de N&oacute;mina </td>
          </tr>
        <tr>
          <td width="141" height="22"><div align="right"> Desde </div></td>
          <td width="119"><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominadesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="104"><div align="right">Hasta </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodnomhas" type="text" id="txtcodnomhas" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominahasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="6" class="titulo-celdanew">Rango de Fecha de Ingreso a la N&oacute;mina </td>
          </tr>
        <tr>
          <td height="22"><div align="right">Desde</div></td>
          <td><div align="left">
            <input name="txtfecingnomdesde" type="text" id="txtfecingnomdesde" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true">
          </div></td>
          <td><div align="right">Hasta</div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecingnomhasta" type="text" id="txtfecingnomhasta" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true">
          </div></td>
        </tr>

        
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">Intervalo de Fecha de Culminaci&oacute;n de Contrato </td>
          </tr>
        <tr>
          <td height="22"><div align="right">Desde</div></td>
          <td><div align="left">
            <input name="txtfecculcontrdes" type="text" id="txtfecculcontrdes" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true">
          </div></td>
          <td><div align="right">Hasta</div></td>
          <td colspan="2"><div align="left">
            <input name="txtfecculcontrhas" type="text" id="txtfecculcontrhas" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true">
          </div></td>
        </tr>
        
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">Estatus del Personal en N&oacute;mina </td>
          </tr>
        <tr>
          <td height="22"><div align="right">Activo
            <input name="chkactivono" type="checkbox" class="sin-borde" id="chkactivono" value="1" checked>
          </div></td>
          <td><div align="right">Vacaciones
            <input name="chkvacacionesno" type="checkbox" class="sin-borde" id="chkvacacionesno" value="1" checked>
          </div></td>
          <td><div align="right">Egresado
            <input name="chkegresadono" type="checkbox" class="sin-borde" id="chkegresadono" value="1" checked>
          </div></td>
          <td width="129"><div align="right">Suspendido
            <input name="chksuspendidono" type="checkbox" class="sin-borde" id="chksuspendidono" value="1" checked>
          </div></td>
          <td width="45">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22" colspan="5"><div align="right"></div>            <div align="right">
              <label></label>
              </div></td>
          </tr>
        <tr>
          <td height="20" colspan="5" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Campos a Reportar </div></td>
          </tr>
        <tr>
          <td height="22" colspan="5"><div align="center">
            <table width="500" border="0">
              <tr>
                <td width="201">&nbsp;</td>
                <td width="57"><div align="center"></div></td>
                <td width="228">&nbsp;</td>
              </tr>
              <tr>
                <td rowspan="6">
					<div align="center">
					  <select name='lstcamposdisp[]' id='lstcamposdisp' size='16' style='width:200px' multiple>
                        <option value='sno_personal.codper'>Código
                          <option value='sno_personal.cedper'>Cédula
                          <option value='sno_personal.nomper'>Nombres
                          <option value='sno_personal.apeper'>Apellidos
                          <option value='sno_personal.fecnacper'>Fecha de Nacimiento
                          <option value='sno_personal.nacper'>Nacionalidad
                          <option value='sno_personal.dirper'>Dirección
                          <option value='sno_personal.telhabper'>Telefono Habitación
                          <option value='sno_personal.telmovper'>Telefono Movil
                          <option value='sno_personal.sexper'>Género
                          <option value='sno_personal.edocivper'>Estado Civil
                          <option value='sno_profesion.despro'>Profesion
                          <option value='sno_personal.nivacaper'>Nivel Academico
                          <option value='sno_personal.numhijper'>Número de Hijos
                          <option value='sno_personal.fecingadmpubper'>Fecha de Ingreso a la Administración Pública
                          <option value='sno_personal.fecingper'>Fecha de Ingreso a la Institución
                          <option value='sno_personal.anoservpreper'>Años de Servicio Previo
                          <option value='sno_personal.fecegrper'>Fecha de Egreso
                          <option value='sno_personal.cauegrper'>Causa de Egreso
                          <option value='sno_personal.estper'>Estatus del Personal
                          <option value='sno_personal.coreleper'>Correo Electrónico
                          <option value='sno_nomina.desnom'>Nómina
                          <option value='sno_personalnomina.sueper'>Sueldo Bs.
                          <option value='sno_personalnomina.sueintper'><?php if ($ls_sueint=="") {print "Sueldo Integral Bs.";}
																			 else { print $ls_sueint." Bs.";}  ?>
					   
                          <option value='sno_personalnomina.sueproper'>Sueldo Promedio Bs.                        
                          <option value='sno_cargo.descar'>Cargo
                          <option value='sno_asignacioncargo.denasicar'>Asignación de Cargo
                          <option value='sno_personalnomina.staper'>Estatus del Personal en Nómina
                          <option value='sno_personalnomina.fecculcontr'>Fecha de Culminación de Contrato
                          <option value='sno_dedicacion.desded'>Dedicación
                          <option value='sno_tipopersonal.destipper'>Tipo de Personal (Dedicación)
						  <option value='seguridad.dentippersss'>Tipo de Personal (Seguridad)
                          <option value='sno_ubicacionfisica.desubifis'>Ubicación Física
                          <option value='sno_personalnomina.codcueban'>Cuenta Bancaria
						  <option value='sno_personalnomina.tipcuebanper'>Tipo de Cuenta Bancaria
						  <option value='scb_banco.nomban'>Banco
                          <option value='sno_unidadadmin.desuniadm'>Unidad Administrativa
						  <option value='srh_gerencia.denger'>Gerencia
                          <option value='sno_personalnomina.codunirac'>Código Único de RAC
						  <option value='sno_personalnomina.codgra'>Grado
						  <option value='sno_personalnomina.codpas'>Paso
						  <option value='sno_personal.codorg'>Ubicaci&oacute;n F&iacute;sica seg&uacute;n Organigrama
                        </select>
					</div></td>
                <td><div align="center"></div></td>
                <td rowspan="6">
					<div align="center">
					  <select name='lstcampossele[]' id='lstcampossele' size='16' style='width:200px' multiple>
					    </select>
                    </div></td>
              </tr>
              <tr>
                <td><div align="center">
                  <input name="btnincluircampos" type="button" class="boton" id="btnincluircampos" style="width: 40px" value="&gt;" onClick="javascript: ue_pasar(form1.lstcamposdisp,form1.lstcampossele);">
                </div></td>
                </tr>
              <tr>
                <td><div align="center">
                  <input name="btnincluircampostodos" type="button" class="boton" id="btnincluircampostodos" style="width: 40px" value="&gt;&gt;" onClick="javascript: ue_pasartodos(form1.lstcamposdisp,form1.lstcampossele);">
                </div></td>
              </tr>
              <tr>
                <td><div align="center">
                  <input name="btnexcluircampos" type="button" class="boton" id="btnexcluircampos" style="width: 40px" value="&lt;"  onClick="javascript: ue_pasar(form1.lstcampossele,form1.lstcamposdisp);">
                </div></td>
                </tr>
              <tr>
                <td><div align="center">
                  <input name="btnexcluircampostodos" type="button" class="boton" id="btnexcluircampostodos" style="width: 40px" value="&lt;&lt;" onClick="javascript: ue_pasartodos(form1.lstcampossele,form1.lstcamposdisp);">
                </div></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                </tr>
            </table>
          </div></td>
          </tr>
        <tr>
          <td height="22" colspan="5"><input name="operacion" type="hidden" id="operacion">
		  							  <input name="tope" type="hidden" id="tope">
									  <input name="reporte" type="hidden" id="reporte"></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		total=0;
		if(!(codnomdes<=codnomhas))
		{
			alert("El rango del nómina está erroneo");
			valido=false;
		}
		if(valido)
		{
			codperdes=f.txtcodperdes.value;
			codperhas=f.txtcodperhas.value;
			if(!(codperdes<=codperhas))
			{
				alert("El rango del personal está erroneo");
				valido=false;
			}
		}
		if(valido)
		{
			edaddesde=f.txtedaddesde.value;
			edadhasta=f.txtedadhasta.value;
			if(!(edaddesde<=edadhasta))
			{
				alert("El rango de edad del personal está erroneo");
				valido=false;
			}
		}
		if(valido)
		{
			f.txtfecinginsdesde.value=ue_validarfecha(f.txtfecinginsdesde.value);	
			f.txtfecinginshasta.value=ue_validarfecha(f.txtfecinginshasta.value);	
			fecinginsdesde=ue_validarfecha(f.txtfecinginsdesde.value);	
			fecinginshasta=ue_validarfecha(f.txtfecinginshasta.value);	
			if(!ue_comparar_fechas(fecinginsdesde,fecinginshasta))
			{
				valido=false;
				alert("El Rango de Fechas de Ingreso a la Institución es Inválido.");
			}
		}
		if(valido)
		{
			f.txtfecingnomdesde.value=ue_validarfecha(f.txtfecingnomdesde.value);	
			f.txtfecingnomhasta.value=ue_validarfecha(f.txtfecingnomhasta.value);	
			fecingnomdesde=ue_validarfecha(f.txtfecingnomdesde.value);	
			fecingnomhasta=ue_validarfecha(f.txtfecingnomhasta.value);	
			if(!ue_comparar_fechas(fecingnomdesde,fecingnomhasta))
			{
				valido=false;
				alert("El Rango de Fechas de Ingreso a la Nómina es Inválido.");
			}
		}
		if(valido)
		{
			f.txtfecculcontrdes.value=ue_validarfecha(f.txtfecculcontrdes.value);	
			f.txtfecculcontrhas.value=ue_validarfecha(f.txtfecculcontrhas.value);	
			fecculcontrdes=ue_validarfecha(f.txtfecculcontrdes.value);	
			fecculcontrhas=ue_validarfecha(f.txtfecculcontrhas.value);	
			if(!ue_comparar_fechas(fecculcontrdes,fecculcontrhas))
			{
				valido=false;
				alert("El Rango de Fechas de Culminación de Contratos es Inválido.");
			}
		}
		if(valido)
		{
			if(f.lstcampossele!=null)
			{
				total=f.lstcampossele.length;	
			}
			for(i=0;i<total;i++)
			{
				f.lstcampossele[i].selected=true;
			}
		}
		if(valido)
		{
			if(total==0)
			{
				alert("Debe selecionar al menos un campo a reportar");
				valido=false;
			}
			if(total>10)
			{
				alert("Debe selecionar Maximo 10 campos a reportar");
				valido=false;
			}
		}
		if(valido)
		{
			f.operacion.value="REPORTAR";
			f.action="sigesp_snorh_r_listadopersonalgenerico.php";
			f.tope.value=10;
			f.reporte.value="PDF";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		total=0;
		if(!(codnomdes<=codnomhas))
		{
			alert("El rango del nómina está erroneo");
			valido=false;
		}
		if(valido)
		{
			codperdes=f.txtcodperdes.value;
			codperhas=f.txtcodperhas.value;
			if(!(codperdes<=codperhas))
			{
				alert("El rango del personal está erroneo");
				valido=false;
			}
		}
		if(valido)
		{
			edaddesde=f.txtedaddesde.value;
			edadhasta=f.txtedadhasta.value;
			if(!(edaddesde<=edadhasta))
			{
				alert("El rango de edad del personal está erroneo");
				valido=false;
			}
		}
		if(valido)
		{
			f.txtfecinginsdesde.value=ue_validarfecha(f.txtfecinginsdesde.value);	
			f.txtfecinginshasta.value=ue_validarfecha(f.txtfecinginshasta.value);	
			fecinginsdesde=ue_validarfecha(f.txtfecinginsdesde.value);	
			fecinginshasta=ue_validarfecha(f.txtfecinginshasta.value);	
			if(!ue_comparar_fechas(fecinginsdesde,fecinginshasta))
			{
				valido=false;
				alert("El Rango de Fechas de Ingreso a la Institución es Inválido.");
			}
		}
		if(valido)
		{
			f.txtfecingnomdesde.value=ue_validarfecha(f.txtfecingnomdesde.value);	
			f.txtfecingnomhasta.value=ue_validarfecha(f.txtfecingnomhasta.value);	
			fecingnomdesde=ue_validarfecha(f.txtfecingnomdesde.value);	
			fecingnomhasta=ue_validarfecha(f.txtfecingnomhasta.value);	
			if(!ue_comparar_fechas(fecingnomdesde,fecingnomhasta))
			{
				valido=false;
				alert("El Rango de Fechas de Ingreso a la Nómina es Inválido.");
			}
		}
		if(valido)
		{
			f.txtfecculcontrdes.value=ue_validarfecha(f.txtfecculcontrdes.value);	
			f.txtfecculcontrhas.value=ue_validarfecha(f.txtfecculcontrhas.value);	
			fecculcontrdes=ue_validarfecha(f.txtfecculcontrdes.value);	
			fecculcontrhas=ue_validarfecha(f.txtfecculcontrhas.value);	
			if(!ue_comparar_fechas(fecculcontrdes,fecculcontrhas))
			{
				valido=false;
				alert("El Rango de Fechas de Culminación de Contratos es Inválido.");
			}
		}
		if(valido)
		{
			if(f.lstcampossele!=null)
			{
				total=f.lstcampossele.length;	
			}
			for(i=0;i<total;i++)
			{
				f.lstcampossele[i].selected=true;
			}
		}
		if(valido)
		{
			if(total==0)
			{
				alert("Debe selecionar al menos un campo a reportar");
				valido=false;
			}
		}
		if(valido)
		{
			f.operacion.value="REPORTAR";
			f.action="sigesp_snorh_r_listadopersonalgenerico.php";
			f.tope.value=40;
			f.reporte.value="EXCEL";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_buscarnominadesde()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=replisperdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=replisperhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina desde.");
	}
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=replisperdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	if(f.txtcodperdes.value!="")
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=replisperhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un personal desde.");
	}
}

function ue_pasar(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		if(obj_desde.options[i].selected)
		{
			asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
			asignados=obj_hasta.length;
			if (asignados< 1)
			{
				obj_hasta.options[asignados] = asignar;
			}
			else
			{
				obj_hasta.options[tothas] = asignar;
			}
			tothas=asignados + 1;
		}
	
	}
	ue_borrar_listaseleccionado(obj_desde);
}

function ue_pasartodos(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
		asignados=obj_hasta.length;
		if (asignados< 1)
		{
			obj_hasta.options[asignados] = asignar;
		}
		else
		{
			obj_hasta.options[tothas] = asignar;
		}
		tothas=asignados + 1;
		
	}
	ue_borrar_listacompleta(obj_desde);
}

function ue_borrar_listacompleta(obj) 
{
	var  largo= obj.length;
	for (i=largo-1;i>=0;i--) 
	{	
		obj.options[i] = null;
	}
}

function ue_borrar_listaseleccionado(obj) 
{
	var largo= obj.length;
	var x;
	var count=0;
	arrSelected = new Array();
	for(i=0;i<largo;i++) // se coloca en el arreglo los campos seleccionados
	{	
		if(obj.options[i].selected) 
		{
			arrSelected[count]=obj.options[i].value;
		}
		count++;
	}
	for(i=0;i<largo;i++) // se colocan en null los que están en el arreglo
	{
		for(x=0;x<arrSelected.length;x++) 
		{
			if (obj.options[i].value==arrSelected[x]) 
			{
				obj.options[i]=null;
			}
		}
		largo = obj.length;
	}
}

</script> 
</html>