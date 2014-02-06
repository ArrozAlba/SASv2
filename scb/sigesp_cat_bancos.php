<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
  	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Bancos</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="74" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="424" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" maxlength="3" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="70">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
$in     = new sigesp_include();
$con    = $in->uf_conectar();
$io_msg = new class_mensajes();
$io_sql    = new class_sql($con);
$ls_codemp=$arr["codemp"];
$ls_casacon=$arr["casconmov"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	 $ls_codban = $_POST["codigo"];
	 $ls_denban = $_POST["denominacion"];
	 $ls_codcon=$_POST["codcon"];
	 if (array_key_exists("procede",$_GET))
	    {
		  $ls_procede=$_GET["procede"];
	    }
	 else
	    {
		  $ls_procede='';
	    }
	if (array_key_exists("codcon",$_GET))
	    {
		  $ls_codcon=$_GET["codcon"];
	    }
	 else
	    {
		  $ls_codcon='---';
	    }
   }
else
   {
	 $ls_operacion="BUSCAR";
	 if (array_key_exists("codcon",$_GET))
	    {
		  $ls_codcon=$_GET["codcon"];
	    }
	 else
	    {
		  $ls_codcon='---';
	    }
	 $ls_codban = "";
	 $ls_denban = "";	 
	 if (array_key_exists("procede",$_GET))
	    {
		  $ls_procede=$_GET["procede"];
	    }
	 else
	    {
		  $ls_procede='';
	    }
   }

echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>Código</td>";
echo "<td style=text-align:center width=400>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     
	 if (($ls_casacon=="1")&&(trim($ls_codcon!="---")))
	 {
	 	 $ls_sql = "SELECT scb_banco.codban, scb_banco.nomban
					  FROM scb_banco
					 join scb_casamientoconcepto on (scb_casamientoconcepto.codban=scb_banco.codban)
					 WHERE scb_banco.codemp='".$ls_codemp."' 
					   AND scb_banco.codban like '%".$ls_codban."%' 
					   AND UPPER(scb_banco.nomban) like '%".strtoupper($ls_denban)."%' 
					   AND scb_casamientoconcepto.codconmov='".$ls_codcon."'
					 ORDER BY scb_banco.codban ASC"; 
	 }
	 else
	 {
		 $ls_sql = "SELECT codban, nomban
					  FROM scb_banco 
					 WHERE codemp='".$ls_codemp."' 
					   AND codban like '%".$ls_codban."%' 
					   AND UPPER(nomban) like '%".strtoupper($ls_denban)."%' 
					 ORDER BY codban ASC"; 
	 }

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
			           $ls_codban = $rs_data->fields["codban"];
			           $ls_denban = $rs_data->fields["nomban"];
				       if (empty($ls_procede))
				          {
					        echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_codban','$ls_denban');\">".$ls_codban."</a></td>";
				          }
				       else
				          {
					        echo "<td style=text-align:center width=100><a href=\"javascript: aceptar_aut('$ls_codban','$ls_denban');\">".$ls_codban."</a></td>";
				          }
				       echo "<td style=text-align:left title='".$ls_denban."' width=400>".$ls_denban."</td>";
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
					 if (($ls_casacon==1)&&(rtrim($ls_codcon!="---")))
					{
						$io_msg->message("El tipo de concepto seleccionado no posee Bancos asociados !!!");   
					}
					else
					{
						$io_msg->message("No se han definido Bancos !!!");
					}
			  }
		 }  		 
   }
echo "</table>";
?>
</div>
</div>
<input name="codcon" type="hidden" id="codcon" value="<? print $ls_codcon;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(as_codban,as_nomban)
{
	fop = opener.document.form1; 
	ls_opener = opener.document.form1.id;
	if (ls_opener=='sigesp_scb_p_progpago_creditos.php')
	   {
		 li_filsel = fop.hidfilsel.value;
		 ls_denban = as_codban+" - "+as_nomban;
		 eval("fop.txtcodban"+li_filsel+".value="+"'      "+ls_denban+"'");
		 eval("fop.txtcodban"+li_filsel+".title="+"'"+ls_denban+"'");
		 eval("fop.hidcodban"+li_filsel+".value="+"'"+as_codban+"'");
		 eval("fop.hidnomban"+li_filsel+".value="+"'"+as_nomban+"'");
	   }
	else
	   {
		 if (ls_opener=='sigesp_scb_p_liquidacion_creditos.php')
			{
			  fop.txtcodban.value = as_codban;
			  fop.txtnomban.value = as_nomban;
			  fop.txtctaban.value = "";
			  fop.txtdenctaban.value = "";			  
			}
		 else
			{
			  if (ls_opener=='sigesp_scb_p_progpago.php')
			     {
				   lb_valido = uf_evaluate_datos_programacion(as_codban);
				   if (lb_valido)
				      {
						fop.txtcodban.value=as_codban;
					    fop.txtdenban.value=as_nomban;
					    fop.txtcuenta.value="";
					    fop.txtdenominacion.value="";  
					  }
				 }
			  else
			     {
				   fop.txtcodban.value=as_codban;
				   fop.txtdenban.value=as_nomban;
				   fop.txtcuenta.value="";
				   fop.txtdenominacion.value="";			
				 }
			}
	   }
	close();
}

function aceptar_aut(as_codban,as_nomban)
{
  opener.document.form1.codbanaut.value = as_codban;
  opener.document.form1.nombanaut.value = as_nomban;
  close();
}

function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_bancos.php";
  f.submit();
}

function uf_evaluate_datos_programacion(as_codban)
{
  fop       = opener.document.form1;
  li_totrow = fop.totsol.value;
  lb_valido = true;
  for (li_i=1;li_i<=li_totrow;li_i++)
      {
	    if (eval("fop.chksel"+li_i+".checked"))
		   {
			 ls_codban = eval("fop.hidcodban"+li_i+".value");			 
			 if (ls_codban!="")
			    {
				  if (ls_codban!=as_codban)
				     {
					   lb_valido = false;
					   ls_numsol = eval("fop.txtnumsol"+li_i+".value");
					   alert("Solicitud "+ls_numsol+ ", esta asociada a Orden de Pago Ministerio emitida por Banco Distinto !!!");
					 }
				}
		   }
	  }
  return lb_valido;
}
</script>
</html>