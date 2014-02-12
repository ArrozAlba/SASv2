<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas del Plan Unico de Recursos y Egresos</title>
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
        <td height="22" colspan="2">Cat&aacute;logo de Cuentas del Plan Unico de Recursos y Egresos</td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="81" height="22" style="text-align:right">Cuenta</td>
        <td width="417" height="22"><input name="codigo" type="text" id="codigo"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22"><div align="left">
          <input name="nombre" type="text" id="nombre" size="70">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<p><br>
<?php
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../class_folder/class_funciones_configuracion.php");
$fun_conf=new class_funciones_configuracion();
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	 $ls_codigo=$_POST["codigo"]."%";
	 $ls_denominacion="%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion="";
   }

$ls_destino=$fun_conf->uf_obtenervalor_get("destino","");
$li_filauso=$fun_conf->uf_obtenervalor_get("filauso","");
if ($ls_destino=="destino")
{
  $li=0;
}
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>Cuenta</td>";
echo "<td style=text-align:center width=400>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	  if (!empty($ls_destino))
	     {
		   $ls_sqlaux = "AND status='C'";
		 }
	  $ls_sql = "SELECT trim(sig_cuenta) as sig_cuenta,denominacion 
	               FROM sigesp_plan_unico_re
		          WHERE sig_cuenta like '".$ls_codigo."' 
				    AND denominacion like '".$ls_denominacion."' $ls_sqlaux
			      ORDER BY sig_cuenta";
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
					   echo "<tr class=celdas-blancas>";
					   $ls_sigcta = $rs_data->fields["sig_cuenta"];
					   $ls_dencta = $rs_data->fields["denominacion"];
					   switch ($ls_destino){
					     case "":
						   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_sigcta','$ls_dencta');\">".$ls_sigcta."</a></td>";
						 break;
						 case "destino":
						   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar2('$ls_sigcta','$ls_dencta','$li_filauso');\">".$ls_sigcta."</a></td>";
						 break;
					   }
				       echo "<td style=text-align:left title='".$ls_dencta."' width=400>".$ls_dencta."</td>";
					   echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas !!!");   
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

  function aceptar(cuenta,d,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=d;
	opener.document.form1.txtcuenta.readOnly=true;
	opener.document.form1.status.value='C';
	 close();
  }

  function aceptar2(cuenta,d,li_filauso)
  {
      f=document.form1;
	  fop=opener.document.form1;
	  li_total=fop.total.value;
	  li_sel=0;
	  li_row=fop.lastrow.value; 
	  lb_valido=true;
	  li_gridtotrows=eval("opener.document.form1.fila.value");
	  for(li_j=1; (li_j<=li_gridtotrows)&& lb_valido; li_j++)
		{  
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
		  for(i=1;(i<=parseInt(li_total,10))&&(li_sel<50);i++)	
		  {  
			li_sel=li_sel+1;
			li_row=parseInt(li_row,10)+1;
			if (li==i)
			{ 
			eval("fop.txtcuentaspg"+li+".value='"+cuenta+"'");
			eval("fop.txtcuentaspg"+li+".readonly=false");
			eval("fop.txtdencuenta"+li+".value='"+d+"'");	
			eval("fop.txtdencuenta"+li+".readonly=false");	
			}
			if(li==50)
			{
				alert("Se seleccionaran las primeras 50 cuentas, \n para continuar procese y seleccione el siguiente grupo");
				//close();
			}
		
		  }
	    }
	  	close(); 
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_scg_cat_ctaspure.php?destino=<?PHP print $ls_destino;?>&filauso=<?PHP print $li_filauso;?>";
	  f.submit();
  }

</script>
</html>