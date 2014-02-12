<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_codper,$as_codvac,$as_stavac)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//				   as_codvac  // Código de Vacaciones
		//				   as_stavac  // Estatus de las vacaciones
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=80>Fecha Vencimiento</td>";
		print "<td width=80>Fecha Disfrute</td>";
		print "<td width=80>Fecha Reingreso</td>";
		print "<td width=100>Días Hábiles</td>";
		print "<td width=100>Estatus</td>";
		print "</tr>";
		$ls_sql="SELECT codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, sueintbonvac, sueintvac, diabonvac, obsvac, ".
				"		diapenvac, persalvac, peringvac, dianorvac, quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, ".
				"		diapag, pagcan, periodo_1, cod_1, nro_dias_1, monto_1, periodo_2, cod_2, nro_dias_2, monto_2, periodo_3, ".
				"		cod_3,nro_dias_3, monto_3, periodo_4, cod_4, nro_dias_4, monto_4, periodo_5, cod_5, nro_dias_5, monto_5, ".
				"       diapervac, pagpersal".
				"  FROM sno_vacacpersonal ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'";
		if(!empty($as_codvac))
		{
			$ls_sql=$ls_sql."  AND codvac=".$as_codvac."";
		}
		if(!empty($as_stavac))
		{
			$ls_sql=$ls_sql."  AND stavac=".$as_stavac."";
		}
		$ls_sql=$ls_sql." ORDER BY codper";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_codvac=$row["codvac"];
				$ld_fecvenvac=$io_funciones->uf_convertirfecmostrar($row["fecvenvac"]);
				$ld_fecdisvac=$io_funciones->uf_convertirfecmostrar($row["fecdisvac"]);
				$ld_fecreivac=$io_funciones->uf_convertirfecmostrar($row["fecreivac"]);
				$li_diavac=$row["diavac"];
				$li_stavac=$row["stavac"];
				$li_sueintbonvac=$io_fun_nomina->uf_formatonumerico($row["sueintbonvac"]);
				$li_sueintvac=$io_fun_nomina->uf_formatonumerico($row["sueintvac"]);
				$li_diabonvac=$row["diabonvac"];
				$ls_obsvac=$row["obsvac"];
				$li_diapenvac=$row["diapenvac"];
				$ls_persalvac=$row["persalvac"];
				$ls_peringvac=$row["peringvac"];
				$li_dianorvac=$row["dianorvac"];
				$li_quisalvac=$row["quisalvac"];
				$li_quireivac=$row["quireivac"];
				$li_diaadivac=$row["diaadivac"];
				$li_diaadibon=$row["diaadibon"];
				$li_diafer=$row["diafer"];
				$li_sabdom=$row["sabdom"];
				$li_diapag=$row["diapag"];
				$li_pagcan=$row["pagcan"];
				$li_diapervac=$row["diapervac"];
				$ls_pagpersal=$row["pagpersal"];
				switch($li_stavac)
				{
					case 1:// Vacaciones Vencidas
						$ls_estatus="Vencidas";
						break;

					case 2:// Vacaciones Programadas
						$ls_estatus="Programadas";
						break;

					case 3:// En Vacaciones
						$ls_estatus="En Vacaciones";
						break;

					case 4:// Vacaciones Disfrutadas
						$ls_estatus="Disfrutadas";
						break;
				}
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$li_codvac','$ld_fecvenvac','$ld_fecdisvac','$ld_fecreivac','$li_diavac',";
				print "'$li_stavac','$li_sueintbonvac','$li_sueintvac','$li_diabonvac','$ls_obsvac','$li_diapenvac','$ls_persalvac',";
				print "'$ls_peringvac','$li_dianorvac','$li_quisalvac','$li_quireivac','$li_diaadivac','$li_diaadibon','$li_diafer',";
				print "'$li_sabdom','$li_diapag','$li_pagcan','$li_diapervac','$ls_pagpersal');\">".$li_codvac."</a></td>";
				print "<td>".$ld_fecvenvac."</td>";
				print "<td>".$ld_fecdisvac."</td>";
				print "<td>".$ld_fecreivac."</td>";
				print "<td>".($li_diavac+$li_diaadivac)."</td>";
				print "<td>".$ls_estatus."</td>";
				print "</tr>";			
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Vacaciones</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Vacaciones </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">N&uacute;mero </div></td>
        <td width="431"><div align="left">
            <input name="txtcodvac" type="text" id="txtcodvac" size="30" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Estatus</div></td>
        <td><div align="left">
          <select name="cmbstavac" id="cmbstavac">
            <option value="0" selected>-- Seleccione Uno--</option>
            <option value="1">Vencidas</option>
            <option value="2">Programadas </option>
            <option value="3">en Vacaciones</option>
            <option value="4">Disfrutadas</option>
          </select>
</div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codvac=$_POST["txtcodvac"];
		$ls_stavac=$_POST["cmbstavac"];
		$ls_codper=$_POST["txtcodper"];
		uf_print($ls_codper,$ls_codvac,$ls_stavac);
	}
	else
	{
		$ls_codper=$_GET["codper"];
		$ls_codvac="";
		$ls_stavac="";
		uf_print($ls_codper,$ls_codvac,$ls_stavac);
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codvac,fecvenvac,fecdisvac,fecreivac,diavac,stavac,sueintbonvac,sueintvac,diabonvac,obsvac,diapenvac,persalvac,
                peringvac,dianorvac,quisalvac,quireivac,diaadivac,diaadibon,diafer,sabdom,diapag,pagcan,diapervac,pagpersal)
{
	opener.document.form1.txtcodvac.value=codvac;
	opener.document.form1.txtcodvac.readOnly=true;
	opener.document.form1.txtfecvenvac.value=fecvenvac;
	opener.document.form1.txtfecdisvac.value=fecdisvac;
    opener.document.form1.txtfecreivac.value=fecreivac;
    opener.document.form1.txtdiavac.value=diavac;
    opener.document.form1.cmbstavac.value=stavac;
    opener.document.form1.txtstavac.value=stavac;
	opener.document.form1.txtsueintbonvac.value=sueintbonvac;
	opener.document.form1.txtsueintvac.value=sueintvac;
    opener.document.form1.txtdiabonvac.value=diabonvac;
    opener.document.form1.txtobsvac.value=obsvac;
    opener.document.form1.txtdiapenvac.value=diapenvac;
	opener.document.form1.txtdiaadivac.value=diaadivac;
    opener.document.form1.txtdiaadibon.value=diaadibon;
    opener.document.form1.txtdiafer.value=diafer;
    opener.document.form1.txtsabdom.value=sabdom;
	opener.document.form1.txtpersalvac.value=persalvac;
	opener.document.form1.txtperingvac.value=peringvac;
    opener.document.form1.txtdianorvac.value=dianorvac;
    opener.document.form1.txtquisalvac.value=quisalvac;
    opener.document.form1.txtquireivac.value=quireivac;
	opener.document.form1.txtdiapervac.value=diapervac;
	opener.document.form1.existe.value="TRUE";
	if (diapag=="1")
	{
		opener.document.form1.chkdiapag.checked=true;
	}
	else
	{
		opener.document.form1.chkdiapag.checked=false;
	}
	if (pagcan=="1")
	{
		opener.document.form1.chkpagcan.checked=true;
	}
	else
	{
		opener.document.form1.chkpagcan.checked=false;
	}
	if (pagpersal=="1")
	{
		opener.document.form1.chkpagpersal.checked=true;
	}
	else
	{
		opener.document.form1.chkpagpersal.checked=false;
	}
	if((stavac=="3")||(stavac=="4"))
	{
		opener.document.form1.txtfecvenvac.readOnly=true;
		opener.document.form1.txtfecdisvac.readOnly=true;
		opener.document.form1.txtfecreivac.readOnly=true;
		opener.document.form1.txtdiavac.readOnly=true;
		opener.document.form1.cmbstavac.disabled=true;
		opener.document.form1.txtsueintbonvac.readOnly=true;
		opener.document.form1.txtsueintvac.readOnly=true;
		opener.document.form1.txtdiabonvac.readOnly=true;
		opener.document.form1.txtobsvac.readOnly=true;
		opener.document.form1.txtdiapenvac.readOnly=true;
		opener.document.form1.txtdiaadivac.readOnly=true;
		opener.document.form1.txtdiaadibon.readOnly=true;
		opener.document.form1.txtdiafer.readOnly=true;
		opener.document.form1.txtsabdom.readOnly=true;
		opener.document.form1.chkdiapag.disabled=true;
		opener.document.form1.chkpagcan.disabled=true;
		opener.document.form1.txtpersalvac.readOnly=true;
		opener.document.form1.txtperingvac.readOnly=true;
		opener.document.form1.txtdianorvac.readOnly=true;
		opener.document.form1.txtquisalvac.readOnly=true;
		opener.document.form1.txtquireivac.readOnly=true;
	}
	else
	{
		opener.document.form1.txtfecvenvac.readOnly=false;
		opener.document.form1.txtfecdisvac.readOnly=false;
		opener.document.form1.txtfecreivac.readOnly=false;
		opener.document.form1.txtdiavac.readOnly=false;
		opener.document.form1.cmbstavac.disabled=false;
		opener.document.form1.txtsueintbonvac.readOnly=false;
		opener.document.form1.txtsueintvac.readOnly=false;
		opener.document.form1.txtdiabonvac.readOnly=false;
		opener.document.form1.txtobsvac.readOnly=false;
		opener.document.form1.txtdiapenvac.readOnly=false;
		opener.document.form1.txtdiaadivac.readOnly=false;
		opener.document.form1.txtdiaadibon.readOnly=false;
		opener.document.form1.txtdiafer.readOnly=false;
		opener.document.form1.txtsabdom.readOnly=false;
		opener.document.form1.chkdiapag.disabled=false;
		opener.document.form1.chkpagcan.disabled=false;
		opener.document.form1.txtpersalvac.readOnly=false;
		opener.document.form1.txtperingvac.readOnly=false;
		opener.document.form1.txtdianorvac.readOnly=false;
		opener.document.form1.txtquisalvac.readOnly=false;
		opener.document.form1.txtquireivac.readOnly=false;
	}
	close();
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_vacacion.php";
  	f.submit();
}
</script>
</html>
