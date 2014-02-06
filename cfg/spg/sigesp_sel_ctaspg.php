<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");
$in              = new sigesp_include();
$con             = $in->uf_conectar();
$dat             = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes();
$grid            = new grid_param();
$io_funcion      = new class_funciones();
$io_sql          = new class_sql($con);
$ds              = new class_datastore();
$arr             = $_SESSION["la_empresa"];
$as_codemp       = $arr["codemp"];
$ls_formplan     = $arr["formplan"];
$ls_codigo_gasto = $arr["gasto_p"];
$ls_formato      = str_replace( "-", "",$ls_formplan);
$li_size         = strlen($ls_formato);

$ls_formatopresupuestario    = trim($arr["formpre"]);
$ls_formatopresupuestario = str_replace( "-", "",$ls_formatopresupuestario);
$li_size_presupuesto   = strlen($ls_formatopresupuestario);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
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
		$ls_operacion      = $_POST["operacion"];
		$ls_codigo         = $_POST["codigo"];
		$ls_nombre         = $_POST["nombre"];
		$ls_codestpro1     = $_POST["txtcodestpro1"];
		$ls_codestpro2     = $_POST["txtcodestpro2"];
		$ls_codestpro3     = $_POST["txtcodestpro3"];
		$ls_codestpro4     = $_POST["txtcodestpro4"];
		$ls_codestpro5     = $_POST["txtcodestpro5"];
		$li_selected       = $_POST["selected"];
		$ls_estcla	       = $_POST["txtestcla"];
		$ls_cuentas_existe = $_POST["cuentas"];
	}
	else
	{
		$ls_operacion      = "";
		$ls_codigo         = "";
		$ls_nombre         = "";
		$li_selected       = 0;
		$ls_codestpro1     = $_GET["txtcodestpro1"];
		$ls_codestpro2     = $_GET["txtcodestpro2"];
		$ls_codestpro3     = $_GET["txtcodestpro3"];
		$ls_codestpro4     = $_GET["txtcodestpro4"];
		$ls_codestpro5     = $_GET["txtcodestpro5"];
		$ls_estcla	       = $_GET["txtestcla"];
		$ls_cuentas_existe = $_GET["cuentas"];
	}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <div align="left">
    <table width="532" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria</td>
      </tr>
      <tr>
        <td height="22" align="right">&nbsp;</td>
        <td height="22" colspan="5">		  <div align="left"></div>
	      <div align="left"></div>	    
	      <div align="left">          </div>	    <div align="left">
        </div></td>
      </tr>
      <tr>
        <td width="153" height="22" align="right"><input name="cuentas" type="hidden" id="cuentas" value="<?php print $ls_cuentas_existe;?>">
        Codigo</td>
        <td width="228" height="22"><div align="left">
            <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td width="149" height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="5"><div align="left">
            <input name="nombre" type="text" id="nombre" size="60">
          </div></td>
      </tr>
      <tr>
        <td height="22" ><div align="right">Aplicar contable a todas <input name="chkscgall" type="checkbox" id="chkscgall" value="Y" style="width:15; height:15"> </div></td>
        <td height="22" colspan="5"><div align="left"><input name="txtcuenta" type="text" id="txtcuenta" size="20" maxlength="20" readonly>
          <a href="javascript:cat_scg();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="45" readonly></div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>">
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2;?>">
        <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>">
        <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>">
        <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5 ?>">
         <input name="txtestcla" type="hidden" id="txtestcla" value="<?php print $ls_estcla ?>" size="2">
         <input name="long_pres" type="hidden" id="long_pres" value="<?php print $li_size_presupuesto ?>" size="2">
		 </td>
        
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
	$ls_sql2=" AND sig_cuenta <> '".$ls_cuenta."' ".$ls_sql2;
}
$ls_codestpro1=str_pad($ls_codestpro1,25,0,0);
$ls_codestpro2=str_pad($ls_codestpro2,25,0,0);
$ls_codestpro3=str_pad($ls_codestpro3,25,0,0);
$ls_codestpro4=str_pad($ls_codestpro4,25,0,0);
$ls_codestpro5=str_pad($ls_codestpro5,25,0,0); 
$ls_cadena="SELECT DISTINCT trim(sig_cuenta) as sig_cuenta,denominacion,".
			"	  (SELECT sc_cuenta".
			"        FROM scg_casa_presu".
			"       WHERE scg_casa_presu.sig_cuenta=sigesp_plan_unico_re.sig_cuenta) as sc_cuenta
			FROM sigesp_plan_unico_re  
			WHERE not exists (select * from spg_cuentas where spg_cuentas.spg_cuenta='".$ls_cuenta."' ";
			if($_SESSION["ls_gestor"]=="MYSQL")
			{
				 $ls_cadena=$ls_cadena." like concat(sigesp_plan_unico_re.sig_cuenta,'%') ";
			 }
			 if($_SESSION["ls_gestor"]=="POSTGRE") 
			 {
				 $ls_cadena=$ls_cadena." like sigesp_plan_unico_re.sig_cuenta||'%' ";
			 }
			 if($_SESSION["ls_gestor"]=="INFORMIX")
			 {
				 $ls_cadena=$ls_cadena." like sigesp_plan_unico_re.sig_cuenta || '%' ";
			 }
			 if($ls_codigo!="")
			 {
				 $ls_cadena=$ls_cadena." AND codestpro1='".$ls_codestpro1."' AND codestpro2='".$ls_codestpro2."' AND codestpro3='".$ls_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND estcla='".$ls_estcla."') AND sig_cuenta like '".$ls_codigo."%' AND denominacion like '%".$ls_nombre."%'";
			 }
			 else
			 {
				 $ls_cadena=$ls_cadena." AND codestpro1='".$ls_codestpro1."' AND codestpro2='".$ls_codestpro2."' AND codestpro3='".$ls_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND estcla='".$ls_estcla."') AND sig_cuenta like '".$ls_codigo_gasto."%' AND denominacion like '%".$ls_nombre."%'";			 	
			 }				 
			 $ls_cadena=$ls_cadena.$ls_sql2." ORDER BY sig_cuenta ";
	$rs_cta=$io_sql->select($ls_cadena); 
	if($rs_cta===false)
	{
		$io_msg->message($io_funcion->uf_convertirmsg($io_sql->message));
	}
	else
	{
			$data=$rs_cta;
			$z=0;
			$totrow=$io_sql->num_rows($rs_cta);

			if($totrow==0)
			{
				$io_msg->message("No existen cuentas asociadas");
			
			}else
			{
				if($totrow>0)
				{
					while($row=$io_sql->fetch_row($rs_cta))
					{
						$z++;
						$cuenta=$row["sig_cuenta"];
						$denominacion=$row["denominacion"];
						$sc_cuenta=$row["sc_cuenta"];
						$object[$z][1]="<input name=chkcta".$z." type=checkbox id=chkcta".$z." value=1 class=sin-borde onClick=javascript:uf_selected('".$z."');>";
						$object[$z][2]="<input type=text name=txtcuenta".$z." value='".$cuenta."' id=txtcuenta".$z." class=sin-borde readonly style=text-align:center size=20 maxlength=20 ><input type=hidden name=txtcontable".$z." value='".$sc_cuenta."' id=txtcontable".$z." class=sin-borde readonly style=text-align:center size=20 maxlength=20 >";		
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
	  ls_codigo_gasto=eval("<?php print $ls_codigo_gasto;?>");
	  if(ls_codigo!="")
	  {
		  if(ls_codigo==ls_codigo_gasto)
		  {
			  f.operacion.value="BUSCAR";
			  f.action="sigesp_sel_ctaspg.php";
			  f.submit();
		  }
		  else
		  {
			alert("La cuenta de gasto debe comenzar con "+ls_codigo_gasto);
		  }
	  }
	  else
	  {
		    f.operacion.value="BUSCAR";
			f.action="sigesp_sel_ctaspg.php";
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
	  fop=opener.document.formulario;
	  li_total=f.total.value;
	  chkscg="";
	  if(f.chkscgall.checked==true)
	  {
	  	chkscg="Y";
	  }
	  ls_cuenta_scg=f.txtcuenta.value;
	  li_selected=f.selected.value;
	  li_sel=0;
	  li_row=fop.lastrow.value;	 
      longitud=f.long_pres.value;
	  for(i=1;(i<=parseInt(li_total,10))&&(li_sel<50);i++)	
	  {
   		if(li_sel<parseInt(li_selected,10))
		{
			if(eval("f.chkcta"+i+".checked==true"))
			{
				li_sel=li_sel+1;
				li_row=parseInt(li_row,10)+1;
				ls_cuenta=eval("f.txtcuenta"+i+".value");
				ls_contable=eval("f.txtcontable"+i+".value");
				ls_cuenta=uf_rellenar_cuenta(ls_cuenta,longitud);
				ls_denominacion=eval("f.txtdencuenta"+i+".value");
				eval("fop.txtcuentaspg"+li_row+".value='"+ls_cuenta+"'");
				eval("fop.txtcuentaspg"+li_row+".readonly=false");
				eval("fop.txtdencuenta"+li_row+".value='"+ls_denominacion+"'");	
				eval("fop.txtcuentascg"+li_row+".value='"+ls_contable+"'");	
//  	  			eval("opener.document.form1.txtcuentascg"+li_row+".value='"+ls_contable+"'");
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
 
function uf_rellenar_cuenta(cadena,longitud)
{
	cadena_ceros=""; 
	lencad=cadena.length; 
	maximo=longitud-lencad; 
	for(j=1;j<=maximo;j++) 
	{ 
		cadena_ceros=cadena_ceros+"0"; 
	} 
	cadena=cadena+cadena_ceros; 
	return cadena;  
}

function uf_select_all()
{
	  f=document.form1;
	  fop=opener.document.formulario;
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
 	        f.selected.value=li_sel;
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