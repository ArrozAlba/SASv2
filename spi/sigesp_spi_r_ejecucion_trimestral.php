<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPI";
	$ls_ventanas="sigesp_spi_r_ejecucion_trimestral.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	$li_estpreing     = $_SESSION["la_empresa"]["estpreing"];
	$li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
	$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"]+10;
	$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"]+10;
	$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"]+10;
	$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"]+10;
	$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"]+10;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>EJECUCION TRIMESTRAL DE INGRESOS Y FUENTES FINANCIERAS</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style></head>
<body>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></td>
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


$la_emp=$_SESSION["la_empresa"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["codestpro1"];
	 $ls_codestpro2 = $_POST["codestpro2"];
	 $ls_codestpro3 = $_POST["codestpro3"];
	 $ls_codestpro4 = $_POST["codestpro4"];
	 $ls_codestpro5 = $_POST["codestpro5"];
	 $ls_codestpro1h = $_POST["codestpro1h"];
	 $ls_codestpro2h = $_POST["codestpro2h"];
	 $ls_codestpro3h = $_POST["codestpro3h"];
	 $ls_codestpro4h = $_POST["codestpro4h"];
	 $ls_codestpro5h = $_POST["codestpro5h"];
	 $ls_estclades   = $_POST["estclades"];
	 $ls_estclahas   = $_POST["estclahas"];
   }
else
   {
	 $ls_operacion="";	
     $ls_codestpro1 = $ls_codestpro1h = "";
	 $ls_codestpro2 = $ls_codestpro2h = "";
	 $ls_codestpro3 = $ls_codestpro3h = "";
	 $ls_codestpro4 = $ls_codestpro4h = "";
	 $ls_codestpro5 = $ls_codestpro5h = "";
	 $ls_estclades  = $ls_estclahas   = "";
   }
if (array_key_exists("cmbmes",$_POST)) 
   {
     $ls_cmbmes=$_POST["cmbmes"];
   }
else
   {
     $ls_cmbmes="s1";
   }
if	(array_key_exists("cmbnivel",$_POST))
	{
	  $ls_cmbnivel=$_POST["cmbnivel"];
    }
else
	{
	  $ls_cmbnivel="s1";
	}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	}
	else
	{
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
<table width="605" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="109"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="3" align="center">EJECUCION  TRIMESTRAL DE INGRESOS Y <br>
FUENTES FINANCIERAS </td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left">&nbsp;</td>
    </tr>
    
    <tr>
      <td height="22" colspan="3" align="left"><table width="550" height="40" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="2"><strong class="titulo-celdanew">Seleccione Trimestre</strong></td>
        </tr>
        <tr>
          <td width="244" height="21" style="text-align:right">Trimestre</td>
          <td width="304" height="21"><select name="cmbmes" id="cmbmes">
            <option value="0103">Enero - Marzo</option>
            <option value="0406">Abril - Junio</option>
            <option value="0709">Julio - Septiembre</option>
            <option value="1012">Octubre - Diciembre</option>
          </select></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left">&nbsp;</td>
    </tr>
    <?php
	  if ($li_estpreing==1)
	     {
	?>
	<tr>
      <td height="22" colspan="3" align="left"><div align="center"><strong><span class="style14">        
        </span></strong>
          <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr class="titulo-celdanew">
              <td colspan="4">Estructura Presupuestaria </td>
            </tr>

            <tr>
              <td width="187" height="22">&nbsp;</td>
              <td width="150" height="22"><strong>Desde</strong></td>
              <td width="11" height="22">&nbsp;</td>
              <td width="200" height="22"><strong>Hasta</strong></td>
            </tr>
            <tr>
              <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro1"] ?></td>
              <td height="22"><label>
                <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php echo $ls_codestpro1; ?>" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro1"]; ?>" size="<?php print $li_loncodestpro1; ?>">
                <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
              <td height="22">&nbsp;</td>
              <td height="22"><label>
                <input name="codestpro1h" type="text" id="codestpro1h" style="text-align:center" value="<?php echo $ls_codestpro1h; ?>" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro1"]; ?>" size="<?php print $li_loncodestpro1; ?>">
                <a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
            </tr>
            <tr>
              <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro2"] ?></td>
              <td height="22"><label>
                <input name="codestpro2" type="text" id="codestpro2" style="text-align:center" value="<?php echo $ls_codestpro2; ?>" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro2"]; ?>" size="<?php print $li_loncodestpro2; ?>">
                <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
              <td height="22">&nbsp;</td>
              <td height="22"><label>
                <input name="codestpro2h" type="text" id="codestpro2h" style="text-align:center" value="<?php echo $ls_codestpro2h; ?>" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro2"]; ?>" size="<?php print $li_loncodestpro2; ?>">
                <a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
            </tr>
            <tr>
              <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro3"] ?></td>
              <td height="22"><label>
                <input name="codestpro3" type="text" id="codestpro3" style="text-align:center" value="<?php echo $ls_codestpro3; ?>" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro3"]; ?>" size="<?php print $li_loncodestpro3; ?>">
                <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
              <td height="22">&nbsp;</td>
              <td height="22"><label>
                <input name="codestpro3h" type="text" id="codestpro3h" style="text-align:center" value="<?php echo $ls_codestpro3h; ?>" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro3"]; ?>" size="<?php print $li_loncodestpro3; ?>">
                <a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
            </tr>
            <?php
			  if ($li_estmodest==2)
			     {				 
			?>
			<tr>
              <td height="22"><span style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro4"] ?></span></td>
              <td height="22"><label>
                <input name="codestpro4" type="text" id="codestpro4" value="<?php echo $ls_codestpro4; ?>" style="text-align:center" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro4"]; ?>" size="<?php print $li_loncodestpro4; ?>">
                <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
              <td height="22">&nbsp;</td>
              <td height="22"><label>
                <input name="codestpro4h" type="text" id="codestpro4h" value="<?php echo $ls_codestpro4h; ?>" style="text-align:center" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro4"]; ?>" size="<?php print $li_loncodestpro4; ?>">
                <a href="javascript:catalogo_estprohas4();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
            </tr>
            <tr>
              <td height="22"><span style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro5"] ?></span></td>
              <td height="22"><label>
                <input name="codestpro5" type="text" id="codestpro5" value="<?php echo $ls_codestpro5; ?>" style="text-align:center" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro5"]; ?>" size="<?php print $li_loncodestpro5; ?>">
                <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
              <td height="22">&nbsp;</td>
              <td height="22"><label>
                <input name="codestpro5h" type="text" id="codestpro5h" value="<?php echo $ls_codestpro5h; ?>" style="text-align:center" readonly maxlength="<?php print $_SESSION["la_empresa"]["loncodestpro5"]; ?>" size="<?php print $li_loncodestpro5; ?>">
                <a href="javascript:catalogo_estprohas5();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a></label></td>
            </tr>
            <?php 
			     }			
			?>
			<tr>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
          </table>
		  <?php
		       }
		  ?>
          </div>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
      <input name="estpreing"   type="hidden"   id="estpreing"   value="<?php print $li_estpreing;?>">
      <input name="estclahas" type="hidden" id="estclahas" value="<?php print $ls_estclahas;?>">
      <input name="estclades" type="hidden" id="estclades" value="<?php print $ls_estclades;?>">
      <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
</span></a></div></td>
    </tr>
  </table>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_showouput()
{
	f=document.form1;
	cmbmes = f.cmbmes.value;
	if (cmbmes=="s1")
       {
	     alert("Por Favor Seleccionar todos los parametros de busqueda");
       }
	else
	   {
	     li_estpreing = f.estpreing.value;
	     if (li_estpreing==1)
		    {
			  ls_codestpro1  = f.codestpro1.value;
			  ls_codestpro2  = f.codestpro2.value;
			  ls_codestpro3  = f.codestpro3.value;
			  ls_codestpro1h = f.codestpro1h.value;
			  ls_codestpro2h = f.codestpro2h.value;
			  ls_codestpro3h = f.codestpro3h.value;
			  estclades      = f.estclades.value;
			  estclahas      = f.estclahas.value;
			  li_estmodest   = f.estmodest.value;
			  if (li_estmodest==2)
			     {
				   ls_codestpro4  = f.codestpro4.value;
				   ls_codestpro5  = f.codestpro5.value;
				   ls_codestpro4h = f.codestpro4h.value;
				   ls_codestpro5h = f.codestpro5h.value;				 
				 }
		      else
			     {
				   ls_codestpro4  = "";
				   ls_codestpro5  = "";
				   ls_codestpro4h = "";
				   ls_codestpro5h = "";
				 }
			  pagina="reportes/sigesp_spi_rpp_ejecucion_trimestral_inst_07.php?cmbmes="+cmbmes+"&codestpro1="+ls_codestpro1+
			         "&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro1h="+ls_codestpro1h+
					 "&codestpro2h="+ls_codestpro2h+"&codestpro3h="+ls_codestpro3h+"&estclades="+estclades+"&estclahas="+estclahas+
					 "&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&codestpro4h="+ls_codestpro4h+"&codestpro5h="+ls_codestpro5h;
			}
		 else
		    {
			  pagina="reportes/sigesp_spi_rpp_ejecucion_trimestral_inst_07.php?cmbmes="+cmbmes;			
			}
         window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
       }
}

function catalogo_estpro1()
{
	   pagina="sigesp_spi_cat_public_estpro1.php?tipo=reporteacumdes";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;	
	estmodest=f.estmodest.value;
	estcla=f.estclades.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumdes"+"&estcla="+estcla+"&tipo=reporteacumdes";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		
		if(codestpro1=='**')
		{
			pagina="sigesp_cat_estpro2.php?tipo=reporteacumdes"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumdes"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}

function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estmodest=f.estmodest.value;
	estcla=f.estclades.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporteacumdes"
			+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla+"&tipo=reporteacumdes";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporteacumdes"
				+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}

function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estcla=f.estclades.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&estcla="+estcla+"&tipo=reporteacumdes";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_spi_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
			+"&tipo=reporteacumdes"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}

function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	codestpro4=f.codestpro4.value;
	codestpro5=f.codestpro5.value;
	estcla=f.estclades.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&estcla="+estcla+"&tipo=reporteacumdes";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_spi_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporteacumdes"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}

function catalogo_estprohas1()
{
	   pagina="sigesp_spi_cat_public_estpro1.php?tipo=reporteacumhas";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estprohas2()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	estmodest=f.estmodest.value;
	estcla=f.estclahas.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumhas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		if(codestpro1=='**')
		{
			pagina="sigesp_cat_estpro2.php?tipo=reporteacumhas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumhas"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}

function catalogo_estprohas3()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	estmodest=f.estmodest.value;
	estcla=f.estclahas.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="sigesp_spi_cat_public_estpro3.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			                                 +"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_estpro3.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_spi_cat_public_estpro3.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}

function catalogo_estprohas4()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	estcla=f.estclahas.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_estpro4.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");

		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_spi_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
			+"&tipo=reporteacumhas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}

function catalogo_estprohas5()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	codestpro4=f.codestpro4h.value;
	codestpro5=f.codestpro5h.value;
	estcla=f.estclahas.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_estpro5.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
			+"&codestpro4="+codestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_spi_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporteacumhas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
</script>
</html>