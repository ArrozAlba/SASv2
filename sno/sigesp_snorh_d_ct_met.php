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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_ct_met.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codigo,$ls_denominacion,$ls_cmbmet,$ld_valor,$ld_valordesc,$ls_operacion,$lb_existe,$la_cmbmet,$io_fun_nomina;
		global $ls_unidad,$ls_codcli,$ls_codprod,$ls_punent;
		
		$ls_codigo="";
		$ls_denominacion="";
		$ls_cmbmet="";
		$ld_valor="";
		$ld_valordesc="";
		$la_cmbmet[0]="";
		$la_cmbmet[1]="";
		$la_cmbmet[2]="";
		$la_cmbmet[3]="";
		$la_cmbmet[4]="";
		$la_cmbmet[5]="";
		$la_cmbmet[6]="";
		$la_cmbmet[7]="";	
		$la_cmbmet[8]="";	
		$la_cmbmet[9]="";
		$la_cmbmet[10]="";	
		$la_cmbmet[11]="";
		$la_cmbmet[12]="";
		$ls_unidad=" disabled";		
		$ls_codcli="";
		$ls_codprod="";
		$ls_punent="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$lb_existe=$io_fun_nomina->uf_obtenerexiste();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_denominacion,$ls_cmbmet,$ld_valor,$ld_valordesc,$ls_codcli,$ls_codprod,$ls_punent;
		
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_cmbmet=$_POST["cmbmet"];
		$ld_valordesc=$_POST["txtvalordesc"];
		$ld_valor=$_POST["txtvalor"];
		$ls_codcli=$_POST["txtcodcli"];
		$ls_codprod=$_POST["txtcodprod"];
		$ls_punent=$_POST["txtpunent"];
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
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
	require_once("sigesp_snorh_c_ct_met.php");
	$io_cestaticket = new sigesp_snorh_c_ct_met();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_cestaticket->uf_guardar($lb_existe,$ls_codigo,$ls_denominacion,$ld_valor,$ls_cmbmet,$ls_codcli,
			                                       $ls_codprod,$ls_punent,$ld_valordesc,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9-10-11-12",$ls_cmbmet,$la_cmbmet,12);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_cestaticket->uf_delete_ct($ls_codigo,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9-10-11-12",$ls_cmbmet,$la_cmbmet,12);
			}
			break;
			
		case "BUSCAR":
			$ls_codigo=$_POST["txtcodcestic"];
			$lb_valido=$io_cestaticket->uf_load_ct($lb_existe,$ls_codigo,$ls_denominacion,$ld_valor,$ls_cmbmet,$ls_codcli,
			                                       $ls_codprod,$ls_punent,$ld_valordesc);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9-10-11-12",$ls_cmbmet,$la_cmbmet,12);
			$ls_unidad="";
			break;
		
	}
	$io_cestaticket->uf_destructor();
	unset($io_cestaticket);
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
        </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif"  title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
  <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
		  <p>&nbsp;</p>
		  <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
            <tr class="titulo-ventana">
              <td height="20" colspan="2"><div align="center">Definici&oacute;n de Cesta Tickets</div></td>
            </tr>
            <tr >
              <td height="22">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="122" height="22"><div align="right" >
                  <p>Codigo</p>
              </div></td>
              <td width="456"><div align="left" >
                  <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,2);">
              </div></td>
            </tr>
            <tr >
              <td height="22"><div align="right">Denominaci&oacute;n</div></td>
              <td><div align="left">
                  <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion?>" size="70" onKeyUp="ue_validarcomillas(this);" >
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Valor</div></td>
              <td><div align="left">
                  <input name="txtvalor" type="text" id="txtvalor"  style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php  print $ld_valor ?>" size="22" maxlength="23">
              </div></td>
            </tr>
			<tr>
              <td height="22"><div align="right">Valor Diario Descuento</div></td>
              <td><div align="left">
                  <input name="txtvalordesc" type="text" id="txtvalordesc"  style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php  print $ld_valordesc ?>" size="22" maxlength="23">
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Metodo</div></td>
              <td><div align="left">
                  <select name="cmbmet" id="cmbmet">
                    <option value=" ">--Seleccione--</option>
                    <option value="1" <?php print $la_cmbmet[0];?>>Accord Ticket Univalor</option>
                    <option value="2" <?php print $la_cmbmet[1];?>>Accord Tarjeta</option>
                    <option value="3" <?php print $la_cmbmet[2];?>>Cesta Casa</option>
                    <option value="4" <?php print $la_cmbmet[3];?>>Valeven Ticket</option>
                    <option value="5" <?php print $la_cmbmet[4];?>>Sodexho Tarjeta</option>
                    <option value="6" <?php print $la_cmbmet[5];?>>Sodexho Ticket</option>
                    <option value="7" <?php print $la_cmbmet[6];?>>Banco Industrial Electronico</option>
                    <option value="8" <?php print $la_cmbmet[7];?>>Accord Ticket Multivalor</option>
					<option value="9" <?php print $la_cmbmet[8];?>>Valeven Tarjeta</option>
					<option value="10" <?php print $la_cmbmet[9];?>>IPSFA</option>
					<option value="11" <?php print $la_cmbmet[10];?>>Todo Ticket Tarjeta</option>
					<option value="12" <?php print $la_cmbmet[11];?>>EfecTicket</option>
					<option value="13" <?php print $la_cmbmet[12];?>>Sodexho Ticket Plus</option>
                  </select>
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">C&oacute;digo Producto </div></td>
              <td><label>
                <input name="txtcodprod" type="text" id="txtcodprod" size="20" maxlength="15" value="<?php print $ls_codprod;?>">
              </label></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td><input name="operacion" type="hidden" id="operacion">
                <input name="existe" type="hidden" id="existe" value="<?php print $lb_existe;?>">
                <input name="txtcodcli" type="hidden" id="txtcodcli" size="20" maxlength="15" value="<?php print $ls_codcli;?>">
                <input name="txtpunent" type="hidden" id="txtpunent" size="20" maxlength="15" value="<?php print $ls_punent;?>"></td>
            </tr>
            <tr>
              <td height="22"><div align="right"></div></td>
              <td><label>
                <input name="btnunidad" type="button" class="boton" id="btnunidad" value="Unidades Administrativas" onClick="javascript: ue_unidadadministrativa();" <?php print $ls_unidad;?>>
              </label></td>
            </tr>
          </table>
		  <p>&nbsp;</p></td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE"
		f.action="sigesp_snorh_d_ct_met.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codigo = ue_validarvacio(f.txtcodigo.value);
		denominacion = ue_validarvacio(f.txtdenominacion.value);
		valor = ue_validarvacio(f.txtvalor.value);
		metodo = ue_validarvacio(f.cmbmet.value);
		if ((codigo!="")&&(denominacion!="")&&(valor!="")&&(metodo!=""))
		{
			f.operacion.value ="GUARDAR";
			f.action="sigesp_snorh_d_ct_met.php";
			f.submit();
		}
		else
		{
			alert("Debe llenar todos los datos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.operacion.value ="ELIMINAR";
				f.action="sigesp_snorh_d_ct_met.php";
				f.submit();
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_ct.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_unidadadministrativa()
{
	f=document.form1;
	codcestic=ue_validarvacio(f.txtcodigo.value);
	dencestic=ue_validarvacio(f.txtdenominacion.value);
	location.href="sigesp_snorh_d_ct_unid.php?codcestic="+codcestic+"&dencestic="+dencestic+"";
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_cestaticket.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

</script>
</html>