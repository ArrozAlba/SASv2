<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}


?>

<?php
	$ls_tipdoc = $_GET["id"];
	$ln_lencon = strlen(trim($_GET["conce"]));	
	$ls_status=$_GET["status"];
	$ldt_fecdes=$_GET["fdesde"];
	$ldt_fechas=$_GET["fhasta"];
	if ($ln_lencon===0)
		{
			$ls_like =   "";
		}
	else
		{
			$ls_like =   " consol like '%".trim($_GET["conce"])."%' and ";
		}
	
	$ls_database_destino = 	$_SESSION["ls_data_des"];
	$ls_database_fuente  = 	$_SESSION["ls_database"];

	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	
	$io_conect	= new sigesp_include();
	$conn		= $io_conect->uf_conectar();
	$io_msg		= new class_mensajes();
	$io_dsprove	= new class_datastore();
	$io_sql		= new class_sql($conn);
	$io_funciones= new class_funciones($conn);
	$ds   		= new class_datastore();
	$ldt_fecdes=$io_funciones->uf_convertirdatetobd($ldt_fecdes);
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($ldt_fechas);
	switch ($_SESSION["ls_gestor"])
	{
		case "MYSQL":
			$ls_cadena="CONCAT('2007',substring(numsol,LENGTH('2007')+1))";
			break;
		case "POSTGRE":
			$ls_cadena="'2007'||substring(numsol,LENGTH('2007')+1)";
			break;
	}
	
	if($ls_status=="C")
	{
		$ls_sql="SELECT 0 as marcado,numsol, fecemisol, consol, monsol,0.00 as pagado".
		        "  FROM cxp_solicitudes".
				" WHERE ".$ls_like."(fecemisol BETWEEN '".$ldt_fecdes."' AND '".$ldt_fechas."') ".
				"   AND estprosol='".$ls_status."'".
				"   AND ".$ls_cadena." NOT IN (SELECT numsol FROM cxp_solicitudes) ".
				"ORDER BY numsol	 ";
	
	}
	else
	{
		$ls_sql="SELECT 0 as marcado,numsol, fecemisol, consol, monsol, ".
				"	    (SELECT COALESCE(SUM(cxp_sol_banco.monto), 0.00) as pagado FROM cxp_sol_banco WHERE cxp_sol_banco.numsol=cxp_solicitudes.numsol) as Pagado ".
				"  FROM cxp_solicitudes ".
				" WHERE ".$ls_like.
				"	(fecemisol BETWEEN '".$ldt_fecdes."' AND '".$ldt_fechas."' AND ".
				"	estprosol='".$ls_status."') and ".
				"  ".$ls_cadena."   NOT IN (SELECT numsol FROM cxp_solicitudes) ".
				"ORDER BY numsol	 ";
	}
	$rs_td=$io_sql->select($ls_sql); 	
?>
<?php		
	//marcado,numsol, fecemisol, consol, monsol,pagado   class=fondo-tabla
	print "<table class=tableone border=0 cellpadding=1 cellspacing=1  align=center>";
//	print "<thead> ";
	print "<tr class=titulo-celda>";
	print "<td class='th1'>  </td>";
	print "<td class='th2'>Solicitud</td>";
	print "<td class='th3'>Emision</td>";
	print "<td class='th4'>Concepto</td>";
	print "<td class='th5'>Solicitado Bs.</td>";
	print "<td class='th6'>Pagado Bs.</td>";
	print "</tr>";
	//print "</thead>";	
	//print "<tbody> ";
	//print "<tr><td colspan='6'>";
	//	print "<div class='innerb' style='height:200px;'>";
	//	print "<table class=tabletwo>";
		$rs_pro=$io_sql->select($ls_sql);
		$data=$rs_pro;
		$totrow=0;
		if ($row=$io_sql->fetch_row($rs_pro))
		{
			$data=$io_sql->obtener_datos($rs_pro);
			$ds->data=$io_sql->obtener_datos($rs_pro);
			$_SESSION["data_aux"]=$ds->data;
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$totrow=$ds->getRowCount("numsol");
			for ($z=1;$z<=$totrow;$z++)
				{
					$marcado= $data["marcado"][$z];		
					$codsol= $data["numsol"][$z];
					$fecsol= $ldt_fecdes=$io_funciones->uf_convertirfecmostrar($data["fecemisol"][$z]);
					$consol= $data["consol"][$z];
					$solic= number_format($data["monsol"][$z],2,',','.');
					$pagado= number_format($data["pagado"][$z],2,',','.');
					if($solic>$pagado)
					{
						print "<tr class=celdas-blancas>";
						print "<input class='td1' name=".$z." type=checkbox id=chk".$z." value=1 onClick='ue_evaluar_click(this.name)'><input name=posicion".$z." type='hidden' id=posicion".$z." value=$codsol>";
						print "<td class='td2'>".$codsol."</td>";
						print "<td class='td3'>".$fecsol."</td>";
						print "<td class='td4'>".$consol."</td>";
						print "<td class='td5' align='right'>".$solic."</td>";
						print "<td class='td6' align='right'>".$pagado."</td>";					
						print "</tr>";			
					}
				}
			print "</table>";
		print "</div>";	
		print "</td>";
		print "</tr>";
		
		//print "</tbody>";		
		//print "</table>";
		}
		print "<input name=total_rows type=hidden id=total_rows value='".$totrow."' >";

?>


