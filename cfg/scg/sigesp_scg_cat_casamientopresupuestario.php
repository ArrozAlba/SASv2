<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Casamiento Presupuestario</title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" align="right"><div align="center">Cat&aacute;logo de Casamiento Contable </div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="122" height="22" align="right">Cuenta</td>
        <td width="238" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
	<p><br>
      <?php
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../class_folder/class_funciones_configuracion.php");
$fun_conf=new class_funciones_configuracion();
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$int_scg    = new class_sigesp_int_scg();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
$li_filauso = $fun_conf->uf_obtenervalor_get("filauso","");

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codigo    = $_POST["codigo"];
   }
else
   {
     $ls_operacion=""; 
   }
$li_fila    = 0;
$ls_filacat = $fun_conf->uf_obtenervalor_get("filacat","");

echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>Cuenta</td>";
echo "<td style=text-align:center width=400>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT trim(sig_cuenta) as sig_cuenta,trim(sc_cuenta) as sc_cuenta,
	                   (SELECT denominacion 
	                      FROM sigesp_plan_unico_re 
	                     WHERE sigesp_plan_unico_re.sig_cuenta=scg_casa_presu.sig_cuenta) as dencta
		          FROM scg_casa_presu
		         WHERE codemp = '".$ls_codemp."'
				   AND sig_cuenta like '".$ls_codigo."%' 
			     ORDER BY sig_cuenta ASC";

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
					   $li_fila++;
					   echo "<tr class=celdas-blancas>";
			           $ls_sigcta = $rs_data->fields["sig_cuenta"];
					   $ls_scgcta = $rs_data->fields["sc_cuenta"];					   
					   $ls_dencta = $rs_data->fields["dencta"];
				       echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_sigcta','$ls_dencta','$ls_scgcta','$li_fila','$ls_filacat');\">".$ls_sigcta."</a></td>";
			           echo "<td style=text-align:left title='".$ls_dencta."' width=400>".$ls_dencta."</td>";
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Casamientos Presupuestarios !!!");   
			  }
		 }  		 
   }
echo "</table>";
?>
</p>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
 i=0;
 function aceptar(cuenta,d,cuenta_contable,fila,filacat)
  { 
      fop=opener.document.form1;
	  li_filauso=opener.document.form1.filauso.value;
	  li_total=opener.document.form1.total.value; 
	  lb_valido=true;
	  li_gridtotrows=eval("opener.document.form1.fila.value"); 
      for(li_j=1; (li_j<=li_gridtotrows)&& lb_valido; li_j++)
		{ 
		    eval("opener.document.form1.fila"+".value='"+li_j+"'");
			ls_codcuegrid=eval("opener.document.form1.txtcuentaspg"+li_j+".value");
			ls_dencuegrid=eval("opener.document.form1.txtdencuenta"+li_j+".value"); 
			if((cuenta==ls_codcuegrid)&&(d==ls_dencuegrid))
			{
				alert("La cuenta: "+" "+ls_dencuegrid+" "+ "ya fue incluida !!!");
				lb_valido=false;
			}
		}
	if (lb_valido)	
	{   
	   li_fila=li_filauso-1;  
	   li=li_total-li_filauso; 
	   fop.filauso.value=li_fila;  
	   fop.fila.value=li;
		if((filacat==0)&&(fila!=50))
		{
			i=i+1; 
			eval("opener.document.form1.txtcuentaspg"+i+".value='"+cuenta+"'");
			eval("opener.document.form1.txtcuentaspg"+i+".readonly=false");
			eval("opener.document.form1.txtdencuenta"+i+".value='"+d+"'");	
			eval("opener.document.form1.txtdencuenta"+i+".readonly=false"); 	
			eval("opener.document.form1.txtcuentascg"+i+".value='"+cuenta_contable+"'");
		}
		else
		{
			eval("opener.document.form1.txtcuentaspg"+fila+".value='"+cuenta+"'");
			eval("opener.document.form1.txtcuentaspg"+fila+".readonly=false");
			eval("opener.document.form1.txtdencuenta"+fila+".value='"+d+"'");	
			eval("opener.document.form1.txtdencuenta"+fila+".readonly=false"); 	
			eval("opener.document.form1.txtcuentascg"+fila+".value='"+cuenta_contable+"'");
		}
		if(fila==50)
		{
			alert("Se seleccionaran las primeras 50 cuentas, \n para continuar procese y seleccione el siguiente grupo");
		}
	 }
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_scg_cat_casamientopresupuestario.php?filacat=<?PHP print $ls_filacat;?>&filauso=<?PHP print $li_filauso;?>";
	  f.submit();
  }
</script>
</html>