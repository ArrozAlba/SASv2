<?Php
/******************************************/
/* FECHA: 27/09/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/
session_start();
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";
	}
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','landscape');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_data2= new class_datastore();
$io_data3=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$ls_fecha=date('d/m/Y');
$ls_den_cla=$_GET["clasificacion"];
$ls_den_sub=$_GET["subclasificacion"];
$ls_nomtienda=$_GET["nomtie"];
$ls_sql1=$_GET["sql1"];
$ls_sql1=str_replace("\\","",$ls_sql1);
$ls_sql2=$_GET["sql2"];
$ls_sql2=str_replace("\\","",$ls_sql2);
$ls_sql3=$_GET["sql3"];
$ls_sql3=str_replace("\\","",$ls_sql3);
$ls_sql4=$_GET["sql4"];
$ls_sql4=str_replace("\\","",$ls_sql4);
$ls_sql5=$_GET["sql5"];
$ls_sql5=str_replace("\\","",$ls_sql5);
$ls_sql6=$_GET["sql6"];
$ls_sql6=str_replace("\\","",$ls_sql6);
$ls_sql7=$_GET["sql7"];
$ls_sql7=str_replace("\\","",$ls_sql7);
$ls_sql8=$_GET["sql8"];
$ls_sql8=str_replace("\\","",$ls_sql8);
$ls_sql9=$_GET["sql9"];
$ls_sql9=str_replace("\\","",$ls_sql9);
$ls_sql10=$_GET["sql10"];
$ls_sql10=str_replace("\\","",$ls_sql10);
$reporte->add_titulo('center',10,10,"PRODUCTOS POR CLASIFICACI�N ".$ls_nomtienda );
$reporte->add_titulo("left",24,7,"Fecha de emisi�n: ".$ls_fecha);
$rs_datauni=$io_sql->select($ls_sql1);
$rs_datauni2=$io_sql->select($ls_sql2);
$rs_datauni3=$io_sql->select($ls_sql3);
$rs_datauni4=$io_sql->select($ls_sql4);
$rs_datauni5=$io_sql->select($ls_sql5);
$rs_datauni6=$io_sql->select($ls_sql6);
$rs_datauni10=$io_sql->select($ls_sql10);
if($rs_datauni==false&&($io_sql->message!=""))
{

}
else
{
   $la_producto=$io_sql->obtener_datos($rs_datauni);
   $la_producto2=$io_sql->obtener_datos($rs_datauni2);
   $la_producto3=$io_sql->obtener_datos($rs_datauni3);
   $la_producto4=$io_sql->obtener_datos($rs_datauni4);
    }
if ($la_producto)
{
					//--------------------------------------------------------------------------------------
					//------------------------TOTAL VENTAS POR PRODUCTO-------------------------------------
					if($row2=$io_sql->fetch_row($rs_datauni10))
 				  {
					$la_productos=$io_sql->obtener_datos($rs_datauni10);
					$io_datastore->data=$la_productos;
					$totrow2=$io_datastore->getRowCount("id_tipouso");
					$ls_codigoant=$io_datastore->getValue("id_tipouso",1);
					$io_pdf->add_lineas(1);
					$i=0;
					$ls_siniva=0;
					$ls_totaliva=0;
					$ls_totalgeneral=0;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{

						$ls_codigo=$io_datastore->getValue("codigo",$li_i);
						$ls_den=$io_datastore->getValue("dentipouso",$li_i);
						$la_datos10['0']["<b>PRODUCTOS VENDIDOS</b>"]='<b>PRODUCTOS VENDIDOS POR USO '.$ls_den.'</b>';
						$la_anchos_col = array(150);
						$la_justificaciones = array('center');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 8,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla3(0,$la_datos10,$la_opciones);
						$io_pdf->add_lineas(0.1);
						/*$ls_cadena="SELECT cla.codcla,cla.dencla FROM sfc_factura f,sfc_subclasificacion su,sfc_clasificacion cla,".
						"sfc_producto p,sfc_detfactura df,sfc_uso u,sfc_tipouso tu WHERE p.codcla=cla.codcla AND ".
						"p.codpro=df.codpro AND p.id_uso=u.id_uso AND cla.dencla='".$ls_den_cla."' AND u.id_tipouso=tu.id_tipouso AND ".
						"tu.dentipouso='".$ls_den."' ".
						" AND f.numfac=df.numfac GROUP BY cla.codcla,cla.dencla;";*/

						$ls_cadena="SELECT cla.codcla,cla.dencla FROM sfc_factura f,sfc_clasificacion cla,sfc_subclasificacion su,".
						"sfc_producto p,sfc_detfactura df,sfc_uso u,sfc_tipouso t,sim_articulo a WHERE " .
						" f.codemp=u.codemp AND f.codemp=t.codemp AND f.codemp=df.codemp" .
						" AND f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=df.numfac AND f.codtiend=df.codtiend AND f.codtiend=p.codtiend  " .
						" AND u.codemp=t.codemp AND u.codemp=df.codemp AND u.codemp=p.codemp AND u.codemp=a.codemp AND u.id_tipouso=t.id_tipouso " .
						" AND u.id_uso=a.id_uso AND t.codemp=df.codemp AND t.codemp=p.codemp AND df.codemp=p.codemp AND df.codemp=a.codemp " .
						" AND df.codart=p.codart AND df.codart=a.codart AND df.codart=p.codart AND df.codtiend=p.codtiend AND p.codemp=a.codemp " .
						" AND p.codart=a.codart AND a.codcla=cla.codcla AND t.dentipouso='".$ls_den."' AND cla.dencla='".$ls_den_cla."' ".
						" AND f.numfac=df.numfac AND su.codcla=cla.codcla GROUP BY cla.codcla,cla.dencla;";

						$arr_clasificacion=$io_sql->select($ls_cadena);
						$la_clasificacion=$io_sql->obtener_datos($arr_clasificacion);
						$io_data2->data=$la_clasificacion;
						$totrow3=$io_data2->getRowCount("codcla");
						$ls_codclaant=$io_datastore->getValue("codcla",$i);
						$j=0;
						$TOTALUSO=0;
						for ($li_j=1;$li_j<=$totrow3;$li_j++)
						{
						$ls_codcla=$la_clasificacion["codcla"][$j+1];
						$la_datos11["0"]["<b>CLASIFICACION</b>"]="<b>".$la_clasificacion["dencla"][$j+1]."</b>";
						$ls_dencla=	"<b>".$la_clasificacion["dencla"][$j+1]."</b>";
						$la_anchos_col = array(150);
						$la_justificaciones = array('left');
						$la_opciones = array(  "color_fondo" => array(0,0,0),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>2,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla2(0,$la_datos11,$la_opciones);
						$io_pdf->add_lineas(0.5);
						/*$ls_cadena="SELECT sub.cod_sub,max(df.porimp),sub.den_sub,sum(df.canpro) as tcant, ".
					"sum(((df.canpro*df.prepro)*(df.porimp/100))+(df.canpro*df.prepro)) as monto,sum((df.canpro*df.prepro)*(df.porimp/100)) as iva".
					" FROM sfc_subclasificacion sub,sfc_detfactura df,sfc_clasificacion cla,sfc_producto p,sfc_tipouso tu,".
					" sfc_factura f,sfc_uso u WHERE sub.cod_sub=p.cod_sub AND p.codpro=df.codpro AND cla.codcla='".$ls_codcla."'".
					" AND tu.dentipouso='".$ls_den."' AND cla.codcla=p.codcla AND sub.codcla=cla.codcla AND f.numfac=df.numfac ".
					" AND u.id_tipouso=tu.id_tipouso AND u.id_uso=p.id_uso ".
					" GROUP BY sub.cod_sub,sub.den_sub;";*/


					$ls_cadena="SELECT sub.cod_sub,max(df.porimp),sub.den_sub,sum(df.canpro) as tcant, sum(((df.canpro*df.prepro)*(df.porimp/100))+" .
							" (df.canpro*df.prepro)) as monto,sum((df.canpro*df.prepro)*(df.porimp/100)) as iva " .
							" FROM sfc_subclasificacion sub,sfc_detfactura df,sfc_clasificacion cla,sfc_producto p,sfc_tipouso tu,sim_articulo a," .
							" sfc_factura f,sfc_uso u WHERE sub.cod_sub=a.cod_sub AND a.codart=df.codart AND cla.codcla='".$ls_codcla."' " .
							" AND tu.dentipouso='".$ls_den."' AND cla.codcla=a.codcla AND sub.codcla=cla.codcla AND f.numfac=df.numfac " .
							" AND u.id_tipouso=tu.id_tipouso AND u.id_uso=a.id_uso AND p.codart=a.codart AND p.codemp=a.codemp" .
							" GROUP BY sub.cod_sub,sub.den_sub";

						$arr_sub=$io_sql->select($ls_cadena);
						$la_sub=$io_sql->obtener_datos($arr_sub);
						$io_data3->data=$la_sub;
						$totrow4=$io_data3->getRowCount("cod_sub");
						$ls_codsubant=$io_data3->getValue("cod_sub",$j);
						$m=$k;
						$k=0;
						$TOTAL=0;

						$la_datos12="";
						$k=0;
						for ($li_k=1;$li_k<=$totrow4;$li_k++)
						{
						$ls_codsub=$la_sub["cod_sub"][$k+1];
						$la_datos12[$k]["<b>SUBCLASIFICACION</b>"]=$la_sub["den_sub"][$k+1];
						$la_datos12[$k]["<b>UNIDADES</b>"]=number_format($la_sub["tcant"][$k+1],2, ',', '.');
						$la_datos12[$k]["<b>MONTO Bs.</b>"]=number_format($la_sub["monto"][$k+1],2, ',', '.');
						$iva=number_format($la_sub["porimp"][$k+1],2, ',', '.');
						if ($iva=='0,00')
						{

						$ls_siniva=$ls_totalsiniva+$la_sub["monto"][$k+1];

						}else{
						$ls_totaliva=$ls_totaliva+$la_sub["iva"][$k+1];
						}
						$TOTAL=$TOTAL+$la_sub["monto"][$k+1];
						$den=$la_sub["den_sub"][$k+1];
						$ls_totalgeneral=$ls_totalgeneral+$la_sub["monto"][$k+1];
						$k++;
						}	//fin for subclasificacion
						$TOTALUSO=$TOTALUSO+$TOTAL;
						$la_anchos_col = array(50,50,50);
						$la_justificaciones = array('left','right','right');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla4(0,$la_datos12,$la_opciones);

						$la_anchos_col = array(100,50);
						$la_justificaciones = array('right','right');
						$la_datos13['0']["total"]='<b> TOTAL </b>'.$ls_dencla;
						$la_datos13['0']["monto"]='<b>'.number_format($TOTAL,2, ',', '.').'</b>';
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla4(0,$la_datos13,$la_opciones);
						$io_pdf->add_lineas(0.5);
						if ($ls_codclaant!=$ls_codcla)
						{
						$j++;
						}
						$ls_codclaant=$ls_codcla;
						}
						$la_anchos_col = array(100,50);
						$la_justificaciones = array('right','right');
					if($TOTALUSO<>"0,00"){
						$la_datos13['0']["total"]='<b> TOTAL USO '.$ls_den.'</b>';
						$la_datos13['0']["monto"]='<b>'.number_format($TOTALUSO,2, ',', '.').'</b>';
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla4(0,$la_datos13,$la_opciones);
						$io_pdf->add_lineas(1);
						$ls_codigoant=$ls_codigo;
						$i++;
					}
						}
						$la_anchos_col = array(50,50,50);
						$la_justificaciones = array('left','left','left');
						$la_datos15['0']["<b>TOTAL IVA</b>"]='<b>TOTAL IVA: '.number_format($ls_totaliva,2, ',', '.').'</b>';
						$la_datos15['0']["<b>TOTAL SIN IVA</b>"]='<b> TOTAL SIN IVA: '.number_format($ls_siniva,2, ',', '.').'</b>';
						$la_datos15['0']["<b>TOTAL VENDIDO</b>"]='<b> TOTAL VENDIDO: '.number_format($ls_totalgeneral,2, ',', '.').'</b>';
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla3(0,$la_datos15,$la_opciones);
						}
$io_pdf->ezStream();
}else{
$io_msg->message("No hay Nada que Reportar");
}
?>