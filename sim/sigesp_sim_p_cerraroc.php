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
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_p_cerraroc.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
		$aa_object[$ai_totrows][1]="<input name=txtnumordcom".$ai_totrows."  type=text   id=txtnumordcom".$ai_totrows."  class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtnompro".$ai_totrows."     type=text   id=txtnompro".$ai_totrows."     class=sin-borde size=15 maxlength=15 readonly>".
						    	   "<input name=txtcodpro".$ai_totrows."     type=hidden id=txtcodpro".$ai_totrows."     class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdenuniadm".$ai_totrows."  type=text   id=txtdenuniadm".$ai_totrows."  class=sin-borde size=40 maxlength=40 readonly>".
								   "<input name=txtcoduniadm".$ai_totrows."  type=hidden id=txtcoduniadm".$ai_totrows."  class=sin-borde size=40 maxlength=40 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtfecordcom".$ai_totrows."  type=text   id=txtfecordcom".$ai_totrows."  class=sin-borde size=12 maxlength=12 readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtmontot".$ai_totrows."     type=text   id=txtmontot".$ai_totrows."     class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][6]="<input name=chkprocesar".$ai_totrows."   type='checkbox'class= sin-borde value=1>";

   }

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ld_fecdes,$ld_fechas;
		global $selected0,$selected1,$ls_codusu,$ls_readonly,$ls_accion;
		
		$ls_numordcom="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_codalm="";
		$ls_nomfisalm="";
		$ld_fechas=date("d/m/Y");
		$ls_mes=date("m");
		$ls_annio=date("Y");
		$ld_fecdes="01/".$ls_mes."/".$ls_annio;
		$ls_obsrec="";
		$selected0="selected";
		$selected1="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_accion=0;
   }
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Cierre de &Oacute;rdenes de Compra</title>
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
<!--

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
//-->
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
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" width="20" height="20"></div></td>
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
	require_once("sigesp_sim_c_cerraroc.php");
	$io_siv=  new sigesp_sim_c_cerraroc();
	require_once("class_funciones_inventario.php");
	$io_inventario=  new class_funciones_inventario();

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = $io_inventario->uf_obtenervalor("totalfilas",1);
	$ls_titletable="Entradas Actuales";
	$li_widthtable=760;
	$ls_nametable="grid";
	$lo_title[1]="Órden de Compra";
	$lo_title[2]="Proveedor ó Beneficiario";
	$lo_title[3]="Unidad Ejecutora";
	$lo_title[4]="Fecha";
	$lo_title[5]="Monto";
	$lo_title[6]="";
	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	//	$ls_status=$_POST["hidestatus"];
	}
	else
	{
		$ls_operacion="";
		$ls_status="";
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,1);
	}
	switch ($ls_operacion) 
	{

		case "PROCESAR":
			$li_temp=0;
			$li_s=0;
			$ld_fecmov= date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecmov);
			$ls_accion=$io_inventario->uf_obtenervalor("cmbaccion",1);
			if($ls_accion==0)
			{
				$ls_estpenalm=1;
			}
			else
			{
				$ls_estpenalm=0;
			}
			$ld_fecdes=$io_inventario->uf_obtenervalor("txtfecdes",0);
			$ld_fechas=$io_inventario->uf_obtenervalor("txtfechas",0);
			if($ls_accion==0)
			{
				$selected1="";
				$selected0="selected";
			}
			else
			{
				$selected1="selected";
				$selected0="";
			}
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$ls_numordcom= $_POST["txtnumordcom".$li_i];
					$ls_codpro=    $_POST["txtcodpro".$li_i];
					$ls_nompro=    $_POST["txtnompro".$li_i];
					$ld_fecordcom= $_POST["txtfecordcom".$li_i];
					$li_montot=    $_POST["txtmontot".$li_i];
					$ls_coduniadm= $_POST["txtcoduniadm".$li_i];
					$ls_denuniadm= $_POST["txtdenuniadm".$li_i];
					if (array_key_exists("chkprocesar".$li_i,$_POST))
					{
						$li_s=$li_s + 1;
						$li_check= $_POST["chkprocesar".$li_i];
						if ($li_check==1)
						{
							$lb_valido=$io_siv->uf_sim_update_statusorden($ls_codemp,$ls_numordcom,$ls_estpenalm,$la_seguridad);
							if($lb_valido)
							{
								if($ls_accion==0)
								{
									$li_totmonart="";
									$li_totmoncar="";
									$lb_valido=$io_siv->uf_sim_load_dt_pendiente($ls_codemp,$ls_numordcom,$ls_coduniadm,$li_totmonart,$li_totmoncar,$la_seguridad);
								}
								else
								{
									$lb_valido=$io_siv->uf_load_comprobante($ls_codemp,$ls_numordcom,$ls_comprobante,$ld_feccmp);
									$ls_procedencia="SPGCMP";
									$ls_tipo="P";
									$ls_cedbene="----------";
									$lb_valido=$io_siv->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procedencia,$ls_comprobante,$ld_feccmp,$ls_tipo,$ls_cedbene,$ls_codpro,false );
									if (!$lb_valido) 
									{$io_msg->message("No existen registros contables");}	
									else
									{
										$lb_valido = $io_siv->io_sigesp_int->uf_int_init_transaction_begin();
										if(!$lb_valido)
										{
											$io_msg->message($io_siv->io_sigesp_int->is_msg_error);
										}	
										if($lb_valido)
										{	
											$lb_valido = $io_siv->io_sigesp_int->uf_init_end_transaccion_integracion();
											if (!$lb_valido)
											{
												$io_msg->message("Error".$io_siv->io_sigesp_int->is_msg_error);
											}
										}
									}		
								}//if($ls_accion==0)
							}
						}
					}
					else
					{
						$li_temp=$li_temp + 1;
		
						$lo_object[$li_temp][1]="<input  name=txtnumordcom".$li_temp."  type=text   id=txtnumordcom".$li_temp."  class=sin-borde size=20 maxlength=15 value='".$ls_numordcom."' readonly>";
						$lo_object[$li_temp][2]="<input  name=txtnompro".$li_temp."     type=text   id=txtnompro".$li_temp."     class=sin-borde size=40 maxlength=40 value='".$ls_nompro."'    readonly>".
											    "<input  name=txtcodpro".$li_temp."     type=hidden id=txtcodpro".$li_temp."     class=sin-borde size=40 maxlength=40 value='".$ls_codpro."'    readonly>";
						$lo_object[$li_temp][3]="<input  name=txtdenuniadm".$li_temp."  type=text   id=txtdenuniadm".$li_temp."  class=sin-borde size=45 maxlength=100 value='".$ls_denuniadm."' readonly>".
												"<input  name=txtcoduniadm".$li_temp."  type=hidden id=txtcoduniadm".$li_temp."  class=sin-borde size=40 maxlength=40  value='".$ls_coduniadm."' readonly>";
						$lo_object[$li_temp][4]="<input  name=txtfecordcom".$li_temp."  type=text   id=txtfecordcom".$li_temp."  class=sin-borde size=12 maxlength=12 value='".$ld_fecordcom."' readonly>";
						$lo_object[$li_temp][5]="<input  name=txtmontot".$li_temp."     type=text   id=txtmontot".$li_temp."     class=sin-borde size=15 maxlength=15 value='".$li_montot."'    readonly style=text-align:right>";
						$lo_object[$li_temp][6]="<input  name=chkprocesar".$li_temp."   type='checkbox' class= sin-borde value=1>";
					}
				}
				if(($li_i<=1)||($li_s==0))
				{
					$io_msg->message("No se pudo realizar el proceso");
					//$li_totrows=1;
					//uf_agregarlineablanca($lo_object,1);
					break;
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El proceso se realizo con exito");
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo realizar el proceso");
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

		case "BUSCARORDEN":
			$li_totrows=0;
			$ls_accion=$io_inventario->uf_obtenervalor("cmbaccion",1);
			//$ls_accion=$io_inventario->uf_obtenervalor("radioaccion",1);
			$ld_fecdes=$io_inventario->uf_obtenervalor("txtfecdes",0);
			$ld_fechas=$io_inventario->uf_obtenervalor("txtfechas",0);
			if($ls_accion==0)
			{
				$selected1="";
				$selected0="selected";
			}
			else
			{
				$selected1="selected";
				$selected0="";
			}
			$lb_valido=$io_siv->uf_sim_load_ordenes($li_totrows,$lo_object,$ls_accion,$ld_fecdes,$ld_fechas);
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
              </tr>
              <tr>
                <td><table width="615" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td colspan="3" class="titulo-ventana">Cierre de &Oacute;rdenes de Compra </td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="25" colspan="3"><label></label></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td width="150" height="22"><div align="right">Acci&oacute;n</div></td>
                      <td colspan="2"><label>
                      <select name="cmbaccion" size="1">
                        <option value="0" <?php print $selected0 ?>>Cerrar &Oacute;rden de Compra</option>
                        <option value="1" <?php print $selected1 ?>>Reverso de Cierre</option>
                      </select>
                      </label></td>
                    </tr>
                    <tr class="formato-blanco">
                    <td height="22"><div align="right">Desde</div></td>
                    <td width="119" height="22"><input name="txtfecdes" type="text" id="txtfecdes"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecdes?>" size="18"  datepicker="true" style="text-align:center "></td>
                    <td width="344"> Hasta
                      <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fechas?>" size="18"  datepicker="true" style="text-align:center "></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="22" colspan="3"><div align="right"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" class="sin-borde">Buscar &Oacute;rdenes</a></div></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="22" colspan="3"><p align="center">
                          <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                      </p></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="28" colspan="3"><div align="center">
                          <input name="operacion" type="hidden" id="operacion">
                          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
                          <input name="filadelete" type="hidden" id="filadelete">
                          <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
                          <input name="btnproceasr" type="button" class="boton" id="btnproceasr" onClick="javascript: ue_procesar();" value="Procesar">
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
function ue_buscar()
{
	
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		valido=f.cmbaccion[0].selected;
		valido1=f.cmbaccion[1].selected;
		if((valido)||(valido1))
		{
			f.operacion.value="BUSCARORDEN";
			f.action="sigesp_sim_p_cerraroc.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un tipo de búsqueda");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_procesar()
{
	
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		f.operacion.value="PROCESAR";
		f.action="sigesp_sim_p_cerraroc.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_validar_oc(li_row)
{
	f=document.form1;
	ls_numordcom=eval("f.txtnumordcom"+li_row+".value");
	li_totrows=f.totalfilas.value;
	for(li_i=1;li_i<=li_totrows;li_i++)
	{
		ls_numordcomgrid=eval("f.txtnumordcom"+li_i+".value");
		if((ls_numordcom==ls_numordcomgrid)&&(li_i!=li_row))
		{
			if(eval("f.chkprocesar"+li_i+".checked")==1)
			{
				alert("No puede cerrar la misma O/C para mas de una unidad");
				obj=eval("f.chkprocesar"+li_row+"");
				obj.checked=0;
			}
		}
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
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

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>