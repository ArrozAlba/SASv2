<?php
session_start();
require_once("class_funciones_activos.php");
$fun_activos=new class_funciones_activos();				
$li_row=$fun_activos->uf_obtenervalor_get("row","");
if ($li_row=="")
   {
	 $li_row=$fun_activos->uf_obtenervalor("hidrow","");
   }
$operacion  = $fun_activos->uf_obteneroperacion();
$ls_destino = $fun_activos->uf_obtenervalor_get("destino","");
if($operacion=="BUSCAR")
{$ls_destino=$fun_activos->uf_obtenervalor("destino","");}

	//-----------------------------------------------------------------------------------------------------------------------------------
	// Función que obtiene e imprime los resultados de la busqueda
	function uf_imprimirresultados($as_codper,$as_cedper,$as_nomper,$as_apeper,$ls_destino)
   	{
		require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$msg=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		$ds=new class_datastore();
   		require_once("../shared/class_folder/class_funciones.php");
		$fun=new class_funciones();				
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=100>Cédula</td>";
		print "<td width=440>Nombre y Apellido</td>";
		print "</tr>";
		$ls_sql="SELECT codper,cedper,nomper,apeper".
		        "  FROM sno_personal".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper like '".$as_codper."'".
				"   AND cedper like '".$as_cedper."'".
				"   AND nomper like '".$as_nomper."'".
				"   AND apeper like '".$as_apeper."'".
				" ORDER BY cedper  ";
		$rs_per=$io_sql->select($ls_sql);
		$li_num=$io_sql->num_rows($rs_per);
		if($li_num>0)
		{
			while($row=$io_sql->fetch_row($rs_per))
			{
				print "<tr class=celdas-blancas>";
				$ls_codper = $row["codper"];
				$ls_cedper = $row["cedper"];
				$ls_nomper = $row["nomper"];
				$ls_apeper = $row["apeper"];
				switch ($ls_destino)
				{
					case "":
						print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_cedper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;

					case "repasignadospri":
						print "<td><a href=\"javascript: ue_primariorep('$ls_codper','$ls_cedper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;

					case "repasignadosuso":
						print "<td><a href=\"javascript: ue_usorep('$ls_codper','$ls_cedper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;

					case "responsableactual":
						print "<td><a href=\"javascript: ue_responsableactual('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;
					case "responsablenuevo":
						print "<td><a href=\"javascript: ue_responsablenuevo('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;
					
					case "responsable":
						print "<td><a href=\"javascript: ue_responsable('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;
					
					case "receptor":
						print "<td><a href=\"javascript: ue_receptor('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;
					
					case "despachador":
						print "<td><a href=\"javascript: ue_despachador('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "</tr>";			
					break;

				}				
			}
		}
		print "</table>";
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Personal</title>
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
  <br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Personal</td>
      </tr>
      <tr>
        <td width="67" height="13">&nbsp;</td>
        <td width="431" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&eacute;dula</div></td>
        <td height="22"><input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td height="22"><div align="left">
          <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="left">
          <input name="hidrow"  type="hidden" id="hidrow"  value="<?php print $li_row; ?>">
          <input name="destino" type="hidden" id="destino" value="<?php print  $ls_destino; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
    <div align="center"><br>
      <?php
	$ls_operacion=$fun_activos->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_imprimirresultados($ls_codper,$ls_cedper,$ls_nomper,$ls_apeper,$ls_destino);
	}
?>
      </div>
    </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function aceptar(codper,cedper,nomper,apeper)
{
	f=document.form1;
	li_row=f.hidrow.value;
	obj=eval("opener.document.form1.txtcodres"+li_row+"");
	obj.value=codper;
	obj=eval("opener.document.form1.txtnomres"+li_row+"");
	obj.value=nomper+" "+apeper;
	close();
}

function ue_primariorep(ls_codper,ls_cedper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcodrespri.value=ls_codper;
	opener.document.form1.txtdenrespri.value=ls_nomper+" "+ls_apeper;
	close();
}

function ue_usorep(ls_codper,ls_cedper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcodresuso.value=ls_codper;
	opener.document.form1.txtdenresuso.value=ls_nomper+" "+ls_apeper;
	close();
}
function ue_responsableactual(ls_codper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcodresact.value=ls_codper;
	opener.document.form1.txtnomresact.value=ls_nomper+" "+ls_apeper;
	close();
}
function ue_responsablenuevo(ls_codper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcodresnew.value=ls_codper;
	opener.document.form1.txtnomresnew.value=ls_nomper+" "+ls_apeper;
	close();
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_saf_cat_personal.php";
  	f.submit();
}

function ue_responsable(ls_codper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcodres.value=ls_codper;
	opener.document.form1.txtnomres.value=ls_nomper+" "+ls_apeper;
	close();
}
function ue_receptor(ls_codper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcodrec.value=ls_codper;
	opener.document.form1.txtnomrec.value=ls_nomper+" "+ls_apeper;
	close();
}
function ue_despachador(ls_codper,ls_nomper,ls_apeper)
{
	opener.document.form1.txtcoddes.value=ls_codper;
	opener.document.form1.txtnomdes.value=ls_nomper+" "+ls_apeper;
	close();
}
</script>
</html>
