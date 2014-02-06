<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/grid_param.php");
$grid=new grid_param();
require_once("../../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();

$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$ls_formplan=$arr["formplan"];
$ls_codigo_ingreso=trim($arr["ingreso_p"]);
$ls_formato = str_replace( "-", "",$ls_formplan);
$li_size=strlen($ls_formato);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>

<body>
<?php
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo=$_POST["codigo"];
		$ls_nombre=$_POST["nombre"];
		$li_selected=$_POST["selected"];
		$ls_cuentas_existe=$_POST["cuentas"];
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_nombre="";
		$li_selected=0;
		$ls_cuentas_existe=$_GET["cuentas"];
	}
?>
<form name="form1" method="post" action="">
  <p align="left">
    <input name="operacion" type="hidden" id="operacion"></p>
  <div align="left">
    <p>&nbsp;</p>
    <table width="520" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas </td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13" colspan="5"></td>
      </tr>
      <tr>
        <td width="153" height="22" style="text-align:right"><input name="cuentas" type="hidden" id="cuentas" value="<?php print $ls_cuentas_existe;?>">
        Codigo</td>
        <td width="228" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" size="22" maxlength="20"></td>
        <td width="137" height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="5"><input name="nombre" type="text" id="nombre" size="69"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Aplicar contable a todas <input name="chkscgall" type="checkbox" id="chkscgall" value="Y" style="width:15; height:15"></td>
        <td height="22" colspan="5"><input name="txtcuenta" type="text" id="txtcuenta" size="20" maxlength="20" readonly>
          <a href="javascript:cat_scg();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="45" readonly></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"></td>
        <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a> <a href="javascript:aceptar();"><img src="../../shared/imagebank/tools20/aprobado.gif" width="20" height="20" border="0">Aceptar</a> </div></td>
      </tr>
    </table>
    <div align="center"><br>
      <?php

$title[1]="Todos <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	$title[2]="Cuenta Presupuestaria";   $title[3]="Denominación"; 
$grid1="grid";	
if($ls_operacion=="BUSCAR")
{
	$arr_cuentas=split("-",$ls_cuentas_existe);
	$li_totcuentas=count($arr_cuentas);
	$ls_sql2="";
	for($li_i=0;$li_i<$li_totcuentas;$li_i++)
	{
		$ls_cuenta=substr($arr_cuentas[$li_i],0,$li_size);	
		$ls_sql2=" AND sig_cuenta<> '".$ls_cuenta."' ".$ls_sql2;
	}
	$li_sizeingreso=strlen($ls_codigo_ingreso);
	if(substr($ls_codigo,0,$li_sizeingreso)==$ls_codigo_ingreso)
	{
		$ls_codigo_busqueda=$ls_codigo;
	}
	else
	{
		$ls_codigo_busqueda=$ls_codigo_ingreso;
	}
	$ls_cadena=" SELECT DISTINCT sig_cuenta,denominacion ".
			   " FROM   sigesp_plan_unico_re             ".  
			   " WHERE  sig_cuenta like '".$ls_codigo_busqueda."%' AND denominacion like '%".$ls_nombre."%' ORDER BY sig_cuenta ";

// Modificado por nelson barraez el 16-12-2010 porque el catalogo de recursos y egresos se cometio el error de 
// desagregar las cuentas hasta el ultimo nivel, cuando el catalogo de recursos y egresos solo se utiliza como plantilla para la creacion del plan de cuentas

/*	"   not exists (select * from spi_cuentas where spi_cuentas.spi_cuenta ";
	if($_SESSION["ls_gestor"]=="MYSQLT")
	{
		 $ls_cadena=$ls_cadena." like concat(sigesp_plan_unico_re.sig_cuenta,'%') ";
	 }
	 if($_SESSION["ls_gestor"]=="POSTGRES") 
	 {
		 $ls_cadena=$ls_cadena." like sigesp_plan_unico_re.sig_cuenta||'%' ";
	 }
	 if($_SESSION["ls_gestor"]=="INFORMIX") 
	 {
		 $ls_cadena=$ls_cadena." like sigesp_plan_unico_re.sig_cuenta||'%' ";
	 }

	 $ls_cadena=$ls_cadena.$ls_sql2." ) AND sig_cuenta like '".$ls_codigo_busqueda."%' AND denominacion like '%".$ls_nombre."%' ORDER BY sig_cuenta";	*/
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta===false)
	{
		$io_msg->message($fun->uf_convertirmsg($SQL->message));
	}
	else
	{
		$data=$rs_cta;
		if($row=$SQL->fetch_row($rs_cta))
		{
			$data=$SQL->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("sig_cuenta");
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$cuenta=$data["sig_cuenta"][$z];
					$denominacion=$data["denominacion"][$z];
					$object[$z][1]="<input name=chkcta".$z." type=checkbox id=chkcta".$z." value=1 class=sin-borde onClick=javascript:uf_selected('".$z."');>";
					$object[$z][2]="<input type=text name=txtcuenta".$z." value='".$cuenta."' id=txtcuenta".$z." class=sin-borde readonly style=text-align:center size=20 maxlength=20 >";		
					$object[$z][3]="<input type=text name=txtdencuenta".$z." value='".$denominacion."' id=txtdencuenta".$z." class=sin-borde readonly style=text-align:left size=150 maxlength=254>";
				}				
			}
			else
			{
					$object[1][1]="<input name=chkcta1 type=checkbox id=chkcta1 value=1 onClick=javascript:uf_selected('".$z."');>";
					$object[1][2]="<input type=text name=txtcuenta1 value='' id=txtcuenta1 class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
					$object[1][3]="<input type=text name=txtdencuenta1 value='' id=txtdencuenta1 class=sin-borde readonly style=text-align:center size=50 maxlength=254>";
					$totrow=1;
			}
			$grid->makegrid($totrow,$title,$object,650,'Cuentas Recursos y Egresos',$grid1);
		}
		else
		{
			$io_msg->message("No existen cuentas asociadas");
		}
	}
}
print "</table>";
?>
      </div>
  </div>
  <p align="center">
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
    <input name="selected" type="hidden" id="selected" value="<?php print $li_selected;?>">
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function ue_search()
  {
	  f=document.form1;
	  ls_codigo=f.codigo.value;
	  ls_codigo=ls_codigo.substr(0,1);
	  ls_codigo_ingreso=eval("<?php print $ls_codigo_ingreso;?>");
	  if(ls_codigo!="")
	  {
		  if(ls_codigo==ls_codigo_ingreso)
		  {
			  f.operacion.value="BUSCAR";
			  f.action="sigesp_sel_ctaspi.php";
			  f.submit();
		  }
		  else
		  {
			alert("La cuenta de gasto debe comenzar con "+ls_codigo_ingreso);
		  }
	  }
	  else
	  {
		    f.operacion.value="BUSCAR";
			f.action="sigesp_sel_ctaspi.php";
			f.submit();
	  
	  }
  }
 function uf_selected(li_i)
 {
 	f=document.form1;
	li_total=f.total.value;
	li_selected=f.selected.value;
	if(eval("f.chkcta"+li_i+".checked==true"))
	{
		li_selected=parseInt(li_selected,10)+1;
	}
 	f.selected.value=li_selected;
 }	
  function aceptar()
  {
	  f=document.form1;
	  fop=opener.document.form1;
	  li_total=f.total.value;
	  chkscg=f.chkscgall.value;
	  ls_cuenta_scg=f.txtcuenta.value;
	  if (f.chkall.value=='T')
	     {
		   li_selected = li_total;
		 }
	  else
	     {
		   li_selected = f.selected.value; 
		 }
	  li_sel=0;
	  li_row=fop.lastrow.value;	 

	  for(i=1;(i<=parseInt(li_total,10))&&(li_sel<50);i++)	
	  {
   		if(li_sel<parseInt(li_selected,10))
		{
			if(eval("f.chkcta"+i+".checked==true"))
			{
				li_sel=li_sel+1;
				li_row=parseInt(li_row,10)+1;
				ls_cuenta=eval("f.txtcuenta"+i+".value");
				ls_denominacion=eval("f.txtdencuenta"+i+".value");
				
				eval("fop.txtcuentaspi"+li_row+".value='"+ls_cuenta+"'");
				//eval("fop.txtcuentaspi"+li_row+".readonly=false");
				eval("fop.txtdencuenta"+li_row+".value='"+ls_denominacion+"'");	
				eval("fop.txtdencuenta"+li_row+".readonly=false");	
				if(chkscg=='Y')
				{
					eval("fop.txtcuentascg"+li_row+".value='"+ls_cuenta_scg+"'");
				}				
			}
			fop.lastrow.value=li_row;
			if(li_sel==50)
			{
				alert("Se seleccionaran las primeras 50 cuentas, \n para continuar procese y seleccione el siguiente grupo");
				close();
				break;
			}
		}
		else
		{
			break;
			close();			
		}	
	  }
	  	close();  
	 
 }
 
function uf_select_all()
{
	  f=document.form1;
	  fop=opener.document.form1;
	  total=f.total.value;
	  sel_all=f.chkall.value;
	  li_sel=0;
	  li_row=0;
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			eval("f.chkcta"+i+".checked=true")
			li_sel=li_sel+1;
		  }
		  if(li_sel>50)
		  {
			alert("Se seleccionaran solo 50 cuentas a procesar");
			return ;
		  }
	}
}
function cat_scg()
{
	   pagina="sigesp_cat_ctasscg.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
</script>
</html>