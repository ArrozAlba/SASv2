<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");
$arr          = $_SESSION["la_empresa"];
$as_codemp    = $arr["codemp"];
$ls_formplan  = $arr["formplan"];
$li_estmodest = $arr["estmodest"];
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$ls_loncodestpro3)+1;
if($li_estmodest=='2')
{
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_longestpro4= (25-$ls_loncodestpro4)+1;
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
$li_longestpro5= (25-$ls_loncodestpro5)+1;
}
$in         = new sigesp_include();
$con        = $in->uf_conectar();
$dat        = $_SESSION["la_empresa"];
$io_msg     = new class_mensajes();
$grid       = new grid_param();
$io_funcion = new class_funciones();
$io_sql     = new class_sql($con);
$io_ds      = new class_datastore();



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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
 if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion  = $_POST["operacion"];
		$ls_codestpro  = $_POST["codigo"];
		$ls_denestpro  = $_POST["nombre"];
		$ls_codscg     = $_POST["txtcuenta"];
		$ls_codestpro1 = $_POST["codestpro1"];
		$ls_codestpro2 = $_POST["codestpro2"];
		$ls_codestpro3 = $_POST["codestpro3"];
	    $ls_codestpro4 = $_POST["codestpro4"];
		$ls_codestpro5 = $_POST["codestpro5"];
		$ls_estcla = $_POST["txtestcla"];
	}
 else
	{
		$ls_operacion  = "";
		$ls_codestpro  = "";
		$ls_denestpro  = "";
		$ls_codscg     = "";
		$ls_codestpro1 = $_GET["codestpro1"];
		$ls_codestpro2 = $_GET["codestpro2"];
		$ls_codestpro3 = $_GET["codestpro3"];
		$ls_estcla = $_GET["estcla"];
		if($li_estmodest=='2')
		{
	    $ls_codestpro4 = $_GET["codestpro4"];
		$ls_codestpro5 = $_GET["codestpro5"];
		}
	}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <br>
  <div align="center">
    <table width="539" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria</td>
      </tr>
      <tr>
        <td height="22" align="right">&nbsp;</td>
        <td height="22" colspan="5">		  <div align="left"></div>
	    <div align="left">   	        </div>	    <div align="left">
        </div>	    <div align="left">
        </div></td>
      </tr>
      <tr>
        <td width="108" height="22" align="right">C&oacute;digo</td>
        <td width="273" height="22"><label>
          <input name="codigo" type="text" id="codigo">
        </label></td>
        <td width="156" height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">Denominaci&oacute;n</td>
        <td height="22"><label>
          <input name="nombre" type="text" id="nombre">
        </label></td>
        <td height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="5"><input name="txtcuenta" type="text" id="txtcuenta" size="20" maxlength="20">
        <a href="javascript:cat_scg();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="48"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><input name="codestpro1" type="hidden" id="codestpro1" value="<?php print $ls_codestpro1;?>">
        <input name="codestpro2" type="hidden" id="codestpro2" value="<?php print $ls_codestpro2;?>">
        <input name="codestpro3" type="hidden" id="codestpro3" value="<?php print $ls_codestpro3;?>">
        <input name="codestpro4" type="hidden" id="codestpro4" value="<?php print $ls_codestpro4;?>">
        <input name="codestpro5" type="hidden" id="codestpro5" value="<?php print $ls_codestpro5;?>">
        <input name="txtestcla" type="hidden" id="txtestcla" value="<?php print $ls_estcla; ?>"></td>
        <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a> <a href="javascript:aceptar();"><img src="../../shared/imagebank/tools15/aprobado.gif" width="15" height="15" border="0">Aceptar</a> </div></td>
      </tr>
    </table>
<br>
	<?php

$title[1]="Check <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	$title[2]="Cuenta Presupuestaria";   $title[3]="Denominación"; $title[4]="Cuenta Contable"; 
$grid1="grid";	
if ($ls_operacion=="BUSCAR")
   {
     if (!empty($ls_codestpro1) && !empty($ls_codestpro2)&&!empty($ls_codestpro3))
	    {
	      $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
  	      $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
		  $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,25);
		  $ls_codestpro4 = $io_funcion->uf_cerosizquierda($ls_codestpro4,25);
		  $ls_codestpro5 = $io_funcion->uf_cerosizquierda($ls_codestpro5,25);
		}
	$ls_cadena = "";
	if ($li_estmodest=='2')
	{
		$ls_cadena = " AND codestpro4 = '".$ls_codestpro4."' AND codestpro5 = '".$ls_codestpro5."'";		
	}
	$ls_sql="SELECT spg_cuenta,denominacion,sc_cuenta,status".
	 		"  FROM spg_cuentas".
			" WHERE codemp = '".$as_codemp."'".
			"   AND spg_cuenta like '".$ls_codestpro."%'".
			"   AND denominacion like '%".$ls_denestpro."%'".
			"   AND sc_cuenta like '".$ls_codscg."%'".
			"   AND codestpro1 = '".$ls_codestpro1."'".
			"   AND estcla = '".$ls_estcla."'".
			"   AND codestpro2 = '".$ls_codestpro2."'".
			"   AND codestpro3 = '".$ls_codestpro3."'".
			 $ls_cadena.
			"ORDER BY spg_cuenta	                                                                    ";
	$rs_data = $io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $io_msg->message($io_funcion->uf_convertirmsg($io_sql->message));
	   }
	else
	{
		$data=$rs_data;
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data        = $io_sql->obtener_datos($rs_data);
			$arrcols     = array_keys($data);
			$totcol      = count($arrcols);
			$io_ds->data = $data;
			$totrow      = $io_ds->getRowCount("spg_cuenta");
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$cuenta       = $data["spg_cuenta"][$z];
					$denominacion = $data["denominacion"][$z];
					$cuentascg    = $data["sc_cuenta"][$z];
					$status       = $data["status"][$z];
					if($status=='C')
					{
						$object[$z][1]="<input name=chkcta".$z." type=checkbox id=chkcta".$z." value=1 class=sin-borde><input name=status".$z." type=hidden id=status".$z." value='".$status."'> ";
						$object[$z][4]="<input type=text name=txtcuentascg".$z." value='".$cuentascg."' id=txtcuentascg".$z." class=sin-borde readonly style=text-align:center size=16 maxlength=25>";
					}
					else
					{
						$object[$z][1]="<input name=status".$z." type=hidden id=status".$z." value='".$status."'> ";
						$object[$z][4]="<input type=text name=txtcuentascg".$z." value='' id=txtcuentascg".$z." class=sin-borde readonly style=text-align:center size=16 maxlength=25>";
					}
					$object[$z][2]="<input type=text name=txtcuenta".$z." value='".$cuenta."' id=txtcuenta".$z." class=sin-borde readonly style=text-align:center size=16 maxlength=20 >";		
					$object[$z][3]="<input type=text name=txtdencuenta".$z." value='".$denominacion."' id=txtdencuenta".$z." class=sin-borde readonly style=text-align:left size=50 maxlength=254>";
					
				}				
			}
			else
			{
					$object[1][1]="<input name=chkcta1 type=checkbox id=chkcta1 value=1>";
					$object[1][2]="<input type=text name=txtcuenta1 value='' id=txtcuenta1 class=sin-borde readonly style=text-align:center size=16 maxlength=20>";		
					$object[1][3]="<input type=text name=txtdencuenta1 value='' id=txtdencuenta1 class=sin-borde readonly style=text-align:center size=50 maxlength=254>";
					$object[1][4]="<input type=text name=txtcuentascg1 value='' id=txtcuentascg1 class=sin-borde readonly style=text-align:center size=16 maxlength=25>";
					$totrow=0;
			}
			$grid->makegrid($totrow,$title,$object,400,'Plan de Cuentas de Presupuesto de Gasto',$grid1);
		}
		else
		{
			$io_msg->message("No se han creado cuentas para la programatica seleccionada");
		}
	}
}
print "</table>";
?>
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f   = document.form1;
fop = opener.document.formulario;

function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status)
{
	fop.txtcuenta.value       = cuenta;
	fop.txtdenominacion.value = deno;
	fop.status.value          = 'C';
    close();
}

function ue_search()
{
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_cat_ctaspg.php";
	f.submit();
}
	
function aceptar()
{
  total=f.total.value;
  ls_cuenta_scg=f.txtcuenta.value;
  li_sel=0;
  li_row=0;
  for(i=1;(i<=total)&&(li_sel<50);i++)	
  {
	
	status=eval("f.status"+i+".value")
	if(status=='C')
	{
		if(eval("f.chkcta"+i+".checked==true"))
		{
			li_sel=li_sel+1;
			li_row=li_row+1;
			ls_cuenta=eval("f.txtcuenta"+i+".value");
			ls_denominacion=eval("f.txtdencuenta"+i+".value");
			ls_cuenta_scg=eval("f.txtcuentascg"+i+".value");
			eval("fop.txtcuentaspg"+li_row+".value='"+ls_cuenta+"'");
			eval("fop.txtcuentaspg"+li_row+".readonly=false");
			eval("fop.txtdencuenta"+li_row+".value='"+ls_denominacion+"'");	
			eval("fop.txtdencuenta"+li_row+".readonly=false");	
			eval("fop.txtcuentascg"+li_row+".value='"+ls_cuenta_scg+"'");
		}
		fop.lastrow.value=li_row;
		if(li_sel==50)
		{
			alert("Se seleccionaran las primeras 50 cuentas, \n para continuar procese y seleccione el siguiente grupo");
			close();
			break;
		}
	}	
  }
}
 
function cat_scg()
{
	pagina="sigesp_cat_ctasscg.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function uf_select_all()
{
	  total=f.total.value;
	  sel_all=f.chkall.checked;
	  li_sel=0;
	  li_row=0;
	 	  
	  if(sel_all)
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			status=eval("f.status"+i+".value")
			if(status=='C')
			{
				eval("f.chkcta"+i+".checked=true")
				li_sel=li_sel+1;
		  	}
		  }
		  if(li_sel>50)
		  {
			alert("Se seleccionaran solo 50 cuentas a procesar");
			return ;
		  }
	}
	else
	{
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			status=eval("f.status"+i+".value")
			if(status=='C')
			{
				eval("f.chkcta"+i+".checked=false")
				li_sel=li_sel+1;
		  	}
		  }
	}
}
</script>
</html>