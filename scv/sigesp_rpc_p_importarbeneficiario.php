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
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_p_revcalcularviaticos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // 				as_valor         //  nombre de la variable que desamos obtener
    // 				as_valordefecto  //  contenido de la variable
    // Description: Función que obtiene el valor de una variable si viene de un submit
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
		$aa_object[$ai_totrows][1]="<input name=txtcodsolvia".$ai_totrows." type=text id=txtcodsolvia".$ai_totrows." class=sin-borde size=12 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtdenmis".$ai_totrows."    type=text id=txtdenmis".$ai_totrows."    class=sin-borde size=44 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdesrut".$ai_totrows."    type=text id=txtdesrut".$ai_totrows."    class=sin-borde size=37 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtdenuniadm".$ai_totrows." type=text id=txtdenuniadm".$ai_totrows." class=sin-borde size=34 readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtfecsolvia".$ai_totrows." type=text id=txtfecsolvia".$ai_totrows." class=sin-borde size=10 readonly>";
		$aa_object[$ai_totrows][6]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";

   }
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
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
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reverso de Calculos de Vi&aacute;ticos </title>
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
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("sigesp_rpc_c_importarbeneficiarios.php");
	$io_scv= new sigesp_rpc_c_importarbeneficiario();

	$ls_codemp= $_SESSION["la_empresa"]["codemp"];
	$ls_codusu= $_SESSION["la_logusr"];
	$li_totrows = uf_obtenervalor("totalfilas",1);
	$ls_titletable="Solicitudes Calculadas";
	$li_widthtable=780;
	$ls_nametable="grid";
	$lo_title[1]="Solicitud";
	$lo_title[2]="Misión";
	$lo_title[3]="Ruta";
	$lo_title[4]="Unidad Solicitante";
	$lo_title[5]="Fecha";
	$lo_title[6]="";
	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_status=$_POST["hidestatus"];
	}
	else
	{
		$ls_operacion="BUSCARSOLICITUD";
		$ls_status="";
		uf_limpiarvariables();
		//uf_agregarlineablanca($lo_object,1);
	}
	switch ($ls_operacion) 
	{

		case "REVERSAR":
			$li_totrows= $_POST["totalfilas"];
			$li_temp=0;
			$li_s=0;
			$ld_fecrev= date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrev);
			$io_sql->begin_transaction();
			if($lb_valido)
			{
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					if (array_key_exists("chkreversar".$li_i,$_POST))
					{
						$li_s= $li_s + 1;
						$li_check= $_POST["chkreversar".$li_i];
						if ($li_check==1)
						{
							$ls_codsolvia= $_POST["txtcodsolvia".$li_i];
							$lb_valido=$io_scv->uf_scv_select_estatus_recepcion($ls_codemp,$ls_codsolvia,$lb_registro,$ls_numrecdoc);
							if ($lb_valido)
							{
								if($lb_registro)
								{
									$lb_valido=$io_scv->uf_scv_delete_dt_rd_scg($ls_codemp,$ls_numrecdoc,$ls_codsolvia,
																			    $la_seguridad);
									if($lb_valido)
									{
										$lb_valido=$io_scv->uf_scv_delete_dt_rd_spg($ls_codemp,$ls_numrecdoc,$ls_codsolvia,
																					$la_seguridad);
										if($lb_valido)
										{
											$lb_valido=$io_scv->uf_scv_delete_rd($ls_codemp,$ls_numrecdoc,$ls_codsolvia,
																				 $la_seguridad);
											if($lb_valido)
											{
												$lb_valido=$io_scv->uf_scv_delete_dt_scg($ls_codemp,$ls_codsolvia,$ls_numrecdoc,
																						 $la_seguridad);
												if($lb_valido)
												{
													$lb_valido=$io_scv->uf_scv_delete_dt_spg($ls_codemp,$ls_codsolvia,$ls_numrecdoc,
																							 $la_seguridad);
													if($lb_valido)
													{
														$lb_valido=$io_scv->uf_scv_update_solivitud_viaticos($ls_codemp,$ls_codsolvia,
																										     $la_seguridad);
													}
												}
											}
										}
									}
								}
								else
								{
									$io_msg->message("Las Recepciones de Documentos asociadas deben estar en estatus de Registro");
								}
							}
							else
							{
								$io_msg->message("No existe Recepcion de Documentos asociada");
							}
						}
					}
					else
					{
						$li_temp=$li_temp + 1;
						$ls_codsolvia= $_POST["txtcodsolvia".$li_i];
						$ls_denmis=    $_POST["txtdenmis".$li_i];
						$ls_desrut=    $_POST["txtdesrut".$li_i];
						$ls_denuniadm= $_POST["txtdenuniadm".$li_i];
						$ld_fecsolvia= $_POST["txtfecsolvia".$li_i];
								
						$lo_object[$li_temp][1]="<input name=txtcodsolvia".$li_temp." type=text id=txtcodsolvia".$li_temp." class=sin-borde size=12 value='".$ls_codsolvia."' readonly>";
						$lo_object[$li_temp][2]="<input name=txtdenmis".$li_temp."    type=text id=txtdenmis".$li_temp."    class=sin-borde size=44 value='".$ls_denmis."'    readonly>";
						$lo_object[$li_temp][3]="<input name=txtdesrut".$li_temp."    type=text id=txtdesrut".$li_temp."    class=sin-borde size=37 value='".$ls_desrut."'    readonly>";
						$lo_object[$li_temp][4]="<input name=txtdenuniadm".$li_temp." type=text id=txtdenuniadm".$li_temp." class=sin-borde size=34 value='".$ls_denuniadm."' readonly>";
						$lo_object[$li_temp][5]="<input name=txtfecsolvia".$li_temp." type=text id=txtfecsolvia".$li_temp." class=sin-borde size=10 value='".$ld_fecsolvia."' readonly>";
						$lo_object[$li_temp][6]="<input type='checkbox' name=chkreversar".$li_temp." class= sin-borde value=1>";
	
					}
				}
				if(($li_i<=1)||($li_s==0))
				{
					$io_msg->message("No se pudo realizar el reverso");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,1);
					break;
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El reverso se realizo con exito");
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo realizar el reverso");
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

		case "BUSCARSOLICITUD":
			$li_totrows=0;
			$lb_valido=$io_scv->uf_scv_obtener_solicitud($li_totrows,$lo_object);
			if (!$lb_valido)
			{
				$lo_object="";
				uf_agregarlineablanca($lo_object,1);
			}

			break;
			
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="793" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="800" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td class="titulo-nuevo">Criterios de B&uacute;squeda </td>
              </tr>
              <tr>
                <td><table width="609" border="0" align="center" cellspacing="0">
                  <tr>
                    <td width="83" height="22"><div align="left"></div></td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td height="22"><div align="right">C&oacute;digo</div></td>
                    <td height="22"><input type="text" name="textfield22"></td>
                    </tr>
                  <tr>
                    <td height="22"><div align="right">C&eacute;dula</div></td>
                    <td height="22"><input type="text" name="textfield3">                      </td>
                    </tr>
                  <tr>
                    <td height="22"><div align="right">Nombre</div></td>
                    <td height="22"><input type="text" name="textfield"></td>
                    </tr>
                  <tr>
                    <td height="22"><div align="right">Apellido</div></td>
                    <td height="22"><input type="text" name="textfield2"></td>
                    </tr>

                  <tr>
                    <td>&nbsp;</td>
                    <td><div align="right"><a href="k"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" class="sin-borde">Buscar</a></div></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td width="805">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="767" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td colspan="2" class="titulo-ventana">Personal</td>
                    </tr>
                    <tr class="formato-blanco">
                      <td width="139" height="13"><input name="hidestatus" type="hidden" id="hidestatus2" value="<?php print $ls_status?>">
                      <input name="hidreadonly" type="hidden" id="hidreadonly2"></td>
                      <td width="626">&nbsp;</td>
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
			if(confirm("¿Esta seguro de querer reversar?"))
			{
				f.operacion.value="REVERSAR"
				f.action="sigesp_scv_p_revcalcularviaticos.php";
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

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>