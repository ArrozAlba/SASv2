<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
	require_once("class_folder/class_funciones_rpc.php");
	$io_rpc=new class_funciones_rpc();
	$ls_reporte=$io_rpc->uf_select_config("RPC","REPORTE","LISTADO_BENEFICIARIOS","sigesp_rpc_rpp_beneficiario.php","C");
	unset($io_rpc);

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
	///////////////// PAGINACION   /////////////////////////////////////////////////////////////////////////////////////////////
	require_once("class_folder/class_funciones_rpc.php");
	$io_fun_rpc=new class_funciones_rpc();
	$li_registros = 5000;
	$ls_codperdes="";
	$ls_codperhas="";
	$li_pagina=$io_fun_rpc->uf_obtenervalor_get("pagina",0); 
	if (!$li_pagina)
	{ 
		$li_inicio = 0; 
		$li_pagina = 1; 
	} 
	else 
	{ 
		$li_inicio = ($li_pagina - 1) * $li_registros; 
	} 
	$li_totpag=0;	
	require_once("class_folder/sigesp_rpc_c_beneficiario.php");
	$io_beneficiorio=new sigesp_rpc_c_beneficiario();
	$ls_valor2=0;
	$io_beneficiorio->uf_buscar_beneficiario($ls_valor2,$li_inicio,$li_registros,$li_totpag,$ls_cedula1,$ls_cedula2);
	if ($ls_valor2<$li_registros)
	{
	  $ls_cedula2="";
	  $ls_cedula1="";
	}		
	///////////////// PAGINACION   /////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Beneficiarios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20" border="0"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
  </tr>
  </table>

</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  
  <table width="391" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="89"></td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="22" colspan="3" align="center">Listado de Beneficiarios</td>
    </tr>
	<tr>
      <td height="19" colspan="3" align="center">&nbsp;</td>
    </tr>
	<tr>
      <td height="33" colspan="3" align="center"><table width="364" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
			<tr>
			  <td colspan="4"><strong>Paginaci&oacute;n del Reporte</strong></td>
			  </tr>
       
				<tr>
				  <td height="22" colspan="3" align="center"><div align="left" class="style14">
				  <?php
					if ($ls_valor2>$li_registros)
					{		
						print "<center>";
						if(($li_pagina - 1) > 0) 
						{
							print "<a href='sigesp_rpc_r_beneficiario.php?&pagina=".($li_pagina-1)."'>< Anterior</a> ";
						}
						for ($li_i=1; $li_i<=$li_totpag; $li_i++)
						{ 
							if ($li_pagina == $li_i) 
							{
								print "<b>".$li_pagina."</b> "; 
							}
							else
							{
								print "<a href='sigesp_rpc_r_beneficiario.php?&pagina=".($li_i)."'>$li_i</a> "; 
							}
						}
						if(($li_pagina + 1)<=$li_totpag) 
						{
							print " <a href='sigesp_rpc_r_beneficiario.php?&pagina=".($li_pagina+1)."'>Siguiente ></a>";
						}
						
						print "</center>";
					}	
					?>
				  
				  </div></td>
				</tr>
			</table>
	</td></tr>	
	<tr>
      <td height="19" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="33" colspan="3" align="center"><table width="364" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"><strong>Intervalo de Beneficiarios</strong></td>
          </tr>
        <tr>
          <td width="60" height="23"><div align="right"><span class="style1 style14">Desde</span></div></td>
          <td width="125"><input name="txtcedula1" type="text" id="txtcedula1" value="<?php print $ls_cedula1 ?>" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogobene();"><img src="../shared/imagebank/tools15/buscar.gif" alt="C&oacute;digos..." width="15" height="15" border="0"  onClick="document.form1.hidrango.value=1"></a></td>
          <td width="65"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td width="114"><input name="txtcedula2" type="text" id="txtcedula2" value="<?php print $ls_cedula2 ?>" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogobene();"><img src="../shared/imagebank/tools15/buscar.gif" alt="C&oacute;digos..." width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"><strong><span class="style14">
            <input name="hidrango" type="hidden" id="hidrango">
            </span></strong></a></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="19" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="38" colspan="3" align="left"><div align="center">
        <table width="364" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="6"><strong>Ordenado Por</strong></td>
            </tr>
          <tr>
            <td width="53"><div align="right">
             
              <input name="radioorden" type="radio" value="0" checked  >
            </div></td>
            <td width="74">C&eacute;dula</td>
            <td width="27"><div align="right">
              <input name="radioorden" type="radio" value="1"  >
            </div></td>
            <td width="68">Nombre</td>
            <td width="44"><div align="center">
              <input name="radioorden" type="radio" value="2">
            </div></td>
            <td width="96">Apellido</td>
			<td width="44"><div align="center">
              <input name="operacion"   type="hidden"   id="operacion2"   >
          </td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"></td>
    </tr>
  </table>
  <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
</form>      
</body>
<script language="JavaScript">
function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for (i=1;i<=total;i++)
	    {
		  cadena_ceros=cadena_ceros+"0";
	    }
	cadena=cadena_ceros+cadena;
 	if (objeto=="txtcedula1")
	   {
	 	 document.form1.txtcedula1.value=cadena;
	   }
	 else
	   {
	     document.form1.txtcedula2.value=cadena;
	   }  
}


function uf_catalogobene()
{
    f=document.form1;
    f.operacion.value="BUSCAR";
    pagina="sigesp_catdin_bene.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=720,height=500,resizable=yes,location=no");
}


function ue_showouput()
{
	f       = document.form1;
	cedula1 = f.txtcedula1.value;
	cedula2 = f.txtcedula2.value;
	if (cedula1<=cedula2)
	{
	if (f.radioorden[0].checked==true)
	   {
	     orden=f.radioorden[0].value;
	   }
	else
	   {
	     if (f.radioorden[1].checked==true)
	        {
	          orden=f.radioorden[1].value;
	        }
	     else
	        {
	          orden=f.radioorden[2].value;
	        } 
	   }     
			reporte=f.reporte.value;
	pagina="reportes/"+reporte+"?hidorden="+orden+"&hidcedula1="+cedula1+"&hidcedula2="+cedula2;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
    }
  else
    {
	  alert("Error en Rango de Cédulas !!!");
	}
}

function ue_imprimir()
{
}
</script>
</html>
