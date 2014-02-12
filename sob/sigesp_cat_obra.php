<?
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
	$ls_campo="o.codobr";
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
<title>Cat&aacute;logo de Obras</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sob_class_obra.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob=new sigesp_sob_c_funciones_sob();
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_obra=new sigesp_sob_class_obra();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
	$ls_codest="%".$_POST["cmbestado"]."%";
	$ls_nompro="%".$_POST["txtnompro"]."%";
	$ls_estobr=$_POST["hidestado"];//Estado viene vacio si no es necesario filtrar por estado, vienecon alguna cadena si es necesario filtrar por algun estado(status)
	
	$ld_fechainicio=$io_funcion->uf_convertirdatetobd($_POST["txtfeciniobr"]);
	$ld_fechafin=$io_funcion->uf_convertirdatetobd($_POST["txtfecfinobr"]);	
	$ls_codigoobra=$_POST["txtcodobr"];	
	$ls_descripcionobra=$_POST["txtdesobr"];	
	$ls_codigoestado=$_POST["cmbestado"];
	$ls_codpai=$_POST["cmbpais"];
	$ls_nombreproveedor=$_POST["txtnompro"];
	$ls_fechaini=$_POST["txtfeciniobr"];
	$ls_fechafin=$_POST["txtfecfinobr"];

}
else
{
	$ls_operacion="";
	$ls_estobr=$_GET["estado"];	
	$ld_fechainicio="";
	$ld_fechafin="";
	$ls_codigoobra="";
	$ls_descripcionobra="";
	$ls_codigoestado="";
	$ls_nombreproveedor="";
	$ls_fechaini="";
	$ls_fechafin="";
	$ls_codpai='058';
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="800" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="800" colspan="2" class="titulo-celda">Cat&aacute;logo de Obras </td>
    	</tr>
	 </table>
	 <br>
	 <table width="800" border="0" cellpadding="0" cellspacing="3" class="formato-blanco" align="center">
      <tr>
        <td height="18"><div align="right"></div></td>
        <td><div align="right">C&oacute;digo</div></td>
        <td width="295"><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codigoobra;?>" size="6" maxlength="6" ></td>
        <td><div align="right"></div></td>
        <td width="133"><div align="right">Organismo Ejecutor</div></td>
        <td><input name="txtnompro" type="text" id="txtnompro" value="<? print $ls_nombreproveedor;?>" size="30"></td>
      </tr>
      <tr>
        <td height="18"><div align="right"></div></td>
        <td height="18"><div align="right">Descripci&oacute;n</div></td>
        <td><input name="txtdesobr" type="text" id="txtdesobr" value="<? print $ls_descripcionobra;?>" size="30"></td>
        <td><div align="right"></div></td>
        <td><div align="right">Fecha de Inicio</div></td>
        <td><input name="txtfeciniobr" type="text"  id="txtfeciniobr" value="<? print $ls_fechaini;?>" size="11" maxlength="10" datepicker="true" onKeyPress="return validaCajas(this,'n',event)"></td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21"><div align="right">Pa&iacute;s</div></td>
        <td><div align="left">
          <?Php
           $lb_valido=$io_obra->uf_llenarcombo_pais($la_paises);
		   
		   if($lb_valido)
		   {
		    $io_data->data=$la_paises;
		    $li_totalfilas=$io_data->getRowCount("codpai");
		   }
		   ?>
          <select name="cmbpais" id="cmbpais" onChange="javascript:document.form1.submit();">
            <option value="" >Seleccione...</option>
            <?Php
			for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
			 {
			  $ls_codigo=$io_data->getValue("codpai",$li_i);
		      $ls_desest=$io_data->getValue("despai",$li_i);
		      if ($ls_codigo==$ls_codpai)
			   {
				print "<option value='$ls_codigo' selected>$ls_desest</option>";
			   }
		       else
			   {
				print "<option value='$ls_codigo'>$ls_desest</option>";
			   }
		      }      
	        ?>
          </select>
          <input name="hidpais" type="hidden" id="hidpais"  value="<? print $ls_codpai?>">
        </div>
          <div align="right"></div>
        </td>
        <td></td>
        <td  ><div align="right">Fecha de Fin</div></td>
        <td><input name="txtfecfinobr" type="text" id="txtfecfinobr2" value="<? print $ls_fechafin;?>" size="11" maxlength="10" datepicker="true" onKeyPress="return validaCajas(this,'n',event)"></td>
      </tr>
      <tr>
        <td width="13" height="21"><div align="right"></div></td>
        <td width="62" height="21"><div align="right">Estado</div></td>
        <td><div align="left">
          <?Php
           $lb_valido=$io_obra->uf_llenarcombo_estado($ls_codpai,$la_estados);
		   
		   if($lb_valido)
		   {
		    $io_data->data=$la_estados;
		    $li_totalfilas=$io_data->getRowCount("codest");
		   }
		   else
		    $li_totalfilas=0;
		   ?>
          <select name="cmbestado" id="cmbestado">
            <option value="" >Seleccione...</option>
            <?Php
			for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
			 {
			  $ls_codigo=$io_data->getValue("codest",$li_i);
		      $ls_desest=$io_data->getValue("desest",$li_i);
		      if ($ls_codigo==$ls_codigoestado)
			   {
				print "<option value='$ls_codigo' selected>$ls_desest</option>";
			   }
		       else
			   {
				print "<option value='$ls_codigo'>$ls_desest</option>";
			   }
		      }      
	        ?>
          </select>
          <input name="hidestado2" type="hidden" id="hidestado2"  value="<? print $ls_codigoestado ?>">
        </div>          </td>
        <td width="3"></td>
        <td  ><div align="right"></div>          <div align="right"></div></td>
        <td width="292"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>

	<input name="hidestado" id="hidestado" type="hidden" value="<? print $ls_estobr;?>">
<?

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT o.codemp,o.codobr,o.codten,o.codtipest,o.codpai,o.codest,o.codmun,o.codpar,o.codcom,o.codsiscon,o.codpro,o.codtob,
			o.desobr,o.dirobr,o.obsobr,o.resobr,o.feciniobr,o.fecfinobr,o.cantobr,o.monto,o.feccreobr,t.nomten,te.nomtipest,
			sc.nomsiscon,pr.nompro,ti.nomtob,es.desest as desest ,o.staobr,o.codpai
			FROM sob_obra o,sob_tenencia t,sob_tipoestructura te, sob_sistemaconstructivo sc,sob_propietario pr,sob_tipoobra ti,sigesp_estados es
			WHERE o.codemp='".$ls_codemp."' AND o.codobr like '".$ls_codobr."' AND o.desobr like '".$ls_desobr."' AND o.codest like '".$ls_codest."' AND o.codpai like '".$ls_codpai."'
			AND pr.nompro like '".$ls_nompro."' AND o.codpro=pr.codpro AND o.codten=t.codten AND es.codpai=o.codpai AND o.codtipest=te.codtipest AND o.codsiscon=sc.codsiscon AND o.codpro=pr.codpro 
			AND o.codtob=ti.codtob AND o.codest=es.codest AND o.staobr<>3 ";				
			//print $ls_sql;
	if($ls_estobr!="")
	{
		$ls_sql=$ls_sql." AND (o.staobr=1 OR o.staobr=2)";
	}
	
	if($ld_fechainicio=="" && $ld_fechafin=="")
	{
		$ls_sql=$ls_sql." ";
	}
	
	if($ld_fechainicio!="" && $ld_fechafin!="")
	{
		$ls_sql=$ls_sql." AND (feciniobr>='".$ld_fechainicio."' AND fecfinobr<='".$ld_fechafin."')";
	}
	if($ld_fechainicio!="" && $ld_fechafin=="")
	{
		$ls_sql=$ls_sql." AND feciniobr>='".$ld_fechainicio."'";			
	}
	if($ld_fechainicio=="" && $ld_fechafin!="")
	{
		$ls_sql=$ls_sql." AND fecfinobr<='".$ld_fechafin."'";			
	}
	$ls_sql=$ls_sql." ORDER BY $ls_campo $ls_orden";
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}else
	{
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$io_datastore->data=$data;
			$li_totrow=$io_datastore->getRowCount("codobr");
			print "<table width=800 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td><a href=javascript:ue_ordenar('o.codobr','BUSCAR');><font color=#FFFFFF>Código</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.desobr','BUSCAR');><font color=#FFFFFF>Descripción</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('pr.nompro','BUSCAR');><font color=#FFFFFF>Organismo Ejecutor</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('es.desest','BUSCAR');><font color=#FFFFFF>Edo.</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.feciniobr','BUSCAR');><font color=#FFFFFF>Fecha Inicio</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.fecfinobr','BUSCAR');><font color=#FFFFFF>Fecha Fin</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.staobr','BUSCAR');><font color=#FFFFFF>Estado</font></a></td>";
			print "</tr>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{
				print "<tr class=celdas-blancas align=center>";
				$ls_codigo=$data["codobr"][$li_z];
				$ls_descripcion=$data["desobr"][$li_z];
				$ls_estado=$data["desest"][$li_z];
				$ls_codest=$data["codest"][$li_z];
				$ls_codten=$data["codten"][$li_z];
				$ls_codtipest=$data["codtipest"][$li_z];
				$ls_codpai=$data["codpai"][$li_z];	
				$ls_codmun=$data["codmun"][$li_z];
				$ls_codpar=$data["codpar"][$li_z];
				$ls_codcom=$data["codcom"][$li_z];
				$ls_codsiscon=$data["codsiscon"][$li_z];
				$ls_codpro=$data["codpro"][$li_z];	
				$ls_codtob=$data["codtob"][$li_z];			
				$ls_dirobr=$data["dirobr"][$li_z];
				$ls_obsobr=$data["obsobr"][$li_z];
				$ls_resobr=$data["resobr"][$li_z];
				$ld_monto=$data["monto"][$li_z];
				$ls_feccreobr=$io_funcion->uf_convertirfecmostrar($data["feccreobr"][$li_z]);
				$ls_nompro=$data["nompro"][$li_z];				 
				$ls_fechainicio=$io_funcion->uf_convertirfecmostrar($data["feciniobr"][$li_z]);
				$ls_fechafin=$io_funcion->uf_convertirfecmostrar($data["fecfinobr"][$li_z]);
				$ls_nomten=$data["nomten"][$li_z];
				$ls_nomtipest=$data["nomtipest"][$li_z];
				$ls_nomsiscon=$data["nomsiscon"][$li_z];
				$ls_nomtob=$data["nomtob"][$li_z];
				$ls_codigopais=$data["codpai"][$li_z];
				$ls_status=$io_funsob->uf_convertir_numeroestado($data["staobr"][$li_z]);
				print "<td align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_descripcion','$ls_estado','$ls_codest','$ls_codten',
				'$ls_codtipest','$ls_codpai','$ls_codmun','$ls_codpar','$ls_codcom','$ls_codsiscon','$ls_codpro','$ls_codtob',
				'$ls_dirobr','$ls_obsobr','$ls_resobr','$ld_monto','$ls_feccreobr','$ls_nompro','$ls_fechainicio',
				'$ls_fechafin','$ls_nomten','$ls_nomtipest','$ls_nomsiscon','$ls_nomtob','$ls_status','$ls_codigopais');\">".$ls_codigo."</a></td>";
				print "<td align=left>".$ls_descripcion."</td>";
				print "<td align=center>".$ls_nompro."</td>";
				print "<td align=center>".$ls_estado."</td>";
				print "<td align=center>".$ls_fechainicio."</td>";
				print "<td align=center>".$ls_fechafin."</td>";			
				print "<td align=center>".$ls_status."</td>";	
				print "</tr>";			
			}
			print "</table>";
		}
		else
		  {
			$io_msg->message("No se han creado Obras que cumplan con estos parámetros de búsqueda");
			print $io_funcion->uf_convertirmsg($io_sql->message);
		  }
		$io_sql->free_result($rs_data);
		$io_sql->close();
	}
}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  
  function aceptar(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
				  ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
				  ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais)
  {
    opener.ue_cargarobra(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
  				         ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
				         ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_obra.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
