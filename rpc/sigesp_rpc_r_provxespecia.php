<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
	require_once("class_folder/class_funciones_rpc.php");
	$io_rpc=new class_funciones_rpc();
	$ls_reporte=$io_rpc->uf_select_config("RPC","REPORTE","LISTADO_PROVEEDORES","sigesp_rpc_rpp_proveedor.php","C");
	unset($io_rpc);

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Proveedores </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a>
	                                                  <a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" title="Excel" width="20" height="20" border="0"></a>
												      <img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20" border="0"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if (array_key_exists("txtcodprov1",$_POST))
   {
     $ls_codprov1=$_POST["txtcodprov1"];	   
   }
else
   {
     $ls_codprov1="";
   }
if (array_key_exists("txtcodprov2",$_POST)) 
   {  
     $ls_codprov2 =$_POST["txtcodprov2"];	  
   }
else
   {
     $ls_codprov2="";
  }
if  (array_key_exists("radioorden",$_POST))
	{
	  $li_orden=$_POST["radioorden"];
    }
else
	{
	  $li_orden="0";
	}
if  (array_key_exists("radiocategoria",$_POST))
	{
	  $ls_tipo=$_POST["radiocategoria"];
    }
else
	{
	  $ls_tipo="P";
	}			
if (array_key_exists("total",$_POST)) 
   {
     $totrow=$_POST["total"];	   
   }
else
   {
     $totrow="";
   }
if (array_key_exists("hidcodesp",$_POST)) 
   {
     $ls_codigoesp=$_POST["hidcodesp"];	   
   }
else
   {
     $ls_codigoesp="";
   } 
if	(array_key_exists("cmbespecialidad",$_POST))
	{
	  $ls_especialidad=$_POST["cmbespecialidad"];
    }
else
	{
	  $ls_especialidad="000";
	}   
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="442" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
  </table>
  <table width="438" height="230" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" align="center" class="titulo-celdanew"><span class="titulo-celdanew">Listado de Proveedores</span></td>
    </tr>
    <tr>
      <td height="50" align="center"><div align="left">
        <table width="414" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
			<tr>
			<td><strong>Categoria
			  <?php 	 
      if (($ls_tipo=="P")||($ls_tipo==""))
	     {
		    $ls_proveedor   ="checked";		
		    $ls_contratista ="";
         }
	   else
	     {
 	       $ls_proveedor   ="";
		   $ls_contratista ="checked";
		 }	
	  ?>
			</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
			</tr>
		  <tr class="formato-blanco">
            <td width="151"><div align="center">Proveedor
                    <input name="radiocategoria" type="radio" value="P"  <?php print $ls_proveedor ?>>
            </div></td>
            <td width="97">&nbsp;</td>
            <td width="164">Contratista
              <input name="radiocategoria" type="radio" value="C" <?php print $ls_contratista ?>></td>
            </tr>
        </table>
      </div>        </td>
    </tr>
    <tr>
      <td height="13" align="center"><div align="right"></div></td>
    </tr>
    <tr>
      <td height="45" align="center">      <div align="left">
        <table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
		    <td colspan="4"><strong>Rango de C&oacute;digos </strong></td>
			</tr>
		  <tr>
            <td width="78"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="134"><input name="txtcodprov1" type="text" id="txtcodprov1" value="<?php print $ls_codprov1 ?>" size="12" maxlength="10"  style="text-align:center "  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov1.name)">
              <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidrango.value=1"></a></td>
            <td width="74"><div align="right"><span class="style1 style14">Hasta</span></div></td>
            <td width="127"><input name="txtcodprov2" type="text" id="txtcodprov2" value="<?php print $ls_codprov2 ?>" size="12" maxlength="10"  style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov2.name)">
              <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"></a></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="13" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">          </div></td>
    </tr>
    <tr>
      <td height="22" align="left"><span class="style14"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Especialidad<?php
		 //Llenar Combo Banco
		 $ls_sql=" SELECT * FROM rpc_especialidad where codesp<>'---' ORDER BY denesp";
		 $rs_especialidad=$io_sql->select($ls_sql);
		 ?>
              <select name="cmbespecialidad" id="cmbespecialidad"  onChange="document.form1.hidcodesp.value=document.form1.cmbespecialidad.value" style="width:150px ">
                <option value="---">---seleccione---</option>
         <?php
		 while ($row=$io_sql->fetch_row($rs_especialidad))
		       {
		         $ls_codesp=$row["codesp"];
		         $ls_denesp=$row["denesp"];
		         if ($ls_codesp==$ls_especialidad)
		            {
		              print "<option value='$ls_codesp'selected>$ls_denesp</option>";
		            }
		         else
		            {
		              print "<option value='$ls_codesp'>$ls_denesp</option>";
		            }
		        } 
		 ?>
              </select>
         <input name="hidcodesp" type="hidden"  id="hidcodesp" value="<?php print $ls_codigoesp ?>">
         <input name="hidrango" type="hidden" id="hidrango">
      </span></div>      
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">      </td>
    </tr>
    <tr>
      <td height="65" align="center"><div align="left">
        <table width="412" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
		    <td colspan="3"><span class="style14"><strong>Ordenado Por</strong></span></td>
			</tr>
		  <tr>
            <td width="93" height="27"><div align="right"><span class="style1">
              <?php 	 
      if (($li_orden=="0")||($li_orden==""))
	     {
		    $ls_codigo   ="checked";		
		    $ls_nombre   ="";
         }
	   else
	     {
 	       $ls_codigo  ="";
		   $ls_nombre  ="checked";
		 }	
	  ?>
              C&oacute;digo
              <input name="radioorden" type="radio" value="0" checked  <?php print $ls_codigo ?>>
              </span></div></td>
            <td width="153">&nbsp;</td>
            <td width="166">Nombre
              <input name="radioorden" type="radio" value="1"  <?php print $ls_nombre ?>></td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_catalogoprov()
{
    f=document.form1;
    pagina="sigesp_catdin_prove.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	   {
		for (i=1;i<=total;i++)
			{
			  cadena_ceros=cadena_ceros+"0";
			}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov1")
		   {
			 document.form1.txtcodprov1.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }  
        }
}

function ue_showouput()
{
	f         = document.form1;
	codprov1  = f.txtcodprov1.value;
	codprov2  = f.txtcodprov2.value;
	codigoesp = f.hidcodesp.value;
	codigoesp= f.cmbespecialidad.value;
	
    if (codprov1<=codprov2)
	   {
			if (codigoesp=='---')
			   {
				  //codigoesp='';
			   }
			if (f.radioorden[0].checked==true)
			   {
				 li_orden = f.radioorden[0].value;
			   }
			else
			   {
				 li_orden = f.radioorden[1].value;
			   }
			if (f.radiocategoria[0].checked==true)
			   {
				 ls_tipo=f.radiocategoria[0].value;
			   }
			else
			   {
				 ls_tipo=f.radiocategoria[1].value;
			   }
			reporte=f.reporte.value;
			pagina="reportes/"+reporte+"?hidorden="+li_orden+"&hidtipo="+ls_tipo+"&hidcodprov1="+codprov1+"&hidcodprov2="+codprov2+"&hidcodesp="+codigoesp;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
    else
	   {
	     alert("Error en Rango de Códigos !!!");
	   }
}

function ue_openexcel()
{
	f         = document.form1;
	codprov1  = f.txtcodprov1.value;
	codprov2  = f.txtcodprov2.value;
	codigoesp = f.hidcodesp.value;
	codigoesp= f.cmbespecialidad.value;
	
    if (codprov1<=codprov2)
	   {
			if (codigoesp=='---')
			   {
				  //codigoesp='';
			   }
			if (f.radioorden[0].checked==true)
			   {
				 li_orden = f.radioorden[0].value;
			   }
			else
			   {
				 li_orden = f.radioorden[1].value;
			   }
			if (f.radiocategoria[0].checked==true)
			   {
				 ls_tipo=f.radiocategoria[0].value;
			   }
			else
			   {
				 ls_tipo=f.radiocategoria[1].value;
			   }
			//reporte=f.reporte.value;
			pagina="reportes/sigesp_rpc_rpp_proveedor_excel.php?hidorden="+li_orden+"&hidtipo="+ls_tipo+"&hidcodprov1="+codprov1+"&hidcodprov2="+codprov2+"&hidcodesp="+codigoesp;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
    else
	   {
	     alert("Error en Rango de Códigos !!!");
	   }	
}
</script>
</html>