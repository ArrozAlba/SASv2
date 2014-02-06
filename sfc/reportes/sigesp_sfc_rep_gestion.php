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
$ls_desde=$_GET["desde"];
$ls_hasta=$_GET["hasta"];
$ls_fecha=date('d/m/Y');
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
$reporte->add_titulo('center',10,10,"REPORTE DE GESTI�N ".$ls_nomtienda );
$reporte->add_titulo('center',15,10,"PERIODO ".substr($ls_desde,8,2).'/'.substr($ls_desde,5,2).'/'.substr($ls_desde,0,4)." AL ".substr($ls_hasta,8,2).'/'.substr($ls_hasta,5,2).'/'.substr($ls_hasta,0,4) );
$reporte->add_titulo("left",24,7,"Fecha de emisi�n: ".$ls_fecha);
//print $ls_sql1;
$rs_datauni=$io_sql->select($ls_sql1);
$rs_datauni2=$io_sql->select($ls_sql2);
$rs_datauni3=$io_sql->select($ls_sql3);
$rs_datauni4=$io_sql->select($ls_sql4);
$rs_datauni5=$io_sql->select($ls_sql5);
$rs_datauni6=$io_sql->select($ls_sql6);
$rs_datauni10=$io_sql->select($ls_sql10);
if($rs_datauni==false&&($io_sql->message!=""))
{
	//$io_msg->message("No hay registros");
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
	//----------------------------------------------------------------------------------------------
	//------------------------TOTAL CLIENTES EFECTIVO Y CREDITO-------------------------------------
	 $la_datos[0]["<b>TOTAL</b>"]= "<b>CLIENTES QUE COMPRAR�N</b>";
	 $la_datos[1]["<b>TOTAL</b>"]= "<b>CLIENTES QUE NO COMPRAR�N</b>";
	 $la_datos[2]["<b>TOTAL</b>"]= "<b>TOTAL CLIENTES ATENDIDOS</b>";
	 $la_datos[0]["<b>CLIENTES</b>"]= $la_producto["totcli"][$i+1];
	 $la_datos[1]["<b>CLIENTES</b>"]= $la_producto2["totcli"][$i+1];
	 $la_datos[2]["<b>CLIENTES</b>"]= $la_producto["totcli"][$i+1]+$la_producto2["totcli"][$i+1];
	$io_pdf->ezSetY(620);
	$la_anchos_col = array(50,20,50,50);
	$la_justificaciones = array('left','right');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>2,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
    $io_pdf->add_tabla2(0,$la_datos,$la_opciones);
	$la_datos2[0]["<b>CONTADO</b>"]=$la_producto3["totcli"][$i+1];
	$la_datos2[1]["<b>CONTADO</b>"]="";
	$la_datos2[2]["<b>CONTADO</b>"]="";
	$io_pdf->ezSetY(618.5);
	$la_anchos_col = array(20);
	$la_justificaciones = array('center');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
    $io_pdf->add_tabla(70,$la_datos2,$la_opciones);
	$la_datos3[0]["<b>CREDITO</b>"]=$la_producto4["totcli"][$i+1];
	$la_datos3[1]["<b>CREDITO</b>"]="";
	$la_datos3[2]["<b>CREDITO</b>"]="";
	$io_pdf->ezSetY(618.5);
	$la_anchos_col = array(20);
	$la_justificaciones = array('center');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
    $io_pdf->add_tabla(90,$la_datos3,$la_opciones);
	$arr_entidad=$io_sql->select($ls_sql7);
	if($arr_entidad==false&&($io_sql->message!=""))
			{
				//$is_msg->message("No hay registros de municipios");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_entidad))
 				  {
					$la_entidad=$io_sql->obtener_datos($arr_entidad);
					$io_datastore->data=$la_entidad;
					$totrow=$io_datastore->getRowCount("id_entidad");
					$ls_codigoant=$io_datastore->getValue("id_entidad",1);
					$j=0;
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("id_entidad",$li_i);
						$ls_denent=$io_datastore->getValue("denominacion",$li_i);
						$ls_cadena5="SELECT COUNT (i.codcli) as total".
						" FROM sfc_instpago i,sfc_factura f WHERE i.id_entidad='".$ls_codigo."'".
						" AND f.numfac=i.numfac AND f.codcli=i.codcli ".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND i.codtiend=f.codtiend";

						$ls_cadena6="SELECT COUNT (i.codcli) as total".
						" FROM sfc_instpago i,sfc_factura f WHERE ".
						" f.codcli=i.codcli AND i.numfac=f.numfac AND f.fecemi>='".$ls_desde."'".
						" AND i.codforpag='04' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A' AND i.codtiend=f.codtiend";
						//print $ls_cadena5."--".$ls_cadena6."<br>";
						$arr_entidadcliente=$io_sql->select($ls_cadena5);
						$la_total=$io_sql->obtener_datos($arr_entidadcliente);
						$arr_entidadcliente2=$io_sql->select($ls_cadena6);
						$la_total2=$io_sql->obtener_datos($arr_entidadcliente2);
						$la_datos5[$j]["<b>ENTIDADES CREDITICIAS</b>"]="<b>".$ls_denent.": </b>".$la_total["total"][$i+1];
						$la_datos5[$j+1]["<b>ENTIDADES CREDITICIAS</b>"]="<b>Total de Facturas con Carta Orden: </b>".$la_total2["total"][$i+1];
						$j++;
						}
						$ls_codigoant=$ls_codigo;
					}
					}
	$io_pdf->ezSetY(618.5);
	$la_anchos_col = array(50);
	$la_justificaciones = array('left');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
    $io_pdf->add_tabla(110,$la_datos5,$la_opciones);
	//----------------------------------------------------------------------------------------------
	//-------------------------------TOTAL CLIENTES*MUNICIPIOS--------------------------------------
	$arr_municipios=$io_sql->select($ls_sql5);
			if($arr_municipios==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de municipios");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_municipios))
 				  {
					$la_municipios=$io_sql->obtener_datos($arr_municipios);
					$io_datastore->data=$la_municipios;
					$totrow=$io_datastore->getRowCount("codmun");
					$ls_codigoant=$io_datastore->getValue("codmun",1);
					$io_pdf->add_lineas(2);
					$la_datos4[0]["<b>MUNICIPIOS</b>"]= "<b>MUNICIPIOS</b>";
					$la_datos4[0]["<b>CLIENTES</b>"]= "<b>CLIENTES</b>";
					$la_datos4[0]["<b>MONTO Bs.</b>"]= "<b>MONTO TOTAL</b>";
						$la_anchos_col = array(50,20,40);
						$la_justificaciones = array('center','center','center');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla2(0,$la_datos4,$la_opciones);
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codmun",$li_i);
						$ls_denmun=$io_datastore->getValue("denmun",$li_i);
						$ls_descripcion=$io_datastore->getValue("denmun",$li_i);
						$ls_cadena="SELECT COUNT(DISTINCT f.codcli) as tcliente,SUM(f.monto) as tmonto".
						" from sfc_factura f,sfc_cliente c where f.codcli=c.codcli and c.codmun='".$ls_codigo."'".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
						//print $ls_cadena."<br>";
						$arr_clientes=$io_sql->select($ls_cadena);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);
						$la_datos4[0]["<b>MUNICIPIOS</b>"]= "<b>".$ls_denmun."</b>";
						$la_datos4[0]["<b>CLIENTES</b>"]= $la_clientes["tcliente"][$i+1];
						$la_datos4[0]["<b>MONTO Bs.</b>"]= number_format($la_clientes["tmonto"][$i+1],2, ',', '.');
						if ($la_datos4[0]["<b>MONTO Bs.</b>"]!='0,00')
						{
						$la_anchos_col = array(50,20,40);
						$la_justificaciones = array('left','right','right');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla2(0,$la_datos4,$la_opciones);
						}
						$ls_codigoant=$ls_codigo;
					}
					$ls_codest=$_SESSION["ls_codest"];
					$ls_cadena2="SELECT COUNT(DISTINCT f.codcli) as tcliente,SUM(f.monto) as tmonto".
						" from sfc_factura f,sfc_cliente c where f.codcli=c.codcli and c.codest<>'".$ls_codest."'".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
					//print $ls_cadena2."<br>";
						$arr_clientes=$io_sql->select($ls_cadena2);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);

						$la_datos4[0]["<b>MUNICIPIOS</b>"]= "<b> OTROS ESTADOS </b>";
						$la_datos4[0]["<b>CLIENTES</b>"]= $la_clientes["tcliente"][$i+1];
						$la_datos4[0]["<b>MONTO Bs.</b>"]= number_format($la_clientes["tmonto"][$i+1],2, ',', '.');
						$la_anchos_col = array(50,20,40);
						$la_justificaciones = array('left','right','right');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla2(0,$la_datos4,$la_opciones);
				}
			}
			//----------------------------------------------------------------------------------------------
			//----------------------------TOTAL CLIENTES*TENECIAS DE TIERRA---------------------------------

				if($row2=$io_sql->fetch_row($rs_datauni6))
 				  {
					$la_tenencias=$io_sql->obtener_datos($rs_datauni6);
					$io_datastore->data=$la_tenencias;
					$totrow2=$io_datastore->getRowCount("codcli");
					$ls_codigoant=$io_datastore->getValue("codigo",1);
					$io_pdf->add_lineas(2);
					$i=0;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codigo",$li_i);
						$ls_cadena="SELECT COUNT(DISTINCT f.codcli) as tcliente,t.denominacion".
						" from sfc_factura f,sfc_cliente c,sfc_tenenciatierra t, sfc_productor p where f.codcli=c.codcli and ".
						" c.codcli=p.codcli and p.codtenencia='".$ls_codigo."' and p.codcli=f.codcli AND t.codtenencia=p.codtenencia ".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A' GROUP BY t.denominacion";
						//print $ls_cadena."<br>";
						$arr_clientes=$io_sql->select($ls_cadena);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);
						if ($la_clientes["tcliente"][$li+1]!='0')
						{
						$la_datos6[$i]["<b>TENENCIA</b>"]="<b>".$la_clientes["denominacion"][$li+1]."</b>";
						$la_anchos_col = array(50,40,40);
						$la_datos6[$i]["<b>TOTAL CLIENTES</b>"]= $la_clientes["tcliente"][$li+1];
						$ls_codigoant=$ls_codigo;
						$i++;
						}
					}
					$ls_cadena3="SELECT COUNT(DISTINCT f.codcli) as tcliente".
						" from sfc_factura f,sfc_cliente c,sfc_productor p where f.codcli=c.codcli and p.codcli=c.codcli and p.codtenencia='' and p.codcli=f.codcli".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
						//print $ls_cadena3."<br>";
						$arr_clientes=$io_sql->select($ls_cadena3);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);
						if ($la_clientes["tcliente"][1]>0)
						{
						$la_datos6[$i]["<b>TENENCIA</b>"]="<b>CLIENTES NO PRODUCTORES</b>";
						$la_datos6[$i]["<b>TOTAL CLIENTES</b>"]= $la_clientes["tcliente"][1];
						}
					    $la_anchos_col = array(50,40);
						$la_justificaciones = array('LEFT','right');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>2,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
					$io_pdf->add_tabla(0,$la_datos6,$la_opciones);
				}
			//----------------------------------------------------------------------------------------------
			//----------------------------TOTAL HAS*RUBRO AGRICOLA---------------------------------
				$arr_rubroagri=$io_sql->select($ls_sql8);
				if($row2=$io_sql->fetch_row($arr_rubroagri))
 				  {
					$la_rubroagri=$io_sql->obtener_datos($arr_rubroagri);
					$io_datastore->data=$la_rubroagri;
					$totrow2=$io_datastore->getRowCount("id_clasificacion");
					$io_pdf->add_lineas(2);
					$i=0;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$la_datos8[$i]["<b>RUBRO AGR�COLA</b>"]="<b>".$la_rubroagri["denominacion"][$i+1]."</b>";
						$la_datos8[$i]["<b>HAS PRODUCTIVAS</b>"]=number_format($la_rubroagri["thas"][$i+1],2, ',', '.');
						$la_datos8[$i]["<b>CANTIDAD DE PRODUCCI�N</b>"]=number_format($la_rubroagri["tprod"][$i+1],2, ',', '.');

						$i++;
					}
					}

					    $la_anchos_col = array(50,40,40);
						$la_justificaciones = array('LEFT','right','right');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>2,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
					$io_pdf->add_tabla(0,$la_datos8,$la_opciones);

			//----------------------------------------------------------------------------------------------
			//----------------------------TOTAL HAS*RUBRO PECUARIO---------------------------------
				$arr_rubropec=$io_sql->select($ls_sql9);
				if($row2=$io_sql->fetch_row($arr_rubropec))
 				  {
					$la_rubropec=$io_sql->obtener_datos($arr_rubropec);
					$io_datastore->data=$la_rubropec;
					$totrow2=$io_datastore->getRowCount("id_clasificacion");
					$io_pdf->add_lineas(2);
					$i=0;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$la_datos9[$i]["<b>RUBRO PECUARIO</b>"]="<b>".$la_rubropec["denominacion"][$i+1]." (".$la_rubropec["rubro"][$i+1].")</b>";
						$la_datos9[$i]["<b>HAS PRODUCTIVAS</b>"]=number_format($la_rubropec["hectprorp"][$i+1],2, ',', '.');
						$la_datos9[$i]["<b>NRO. DE ANIMALES</b>"]=number_format($la_rubropec["tnro_animal"][$i+1],2, ',', '.');
						$la_datos9[$i]["<b>CANTIDAD DE PRODUCCI�N</b>"]=number_format($la_rubropec["tcantrp"][$i+1],2, ',', '.');
						$i++;
					}
					}

					    $la_anchos_col = array(50,40,40);
						$la_justificaciones = array('LEFT','right','right');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>2,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
					$io_pdf->add_tabla(0,$la_datos9,$la_opciones);
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
						$ls_cadena="SELECT cla.codcla,cla.dencla FROM sfc_factura f,sfc_clasificacion cla,".
						"sfc_producto p,sfc_detfactura df,sfc_uso u,sfc_tipouso tu,sim_articulo a WHERE a.codcla=cla.codcla AND ".
						"a.codart=df.codart AND df.codart=p.codart AND p.codart=a.codart AND a.id_uso=u.id_uso AND u.id_tipouso=tu.id_tipouso AND ".
						"tu.dentipouso='".$ls_den."' AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."')".
						" AND f.numfac=df.numfac AND f.estfaccon<>'A' AND df.codtiend=f.codtiend GROUP BY cla.codcla,cla.dencla;";
						//print $ls_cadena."<br>";
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
						$ls_cadena="SELECT sub.cod_sub,max(df.porimp),sub.den_sub,sum(df.canpro) as tcant, ".
					"sum(((df.canpro*df.prepro)*(df.porimp/100))+(df.canpro*df.prepro)) as monto,sum((df.canpro*df.prepro)*(df.porimp/100)) as iva".
					" FROM sfc_subclasificacion sub,sfc_detfactura df,sfc_clasificacion cla,sfc_producto p,sfc_tipouso tu,sim_articulo a,".
					" sfc_factura f,sfc_uso u WHERE sub.cod_sub=a.cod_sub AND p.codart=df.codart AND a.codart=p.codart AND a.codart=df.codart AND cla.codcla='".$ls_codcla."'".
					" AND tu.dentipouso='".$ls_den."' AND cla.codcla=a.codcla AND sub.codcla=cla.codcla AND f.numfac=df.numfac ".
					" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A' AND u.id_tipouso=tu.id_tipouso AND u.id_uso=a.id_uso".
					" GROUP BY sub.cod_sub,sub.den_sub;";
					//print $ls_cadena."<br>";
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
						$TOTAL=$TOTAL+$la_sub["monto"][$k+1];
						$den=$la_sub["den_sub"][$k+1];
						$ls_totalgeneral=$ls_totalgeneral+$la_sub["monto"][$k+1];
						$k++;
						}
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
						$io_pdf->add_tabla(0,$la_datos12,$la_opciones);

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
						//--------------------------------------------------------------------------------------
						//------------------------TOTAL GENERAL-------------------------------------------------
						$ls_cadenas="SELECT SUM(((df.canpro*df.prepro)*df.porimp/100)+(df.canpro*df.prepro)) as coniva,SUM(((df.canpro*df.prepro)*df.porimp/100)+(df.canpro*df.prepro)) from sfc_factura f,sfc_detfactura df where substr(f.fecemi,0,11)>='". $ls_desde."' AND ".
						"substr(f.fecemi,0,11)<='".$ls_hasta."' AND f.estfaccon<>'A' AND df.numfac=f.numfac AND df.codemp=f.codemp AND df.porimp<>0 AND f.codtiend=df.codtiend";
						//print $ls_cadenas."<br>";
						$arr_total3=$io_sql->select($ls_cadenas);
						$la_totalgeneral3=$io_sql->obtener_datos($arr_total3);
						$io_datos3->data=$la_totalgeneral3;
						//$totrows=$io_datos->getRowCount("numfac");
						$la_anchos_col = array(50,50,50);
						$la_justificaciones = array('left','left','left');
						//print $la_totalgeneral["montototal"][0];
						$la_datos15['0']["<b>TOTAL CON IVA</b>"]='<b>TOTAL CON IVA: '.number_format($la_totalgeneral3["coniva"][1],2, ',', '.').'</b>';

					$ls_cadena2="SELECT SUM((df.canpro*df.prepro)) as siniva FROM sfc_detfactura df WHERE ".
					"df.numfac IN (SELECT numfac FROM sfc_factura WHERE substr(fecemi,0,11)>='". $ls_desde."' AND ".
					"substr(fecemi,0,11)<='".$ls_hasta."' AND estfaccon<>'A') AND df.porimp=0";
						//print $ls_cadena."<br>";
						$arr_total2=$io_sql->select($ls_cadena2);
						$la_totalgeneral2=$io_sql->obtener_datos($arr_total2);
						$io_datos2->data=$la_totalgeneral2;
						$la_datos15['0']["<b>TOTAL SIN IVA</b>"]='<b> TOTAL SIN IVA: '.number_format($la_totalgeneral2["siniva"][1],2, ',', '.').'</b>';
						$ls_cadena="SELECT SUM(f.montoret) as montoiva,SUM(f.monto) as montototal FROM sfc_factura f".
						" WHERE substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
					//print $ls_cadena."<br>";
						$arr_total=$io_sql->select($ls_cadena);
						$la_totalgeneral=$io_sql->obtener_datos($arr_total);
						$io_datos->data=$la_totalgeneral;
						$la_datos15['0']["<b>TOTAL VENDIDO</b>"]='<b> TOTAL VENDIDO: '.number_format($la_totalgeneral["montototal"][1],2, ',', '.').'</b>';
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
