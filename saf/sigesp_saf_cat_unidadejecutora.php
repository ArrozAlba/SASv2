<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidad Ejecutora</title>
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
<style type="text/css">
<!--
.Estilo1 {font-size: 36px}
-->
</style>
</head>

<body>
<br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");

$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
$io_msg=new class_mensajes();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$io_fun=new class_funciones(); 
$la_emp=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion = $_POST["operacion"];
	$ls_destino   = $_POST["destino"];
}
else
{
	$ls_operacion="BUSCAR";
	if(array_key_exists("destino",$_GET))
    {
     $ls_destino=$_GET["destino"];	  
    }
    else
    {
     $ls_destino="";
    }
}

if(array_key_exists("txtcoddep",$_POST))
{
  $ls_coddep=$_POST["txtcoddep"];	  
}
else
{
  $ls_coddep="";
}

if(array_key_exists("txtdendep",$_POST))
{
  $ls_dendep=$_POST["txtdendep"];	  
}
else
{
  $ls_dendep="";
}
?>
<form name="form1" method="post" action="">
  <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="6" class="titulo-celda"><div align="center">Cat&aacute;logo de Unidad Ejecutora </div></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td width="77" height="22"><div align="right">
        <p>N&uacute;mero</p>
      </div></td>
      <td width="157" height="22"><p align="left">
        <input name="txtcoddep" type="text" id="txtcoddep" value="<?php print $ls_coddep ?>" size="17" maxlength="15">
      </p>        </td>
      <td width="82" height="22"><div align="right"></div></td>
      <td width="103" height="22">
      <div align="right"></div></td>
      <td width="23" height="22"><p align="center">&nbsp; </p>          </td>
    </tr>
    <tr>
      <td height="22"  align="right"><div align="right">Denominaci&oacute;n</div></td>
      <td height="22"  align="right"><div align="left">
        <input name="txtdendep" type="text" id="txtdendep" value="<?php print $ls_dendep ?>" size="30" maxlength="60">
      </div></td>
      <td height="22"  align="right">&nbsp;</td>
      <td height="22"  align="right">&nbsp;</td>
      <td height="22"  align="right">&nbsp;</td>
      <td width="6" height="22"  align="right">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"  align="right"><a href="javascript: ue_search();">
        <input name="destino"    type="hidden"  id="destino"    value="<?php print $ls_destino;?>">
        <input name="operacion"    type="hidden"  id="operacion"    value="<?php print $ls_operacion;?>">
      <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()">Buscar Unidad Ejecutora</a></div></td>
    </tr>
    <tr>
      <td colspan="6" align="center">
      <div align="center">      </div></td>
    </tr>
  </table>
  <p align="center">
    <?php
	print "<table width=450 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
    print "<tr class=titulo-celda>";
    print "<td align=center width=100>Código</td>";
    print "<td align=left   width=370>Denominación</td>";
    print "</tr>";
if($ls_operacion=="BUSCAR")
  {     
	$ls_codemp=$la_emp["codemp"];     
	$ls_sql=	"SELECT spg_unidadadministrativa.coduniadm, 
		                count(spg_dt_unidadadministrativa.codestpro1)as items,
                        max(spg_unidadadministrativa.denuniadm) as denuniadm,
						max(spg_dt_unidadadministrativa.codestpro1) as codestpro1, 
						max(spg_dt_unidadadministrativa.codestpro2) as codestpro2,  
						max(spg_dt_unidadadministrativa.codestpro3) as codestpro3,  
						max(spg_dt_unidadadministrativa.codestpro4) as codestpro4,  
						max(spg_dt_unidadadministrativa.codestpro5) as codestpro5, 
						max(spg_dt_unidadadministrativa.estcla) as estcla".
				"  FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep5 ".
				" WHERE spg_unidadadministrativa.codemp='".$ls_codemp."' ".
				"   AND spg_unidadadministrativa.coduniadm <>'----------' ".
				"   AND spg_unidadadministrativa.coduniadm like '%".$ls_coddep."%' ".
				"   AND spg_unidadadministrativa.denuniadm like '%".$ls_dendep."%' ".
				"   AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp ".
				"   AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm ".
				"   AND spg_dt_unidadadministrativa.estcla=spg_ep5.estcla ".
				"   AND spg_dt_unidadadministrativa.codestpro1=spg_ep5.codestpro1 ".
				"   AND spg_dt_unidadadministrativa.codestpro2=spg_ep5.codestpro2 ".
				"   AND spg_dt_unidadadministrativa.codestpro3=spg_ep5.codestpro3 ".
				"   AND spg_dt_unidadadministrativa.codestpro4=spg_ep5.codestpro4 ".
				"   AND spg_dt_unidadadministrativa.codestpro5=spg_ep5.codestpro5 ".
				" GROUP BY spg_unidadadministrativa.codemp, spg_unidadadministrativa.coduniadm".
				" ORDER BY coduniadm ASC ";  
	$rs_data=$io_sql->select($ls_sql);
	//$data=$rs;
	$li_row=$io_sql->num_rows($rs_data);
    if($li_row>0)
	{
		while($row=$io_sql->fetch_row($rs_data))
		{
		  print "<tr class=celdas-blancas>";         
		  $ls_codigo       = trim($row["coduniadm"]);
          $ls_denominacion = trim($row["denuniadm"]);  
		  $ls_codestpro1   = trim($row["codestpro1"]);
          $ls_codestpro2   = trim($row["codestpro2"]); 
		  $ls_codestpro3   = trim($row["codestpro3"]);
          $ls_codestpro4   = trim($row["codestpro4"]);  
          $ls_codestpro5   = trim($row["codestpro5"]);  
		  if($ls_destino == "activo")
		  {
		   print "<td align=center width=100><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_codigo."</a></td>";
		   print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		   print "</tr>";
		  }
		  elseif($ls_destino == "SERVICIO")
		  {
		   print "<td align=center width=100><a href=\"javascript: aceptar_srv('$ls_codigo','$ls_denominacion');\">".$ls_codigo."</a></td>";
		   print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		   print "</tr>";
		  }
		  else
		  {
		   print "<td align=center width=100><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_codigo."</a></td>";
		   print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		   print "</tr>";
		  } 
        }//End For
     print "</table>";
    }//End If
}
?>
</p></form>      
</body>
<script language="JavaScript">
f=document.form1;
   function ue_search()
   {
	  
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_saf_cat_unidadejecutora.php";
	  f.submit();
   }

   function aceptar(ls_codunieje,ls_denunieje,estpro1,estpro2,estpro3,estpro4,estpro5)
   {
	 ls_destino = f.destino.value;
	 if (ls_destino=='receptora')
	    {
		  opener.document.form1.txtcoduni2.value = ls_codunieje;
		  opener.document.form1.txtdenuni2.value = ls_denunieje;		
		}
	 else if (ls_destino=='solicitante')
	    {
		  opener.document.form1.txtcodunisol.value = ls_codunieje;
		  opener.document.form1.txtdenunisol.value = ls_denunieje;		
		}
	 else
	    {
		  opener.document.form1.txtcoduniadm.value = ls_codunieje;
		  opener.document.form1.txtdenuniadm.value = ls_denunieje;		
		}
     close(); 
   }
   
   function aceptar_srv(codigo,denominacion)
   {
	 opener.document.form1.txtcoduni.value=codigo;
     opener.document.form1.txtdenuni.value=denominacion;	
     close(); 
   }
</script>
</html>