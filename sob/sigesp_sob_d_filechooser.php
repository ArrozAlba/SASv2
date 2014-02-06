<?Php
	session_start();
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "location.href='../sigesp_conexion.php';";
		 print "</script>";		
	   }
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$io_funsob=new sigesp_sob_c_funciones_sob();
	if(!array_key_exists("documento",$_POST))
	{
		$ls_documento=$_GET["documento"];
		if($ls_documento=="ACTA")
		{
			$ls_codact=$_GET["codact"];
			$ls_codcon=$_GET["codcon"];
			$ls_tipact=$_GET["tipact"];
		}
		elseif($ls_documento=="CONTRATO")
		{
			$ls_codcon=$_GET["codcon"];
		}	
		elseif($ls_documento=="PUNTODECUENTA")
		{
			$ls_codpuncue=$_GET["codpuncue"];
			$ls_codobr=$_GET["codobr"];
		}	
	}
	else
	{
		$ls_documento=$_POST["documento"];
		$ls_ruta=$_POST["hidfile"];				
		if($ls_documento=="ACTA")
		{
			$ls_codact=$_POST["codact"];
			$ls_codcon=$_POST["codcon"];
			$ls_tipact=$_POST["tipact"];			
			require_once("class_folder/sigesp_sob_c_contrato.php");
			$io_contrato=new sigesp_sob_c_contrato();
			require_once("class_folder/sigesp_sob_class_obra.php");
			$io_obra=new sigesp_sob_class_obra();
			require_once("class_folder/sigesp_sob_c_acta.php");
			$io_acta=new sigesp_sob_c_acta();
			require_once("class_folder/sigesp_sob_c_supervisores.php");
			$io_supervisores=new sigesp_sob_c_supervisores();
			//---------------cargando la data en arreglos----------------//
			$io_contrato->uf_select_contrato($ls_codcon,$la_contrato);
			$io_acta->uf_select_acta($ls_codcon,$ls_codact,$ls_tipact,$la_acta);
			$io_obra->uf_select_obra($la_contrato["codobr"][1],$la_obra);
			$io_supervisores->uf_select_supervisor($la_acta["cedinsact"][1],$la_inspector);
			$io_supervisores->uf_select_supervisor($la_acta["cedresact"][1],$la_residente);
			$ls_contrato=$io_funsob->uf_codificar($la_contrato);
			$ls_acta=$io_funsob->uf_codificar($la_acta);
			$ls_obra=$io_funsob->uf_codificar($la_obra);
			$ls_inspector=$io_funsob->uf_codificar($la_inspector);
			$ls_residente=$io_funsob->uf_codificar($la_residente);					
			$io_funsob->uf_ventanasimple_js("sigesp_sob_d_documentos_acta.php?contrato=".$ls_contrato."&acta=".$ls_acta."&obra=".$ls_obra."&inspector=".$ls_inspector."&residente=".$ls_residente."&ruta=".$ls_ruta."&documento=".$ls_documento);				
			
		}
		elseif($ls_documento=="CONTRATO")
		{
			//----------------Clases y Objetos--------------------------------------//
			require_once("class_folder/sigesp_sob_c_contrato.php");
			$io_contrato=new sigesp_sob_c_contrato();
			require_once("class_folder/sigesp_sob_class_obra.php");
			$io_obra=new sigesp_sob_class_obra();
			require_once("class_folder/sigesp_sob_class_asignacion.php");
			$io_asignacion=new sigesp_sob_class_asignacion();
			require_once("class_folder/sigesp_sob_c_supervisores.php");
			$io_supervisores=new sigesp_sob_c_supervisores();
			require_once("class_folder/sigesp_sob_c_unidad.php");
			$io_unidad=new sigesp_sob_c_unidad();
			//---------------cargando la data en arreglos----------------//
			$ls_codcon=$_POST["codcon"];						
			$io_contrato->uf_select_contrato($ls_codcon,$la_contrato);
			$io_contrato->uf_select_condiciones ($ls_codcon,$la_condiciones,$li_rows);
			$io_contrato->uf_select_retenciones ($ls_codcon,$la_retenciones,$li_rows);
			$io_contrato->uf_select_garantias ($ls_codcon,$la_garantias,$li_rows);
			$io_obra->uf_select_obra($la_contrato["codobr"][1],$la_obra);
			$io_asignacion->uf_select_asignacion($la_contrato["codasi"][1],$la_asignacion);
			$io_supervisores->uf_select_proveedor($la_asignacion["cod_pro_ins"][1],$la_inspector);
			$io_supervisores->uf_select_proveedor($la_asignacion["cod_pro"][1],$la_contratista);
			$io_unidad->uf_select_unidades($la_contrato["mulreuni"][1],$la_unidadmulta);
			$io_unidad->uf_select_unidades($la_contrato["lapgaruni"][1],$la_unidadgarantia);
			$ls_contrato=$io_funsob->uf_codificar($la_contrato);
			$ls_condiciones=$io_funsob->uf_codificar_arreglosdobles($la_condiciones);
			$ls_retenciones=$io_funsob->uf_codificar_arreglosdobles($la_retenciones);
			$ls_garantias=$io_funsob->uf_codificar_arreglosdobles($la_garantias);
			$ls_obra=$io_funsob->uf_codificar($la_obra);
			$ls_asignacion=$io_funsob->uf_codificar($la_asignacion);
			$ls_inspector=$io_funsob->uf_codificar($la_inspector);
			$ls_contratista=$io_funsob->uf_codificar($la_contratista);
			$ls_unidadmulta=$io_funsob->uf_codificar($la_unidadmulta);
			$ls_unidadgarantia=$io_funsob->uf_codificar($la_unidadgarantia);			
			//--------------Abriendo ventana de OpenOffice---------------//
			$io_funsob->uf_ventanasimple_js("sigesp_sob_d_documentos_contrato.php?documento=".$ls_documento."&ruta=".$ls_ruta."&contrato=".$ls_contrato."&condiciones=".$ls_condiciones."&retenciones=".$ls_retenciones."&garantias=".$ls_garantias."&obra=".$ls_obra."&asignacion=".$ls_asignacion."&inspector=".$ls_inspector."&contratista=".$ls_contratista."&unidadmulta=".$ls_unidadmulta."&unidadgarantia=".$ls_unidadgarantia);			
		}	
		elseif($ls_documento="PUNTODECUENTA")
		{
			//----------------Clases y Objetos--------------------------------------//
			require_once("class_folder/sigesp_sob_c_puntodecuenta.php");
			$io_puntodecuenta=new sigesp_sob_c_puntodecuenta();
			//---------------cargando la data en arreglos----------------//
			$ls_codpuncue=$_POST["codpuncue"];
			$ls_codobr=$_POST["codobr"];
			$io_puntodecuenta->uf_select_puntodecuenta($ls_codpuncue,$ls_codobr,$la_datapuntodecuenta);
			$io_puntodecuenta->uf_select_cuentas ($ls_codpuncue,$ls_codobr,$la_datacuentas,$li_rows);
			$ls_puntodecuenta=$io_funsob->uf_codificar($la_datapuntodecuenta);
			$ls_codigopresupuestariomonto="";
			$ls_cuentamonto="";
			$ls_codigopresupuestarioiva="";
			$ls_cuentaiva="";
			for($li_i=1;$li_i<=$li_rows;$li_i++)
			{
				if($la_datacuentas["concuepuncue"][$li_i]==1)
				{
					$ls_codigopresupuestariomonto=$la_datacuentas["codestpro1"][$li_i].$la_datacuentas["codestpro2"][$li_i].$la_datacuentas["codestpro3"][$li_i].$la_datacuentas["codestpro4"][$li_i].$la_datacuentas["codestpro5"][$li_i];
					$ls_cuentamonto=$la_datacuentas["spg_cuenta"][$li_i];
				}
				else
				{
					$ls_codigopresupuestarioiva=$la_datacuentas["codestpro1"][$li_i].$la_datacuentas["codestpro2"][$li_i].$la_datacuentas["codestpro3"][$li_i].$la_datacuentas["codestpro4"][$li_i].$la_datacuentas["codestpro5"][$li_i];
					$ls_cuentaiva=$la_datacuentas["spg_cuenta"][$li_i];
				}
			}			
			$io_funsob->uf_ventanasimple_js("sigesp_sob_d_documentos_puntodecuenta.php?documento=".$ls_documento."&ruta=".$ls_ruta."&puntodecuenta=".$ls_puntodecuenta."&codigopresupuestariomonto=".$ls_codigopresupuestariomonto."&cuentamonto=".$ls_cuentamonto."&codigopresupuestarioiva=".$ls_codigopresupuestarioiva."&cuentaiva=".$ls_cuentaiva);			
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Selecci&oacute;n de plantilla para el Documento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<style type="text/css">
<!--
body {
	margin-top: 40px;
}
-->
</style>

</head>

<body>
<form name="form1" method="post" action="">
<table width="356" border="0" align="center" cellpadding="0" cellspacing="0" class="titulo-celdanew">
  <tr>
    <td width="301"><div align="center">Seleccione la plantilla del Documento </div></td>
  </tr>
</table>
  <table width="356" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="43" colspan="2"><div align="center"><input type="file" class="celdas-amarillas" size="40" onKeyPress="return validaCajas(this,'n',event)" name="txtfile" id="txtfile">
      </div></td>
    </tr>
    <tr>
      <td width="107"><div align="right"></div></td>
      <td width="247"><a href="javascript:uf_aceptar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" width="15" height="15" border="0" ></a><a href="javascript:uf_cancelar();"><img src="../shared/imagebank/eliminar.gif" border="0" alt="Cancelar" width="15" height="15" ></a></td>
    </tr>
  </table>
  <input type="hidden" name="codcon" id="codcon" value="<?php print $ls_codcon;?>">
  <input type="hidden" name="codact" id="codact" value="<?php print $ls_codact;?>">
  <input type="hidden" name="tipact" id="tipact" value="<?php print $ls_tipact;?>">
  <input type="hidden" name="codpuncue" id="codpuncue" value="<?php print $ls_codpuncue;?>">
  <input type="hidden" name="codobr" id="codobr" value="<?php print $ls_codobr;?>">
  <input type="hidden" name="documento" id="documento" value="<?php print $ls_documento;?>">
  <input type="hidden" name="hidfile" id="hidfile">
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function uf_aceptar()
{
	f=document.form1;
	var ls_ruta=f.txtfile.value;
	if (f.txtfile.value!="")
	{
		f.hidfile.value=f.txtfile.value;
		f.submit();		
		/*var codcon=f.codcon.value;
		var tipact=f.tipact.value;
		var codact=f.codact.value;
		var documento=f.documento.value;
			
		if(documento=="ACTA")
		{
			pagina="sigesp_sob_documentos.php?ruta="+ls_ruta+"&codcon="+codcon+"&tipact="+tipact+"&codact="+codact+"&documento="+documento;
		}
		else
		{
			pagina="../tbsooo/example/tbsooo_us_examples_hello.php?ruta="+ls_ruta+"&codcon="+codcon+"&tipact="+tipact+"&codact="+codact;
		}
		
		location.href=pagina;
		if (navigator.appName=="Microsoft Internet Explorer")
		{			
			 window.resizeBy(550,550);
			 window.moveTo(20,20);
		}		*/
	}
	else
	{
		alert("Seleccione un Archivo!!!");
		
	}

}

function uf_cancelar()
{
	close();
}

</script>
</html>
