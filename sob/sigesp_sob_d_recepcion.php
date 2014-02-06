<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Recepci&oacute;n de Documentos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	/*require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="sigesp_sob_d_recepcion.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}*/

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_fun=new class_funciones();
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob= new sigesp_sob_c_funciones_sob();
require_once("class_folder/sigesp_sob_class_mensajes.php");
$io_mensaje=new sigesp_sob_class_mensajes();
require_once("class_folder/sigesp_sob_c_recepcion.php");
$io_recepcion=new sigesp_sob_c_recepcion();


$li_ancho=600;
$ls_nametable="grid";
$ls_titulovaluacion="Valuaciones";
$la_columvaluacion[1]="    ";
$la_columvaluacion[2]="<a href=javascript:ue_ordenar('v.codval');><font color=#FFFFFF>Cód. Val.</font></a>";
$la_columvaluacion[3]="<a href=javascript:ue_ordenar('v.codcon');><font color=#FFFFFF>Cód. Con.</font></a>";
$la_columvaluacion[4]="<a href=javascript:ue_ordenar('o.desobr');><font color=#FFFFFF>Descripción de la Obra</a>";
$la_columvaluacion[5]="<a href=javascript:ue_ordenar('v.fecha');><font color=#FFFFFF>Fecha</a>";
$la_columvaluacion[6]="<a href=javascript:ue_ordenar('c.monto');><font color=#FFFFFF>Monto</a>";
$la_columvaluacion[7]="<a href=javascript:ue_ordenar('c.estval');><font color=#FFFFFF>Estado</a>";

$ls_tituloanticipo="Anticipos";
$la_columanticipo[1]="    ";
$la_columanticipo[2]="<a href=javascript:ue_ordenar('an.codant');><font color=#FFFFFF>Cód. Ant.</a>";
$la_columanticipo[3]="<a href=javascript:ue_ordenar('an.codcon');><font color=#FFFFFF>Cód. Cont.</a>";
$la_columanticipo[4]="<a href=javascript:ue_ordenar('o.desobr');><font color=#FFFFFF>Descripción de la Obra</a>";
$la_columanticipo[5]="<a href=javascript:ue_ordenar('an.fecant');><font color=#FFFFFF>Fecha</a>";
$la_columanticipo[6]="<a href=javascript:ue_ordenar('an.monto');><font color=#FFFFFF>Monto</a>";
$la_columanticipo[7]="<a href=javascript:ue_ordenar('an.estant');><font color=#FFFFFF>Estado</a>";

if(!array_key_exists("operacion",$_POST))
{
	$ls_documento="s1";
	$li_filas=1;
	$ls_orden="ASC";
	$ls_campo="";
}
else
{
	$ls_documento=$_POST["cmbdocumento"];
	$ls_operacion=$_POST["operacion"];
	$ls_orden=$_POST["orden"];
	$ls_campo=$_POST["campo"];
	
	if($ls_documento=="VALUACION" && $ls_operacion=="")
	{
		if($ls_campo=="") $ls_campobusqueda="v.codval";
		else $ls_campobusqueda=$ls_campo;
		$lb_valido=$io_recepcion->uf_select_valuacion($ls_campobusqueda,$ls_orden,$la_valuaciones,$li_filas);
		for($li_i=1;$li_i<=$li_filas;$li_i++)
		{					
			$ls_codcon=$la_valuaciones["codcon"][$li_i];
			$ls_codval=$la_valuaciones["codval"][$li_i];
			$ls_desobr=$la_valuaciones["desobr"][$li_i];
			$ls_fecval=$io_fun->uf_convertirfecmostrar($la_valuaciones["fecha"][$li_i]);
			$ls_monto=$io_funsob->uf_convertir_numerocadena($la_valuaciones["montotval"][$li_i]);
			$ls_estval=$io_funsob->uf_convertir_numeroestado ($la_valuaciones["estval"][$li_i]);
			$la_object[$li_i][1]="<input type=checkbox name=chkb".$li_i." value=1  class=sin-borde>";
			$la_object[$li_i][2]="<input name=txtcodval".$li_i."  type=text id=txtcodval".$li_i."  class=sin-borde style= text-align:center value='".$ls_codval."' size=5 readonly>";
			$la_object[$li_i][3]="<input name=txtcodcon".$li_i."  type=text id=txtcodcon".$li_i."  class=sin-borde style= text-align:center value='".$ls_codcon."' size=5 readonly>";
			$la_object[$li_i][4]="<textarea name=txtdesobr".$li_i."id=txtdesobr".$li_i."  class=sin-borde cols=50 rows=2 text-align:center>".$ls_desobr."</textarea>";
			$la_object[$li_i][5]="<input name=txtfecval".$li_i."  type=text id=txtfecval".$li_i."  class=sin-borde style= text-align:center value='".$ls_fecval."'  size=8 readonly>";
			$la_object[$li_i][6]="<input name=txtmonto".$li_i."  type=text id=txtmonto".$li_i."  class=sin-borde style= text-align:center value='".$ls_monto."'  size=15 readonly>";
			$la_object[$li_i][7]="<input name=txtestval".$li_i."  type=text id=txtestval".$li_i."  class=sin-borde style= text-align:center value='".$ls_estval."'  size=13 readonly>";
		}		
	}
	elseif($ls_documento=="ANTICIPO" && $ls_operacion=="")
	{
		if($ls_campo=="") $ls_campobusqueda="an.codant";
		else $ls_campobusqueda=$ls_campo;
		
		$lb_valido=$io_recepcion->uf_select_anticipo($ls_campobusqueda,$ls_orden,$la_anticipo,$li_filas);
			
		for($li_i=1;$li_i<=$li_filas;$li_i++)
		{					
			$ls_codcon=$la_anticipo["codcon"][$li_i];
			$ls_codant=$la_anticipo["codant"][$li_i];
			$ls_desobr=$la_anticipo["desobr"][$li_i];
			$ls_fecant=$io_fun->uf_convertirfecmostrar($la_anticipo["fecant"][$li_i]);
			$ls_monto=$io_funsob->uf_convertir_numerocadena($la_anticipo["montotant"][$li_i]);
			$ls_estant=$io_funsob->uf_convertir_numeroestado ($la_anticipo["estant"][$li_i]);
			$la_object[$li_i][1]="<input type=checkbox name=chkb".$li_i." value=1  class=sin-borde>";
			$la_object[$li_i][2]="<input name=txtcodant".$li_i."  type=text id=txtcodant".$li_i."  class=sin-borde style= text-align:center value='".$ls_codant."' size=5 readonly>";
			$la_object[$li_i][3]="<input name=txtcodcon".$li_i."  type=text id=txtcodcon".$li_i."  class=sin-borde style= text-align:center value='".$ls_codcon."' size=5 readonly>";
			$la_object[$li_i][4]="<textarea name=txtdesobr".$li_i."id=txtdesobr".$li_i."  class=sin-borde cols=50 rows=2 text-align:center>".$ls_desobr."</textarea>";
			$la_object[$li_i][5]="<input name=txtfecant".$li_i."  type=text id=txtfecant".$li_i."  class=sin-borde style= text-align:center value='".$ls_fecant."'  size=8 readonly>";
			$la_object[$li_i][6]="<input name=txtmonto".$li_i."  type=text id=txtmonto".$li_i."  class=sin-borde style= text-align:center value='".$ls_monto."'  size=15 readonly>";
			$la_object[$li_i][7]="<input name=txtestant".$li_i."  type=text id=txtestant".$li_i."  class=sin-borde style= text-align:center value='".$ls_estant."'  size=13 readonly>";
		}		
	}	
}

?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
/*if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="798" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td width="20">&nbsp;</td>
        <td colspan="10" class="titulo-celdanew">Recepción de Documentos</td>
        <td width="17">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="29">&nbsp;</td>
        <td width="62" align="right">Documento</td>
        <td width="199" align="left">
		  <div align="left">
		  <select name="cmbdocumento" onChange="javascript:ue_refrescar()">
	        <option value="s1">Seleccione</option>
            <?Php
		  			$la_textos = array("Anticipo","Valuación");
		  			$la_valores = array("ANTICIPO","VALUACION");
					for($li_i=0;$li_i< count($la_textos);$li_i++)
					{
					 $ls_codigo=$la_valores[$li_i];
					 $ls_descripcion=$la_textos[$li_i];
					 if ($ls_codigo==$ls_documento)
					 {
						  print "<option value='$ls_codigo' selected>$ls_descripcion</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_descripcion</option>";
					 }
					}
	           ?>
          </select>
		  <input type="hidden" name="hiddocumento" id="hiddocumento" value="<?php print $ls_documento?>">
		    </div></td>
        <td width="431">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="formato-blanco">
        <td colspan="12">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td>		
		<?Php
			if($ls_documento!="s1" && $li_filas>0)
			{
			?>
				<div align="center">		  </div>
		</td>
				  <td colspan="10"><table width="699" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
                    <tr class="formato-blanco">
                      <td width="15" height="13">&nbsp;</td>
                      <td width="684"><div align="left"></div></td>
                    </tr>
                    <tr align="center" class="formato-blanco">
                      <td colspan="2">
                        <?php
					  	 if($ls_documento=="ANTICIPO")
						 { 
					  		$io_grid->makegrid($li_filas,$la_columanticipo,$la_object,$li_ancho,$ls_tituloanticipo,$ls_nametable);
						}
						elseif($ls_documento=="VALUACION")
						{
							$io_grid->makegrid($li_filas,$la_columvaluacion,$la_object,$li_ancho,$ls_titulovaluacion,$ls_nametable);
						}
						?>
                      </td>
                    </tr>
                    <tr class="formato-blanco">
                      <td colspan="2">&nbsp;</td>
                    </tr>
                  </table></td>
				  <td>&nbsp;</td>
      </tr>
				  <tr class="formato-blanco">
					<td>&nbsp;</td>
					<td colspan="10" align="center">					 
					  <table width="169" class="celdas-azules">
					  <tr>
						<td width="161" class="celdas-blancas">
						<input type="radio" name="radio" value="radiogenerar" class="sin-borde"><label>Generar Documento</label>
						</td>
						</tr>
					  <tr>
						<td class="celdas-blancas">
						  <input type="radio" name="radio" value="radioreversar"   class=sin-borde><label>Reversar Documento</label>
					    </td>
						</tr>
					</table>   			         
			 <?
			}
			elseif($ls_documento!="s1" && $li_filas==0)
			{
				if($ls_documento=="ANTICIPO")
					$io_msg->message("No se han creado anticipos que puedan generar documentos!!!");
				elseif($ls_documento=="VALUACION")
					$io_msg->message("No se han creado valuaciones que puedan generar documentos!!!");
			}
			?>	  	
		</td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="12"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="12">&nbsp;</td>
      </tr>
    </table>
  <!-- Los Hidden son colocados a partir de aca-->
<input type="hidden" name="operacion" id="operacion">
<input type="hidden" name="filas" id="filas" value="<?php print $li_filas;?>">
<input type="hidden" name="campo" id="campo" value="<?php print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<?php print $ls_orden;?>">


<!-- Fin de la declaracion de Hidden-->
  </form>
</body>
<script language="javascript">
function ue_refrescar()
{
	f=document.form1;
	f.submit();
}

function ue_ordenar(campo)
{
	f=document.form1;
	f.operacion.value="";
	if(campo==f.campo.value)
	{
		if(f.orden.value=="ASC")
			f.orden.value="DESC";
		else
			f.orden.value="ASC";
	}
	else
		f.orden.value="ASC";
	f.campo.value=campo;	
	f.submit();
}
///////Funciones para llamar catalogos////////////////
/*function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";			
	var estado="5";
	pagina="sigesp_cat_contrato.php?estado="+estado;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,left=0,top=0;");
}*/

/*function ue_catretenciones()
{
	f=document.form1;
	if(ue_valida_null(f.txtcodant,"Debe seleccionar un nuevo Anticipo!!!")==false)
	{
	}
	else
	{*/
		/*if(f.txtmonto.value=="" || parseInt(f.txtmonto.value)==0)
		{
			alert("Debe especificar un monto para el Anticipo!!!");
			f.txtmonto.value="0,00";
			f.txtmonto.focus();			
		}*/
		/*else
		{*/
			/*f.operacion.value="";			
			pagina="sigesp_cat_retenciones.php";
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,left=0,top=0;");
		/*}*/
	/*}
	
}

///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////
function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins)
{
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.hidobra.value=ls_codobr;
	f.operacion.value="ue_cargarcontrato";
	f.submit();
}
function ue_cargarretenciones(codigo,descripcion,cuenta,deducible,formula)
{
	f=document.form1;
	f.operacion.value="ue_cargarretenciones";	
	lb_existe=false;
		
	for(li_i=1;li_i<=f.hidfilasretenciones.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodret"+li_i+".value");
		//alert("codigo nuevo '"+codigo+"' codigo de la comparacion '"+eval("f.txtcodpar"+f.filaspartidas.value+".value")+"'");
		if(ls_codigo==codigo)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
		eval("f.txtcodret"+f.hidfilasretenciones.value+".value='"+codigo+"'");
		eval("f.txtdesret"+f.hidfilasretenciones.value+".value='"+descripcion+"'");
		eval("f.txtcueret"+f.hidfilasretenciones.value+".value='"+cuenta+"'");
		eval("f.txtdedret"+f.hidfilasretenciones.value+".value='"+deducible+"'");
		eval("f.hidformula"+f.hidfilasretenciones.value+".value='"+formula+"'");
		f.submit();
	}

}

function ue_removerretenciones(li_fila)
{
	f=document.form1;
	f.hidremoverretenciones.value=li_fila;
	f.operacion.value="ue_removerretenciones"
	f.action="sigesp_sob_d_anticipo.php";
	f.submit();
}

function ue_cargaranticipo (ls_codcon,ls_desobr,ls_estado,ls_codest,ls_codobr,ld_monto,ls_codant,ls_fecintant,ld_porant,ls_conant,ld_montotant,ls_cuenta,ls_fecant)
{
	f=document.form1;
	f.txtcodcon.value=ls_codcon;
	f.hidobra.value=ls_codobr;
	f.txtcodant.value=ls_codant;
	f.txtfecintant.value=ls_fecintant;
	f.txtfecant.value=ls_fecant;
	f.txtmonto.value=uf_convertir(ld_monto);
	f.txtporant.value=uf_convertir(ld_porant);
	f.txtsc_cuenta.value=ls_cuenta;
	f.txtconant.value=ls_conant;
	f.txtestant.value=ls_estado;
	f.txtmontotant.value=uf_convertir(ld_montotant);
	f.operacion.value="ue_cargaranticipo";
	f.hidstatus.value="C";
	f.submit();
}

//////Fin de las funciones para cargar datos provenientes de catalogos///



//Funciones de Validacion///////////////////

function validamontolleno()
{
	lb_valido=true;
	for(li_i=1;li_i<f.filasfuentes.value;li_i++)
	{
		if((eval("f.txtmonfuefin"+li_i+".value")  == "") || (eval("f.txtmonfuefin"+li_i+".value")  == "0,00"))
		{
			lb_valido=false;
		}
	}	
	return lb_valido;
}

function ue_validamonto(txt)
{
	f=document.form1;
	ld_montolimitecontrato=parseFloat(uf_convertir_monto(f.hidmontocontrato.value))-parseFloat(uf_convertir_monto(f.hidmontototalanticipo.value));
	ld_montocontrato=parseFloat(uf_convertir_monto(f.hidmontocontrato.value));
	ld_montoanticipo=parseFloat(uf_convertir_monto(f.txtmonto.value));
	ld_porcentaje=parseFloat(uf_convertir_monto(f.txtporant.value));
	ld_porcentajelimite=ld_montolimitecontrato*100/ld_montocontrato;
	if(txt.value!="")
	{
		if(txt.id=="txtmonto")
		{
			if(f.monto.value!=txt.value)
			{
				if(ld_montoanticipo<=ld_montolimitecontrato)
				{
					f.txtporant.value=uf_convertir(ld_montoanticipo*100/ld_montocontrato);
					if(f.hidfilasretenciones.value>1)
						ue_actualizarmontototal();
					else
						f.txtmontotant.value=f.txtmonto.value;	
				}
				else
				{
					txt.value="0,00";
					alert("El monto del Anticipo no debe pasar al monto total del contrato menos la sumatoria de los anticipos anteriores!!!");
					txt.focus();
				}
			}			
		}
		else
		{
			if(f.porcentaje.value!=txt.value)
			{
				if(ld_porcentaje<=ld_porcentajelimite)
				{
					f.txtmonto.value=uf_convertir(ld_porcentaje*ld_montocontrato/100);
					if(f.hidfilasretenciones.value>1)
						ue_actualizarmontototal();	
					else
						f.txtmontotant.value=f.txtmonto.value;	
				}
				else
				{
					txt.value="0,00";
					alert("El monto del Anticipo no debe pasar al monto total del contrato menos la sumatoria de los anticipos anteriores!!!");
					txt.focus();
				}
			}			
		}		
	}	
}

function ue_actualizarmontototal()
{
	f=document.form1;
	f.operacion.value="ue_actualizarmontototal";
	f.submit();
}
*/

/*function uf_procesarporcentaje (txt)
{
	f=document.form1;
	ld_montocontrato=parseFloat(uf_convertir_monto(f.txtmonto.value));	
	ld_montomaximo=parseFloat(uf_convertir_monto(f.txtmonmaxcon.value));
	if (ld_montocontrato!=ld_montomaximo || f.txtpormaxcon.value!="0,00")
	{	//7
		if(txt.id=="txtmonmaxcon")
		{//3
			if(ld_montomaximo>0)
			{//4
				if (ld_montomaximo < ld_montocontrato)
				{//5
					alert("El Monto Máximo debe ser mayor o igual al Monto del Contrato!!!");
					txt.value=uf_convertir(ld_montocontrato);
				}//5
				else
				{//6
					/*alert("monto maximo "+ld_montomaximo);
					alert("monto contrato "+ld_montocontrato);
					ld_montoaumento=parseFloat(ld_montomaximo-ld_montocontrato);
					/*alert ("monto aument "+ld_montoaumento);
					alert ("monto contrato "+ld_montocontrato);
					ld_porcentaje=(ld_montoaumento*100/ld_montocontrato);
					f.txtpormaxcon.value=uf_convertir(ld_porcentaje);
				}//6
			}//4
		}//3
		else
		{//2
			ld_porcentaje=uf_convertir_monto(txt.value);
			if (ld_porcentaje>0)//1
			{
				ld_montomaximo=parseFloat(ld_porcentaje*ld_montocontrato/100)+parseFloat(ld_montocontrato);
				f.txtmonmaxcon.value=uf_convertir(ld_montomaximo);		
			}//1
		}//2
	}//end primer if//7
}

function ue_guardarvalor()
{
	f=document.form1;
	f.monto.value=f.txtmonto.value;
	f.porcentaje.value=f.txtporant.value;
}

//////////////////////////////Fin de las funciones de validacion
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		if(f.txtcodcon.value=="")
		{
			alert("Debe seleccionar un Contrato!!!");
		}
		else
		{
			f.operacion.value="ue_nuevo";
			f.action="sigesp_sob_d_anticipo.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}	    	
}
		/*Function:  ue_buscar()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de obras*/  
/*function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		var estado="";
		pagina="sigesp_cat_anticipo.php?estado="+estado;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=350,resizable=yes,location=no,status=no,top=0,left=0");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
} 
/*Fin de la Función ue_buscar()*/

/*Function ue_guardar
	Funcion que se encarga de guardar los datos de la obra, revisando previamente la validez de los datos
*/

/*function ue_guardar()
{
	f=document.form1;		
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(form1)
		{
			if(ue_valida_null(txtcodcon,"Código del Contrato")==false)
			{
			}
			else
			{
				if(ue_valida_null(txtcodant,"Código de Anticipo")==false)
				{				
				}
				else
				{
					if(ue_valida_null(txtfecintant,"Fecha del Anticipo")==false)
					{				
						txtfecintant.focus();
					}
					else
					{
						if(txtmonto.value=="" || parseInt(txtmonto.value)==0)
						{				
							txtmonto.focus();
							alert("El Campo Monto está vacío!!!");
						}
						else
						{
							if(txtporant.value=="" || txtporant.value=="0,00" || txtporant.value=="0,0" || txtporant.value=="0," || txtporant.value=="0")
							{				
								txtporant.focus();
								alert("El campo porcentaje está vacío!!!");
							}
							else
							{
								if(ue_valida_null(txtconant,"Concepto")==false)
								{				
									txtconant.focus();
								}
								else
								{
									f.action="sigesp_sob_d_anticipo.php";
									f.operacion.value="ue_guardar";
									f.submit();
								}
							}
						}
					}
				}
			}		
		}//fin del with	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}	
}///////Fin de la funcion ue_guardar

function ue_eliminar()
{
	var lb_borrar="";		
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
	   if (f.txtcodant.value=="")
	   {
		 alert("No ha seleccionado ningún registro para eliminar !!!");
	   }
		else
		{
			borrar=confirm("¿ Esta seguro de eliminar este registro ?");
			if (borrar==true)
			   { 
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="sigesp_sob_d_anticipo.php";
				 f.submit();
			   }
		}	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}   
}
	
function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!!");
	}
	else
	{
		if (f.hiddatoscontrato.value == "OCULTAR")
		{
			f.hiddatoscontrato.value = "MOSTRAR";
			f.operacion.value="ue_cargarcontrato";
			
		}
		else
		{
			f.hiddatoscontrato.value = "OCULTAR";
			f.operacion.value="";
		}
		f.submit();
	}
}

function uf_mostrar_ocultar_obra()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!!");
	}
	else
	{
		if (f.hiddatosobra.value == "OCULTAR")
		{
			f.hiddatosobra.value = "MOSTRAR";
			f.operacion.value="ue_cargarobra";
			
		}
		else
		{
			f.hiddatosobra.value = "OCULTAR";
			f.operacion.value="";
		}
		f.submit();
	}
}
*/

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>