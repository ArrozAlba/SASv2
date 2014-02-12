<?php
session_start();

if(array_key_exists("tipo",$_GET))
	{
	  $ls_tipo=$_GET["tipo"];	  
	}

if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{
	  $ls_ejecucion="";
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
<title>Cat&aacute;logo de Ganadores de Concurso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_cat_ganadores_concurso.js"></script>



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
	   
</p>
  <table width="521" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Ganadores de Concurso </td>
    </tr>
  </table>
  <br>
    <table width="521" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="151">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      
	  <tr>
        <td><div align="right">C&oacute;digo Concurso</div></td>
        <td height="22" colspan="3"><div align="left">          <input name="txtcodcon" type="text" id="txtcodcon" size=16 readonly > <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Concurso" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
            <input name="txtdescon" type="text" id="txtdescon" size=40 class="sin-borde" readonly >
        </div></td>
      </tr>
	

  </tr>
 
     <tr class="formato-blanco"> 
		 <td height="28"><div align="right">Fecha Registro Desde</div></td>
		  <td height="28" colspan="2" valign="middle"><input name="txtfechades" type="text" id="txtfechades"  onChange="javascript: Buscar()" value="<?php print ($ldt_fecdes)?>" size="16"   style="text-align:center" readonly >
		  <input name="reset" type="reset" onclick="return showCalendar('txtfechades', '%d/%m/%Y');" value=" ... " /> </td>
	  </tr>
		   <tr>
		   <tr class="formato-blanco"> 
		 <td height="28"><div align="right">Fecha Registro Hasta</div></td>
		  <td height="28" colspan="2" valign="middle"><input name="txtfechahas" type="text" id="txtfechahas"  onChange="javascript: Buscar()"   value="<?php print ($ldt_fechas)?>" size="16"   style="text-align:center" readonly >
		  <input name="reset" type="reset" onclick="return showCalendar('txtfechahas', '%d/%m/%Y');" value=" ... " /> </td>
		  </tr>
		  
   
    <tr>
         <td><input name="txtfechas2" type="hidden" id="txtfechas2"  value="<?php print ($ldt_fechas)?>" readonly >
		    <input name="txtfecdes2" type="hidden" id="txtfecdes2"  value="<?php print ($ldt_fecdes)?>" readonly >
			<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
			<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
			<input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="1">
            <input name="hidcontrol" type="hidden" id="hidcontrol" value="">
		</td>
   
    <td width="161"><div align="right"><a href="javascript: Limpiar_busqueda(); "> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
    <td width="209"><div align="right"><a href="javascript: Buscar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
  </tr>
  </table>
<div align="center" id="mostrar" class="oculto1"></div>  
 
    <table width="521" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      
      <tr>
        <td width="521" bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
       <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td height="19" align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="600" style="background-position:center"></div></td>
      </tr>
	   
	  
  </table>
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

</html>
