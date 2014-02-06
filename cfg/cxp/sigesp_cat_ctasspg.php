<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$ls_loncodestpro3)+1;
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_longestpro4= (25-$ls_loncodestpro4)+1;
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
$li_longestpro5= (25-$ls_loncodestpro5)+1;
$in           = new sigesp_include();
$con          = $in->uf_conectar();
$ds           = new class_datastore();
$io_sql       = new class_sql($con);
$io_funcion   = new class_funciones();
$arr          = $_SESSION["la_empresa"];
$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestaria</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="615" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="6" align="right"><div align="center">Cat&aacute;logo de Cuentas Presupuestaria </div></td>
      </tr>
      <tr>
        <td height="22" align="right"><input name="hidmaestro" type="hidden" id="hidmaestro" value="N">
        <?php
		if (array_key_exists("operacion",$_POST))
		   {
		     $ls_operacion  = $_POST["operacion"];
	  	     $ls_codigo     = $_POST["txtcodigo"];
	         $ls_denominacion=$_POST["txtdenominacion"];
		     $ls_cuenta     = $_POST["txtcuentascg"];		   
		     $ls_codestpro1 = $_POST["txtcodestpro1"];  
		     $ls_codestpro2 = $_POST["txtcodestpro2"];
		     $ls_codestpro3 = $_POST["txtcodestpro3"];
		     $ls_denestpro1 = $_POST["txtdenestpro1"];
		     $ls_denestpro2 = $_POST["txtdenestpro2"];
		     $ls_denestpro3 = $_POST["txtdenestpro3"];
		     $ls_estcla1=$_POST["txtestcla1"];
		     $ls_estcla2=$_POST["txtestcla2"];
		     $ls_estcla3=$_POST["txtestcla3"];
		     $ls_estcla4=$_POST["txtestcla4"];
		     $ls_estcla5=$_POST["txtestcla5"];
		     if ($li_estmodest=='2')
			    {
			      $ls_codestpro4 = $_POST["txtcodestpro4"];
		          $ls_codestpro5 = $_POST["txtcodestpro5"];
				  $ls_denestpro4 = $_POST["txtdenestpro4"];
		          $ls_denestpro5 = $_POST["txtdenestpro5"];
				}
			 else
			    {
		         
		  		  $ls_codestpro4 = $io_funcion->uf_cerosizquierda(0,25);
		  		  $ls_codestpro5 = $io_funcion->uf_cerosizquierda(0,25);
				 
				  $ls_denestpro4 = "";
		          $ls_denestpro5 = "";
				}
		   }
		else
		   {
			 $ls_operacion="";
		     $ls_codigo     = "";
	         $ls_denominacion="";
		     $ls_cuenta     = "";
		     $ls_codestpro1 = "";
		     $ls_codestpro2 = "";
		     $ls_codestpro3 = "";
		     $ls_codestpro4 = "";
		     $ls_codestpro5 = "";
             $ls_denestpro1 = "";
		     $ls_denestpro2 = "";
		     $ls_denestpro3 = "";
		     $ls_denestpro4 = "";
		     $ls_denestpro5 = "";
		     $ls_estcla1="";
		     $ls_estcla2="";
		     $ls_estcla3="";
		     $ls_estcla4="";
		     $ls_estcla5="";
		   }

		?>          <?php print $arr["nomestpro1"];?></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print $ls_loncodestpro1+2 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" style="text-align:center "  value="<?php print $ls_codestpro1;?>" readonly>
        <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Estructuras de Nivel 1" width="15" height="15" border="0"></a>
        <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
        <input name="txtestcla1" type="hidden" id="txtestcla1" value="<?php print $ls_estcla1 ?>" size="2">
        </div></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro2"];?></td>
        <td height="22" colspan="5"><input name="txtcodestpro2" type="text"   id="txtcodestpro2" size="<?php print $ls_loncodestpro2 ?>" maxlength="<?php print $ls_loncodestpro2 ?>" style="text-align:center "  value="<?php print $ls_codestpro2;?>" readonly>
        <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Estructuras de Nivel 2" width="15" height="15" border="0"></a>
        <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
        <input name="txtestcla2" type="hidden" id="txtestcla2" value="<?php print $ls_estcla2 ?>" size="2">
        <input name="hidcodest2" type="hidden" id="hidcodest2">        </td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro3"];?></td>
        <td height="22" colspan="5"><input name="txtcodestpro3" type="text" id="txtcodestpro3" size="<?php print $ls_loncodestpro3+2 ?>" maxlength="<?php print $ls_loncodestpro3 ?>" style="text-align:center "  value="<?php print $ls_codestpro3;?>" readonly>
        <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Estructuras de Nivel 3" width="15" height="15" border="0"></a>
        <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
        <input name="txtestcla3" type="hidden" id="txtestcla3" value="<?php print $ls_estcla3 ?>" size="2">
        <input name="hidcodest3" type="hidden" id="hidcodest3">        </td>
      </tr>
      <?
	   if ($li_estmodest=='2')
	      { ?>
	  <tr>
        <td height="22" align="right"><?php print $arr["nomestpro4"];?></td>
        <td height="22" colspan="5"><input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4 ?>" style="text-align:center " maxlength="<?php print $ls_loncodestpro4 ?>" readonly>
          <a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Estructuras de Nivel 4" width="15" height="15" border="0"></a>
        <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default"></td>
        <input name="txtestcla4" type="hidden" id="txtestcla4" value="<?php print $ls_estcla4 ?>" size="2">
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro5"];?></td>
        <td height="22" colspan="5"><label>
          
          <div align="left">
            <input name="txtcodestpro5" type="text" id="txtcodestpro5" style="text-align:center " value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5+2 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" readonly>
          <a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Estructura de Nivel 5" width="15" height="15" border="0"></a>
          <input name="txtdenestpro5" type="hidden" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
          <input name="txtestcla5" type="hidden" id="txtestcla5" value="<?php print $ls_estcla5 ?>" size="2">
          </div>
        </label>          <label></label></td>
      </tr>		  
	  <?
	  } else 
	  {
	  	?>
	  	  <input name="txtcodestpro4" type="hidden" id="txtcodestpro4"   value="<?php print $ls_codestpro4 ?>" size="10" maxlength="10" readonly>
	  	  <input name="txtdenestpro4" type="hidden"  id="txtdenestpro4" value="<?php print $ls_denestpro5 ?>" size="10" >
	  	  <input name="txtestcla4" type="hidden" id="txtestcla4" value="<?php print $ls_estcla5 ?>" size="2">
	  	  <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" style="text-align:center " value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5+2 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" readonly>
	  	  <input name="txtdenestpro5" type="hidden" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
	  	  <input name="txtestcla5" type="hidden" id="txtestcla5" value="<?php print $ls_estcla5 ?>" size="2">
	  	
	  	<?
	  }
	  ?>
	  
      <tr>
        <td width="135" height="22" align="right">Código</td>
        <td width="403" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo ?>" size="22" maxlength="20" style="text-align:center">        
        </div></td>
        <td height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="5"><div style="text-align:left">
          <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion ?>" size="72" maxlength="254">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" value="<?php print $ls_cuenta ?>" size="22" maxlength="20" style="text-align:center">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
<?php



print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
if ($li_estmodest=='2') 
   {
     print "<td>".$arr["nomestpro4"]."</td>";
     print "<td>".$arr["nomestpro5"]."</td>";
   }
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	if (!empty($ls_codestpro1)&&!empty($ls_codestpro2)&&!empty($ls_codestpro3))
	   {
		 
	   }
	
	$ls_cadena =" SELECT spg_cuenta,denominacion,sc_cuenta,status,estcla,SUBSTR(codestpro1,".$li_longestpro1.",25) as codestpro1,SUBSTR(codestpro2,".$li_longestpro2.",25) as codestpro2,SUBSTR(codestpro3,".$li_longestpro3.",25) as codestpro3,SUBSTR(codestpro4,".$li_longestpro4.",25) as codestpro4,SUBSTR(codestpro5,".$li_longestpro5.",25) as codestpro5 FROM spg_cuentas                                                                        ".
		        "  WHERE codemp = '".$ls_codemp."'                  AND spg_cuenta like '".$ls_codigo."%'      AND ".
				"        denominacion like '%".$ls_denominacion."%' AND sc_cuenta  like '%".$ls_cuenta."%'     AND ".
				"        codestpro1 like '%".$ls_codestpro1."%'     AND codestpro2 like '%".$ls_codestpro2."%' AND ".
				"        codestpro3 like '%".$ls_codestpro3."%'     AND codestpro4 like '%".$ls_codestpro4."%' AND ".
				"        codestpro5 like '%".$ls_codestpro5."%'      AND estcla like '%$ls_estcla3%'                                              ".
				" ORDER BY spg_cuenta 
				                                                                          ";
	$rs_cta=$io_sql->select($ls_cadena);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data     = $io_sql->obtener_datos($rs_cta);
		$arrcols  = array_keys($data);
		$totcol   = count($arrcols);
		$ds->data = $data;
		$totrow   = $ds->getRowCount("spg_cuenta");
		for($z=1;$z<=$totrow;$z++)
		{
			$cuenta        = trim($data["spg_cuenta"][$z]);
			$denominacion  = $data["denominacion"][$z];
			$ls_codestpro1 = trim($data["codestpro1"][$z]);
			$ls_codestpro2 = trim($data["codestpro2"][$z]);
			$ls_codestpro3 = trim($data["codestpro3"][$z]);
			$ls_codestpro4 = trim($data["codestpro4"][$z]);
			$ls_codestpro5 = trim($data["codestpro5"][$z]);
			$ls_estcla     = trim($data["estcla"][$z]);
			$ls_codestp1   = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	   		$ls_codestp2   = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
			$ls_codestp3   = $io_funcion->uf_cerosizquierda($ls_codestpro3,25);
			$ls_codestp4   = $io_funcion->uf_cerosizquierda($ls_codestpro4,25);
			$ls_codestp5   = $io_funcion->uf_cerosizquierda($ls_codestpro5,25);
			$ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
			if ($li_estmodest=='2') 
			   {
			   $ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   }
			$scgcuenta     = trim($data["sc_cuenta"][$z]);
			$status        = $data["status"][$z];
			if($status=="S")
			{
				print "<tr class=celdas-blancas>";
				print "<td  align=center>".$cuenta."</td>";
				print "<td  align=center>".$ls_codestpro1."</td>";
				print "<td  align=center>".$ls_codestpro2."</td>";
			    print "<td  align=center>".$ls_codestpro3."</td>";
			    if ($li_estmodest=='2') 
			       {
                     print "<td  align=center>".$ls_codestpro4."</td>";
			         print "<td  align=center>".$ls_codestpro5."</td>";
				   }
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
				
				
			}
			else
			{ 
				print "<tr class=celdas-azules>";
				print "<td  align=center><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$ls_codestp1','$ls_codestp2','$ls_codestp3','$ls_codestp4','$ls_codestp5','$status','$ls_estcla','$ls_codestpro');\">".$cuenta."</a></td>";
				print "<td  align=center>".$ls_codestpro1."</td>";
				print "<td  align=center>".$ls_codestpro2."</td>";
				print "<td  align=center>".$ls_codestpro3."</td>";
			    if ($li_estmodest=='2') 
			       {
                     print "<td  align=center>".$ls_codestpro4."</td>";
			         print "<td  align=center>".$ls_codestpro5."</td>";
			       }
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
			}
			print "</tr>";			
		}
		$io_sql->free_result($rs_cta);
		$io_sql->close();
	}
	else
		{ ?>
			<script language="javascript">
			alert("No se han creado Cuentas de Gasto para la Estructura Programática seleccionada !!!");
			//close();
		    </script>
  	    <?php
		}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f   = document.form1;
fop = opener.document.form1
function aceptar(cuenta,deno,sccuenta,ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,status,ls_estcla,ls_codestpro)
{
	fop.txtpresupuestaria.value=cuenta;
	fop.txtestcla.value=ls_estcla;
	li_estmodest = <?php print $li_estmodest ?>;
		 fop.txtcodestpro.value=ls_codestpro;
		 fop.txtcodestpro1.value=ls_codestpro1;
		 fop.txtcodestpro2.value=ls_codestpro2;
		 fop.txtcodestpro3.value=ls_codestpro3;
		 fop.txtcodestpro4.value=ls_codestpro4;
		 fop.txtcodestpro5.value=ls_codestpro5;
	close();
}

  function ue_search()
  {
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspg.php";
	  f.submit();
  }

function catalogo_estpro1()
{
	pagina="sigesp_cxp_cat_estpro1.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_estcla1    = f.txtestcla1.value;
	if((ls_codestpro1!="")&&(ls_denestpro1!=""))
	{
		pagina="sigesp_cxp_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtestcla1="+ls_estcla1;
		window.open(pagina,"blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
    else
	{
	  alert("Debe seleccionar una estructura del Nivel 1 !!!");
	}
}
function catalogo_estpro3()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;	
	ls_estcla2     = f.txtestcla2.value;
	if((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3==""))
	{
		pagina="sigesp_cxp_cat_estpro3.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtestcla2="+ls_estcla2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	}
	else
	{
		li_estmodest = "<?php print $li_estmodest ?>";
		if (li_estmodest=='1')
		   {
		     pagina="sigesp_cat_public_estpro.php?submit=si";
		     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	       }
		else
		   {
		     alert("Debe seleccionar una estructura del Nivel 2 !!!");
		   }
	}
}

function catalogo_estpro4()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4==""))
	   {
		 pagina="sigesp_cxp_cat_estpro4.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
 	   }
    else
	   {
	     alert("Debe seleccionar una estructuta del Nivel 3 !!!");
	   }
}

function catalogo_estpro5()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
    ls_codestpro5 = f.txtcodestpro5.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_denestpro4 = f.txtdenestpro4.value;
	ls_denestpro5 = f.txtdenestpro5.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4!="")&&(ls_denestpro4!="")&&(ls_codestpro5==""))
	   {
    	 pagina="sigesp_cxp_cat_estpro5.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
	else
	   {
		 pagina="sigesp_cat_public_estpro.php?submit=no";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
}
</script>
</html>