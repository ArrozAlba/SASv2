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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_isr.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $li_porisrper, $la_porisr, $la_bloqueado, $ls_operacion, $ls_existe,$io_fun_nomina;
		
		$li_porisrper=0;
		$la_porisr[1]=0;
		$la_porisr[2]=0;
		$la_porisr[3]=0;
		$la_porisr[4]=0;
		$la_porisr[5]=0;
		$la_porisr[6]=0;
		$la_porisr[7]=0;
		$la_porisr[8]=0;
		$la_porisr[9]=0;
		$la_porisr[10]=0;
		$la_porisr[11]=0;
		$la_porisr[12]=0;
		$la_bloqueado[1]="";
		$la_bloqueado[2]="";
		$la_bloqueado[3]="";
		$la_bloqueado[4]="";
		$la_bloqueado[5]="";
		$la_bloqueado[6]="";
		$la_bloqueado[7]="";
		$la_bloqueado[8]="";
		$la_bloqueado[9]="";
		$la_bloqueado[10]="";
		$la_bloqueado[11]="";
		$la_bloqueado[12]="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
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
<title >Definici&oacute;n de Impuesto sobre la Renta</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<?php 
	require_once("sigesp_snorh_c_isr.php");
	$io_isr=new sigesp_snorh_c_isr();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			for($li_i=1;$li_i<13;++$li_i)
			{
				$ls_codisr=$li_i."";
				if(strlen($ls_codisr)==1)
				{
					$ls_codisr="0".$ls_codisr;
				}
				$porisr=0;
				$lb_valido=$io_isr->uf_insert_defecto($ls_codper,$ls_codisr,$porisr);
			}
			$lb_valido=$io_isr->uf_load_isr($ls_codper,$ls_existe,$li_porisrper,$la_porisr,$li_codconret,$la_bloqueado);
			break;

		case "GUARDAR":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$li_porisrper=$_POST["txtporisrper"];
			$li_codconret=$_POST["txtcodconret"];
			$io_isr->io_sql->begin_transaction();		
			$lb_valido=$io_isr->uf_update_global($ls_codper,$li_porisrper,$la_seguridad);
			if ($lb_valido)
			{
				for($li_i=1;$li_i<13;++$li_i)
				{
					$ls_codisr=$li_i."";
					if(strlen($ls_codisr)==1)
					{
						$ls_codisr="0".$ls_codisr;
					}
					$la_porisr[$li_i]=$io_fun_nomina->uf_obtenervalor("txtporisr".$li_i,"");
					if($la_porisr[$li_i]!="")
					{
						$lb_valido=$io_isr->uf_guardar($ls_codper,$ls_codisr,$la_porisr[$li_i],$li_codconret,$la_seguridad);
					}
				}
			}
			if($lb_valido)
			{
				$io_isr->io_sql->commit();
				$io_isr->io_mensajes->message("El isr fue registrado.");
			}
			else
			{
				$io_isr->io_sql->rollback();
				$io_isr->io_mensajes->message("Ocurrio un error al registra el isr.");
			}
			$lb_valido=$io_isr->uf_load_isr($ls_codper,$ls_existe,$li_porisrper,$la_porisr,$ls_codconret,$la_bloqueado);
			break;
	}
	$io_isr->uf_destructor();
	unset($io_isr);
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_personal.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="510" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="460" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4" class="sin-borde2"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Impuesto Sobre la Renta </td>
        </tr>
        <tr>
          <td width="133" height="22"><div align="right"></div></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Monto Global </div></td>
          <td colspan="3"><div align="left">
                <input name="txtporisrper" type="text" id="txtporisrper" value="<?php print $li_porisrper;?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))"> 
            Aplicar a Todos 
            <input name="chkaplicartodos" type="checkbox" class="sin-borde" id="chkaplicartodos" onChange="ue_aplicar();" value="1">
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td><div align="left"></div></td>
          <td>&nbsp;</td>
          <td><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Enero</div></td>
          <td width="94"><div align="left">
              <input name="txtporisr1" type="text" id="txtporisr1" value="<?php print $la_porisr[1];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[1];?>>
          </div></td>
          <td width="85"><div align="right">Febrero</div></td>
          <td width="138"><div align="left">
              <input name="txtporisr2" type="text" id="txtporisr2" value="<?php print $la_porisr[2];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[2];?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Marzo</div></td>
          <td><div align="left">
              <input name="txtporisr3" type="text" id="txtporisr3" value="<?php print $la_porisr[3];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[3];?>>
          </div></td>
          <td><div align="right">Abril</div></td>
          <td><div align="left">
              <input name="txtporisr4" type="text" id="txtporisr4" value="<?php print $la_porisr[4];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[4];?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Mayo</div></td>
          <td><div align="left">
              <input name="txtporisr5" type="text" id="txtporisr5" value="<?php print $la_porisr[5];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[5];?>>
          </div></td>
          <td><div align="right">Junio</div></td>
          <td><div align="left">
              <input name="txtporisr6" type="text" id="txtporisr6" value="<?php print $la_porisr[6];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[6];?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Julio</div></td>
          <td><div align="left">
              <input name="txtporisr7" type="text" id="txtporisr7" value="<?php print $la_porisr[7];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[7];?>>
          </div></td>
          <td><div align="right">Agosto</div></td>
          <td><div align="left">
              <input name="txtporisr8" type="text" id="txtporisr8" value="<?php print $la_porisr[8];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[8];?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Septiembre</div></td>
          <td><div align="left">
              <input name="txtporisr9" type="text" id="txtporisr9" value="<?php print $la_porisr[9];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[9];?>> 
          </div></td>
          <td><div align="right">Octubre</div></td>
          <td><div align="left">
              <input name="txtporisr10" type="text" id="txtporisr10" value="<?php print $la_porisr[10];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[10];?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Noviembre</div></td>
          <td><div align="left">
              <input name="txtporisr11" type="text" id="txtporisr11" value="<?php print $la_porisr[11];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[11];?>>
          </div></td>
          <td><div align="right">Diciembre</div></td>
          <td><div align="left">
              <input name="txtporisr12" type="text" id="txtporisr12" value="<?php print $la_porisr[12];?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $la_bloqueado[12];?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
      	<tr>
			<td height="20" style="text-align:right"><p align="right">Concepto</p></td>
                      <td height="20" colspan="6" style="text-align:left"><input name="txtcodconret" type="text" id="txtcodconret" style="text-align:center" value="<?php print $li_codconret; ?>" size="12" maxlength="10" readonly>
                      <a href="javascript:catalogo_concepto();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Conceptos" width="15" height="15" border="0"></a>
                      <input name="txtdenconret" type="text" class="sin-borde" id="txtdenconret" value="<?php print $ls_denconret; ?>" size="30" readonly>
                      </td>
		</tr>
	  </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_personal.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codper = ue_validarvacio(f.txtcodper.value);
		porisrper = ue_validarvacio(f.txtporisrper.value);
		porisr1 = ue_validarvacio(f.txtporisr1.value);
		porisr2 = ue_validarvacio(f.txtporisr2.value);
		porisr3 = ue_validarvacio(f.txtporisr3.value);
		porisr4 = ue_validarvacio(f.txtporisr4.value);
		porisr5 = ue_validarvacio(f.txtporisr5.value);
		porisr6 = ue_validarvacio(f.txtporisr6.value);
		porisr7 = ue_validarvacio(f.txtporisr7.value);
		porisr8 = ue_validarvacio(f.txtporisr8.value);
		porisr9 = ue_validarvacio(f.txtporisr9.value);
		porisr10 = ue_validarvacio(f.txtporisr10.value);
		porisr11 = ue_validarvacio(f.txtporisr11.value);
		porisr12 = ue_validarvacio(f.txtporisr12.value);
		if ((codper!="")&&(porisrper!="")&&(porisr1!="")&&(porisr2!="")&&(porisr3!="")&&(porisr4!="")&&(porisr5!="")&&(porisr6!="")&&(porisr7!="")&&(porisr8!="")&&(porisr9!="")&&(porisr10!="")&&(porisr11!="")&&(porisr12!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_isr.php";
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
function catalogo_concepto()
{
	f=document.form1;
	pagina="../cfg/cxp/sigesp_cat_conceptosret.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
//	window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_aplicar()
{
	f=document.form1;
	if(f.chkaplicartodos.checked==true)
	{
		for(li_i=1;li_i<=12;li_i++)
		{
			obj=eval("f.txtporisr"+li_i);
			if(obj.disabled==false)
			{
				eval("f.txtporisr"+li_i+".value=f.txtporisrper.value;")
			}
		}
	}
	else
	{
		for(li_i=1;li_i<=12;li_i++)
		{
			obj=eval("f.txtporisr"+li_i);
			if(obj.disabled==false)
			{
				eval("f.txtporisr"+li_i+".value=0;")
			}
		}
	}
}
</script> 
</html>