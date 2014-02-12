<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Control Numerico</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celdanew">Cat&aacute;logo de Control Numerico</td>
    </tr>
</table>
  <br>
<?php
require_once("class_folder/sigesp_cfg_c_ctrl_numero.php");
$io_ctrl_numero=new sigesp_cfg_c_ctrl_numero();
require_once("../shared/class_folder/sigesp_include.php");
$io_conect=new sigesp_include();
$con=$io_conect->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$io_dsprocedencia=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$la_emp=$_SESSION["la_empresa"];
    if (array_key_exists("txtcodusu",$_GET))
    {
    	$ls_codusu=$_GET["txtcodusu"];
    }
    else 
    {
    	$ls_codusu="";
    }
	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
		 $ls_codusu   =$_POST["txtcodusu"];
		 $ls_codigo   ="%".$_POST["txtcodigo"]."%";
	   }
	else
	   {
		 $ls_operacion="";
	   }
?>
<form name="form1" method="post" action="">
<table width="498" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="88" height="15" align="right">&nbsp;</td>
        <td width="149">&nbsp;        </td>
        <td width="159" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" align="right">C&oacute;digo</td>
        <td><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center"  maxlength="3">
        <input name="txtcodusu" type="hidden" id="txtcodusu" style="text-align:center"   value="<?print $ls_codusu?>">
        <input name="operacion" type="hidden" id="operacion"></td>
        <td align="right"><div align="left"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()">Buscar</a></div></td>
      <tr>
        <td height="18" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
  </table> 
</form>      
<div align="center">
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código</td>";
print "<td>Sistema</td>";
print "<td>Procede</td>";
print "<td>Numero Actual</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_sql= " SELECT codsis,procede,id,prefijo,nro_inicial,nro_final,maxlen,nro_actual,estcompscg  ".
		         " FROM sigesp_ctrl_numero ".
		        // " WHERE id like '".$ls_codigo."' AND codusu='".$ls_codusu."' order by id asc";
				 " WHERE id like '".$ls_codigo."' ".
				 "group by codsis,procede,id,prefijo,nro_inicial,nro_final,maxlen,nro_actual,estcompscg ".
				 "order by id asc"; 
		$rs_ctrl_numero=$io_sql->select($ls_sql);
		$data=$rs_ctrl_numero;
    	if ($row=$io_sql->fetch_row($rs_ctrl_numero))
		   {
			 $data=$io_sql->obtener_datos($rs_ctrl_numero);
			 $arrcols=array_keys($data);
			 $totcol=count($arrcols);
			 $io_dsprocedencia->data=$data;
			 $totrow=$io_dsprocedencia->getRowCount("procede");
			 for ($z=1;$z<=$totrow;$z++)
				 {
			  	   print "<tr class=celdas-blancas>";
			  	   $ls_codsis=$data["codsis"][$z];
				   $ls_procede=$data["procede"][$z];				   
		           $ls_codigo=$data["id"][$z];
		           $ls_prefijo=$data["prefijo"][$z];
		           $ls_numini=$data["nro_inicial"][$z];
		           $ls_numfin=$data["nro_final"][$z];
		           $ls_maxlen=$data["maxlen"][$z];
				   $ls_numact=$data["nro_actual"][$z];
				   $ls_estcompscg=$data["estcompscg"][$z];
				   print "<td><a href=\"javascript: aceptar('$ls_codsis','$ls_procede','$ls_codigo','$ls_prefijo','$ls_numini','$ls_numfin','$ls_maxlen','$ls_numact','$ls_estcompscg');\">".$ls_codigo."</a></td>";
				   print "<td  align=center>".$ls_codsis."</td>";
				   print "<td  align=center>".$ls_procede."</td>";
				   print "<td  align=center>".$ls_numact."</td>";
				   print "</tr>";			
			     }
		   print "</table>";
		   $io_sql->free_result($rs_ctrl_numero);
		   }
		else
		 { ?>
		   <script  language="javascript">
		   alert("No se han creado Numeros de Control !!!");
		   </script>
		 <?php
		 }  
}
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(sistema,procede,codigo,prefijo, numini,numfin,maxlen,numact,ls_estcompscg)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodsis.value=procede; 
	opener.document.form1.txtprefi.value=prefijo; 
	
	opener.document.form1.txtnumini.value=numini;
	opener.document.form1.txtnumfin.value=numfin;
	opener.document.form1.txtlong.value=maxlen;
	opener.document.form1.txtnumact.value=numact;
	
	opener.document.form1.status.value='C';
	opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtprefi.readOnly=true;
	opener.document.form1.operacion.value = "buscar"; 
	if (ls_estcompscg=="1")
    { 
	  opener.document.form1.chkestinicero.checked=true;
	  opener.document.form1.chkestinicero.disabled=false;
    }
	if(procede!='SCGCMP')
	{ 
	  opener.document.form1.chkestinicero.checked=false; 
	  opener.document.form1.chkestinicero.disabled=true;
	}
	opener.document.form1.submit();
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cfg_cat_ctrl_numero.php";
    f.submit();
  }
</script>
</html>