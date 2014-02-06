<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Cuentas Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("class_folder/class_funciones_configuracion.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcfg  = new class_funciones_configuracion();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_scgcta    = $_POST["codigo"];
	 $ls_denscgcta = $_POST["nombre"];
     $ls_resultado = $_POST["txtresultado"];
   }
else
   {
	 $ls_operacion="";
	 if (array_key_exists("txtresultado",$_GET))
	    {
	      $ls_resultado = $_GET["txtresultado"];
		}     
	 else
	    {
		  $ls_resultado = "";
		}
   }
$ls_ctacont   = $io_funcfg->uf_obtenervalor_get("ctacont","");
if (array_key_exists("hidtipctares",$_POST))
   {
	 $ls_tipctares = $io_funcfg->uf_obtenervalor("hidtipctares","");//Tipo de la Cuenta Resultado de Consolidacion.   
   }
else
   {
	 $ls_tipctares = $io_funcfg->uf_obtenervalor_get("tipctares","");//Tipo de la Cuenta Resultado de Consolidacion.   
   }
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"></p>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" align="right"><div align="center">Cat&aacute;logo de Cuentas Contables </div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="84" height="22" align="right">Cuenta</td>
        <td width="276" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="40" maxlength="25" style="text-align:center">
          <input name="txtresultado" type="hidden" id="txtresultado" value="<?php print $ls_resultado ?>">
          <input name="hidtipctares" type="hidden" id="hidtipctares" value="<?php echo $ls_tipctares; ?>">
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="65" maxlength="254" style="text-align:left">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
	<br>
<?php
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td  style=text-align:center width=100>Cuenta</td>";
echo "<td  style=text-align:center width=400>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {    
	 $ls_sqlaux = "";
	 if ($ls_tipctares==3 || $ls_tipctares==4)
	    {
		  $ls_sqlaux = " AND status = 'S'";
		}
	 $ls_sql =" SELECT TRIM(sc_cuenta) as sc_cuenta,status,denominacion 
	              FROM scg_cuentas
		         WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'
				   AND sc_cuenta like '".$ls_resultado."%'
				   AND sc_cuenta like '".$ls_scgcta."%'
				   AND UPPER(denominacion) like '%".strtoupper($ls_denscgcta)."%' $ls_sqlaux
				 ORDER BY sc_cuenta ASC";

     $rs_data = $io_sql->select($ls_sql);
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
					  $ls_scgcta    = $rs_data->fields["sc_cuenta"];
					  $ls_denctascg = $rs_data->fields["denominacion"];
					  $ls_estctascg = $rs_data->fields["status"];
					  if ($ls_estctascg=='S')
					     {
					       echo "<tr class=celdas-blancas>";
						   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_scgcta');\">".$ls_scgcta."</a></td>";
						 }
					  elseif($ls_estctascg=='C')
					     {
						   echo "<tr class=celdas-azules>";
						   if ($ls_ctacont==1)
							  {
							    echo "<td style=text-align:center width=100><a href=\"javascript: aceptar2('$ls_scgcta');\">".$ls_scgcta."</a></td>";
							  }
						   else
							  {
							    echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_scgcta');\">".$ls_scgcta."</a></td>";
							  }
						 }
					  echo "<td style=text-align:left width=400 title='".$ls_denctascg."'>".$ls_denctascg."</td>";
					  $rs_data->MoveNext();
					}
             }
          else
		     {
			   $io_msg->message("No se han creado Cuentas Contables !!!");
			 }
        }
   }
echo "</table>";
?>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(cuenta)
  {
    fop    = opener.document.form1;
	objeto = fop.hidresultado.value; 
	if (objeto=='1')
	   {
	     fop.txtresultadoactual.value=cuenta;   
	     fop.txtresultadoactual.readOnly=true;
	   }
	else if (objeto=='2')
	   {
	     fop.txtresultadoanterior.value=cuenta;
	     fop.txtresultadoanterior.readOnly=true;
	   }
	else if (objeto=='3')
	   {
	     fop.txtctaresact.value=cuenta;
	     fop.txtctaresact.readOnly=true;
	   }
	else
	   {
	     fop.txtctaresant.value=cuenta;
	     fop.txtctaresant.readOnly=true;
	   }
    close();
  }
  
  function aceptar2(cuenta)
  {
    opener.document.form1.txtcuenta.value=cuenta;	
    opener.document.form1.txtcuenta.readOnly=true;	
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cfg_cat_scgcuentas.php?ctacont=<?PHP print $ls_ctacont;?>";
	  f.submit();
  }
</script>
</html>