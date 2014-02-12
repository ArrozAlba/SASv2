<?php
//session_id('8675309');
session_start();
include("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();

$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$ls_formplan=$arr["formplan"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Presupuestarias</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo=$_POST["codigo"];
		$ls_denominacion=$_POST["nombre"];
		$ls_codscg=$_POST["txtcuenta"];
		$ls_estpro1=$_POST["codestpro1"];
		$ls_estpro2=$_POST["codestpro2"];
		$ls_estpro3=$_POST["codestpro3"];
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_denominacion="";
		$ls_codscg="";
		$ls_estpro1=$_GET["codestpro1"];
		$ls_estpro2=$_GET["codestpro2"];
		$ls_estpro3=$_GET["codestpro3"];
	}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="520" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="520" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right">&nbsp;</td>
        <td colspan="5">		  <div align="left"></div>
	    <div align="left">   	        </div>	    <div align="left">
        </div>	    <div align="left">
        </div></td>
      </tr>
      <tr>
        <td align="right" width="112">Codigo</td>
        <td width="269"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td width="137" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td colspan="5"><input name="txtcuenta" type="text" id="txtcuenta" size="20" maxlength="20">
        <a href="javascript:cat_scg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>        <input name="txtdenominacion" type="text" id="txtdenominacion" size="48"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="codestpro1" type="hidden" id="codestpro1" value="<?php print $ls_estpro1;?>">
        <input name="codestpro2" type="hidden" id="codestpro2" value="<?php print $ls_estpro2;?>">
        <input name="codestpro3" type="hidden" id="codestpro3" value="<?php print $ls_estpro3;?>"></td>
        <td colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a> <a href="javascript:aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" border="0">Aceptar</a> </div></td>
      </tr>
    </table>
	<?php

$title[1]="Check <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	$title[2]="Cuenta Presupuestaria";   $title[3]="Denominación"; $title[4]="Cuenta Contable"; 
$grid1="grid";	
if($ls_operacion=="BUSCAR")
{
        $ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$as_codemp."','SPG','".$ls_logusr."',codestpro1,estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,CONCAT(substr(codintper,1,25),substr(codintper,126,1))) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$as_codemp."'||'SPG'||'".$ls_logusr."'||codestpro1||estcla IN (SELECT distinct codemp||codsis||codusu||substr(codintper,1,25)||substr(codintper,126,1)
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
$ls_cadena ="SELECT * FROM spg_cuentas ".
		    "WHERE codemp = '".$as_codemp."' AND spg_cuenta like '".$ls_codigo."%' AND denominacion like '%".$ls_denominacion."%' AND sc_cuenta like '".$ls_codscg."%' AND codestpro1 like '%".$ls_estpro1."%' AND codestpro2 like '%".$ls_estpro2."%' AND codestpro3 like '%".$ls_estpro3."%' ORDER BY spg_cuenta";
	$rs_cta=$SQL->select($ls_cadena);
	
	if($rs_cta==false)
	{
		$msg->message($fun->uf_convertirmsg($SQL->message));
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
			$totrow=$ds->getRowCount("spg_cuenta");
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$cuenta=$data["spg_cuenta"][$z];
					$denominacion=$data["denominacion"][$z];
					$cuentascg=$data["sc_cuenta"][$z];
					$status=$data["status"][$z];
					if($status=='C')
					{
						$object[$z][1]="<input name=chkcta".$z." type=checkbox id=chkcta".$z." value=1 class=sin-borde><input name=status".$z." type=hidden id=status".$z." value='".$status."'> ";
					}
					else
					{
						$object[$z][1]="<input name=status".$z." type=hidden id=status".$z." value='".$status."'> ";
					}
					$object[$z][2]="<input type=text name=txtcuenta".$z." value='".$cuenta."' id=txtcuenta".$z." class=sin-borde readonly style=text-align:center size=16 maxlength=20 >";		
					$object[$z][3]="<input type=text name=txtdencuenta".$z." value='".$denominacion."' id=txtdencuenta".$z." class=sin-borde readonly style=text-align:left size=50 maxlength=254>";
					$object[$z][4]="<input type=text name=txtcuentascg".$z." value='".$cuentascg."' id=txtcuentascg".$z." class=sin-borde readonly style=text-align:center size=16 maxlength=25>";
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
			print "No se han creado Cuentas de gasto para la programatica seleccionada";
			
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
  //	opener.document.form1.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_ctaspg.php";
	  f.submit();
  }
	
  function aceptar()
  {
	  f=document.form1;
	  fop=opener.document.form1;
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
