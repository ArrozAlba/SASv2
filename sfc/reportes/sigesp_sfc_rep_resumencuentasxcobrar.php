<?Php
/******************************************/
/* FECHA: 13/01/2011                      */
/* AUTOR: ING. NELSON BARRAEZ             */
/******************************************/

session_start();
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(3,3,3,3);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$coord_x = $io_pdf->ancho_pagina/2;
$coord_y = $io_pdf->margen_inferior*3/4;
$io_pdf->ezStartPageNumbers($coord_x,$coord_y,6);
$ls_fecha=date('d/m/Y');
$ls_fecemi=$_GET["fecemi"];
$ls_fecemi2=$_GET["fecemi2"];
$ld_monto1=$_GET["monto"];
$ld_monto2=$_GET["monto2"];

$reporte->add_titulo("center",9,13,"CUENTAS POR COBRAR DESDE ".$ls_fecemi." HASTA ".$ls_fecemi2);
if($ld_monto1!='' &&$ld_monto1!='0,00')
{
	$ls_where="  WHERE (monto-montos_cobrado) BETWEEN ".$ld_monto1." AND ".$ld_monto2;	
	$reporte->add_titulo("center",14,11,"MONTO DEUDOR DESDE ".$ld_monto1." HASTA ".$ld_monto2);
}


$ld_monto1=str_replace(".","",$ld_monto1);
$ld_monto1=str_replace(",",".",$ld_monto1);
$ld_monto2=str_replace(".","",$ld_monto2);
$ld_monto2=str_replace(",",".",$ld_monto2);

$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
$ls_fecemi2=$io_funcion->uf_convertirdatetobd($ls_fecemi2);
$ld_where="";
if($ld_monto1!='' &&$ld_monto1!='0,00')
{
	$ls_where="  WHERE (monto-montos_cobrado) BETWEEN ".$ld_monto1." AND ".$ld_monto2;	
}

$ls_sql="SELECT codcli,cedcli,razcli,tipocliente,codtipcli,obstipcli, SUM(monto) as monto,SUM(montopar) as montopar,SUM(montos_cobrado) as montos_cobrado
		   FROM ((SELECT c.codcli,c.cedcli,c.razcli,tipocliente,tc.codtipcli,tc.obstipcli, f.monto,f.montopar,SUM(cc.moncancel) as montos_cobrado 
				   FROM sfc_cliente c,sfc_factura f,sfc_cobro_cliente co,sfc_dt_cobrocliente cc,sfc_tipocliente tc
				  WHERE c.codcli=co.codcli AND c.codcli=cc.codcli AND cc.codcli=f.codcli AND c.codemp=co.codemp AND c.codemp=cc.codemp AND f.codemp=co.codemp AND f.codemp=cc.codemp 
					AND f.numfac=cc.numfac AND f.codtiend=co.codtiend AND f.codtiend=cc.codtiend AND co.codemp=cc.codemp AND co.codcli=cc.codcli AND co.numcob=cc.numcob 
					AND co.codtiend=cc.codtiend AND f.estfaccon='P' AND (f.conpag='2' OR f.conpag='3') AND co.estcob<>'A' AND c.cedcli ilike '%%' AND cc.tipcancel<>'n' 
					AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') AND f.codtiend='".$_SESSION["ls_codtienda"]."' 
				    AND c.codtipcli=tc.codtipcli
				  GROUP BY c.codcli,c.razcli,c.cedcli,c.tipocliente,tc.codtipcli,tc.obstipcli,f.monto,f.montopar
				  ORDER BY c.codcli,c.razcli,c.cedcli)
				UNION ALL 
				(SELECT c.codcli,c.cedcli,c.razcli,tipocliente,tc.codtipcli,tc.obstipcli,SUM(fac.monto) as monto,SUM(fac.montopar) as montopar,0 
				   FROM sfc_cliente c,sfc_factura fac ,sfc_tipocliente tc
				  WHERE c.codcli=fac.codcli AND c.codemp=fac.codemp AND fac.estfaccon='N' AND (fac.conpag='2' OR fac.conpag='3') 
					 AND (substr(cast (fac.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (fac.fecemi as char(30)),0,11)<='".$ls_fecemi2."') AND fac.codtiend='".$_SESSION["ls_codtienda"]."' 
					 AND c.codtipcli=tc.codtipcli
				   GROUP BY c.codcli,c.razcli,c.cedcli,c.tipocliente,tc.codtipcli,tc.obstipcli
				  ORDER BY c.codcli,c.razcli,c.cedcli)) a ".$ls_where."
			GROUP BY codcli,cedcli,razcli,tipocliente,codtipcli,obstipcli
		    ORDER BY a.tipocliente,a.codtipcli,a.cedcli";
$reporte->add_titulo("left",25,7,"Fecha emisi�n: ".$ls_fecha);
$existe=0;
$ls_monto2=0;
$li_i2=0;
$li_i3=0;
if ($ls_fecemi<>"")
{
	$ls_fecemi="".substr($ls_fecemi,8,2)."/".substr($ls_fecemi,5,2)."/".substr($ls_fecemi,0,4)."";
	$ls_fecemi2="".substr($ls_fecemi2,8,2)."/".substr($ls_fecemi2,5,2)."/".substr($ls_fecemi2,0,4)."";
	$reporte->add_titulo("right",25,7,"Fecha desde: ".$ls_fecemi."  Hasta ".$ls_fecemi2);
}
$arr_detfactura=$io_sql->select($ls_sql);
$total=0;
$total_tipocliente=0;
$total_general=0;

$total_fact=0;
$total_tipocliente_fact=0;
$total_general_fact=0;

$total_cob=0;
$total_tipocliente_cob=0;
$total_general_cob=0;

$ls_aux_tipocliente="";
$ls_aux_codtipcli="";
/////////////////////Configuracion de tablas////////////////////////////////////
$la_anchos_col1 = array(206);
$la_justificaciones1 = array('center');
$la_opciones1 = array(  "color_fondo" => array(200,200,200),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col1,
					   "tamano_texto"=> 10,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones1,
					   "margen_horizontal"=>1);
$la_justificaciones11 = array('left');					   
$la_opciones11 = array(  "color_fondo" => array(230,230,240),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col1,
					   "tamano_texto"=> 8,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones11,
					   "margen_horizontal"=>1);					   
$la_anchos_col2 = array(20,120,20,20,26);
$la_justificaciones2 = array('center','left','right','right','right');
$la_opciones2 = array(  "color_fondo" => array(255,255,255),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col2,
					   "tamano_texto"=> 7,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones2,
					   "margen_horizontal"=>1);					   
$la_anchos_col3 = array(140,20,20,26);
$la_justificaciones3 = array('right','right','right','right');
$la_opciones3 = array(  "color_fondo" => array(229,229,229),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col3,
					   "tamano_texto"=> 7,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones3,
					   "margen_horizontal"=>1);
$la_opciones4 = array(  "color_fondo" => array(200,200,200),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col3,
					   "tamano_texto"=> 11,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones3,
					   "margen_horizontal"=>1);
while($row=$io_sql->fetch_row($arr_detfactura))
{
	$existe=1;
	$li_i2=$li_i2+1;
	$li_i3=$li_i3+1;	
	$ls_tipocliente=$row["tipocliente"];
	$ls_codtipcli=$row["codtipcli"];
	$ls_dentipcli=$row["obstipcli"];
	$ls_razcli=$row["razcli"];
	//print "<br>".$ls_tipocliente." *  ".$ls_codtipcli." -  ".$ls_dentipcli."  *  ".$ls_razcli."<br>";
	if($ls_tipocliente!=$ls_aux_tipocliente)
	{
		if($li_i3==1)
		{
			//print " --1-- <br>";
			$la_titulo1[0]["<b>TIPOCLIENTE</b>"]= "<b>RED SOCIALISTA</b>";		
			$io_pdf->add_tabla(-25,$la_titulo1,$la_opciones1);
			$io_pdf->add_lineas(1);
			$la_titulo1[0]["<b>TIPOCLIENTE</b>"]= "<b>".$ls_dentipcli."</b>";		
			$io_pdf->add_tabla(-25,$la_titulo1,$la_opciones11);
		//	print_r($la_titulo); 
			unset($la_titulo);
		//	print "<br>";
		}
		else
		{
			//print " --2-- <br>";
			$total=number_format($total,2, ',', '.');
			//$io_pdf->ezSetY(620);
			
			$la_titulos[0]["<b>R.I.F.</b>"]= "<b>R.I.F.</b>";
			$la_titulos[0]["<b>RAZON SOCIAL</b>"]= "<b>RAZON SOCIAL</b>";
			$la_titulos[0]["<b>DEBITO</b>"]="<b>DEBITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>CREDITO</b>"]= "<b>CREDITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>MONTO*COBRAR</b>"]= "<b>MONTO*COBRAR</b>"; //suma de montos cobrados
			$io_pdf->add_tabla(-25,$la_titulos,$la_opciones2);
			$io_pdf->add_tabla(-25,$la_datos  ,$la_opciones2);
		//	print_r($la_datos);
			$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR Bs.</b>";
			$la_datos2[0]["totalfact"]= "<b>".number_format($total_fact,2,",",".")."</b>";
			$la_datos2[0]["totalcob"]= "<b>".number_format($total_cob,2,",",".")."</b>";
			$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL			
			$io_pdf->add_tabla2(-23.5,$la_datos2,$la_opciones3);
			$io_pdf->add_lineas(1);
			$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR RED PUBLICA Bs.</b>";
			$la_datos2[0]["totalfact"]= '<b>'.number_format($total_tipocliente_fact,2,",",".").'</b>';     // SUBTOTAL			
			$la_datos2[0]["totalcob"]= '<b>'.number_format($total_tipocliente_cob,2,",",".").'</b>';     // SUBTOTAL			
			$la_datos2[0]["resultados"]= '<b>'.number_format($total_tipocliente,2,",",".").'</b>';     // SUBTOTAL			
			$io_pdf->add_tabla2(-23.5,$la_datos2,$la_opciones3);
		//	print_r($la_datos2);
			unset($la_datos);unset($la_datos2);$total=0;$total_tipocliente=0;$total_fact=0;$total_tipocliente_fact=0;$total_cob=0;$total_tipocliente_cob=0;						
			$li_i2=1;	
			$io_pdf->ezNewPage();
			$la_titulo1[0]["<b>TIPOCLIENTE</b>"]= "<b>RED PRIVADA</b>";	
			$io_pdf->add_tabla(-25,$la_titulo1,$la_opciones1);
			$io_pdf->add_lineas(1);
			$la_titulo1[0]["<b>TIPOCLIENTE</b>"]= "<b>".$ls_dentipcli."</b>";		
			$io_pdf->add_tabla(-25,$la_titulo1,$la_opciones11);

			/*print_r($la_titulo); 
			print "<br>";*/
		
		}
		
	}
	else
	{
		if($ls_codtipcli!=$ls_aux_codtipcli)
		{
			//print " --3-- <br>";
			$total=number_format($total,2, ',', '.');
			//$io_pdf->ezSetY(620);			
			$la_titulos[0]["<b>R.I.F.</b>"]= "<b>R.I.F.</b>";
			$la_titulos[0]["<b>RAZON SOCIAL</b>"]= "<b>RAZON SOCIAL</b>";
			$la_titulos[0]["<b>DEBITO</b>"]="<b>DEBITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>CREDITO</b>"]= "<b>CREDITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>MONTO*COBRAR</b>"]= "<b>MONTO*COBRAR</b>"; //suma de montos cobrados
			
			$io_pdf->add_tabla(-25,$la_titulos,$la_opciones2);
			$io_pdf->add_tabla(-25,$la_datos  ,$la_opciones2);
		//	print_r($la_datos);
			$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR Bs.</b>";
			$la_datos2[0]["totalfact"]= '<b>'.number_format($total_fact,2,",",".").'</b>';     // SUBTOTAL
			$la_datos2[0]["totalcob"]= '<b>'.number_format($total_cob,2,",",".").'</b>';     // SUBTOTAL
			$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL			
			$io_pdf->add_tabla2(-23.5,$la_datos2,$la_opciones3);
			$io_pdf->add_lineas(1);
		//	print_r($la_datos2);
			unset($la_datos);unset($la_datos2);$total=0;$total_fact=0;$total_cob=0;
			$li_i2=1;
			$la_titulo1[0]["<b>TIPOCLIENTE</b>"]= "<b>".$ls_dentipcli."</b>";		
			$io_pdf->add_tabla(-25,$la_titulo1,$la_opciones11);
		}
	}
	$ls_aux_tipocliente=$ls_tipocliente;
	$ls_aux_codtipcli=$ls_codtipcli;
	$ls_razcli=$row["razcli"];
	$ls_codcli=$row["cedcli"];
	$ls_montos_cobrado=$row["montos_cobrado"];
	$ls_montopar=$row["montopar"];

	$la_datos[$li_i2-1]["<b>R.I.F.</b>"]= $ls_codcli;
	$la_datos[$li_i2-1]["<b>RAZON SOCIAL</b>"]= $ls_razcli;
	$la_datos[$li_i2-1]["<b>DEBITO</b>"]= number_format($ls_montopar,2, ',', '.');
	$la_datos[$li_i2-1]["<b>CREDITO</b>"]= number_format($ls_montos_cobrado,2, ',', '.');
	$la_datos[$li_i2-1]["<b>MONTO*COBRAR</b>"]= number_format($ls_montopar-$ls_montos_cobrado,2, ',', '.');
	$total=$total+($ls_montopar-$ls_montos_cobrado);
	$total_tipocliente=$total_tipocliente+($ls_montopar-$ls_montos_cobrado);
	$total_general=$total_general+($ls_montopar-$ls_montos_cobrado);
	
	$total_fact=$total_fact+$ls_montopar;
	$total_tipocliente_fact=$total_tipocliente_fact+$ls_montopar;
	$total_general_fact=$total_general_fact+$ls_montopar;
	
	$total_cob=$total_cob+$ls_montos_cobrado;
	$total_tipocliente_cob=$total_tipocliente_cob+$ls_montos_cobrado;
	$total_general_cob=$total_general_cob+$ls_montos_cobrado;
}
$total=number_format($total,2, ',', '.');
//$io_pdf->ezSetY(620);
$io_pdf->add_tabla(-25,$la_titulos,$la_opciones2);
$io_pdf->add_tabla(-25,$la_datos,$la_opciones2);

$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR Bs.</b>";
$la_datos2[0]["totalfact"]= '<b>'.number_format($total_fact,2,",",".").'</b>';     // SUBTOTAL
$la_datos2[0]["totalcob"]= '<b>'.number_format($total_cob,2,",",".").'</b>';     // SUBTOTAL
$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL
$io_pdf->add_tabla2(-23.5,$la_datos2,$la_opciones3);
$io_pdf->add_lineas(0);
$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR RED PRIVADA Bs.</b>";
$la_datos2[0]["totalfact"]= '<b>'.number_format($total_tipocliente_fact,2,",",".").'</b>';     // SUBTOTAL
$la_datos2[0]["totalcob"]= '<b>'.number_format($total_tipocliente_cob,2,",",".").'</b>';     // SUBTOTAL
$la_datos2[0]["resultados"]= '<b>'.number_format($total_tipocliente,2,",",".").'</b>';     // SUBTOTAL
$io_pdf->add_tabla2(-23.5,$la_datos2,$la_opciones3);
$io_pdf->add_lineas(0);
$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR GENERAL Bs.</b>";
$la_datos2[0]["totalfact"]= '<b>'.number_format($total_general_fact,2,",",".").'</b>';     // SUBTOTAL
$la_datos2[0]["totalcob"]= '<b>'.number_format($total_general_cob,2,",",".").'</b>';     // SUBTOTAL
$la_datos2[0]["resultados"]= '<b>'.number_format($total_general,2,",",".").'</b>';     // SUBTOTAL
$io_pdf->add_tabla(-24.6,$la_datos2,$la_opciones3);

if ($existe==1 )
{
$io_pdf->ezStream();
}else{
$io_msg->message("No hay Nada que Reportar");
}


?>
