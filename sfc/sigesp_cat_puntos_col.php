<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="cod_pro";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Puntos de Colocaci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
	color: #006699#006699;
}
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?php

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_razcli="%".$_POST["razcli"]."%";
	$ls_codptocol="%".$_POST["codptocol"]."%";
	$ls_codest=$_POST["cmbestado"];
	$ls_codesta="%".$_POST["cmbestado"]."%";
	$ls_codcli=$_POST["codcli"];
	$ls_opener=$_POST["opener"];
}
else
{
	$ls_operacion="";
	$ls_razcli="";
	$ls_codptocol="";
	$ls_codest="";
	$ls_codesta="";
	$ls_codcli=$_GET["codcli"];
	if(array_key_exists("opener",$_GET))
	{
		$ls_opener=$_GET["opener"];
	}
	else
	{
		$ls_opener="";
	}	
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

    <input name="codcli" type="hidden" id="codcli" value="<?php print $ls_codcli;?>">
    <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Puntos de Colocación </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td width="67" height="30"><div align="right">Estado</div></td>
        <td width="431"><div align="left"><span class="style6">
            <select name="cmbestado" size="1" id="cmbestado">
            <option value="">Seleccione...</option>
            <?Php
		     $ls_sql="SELECT codest ,desest
                       FROM sigesp_estados
                      WHERE codpai='058' ORDER BY codest ASC";

			$rs_data=$io_sql->select($ls_sql);
			if($rs_data==false)
			 {
			  
			 }
			 else
			 { 
			 		while($row=$io_sql->fetch_row($rs_data))		 
					{
						 $ls_codigo=$row["codest"];
						 $ls_desest=$row["desest"];
						 if ($ls_codigo==$ls_codest)
						 {
							  print "<option value='$ls_codigo' selected>$ls_desest</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_desest</option>";
						 }
					} 
			}
	        ?>
            </select>
        </span></div></td>
      </tr>
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="codptocol" type="text" id="codptocol"  size="60" maxlength="225">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>

	  <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="razcli" type="text" id="razcli"  size="60" maxlength="225">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php


if($ls_operacion=="BUSCAR")
{
 $ls_cadena=" SELECT sfc_puntocolocacion.*
			    FROM sfc_puntocolocacion
			   WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'
			     AND codcliperptocol='".$ls_codcli."'
			     AND sfc_puntocolocacion.codest ilike '".$ls_codesta."'
			     AND sfc_puntocolocacion.razptocol ilike '".$ls_razcli."'
			     AND sfc_puntocolocacion.codptocol ilike '".$ls_codptocol."' 
			   ORDER BY codptocol";
			$rs_data=$io_sql->select($ls_cadena);
			if($rs_data==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td><font color=#FFFFFF>Cedula</font></a></td>";
				print "<td><font color=#FFFFFF>Razon Social</font></a></td></tr>";
				$li_rows=0;
				while($row=$io_sql->fetch_row($rs_data))
				{
						$li_rows++;
						print "<tr class=celdas-blancas>";
						$codcliperptocol= $row["codcliperptocol"];
						$codptocol      = $row["codptocol"];
		                $nomptocol      = $row["razptocol"];
						$dirptocol      = $row["dirptocol"];
						$refptocol      = $row["ptorefptocol"];
						$telfijptocol   = $row["telfijptocol"];
						$telfaxptocol   = $row["telfaxptocol"];
						$telmovptocol   = $row["telmovptocol"];
						$obsptocol      = $row["obsptocol"];
						$nomconptocol   = $row["nomconptocol"];
						$cedconptocol   = $row["cedconptocol"];
						$emailconptocol = $row["emailconptocol"];
						$telfijconptocol= $row["telfijconptocol"];
						$telmovconptocol= $row["telmovconptocol"];					
						$codpai         = $row["codpai"];
		                $codest         = $row["codest"];
						$codmun         = $row["codmun"];
						$codpar         = $row["codpar"];
						$estatus        = $row["estatus"];
						if($ls_opener=="")
						{
							print "<td><a href=\"javascript: aceptar('$codcliperptocol','$codptocol','$nomptocol','$dirptocol','$refptocol','$codpai','$codest','$codmun','$codpar','$telfijptocol','$telfaxptocol','$telmovptocol','$obsptocol','$nomconptocol','$cedconptocol','$emailconptocol','$telfijconptocol','$telmovconptocol','$estatus');\">".$codptocol ."</a></td>";
						}
						else
						{
							print "<td><a href=\"javascript:ue_aceptar_pto('$codcliperptocol','$codptocol','$nomptocol','$dirptocol','$nomconptocol','$telmovconptocol');\">".$codptocol ."</a></td>";
						}
						print "<td align=left>".$nomptocol."</td>";
						print "</tr>";
						
				}
				if($li_rows==0)
				{
					$io_msg->message("No se han registrado puntos de Colocación para el cliente ");
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
function aceptar(codcliperptocol,codptocol,nomptocol,dirptocol,refptocol,codpai,codest,codmun,codpar,telfijptocol,telfaxptocol,telmovptocol,obsptocol,nomconptocol,cedconptocol,emailconptocol,telfijconptocol,telmovcontpocol,estatus)
{
	f=opener.document.form1;
	f.txtcodptocol.value=codptocol;
	f.txtrazpto.value=nomptocol;
	f.txtdirpto.value=dirptocol;
	f.txtptorefdir.value=refptocol;
	f.codpai.value=codpai;
	f.codest.value=codest;
	f.codmun.value=codmun;
	f.codpar.value=codpar;
	f.txttelffijopto.value=telfijptocol;
	f.txtfaxpto.value=telfaxptocol;
	f.txtmovilpto.value=telmovptocol;
	f.txtobsptocol.value=obsptocol;
	f.txtnompercon.value=nomconptocol;
	f.txtcedpercon.value=cedconptocol;
	f.txtemailcon.value =emailconptocol;
	f.txttelffijcon.value=telfijconptocol;
	f.txttelfmovcon.value=telmovcontpocol;
	f.hidstatus.value=estatus;
	f.operacion.value="";
	f.submit();
	close();
}

function ue_aceptar_pto(codcliperptocol,codptocol,nomptocol,dirptocol,refptocol,telptocol)
{
	f=opener.document.form1;
	f.txtcodptocol.value=codptocol;
	f.txtdenptocol.value=nomptocol;
	f.txtdirptocol.value=dirptocol;
	f.txtpersonacontacto.value=refptocol;
	f.txttelefono.value=telptocol;
	close();	
}

function ue_search()
{
	f=document.form1;
	ls_nombre=f.razcli.value;
	ls_codpto=f.codptocol.value;
	ls_estado=f.cmbestado.value;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cat_puntos_col.php";
    f.submit();	
}

</script>
</html>
