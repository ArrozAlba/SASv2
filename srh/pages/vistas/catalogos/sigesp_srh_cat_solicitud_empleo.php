<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ 
	  $ls_ejecucion=$_GET["valor_cat"];
	}
	else
	{
	  $ls_ejecucion="";
	}
	if(array_key_exists("tipo",$_GET))
	{
	  $ls_tipo=$_GET["tipo"];	  
	}
	else
	{
	  $ls_tipo="";
	}
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
$ldt_fecdes="01/01/".$li_ano;
$ldt_fechas=date("d/m/Y");
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Solicitud de Empleo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_cat_solicitud_empleo.js"></script>


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

</head>

<body onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">

    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print trim($ls_ejecucion)?>">
    <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print trim($ls_tipo)?>"> 

</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Solicitud de Empleo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="140"><div align="right">N&uacute;mero Solicitud</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtnrosol" type="text" id="txtnrosol" onKeyUp="javascript: ue_validarnumero(this);" >
        </div></td>
      </tr>
      
      
        <tr class="formato-blanco"> 
 <td height="28"><div align="right">Fecha Solicitud Desde</div></td>
  <td width="142" height="28" valign="middle"><input name="txtfecsoldes" type="text" id="txtfecsoldes"    size="16"   style="text-align:center" value="<?php print ($ldt_fecdes)?>" readonly >
  <input name="reset" type="reset" onclick="return showCalendar('txtfecsoldes', '%d/%m/%Y');" value=" ... " /> </td>
  </tr>
   <tr class="formato-blanco"> 
 <td height="28"><div align="right">Fecha Solicitud Hasta</div></td>
  <td width="142" height="28" valign="middle"><input name="txtfecsolhas" type="text" id="txtfecsolhas"    size="16"   style="text-align:center" value="<?php print ($ldt_fechas)?>" readonly >
  <input name="reset" type="reset" onclick="return showCalendar('txtfecsolhas', '%d/%m/%Y');" value=" ... " /> </td>
  </tr>
   <tr>
        <td><div align="right">C&eacute;dula Solicitante</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtcedper" type="text" id="txtcedper"   onKeyUp="javascript: ue_validarnumero(this);" >
        </div></td>
      </tr>

  </tr>
   <tr>
        <td><div align="right">Nombre Solicitante</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnomper" type="text" id="txtnomper" size=40  >
        </div></td>
      </tr>
 
  </tr>
   <tr class="formato-blanco"> 
  <td><div align="right">Apellido Solicitante</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtapeper" type="text" id="txtapeper" size=40  >
        </div></td>
         <td width="12">&nbsp;</td>
  </tr>
    
     <tr>
        <td><input name="txtfecsolhas2" type="hidden" id="txtfecsolhas2"  value="<?php print ($ldt_fechas)?>" readonly >
		    <input name="txtfecsoldes2" type="hidden" id="txtfecsoldes2"  value="<?php print ($ldt_fecdes)?>" readonly >
		</td>
            </tr>
			<tr>
    <td>&nbsp;</td>
   
    <td width="142"><div align="right"><a href="javascript: Limpiar_busqueda();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="206"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
    
  </tr>
    </table>
<div align="center" id="mostrar" class="oculto1"></div>  
 
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      
      <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
       <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="800" style="background-position:center"></div></td>
      </tr>
	  
    </table>
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

</html>