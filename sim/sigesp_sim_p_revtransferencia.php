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
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_p_revtransferencia.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato num�rico
		//	Returns:	  $as_valor // valor num�rico formateado
		//	Description:  Funci�n que le da formato a los valores num�ricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=    str_replace(".",",",$as_valor);
		$li_poscoma = stripos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0))
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // 				as_valor         //  nombre de la variable que desamos obtener
    // 				as_valordefecto  //  contenido de la variable
    // Description: Funci�n que obtiene el valor de una variable si viene de un submit
	//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor;
   }
   //--------------------------------------------------------------

   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_agregarlineablanca
	//	Access:    public
	//	Arguments:
	//  			  aa_object // arreglo de titulos
	//  			  ai_totrows // ultima fila pintada en el grid
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//
	//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtnumtra".$ai_totrows."    type=text id=txtnumtra".$ai_totrows."    class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtnomfisori".$ai_totrows." type=text id=txtnomfisori".$ai_totrows." class=sin-borde size=30 maxlength=50 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtnomfisdes".$ai_totrows." type=text id=txtnomfisdes".$ai_totrows." class=sin-borde size=30 maxlength=50 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtfecemi".$ai_totrows."    type=text id=txtfecemi".$ai_totrows."    class=sin-borde size=12 maxlength=12 readonly>";
		$aa_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
   }
   //--------------------------------------------------------------
   function uf_pintardetalle($ai_totrows,$ls_estpro)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_pintardetalle
	//	Access:    public
	//	Arguments:
	//  		      ai_totrows    // cantidad de filas que tiene el grid
	//				  ls_estpro     // indica que valor tiene el radiobutton O--> Orden de compra F--> Factura
	//  		      ls_checkedord // variable imprime o no "checked" para el radiobutton en la orden de compra
	//				  ls_checkedfac // variable imprime o no "checked" para el radiobutton en la factura
	//	Description:  Funcion que vuelve a pintar el detalle del grid tal cual como estaba.
	//
	//////////////////////////////////////////////////////////////////////////////
		global $lo_object;

		if($ls_estpro=="O")
		{
			$ls_checkedord="checked";
			$ls_checkedfac="";
		}
		elseif($ls_estpro=="F")
		{
			$ls_checkedord="";
			$ls_checkedfac="checked";
		}
		else
		{
			$ls_checkedord="";
			$ls_checkedfac="";
		}
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			$la_unidad[0]="";
			$la_unidad[1]="";
			$ls_codart=    $_POST["txtcodart".$li_i];
			$ls_unidad=    $_POST["txtunidad".$li_i];
			$li_canart=    $_POST["txtcanart".$li_i];
			$li_penart=    $_POST["txtpenart".$li_i];
			$li_preuniart= $_POST["txtpreuniart".$li_i];
			$li_canoriart= $_POST["txtcanoriart".$li_i];
			$li_montotart= $_POST["txtmontotart".$li_i];
			//uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);

			$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codart."' readonly>";
			$lo_object[$li_i][2]="<input name=txtunidad".$li_i." type=text id=txtunidad".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
			$lo_object[$li_i][3]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][4]="<input name=txtpenart".$li_i." type=text id=txtpenart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_penart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$lo_object[$li_i][6]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_montotart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][8]="";
			$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";
	   }
	   uf_agregarlineablanca($lo_object,$ai_totrows);
   }
  	//--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ld_fecrec,$ls_obsrec;
		global $ls_checkedord,$ls_checkedfac,$ls_codusu,$ls_readonly;

		$ls_numordcom="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_codalm="";
		$ls_nomfisalm="";
		$ld_fecrec="";
		$ls_obsrec="";
		$ls_checkedord="";
		$ls_checkedfac="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
   }

   function uf_obtenervalorunidad($li_i)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalorunidad
	//	Access:    public
	//	Arguments:
    // 				li_i         //  valor del
    // 				ls_valor     //  nombre de la variable que desamos obtener
    // Description: Funci�n que obtiene el contenido del combo cmbunidad o
	//				del campo txtunidad deacuerdo sea el caso
	//////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("cmbunidad".$li_i,$_POST))
		{
			$ls_valor= $_POST["cmbunidad".$li_i];
		}
		else
		{
			$ls_valoraux= $_POST["txtunidad".$li_i];
			if($ls_valoraux=="Mayor")
			{
				$ls_valor="M";
			}
			else
			{
				$ls_valor="D";
			}
		}
   		return $ls_valor;
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reverso de Transferencias entre Almacenes </title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<style type="text/css">
<!--
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Inventario</span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"></div></td>
    <td class="toolbar" width="22"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=      new sigesp_include();
	$con=     $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=  new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun=  new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("sigesp_sim_c_revtransferencia.php");
	$io_siv=  new sigesp_sim_c_revtransferencia();
	require_once("sigesp_sim_c_revtransferencia_transf.php");
	$io_archivo=  new sigesp_sim_c_revtransferencia_transf();
	require_once("sigesp_sim_c_articuloxalmacen.php");
	$io_art= new sigesp_sim_c_articuloxalmacen();


	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = uf_obtenervalor("totalfilas",1);

	$ls_titletable="Entradas Actuales";
	$li_widthtable=620;
	$ls_nametable="grid";
	$lo_title[1]="Transferencia";
	$lo_title[2]="Almacen Origen";
	$lo_title[3]="Almacen Destino";
	$lo_title[4]="Fecha";
	$lo_title[5]="";

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_status=$_POST["hidestatus"];
		$ls_codtiend=$_POST["txtcodtie"];
		$ls_destienda=$_POST["txtdestienda"];
	}
	else
	{
		$ls_operacion=" ";
		$ls_status="";
		uf_limpiarvariables();
		//uf_agregarlineablanca($lo_object,1);
	}


	switch ($ls_operacion)
	{


		case "REVERSAR":
			$li_totrows= $_POST["totalfilas"];
		//	print $li_totrows."<br>";
			$li_temp=0;
			$li_s=0;
			$ld_fecrev= date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrev);

			if($lb_valido)
			{
				$io_sql->begin_transaction();
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{

					if (array_key_exists("chkreversar".$li_i,$_POST))
					{
						$li_s=$li_s + 1;
						$li_check= $_POST["chkreversar".$li_i];

						if ($li_check==1)
						{
							$ls_numtra= $_POST["txtnumtra".$li_i];

							$ls_codalmori= "";
							$ls_codalmdes= "";

							$lb_valido=$io_siv->uf_sim_select_transferencia($ls_codemp,$ls_numtra,$ls_codalmori,$ls_codalmdes,$ls_codtiend);
							if ($lb_valido)
							{

									$lb_valido=$io_siv->uf_sim_update_articulos($ls_codemp,$ls_numtra,$ls_codalmori,$ls_codalmdes,$la_seguridad,$ls_codtiend);

									if($lb_valido)
									{
										//print "ACA";
										$ls_opeinv= "SAL";
										$ls_codprodoc= "REV";
										$ls_promov= "TRA";
										$li_candesart= 0;
										$lb_valido=$io_siv->uf_sim_crear_movimientos($ls_codemp,$ld_fecrev,$ls_codalmori,$ls_codalmdes,
																					 $ls_opeinv,$ls_codprodoc,$ls_numtra,$ls_promov,
																					 $li_candesart,$ls_codusu,$la_seguridad,$ls_codtiend);
										if($lb_valido)
										{
											$lb_valido=$io_siv->uf_sim_delete_dt_transferencia($ls_codemp,$ls_numtra,$ls_codtiend);
											if ($lb_valido)
											{
												$lb_valido=$io_siv->uf_sim_delete_transferencia($ls_codemp,$ls_numtra,$ls_codtiend);
											}
										}
										else
										{
											$io_sql->rollback();
										}
									}
								}

					else
					{
						$li_temp=$li_temp + 1;
						$ls_numtra=    $_POST["txtnumtra".$li_i];
						$ls_nomfisori= $_POST["txtnomfisori".$li_i];
						$ls_nomfisdes= $_POST["txtnomfisdes".$li_i];
						$ld_fecemi=    $_POST["txtfecemi".$li_i];

						$lo_object[$li_temp][1]="<input name=txtnumtra".$li_temp."    type=text id=txtnumtra".$li_temp."    class=sin-borde size=20 maxlength=15 value='".$ls_numtra."' readonly>";
						$lo_object[$li_temp][2]="<input name=txtnomfisori".$li_temp." type=text id=txtnomfisori".$li_temp." class=sin-borde size=30 maxlength=50 value='".$ls_nomfisori."' readonly>";
						$lo_object[$li_temp][3]="<input name=txtnomfisdes".$li_temp." type=text id=txtnomfisdes".$li_temp." class=sin-borde size=30 maxlength=50 value='".$ls_nomfisdes."' readonly>";
						$lo_object[$li_temp][4]="<input name=txtfecemi".$li_temp."    type=text id=txtfecemi".$li_temp."    class=sin-borde size=12 maxlength=12 value='".$ld_fecemi."' readonly>";
						$lo_object[$li_temp][5]="<input type='checkbox' name=chkreversar".$li_temp." class= sin-borde value=1>";

					}
				}
						}
					}
				if(($li_i<1)||($li_s==0))
				{


					$io_msg->message("No se pudo realizar el reverso 1");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,1);
					break;
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El reverso se realizo con exito");
					$ls_operacion=" ";
					$ls_status="";
					$ls_codtiend="";
					$ls_destienda="";
					uf_agregarlineablanca($lo_object,1);
					print("<script language=JavaScript>");
					print("pagina='../sim/sigesp_sim_p_revtransferencia.php';");
		   			print(" location.href='../sfc/sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    		print("</script>");



				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo realizar el reverso 2");
				}

				if ($li_temp)
				{
					$li_totrows=$li_temp;
				}
				else
				{
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,1);
				}
			}

			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,1);
			}


		break;

		case "BUSCARRECEPCION":
			/*$ls_codtiend=$_POST["txtcodtie"];
		    $ls_destienda=$_POST["txtdestienda"];*/
			$li_totrows=0;
			$lb_valido=$io_siv->uf_sim_load_transferencia($li_totrows,$lo_object,$ls_codtiend);
			if (!$lb_valido)
			{
				//$lo_object="";
				uf_agregarlineablanca($lo_object,1);
			}

			break;

	}


?>

<p>&nbsp;</p>
<div align="center">
  <table width="649" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="755" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="626" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
               <td width="334" ><input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtiend?>" size="5" maxlength="4"></td>
              </tr>
              <tr>
                <td><table width="615" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td colspan="2" class="titulo-ventana">Reverso de Transferencias entre Almacenes</td>
                    </tr>

					<tr>
				        <td width="67" height="33"><div align="right">Unidad Operativa de Suministro</div></td>
				        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
				        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
				    </tr>


                    <tr class="formato-blanco">
                      <td width="154" height="13"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
                          <input name="hidreadonly" type="hidden" id="hidreadonly2"></td>
                      <td width="578">
                        <input name="txtdesalm" type="hidden" id="txtdesalm">
                        <input name="txttelalm" type="hidden" id="txttelalm">
                        <input name="txtubialm" type="hidden" id="txtubialm">
                        <input name="txtnomresalm" type="hidden" id="txtnomresalm">
                        <input name="txttelresalm" type="hidden" id="txttelresalm">
                        <input name="hidstatus" type="hidden" id="hidstatus"></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="22" colspan="2"><p align="center">
                    <?php
						$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                      </p></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="28" colspan="2"><div align="center">
                          <input name="operacion" type="hidden" id="operacion">
                          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
                          <input name="filadelete" type="hidden" id="filadelete">
                          <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
                          <input name="btnreversar" type="button" class="boton" id="btnreversar" onClick="javascript: uf_reversar();" value="Reversar">
</div></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td><div align="center"> </div></td>
              </tr>
            </table>
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function uf_reversar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		lb_valido=false;
		li_total=f.totalfilas.value;
		for(li_i=1; li_i<=li_total;li_i++)
		{

			ls_reversar=eval("f.chkreversar"+li_i+".checked");
			if(ls_reversar==true)
			{
				lb_valido=true;
				break;
			}
		}
		if(lb_valido)
		{
			if(confirm("�Esta seguro de querer reversar?"))
			{
				f.operacion.value="REVERSAR"
				f.action="sigesp_sim_p_revtransferencia.php";
				f.submit();
			}
		}
		else
		{
			alert("No selecciono documento a reversar");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


/**********************************************************************/

	function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);




		}

/**********************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentaiva,deniva)
		{
			f=document.form1;

			f.txtcodtie.value=codtie;
            f.txtdestienda.value=nomtie;
           	f.operacion.value="BUSCARRECEPCION";
    //f.action="sigesp_sim_p_revtransferencia.php";
				f.submit();



		}





</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>