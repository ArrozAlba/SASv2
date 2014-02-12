<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");

$in     = new sigesp_include();
$con    = $in->uf_conectar();
$dat    = $_SESSION["la_empresa"];
$io_msg = new class_mensajes();
$grid   = new grid_param();
$fun    = new class_funciones();
$SQL    = new class_sql($con);
$ds     = new class_datastore();

$arr         = $_SESSION["la_empresa"];
$as_codemp   = $arr["codemp"];
$ls_formplan = $arr["formplan"];
require_once("../class_folder/class_funciones_configuracion.php");
$io_fun_cfg=new class_funciones_configuracion();
$ls_destino=$io_fun_cfg->uf_obtenervalor_get("destino","");
$ls_codestpro1=$io_fun_cfg->uf_obtenervalor_get("txtcodestpro1","");
$ls_codestpro2=$io_fun_cfg->uf_obtenervalor_get("txtcodestpro2","");
$ls_codestpro3=$io_fun_cfg->uf_obtenervalor_get("txtcodestpro3","");
$ls_codestpro4=$io_fun_cfg->uf_obtenervalor_get("txtcodestpro4","");
$ls_codestpro5=$io_fun_cfg->uf_obtenervalor_get("txtcodestpro5","");
$ls_estcla=$io_fun_cfg->uf_obtenervalor_get("txtestcla","");
$ls_codestpro1=str_pad($ls_codestpro1,25,0,0);
$ls_codestpro2=str_pad($ls_codestpro2,25,0,0);
$ls_codestpro3=str_pad($ls_codestpro3,25,0,0);
$ls_codestpro4=str_pad($ls_codestpro4,25,0,0);
$ls_codestpro5=str_pad($ls_codestpro5,25,0,0);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas de Ingreso</title>
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
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion    = $_POST["operacion"];
		$ls_codigo       = $_POST["codigo"];
		$ls_denominacion = $_POST["nombre"];
	}
	else
	{
		$ls_operacion    = "";
		$ls_codigo       = "";
		$ls_denominacion = "";
	}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <br>
  <div align="center">
    <table width="543" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" align="right" class="titulo-celda">		  <div align="center">Cat&aacute;logo de Cuentas de Ingreso  </div>
          <div align="left">   	        </div>
          <div align="left">            </div>          <div align="left">
          </div></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="112" height="22" align="right">Codigo</td>
        <td width="269" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="30" maxlength="20" style="text-align:center">        
        </div></td>
        <td width="160" height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"></td>
        <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a> <a href="javascript:aceptar();"><img src="../../shared/imagebank/tools15/aprobado.gif" width="15" height="15" border="0">Aceptar</a> </div></td>
      </tr>
    </table>
<br>	
	<?php

$title[1]="Check <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	
$title[2]="Cuenta de Ingreso";   $title[3]="Denominación";  
$grid1="grid";	
if($ls_operacion=="BUSCAR")
{
if($ls_destino=="destino")
{
    
   $ls_cadena =" SELECT spi_cuentas_estructuras.*,(SELECT spi_cuentas.denominacion from spi_cuentas where spi_cuentas.codemp=spi_cuentas_estructuras.codemp".
            " and spi_cuentas.spi_cuenta=spi_cuentas_estructuras.spi_cuenta and  spi_cuenta like '".$ls_codigo."%'".
			" and denominacion like '%".$ls_denominacion."%') as denominacion,".
			"(SELECT spi_cuentas.status from spi_cuentas where spi_cuentas.codemp=spi_cuentas_estructuras.codemp".
            " and spi_cuentas.spi_cuenta=spi_cuentas_estructuras.spi_cuenta) as status".
			" FROM  spi_cuentas_estructuras ".
		    " WHERE spi_cuentas_estructuras.codemp = '".$as_codemp."'".
			" and spi_cuentas_estructuras.codestpro1='".$ls_codestpro1."'".
			" and spi_cuentas_estructuras.codestpro2='".$ls_codestpro2."'".
			" and spi_cuentas_estructuras.codestpro3='".$ls_codestpro3."'".
			" and spi_cuentas_estructuras.codestpro4='".$ls_codestpro4."'".
			" and spi_cuentas_estructuras.codestpro5='".$ls_codestpro5."'".
			" and spi_cuentas_estructuras.estcla='".$ls_estcla."'".
			" ORDER BY spi_cuenta"; // print $ls_cadena;
}
else
{
       $ls_cadena =" SELECT spi_cuentas_estructuras.*,(SELECT spi_cuentas.denominacion from spi_cuentas where spi_cuentas.codemp=spi_cuentas_estructuras.codemp".
            " and spi_cuentas.spi_cuenta=spi_cuentas_estructuras.spi_cuenta and  spi_cuenta like '".$ls_codigo."%'".
			" and denominacion like '%".$ls_denominacion."%') as denominacion,".
			"(SELECT spi_cuentas.status from spi_cuentas where spi_cuentas.codemp=spi_cuentas_estructuras.codemp".
            " and spi_cuentas.spi_cuenta=spi_cuentas_estructuras.spi_cuenta) as status".
			" FROM  spi_cuentas_estructuras ".
		    " WHERE codemp = '".$as_codemp."'".
			" ORDER BY spi_cuenta";
}			
   
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
			$totrow=$ds->getRowCount("spi_cuenta");
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$cuenta= $data["spi_cuenta"][$z];
					$denominacion= $data["denominacion"][$z];
					$codestpro1= $data["codestpro1"][$z];
					$codestpro2= $data["codestpro2"][$z];
					$codestpro3= $data["codestpro3"][$z];
					$codestpro4= $data["codestpro4"][$z];
					$codestpro5= $data["codestpro5"][$z];
					$estcla= $data["estcla"][$z];
					$status= $data["status"][$z];
					$previsto= $data["previsto"][$z];
					if($status=='C')
					{
						$object[$z][1]="<input name=chkcta".$z." type=checkbox id=chkcta".$z." value=1 class=sin-borde><input name=status".$z." type=hidden id=status".$z." value='".$status."'> ";
					}
					else
					{
						$object[$z][1]="<input name=status".$z." type=hidden id=status".$z." value='".$status."'> ";
					}
					$object[$z][2]="<input type=text name=txtcuenta".$z." value='".$cuenta."' id=txtcuenta".$z." class=sin-borde readonly style=text-align:center size=20 maxlength=20 >";		
					$object[$z][3]="<input type=text name=txtdencuenta".$z." value='".$denominacion."' id=txtdencuenta".$z." class=sin-borde readonly style=text-align:left size=100 maxlength=254>";
				}				
			}
			else
			{
					$object[1][1]="<input name=chkcta1 type=checkbox id=chkcta1 value=1>";
					$object[1][2]="<input type=text name=txtcuenta1 value='' id=txtcuenta1 class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
					$object[1][3]="<input type=text name=txtdencuenta1 value='' id=txtdencuenta1 class=sin-borde readonly style=text-align:center size=100 maxlength=254>";
					$totrow=0;
			}
			$grid->makegrid($totrow,$title,$object,400,'Plan de Cuentas de Ingreso',$grid1);
		}
		else
		{
			$io_msg->message("No se han creado cuentas de Ingreso");
			print "<script language=JavaScript>";
			print " close();";
			print "</script>";
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

  function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	opener.document.form1.status.value='C';
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_spg_cat_ctaspi.php?destino=<?PHP print $ls_destino;?>&txtcodestpro1=<?PHP print $ls_codestpro1;?>&txtcodestpro2=<?PHP print $ls_codestpro2;?>&txtcodestpro3=<?PHP print $ls_codestpro3;?>&&txtcodestpro4=<?PHP print $ls_codestpro4;?>&txtcodestpro5=<?PHP print $ls_codestpro5;?>&txtestcla=<?PHP print $ls_estcla;?>";
	  f.submit();
  }
	
  function aceptar()
  {
	  f=document.form1;
	  fop=opener.document.form1;
	  total=f.total.value;
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
				eval("fop.txtcuentaspi"+li_row+".value='"+ls_cuenta+"'");
				eval("fop.txtcuentaspi"+li_row+".readonly=false");
				eval("fop.txtdencuenta"+li_row+".value='"+ls_denominacion+"'");	
				eval("fop.txtdencuenta"+li_row+".readonly=false");	
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
	  f=document.form1;
	  fop=opener.document.form1;
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