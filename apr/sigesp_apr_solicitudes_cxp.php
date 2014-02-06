<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}

	$arr_emp=$_SESSION["la_empresa"];	
	$ls_database_destino= 	$_SESSION["ls_data_des"];
	$ls_database_fuente= 	$_SESSION["ls_database"];

	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_conect= new sigesp_include();
	$io_msg= new class_mensajes();
	$io_dsprove= new class_datastore();
	$io_conexion_origen= $io_conect->uf_conectar();
	$io_conexion_destino= $io_conect->uf_conectar($ls_database_destino);
	$io_sql_origen= new class_sql($io_conexion_origen);
	$io_sql_destino= new class_sql($io_conexion_destino);
	$io_fun= new class_funciones();
	$ds= new class_datastore();
	$ds_detsol= new class_datastore();
	$ds_cucr= new class_datastore();
	$io_rcbsf= new sigesp_c_reconvertir_monedabsf(); 
	$li_candeccon= 2;
	$li_tipconmon= 1;
	$li_redconmon=1;
	$ls_accion= $_GET["id"];
	$li_pos= $_GET["posicion"];
	$ls_ctascg= "";
	$ls_ctaspg= $_GET["ctaspg"];
	$ls_fopera= $_GET["fopera"];
	$ls_prefijo= $_GET["prefijo"];
	$ls_estconpre= $_GET["estconpre"];
	$ls_codigotipdoc= $_GET["codtipdoc"];
	$ln_montospg= 0.00;
	$ln_montocargo= 0.00;
	
	if ($ls_accion=='1')
	{	
		uf_update_ds($li_pos);
	}
	if ($ls_accion=='2')
	{
		uf_procesar_solicitudes();
	}	
function uf_update_ds($ai_pos)	
{	
	
	$filas=$_SESSION["data_aux"];

	if ($filas["marcado"][$ai_pos]==1)
		{
			$filas["marcado"][$ai_pos]=0;
		}
	else
		{
			$filas["marcado"][$ai_pos]=1;
		}
	$_SESSION["data_aux"]=$filas;
}	



function uf_procesar_solicitudes()
{	
	global $io_fun;
	global $io_msg;
	if ($_GET['id']=='2')
	{
		$filas=$_SESSION["data_aux"];
		$ds_data=new class_datastore();
		$ds_data->data=$filas;
		$totrow=$ds_data->getRowCount("numsol");
		for ($z=1;$z<=$totrow;$z++)
		{
			$solicitud 	= $filas["numsol"][$z];	
			$marcado	= $filas["marcado"][$z];	
			if ($marcado==1)
			{
				$lb_existe=uf_existe_en_destino($solicitud);
				if ($lb_existe)
					{
						print $solicitud." existe <br>";
					}
				else
					{				
						$ls_prefijo	 = trim($_GET["prefijo"]);									// obtengo el prefijo
						$ls_codigotipdoc= $_GET["codtipdoc"];
						$ls_estconpre= $_GET["estconpre"];
						$ls_estconpre= $_GET["estconpre"];
						$ls_codestpro1= $io_fun->uf_cerosizquierda($_GET["codestpro1"],20);
						$ls_codestpro2= $io_fun->uf_cerosizquierda($_GET["codestpro2"],6);
						$ls_codestpro3= $io_fun->uf_cerosizquierda($_GET["codestpro3"],3);
						$ls_codestpro4= $io_fun->uf_cerosizquierda($_GET["codestpro4"],2);
						$ls_codestpro5= $io_fun->uf_cerosizquierda($_GET["codestpro5"],2);
						$ls_spgcuenta= $_GET["ctaspg"];
						$li_lenpre   = strlen(trim($ls_prefijo));								// longitud de la cadena prefijo
						$li_lensol	 = 15 - $li_lenpre;											// longitud que debe tener la cadena extraida de la solicitud vieja
						$ls_newsol	 = $ls_prefijo.substr($solicitud,$li_lenpre,$li_lensol);	// armo la nueva solicitud
						$ln_monto	 = number_format($filas["monsol"][$z],2,'.','');
						$ln_pagado	 = number_format($filas["Pagado"][$z],2,'.','');
						$ln_factor	 = ($ln_monto - $ln_pagado) / $ln_monto	;
						$lb_valido = procesar($solicitud,$ls_newsol,$ls_prefijo,$_GET["ctascg"],$_GET["codtipdoc"],
											  'programatica',$_GET["ctaspg"],$_GET["fopera"],$ln_factor,$ls_codigotipdoc,
											  $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
											  $ls_spgcuenta,$ls_estconpre);
						if(!lb_valido)
						{
							break;
						}

					}
			}
		}
				$io_msg->uf_mensajes_ajax("Informacion","El proceso se ejecuto satisfactoriamente",true,"javascript: ue_close();"); 				
	}
}


function procesar($as_numsol, $as_newsol, $as_prefijo, $as_contable, $as_coddoc, $as_Prog, $as_spgcuenta, $as_fechanew,
				  $an_factor,$as_codigotipdoc,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
				  $as_spgcuenta,$as_estconpre)
{	
	$lb_valido= true;
	$lb_existe= uf_existe_en_destino($as_newsol);
	if  (!$lb_existe)
	{
		$lb_valido = uf_copia_solicitud($as_numsol, $as_newsol, $as_fechanew, $an_factor);
		if ($lb_valido)
		{
			$lb_valido= uf_copia_cxp_det_sol($as_numsol, $as_newsol, $as_prefijo, $as_contable, $as_coddoc, $as_Prog, $as_spgcuenta,
								 $as_fechanew, $an_factor,$as_codigotipdoc,$as_codestpro1,$as_codestpro2,$as_codestpro3,
								 $as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estconpre);
		}			
	}
	return $lb_valido;
}


function uf_copia_solicitud($as_numsol, $as_newnumsol, $as_fechanew, $an_factor)
{
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	global $io_sql_origen;
	global $io_sql_destino;
	global $io_fun;
	global $io_msg;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	$ls_sql= 	"SELECT * ".
				"FROM cxp_solicitudes ".
				"WHERE codemp = '".$ls_codemp."' AND ". 
				"numsol = '".$as_numsol."' ";

	$rs_td=$io_sql_origen->select($ls_sql);
	if($row=$io_sql_origen->fetch_row($rs_td))
	{
		$ln_newmonto = $row['monsol']*$an_factor;
		if($row['fecpagsol']=='')
		{
			$ld_fecpagsol='null';
		}
		else
		{
			$ld_fecpagsol="'".$io_fun->uf_convertirdatetobd($row["fecpagsol"])."'";
		}
		if($row['feccmp']=='')
		{
			$ld_feccmp='null';
		}
		else
		{
			$ld_feccmp="'".$io_fun->uf_convertirdatetobd($row["feccmp"])."'";
		}
		$li_monto=$io_rcbsf->uf_convertir_monedabsf($ln_newmonto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
		$ls_sql="INSERT INTO cxp_solicitudes (codemp, numsol, cod_pro, ced_bene, codfuefin, tipproben, fecemisol, fecpagsol,".
				"                             consol, estprosol, monsol, obssol, procede, numcmp, feccmp, estaprosol,".
				"                             fecaprosol, usuaprosol, numpolcon) ".
				"VALUES ('".$row['codemp']."','".$as_newnumsol."','".$io_fun->uf_cerosizquierda($row['cod_pro'],10)."','".$row['ced_bene']."','".$row['codfuefin']."','".
				$row['tipproben']."','".$io_fun->uf_convertirdatetobd($as_fechanew)."',".$ld_fecpagsol.",'".$row['consol']."','".'E'."','". 
				$li_monto."','".$row['obssol']."','".$row['procede']."','".$row['numcmp']."',".$ld_feccmp.",'0',".
				"'1900-01-01','".$row['usuaprosol']."','".$row['numpolcon']."')	";
		$rs_td=$io_sql_destino->execute($ls_sql);
		if ($rs_td===false)
		{
			$io_msg->message("CLASE->APERTURA_SOLICITUDES MTODO->uf_copia_solicitud ERROR->".$io_fun->uf_convertirmsg($io_sql_destino->message)); 
			return false;
		}
		else
		{
			return true;
		}	
	}
}

function uf_copia_cxp_det_sol($as_numsol, $as_newsol, $as_prefijo, $as_contable, $as_coddoc, $as_Prog, $as_spgcuenta, $as_fechanew,
							  $an_factor,$as_codigotipdoc,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
							  $as_codestpro5,$as_spgcuenta,$as_estconpre)
{
	$lb_valido = true;
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	global $io_sql_origen;
	global $io_sql_destino;
	global $io_fun;
	global $io_msg;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	$ds_detsol=new class_datastore();
	$ls_sql = 	"SELECT * ".
				"FROM cxp_dt_solicitudes ".
				"WHERE codemp = '".$ls_codemp."' AND ". 
				"numsol = '".$as_numsol."' ";
	$rs_detsol=$io_sql_origen->select($ls_sql);
	if ($row=$io_sql_origen->fetch_row($rs_detsol))
	{
		$data=$io_sql_origen->obtener_datos($rs_detsol);
		$ds_detsol->data=$data;
		$_SESSION["data_detsol"]=$ds_detsol->data;
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$totrow=$ds_detsol->getRowCount("numsol");
		for ($z=1;$z<=$totrow;$z++)
		{
			$li_lenpre   = strlen(trim($as_prefijo));								// longitud de la cadena prefijo
			$li_lensol	 = 15 - $li_lenpre;											// longitud que debe tener la cadena extraida de la solicitud vieja
			$ls_newrd	 = $as_prefijo.substr($as_numsol,$li_lenpre,$li_lensol);	// armo la nueva solicitud 
			$lb_valido_rd = uf_copia_rd($data["numrecdoc"][$z],$data["codtipdoc"][$z],$data["cod_pro"][$z],$data["ced_bene"][$z],
										$ls_newrd,$as_contable,$data["codtipdoc"][$z], $as_Prog, $as_spgcuenta, $as_fechanew,
										$an_factor,$as_codigotipdoc,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
										$as_codestpro5,$as_spgcuenta,$as_estconpre);
			if ($lb_valido_rd)
			{
				$ld_newmonto = $an_factor * $data["monto"][$z];	
				$li_monto=$io_rcbsf->uf_convertir_monedabsf($ld_newmonto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
				$ls_sql_insert_ds =	" INSERT INTO cxp_dt_solicitudes (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, monto) ".
							" values ('".$ls_codemp."','".$as_newsol."','".$data["numrecdoc"][$z]."','".$as_codigotipdoc."','".$data["ced_bene"][$z]."','".$io_fun->uf_cerosizquierda($data["cod_pro"][$z],10)."','".$ld_newmonto."') ";
				$rs_dsol=$io_sql_destino->execute($ls_sql_insert_ds);
				if($rs_dsol===false)
				{
					print "Error en uf_copia_cxp_det_sol ".$io_sql_destino->message;
				}
			}
			else
			{
				break;
				$lb_valido=false;
			}				
		}
	}
	return $lb_valido;
}

function uf_copia_rd($as_tcNumdoc, $as_tcCodtipdoc, $as_Cod_pro, $as_Ced_bene, $as_tcnewnumdoc, $ab_tlContable, $as_tcnewcoddoc,
					 $as_tcProg, $as_tcSPGCuenta, $as_tdfechanew, $as_tnFactor,$as_codigotipdoc,$as_codestpro1,$as_codestpro2,
					 $as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estconpre)
{
		print "AQUI VOY";
	global $io_sql_origen;
	global $io_sql_destino;
	global $io_fun;
	global $io_msg;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	$ds_cucr=new class_datastore();
	$ls_sql_rd="SELECT * ".
			   "FROM cxp_rd rdc, cxp_documento doc ".
			   "WHERE rdc.codtipdoc=doc.codtipdoc and ".
			   "rdc.codemp = '".$_SESSION["la_empresa"]["codemp"]."' and ".
			   "rdc.numrecdoc = '$as_tcNumdoc' and ".
			   "rdc.cod_pro =  '$as_Cod_pro' and ".
			   "rdc.ced_bene = '$as_Ced_bene' ".
			   " AND rdc.codtipdoc='".$as_tcCodtipdoc."'";
	
	$rs_cucr=$io_sql_origen->select($ls_sql_rd);
	$data_cucr=$rs_cucr;
	if($rs_cucr===false)
	{
		print "Error en copia rd ".$io_sql->message;
		return false;
	}
	if ($row=$io_sql_origen->fetch_row($rs_cucr))
	{
		$ds_cucr->data=$io_sql_origen->obtener_datos($rs_cucr);
		$_SESSION["data_cucr"]=$ds_cucr->data;		
		$li_total=$ds_cucr->getRowCount("numrecdoc");	
		
		$ls_chgcoddoc = "";
		$lb_valido = true;
		if ($ds_cucr->getValue("estlibcom",1)==1)
		{
			$li_newlc = 2;
		}
		else
		{
			$li_newlc = 0;
		}	
		
		$ls_numrecdoc  	= rtrim($ds_cucr->getValue("numrecdoc",1));
		$ls_codpro  	= $ds_cucr->getValue("cod_pro",1);
		$ls_cedbene  	= $ds_cucr->getValue("ced_bene",1);
		$ls_concepto  	= rtrim($ds_cucr->getValue("dencondoc",1));
		$ls_codcla    	= $ds_cucr->getValue("codcla",1);
		$ls_tipproben  	= $ds_cucr->getValue("tipproben",1);
		$ls_referencia  = rtrim($ds_cucr->getValue("numref",1));
		$ls_estprodoc 	= $ds_cucr->getValue("estprodoc",1);
		$ls_procede   	= $ds_cucr->getValue("procede",1);
		$ls_estaprord 	= $ds_cucr->getValue("estaprord",1);
		$ld_fecaprord   = $ds_cucr->getValue("fecaprord",1);
		$ls_usuaprord 	= rtrim($ds_cucr->getValue("usuaprord",1));
		$ln_numpolcon 	= $ds_cucr->getValue("numpolcon",1);
		$ln_estimpmun 	= $ds_cucr->getValue("estimpmun",1);
		$ln_montot    	= $ds_cucr->getValue("montotdoc",1); 
		$ln_deducciones = $ds_cucr->getValue("mondeddoc",1); 
		$ln_cargos    	= $ds_cucr->getValue("moncardoc",1);
		if ($lb_valido)
		{
			$ln_montocargo=uf_get_monto_cargos($as_tcNumdoc,$as_tcCodtipdoc, $as_Cod_pro, $as_Ced_bene); //retorna $ln_montocargo
		}
		if ($lb_valido)
		{
			$ln_newmonto 		= $as_tnFactor * $ln_montot;
			$ln_newdeducciones  = $as_tnFactor * $ln_deducciones;
			$ln_newcargos 		= $as_tnFactor *$ln_cargos;
			$ln_newmonto=$io_rcbsf->uf_convertir_monedabsf($ln_newmonto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ln_newdeducciones=$io_rcbsf->uf_convertir_monedabsf($ln_newdeducciones,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ln_newcargos=$io_rcbsf->uf_convertir_monedabsf($ln_newcargos,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ls_cad_ins = "INSERT INTO cxp_rd (codemp, numrecdoc, codtipdoc, cod_pro, ced_bene, codcla, dencondoc,".
					  "fecemidoc, fecregdoc, fecvendoc, montotdoc, mondeddoc, moncardoc, ".
					  "tipproben, numref, estprodoc, procede, estlibcom, estaprord, fecaprord, ".
					  "usuaprord, numpolcon, estimpmun, montot) ".
					  "values ('".$_SESSION["la_empresa"]["codemp"]."','".$ls_numrecdoc."','".$as_codigotipdoc."','".$ls_codpro."','".$ls_cedbene."','".$ls_codcla."','".$ls_concepto."', '".
					  $io_fun->uf_convertirdatetobd($as_tdfechanew)."','".$io_fun->uf_convertirdatetobd($as_tdfechanew)."','".$io_fun->uf_convertirdatetobd($as_tdfechanew)."','".$ln_newmonto."','".$ln_newdeducciones."','".$ln_newcargos."', '".
					  $ls_tipproben."','".$ls_referencia."','E','".$ls_procede."','".$li_newlc."','".$ls_estaprord."','".$ld_fecaprord."','".
					  $ls_usuaprord."','".$ln_numpolcon."','".$ln_estimpmun."','".$ln_newmonto."')";
			
			$rs_cxp_rd=$io_sql_destino->execute($ls_cad_ins);	
			if($rs_cxp_rd===false)								// inserta en rd
			{
				print "Error en copia_rd ".$io_sql_destino->message;
			}
		}
		if($lb_valido)
		{
			$ls_ccontable=rtrim(uf_get_inf_contable($ls_tipproben,$ls_codpro, $ls_cedbene));
			if($as_estconpre==0)
			{
				if($ls_ccontable!="")
				{
					$lb_valido=uf_det_cont_spg($ls_numrecdoc,$as_codigotipdoc,$ls_codpro, $ls_cedbene,$ls_numrecdoc,$ls_ccontable,
											   $ln_newmonto,'D');
					if($lb_valido)
					{
						$lb_valido=uf_det_cont_spg($ls_numrecdoc,$as_codigotipdoc,$ls_codpro, $ls_cedbene,$ls_numrecdoc,$ls_ccontable,
												   $ln_newmonto,'H');
					}
				}
			}
			else
			{
				$ls_sccuenta=rtrim(uf_get_contable_presupuesto($as_spgcuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,
														 $as_codestpro4,$as_codestpro5));
				if(($ls_sccuenta!="")&&($ls_ccontable!=""))
				{
					$lb_valido=uf_det_presupuesto($ls_numrecdoc,$as_codigotipdoc,$ls_codpro,$ls_cedbene,$ls_numrecdoc,
												  $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
												  $as_spgcuenta,$ln_newmonto);
					if($lb_valido)
					{
						$lb_valido=uf_det_cont_spg($ls_numrecdoc,$as_codigotipdoc,$ls_codpro, $ls_cedbene,$ls_numrecdoc,$ls_ccontable,
												   $ln_newmonto,'H');
						if($lb_valido)
						{
							$lb_valido=uf_det_cont_spg($ls_numrecdoc,$as_codigotipdoc,$ls_codpro, $ls_cedbene,$ls_numrecdoc,
													   $ls_sccuenta,$ln_newmonto,'D');
						}
					}
				}
				else
				{
					$lb_valido=false;
					$io_msg->uf_mensajes_ajax("Informacion","No se procesaron las solicitudes, Favor verifique las cuentas contables de la recepcion ".$ls_numrecdoc." ",true,"javascript: ue_close();"); 				
				}
			}
		}
		return $lb_valido;
	}
}

function uf_get_new_tipodoc($as_tcon,$as_tpre)
{
	global $io_sql_origen;
	$ls_sql_td = "select * ".										
				 "from cxp_documento ".
				 "where estcon ='".$as_tcon."' and ".
				 "	estpre ='".$as_tpre."' ".
				 "order by codtipdoc	";
	
	$rs_ntd=$io_sql_origen->select($ls_sql_td);
	if ($row=$io_sql_origen->fetch_row($rs_ntd))
		{
			$ls_codigotipdoc = $row["codtipdoc"];	
			return true;
		}
	else
		{
			return false;
		}	
}

function uf_get_monto_spg($as_numdoc,$as_codtipdoc, $as_codpro, $as_cedbene)
{

	$li_total=0.00;
	global $io_sql_origen;
	$ls_sql = 	"select sum(monto) as total ".
				"from cxp_rd_spg ".
				"where codemp = '".$_SESSION["la_empresa"]["codemp"]."' and ".  
				"	numrecdoc = '".$as_numdoc."' and ".
				"	codtipdoc =  '".$as_codtipdoc."' and  ".
				"	cod_pro   =  '".$as_codpro."' and ".
				"	ced_bene  =  '".$as_cedbene."' ";

	$rs_td=$io_sql_origen->select($ls_sql);
	if($row=$io_sql_origen->fetch_row($rs_td))
	{
		$li_total = $row["total"];
	}
	return $li_total;
}

function uf_get_monto_cargos($as_numdoc,$as_codtipdoc, $as_codpro, $as_cedbene)
{
	$ln_total_cargos = 0.00;
	global $io_sql_origen;
	$ls_sql= "select sum(rc.monret) as tcargos ".
			 "from cxp_rd_cargos rc, sigesp_cargos cr ".
			 "where rc.codemp = '".$_SESSION["la_empresa"]["codemp"]."' and ".
			 "	rc.codemp	=  cr.codemp and ".
			 "	rc.codcar	=  cr.codcar and ".
			 "	numrecdoc	= '".$as_numdoc."' and ".
			 "	codtipdoc	= '".$as_codtipdoc."' and ".
			 "	cod_pro		= '".$as_codpro."' and ".
			 "	ced_bene	= '".$as_cedbene."' ";

	$rs_td=$io_sql_origen->select($ls_sql);
	if($row=$io_sql_origen->fetch_row($rs_td))
	{
		$ln_total_cargos = $row["tcargos"];
	}			
	return $ln_total_cargos;
}

function uf_get_inf_contable($as_tippb,$as_codpro, $as_cedbene)
{
	$ls_sc_cuenta="";
	global $io_sql_destino;
	if ($as_tippb=='P')
	{
		$ls_sql = "select sc_cuenta ".
				 "from rpc_proveedor ".
				 "where codemp = '".$_SESSION["la_empresa"]["codemp"]."' and ".
				 "  	cod_pro = '".$as_codpro."' ";
	}
	else
	{
		$ls_sql = "select sc_cuenta ".
				 "from rpc_beneficiario ".
				 "where codemp = '".$_SESSION["la_empresa"]["codemp"]."' and ".
				 "  	ced_bene = '".$as_cedbene."' ";
	} 
	$rs_td=$io_sql_destino->select($ls_sql);
	if($row=$io_sql_destino->fetch_row($rs_td))
	{
		$ls_sc_cuenta = $row["sc_cuenta"];
	}
	return $ls_sc_cuenta;
}

function uf_get_contable_presupuesto($as_spgcuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
	global $io_sql_destino;
	$ls_sc_cuenta="";
		$ls_sql="SELECT sc_cuenta ".
				"  FROM spg_cuentas".
				" WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'".
				"   AND codestpro1='".$as_codestpro1."'".
				"   AND codestpro2='".$as_codestpro2."'".
				"   AND codestpro3='".$as_codestpro3."'".
				"   AND codestpro4='".$as_codestpro4."'".
				"   AND codestpro5='".$as_codestpro5."'".
				"  	AND spg_cuenta = '".$as_spgcuenta."' ";
	$rs_td=$io_sql_destino->select($ls_sql);
	if($row=$io_sql_destino->fetch_row($rs_td))
	{
		$ls_sc_cuenta = $row["sc_cuenta"];
	}
	return $ls_sc_cuenta;
}

function uf_det_cont_spg($as_numdoc,$as_codigotipdoc,$as_codpro, $as_cedbene,$as_newnumdoc,$as_contable,$an_monto,$as_debhab)
{
	global $io_fun;
	global $io_sql_destino;
	$lb_valido=true;
	$ls_sql="insert into cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab,".
			"                        sc_cuenta, monto, estgenasi) ".
			"values ('".$_SESSION["la_empresa"]["codemp"]."','".$as_numdoc."','".$as_codigotipdoc."','".$as_cedbene."',".
			"        '".$as_codpro."','CXPSOP','".$as_newnumdoc."','".$as_debhab."','".$as_contable."',".$an_monto.",0) "; 
	$rs_cxp_rd_scg = $io_sql_destino->execute($ls_sql);
	if($rd_cxp_rd_scg===false)
	{
		print "Error en uf_det_cont_spg ".$io_sql_destino->message;
		$lb_valido=false;
	}
	return $lb_valido;
}

function uf_det_contable_01($ls_numdoc,$ls_codtipdoc,$ls_codpro,$ls_cedbene,$ls_newnumdoc,$ls_newcoddoc,$ls_contable,$li_factor)
{

	$lb_valido = true;
	global $io_sql_origen;
	global $io_sql_destino;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	global $io_fun;
	$ls_sql ="SELECT numrecdoc, codtipdoc, cod_pro, ced_bene, procede_doc, numdoccom, debhab, sum(monto) as monto 
			  FROM  cxp_rd_scg
			  WHERE  codemp='".$_SESSION["la_empresa"]["codemp"]."' AND numrecdoc='".$ls_numdoc."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedbene."'
			  GROUP BY numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab";
	
	$rs_data=$io_sql_origen->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en  detalle contable 01 ".$io_sql->message;
		return false;
	}
	else
	{
		while($row=$io_sql_origen->fetch_row($rs_data))
		{
			$ldec_newmonto = $row["monto"]*$li_factor;
			$ldec_newmonto=$io_rcbsf->uf_convertir_monedabsf($ldec_newmonto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ls_insert="INSERT INTO cxp_rd_scg(codemp,numrecdoc, codtipdoc, cod_pro, ced_bene, procede_doc,
																			 numdoccom, debhab, sc_cuenta, monto) 
						VALUES('".$_SESSION["la_empresa"]["codemp"]."','".$ls_newnumdoc."','".$ls_newcoddoc."','".$io_fun->uf_cerosizquierda($row["cod_pro"],10)."','".$row["ced_bene"]."','".$row["procede_doc"]."',
							   '".$row["numdoccom"]."','".$row["debhab"]."','".$ls_contable."','".$ldec_newmonto."')";
		
			$li_row=$io_sql_destino->execute($ls_insert);
			if($li_row===false)
			{
				print "Error al insertar en detalle contable 01 ".$io_sql_destino->message;
				return false;
			}
			else
			{
				$lb_valido=true;
			}
		}	
	}	
	return $lb_valido;
}

function uf_det_presupuesto($ls_numdoc,$ls_codtipdoc,$ls_codpro,$ls_cedbene,$ls_newnumdoc,$as_codestpro1,$as_codestpro2,
							$as_codestpro3,$as_codestpro4,$as_codestpro5,$ls_cuenta_spg,$ldec_monto)
{
	global $io_sql_destino;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	$ls_codestpro=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	$ls_sql="INSERT INTO cxp_rd_spg(codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro,".
			"                       spg_cuenta, codfuefin, monto)".
			" VALUES('".$_SESSION["la_empresa"]["codemp"]."','".$ls_numdoc."','".$ls_codtipdoc."','".$ls_cedbene."',".
			"        '".$ls_codpro."','CXPSOP','".$ls_numdoc."','".$ls_codestpro."','".$ls_cuenta_spg."','--',".$ldec_monto.")";
	$li_row=$io_sql_destino->execute($ls_sql);
	if($li_row===false)
	{
		print "Error en insert det_presupuesto ".$io_sql_destino->message;
		return false;
	}
	return true;
}

function uf_det_contable($ls_numdoc,$ls_codtipdoc,$ls_codpro,$ls_cedbene,$ls_newnumdoc,$ls_newtipdoc,$li_factor)
{
	$lb_valido=	true;
	global $io_sql_origen;
	global $io_sql_destino;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	global $io_fun;
	$ls_sql="SELECT  * 
			 FROM  cxp_rd_scg
			 WHERE codemp='".$_SESSION["codemp"]." AND 'numrecdoc='".$ls_numdoc."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' 
			 AND ced_bene='".$ls_cedbene."'	";
	
	$rs_data=$io_sql_origen->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en uf_det_contable ".$io_sql_origen->message;
		return false;
	}
	else
	{
		while($row=$io_sql_origen->fetch_row($rs_data))
		{
			if($ls_codpro=='----------')
			{
				$ls_tipo='B';
				$ls_cuenta=uf_buscar_prov_bene($ls_cedbene,$ls_tipo);
			}
			elseif($ls_cedbene=='----------')
			{
				$ls_tipo='P';
				$ls_cuenta=uf_buscar_prov_bene($ls_codpro,$ls_tipo);
			}
			$ldec_new_monto=$row["monto"]*$li_factor;
			$ldec_new_monto=$io_rcbsf->uf_convertir_monedabsf($ldec_new_monto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ls_sql="INSERT INTO cxp_rd_scg(codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta,monto)
					 VALUES('".$ls_newnumdoc."','".$ls_newtipdoc."', '".$io_fun->uf_cerosizquierda($ls_codpro,10)."','".$ls_cedbene."','".$row["procede_doc"]."','".$row["numdoccom"]."',
							'".$row["debhab"]."','".$ls_cuenta."',".$ldec_new_monto.")";
			$li_row=$io_sql_destino->execute($ls_sql);
			if($li_row===false)							
			{
				print "Error en uf_det_contable ".$io_sql_destino->message;
				return false;
			}
			else
			{
				$lb_valido=true;
			}
		}	
	}
	return $lb_valido;
}

function uf_det_carded_nspg($ls_numdoc,$ls_codtipdoc,$ls_codpro,$ls_cedbene,$ls_newnumdoc,$ls_newtipdoc,$li_factor)
{
	$lb_valido=true;
	global $io_sql_origen;
	global $io_sql_destino;
	global $io_rcbsf; 
	global $li_candeccon;
	global $li_tipconmon;
	global $li_redconmon;
	global $io_fun;
	$ls_sql="SELECT *
			 FROM  cxp_rd_cargos XDC
			 WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."' AND numrecdoc='".$ls_numdoc."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedbene."'";
	
	
	$rs_data=$io_sql_origen->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en uf_det_carded_nspg al copiar cargos ".$io_sql_origen->message;
		return false;
	}
	else
	{
		while($row=$io_sql_origen->fetch_row($rs_data))
		{
			$ldec_newmonto=$row["monret"]*$li_factor;
			$ldec_newmonobjret=$row["monobjret"].$li_factor;
			$ldec_newmonto=$io_rcbsf->uf_convertir_monedabsf($ldec_newmonto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ldec_newmonobjret=$io_rcbsf->uf_convertir_monedabsf($ldec_newmonobjret,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ls_insert="INSERT INTO cxp_rd_cargos(codemp, numrecdoc, codtipdoc, cod_pro, ced_bene, codcar,
																			 procede_doc, numdoccom, monobjret, monret, codestpro1, 
																			 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta,
																			  porcar, formula) 
						VALUES('".$_SESSION["la_empresa"]["codemp"]."','".$ls_newnumdoc."','".$ls_newtipdoc."','".$io_fun->uf_cerosizquierda($row["cod_pro"],10)."','".$io_fun->uf_cerosizquierda($row["ced_bene"],10)."','".$row["codcar"]."',
							   '".$row["procede_doc"]."','".$row["numdoccom"]."','".$ldec_newmonobjret."','".$ldec_newmonto."','".$row["codestpro1"]."',
							   '".$row["codestpro2"]."','".$row["codestpro3"]."','".$row["codestpro4"]."','".$row["codestpro5"]."',
							   '".$row["spg_cuenta"]."','".$row["porcar"]."','".$row["formula"]."')";		
		
			$li_row=$io_sql_destino->execute($ls_insert);
			if($li_row===false)
			{
				print "Error en uf_det_carded_nspg al copiar cargos".$io_sql_destino->message;
				return false;
			}
			else
			{
				$lb_valido=true;
			}
		}	
	}	
	
	$ls_sql="SELECT *
			 FROM  cxp_rd_deducciones XDC
			 WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."' AND numrecdoc='".$ls_numdoc."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedbene."'";
	
	
	$rs_data=$io_sql_origen->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en uf_det_carded_nspg al seleccionar deducciones ".$io_sql_origen->message;
		return false;
	}
	else
	{
		while($row=$io_sql_origen->fetch_row($rs_data))
		{
			$ldec_newmonto=$row["monret"]*$li_factor;
			$ldec_newmonobjret=$row["monobjret"].$li_factor;
			$ldec_newmonto=$io_rcbsf->uf_convertir_monedabsf($ldec_newmonto,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ldec_newmonobjret=$io_rcbsf->uf_convertir_monedabsf($ldec_newmonobjret,$li_candeccon,$li_tipconmon,1000,$li_redconmon);
			$ls_insert="INSERT INTO cxp_rd_deducciones(codemp, numrecdoc, codtipdoc, cod_pro, ced_bene, 
																				codded, procede_doc, numdoccom, monobjret, monret, 
																				sc_cuenta, porded) 
						VALUES('".$_SESSION["la_empresa"]["codemp"]."','".$ls_newnumdoc."','".$ls_newtipdoc."','".$io_fun->uf_cerosizquierda($row["cod_pro"],10)."','".$row["ced_bene"]."','".$row["codded"]."',
							   '".$row["procede_doc"]."','".$row["numdoccom"]."','".$ldec_newmonobjret."','".$ldec_newmonto."','".$row["sc_cuenta"]."','".$row["porded"]."')";		
		
			$li_row=$io_sql_destino->execute($ls_insert);
			if($li_row===false)
			{
				print "Error en uf_det_carded_nspg al copiar deducciones".$io_sql_destino->message;
				return false;
			}
			else
			{
				$lb_valido=true;
			}
		}	
	}	
	
	
	return $lb_valido;
}

function uf_buscar_prov_bene($ls_codigo,$ls_tipo)
{
	global $io_sql_origen;
	$ls_cuenta="";
	if($ls_tipo=='P')
	{
		$ls_sql="SELECT sc_cuenta FROM rpc_proveedor WHERE cod_pro='".$ls_codigo."'";
	}	
	else
	{
		$ls_sql="SELECT sc_cuenta FROM rpc_beneficiario WHERE ced_bene='".$ls_codigo."'";
	}
	$rs_data=$io_sql_origen->select($ls_sql);
	if($rs_data===false)
	{
		print "Error al buscar prov_bene ".$io_sql_origen->message;
	}
	else
	{
		if($row=$io_sql_origen->fetch_row($rs_data))
		{
			$ls_cuenta=$row["sc_cuenta"];
		}
	}
	return  $ls_cuenta;
}

function uf_existe_en_destino($as_NewSol)
{
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$li_veces=0;
	global $io_sql_destino;
	$ls_sql = 	"SELECT count(*) as nveces ".
				"FROM 	cxp_solicitudes ".
				"WHERE 	codemp = ".$ls_codemp." AND ". 
				"		numsol = ".$as_NewSol." ";
	$rs_td=$io_sql_destino->select($ls_sql);
	if($row=$io_sql_destino->fetch_row($rs_td))
	{
		$li_veces = $row["nveces"];
	}
	if ($li_veces==0)
	{
		return false;
	}
	else
	{
		return true;
	}
}
?>
