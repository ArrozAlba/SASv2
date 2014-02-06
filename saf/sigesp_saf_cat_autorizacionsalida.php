<?php
session_start();
require_once("class_funciones_activos.php");
$io_fact= new class_funciones_activos("../");
if(array_key_exists("coddestino",$_POST))
{
	$ls_coddestino=$_POST["coddestino"];
	$ls_tipo=$_POST["tipo"];
}
else
{
	$ls_coddestino=$io_fact->uf_obtenervalor_get("coddestino","");
	$ls_tipo=$io_fact->uf_obtenervalor_get("tipo","");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Autorización de Salida</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion"  type="hidden" id="operacion">
    <input name="hidstatus"  type="hidden" id="hidstatus">
    <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>">
    <input name="tipo"       type="hidden" id="tipo"       value="<?php print $ls_tipo ?>">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Autorizaci&oacute;n de Salida </td>
    </tr>
  </table>
<br>
   <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
        <tr>
          <td>&nbsp;</td>
          <td height="18">&nbsp;</td>
          <td width="148" rowspan="3"><table width="145" border="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="2"><div align="center" class="titulo-conect">Rango de Fechas </div></td>
            </tr>
            <tr>
              <td width="36"><div align="right">Desde</div></td>
              <td width="103" height="22"><input name="txtdesde" type="text" id="txtdesde" size="15"  datepicker="true"  onKeyPress="ue_separadores(this,'/',patron,true);"></td>
            </tr>
            <tr>
              <td><div align="right">Hasta</div></td>
              <td height="22"><input name="txthasta" type="text" id="txthasta" size="15"  datepicker="true"  onKeyPress="ue_separadores(this,'/',patron,true);"></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      <tr>
        <td width="120"><div align="right">Comprobante</div></td>
        <td width="162" height="22"><div align="left">
          <input name="txtcmpmov" type="text" id="txtcmpmov">
        </div></td>
        <td width="68">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right"></div></td>
        <td height="22"><div align="left"></div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];
$ls_gestor = $_SESSION["ls_gestor"];
require_once("sigesp_saf_c_activo.php");
$io_saf = new sigesp_saf_c_activo();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_cmpsal="%".$_POST["txtcmpmov"]."%";
	$ld_fecdes="%".$_POST["txtdesde"]."%";
	$ld_fechas="%".$_POST["txthasta"]."%";
	if(($ld_fecdes=="%%")||($ld_fechas=="%%"))
	{
		$ld_fecdes="";
		$ld_fechas="";
	}
	else
	{
		$ld_fecdes=substr($ld_fecdes,1,10);
		$ld_fechas=substr($ld_fechas,1,10);
			
		$ld_fecdes=$io_fun->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_fun->uf_convertirdatetobd($ld_fechas);
	}
}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Comprobante </td>";
print "<td width='150'>Unidad Cedente</td>";
print "<td width='150'>Empresa que Recibe</td>";
print "<td width='65'>Fecha</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	if(($ld_fecdes!="")&&($ld_fechas!=""))
	{
		$ls_sqlint=" AND  saf_autsalida.fecaut >= '".$ld_fecdes." 00:00:00'".
				   " AND  saf_autsalida.fecaut <= '".$ld_fechas." 23:59:59' ";
	}
	else
	{
		$ls_sqlint="";
	}
	$ls_sql="SELECT distinct saf_autsalida.cmpsal,saf_autsalida.coduniadm,saf_autsalida.codpro,saf_autsalida.fecaut,saf_autsalida.fecent,saf_autsalida.fecdev,
	          (select spg_unidadadministrativa.denuniadm 
			      from spg_unidadadministrativa
			      where spg_unidadadministrativa.codemp='".$ls_codemp."'
				  and saf_autsalida.coduniadm=spg_unidadadministrativa.coduniadm)AS nomadmced,
			  saf_autsalida.codpro,
			  (select nompro
			      from rpc_proveedor
			      where rpc_proveedor.codemp='".$ls_codemp."' 
				  and rpc_proveedor.cod_pro=saf_autsalida.codpro)AS nomprov,
			 (select cedrep 
			      from rpc_proveedor
			      where rpc_proveedor.codemp='".$ls_codemp."' 
				  and rpc_proveedor.cod_pro=saf_autsalida.codpro)AS cedresp,
			  (select nomreppro
				   from rpc_proveedor
				   where rpc_proveedor.codemp='".$ls_codemp."'
				   and rpc_proveedor.cod_pro=saf_autsalida.codpro)AS nomrep,".
			"  saf_autsalida.estprosal,saf_autsalida.consal,saf_autsalida.obssal".
			"  FROM saf_autsalida,saf_dt_autsalida".
			"  WHERE saf_autsalida.codemp='".$ls_codemp."' ".
			"   AND saf_autsalida.cmpsal=saf_dt_autsalida.cmpsal".
			"   AND saf_autsalida.coduniadm=saf_dt_autsalida.coduniadm ".
			"   AND saf_autsalida.fecaut=saf_dt_autsalida.fecaut".
			"   AND saf_autsalida.codemp='".$ls_codemp."'".$ls_sqlint.
			"   AND saf_autsalida.cmpsal like '".$ls_cmpsal."' "; 
	//print "---------->".$ls_sql;
	$rs_cta=$io_sql->select($ls_sql);
	$li_row=$io_sql->num_rows($rs_cta);
	if($li_row>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_cmpsal=$row["cmpsal"]; 
			$ls_coduniced=$row["coduniadm"];
			$ls_desuniced=$row["nomadmced"];
			$ld_fecaut=$row["fecaut"];
			$ld_fecaut=$io_fun->uf_convertirfecmostrar($ld_fecaut);
			$ld_fecent=$row["fecent"];
			$ld_fecent=$io_fun->uf_convertirfecmostrar($ld_fecent);
			$ld_fecdev=$row["fecdev"];
			$ld_fecdev=$io_fun->uf_convertirfecmostrar($ld_fecdev); 
			$ls_codpro=$row["codpro"];
			$ls_nompro=$row["nomprov"];
			$ls_cedresp=$row["cedresp"];   
			$ls_nomrep=$row["nomrep"];
			$ls_estprosal=$row["estprosal"];
			$ls_consal=$row["consal"];  
			$ls_obser=$row["obssal"]; 
			print "<td><a href=\"javascript: aceptar('$ls_cmpsal','$ls_coduniced','$ls_desuniced','$ld_fecaut','$ld_fecent',".
				  "'$ld_fecdev','$ls_codpro','$ls_nompro','$ls_cedresp','$ls_nomrep','$ls_estprosal','$ls_consal','$ls_obser');\">".$ls_cmpsal."</a></td>";
			print "<td>".$ls_desuniced."</td>";
			print "<td>".$ls_nompro."</td>";
			print "<td>".$ld_fecaut."</td>";
			print "</tr>";			
		}
	}
	else
	{
			$io_msg->message("No hay registros");
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
	function aceptar($ls_cmpsal,$ls_coduniced,$ls_desuniced,$ld_fecaut,$ld_fecent,$ld_fecdev,$ls_codpro,$ls_nompro,
	                 $ls_cedresp,$ls_nomrep,$ls_estprosal,$ls_consal,$ls_obser)
	{
		opener.document.form1.txtautosali.value=$ls_cmpsal;
		opener.document.form1.txtcoduniadm.value=$ls_coduniced;
		opener.document.form1.txtdenuniadm.value=$ls_desuniced;
		opener.document.form1.txtcodpro.value=$ls_codpro;  
		opener.document.form1.txtdenpro.value=$ls_nompro; 
		opener.document.form1.txtfechsalida.value=$ld_fecaut; 
		opener.document.form1.txtcedrepre.value=$ls_cedresp;
		opener.document.form1.txtnomrepre.value=$ls_nomrep;
		opener.document.form1.txtconcepto.value=$ls_consal;
		opener.document.form1.txtfecentrega.value=$ld_fecent;
		opener.document.form1.txtfecdevolucion.value=$ld_fecdev;
		opener.document.form1.txtobser.value=$ls_obser;
		opener.document.form1.hidestauto.value=$ls_estprosal;
		
		opener.document.form1.txtautosali.readOnly=true;
		opener.document.form1.txtcoduniadm.readOnly=true;
		opener.document.form1.txtdenuniadm.readOnly=true;
		opener.document.form1.txtcodpro.readOnly=true;
		opener.document.form1.txtdenpro.readOnly=true;
		opener.document.form1.txtfechsalida.readOnly=true;   
		opener.document.form1.txtcedrepre.readOnly=true;
		opener.document.form1.txtnomrepre.readOnly=true;
		opener.document.form1.txtconcepto.readOnly=true;
		opener.document.form1.txtfecentrega.readOnly=true;
		opener.document.form1.txtfecdevolucion.readOnly=true;
		opener.document.form1.txtobser.readOnly=true;
		opener.document.form1.hidestauto.readOnly=true;
		tipo=document.form1.tipo.value;
	
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.submit();
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_autorizacionsalida.php";
		f.submit();
	}
 
////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>