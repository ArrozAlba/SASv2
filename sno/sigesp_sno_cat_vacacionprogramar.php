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
   function uf_print($as_codper,$as_stavac)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//	    		   as_stavac  // Estatus de Vacaciones
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
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
		print "<td width=40>Código</td>";
		print "<td width=60>Cédula</td>";
		print "<td width=210>Nombre y Apellido</td>";
		print "<td width=60>Fecha Vencimiento</td>";
		print "<td width=60>Fecha Disfrute</td>";
		print "<td width=70>Estatus</td>";
		print "</tr>";
		$ls_sql="SELECT sno_vacacpersonal.codper, sno_vacacpersonal.codvac, sno_vacacpersonal.fecvenvac, sno_vacacpersonal.fecdisvac, ".
				"		sno_vacacpersonal.fecreivac, sno_vacacpersonal.diavac, sno_vacacpersonal.stavac, sno_vacacpersonal.sueintbonvac, ".
				"		sno_vacacpersonal.sueintvac, sno_vacacpersonal.diabonvac, sno_vacacpersonal.obsvac, sno_vacacpersonal.diapenvac, ".
				"		sno_vacacpersonal.persalvac, sno_vacacpersonal.peringvac, sno_vacacpersonal.dianorvac, sno_vacacpersonal.quisalvac, ".
				"		sno_vacacpersonal.quireivac, sno_vacacpersonal.diaadivac, sno_vacacpersonal.diaadibon, sno_vacacpersonal.diafer, ".
				"		sno_vacacpersonal.sabdom, sno_vacacpersonal.diapag, sno_vacacpersonal.pagcan, sno_vacacpersonal.periodo_1, ".
				"		sno_vacacpersonal.cod_1, sno_vacacpersonal.nro_dias_1, sno_vacacpersonal.Monto_1, sno_vacacpersonal.periodo_2, ".
				"		sno_vacacpersonal.cod_2, sno_vacacpersonal.nro_dias_2, sno_vacacpersonal.Monto_2, sno_vacacpersonal.periodo_3, ".
				"		sno_vacacpersonal.cod_3, sno_vacacpersonal.nro_dias_3, sno_vacacpersonal.Monto_3, sno_vacacpersonal.periodo_4, ".
				"		sno_vacacpersonal.cod_4, sno_vacacpersonal.nro_dias_4, sno_vacacpersonal.Monto_4, sno_vacacpersonal.periodo_5, ".
				"		sno_vacacpersonal.cod_5, sno_vacacpersonal.nro_dias_5, sno_vacacpersonal.Monto_5, ".
				"		sno_vacacpersonal.diapervac, sno_vacacpersonal.pagpersal,".
				"		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper ".				
				"  FROM sno_vacacpersonal, sno_personal ".
				" WHERE sno_vacacpersonal.codemp='".$ls_codemp."' ".
				"   AND sno_vacacpersonal.codper like '".$as_codper."' ".
				"	AND sno_vacacpersonal.codemp=sno_personal.codemp ".
				"   AND sno_vacacpersonal.codper=sno_personal.codper ";
		if(!empty($as_stavac))
		{
			$ls_sql=$ls_sql."   AND sno_vacacpersonal.stavac=".$as_stavac." ";
		}
		else
		{
			$ls_sql=$ls_sql."   AND (sno_vacacpersonal.stavac=1 OR sno_vacacpersonal.stavac=2)";
		}
		$ls_sql=$ls_sql." ORDER BY sno_personal.codper, sno_vacacpersonal.codvac ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_cedper=$row["cedper"];
				$ls_nomper=$row["apeper"].", ".$row["nomper"];
				$li_codvac=$row["codvac"];
				$ld_fecvenvac=$io_funciones->uf_formatovalidofecha($row["fecvenvac"]);
				$ld_fecdisvac=$io_funciones->uf_formatovalidofecha($row["fecdisvac"]);
				$ld_fecreivac=$io_funciones->uf_formatovalidofecha($row["fecreivac"]);
				$ld_fecvenvac=$io_funciones->uf_convertirfecmostrar($ld_fecvenvac);
				$ld_fecdisvac=$io_funciones->uf_convertirfecmostrar($ld_fecdisvac);
				$ld_fecreivac=$io_funciones->uf_convertirfecmostrar($ld_fecreivac);
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
				print "'$li_sabdom','$li_diapag','$li_pagcan','$ls_codper','$ls_nomper','$li_diapervac','$ls_pagpersal');\">".$li_codvac."</a></td>";
				print "<td>".$ls_cedper."</td>";
				print "<td>".$ls_nomper."</td>";
				print "<td>".$ld_fecvenvac."</td>";
				print "<td>".$ld_fecdisvac."</td>";
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
<title>Cat&aacute;logo de Vacaciones por Programar</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Vacaciones por Programar </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22"><div align="right">Personal </div></td>
        <td><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="10" maxlength="100">
            <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" size="50" maxlength="100" readOnly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Estatus</div></td>
        <td><div align="left">
          <select name="cmbstavac" id="cmbstavac">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="1">Vencidas</option>
              <option value="2">Programadas</option>
          </select>
        </div></td>
      </tr>
      <tr>
        <td width="67" height="22">&nbsp;</td>
        <td width="431"><div align="left">
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
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_stavac=$_POST["cmbstavac"];
		uf_print($ls_codper,$ls_stavac);
	}
	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codvac,fecvenvac,fecdisvac,fecreivac,diavac,stavac,sueintbonvac,sueintvac,diabonvac,obsvac,diapenvac,persalvac,
                peringvac,dianorvac,quisalvac,quireivac,diaadivac,diaadibon,diafer,sabdom,diapag,pagcan,codper,nomper,diapervac,		
				pagpersal)
{
    opener.document.form1.txtcodper.value=codper;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtcodvac.value=codvac;
	opener.document.form1.txtfecvenvac.value=fecvenvac;
	opener.document.form1.txtfecdisvac.value=fecdisvac;
    opener.document.form1.txtfecreivac.value=fecreivac;
    opener.document.form1.txtdiavac.value=diavac;
    opener.document.form1.cmbstavac.value=stavac;
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
    opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.readOnly=true;
	opener.document.form1.txtcodvac.readOnly=true;
	opener.document.form1.txtfecvenvac.readOnly=true;
	opener.document.form1.txtfecreivac.readOnly=true;
	opener.document.form1.txtdiavac.readOnly=true;
	opener.document.form1.txtsueintbonvac.readOnly=true;
	if(parseInt(sueintvac)>0)
	{
		opener.document.form1.txtsueintvac.readOnly=true;	
	}
	else
	{
		opener.document.form1.txtsueintvac.readOnly=false;		
	}
	
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
	close();
}

function ue_buscarpersonal()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=catvacacion","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sno_cat_vacacionprogramar.php";
  	f.submit();
}
</script>
</html>