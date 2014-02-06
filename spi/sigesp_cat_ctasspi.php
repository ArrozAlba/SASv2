<?
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$li_estpreing     = $arr["estpreing"];
$li_estmodest     = $arr["estmodest"];

if ($li_estpreing==1)
   {
	 $li_loncodestpro1 = $arr["loncodestpro1"];
	 $li_loncodestpro2 = $arr["loncodestpro2"];
 	 $li_loncodestpro3 = $arr["loncodestpro3"];
	 $li_loncodestpro4 = $arr["loncodestpro4"];
	 $li_loncodestpro5 = $arr["loncodestpro5"];	
   }

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_codscg	= $_POST["txtcuentascg"]."%";
	$ls_estcla= $_GET["estcla"];
	if ($li_estpreing==1)
	    {
		  $ls_estcla     = $_GET["estcla"];
		  $ls_codestpro1 = $_GET["codestpro1"];
		  $ls_codestpro2 = $_GET["codestpro2"];
		  $ls_codestpro3 = $_GET["codestpro3"];		  
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
		else
		{
			 $ls_estcla     = "";
		  	 $ls_codestpro1 = ""; 
		 	 $ls_codestpro2 = "";
		     $ls_codestpro3 = "";	
			 $ls_codestpro4 = "";
			 $ls_codestpro5 = "";	
		}
	
}
else
{
	$ls_operacion="";
	$ls_codscg="";
	$ls_estcla= $_GET["estcla"];
	if ($li_estpreing==1)
	    {
		  $ls_estcla     = $_GET["estcla"];
		  $ls_codestpro1 = $_GET["codestpro1"];
		  $ls_codestpro2 = $_GET["codestpro2"];
		  $ls_codestpro3 = $_GET["codestpro3"];		  
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
		else
		{
			 $ls_estcla     = "";
		  	 $ls_codestpro1 = ""; 
		 	 $ls_codestpro2 = "";
		     $ls_codestpro3 = "";	
			 $ls_codestpro4 = "";
			 $ls_codestpro5 = "";	
		}
}
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Ingreso </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right" width="135">Codigo</td>
        <td width="122"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td width="341">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?

print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Ingreso</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "<td>Disponible</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{    
     $ls_straux="";
	 $ls_sqlaux="";
	 if ($li_estpreing==1)
	    {
		  $ls_straux = ", spi_cuentas_estructuras, spg_ep5";
		  $ls_codestpro1 = str_pad(trim($ls_codestpro1),25,0,0); 
		  $ls_codestpro2 = str_pad(trim($ls_codestpro2),25,0,0);
		  $ls_codestpro3 = str_pad(trim($ls_codestpro3),25,0,0);	
		  if ($li_estmodest==2)
			 {  
			   $ls_codestpro4 = str_pad(trim($ls_codestpro4),25,0,0);
			   $ls_codestpro5 = str_pad(trim($ls_codestpro5),25,0,0);
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
						 AND TRIM(spi_cuentas.codemp)=TRIM(spi_cuentas_estructuras.codemp)
						 AND spi_cuentas_estructuras.codemp = spg_ep5.codemp
					     AND spi_cuentas_estructuras.codestpro1 = spg_ep5.codestpro1
					     AND spi_cuentas_estructuras.codestpro2 = spg_ep5.codestpro2
					     AND spi_cuentas_estructuras.codestpro3 = spg_ep5.codestpro3
					     AND spi_cuentas_estructuras.codestpro4 = spg_ep5.codestpro4
					     AND spi_cuentas_estructuras.codestpro5 = spg_ep5.codestpro5
						 AND spi_cuentas_estructuras.spi_cuenta = spi_cuentas.spi_cuenta
					     AND spi_cuentas_estructuras.estcla = spg_ep5.estcla";
								
	      $ls_cadena =" SELECT spi_cuentas.*,(spi_cuentas_estructuras.previsto+spi_cuentas.aumento-spi_cuentas.disminucion) as disponible 
				  FROM spi_cuentas $ls_straux
		   		  WHERE spi_cuentas.codemp = '".$as_codemp."' 
				    AND spi_cuentas.spi_cuenta like '".$ls_codigo."' 
					AND spi_cuentas.denominacion like '".$ls_denominacion."' 
					AND spi_cuentas.sc_cuenta like '".$ls_codscg."' $ls_sqlaux 
				  ORDER BY spi_cuentas.spi_cuenta"; 
		}
		else
		{		  
			$ls_cadena =" SELECT *,(previsto+aumento-disminucion) as disponible 
				  FROM spi_cuentas 
		   		  WHERE codemp = '".$as_codemp."' AND spi_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' 
				  ORDER BY spi_cuenta";
		}
    $rs_data = $io_sql->select($ls_cadena);
	if ($rs_data==false)
	   {
	     $msg->message($fun->uf_convertirmsg($io_sql->message));
  	   }
	else
 	   {
		 $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while($row=$io_sql->fetch_row($rs_data))
			       {
				     $cuenta       = $row["spi_cuenta"];
				     $denominacion = $row["denominacion"];
					 $scgcuenta    = $row["sc_cuenta"];
					 $status       = $row["status"];
					 $disponible   = $row["disponible"];
					 if ($status=="S")
					    {
						  print "<tr class=celdas-blancas>";
						  print "<td>".$cuenta."</td>";
						  print "<td  align=left>".$denominacion."</td>";
						  print "<td  align=center>".$scgcuenta."</td>";
						  print "<td  align=center width=119>".number_format($disponible,2,",",".")."</td>";
				     	}
					 else
					    {
						  print "<tr class=celdas-azules>";
						  print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$status');\">".$cuenta."</a></td>";
						  print "<td  align=left>".$denominacion."</td>";
						  print "<td  align=center>".$scgcuenta."</td>";
						  print "<td  align=center>".number_format($disponible,2,",",".")."</td>";				
				  	    }
					 print "</tr>";			
				   }
			  $io_sql->free_result($rs_data);
			  $io_sql->close();
			}
	     else
		    { ?>
	          <script language="javascript">
			  alert("No se encontraron registros !!!");
			  //close();
			  </script>
	          <?php
			}
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
  function aceptar(cuenta,deno,scgcuenta,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	close();
  }

  function ue_search()
  {
	  f=document.form1;     
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspi.php?codestpro1=+<? print $ls_codestpro1?>&codestpro2=+<? print $ls_codestpro2?>+&codestpro3=<? print $ls_codestpro3?>+&codestpro4=<? print $ls_codestpro4?>+&codestpro5=<? print $ls_codestpro5?>+&estcla=<? print $ls_estcla?>";
	 
	  f.submit();
  }	
</script>
</html>