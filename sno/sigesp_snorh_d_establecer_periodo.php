<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_establecer_periodo.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codnom, $ls_desnom, $ls_operacion, $li_totrows, $ls_titletable, $li_widthtable;
		global $ls_nametable, $lo_title, $io_fun_nomina;
		
	 	$ls_codnom="";
		$ls_desnom="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Periodos Disponibles Para establecer";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Establecer";
		$lo_title[2]="Periodo";
		$lo_title[3]="Desde";
		$lo_title[4]="Hasta";
	}
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Establecer Periodo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
}
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
<script type="text/javascript" language="JavaScript1.2" src="../../../swap/js/stm31.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_nominas.php");
	$io_nomina=new sigesp_snorh_c_nominas();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codnom=$_GET["codnom"];
			$ls_desnom=$_GET["desnom"];
			$lb_valido=$io_nomina->uf_load_periodo_establecer($ls_codnom,$li_totrows,$lo_object);
			break;
			
		case "GUARDAR":
		 	$ls_codnom=$_POST["txtcodnom"];
			$ls_desnom=$_POST["txtdesnom"];
			$lb_valido=true;
			$io_nomina->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_totrows&&$lb_valido;$li_i++)
			{
			    $ls_codperi=$_POST["txtcodperi".$li_i];
				$ls_fecdesper=$_POST["txtfecdesper".$li_i];
				$ls_fechasper=$_POST["txtfechasper".$li_i];
				$li_aplica=$io_fun_nomina->uf_obtenervalor("chkaplica".$li_i."","0");
				if($li_aplica=="1")
				{
					$lb_valido=$io_nomina->uf_update_periodo($ls_codnom,$ls_codperi,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_nomina->io_sql->commit();
				$li_totrows=0;
				$io_nomina->io_mensajes->message("El Período fue establecido.");
			}
			else
			{
				$io_nomina->io_sql->rollback();
				$io_nomina->io_mensajes->message("Ocurrio un error al establecer el período.");
				$lb_valido=$io_nomina->uf_load_periodo_establecer($ls_codnom,$li_totrows,$lo_object);
			}
			break;
	}
	$io_nomina->uf_destructor();
	unset($io_nomina);	
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="432" height="20" bgcolor="#E7E7E7"><span class="Estilo1">Sistema de N&oacute;mina</span></td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
      </table></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_nominas.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<div align="center">
  <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td><input name="txtdesnom" type="text" class="sin-borde2" id="txtdesnom" value="<?php print $ls_desnom;?>" size="60" readonly>
            <input name="txtcodnom" type="hidden" id="txtcodnom" value="<?php print $ls_codnom;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" class="titulo-ventana">Establecer Per&iacute;odo </td>
        </tr>
    <tr>
      <td width="425" height="70" valign="top"><p>&nbsp;</p>
            <div align="center">
              <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
              <input name="operacion" type="hidden" id="operacion">
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows ?>">
          </div></td>
    </tr>
  </table>
</div>
</form>
</body>
<script language="javascript">
function ue_guardar()
{
	f=document.form1;
	lb_valido=false;
	li_total=f.totalfilas.value;
	marcados=0;
	for(li_i=1;(li_i<=li_total);li_i++)
	{
		lb_valido=eval("f.chkaplica"+li_i+".checked");
		if(lb_valido)
		{
			marcados=marcados+1;
		}
	}
	if(marcados>0)
	{
		if(marcados>1)
		{
			alert("Debe Seleccionar solo un periodo a establecer.");
		}
		else
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_establecer_periodo.php";
			f.submit();		
		}
	}
	else
	{
		alert("Debe Seleccionar un periodo a establecer.");
	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	codnom=f.txtcodnom.value;
	f.action="sigesp_snorh_d_nominas.php?codnom="+codnom;
	f.submit();
}
</script>
<?php
if (($ls_operacion=="GUARDAR")&&($lb_valido))
{
	print "<script language=JavaScript>";
	print "   ue_volver();";
	print "</script>";
}
?>		  
</html>