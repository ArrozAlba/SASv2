<?php
session_start();
require_once("class_funciones_activos.php");
$fun_activos=new class_funciones_activos();				
$li_row=$fun_activos->uf_obtenervalor_get("row","");
if($li_row=="")
{
	$li_row=$fun_activos->uf_obtenervalor("hidrow","");
}
$operacion=$fun_activos->uf_obteneroperacion();
$ls_destino=$fun_activos->uf_obtenervalor_get("destino","");
$ls_codact=$fun_activos->uf_obtenervalor_get("codact","");
$ls_idact=$fun_activos->uf_obtenervalor_get("idact","");
$ls_tiporesponsable=$fun_activos->uf_obtenervalor_get("tiporesponsable","");

	//-----------------------------------------------------------------------------------------------------------------------------------
	// Función que obtiene e imprime los resultados de la busqueda
	function uf_imprimirresultados($as_codact,$as_idact,$as_tiporesponsable,$as_destino)
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
		switch ($as_tiporesponsable)
		{
			case "uso":
				$ls_sql="SELECT DISTINCT(codres) AS codres,".
						"	    (SELECT nomper".
						" 		   FROM sno_personal".
						"		  WHERE sno_personal.codemp=saf_dta.codemp".
						"		    AND sno_personal.codper=saf_dta.codres) AS nomres1,".
						"	    (SELECT nombene".
						" 		   FROM rpc_beneficiario".
						"		  WHERE rpc_beneficiario.codemp=saf_dta.codemp".
						"		    AND rpc_beneficiario.ced_bene=saf_dta.codres) AS nomres2,".
						"	    (SELECT apeper".
						" 		   FROM sno_personal".
						"		  WHERE sno_personal.codemp=saf_dta.codemp".
						"		    AND sno_personal.codper=saf_dta.codres) AS aperes1,".
						"	    (SELECT apebene".
						" 		   FROM rpc_beneficiario".
						"		  WHERE rpc_beneficiario.codemp=saf_dta.codemp".
						"		    AND rpc_beneficiario.ced_bene=saf_dta.codres) AS aperes2".
						"  FROM saf_dta".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codact='".$as_codact."'";	
						
			break;
			case "primario":
				$ls_sql="SELECT DISTINCT(codrespri) AS codres,".
						"	    (SELECT nomper".
						" 		   FROM sno_personal".
						"		  WHERE sno_personal.codemp=saf_dta.codemp".
						"		    AND sno_personal.codper=saf_dta.codrespri) AS nomres1,".
						"	    (SELECT nombene".
						" 		   FROM rpc_beneficiario".
						"		  WHERE rpc_beneficiario.codemp=saf_dta.codemp".
						"		    AND rpc_beneficiario.ced_bene=saf_dta.codrespri) AS nomres2,".
						"	    (SELECT apeper".
						" 		   FROM sno_personal".
						"		  WHERE sno_personal.codemp=saf_dta.codemp".
						"		    AND sno_personal.codper=saf_dta.codrespri) AS aperes1,".
						"	    (SELECT apebene".
						" 		   FROM rpc_beneficiario".
						"		  WHERE rpc_beneficiario.codemp=saf_dta.codemp".
						"		    AND rpc_beneficiario.ced_bene=saf_dta.codrespri) AS aperes2".
						"  FROM saf_dta".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codact='".$as_codact."'";	
			break;
		}
		$rs_data=$io_sql->select($ls_sql);
		$li_num=$io_sql->num_rows($rs_data);
		if($li_num>0)
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codres=$row["codres"];
				$ls_nomres1=$row["nomres1"];
				if ($ls_nomres1=="")
				{
					$ls_nomres=$row["nomres2"]." ".$row["aperes2"]; 
				}
				else
				{
					$ls_nomres=$row["nomres1"]." ".$row["aperes1"]; 
				}
				
				if($ls_codres!="")
				{
					print "<tr class=celdas-blancas>";					
					print "<td><a href=\"javascript: aceptar('$ls_codres','$ls_nomres');\">".$ls_codres."</a></td>";
					print "<td>".$ls_codres."</td>";
					print "<td>".$ls_nomres."</td>";
					print "</tr>";			
								
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
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Responsables por Activo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td width="76"><div align="right">Activo</div></td>
        <td width="415"><div align="left">
          <input name="txtcodact" type="text" id="txtcodact" style="text-align:center" value="<?php print $ls_codact; ?>" size="30" readonly>        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Identificador</div></td>
        <td><input name="txtidact" type="text" id="txtidact" style="text-align:center" value="<?php print $ls_idact; ?>" size="30" readonly></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left">
          <input name="hidrow" type="hidden" id="hidrow" value="<?php print $li_row; ?>">
          <input name="destino" type="hidden" id="destino" value="<?php print  $ls_destino; ?>">
        </div></td>
      </tr>
  </table>
  <br>
<?php
	uf_imprimirresultados($ls_codact,$ls_idact,$ls_tiporesponsable,$ls_destino);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function aceptar(codres,nomres)
{
	opener.document.form1.txtcodres.value=codres;
	opener.document.form1.txtnomres.value=nomres;
	close();
}

function aceptar_primario(codres,nomres)
{
	opener.document.form1.txtcodresnew.value=codres;
	opener.document.form1.txtnomresnew.value=nomres;
	close();
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_saf_cat_activoresponsable.php";
  	f.submit();
}
</script>
</html>
