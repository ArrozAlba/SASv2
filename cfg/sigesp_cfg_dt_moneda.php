<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Registro de Procedencias</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14;
	color: #6699CC;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><!--<a href="javascript:ue_nuevo();"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"></a><a href="javascript:ue_eliminar();">--></a><a href="javascript:ue_salir();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
require_once("class_folder/sigesp_cfg_c_monedas.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("../shared/class_folder/class_funciones.php");
$io_funcion = new class_funciones();
$io_moneda = new sigesp_cfg_c_monedas();
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$la_emp          = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_dsest        = new class_datastore(); //Instanciando la clase datastore
$lb_valido       = "";
$io_chkrel       = new sigesp_c_check_relaciones($con);


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	require_once("../shared/class_folder/grid_param.php");	
	$io_grid=new grid_param();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_moneda.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
	//--titulos del Grid
	$title[1]="Fecha";
	$title[2]="Tasa de Cambio Moneda Principal";
	$title[3]="Tasa de Cambio Moneda Secundaria";
	$title[4]="";
	$grid1="grid";	

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
			$la_accesos["leer"]     = $_POST["leer"];
			$la_accesos["incluir"]  = $_POST["incluir"];
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]     = "";
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = "";
		$la_accesos["ejecutar"] = "";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_agregar_lineablnaca(&$object,$totfila)
	{
		$object[$totfila][1]="<input type=text name=txtfecha".$totfila." value='' id=txtfecha".$totfila." class=sin-borde style=text-align:center size=20 readonly>";	
		$object[$totfila][2]="<input type=text name=txttasa1".$totfila." value='' id=txttasa1".$totfila." class=sin-borde style=text-align:center size=20 readonly>";
		$object[$totfila][3]="<input type=text name=txttasa2".$totfila." value='' id=txttasa2".$totfila." class=sin-borde style=text-align:center size=20 readonly>";	
		$object[$totfila][4] ="<a href=javascript:uf_delete_dt('".$totfila."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
	}
//---------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
 function cargar_detalle($as_codigo)
 {
 
 } //----------------------------------------------------------------------------------------------------------------------------------------

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion             = $_POST["operacion"];
	     $ld_fecha	           = $_POST["txtfecha"];
	  $ls_tasacam1             = $_POST["txttascam1"];
	  $ls_tasacam2             = $_POST["txttascam2"];
	    $ls_codigo			   = $_POST["codigo"];
		$ls_denmon             = $_POST["denmon"];
		 $ls_fila              = $_POST["fila"];
   }
else
   {
		if(array_key_exists("codigo",$_GET))
		{
			$ls_codigo=$_GET["codigo"];
		}
		else
		{
			$ls_codigo="";
		}
		if(array_key_exists("denmon",$_GET))
		{
			$ls_denmon=$_GET["denmon"];
		}
		else
		{
			$ls_denmon="";
		}
		$ls_operacion     = "";
		$ld_fecha         = "";	
	 	$ls_estatus       = "N";
		$ls_tasacam1      = "";
	    $ls_tasacam2      = "";
		$ls_fila          = 0;
		
		$totfila=1;
		$io_moneda->uf_buscar_dt_monedas($ls_codigo);
		$li_totrow=$io_moneda->ds_moneda->getRowCount("codmon");
		for ($i=1;$i<=$li_totrow;$i++)
		{
			$ls_fecha=$io_moneda->ds_moneda->data["fecha"][$i];
			$ls_fecha=$io_funcion->uf_convertirfecmostrar($ls_fecha);
			$ls_tascam1=$io_moneda->ds_moneda->data["tascam1"][$i];
			$ls_tascam2=$io_moneda->ds_moneda->data["tascam2"][$i];
			
			$object[$i][1]="<input type=text name=txtfecha".$i." value='".$ls_fecha."' id=txtfecha".$i." class=sin-borde style=text-align:center size=20 readonly>";	
		    $object[$i][2]="<input type=text name=txttasa1".$i." value='".number_format($ls_tascam1,2,",",".")."' id=txttasa1".$i." class=sin-borde style=text-align:center size=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)); readonly>";
		    $object[$i][3]="<input type=text name=txttasa2".$i." value='".number_format($ls_tascam2,2,",",".")."' id=txttasa2".$i." class=sin-borde style=text-align:center size=20  readonly>";
		   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		}//fin del for
		$li_totrow=$li_totrow+1;
		uf_agregar_lineablnaca($object,$li_totrow);
   }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   if ($ls_operacion=="ELIMINAR")
   {
	    $ls_fila= $_POST["fila"]; 
	    $io_moneda->uf_buscar_dt_monedas($ls_codigo);
		$li_totrow=$io_moneda->ds_moneda->getRowCount("codmon");		
		for ($i=1;$i<=$li_totrow;$i++)
		{
			$ls_fecha=$io_moneda->ds_moneda->data["fecha"][$i];
			$ls_fecha=$io_funcion->uf_convertirfecmostrar($ls_fecha);
			$ls_tascam1=$io_moneda->ds_moneda->data["tascam1"][$i];
			$ls_tascam2=$io_moneda->ds_moneda->data["tascam2"][$i];
			
			if ($i==$ls_fila)
			{
				$io_moneda->uf_delete_dt_moneda($ls_codigo,$ls_fecha,$la_seguridad);
			}
			if ($i!=$ls_fila)
			{
				$object[$i][1]="<input type=text name=txtfecha".$i." value='".$ls_fecha."' id=txtfecha".$i." class=sin-borde style=text-align:center size=20 readonly>";	
				$object[$i][2]="<input type=text name=txttasa1".$i." value='".number_format($ls_tascam1,2,",",".")."' id=txttasa1".$i." class=sin-borde style=text-align:center size=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)); readonly>";
				$object[$i][3]="<input type=text name=txttasa2".$i." value='".number_format($ls_tascam2,2,",",".")."' id=txttasa2".$i." class=sin-borde style=text-align:center size=20  readonly>";
			   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			}
		}//fin del for
		$li_totrow=$li_totrow; 
		uf_agregar_lineablnaca($object,$li_totrow);   
   }

if ($ls_operacion=="AGREGAR")
   {
	    $lb_valido = $io_moneda->uf_agregar($ls_codigo,$ld_fecha,$ls_tasacam1,$ls_tasacam2,$la_seguridad);
	    $io_moneda->uf_buscar_dt_monedas($ls_codigo);
		$li_totrow=$io_moneda->ds_moneda->getRowCount("codmon");
		for ($i=1;$i<=$li_totrow;$i++)
		{
			$ls_fecha=$io_moneda->ds_moneda->data["fecha"][$i];
			$ls_fecha=$io_funcion->uf_convertirfecmostrar($ls_fecha);
			$ls_tascam1=$io_moneda->ds_moneda->data["tascam1"][$i];
			$ls_tascam2=$io_moneda->ds_moneda->data["tascam2"][$i];
			
			$object[$i][1]="<input type=text name=txtfecha".$i." value='".$ls_fecha."' id=txtfecha".$i." class=sin-borde style=text-align:center size=20 readonly>";	
		    $object[$i][2]="<input type=text name=txttasa1".$i." value='".number_format($ls_tascam1,2,",",".")."' id=txttasa1".$i." class=sin-borde style=text-align:center size=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)); readonly>";
		    $object[$i][3]="<input type=text name=txttasa2".$i." value='".number_format($ls_tascam2,2,",",".")."' id=txttasa2".$i." class=sin-borde style=text-align:center size=20  readonly>";
		   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		}//fin del for
		$li_totrow=$li_totrow+1;
		uf_agregar_lineablnaca($object,$li_totrow);
	   	   
   }
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="515" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
      <td width="659" height="166"><table width="479"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
	  <tr><td height="22" colspan="2"><input name="txtcodigo" type="text" id="txtcodigo"  class="sin-borde2" size="50" value="<?php print $ls_codigo."   ".$ls_denmon?>"  style="text-align:center" readonly>
        <tr class="titulo-ventana">
          <td height="22" colspan="2" class="titulo-ventana">Detalle  de la Moneda </td>
        </tr>
        <tr>
          <td width="215" height="22"><div align="right">Fecha </div></td>
          <td width="262" height="22"><div align="left" >
              <input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="22" maxlength="10"  onKeyPress="currencyDate(this);"  datepicker="true">
          </div></td>
        </tr>
        <tr>
           <td width="215" height="22"><div align="right">Tasa de Cambio (Moneda Principal)</div> </td>
          <td height="22" align="left"><input name="txttascam1" type="text" id="txttascam1"  size="10"  maxlength="10" 
		                               value="<?php print number_format($ls_tasacam1,2,",",".");?>"  
									   style="text-align:center" onKeyPress="return(currencyFormat(this,'.',',',event))"></td>
        <tr>
           <td width="215" height="22"><div align="right">Tasa de Cambio (Moneda Secundaria) </div></td>         
          <td height="22" align="left"><input name="txttascam2" type="text" id="txttascam2"  size="10"  
		                               value="<?php print number_format($ls_tasacam2,2,",",".");?>"   
									   style="text-align:center" maxlength="10" 
									   onKeyPress="return(currencyFormat(this,'.',',',event))"></td>
        <tr>        </tr>
        <tr>        </tr>
        <tr>        </tr>
        <tr>
          <td height="22" align="right">&nbsp;</td>
          <td height="22" align="right"><a href="javascript:uf_agregar();">Agregar</a></td>
        </tr>
        <tr>
          <td height="22" colspan="2" align="right">
		        <?php $io_grid->makegrid($li_totrow,$title,$object,580,'Detalles de las Monedas',$grid1);?>
                <input name="total" type="hidden" id="total" value="<?php print $totfila?>"></td>
        </tr>
        <tr>
          <td height="22" colspan="2" align="right"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>">
            <input name="operacion" type="hidden" id="operacion">
            <input name="estmoneda" type="hidden" id="estmoneda">
		    <input name="codigo" type="hidden" id="codigo" value="<? print $ls_codigo;?>">
			<input name="denmon" type="hidden" id="denmon" value="<? print $ls_denmon;?>">			
			<input name="fila" type="hidden" id="fila" value="<? print $ls_fila;?>">
		    </td>         
        </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>

<script language="javascript">

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.txtcodigo.readOnly=false;
	   f.txtcodigo.value="";
	   f.txtcodigosistema.value="";
	   f.txtoperacion.value="";
	   f.txtdescripcion.value="";
	   f.txtcodigo.focus(); 
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_guardar()
{
  var resul=""; 
             f = document.form1;
	li_incluir = f.incluir.value;
	li_cambiar = f.cambiar.value;
	lb_status  = f.status.value;
	if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
	   {
		 with (document.form1)
			  {    
					f=document.form1;
					f.operacion.value="GUARDAR";
					f.action="sigesp_cfg_d_moneda.php";
					f.submit(); 
			  }
	   }
	  else
		{
		  alert("No tiene permiso para realizar esta operación");
		}
}


function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}	

function ue_eliminar()
{
var borrar="";

f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigo.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cfg_d_moneda.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminación Cancelada !!!");
			 }
	    }
   }
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_buscar()
{
	f=document.form1;
    li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_cfg_cat_moneda.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }   
}
function ue_salir()
{
	close();
}

function uf_agregar()
{
	 f=document.form1;
	 f.operacion.value="AGREGAR";			
	 pagina="sigesp_cfg_dt_moneda.php";
	 f.submit();
}


function uf_delete_dt(fila)
{
	 f=document.form1;
	 f.fila.value=fila;
	 f.operacion.value="ELIMINAR";			
	 pagina="sigesp_cfg_dt_moneda.php";
	 f.submit();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>