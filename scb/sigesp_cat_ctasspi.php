<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "opener.document.form1.submit();";
	 print "close();";
	 print "</script>";		
   }
$la_empresa		  = $_SESSION["la_empresa"];
$li_estmodest     = $la_empresa["estmodest"];
$li_estpreing     = $la_empresa["estpreing"];
if ($li_estpreing==1)
   {
	 $li_loncodestpro1 = $la_empresa["loncodestpro1"];
	 $li_loncodestpro2 = $la_empresa["loncodestpro2"];
 	 $li_loncodestpro3 = $la_empresa["loncodestpro3"];
	 $li_loncodestpro4 = $la_empresa["loncodestpro4"];
	 $li_loncodestpro5 = $la_empresa["loncodestpro5"];
	
	 $li_size1 = $li_loncodestpro1+10;
	 $li_size2 = $li_loncodestpro2+10;
	 $li_size3 = $li_loncodestpro3+10;
	 $li_size4 = $li_loncodestpro4+10;
	 $li_size5 = $li_loncodestpro5+10;
   }
   
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion  = $_POST["operacion"];
	 $ls_spicta     = $_POST["codigo"];
	 $ls_denspicta  = $_POST["nombre"];
	 $ls_scgcta	    = $_POST["txtcuentascg"];
	 if ($li_estpreing==1)
	    {
		  $ls_codestpro1 = $_POST["codestpro1"];
		  $ls_codestpro2 = $_POST["codestpro2"];
		  $ls_codestpro3 = $_POST["codestpro3"];
		  $ls_denestpro1 = $_POST["txtdenestpro1"];
		  $ls_denestpro2 = $_POST["txtdenestpro2"];
		  $ls_denestpro3 = $_POST["txtdenestpro3"];
		  if (array_key_exists("hicodest4",$_POST))
			 {
			   $ls_codestpro4 = $_POST["hicodest4"];
			   $ls_codestpro5 = $_POST["hicodest5"];
			   $ls_denestpro4 = $_POST["txtdenestpro4"];
			   $ls_denestpro5 = $_POST["txtdenestpro5"];
			 }
		  else
			 {
			   $ls_codestpro4 = "";
			   $ls_codestpro5 = "";
			   $ls_denestpro4 = "";
			   $ls_denestpro5 = "";
			 }
		  $ls_estcla = $_POST["hidestcla"];		
		}
   }
else
   {
	 $ls_operacion  = "";
	 $ls_scgcta     = "";
	 if ($li_estpreing==1)
	    {
		  $ls_estcla     = $_GET["estcla"];
		  $ls_codestpro1 = $_GET["codestpro1"];
		  $ls_codestpro2 = $_GET["codestpro2"];
		  $ls_codestpro3 = $_GET["codestpro3"];
		  $ls_denestpro1 = $_GET["txtdenestpro1"];
		  $ls_denestpro2 = $_GET["txtdenestpro2"];
		  $ls_denestpro3 = $_GET["txtdenestpro3"];
		  if (array_key_exists("hicodest4",$_GET))
			 {  
			   $ls_codestpro4 = $_GET["codestpro4"];
			   $ls_codestpro5 = $_GET["codestpro5"];
			   $ls_denestpro4 = $_GET["txtdenestpro4"];
			   $ls_denestpro5 = $_GET["txtdenestpro5"];
			 }
		  else
			 {
			   $ls_codestpro4 = "";
			   $ls_codestpro5 = "";
			   $ls_denestpro4 = "";
			   $ls_denestpro5 = "";
			 }
		}
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas de Ingreso</title>
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
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center"><input name="operacion" type="hidden" id="operacion">
          Cat&aacute;logo de Cuentas de Ingreso
        </td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <?php
	  if ($li_estpreing==1)
	     {
	  ?>
	  <tr>
        <td height="22" align="right"><span style="text-align:right"><?php print $la_empresa["nomestpro1"];?></span></td>
        <td height="22" colspan="2"><input name="codestpro1" type="text" id="codestpro1" size="<?php print $li_size1 ;?>" maxlength="<?php echo $li_loncodestpro1;?>" style="text-align:center " readonly value="<?php print $ls_codestpro1;?>">
        <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php echo $ls_denestpro1 ?>" size="50" readonly>
        <span style="text-align:center">
        <input name="hidestcla" type="hidden" id="hidestcla" value="<?php echo $ls_estcla ?>">
        </span></td>
      </tr>
      <tr>
        <td height="22" align="right"><span style="text-align:right"><?php print $la_empresa["nomestpro2"];?></span></td>
        <td height="22" colspan="2"><input name="codestpro2" type="text" id="codestpro2" size="<?php echo $li_size2;?>" maxlength="<?php echo $li_loncodestpro2;?>" style="text-align:center " readonly value="<?php print $ls_codestpro2;?>">
        <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php echo $ls_denestpro2 ?>" size="50" readonly></td>
      </tr>
      <tr>
        <td height="22" align="right"><span style="text-align:right"><?php print $la_empresa["nomestpro3"];?></span></td>
        <td height="22" colspan="2"><input name="codestpro3" type="text" id="codestpro3" size="<?php echo $li_size3;?>" maxlength="<?php echo $li_loncodestpro3;?>" style="text-align:center " readonly value="<?php print $ls_codestpro3;?>">
        <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php echo $ls_denestpro3 ?>" size="50" readonly></td>
      </tr>
      <?php
	    if ($li_estmodest==2)
		   {
	  ?>
	  <tr>
        <td height="22" align="right"><span style="text-align:right"><?php echo $la_empresa["nomestpro4"]; ?></span></td>
        <td height="22" colspan="2"><input name="codestpro4" id="codestpro4" type="text" size="<?php echo $li_size4;?>" maxlength="<?php echo $li_loncodestpro4;?>" readonly style="text-align:center" value="<?php print $ls_estpro4;?>">
        <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php echo $ls_denestpro4 ?>" size="50" readonly></td>
      </tr>
      <tr>
        <td height="22" align="right"><span style="text-align:right"><?php echo $la_empresa["nomestpro5"]; ?></span></td>
        <td height="22" colspan="2"><input name="codestpro5" id="codestpro5" type="text" size="<?php echo $li_size5;?>" maxlength="<?php echo $li_loncodestpro5;?>" readonly style="text-align:center" value="<?php print $ls_estpro5;?>">
        <input name="txtdenestpro15" type="text" class="sin-borde" id="txtdenestpro15" value="<?php echo $ls_denestpro5 ?>" size="50" readonly></td>
      </tr>
      <?php
	       }
	  }
	  ?>
	  <tr>
        <td width="135" height="22" align="right">Codigo</td>
        <td width="135" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td width="328" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2" style="text-align:left"><input name="nombre" type="text" id="nombre" size="72"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Cuenta Contable</td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<p><br>
<?php
echo "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=100 style=text-align:center>Cuenta</td>";
echo "<td width=450 style=text-align:center>Denominación</td>";
echo "<td width=100 style=text-align:center>Contable</td>";
echo "<td width=100 style=text-align:center>Disponible</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sqlaux = $ls_straux = "";
	 if ($li_estpreing==1)
	    {
		  $ls_straux = ", spi_cuentas_estructuras, spg_ep5";
		  $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
		  $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
		  $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);	
		  if ($li_estmodest==2)
			 {  
			   $ls_codestpro4 = str_pad($ls_codestpro4,25,0,0);
			   $ls_codestpro5 = str_pad($ls_codestpro5,25,0,0);
			 }		
		  elseif($li_estmodest==1)
		     {
			   $ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
			 }
		  $ls_sqlaux = " AND spi_cuentas_estructuras.codestpro1 = '".$ls_codestpro1."'
				         AND spi_cuentas_estructuras.codestpro2 = '".$ls_codestpro2."'
						 AND spi_cuentas_estructuras.codestpro3 = '".$ls_codestpro3."'
						 AND spi_cuentas_estructuras.codestpro4 = '".$ls_codestpro4."'
						 AND spi_cuentas_estructuras.codestpro5 = '".$ls_codestpro5."'
						 AND spi_cuentas_estructuras.estcla = '".$ls_estcla."'
						 AND spi_cuentas.codemp=spi_cuentas_estructuras.codemp
						 AND spi_cuentas.spi_cuenta=spi_cuentas_estructuras.spi_cuenta
						 AND TRIM(spi_cuentas.codemp)=TRIM(spi_cuentas_estructuras.codemp)
						 AND spi_cuentas_estructuras.codemp = spg_ep5.codemp
					     AND spi_cuentas_estructuras.codestpro1 = spg_ep5.codestpro1
					     AND spi_cuentas_estructuras.codestpro2 = spg_ep5.codestpro2
					     AND spi_cuentas_estructuras.codestpro3 = spg_ep5.codestpro3
					     AND spi_cuentas_estructuras.codestpro4 = spg_ep5.codestpro4
					     AND spi_cuentas_estructuras.codestpro5 = spg_ep5.codestpro5
					     AND spi_cuentas_estructuras.estcla = spg_ep5.estcla";
		}
	 $ls_sql ="SELECT DISTINCT TRIM(spi_cuentas.spi_cuenta) as spi_cuenta,
	                  spi_cuentas.denominacion,spi_cuentas.status,
					  (spi_cuentas.previsto+spi_cuentas.aumento-spi_cuentas.disminucion) as disponible,
	                  TRIM(spi_cuentas.sc_cuenta) as sc_cuenta
				 FROM spi_cuentas $ls_straux
		   		WHERE spi_cuentas.codemp = '".$ls_codemp."' 
				  AND spi_cuentas.spi_cuenta like '".$ls_spicta."%' 
				  AND UPPER(spi_cuentas.denominacion) like '%".strtoupper($ls_denspicta)."%' 
				  AND spi_cuentas.sc_cuenta like '".$ls_scgcta."%' $ls_sqlaux
				ORDER BY spi_cuenta ASC"; //print $ls_sql;

	 $rs_data = $io_sql->select($ls_sql);//echo $ls_sql.'<br>';
	 if ($rs_data===false)
	    {
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		}
     else
	    {
		  $li_totrows = $io_sql->num_rows($rs_data);
		  if ($li_totrows>0)
		     {
			   while(!$rs_data->EOF)
				    {
			          $ls_spicta    = $rs_data->fields["spi_cuenta"];
					  $ls_denspicta = $rs_data->fields["denominacion"];
					  $ls_scgcta    = $rs_data->fields["sc_cuenta"];
					  $ld_totmondis = number_format($rs_data->fields["disponible"],2,',','.');
					  $ls_estspicta = $rs_data->fields["status"];
				      if ($ls_estspicta=='S')
					     {
						   echo "<tr class=celdas-blancas>";
						   echo "<td width=100 style=text-align:center>".$ls_spicta."</td>";
						 }
					  elseif($ls_estspicta=='C')
					     {
						   echo "<tr class=celdas-azules>";
						   echo "<td style=text-align:center><a href=\"javascript: aceptar('$ls_spicta','$ls_denspicta','$ls_scgcta','$ls_estspicta');\">".$ls_spicta."</a></td>";
						 }
					  echo "<td width=450 style=text-align:left title='".$ls_denspicta."'>".$ls_denspicta."</td>";
					  echo "<td width=100 style=text-align:center>".$ls_scgcta."</td>";
					  echo "<td width=100 style=text-align:right>".$ld_totmondis."</td>";				
					  echo "</tr>";
                      $rs_data->MoveNext();
					}
			 }
		  else
		     {
			   $io_msg->message("No se encontraron cuentas Presupuestarias de Ingreso !!!");   
			 }
		}  		 
   }
echo "</table>";
?></p>
	</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(cuenta,deno,scgcuenta,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	opener.document.form1.cuenta_ingreso.value=scgcuenta;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspi.php";
	  f.submit();
  }	
</script>
</html>