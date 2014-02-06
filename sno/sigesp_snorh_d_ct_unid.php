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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_ct_unid.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_denominacion,$ls_est1cestic, $ls_est2cestic, $ls_cmbmet, $ls_operacion,$ls_existe,$io_fun_nomina;
		global $ls_nametable, $lo_title, $li_totrows, $ls_titletable, $li_widthtable;		
		
		$ls_codigo="";
		$ls_denominacion="";
		$ls_est1cestic="";
		$ls_est2cestic="";
		$ls_cmbmet="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Unidades Administrativas";
		$li_widthtable=650;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="Código de Empresa";
		$lo_title[4]="Código de Dirección";
		$lo_title[5]="Asignar";
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables($ai_i)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_est1cestic, $ls_est2cestic, $li_aplica, $io_fun_nomina;
		
		$ls_codigo=$_POST["txtcodigo".$ai_i];
		$ls_est1cestic=$_POST["txtest1cestic".$ai_i];
		$ls_est2cestic=$_POST["txtest2cestic".$ai_i];
		$li_aplica=$io_fun_nomina->uf_obtenervalor("chkaplica".$ai_i,"0");
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
<title>Definici&oacute;n de Cesta Ticket</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_ct_unid.php");
	$io_cestaunidad=new sigesp_snorh_c_ct_unid();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codcestic=$_GET["codcestic"];
			$ls_dencestic=$_GET["dencestic"];
			$lb_valido=$io_cestaunidad->uf_load_unidad($ls_codcestic,$li_totrows,$lo_object);
			break;
			
		case "GUARDAR":
		 	$ls_codcestic=$_POST["txtcodcestic"];
			$ls_dencestic=$_POST["txtdencestic"];
			$lb_valido=true;
			$io_cestaunidad->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_totrows&&$lb_valido;$li_i++)
			{		
				uf_load_variables($li_i);
				if($li_aplica=="1") // Se inserta
				{
					$lb_valido=$io_cestaunidad->uf_guardar($ls_codigo,$ls_codcestic,$ls_est1cestic,$ls_est2cestic,$la_seguridad);
				}
				else // Se elimina
				{
					$lb_valido=$io_cestaunidad->uf_delete_unidad($ls_codigo,$ls_codcestic,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_cestaunidad->io_sql->commit();
				$io_cestaunidad->io_mensajes->message("Las Unidades Administrativas fueron actualizadas.");
			}
			else
			{
				$io_cestaunidad->io_sql->rollback();
				$io_cestaunidad->io_mensajes->message("Ocurrio un error al actualizar las Unidades Administrativas.");
			}
			$lb_valido=$io_cestaunidad->uf_load_unidad($ls_codcestic,$li_totrows,$lo_object);
			break;
	}
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>	 </td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
<div align="center">
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="750" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
		<p>&nbsp;</p>
		<table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="3"><div align="center">
              <input name="txtdencestic" type="text" class="sin-borde2" id="txtdencestic" value="<?php print $ls_dencestic;?>" style="text-align:center" size="60" readonly>
              <input name="txtcodcestic" type="hidden" id="txtcodcestic" value="<?php print $ls_codcestic;?>">
          </div></td>
        </tr>
              <tr class="titulo-ventana">
                <td height="20" colspan="3"><div align="center">Asignaci&oacute;n de Cesta Tickets a la Unidad Administrativa</div></td>
              </tr>
              <tr >
                <td width="127" height="22"><div align="right"></div></td>
                <td width="528"><label>
                  <div align="right">
                    Asignar Todos
                      <input name="chktodos" type="checkbox" class="sin-borde" id="chktodos" value="1" onChange="ue_aplicar();">
                    </div>
                </label></td>
                <td width="37">&nbsp;</td>
              </tr>
			<tr>
			  <td colspan="3"><div align="center">
				<?php
						$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
						unset($io_grid);
				?>
				</div></td>
		    </tr>
            <tr>
              <td height="22"><div align="right"></div></td>
              <td colspan="2"><input name="operacion" type="hidden" id="operacion">
			  	<input name="existe" type="hidden" id="existe">
			    <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
            </tr>
          </table>
          <p>&nbsp;</p></td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value ="GUARDAR";
		f.action="sigesp_snorh_d_ct_unid.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	f.action="sigesp_snorh_d_ct_met.php";
	f.submit();
}
function ue_aplicar()
{
	f=document.form1;
	if(f.chktodos.checked==true)
	{
		total=f.totalfilas.value;
		for(i=1;i<=total;i++)
		{
			eval("f.chkaplica"+i+".checked=true;");
		}
	}
	else
	{
		total=f.totalfilas.value;
		for(i=1;i<=total;i++)
		{
			eval("f.chkaplica"+i+".checked=false;");
		}
	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_cestaticket.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

</script>
</html>