<?php
    session_start();
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "close();";
		 print "</script>";
	   }


	   if (array_key_exists("desde",$_POST))
	   {
	     $ls_fechadesde=$_POST["desde"];
	   }
    else
	   {
	     $ls_fechadesde=$_GET["desde"];
	   }
	   if (array_key_exists("hasta",$_POST))
	   {
	     $ls_fechahasta=$_POST["hasta"];
	   }
    else
	   {
	     $ls_fechahasta=$_GET["hasta"];
	   }

	//print $ls_fechadesde;//-----------------------------------------------------------------------------------------------------------------------------------
	/*function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 22/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;

		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_cuentas.php",$ls_descripcion);
		return $lb_valido;
	}*/
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio
		//	Description: Este m�todo realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;

		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/tmp", "gestion.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_sfc_c_reportes.php");
		$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");

		$io_in    = new sigesp_include();
		$con      = $io_in->uf_conectar();
		$io_sql   = new class_sql($con);
		$io_sql2   = new class_sql($con);
		$io_sql3   = new class_sql($con);
		$io_sql5   = new class_sql($con);
		$io_sql9   = new class_sql($con);
		$io_sql10   = new class_sql($con);
		$io_data2= new class_datastore();
		$io_data3= new class_datastore();
		$io_datastore5= new class_datastore();
		$io_datastore4= new class_datastore();
		$io_datastore= new class_datastore();
		$io_datastore2= new class_datastore();
		$io_datastore3= new class_datastore();



		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();

		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte
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


		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "REPORTE DE GESTION DE ".$ls_nomtienda;
		$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_sql="Select dentie from sfc_tienda where codtie='".$ls_codtie."' ";
		$rs_data=$io_sql->select($ls_sql);
			if($rs_data==false&&($io_sql->message!=""))
			{
				//$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_data))
				{
					$la_agrotienda=$io_sql->obtener_datos($rs_data);
					$io_datastore4->data=$la_agrotienda;
					$totrowt=$io_datastore4->getRowCount("dentie");
					//print_r ($la_agrotienda);

						//print "Paso".$totrowt;
					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}


			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_align('center');
			$lo_encabezado->set_size('11');
			$lo_titulo= &$lo_libro->addformat();
			$lo_titulo->set_bold();
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('9');

			$lo_total= &$lo_libro->addformat();
			$lo_total->set_bold();
			$lo_total->set_font("Verdana");
			$lo_total->set_align('right');
			$lo_total->set_size('9');

			$lo_resumen= &$lo_libro->addformat();
			$lo_resumen->set_bold();
			$lo_resumen->set_font("Verdana");
			$lo_resumen->set_align('left');
			$lo_resumen->set_size('9');


			$lo_datacenter= &$lo_libro->addformat();
			$lo_datacenter->set_font("Verdana");
			$lo_datacenter->set_align('center');
			$lo_datacenter->set_size('9');
			$lo_dataleft= &$lo_libro->addformat();
			$lo_dataleft->set_text_wrap();
			$lo_dataleft->set_font("Verdana");
			$lo_dataleft->set_align('left');
			$lo_dataleft->set_size('9');
			$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');
			$lo_hoja->set_column(0,1,20);
			$lo_hoja->write(0,1,$ls_nombemp,$lo_encabezado);

			$lo_hoja->write(2,1,$ls_titulo,$lo_encabezado);

			$as_fechadesde = substr($ls_fechadesde,8,2).'/'.substr($ls_fechadesde,5,2).'/'.substr($ls_fechadesde,0,4);
			$as_fechahasta = substr($ls_fechahasta,8,2).'/'.substr($ls_fechahasta,5,2).'/'.substr($ls_fechahasta,0,4);


			$lo_hoja->write(3,1,"PERIODO ".$as_fechadesde." AL ".$as_fechahasta,$lo_encabezado);

			$lo_hoja->write(4,0,"Fecha de Emision: ".$ls_fecha,$lo_dataleft);



			$lo_hoja->write(5, 0, "CLIENTES QUE COMPRARON",$lo_titulo);

			$lo_hoja->write(6, 0, "CLIENTES QUE NO COMPRARON",$lo_titulo);

			$lo_hoja->write(7, 0, "TOTAL CLIENTES ATENDIDOS",$lo_titulo);




			$li_row=5;

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

	$ls_totalcli=$la_producto["totcli"][$i+1];
	$ls_totalcli2= $la_producto2["totcli"][$i+1];
	$ls_totalcliente= $la_producto["totcli"][$i+1]+$la_producto2["totcli"][$i+1];

	$lo_hoja->set_column(5,1,30);
	$lo_hoja->write(5, 1, $ls_totalcli,$lo_datacenter);
	$lo_hoja->set_column(6,1,30);
	$lo_hoja->write(6, 1, $ls_totalcli2,$lo_datacenter);
	$lo_hoja->set_column(7,1,30);
	$lo_hoja->write(7, 1, $ls_totalcliente,$lo_titulo);



	$lo_hoja->set_column(5,2,30);
	$lo_hoja->write(5, 2, "CONTADO",$lo_titulo);
	$lo_hoja->set_column(5,3,30);
	$lo_hoja->write(5, 3, "CREDITO",$lo_titulo);

	$ls_clicontado=$la_producto3["totcli"][$i+1];
	$lo_hoja->set_column(6,2,30);
	$lo_hoja->write(6, 2, $ls_clicontado,$lo_datacenter);

	$ls_clicredito=$la_producto4["totcli"][$i+1];
	$lo_hoja->set_column(6,3,30);
	$lo_hoja->write(6, 3, $ls_clicredito,$lo_datacenter);


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
					$j=0;$i=0;
					$li_row=6;
					$li_ne=4;
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
						//print $ls_cadena5;
						$arr_entidadcliente=$io_sql9->select($ls_cadena5);
						$la_total=$io_sql9->obtener_datos($arr_entidadcliente);
						$arr_entidadcliente2=$io_sql10->select($ls_cadena6);
						$la_total2=$io_sql10->obtener_datos($arr_entidadcliente2);
						$ls_entidad=$ls_denent.": ".$la_total["total"][$i+1];
						$ls_totalfac=$la_total2["total"][$i+1];


							$lo_hoja->set_column(0,4,25);
						$lo_hoja->write(5, 4, "ENTIDADES CREDITICIAS",$lo_titulo);
						$lo_hoja->write($li_row, $li_ne, $ls_entidad , $lo_dataleft);
						$li_ne=$li_ne++;
						$li_row++;

						$j++;
						}
						$li_row++;
						$lo_hoja->write($li_row, $li_ne, "Total de Facturas con Carta Orden: ".$ls_totalfac, $lo_dataleft);
						$ls_codigoant=$ls_codigo;
					}
					}

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


					$lo_hoja->set_column(10,0,30);
					$lo_hoja->write(10, 0, "MUNICIPIOS",$lo_titulo);
					$lo_hoja->set_column(10,1,30);
					$lo_hoja->write(10, 1, "CLIENTES",$lo_titulo);
					$lo_hoja->set_column(10,2,30);
					$lo_hoja->write(10, 2, "MONTO TOTAL",$lo_titulo);

					$li_row=11;
					$li_cli=1;
					$li_nummonto=2;
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codmun",$li_i);
						$ls_denmun=$io_datastore->getValue("denmun",$li_i);
						$ls_descripcion=$io_datastore->getValue("denmun",$li_i);
						$ls_cadena="SELECT COUNT(DISTINCT f.codcli) as tcliente,SUM(f.monto) as tmonto".
						" from sfc_factura f,sfc_cliente c where f.codcli=c.codcli and c.codmun='".$ls_codigo."'".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
						//print $ls_cadena;
						$arr_clientes=$io_sql->select($ls_cadena);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);


						$lo_hoja->write($li_row,0, $ls_denmun , $lo_dataleft);


						$li_numcli= $la_clientes["tcliente"][$i+1];
						$li_monto= number_format($la_clientes["tmonto"][$i+1],2, ',', '.');
						if ($li_monto!='0,00')
						{

							$lo_hoja->set_column(10,2,30);
							$lo_hoja->write($li_row, $li_cli, $li_numcli,$lo_datacenter);

							$lo_hoja->set_column(10,3,30);
							$lo_hoja->write($li_row, $li_nummonto, $li_monto,$lo_dataright);

						$li_row++;

						}

						$ls_codigoant=$ls_codigo;
					}



					$ls_codest=$_SESSION["ls_codest"];
					$ls_cadena2="SELECT COUNT(DISTINCT f.codcli) as tcliente,SUM(f.monto) as tmonto".
						" from sfc_factura f,sfc_cliente c where f.codcli=c.codcli and c.codest<>'".$ls_codest."'".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
					//print $ls_cadena2;
						$arr_clientes=$io_sql->select($ls_cadena2);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);

						$lo_hoja->write($li_row, 0, "OTROS ESTADOS" , $lo_dataleft);



						$li_numcliotros= $la_clientes["tcliente"][$i+1];
						$li_montootros= number_format($la_clientes["tmonto"][$i+1],2, ',', '.');
						$lo_hoja->set_column(10,2,30);
						$lo_hoja->write($li_row, $li_cli, $li_numcliotros,$lo_datacenter);

						$lo_hoja->set_column(10,3,30);
						$lo_hoja->write($li_row, $li_nummonto, $li_montootros,$lo_dataright);


				}
			}
			//----------------------------------------------------------------------------------------------
			//----------------------------TOTAL CLIENTES*TENECIAS DE TIERRA---------------------------------

			$li_inipro=$li_row+1;
				if($row2=$io_sql->fetch_row($rs_datauni6))
 				  {
					$la_tenencias=$io_sql->obtener_datos($rs_datauni6);
					$io_datastore->data=$la_tenencias;
					$totrow2=$io_datastore->getRowCount("codcli");
					$ls_codigoant=$io_datastore->getValue("codigo",1);

					//$lo_hoja->set_column(10,2,30);
					$lo_hoja->write($li_inipro, 0, "TENENCIA",$lo_datacenter);
					$lo_hoja->write($li_inipro, 1, "TOTAL CLIENTES",$lo_datacenter);

					$i=0;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codigo",$li_i);
						$ls_cadena="SELECT COUNT(DISTINCT f.codcli) as tcliente,t.denominacion".
						" from sfc_factura f,sfc_cliente c,sfc_tenenciatierra t,sfc_productor p where f.codcli=c.codcli and ".
						" c.codcli=p.codcli and p.codtenencia='".$ls_codigo."' and p.codcli=f.codcli AND t.codtenencia=p.codtenencia ".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A' GROUP BY t.denominacion";
						//print $ls_cadena;
						$arr_clientes=$io_sql->select($ls_cadena);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);
						if ($la_clientes["tcliente"][$li+1]!='0')
						{
							$li_iniclipro=$li_inipro+$li_i;
							$lo_hoja->write($li_iniclipro, 0, $la_clientes["denominacion"][$li+1],$lo_datacenter);
							$lo_hoja->write($li_iniclipro, 1, $la_clientes["tcliente"][$li+1],$lo_datacenter);

						$ls_codigoant=$ls_codigo;
						$i++;
						}
					}
					$ls_cadena3="SELECT COUNT(DISTINCT f.codcli) as tcliente".
						" from sfc_factura f,sfc_cliente c,sfc_productor p where f.codcli=c.codcli and p.codtenencia='' and p.codcli=c.codcli and f.codcli=p.codcli".
						" AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";//QUEDE AQUI
						//print $ls_cadena3;
						$arr_clientes=$io_sql->select($ls_cadena3);
						$la_clientes=$io_sql->obtener_datos($arr_clientes);
						$ls_iniclinopro=$li_iniclipro+2;
						if ($la_clientes["tcliente"][1]>0)
						{
							$lo_hoja->write($ls_iniclinopro, 0, "TENENCIA",$lo_datacenter);
							$lo_hoja->write($ls_iniclinopro, 1, "CLIENTES NO PRODUCTORES",$lo_datacenter);

							$ls_iniclinopro++;
							$lo_hoja->write($ls_iniclinopro, 0, $la_clientes["denominacion"][$li+1],$lo_datacenter);
							$lo_hoja->write($ls_iniclinopro, 1, $la_clientes["tcliente"][1],$lo_datacenter);


						}

					$li_rubpro=$ls_iniclinopro+2;
				}


			//----------------------------------------------------------------------------------------------
			//----------------------------TOTAL HAS*RUBRO AGRICOLA---------------------------------


				$arr_rubroagri=$io_sql->select($ls_sql8);
				if($row2=$io_sql->fetch_row($arr_rubroagri))
 				  {
					$la_rubroagri=$io_sql->obtener_datos($arr_rubroagri);
					$io_datastore->data=$la_rubroagri;
					$totrow2=$io_datastore->getRowCount("id_clasificacion");

					$i=0;

					$lo_hoja->set_column($li_rubpro,0,30);
					$lo_hoja->write($li_rubpro, 0, "RUBRO AGRICOLA",$lo_titulo);
					$lo_hoja->set_column($li_rubpro,1,30);
					$lo_hoja->write($li_rubpro, 1, "HAS. PRODUCTIVAS",$lo_titulo);
					$lo_hoja->set_column($li_rubpro,2,30);
					$lo_hoja->write($li_rubpro, 2, "CANTIDAD PRODUCCION",$lo_titulo);

					$li_detrubro=$li_rubpro;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$li_detrubro++;
						$lo_hoja->write($li_detrubro, 0, $la_rubroagri["denominacion"][$i+1],$lo_dataleft);
						$lo_hoja->write($li_detrubro, 1,number_format($la_rubroagri["thas"][$i+1],2, ',', '.'),$lo_dataright);
						$lo_hoja->write($li_detrubro, 2, number_format($la_rubroagri["tprod"][$i+1],2, ',', '.'),$lo_dataright);


						$i++;
					}
					}


			$li_rubpec=$li_detrubro+3;
			//----------------------------------------------------------------------------------------------
			//----------------------------TOTAL HAS*RUBRO PECUARIO---------------------------------
				$arr_rubropec=$io_sql->select($ls_sql9);
				if($row2=$io_sql->fetch_row($arr_rubropec))
 				  {
					$la_rubropec=$io_sql->obtener_datos($arr_rubropec);
					$io_datastore->data=$la_rubropec;
					$totrow2=$io_datastore->getRowCount("id_clasificacion");

					$i=0;

					$lo_hoja->set_column($li_rubpec,0,30);
					$lo_hoja->write($li_rubpec, 0, "RUBRO PECUARIO",$lo_titulo);
					$lo_hoja->set_column($$li_rubpec,1,30);
					$lo_hoja->write($li_rubpec, 1, "HAS, PRODUCTIVAS",$lo_titulo);
					$lo_hoja->set_column($li_rubpec,2,30);
					$lo_hoja->write($li_rubpec, 2, "NRO. DE ANIMALES",$lo_titulo);
					$lo_hoja->set_column($li_rubpec,3,30);
					$lo_hoja->write($li_rubpec, 3, "CANTIDAD PRODUCCION",$lo_titulo);

					$li_detrubpec=$li_rubpec+2;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$li_detrubpec++;

						$lo_hoja->write($li_detrubpec,0,$la_rubropec["denominacion"][$i+1]." (".$la_rubropec["rubro"][$i+1].")",$lo_dataleft);
						$lo_hoja->write($li_detrubpec, 1,number_format($la_rubropec["hectprorp"][$i+1],2, ',', '.'),$lo_dataright);
						$lo_hoja->write($li_detrubpec, 2,number_format($la_rubropec["tnro_animal"][$i+1],2, ',', '.'),$lo_dataright);
						$lo_hoja->write($li_detrubpec, 3, number_format($la_rubropec["tcantrp"][$i+1],2, ',', '.'),$lo_dataright);

						$i++;
					}
					}


				$li_venpro=$li_detrubpec+5;
					//--------------------------------------------------------------------------------------
					//------------------------TOTAL VENTAS POR PRODUCTO-------------------------------------
					if($row2=$io_sql->fetch_row($rs_datauni10))
 				  {
					$la_productos=$io_sql->obtener_datos($rs_datauni10);
					$io_datastore->data=$la_productos;
					$totrow2=$io_datastore->getRowCount("id_tipouso");
					$ls_codigoant=$io_datastore->getValue("id_tipouso",1);

					$i=0;
					$ls_siniva=0;
					$ls_totaliva=0;
					$ls_totalgeneral=0;
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
$li_venpro++;

						$ls_codigo=$io_datastore->getValue("codigo",$li_i);
						$ls_den=$io_datastore->getValue("dentipouso",$li_i);


						$lo_hoja->set_column($li_venpro,1,30);
						$lo_hoja->write($li_venpro, 1, "PRODUCTOS VENDIDOS POR USO 1".$ls_den,$lo_titulo);

						$ls_cadena="SELECT cla.codcla,cla.dencla FROM sfc_factura f,sfc_clasificacion cla,".
						"sfc_producto p,sfc_detfactura df,sfc_uso u,sfc_tipouso tu,sim_articulo a WHERE p.codcla=cla.codcla AND ".
						"a.codart=p.codart AND a.codart=df.codart AND p.codart=df.codart AND a.id_uso=u.id_uso AND u.id_tipouso=tu.id_tipouso AND ".
						"tu.dentipouso='".$ls_den."' AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."')".
						" AND f.numfac=df.numfac AND f.estfaccon<>'A'  AND df.codtiend=f.codtiend GROUP BY cla.codcla,cla.dencla;";
						//print $ls_cadena;
						$arr_clasificacion=$io_sql->select($ls_cadena);
						$la_clasificacion=$io_sql->obtener_datos($arr_clasificacion);
						$io_data2->data=$la_clasificacion;
						$totrow3=$io_data2->getRowCount("codcla");
						$ls_codclaant=$io_datastore->getValue("codcla",$i);
						$j=0;
						$TOTALUSO=0;

					$li_detpro=$li_venpro+3;

						for ($li_j=1;$li_j<=$totrow3;$li_j++)
						{
							$li_detpro++;
							$ls_codcla=$la_clasificacion["codcla"][$j+1];

							$lo_hoja->set_column($li_detpro,0,30);
							$lo_hoja->write($li_detpro,1, " ".$la_clasificacion["dencla"][$j+1],$lo_titulo);

							$ls_dencla=$la_clasificacion["dencla"][$j+1];

						$ls_cadena="SELECT sub.cod_sub,max(df.porimp),sub.den_sub,sum(df.canpro) as tcant, ".
					"sum(((df.canpro*df.prepro)*(df.porimp/100))+(df.canpro*df.prepro)) as monto,sum((df.canpro*df.prepro)*(df.porimp/100)) as iva".
					" FROM sfc_subclasificacion sub,sfc_detfactura df,sfc_clasificacion cla,sfc_producto p,sfc_tipouso tu,".
					" sfc_factura f,sfc_uso u,sim_articulo a WHERE sub.cod_sub=a.cod_sub AND a.codart=p.codart AND a.codart=df.codart AND a.codcla=cla.codcla " .
					" AND p.codart=df.codart AND cla.codcla='".$ls_codcla."' AND tu.dentipouso='".$ls_den."' AND cla.codcla=p.codcla AND " .
					"sub.codcla=cla.codcla AND f.numfac=df.numfac AND substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND " .
					"f.estfaccon<>'A' AND u.id_tipouso=tu.id_tipouso AND u.id_uso=a.id_uso AND df.codtiend=f.codtiend".
					" GROUP BY sub.cod_sub,sub.den_sub;";
					//print $ls_cadena;
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
						$li_subcla=$li_detpro+2;
						$lo_hoja->set_column($li_subcla,0,30);
						$lo_hoja->write($li_subcla, 0, "SUBCLASIFICACION ",$lo_titulo);
						$lo_hoja->write($li_subcla, 1, "UNIDADES ",$lo_titulo);
						$lo_hoja->write($li_subcla, 2, "MONTO Bs. ",$lo_titulo);


						$li_detsub=$li_subcla+2;
						for ($li_k=1;$li_k<=$totrow4;$li_k++)
						{

							$ls_codsub=$la_sub["cod_sub"][$k+1];


							$lo_hoja->write($li_detsub,0,$la_sub["den_sub"][$k+1],$lo_dataleft);
							$lo_hoja->write($li_detsub, 1,number_format($la_sub["tcant"][$k+1],2, ',', '.'),$lo_dataright);
							$lo_hoja->write($li_detsub, 2,number_format($la_sub["monto"][$k+1],2, ',', '.'),$lo_dataright);

							$li_detsub++;
							$TOTAL=$TOTAL+$la_sub["monto"][$k+1];
							$den=$la_sub["den_sub"][$k+1];
							$ls_totalgeneral=$ls_totalgeneral+$la_sub["monto"][$k+1];
							$k++;
							$li_detpro=$li_detsub+1;
						}
						$TOTALUSO=$TOTALUSO+$TOTAL;

						$lo_hoja->write($li_detsub,1,"TOTAL ".$ls_dencla,$lo_dataright);
						$lo_hoja->write($li_detsub,2,number_format($TOTAL,2, ',', '.'),$lo_dataright);

						if ($ls_codclaant!=$ls_codcla)
						{
						$j++;
						}
						$ls_codclaant=$ls_codcla;
						}
						$li_totusu=$li_venpro+$li_detpro+$li_detsub+4;
						$lo_hoja->write($li_totusu,1,"TOTAL USO ".$ls_den,$lo_dataright);
						$lo_hoja->write($li_totusu,2,number_format($TOTALUSO,2, ',', '.'),$lo_dataright);
						$li_venpro=	$li_totusu+2;

						$ls_codigoant=$ls_codigo;
						$i++;
						//$li_venpro++;

						}
						//--------------------------------------------------------------------------------------
						//------------------------TOTAL GENERAL-------------------------------------------------
						$ls_cadenas="SELECT SUM(((df.canpro*df.prepro)*df.porimp/100)+(df.canpro*df.prepro)) as coniva," .
								"SUM(((df.canpro*df.prepro)*df.porimp/100)+(df.canpro*df.prepro)) from sfc_factura f,sfc_detfactura df where " .
								"substr(f.fecemi,0,11)>='". $ls_desde."' AND substr(f.fecemi,0,11)<='".$ls_hasta."' AND f.estfaccon<>'A' AND df.numfac=f.numfac AND " .
								"df.codemp=f.codemp AND df.porimp<>0 and AND df.codtiend=f.codtiend";
						//print $ls_cadenas;
						$arr_total3=$io_sql->select($ls_cadenas);
						$la_totalgeneral3=$io_sql->obtener_datos($arr_total3);
						$io_datos3->data=$la_totalgeneral3;


			$li_totgral=$li_totusu+4;
			$lo_hoja->write($li_totgral,0,"TOTAL CON IVA: ".number_format($la_totalgeneral3["coniva"][1],2,',', '.'),$lo_titulo);


					$ls_cadena2="SELECT SUM((df.canpro*df.prepro)) as siniva FROM sfc_detfactura df WHERE ".
					"df.numfac IN (SELECT numfac FROM sfc_factura WHERE substr(fecemi,0,11)>='". $ls_desde."' AND ".
					"substr(fecemi,0,11)<='".$ls_hasta."' AND estfaccon<>'A') AND df.porimp=0 AND f.codtiend=df.codtiend";
						//print $ls_cadena;
						$arr_total2=$io_sql->select($ls_cadena2);
						$la_totalgeneral2=$io_sql->obtener_datos($arr_total2);
						$io_datos2->data=$la_totalgeneral2;


			$lo_hoja->write($li_totgral,1,"TOTAL SIN IVA: ".number_format($la_totalgeneral2["siniva"][1],2,',', '.'),$lo_titulo);


						$ls_cadena="SELECT SUM(f.montoret) as montoiva,SUM(f.monto) as montototal FROM sfc_factura f".
						" WHERE substr(f.fecemi,0,11)>='".$ls_desde."' AND (substr(f.fecemi,0,11)<='".$ls_hasta."') AND f.estfaccon<>'A'";
					//print $ls_cadena;
						$arr_total=$io_sql->select($ls_cadena);
						$la_totalgeneral=$io_sql->obtener_datos($arr_total);
						$io_datos->data=$la_totalgeneral;
						$lo_hoja->set_column($li_totgral,2,40);
		$lo_hoja->write($li_totgral,2,"TOTAL VENDIDO: ".number_format($la_totalgeneral["montototal"][1],2, ',', '.'),$lo_titulo);

						}



						for($li=$li_total;$li>1;$li--)
				{
					$li_ant=$ia_niveles_scg[$li-1];
					$li_act=$ia_niveles_scg[$li];
					$li_fila=$li_act-$li_ant;
					$li_len=strlen($ls_cuenta);
					$li_totfil=$li_totfil+$li_fila;
					$li_inicio=$li_len-$li_totfil;

				}

	$li_n++;
				$li_fila=$ia_niveles_scg[1]+1;

			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"gestion.xls\"");
			header("Content-Disposition: inline; filename=\"gestion.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");


		}
?>