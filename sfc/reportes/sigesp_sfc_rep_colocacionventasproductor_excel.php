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
		$lo_archivo = tempnam("/tmp", "COLOCACION_VENTAS_PRODUCTOR.xls");
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
		$io_connect      = $io_in->uf_conectar();
		$io_sql   = new class_sql($io_connect);
		$io_sql2   = new class_sql($io_connect);
		$io_sql3   = new class_sql($io_connect);
		$io_sql5   = new class_sql($io_connect);
		$io_datastore5= new class_datastore();
		$io_datastore4= new class_datastore();
		$io_datastore1= new class_datastore();
		$io_datastore2= new class_datastore();
		$io_datastore3= new class_datastore();

		$io_sql_bd=new class_sql($io_connect);



		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();

		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte

		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "COLOCACIÓN Y VENTAS POR PERFIL DE PRODUCTOR-INSTITUCIÓN";
		$ls_nombemp = "CVA-ECISA";
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_sql="Select t.dentie,e.desest,m.denmun,p.denpar from sfc_tienda t,sigesp_estados e,sigesp_municipio m,sigesp_parroquia p" .
				" where codtie='".$ls_codtie."' AND t.codest=e.codest AND t.codmun=m.codmun and t.codpar=p.codpar  ";
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
					$io_datastore1->data=$la_agrotienda;
					$totrowt=$io_datastore1->getRowCount("dentie");
					//print_r($la_agrotienda);

					//	print "Paso".$totrowt;
					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore1->getValue("dentie",$t);
						$ls_ubicacion="Estado ".$io_datastore1->getValue("desest",$t)." Municipio ".$io_datastore1->getValue("denmun",$t)." Parroquia ".$io_datastore1->getValue("denpar",$t);

					}
				}
			}


		//print $ls_titulo;
		$ls_fecha=date('d/m/Y  h:m:s');



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
			$lo_dataright= &$lo_libro->addformat();
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');
			$lo_hoja->set_column(3,3,30);
			$lo_hoja->write(0,3,$ls_nombemp,$lo_encabezado);
			$lo_hoja->write(2,3,$ls_dentie,$lo_encabezado);
			$lo_hoja->write(3,3,$ls_titulo,$lo_encabezado);
			//$lo_hoja->write(4,3,$ls_fecha,$lo_encabezado);



			$lo_hoja->set_column(0,0,40);
			$lo_hoja->write(6, 0, "TIENDA",$lo_titulo);
			$lo_hoja->write(8, 0, $ls_dentie,$lo_titulo);

			$lo_hoja->set_column(1,1,20);
			$lo_hoja->write(6, 1, "LINEA DE PRODUCTO",$lo_titulo);

			$lo_hoja->set_column(1,2,25);
			$lo_hoja->write(6, 2, "SUBLINEA DE PRODUCTO",$lo_titulo);

			//$lo_hoja->set_column(3,2,120);
			$lo_hoja->write(6, 3, "PRODUCTO",$lo_titulo);

			$lo_hoja->set_column(4,4,15);
			$lo_hoja->write(6, 4, "UNIDAD DE MEDIDA",$lo_titulo);


/**************************** FONDAS   ***************************************/
			$lo_hoja->set_column(4,5,8);
			$lo_hoja->write(5, 6, "PRODUCTORES CREDITO FONDAS",$lo_titulo);
			$lo_hoja->write(6, 5, "CANTIDAD",$lo_titulo);

			$lo_hoja->set_column(4,6,10);
			$lo_hoja->write(6, 6, "VENTAS Bs",$lo_titulo);

			$lo_hoja->set_column(4,7,5);
			$lo_hoja->write(6, 7, "No PRODUCTORES",$lo_titulo);

			$lo_hoja->set_column(4,8,10);
			$lo_hoja->write(6, 8, "SUPERFICIE HECTAREAS",$lo_titulo);

			$lo_hoja->set_column(30,9,25);
			$lo_hoja->write(6, 9, "UBICACION",$lo_titulo);
			$lo_hoja->write(8, 9, $ls_ubicacion,$lo_datacenter);

			/**************************** BAV   ***************************************/

			$lo_hoja->set_column(4,10,8);
			$lo_hoja->write(5, 11, "PRODUCTORES CREDITO BAV",$lo_titulo);
			$lo_hoja->write(6, 10, "CANTIDAD",$lo_titulo);

			$lo_hoja->set_column(4,11,10);
			$lo_hoja->write(6, 11, "VENTAS Bs",$lo_titulo);

			$lo_hoja->set_column(4,12,5);
			$lo_hoja->write(6, 12, "No PRODUCTORES",$lo_titulo);

			$lo_hoja->set_column(4,13,10);
			$lo_hoja->write(6, 13, "SUPERFICIE HECTAREAS",$lo_titulo);

			$lo_hoja->set_column(4,14,25);
			$lo_hoja->write(6, 14, "UBICACION",$lo_titulo);
			$lo_hoja->write(8, 14, $ls_ubicacion,$lo_datacenter);


			$li_row=6;
			$li_rowcol=6;


/************************************** FIn for insertar en tabla Temporal  *******************************************************************/

//$lb_inserto=true;
$lb_inserto=$_GET["lb_inserto"];
$li_suma=$_GET["li_suma"];
$ls_cant=explode("-",$_GET["ls_cant"]);

$ls_venta=explode("-",$_GET["ls_venta"]);
$total=$_GET["total"];

$ls_entidades=explode("-",$_GET["ls_entidades"]);


		if($lb_inserto===false)
				{

					$msg->message("NO hay Datos que reportar");


				}
				else
				{



					$ls_sql_result="select dencla,den_sun,denpro,deunimed, SUM(cantf) as cantf,SUM(ventaf) as ventaf," .
									"	SUM(cantb) as cantb,SUM(preprob) as preprob,SUM(ventab) as ventab,".$li_suma."SUM(cantc) as cantc,SUM(preproc) as preproc," .
									"	SUM(ventac) as ventac,SUM(cantcop) as cantcop,SUM(preprocop) as preprocop,SUM(ventacop) as ventacop,SUM(cantp) as cantp," .
									"   SUM(preprop) as preprop,SUM(ventap) as ventap,SUM(canto) as canto," .
									"   SUM(preproo) as preproo,SUM(ventao) as ventao  from" .
									"	(  select * from temporalcoloca ) resulta  group by dencla,den_sun,denpro,deunimed order by dencla,den_sun,denpro";

							$rs_resultado=$io_sql_bd->select($ls_sql_result);
//print $ls_sql_result;

							$la_resultado=$io_sql_bd->obtener_datos($rs_resultado);


							$io_datastore4->data=$la_resultado;
							$tota=$io_datastore4->getRowCount("denpro");

							//$li_row=$li_row+2;
							//var_dump($io_datastore4);
							//exit;
							$totalcla=$io_datastore4->getRowCount("dencla");
							$totalpro=$$tota;
							$li_inicio_cla=3;
							$li_fin_cla=0;
							$li_inicio_sub=3;
							$li_inicio_pro=3;
							$li_fin_sub=0;
							$li_mitad=0;
							$li_total_general=0;
							$li_total_ventas=0;

							$li_columna=15;
							$li_columna2=16;






							for($a=0;$a<=$tota;$a++)
							{

									if ($a<$totalcla)
									{
										$ls_dencla_sig=  $io_datastore4->data["dencla"][$a+1];
										$ls_densub_sig=  $io_datastore4->data["den_sub"][$a+1];
									}

									$ls_dencla=$io_datastore4->getValue("dencla",$a);
									$ls_densub=$io_datastore4->getValue("den_sun",$a);

									//print $ls_dencla.$ls_densub."PASOOO<br>";
									$ls_denpro=$io_datastore4->getValue("denpro",$a);
									$ls_denunimed=$io_datastore4->getValue("deunimed",$a);



									$li_row++;
									$lo_hoja->write($li_row, 1, $ls_dencla , $lo_dataleft);
									$lo_hoja->write($li_row, 2, $ls_densub, $lo_dataleft);
									$lo_hoja->write($li_row, 3, $ls_denpro, $lo_dataleft);
									$lo_hoja->write($li_row, 4, $ls_denunimed, $lo_dataleft);


									$ls_cantf=$io_datastore4->getValue("cantf",$a);
									$ls_ventf=$io_datastore4->getValue("ventaf",$a);


									$ls_cantc[$a]=$io_datastore4->getValue("cantc",$a);
									$ls_ventc[$a]=$io_datastore4->getValue("ventac",$a);

									$ls_cantcop[$a]=$io_datastore4->getValue("cantcop",$a);
									$ls_ventcop[$a]=$io_datastore4->getValue("ventacop",$a);

									$ls_cantp[$a]=$io_datastore4->getValue("cantp",$a);
									$ls_ventp[$a]=$io_datastore4->getValue("ventap",$a);

									$ls_canto[$a]=$io_datastore4->getValue("canto",$a);
									$ls_vento[$a]=$io_datastore4->getValue("ventao",$a);


									$lo_hoja->write($li_row, 5, $ls_cantf , $lo_dataright);
									$lo_hoja->write($li_row, 6, $ls_ventf  , $lo_dataright);


									$ls_cantb=$io_datastore4->getValue("cantb",$a);
									$ls_ventb=$io_datastore4->getValue("ventab",$a);

									$lo_hoja->write($li_row, 10, $ls_cantb, $lo_dataright);
									$lo_hoja->write($li_row, 11, $ls_ventb , $lo_dataright);

									//echo "CANT= ".$ls_totproe."<br>"."VENT= ".$ls_totvente."<br>";

								//echo "PROD: ".$ls_denpro;

								$la_productos[$a]=(int)($ls_cantf)+(int)($ls_cantb)+(int)($ls_cantc[$a])+(int)$ls_cantcop[$a]+(int)($ls_cantp[$a])+(int)($ls_canto[$a])+$ls_totproe;
								$la_ventas[$a]=(double)($ls_ventf)+(double)($ls_ventb)+(double)($ls_ventc[$a])+(double)$ls_ventcop[$a]+(double)($ls_ventp[$a])+(double)($ls_vento[$a])+$ls_totvente;

								$li_totcantf=$li_totcantf+(int)($ls_cantf);
								$li_totventf=$li_totventf+(double)($ls_ventf);

								$li_totcantb=$li_totcantb+(int)($ls_cantb);
								$li_totventb=$li_totventb+(double)($ls_ventb);

								//$ls_tothect=;


							}

//exit;


							/************************** OTRASS ENTIDADES CREDITICIAS  *************************/

							if ($total>0)
									{


										for ($e=0;$e<$total;$e++)
										{

											$li_columna++;
											$li_columna2++;

											$lo_hoja->set_column(4,$li_columna,8);
											$lo_hoja->write(5, $li_columna2, "PRODUCTORES CON CREDITO  ".$ls_entidades[$e],$lo_titulo);
											$lo_hoja->write(6, $li_columna, "CANTIDAD",$lo_titulo);

											$lo_hoja->set_column(4,$li_columna2,10);
											$lo_hoja->write(6, $li_columna2, "VENTAS Bs",$lo_titulo);

											$lo_hoja->set_column(4,$li_columna2+1,10);
											$lo_hoja->write(6, $li_columna2+1, "No PRODUCTORES",$lo_titulo);

											$lo_hoja->set_column(4,$li_columna2+2,10);
											$lo_hoja->write(6, $li_columna2+2, "SUPERFICIE HECTAREAS",$lo_titulo);

											$lo_hoja->set_column(4,$li_columna2+3,25);
											$lo_hoja->write(6, $li_columna2+3, "UBICACION",$lo_titulo);
											$lo_hoja->write(8, $li_columna2+3, $ls_ubicacion,$lo_datacenter);

											for($a=0;$a<=$tota;$a++)
											{

											$ls.$ls_cant_p[$e]=$io_datastore4->getValue($ls_cant[$e],$a);
											$ls_.$ls_venta_p[$e]=$io_datastore4->getValue($ls_venta[$e],$a);

											$lo_hoja->write($li_row,$li_columna, $ls.$ls_cant_p[$e], $lo_dataright);
											$lo_hoja->write($li_row, $li_columna2, $ls_.$ls_venta_p[$e] , $lo_dataright);

/*print $li_columna.$ls.$ls_cant_p[$e]."<br>";
print $li_columna2.$ls.$ls_venta_p[$e]."<br>";*/
											}
											/***********************************************************************************/

										/*****************************************************************************************/

										//--Productores on credito otras entidades crediticias

											$ls_sql_numpe="SELECT COUNT(codcli) as num from (SELECT p.codcli,e.denominacion from sfc_productor p, sfc_factura f," .
												"sfc_instpago i,sfc_entidadcrediticia e where p.codcli=f.codcli AND" .
												" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND f.numfac=i.numfac " .
												" AND i.codforpag='04' and i.id_entidad=e.id_entidad AND e.denominacion ilike '".$ls_entidades[$e]."' group by e.denominacion,p.codcli) fo";
											$rs_numpe=$io_sql_bd->select($ls_sql_numpe);

											$la_resul_numpe=$io_sql_bd->obtener_datos($rs_numpe);
											$io_datastore5->data=$la_resul_numpe;
											$totrowt=$io_datastore5->getRowCount("num");
											$ls_numproe=$io_datastore5->getValue("num",$totrowt);

											$lo_hoja->set_column(4,$li_columna2+1,5);
											$lo_hoja->write(8, $li_columna2+1, $ls_numproe, $lo_dataright);

//echo "NUMP--".$ls_numproe;


										/******************************************************************************************/


										$ls_totproe=$ls_totproe+(int)($ls.$ls_cant_p[$e]);
										$ls_totvente=$ls_totvente+(double)($ls_.$ls_venta_p[$e]);
										$ls_totclie=$ls_totclie+$ls_numproe;
										}


										$lo_hoja->write($li_row+1, $li_columna, $ls_totproe, $lo_dataright);
										$lo_hoja->write($li_row+1, $li_columna+1, $ls_totvente, $lo_dataright);


										$li_columna2=$li_columna2+4;
									}



						/*****************************************************************************************/

							//--Productores con carta orden fondas

								$ls_sql_numpf="SELECT COUNT(codcli) as num from (SELECT p.codcli,e.denominacion from sfc_productor p, sfc_factura f," .
									"sfc_instpago i,sfc_entidadcrediticia e where p.codcli=f.codcli AND" .
									" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND f.numfac=i.numfac" .
									" AND i.codforpag='04' and i.id_entidad=e.id_entidad AND e.denominacion ilike 'fondas' group by e.denominacion,p.codcli) fo";
								$rs_numpf=$io_sql_bd->select($ls_sql_numpf);

								$la_resul_numpf=$io_sql_bd->obtener_datos($rs_numpf);
								$io_datastore5->data=$la_resul_numpf;
								$totrowt=$io_datastore5->getRowCount("num");
								$ls_numprof=$io_datastore5->getValue("num",$totrowt);

								$lo_hoja->set_column(4,7,10);
								$lo_hoja->write(8, 7, $ls_numprof, $lo_dataright);

								;
//echo "<br>"."NUMP--".$ls_numprof;


						/******************************************************************************************/



						/*****************************************************************************************/

							//--Productores Credito BAV

								$ls_sql_numpb="SELECT COUNT(codcli) as num from (SELECT p.codcli from sfc_productor p, sfc_factura f,sfc_instpago i," .
										"scb_banco b,sfc_formapago fp where p.codcli=f.codcli AND (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06')" .
										" AND b.nomban ilike 'BANCO AGRICOLA DE VENEZUELA'	AND b.codban=i.codban " .
										" AND fp.denforpag ilike 'cheque' AND i.codforpag=fp.codforpag AND f.estfaccon<>'A' group by p.codcli) bav";

								$rs_numpb=$io_sql_bd->select($ls_sql_numpb);

								$la_resul_numpb=$io_sql_bd->obtener_datos($rs_numpb);
	//var_dump($la_resul_numpb)."<br>";
								$io_datastore5->data=$la_resul_numpb;
								$totrowt=$io_datastore5->getRowCount("num");
								$ls_numprob=$io_datastore5->getValue("num",$totrowt);

								$lo_hoja->set_column(4,12,5);
								$lo_hoja->write(8, 12, $ls_numprob, $lo_dataright);

//echo "<br>"."NUMP--".$ls_numprob;


						/******************************************************************************************/

	$lo_hoja->write($li_row+1, 5, $li_totcantf, $lo_dataright);
	$lo_hoja->write($li_row+1, 6, $li_totventf, $lo_dataright);

	$lo_hoja->write($li_row+1, 10, $li_totcantb, $lo_dataright);
	$lo_hoja->write($li_row+1, 11, $li_totventb, $lo_dataright);


						/**************************** PRODUCTORES Y NO PRODUCTORES CON CONVENIOS   ***************************************/

							$lo_hoja->set_column(4,$li_columna2,8);
							$lo_hoja->write(5, $li_columna2+1, "INSTITUCIONES GUBERNAMENTALES",$lo_titulo); //$li_columnar=21
							$lo_hoja->write(6, $li_columna2, "CANTIDAD",$lo_titulo);

							$lo_hoja->set_column(4,$li_columna2+1,10);
							$lo_hoja->write(6, $li_columna2+1, "VENTAS Bs",$lo_titulo);

							$lo_hoja->set_column(4,$li_columna2+2,10);
							$lo_hoja->write(6, $li_columna2+2, "No PRODUCTORES",$lo_titulo);

							$lo_hoja->set_column(4,$li_columna2+3,10);
							$lo_hoja->write(6, $li_columna2+3, "SUPERFICIE HECTAREAS",$lo_titulo);

							$lo_hoja->set_column(4,$li_columna2+4,25);
							$lo_hoja->write(6, $li_columna2+4, "UBICACION",$lo_titulo);
							$lo_hoja->write(8, $li_columna2+4, $ls_ubicacion,$lo_datacenter);

							for($a=0;$a<=$tota;$a++)
							{
								$li_rowcol++;
								$lo_hoja->write($li_rowcol, $li_columna2, $ls_cantc[$a], $lo_dataright);
								$lo_hoja->write($li_rowcol, $li_columna2+1, $ls_ventc [$a], $lo_dataright);
								$li_totcantcon=$li_totcantcon+(int)($ls_cantc[$a]);
								$li_totventcon=$li_totventcon+(double)($ls_ventc[$a]);


							}

							$lo_hoja->write($li_rowcol+1, $li_columna2, $li_totcantcon , $lo_dataright);
							$lo_hoja->write($li_rowcol+1, $li_columna2+1, $li_totventcon, $lo_dataright);
							/*****************************************************************************************/

							//--Productores y NO productores con convenio

								$ls_sql_numpg="SELECT COUNT(codcli) as num from (SELECT c.codcli from sfc_factura f," .
									"sfc_cliente c,sfc_instpago ip where c.codcli=f.codcli AND" .
									" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND f.numfac=ip.numfac" .
									" AND c.cedcli like 'G%' AND ip.codforpag<>'04' AND f.estfaccon<>'A' group by c.codcli) gub";
								$rs_numpg=$io_sql_bd->select($ls_sql_numpg);

								$la_resul_numpg=$io_sql_bd->obtener_datos($rs_numpg);
								$io_datastore5->data=$la_resul_numpg;
								$totrowt=$io_datastore5->getRowCount("num");
								$ls_numprog=$io_datastore5->getValue("num",$totrowt);

								$lo_hoja->set_column(4,$li_columna2+2,5);
								$lo_hoja->write(8, $li_columna2+2, $ls_numprog, $lo_dataright);


//echo "<br>"."NUMP--".$ls_numprog;

						/******************************************************************************************/

						$li_columna2=$li_columna2+5;


				/********************************************************************************************************************/


				/**************************** COOPERATIVAS   ***************************************/

				$lo_hoja->set_column(4,$li_columna2,8);  //$li_columnac=25
				$lo_hoja->write(5, $li_columna2+1, "COOPERATIVAS",$lo_titulo);
				$lo_hoja->write(6, $li_columna2, "CANTIDAD",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+1,10);
				$lo_hoja->write(6, $li_columna2+1, "VENTAS Bs",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+2,10);
				$lo_hoja->write(6, $li_columna2+2, "No PRODUCTORES",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+3,10);
				$lo_hoja->write(6, $li_columna2+3, "SUPERFICIE HECTAREAS",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+4,25);
				$lo_hoja->write(6, $li_columna2+4, "UBICACION",$lo_titulo);
				$lo_hoja->write(8, $li_columna2+4, $ls_ubicacion,$lo_datacenter);
				$li_rowcol=6;
					for($a=0;$a<=$tota;$a++)
					{
							$li_rowcol++;
							$lo_hoja->write($li_rowcol, $li_columna2, $ls_cantcop[$a], $lo_dataright);
							$lo_hoja->write($li_rowcol, $li_columna2+1, $ls_ventcop[$a], $lo_dataright);

						$li_totcantcop=$li_totcantcop+(int)($ls_cantcop[$a]);
						$li_totventcop=$li_totventcop+(double)($ls_ventcop[$a]);

					}

					$lo_hoja->write($li_rowcol+1, $li_columna2, $li_totcantcop , $lo_dataright);
					$lo_hoja->write($li_rowcol+1, $li_columna2+1, $li_totventcop, $lo_dataright);

				/*****************************************************************************************/

							//--COOPERATIVAS

								$ls_sql_numpc="SELECT COUNT(codcli) as num from (SELECT c.codcli from sfc_factura f," .
									"sfc_cliente c,scb_banco b,sfc_instpago ip where c.codcli=f.codcli AND" .
									" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND f.numfac=ip.numfac" .
									" AND (c.razcli ilike 'COOP%' or c.razcli ilike 'ASOC%' ) AND ip.codforpag<>'04' " .
									" AND b.nomban not ilike 'BANCO AGRICOLA DE VENEZUELA' " .
									" AND f.estfaccon<>'A' AND f.numfac=ip.numfac group by c.codcli) gub";
								$rs_numpc=$io_sql_bd->select($ls_sql_numpc);

								$la_resul_numpc=$io_sql_bd->obtener_datos($rs_numpc);
								//echo $ls_sql_numpc."<br>";var_dump($la_resul_numpc);
								$io_datastore5->data=$la_resul_numpc;
								$totrowt=$io_datastore5->getRowCount("num");
								$ls_numpropc=$io_datastore5->getValue("num",$totrowt);

								$lo_hoja->set_column(4,$li_columna2+2,5);
								$lo_hoja->write(8, $li_columna2+2, $ls_numpropc, $lo_dataright);

//echo "<br>"."NUMP--".$ls_numproc;


				/******************************************************************************************/



				$li_columna2=$li_columna2+5;


				/****************************************************************************/



				/**************************** PRODUCTORES PARTICULARES   ***************************************/

				$lo_hoja->set_column(4,$li_columna2,8);  //$li_columnac=25
				$lo_hoja->write(5, $li_columna2+1, "PRODUCTORES PARTICULARES",$lo_titulo);
				$lo_hoja->write(6, $li_columna2, "CANTIDAD",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+1,10);
				$lo_hoja->write(6, $li_columna2+1, "VENTAS Bs",$lo_titulo);


				$lo_hoja->set_column(4,$li_columna2+2,10);
				$lo_hoja->write(6, $li_columna2+2, "No PRODUCTORES",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+3,10);
				$lo_hoja->write(6, $li_columna2+3, "SUPERFICIE HECTAREAS",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+4,25);
				$lo_hoja->write(6, $li_columna2+4, "UBICACION",$lo_titulo);
				$lo_hoja->write(8, $li_columna2+4, $ls_ubicacion,$lo_datacenter);
				$li_rowcol=6;
					for($a=0;$a<=$tota;$a++)
						{
							$li_rowcol++;
							$lo_hoja->write($li_rowcol, $li_columna2, $ls_cantp[$a], $lo_dataright);
							$lo_hoja->write($li_rowcol, $li_columna2+1, $ls_ventp[$a], $lo_dataright);

							$li_totcantp=$li_totcantp+(int)($ls_cantp[$a]);
							$li_totventp=$li_totventp+(double)($ls_ventp[$a]);

						}

				$lo_hoja->write($li_rowcol+1, $li_columna2, $li_totcantp , $lo_dataright);
				$lo_hoja->write($li_rowcol+1, $li_columna2+1, $li_totventp, $lo_dataright);
				/*****************************************************************************************/

							//--Productores Particulares

								$ls_sql_numopp="SELECT COUNT(codcli) as num from (SELECT c.codcli from sfc_factura f," .
									"sfc_cliente c,sfc_instpago ip,scb_banco b where c.codcli=f.codcli AND" .
									" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND f.numfac=ip.numfac" .
									" AND c.cedcli not like 'G%' AND ip.codforpag<>'04' AND f.estfaccon<>'A'" .
									" AND ip.codforpag<>'04' AND b.nomban not ilike 'BANCO AGRICOLA DE VENEZUELA'" .
									" AND b.codban=ip.codban AND (c.razcli not ilike 'COOP%' or c.razcli not ilike 'ASOC%' ) group by c.codcli) pp";
								$rs_numpp=$io_sql_bd->select($ls_sql_numopp);

								$la_resul_numpp=$io_sql_bd->obtener_datos($rs_numpp);
								$io_datastore5->data=$la_resul_numpp;
								$totrowt=$io_datastore5->getRowCount("num");
								$ls_numprop=$io_datastore5->getValue("num",$totrowt);

								$lo_hoja->set_column(4,$li_columna2+2,5);
								$lo_hoja->write(8, $li_columna2+2, $ls_numprop, $lo_dataright);


//echo "<br>"."NUMP--".$ls_numprop;

				/******************************************************************************************/


				$li_columna2=$li_columna2+5;

				/****************************************************************************/


				/**************************** CLIENTES PARTICULARES   ***************************************/

				$lo_hoja->set_column(4,$li_columna2,8);  //$li_columnac=25
				$lo_hoja->write(5, $li_columna2+1, "CLIENTES PARTICULARES ",$lo_titulo);
				$lo_hoja->write(6, $li_columna2, "CANTIDAD",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+1,10);
				$lo_hoja->write(6, $li_columna2+1, "VENTAS Bs",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+2,10);
				$lo_hoja->write(6, $li_columna2+2, "No PRODUCTORES",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+3,10);
				$lo_hoja->write(6, $li_columna2+3, "SUPERFICIE HECTAREAS",$lo_titulo);

				$lo_hoja->set_column(4,$li_columna2+4,25);
				$lo_hoja->write(6, $li_columna2+4, "UBICACION",$lo_titulo);
				$lo_hoja->write(8, $li_columna2+4, $ls_ubicacion,$lo_datacenter);
				$li_rowcol=6;
					for($a=0;$a<=$tota;$a++)
						{
							$li_rowcol++;
							$lo_hoja->write($li_rowcol, $li_columna2, $ls_canto[$a], $lo_dataright);
							$lo_hoja->write($li_rowcol, $li_columna2+1, $ls_vento[$a], $lo_dataright);

							$li_totcanto=$li_totcanto+(int)($ls_canto[$a]);
							$li_totvento=$li_totvento+(double)($ls_canto[$a]);
						}

				$lo_hoja->write($li_rowcol+1, $li_columna2, $li_totcanto , $lo_dataright);
				$lo_hoja->write($li_rowcol+1, $li_columna2+1, $li_totvento, $lo_dataright);
				/*****************************************************************************************/

							//--Clientes Particulares

								$ls_sql_numc="SELECT COUNT(codcli) as num from (SELECT c.codcli from sfc_factura f," .
									"sfc_cliente c,sfc_instpago ip where c.codcli=f.codcli AND" .
									" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND f.numfac=ip.numfac" .
									" AND c.cedcli not like 'G%' AND ip.codforpag<>'04' AND f.estfaccon<>'A'" .
									" AND ip.codforpag<>'04' AND c.codcli NOT in (select codcli from sfc_productor)" .
									" AND (c.razcli not ilike 'COOP%' or c.razcli not ilike 'ASOC%' ) group by c.codcli) cp";
								$rs_numc=$io_sql_bd->select($ls_sql_numc);

								$la_resul_numc=$io_sql_bd->obtener_datos($rs_numc);
								$io_datastore5->data=$la_resul_numc;
								$totrowt=$io_datastore5->getRowCount("num");
								$ls_numproc=$io_datastore5->getValue("num",$totrowt);

								$lo_hoja->set_column(4,$li_columna2+2,5);
								$lo_hoja->write(8, $li_columna2+2, $ls_numproc, $lo_dataright);

/*echo "<br>"."NUMP--".$ls_numprocp;

exit;*/
				/******************************************************************************************/


				$ls_columnatot=$li_columna2+5;

				/****************************************************************************/

			/*print $ls_columnatot;

exit;*/



			$ls_totcli=$ls_numprof+$ls_numprob+$ls_numproe+$ls_numprog+$ls_numpropc+$ls_numprop;

/******************************* TOTALES  *********************************************/





			$lo_hoja->set_column(4,$ls_columnatot,18);
			$lo_hoja->write(6, $ls_columnatot, "TOTAL COLOCACION",$lo_titulo);
			//$lo_hoja->write(8, $ls_columnatot,$ls_totpro ,$lo_dataright);

			$lo_hoja->set_column(4,$ls_columnatot+1,18);
			$lo_hoja->write(6, $ls_columnatot+1, "TOTAL VENTAS",$lo_titulo);
			$lo_hoja->write(8, $ls_columnatot+1, $ls_totvent,$lo_dataright);
			$li_rowcoltot=7;
			for($a=1;$a<=$tota;$a++)
			{
				$li_rowcoltot++;
				//echo "A:".$a."CANT=". $la_productos[$a]."<br>";
				$lo_hoja->write($li_rowcoltot, $ls_columnatot, $la_productos[$a], $lo_dataright);
				$lo_hoja->write($li_rowcoltot, $ls_columnatot+1, $la_ventas[$a], $lo_dataright);
				$li_totventas=$li_totventas+(double)($la_ventas[$a]);
				$li_totcantidades=$li_totcantidades+(int)($la_productos[$a]);

			}

			$lo_hoja->write($li_rowcoltot+1, 4, " TOTALES ", $lo_dataright);
			$lo_hoja->write($li_rowcoltot+1, $ls_columnatot, $li_totventas, $lo_dataright);
			$lo_hoja->write($li_rowcoltot+1, $ls_columnatot+1, $li_totventas, $lo_dataright);

//exit;
			$lo_hoja->set_column(4,$ls_columnatot+2,18);
			$lo_hoja->write(6, $ls_columnatot+2, "TOTAL PRODUCTORES",$lo_titulo);
			$lo_hoja->write(8, $ls_columnatot+2,$ls_totcli ,$lo_dataright);

			$lo_hoja->set_column(4,$ls_columnatot+3,18);
			$lo_hoja->write(6, $ls_columnatot+3, "TOTAL SUPERFICIE HECTAREAS",$lo_titulo);



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

//exit;


				}
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"COLOCACION_VENTAS_PRODUCTOR.xls\"");
			header("Content-Disposition: inline; filename=\"COLOCACION_VENTAS_PRODUCTOR.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");





?>