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
require_once("class_folder/class_funciones_sob.php");
$io_fun_sob=new class_funciones_sob();
$io_fun_sob->uf_load_seguridad("SCV","sigesp_scv_p_revcalcularviaticos.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   
   function uf_agregarlineablanca(&$aa_object,&$aa_title,&$as_titletable,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_agregarlineablanca
		//	Access:    public
		//	Arguments:
		//  			  aa_object // arreglo de objetos 
		//  			  aa_title  // arreglo de titulos 
		//  			  ai_totrows // ultima fila pintada en el grid
		//	Description:  Funcion que agrega una linea en blanco al final del grid
		//              
		//////////////////////////////////////////////////////////////////////////////		
		$aa_title[1]="--";
		$aa_title[2]="--";
		$aa_title[3]="--";
		$aa_title[4]="";
		$aa_object[$ai_totrows][1]="<input name=txtnumrecdoc".$ai_totrows." type=text id=txtnumrecdoc".$ai_totrows." class=sin-borde size=20 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtconcepto".$ai_totrows."  type=text id=txtconcepto".$ai_totrows."  class=sin-borde size=20 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtfecha".$ai_totrows."     type=text id=txtfecha".$ai_totrows."     class=sin-borde size=55 readonly>";
		$aa_object[$ai_totrows][4]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
		$as_titletable="Recepciones de Documentos";

   }
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ld_fecregdes,$ld_fecreghas,$ls_chkant,$ls_chkval;
		
		$ld_fecregdes="01/".date("m/Y");
		$ld_fecreghas=date("d/m/Y");
		$ls_chkant="checked";
		$ls_chkval="";
   }
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reverso de Recepciones de Documentos</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: uf_reversar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Grabar" width="20" height="20" border="0" title="Procesar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
	require_once("class_folder/sigesp_sob_c_revanticipo_rd.php");
	$io_sob=  new sigesp_sob_c_revanticipo_rd();

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = uf_obtenervalor("totalfilas",1);
	$li_widthtable=780;
	$ls_nametable="grid";
	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="NUEVO";
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
			$ls_tiprecdoc=$io_fun_sob->uf_obtenervalor("rdtipord", 0);
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
							if($ls_tiprecdoc==0)
							{
								$ls_codcon=$io_fun_sob->uf_obtenervalor("txtcodcon".$li_i, 0);
								$ls_codant=$io_fun_sob->uf_obtenervalor("txtcodant".$li_i, 0);
								$ls_numrecdoc=$ls_codcon.$ls_codant;
								$ls_codpro=$io_sob->uf_select_contratista($ls_codcon);
								$lb_valido=$io_sob->uf_select_estatus_recepcion($ls_numrecdoc,$ls_codpro,$lb_registro);
								if ($lb_valido)
								{
									if($lb_registro)
									{
											$lb_valido=$io_sob->uf_delete_dt_rd($ls_numrecdoc,$ls_codpro,$la_seguridad);
											if($lb_valido)
											{
												$lb_valido=$io_sob->uf_delete_rd($ls_numrecdoc,$ls_codpro,$la_seguridad);
												if($lb_valido)
												{
													$lb_valido=$io_sob->uf_update_estatus_anticipo($ls_codcon,$ls_codant,$la_seguridad);
												}
											}
									}
									else
									{
										$io_msg->message("Las Recepciones de Documentos asociadas deben estar en estatus de Registro - No Aprobada");
									}
								}
								else
								{
									$io_msg->message("No existe Recepcion de Documentos asociada");
								}
							}
							else
							{
								$ls_codcon=$io_fun_sob->uf_obtenervalor("txtcodcon".$li_i, 0);
								$ls_codval=$io_fun_sob->uf_obtenervalor("txtcodval".$li_i, 0);
								$ls_numrecdoc=$ls_codcon;
								$ls_codpro=$io_sob->uf_select_contratista($ls_codcon);
								$lb_valido=$io_sob->uf_select_estatus_recepcion($ls_numrecdoc,$ls_codpro,$lb_registro);
								if ($lb_valido)
								{
									if($lb_registro)
									{
											$lb_valido=$io_sob->uf_delete_dt_rd($ls_numrecdoc,$ls_codpro,$la_seguridad);
											if($lb_valido)
											{
												$lb_valido=$io_sob->uf_delete_rd($ls_numrecdoc,$ls_codpro,$la_seguridad);
												if($lb_valido)
												{
													$lb_valido=$io_sob->uf_update_estatus_anticipo($ls_codcon,$ls_codval,$la_seguridad);
												}
											}
									}
									else
									{
										$io_msg->message("Las Recepciones de Documentos asociadas deben estar en estatus de Registro - No Aprobada");
									}
								}
								else
								{
									$io_msg->message("No existe Recepcion de Documentos asociada");
								}
							}
						}
					}
					else
					{
						$li_totrows=1;
						uf_agregarlineablanca($lo_object,$lo_title,$ls_titletable,1);
					}
				}
				if(($li_i<=1)||($li_s==0))
				{
					$io_msg->message("No se pudo realizar el reverso");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,$lo_title,$ls_titletable,1);
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El reverso se realizo con exito");
					uf_limpiarvariables();
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo realizar el reverso");
					uf_limpiarvariables();
				}
	
				if ($li_temp)
				{
					$li_totrows=$li_temp;
				}
				else
				{
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,$lo_title,$ls_titletable,1);
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$lo_title,$ls_titletable,1);
			}
		break;

		case "BUSCARSOLICITUD":
			$li_totrows=0;
			$ld_fecregdes=$io_fun_sob->uf_obtenervalor("txtfecregdes", "");
			$ld_fecreghas=$io_fun_sob->uf_obtenervalor("txtfecreghas", "");
			$ls_codcon=$io_fun_sob->uf_obtenervalor("txtcodcon", "");
			$ls_tiprecdoc=$io_fun_sob->uf_obtenervalor("rdtipord", 0);
			$ls_status=$ls_tiprecdoc;
			if($ls_tiprecdoc==0)
			{
				$lb_valido=$io_sob->uf_scv_obtener_anticipos($ls_codcon,$ld_fecregdes,$ld_fecreghas,$li_totrows,$lo_object,$lo_title,$ls_titletable);
				$ls_chkant="checked";
				$ls_chkval="";
			}
			else
			{
				$lb_valido=$io_sob->uf_scv_obtener_valuaciones($ls_codcon,$ld_fecregdes,$ld_fecreghas,$li_totrows,$lo_object,$lo_title,$ls_titletable);
				$ls_chkant="";
				$ls_chkval="checked";
			}
		//	$lb_valido=$io_sob->uf_scv_obtener_solicitud($ls_numsol,$ld_fecregdes,$ld_fecreghas,$li_totrows,$lo_object);
			if (!$lb_valido)
			{
				$lo_object="";
				uf_agregarlineablanca($lo_object,$lo_title,$ls_titletable,1);
			}

		break;
		
		case "NUEVO":
			uf_agregarlineablanca($lo_object,$lo_title,$ls_titletable,$li_totrows);
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
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="805">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="767" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td colspan="2" class="titulo-ventana">Reverso de Recepciones de Documentos</td>
                    </tr>
                    <tr class="formato-blanco">
                      <td width="139" height="13"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
                      <input name="hidreadonly" type="hidden" id="hidreadonly2"></td>
                      <td width="626">&nbsp;</td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="13" colspan="2"><table width="667" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="22"  align="right">&nbsp;</td>
                          <td height="22" colspan="2" ><label><input name="rdtipord" type="radio" class="sin-borde" value="0" <?php print $ls_chkant; ?>>
                            Anticipo</label>
                              <label><input name="rdtipord" type="radio" class="sin-borde" value="1" <?php print $ls_chkval; ?>>
                            Valuaci&oacute;n</label>                            </td>
                        </tr>
                        <tr>
                          <td width="151" height="22"  align="right">Contrato</td>
                          <td height="22" colspan="2" ><input name="txtcodcon" type="text" id="txtcodcon" size="15"></td>
                        </tr>
                        <tr>
                          <td height="22"  align="right">Fecha de Registro </td>
                          <td width="128" height="22" align="center">Desde
                            <input name="txtfecregdes" type="text" id="txtfecregdes"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecregdes; ?>" size="13" maxlength="10" datepicker="true"></td>
                          <td width="388">Hasta
                            <input name="txtfecreghas" type="text" id="txtfecreghas"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecreghas; ?>" size="13" maxlength="10" datepicker="true"></td>
                        </tr>
                        <tr>
                          <td height="22"  align="right">&nbsp;</td>
                          <td height="22" colspan="2">&nbsp;</td>
                        </tr>
                      </table></td>
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
		status=f.hidestatus.value;
		 for(i=0;i<f.rdtipord.length;i++)
        if(f.rdtipord[i].checked) tipord= f.rdtipord[i].value;
		if(tipord==status)
		{
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
					f.action="sigesp_sob_p_revanticipo_rd.php";
					f.submit();
				}
			}
			else
			{
				alert("No selecciono documento a reversar");
			}
		}
		else
		{alert ("El tipo de Recepcion a Procesar no corresponde con la busqueda realizada");}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		valido=ue_validarcampo(f.txtfecregdes.value,"Los campos de fecha no deben estar vacios",f.txtfecregdes);
		if(valido)
		{
			valido=ue_validarcampo(f.txtfecreghas.value,"Los campos de fecha no deben estar vacios",f.txtfecreghas.value);
			if(valido)
			{
				f.operacion.value="BUSCARSOLICITUD"
				f.action="sigesp_sob_p_revanticipo_rd.php";
				f.submit();
			}
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
////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}
//--------------------------------------------------------
//	Función que verifica si un campo esta vacio y de ser asi lo mando a ese campo
//--------------------------------------------------------
function ue_validarcampo(campo,mensaje,foco)
{
	valido=true;
	if(campo=="")
	{
		alert(mensaje);
		foco.focus();
		valido=false;
	}
	return valido;
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>