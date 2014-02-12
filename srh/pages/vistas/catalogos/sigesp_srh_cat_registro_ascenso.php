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

if (isset($_GET["tipo"]))
{ 
	$ls_tipo= trim ($_GET["tipo"]);	
}
else
{ 
	$ls_tipo="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Registro de Postulados para Ascenso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_cat_registro_ascenso.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_registro_ascenso.js"></script>


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

    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">
	 <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo ?>">
	<input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Registro de Postulados para Ascenso </td>
    </tr>
  </table>
<br>
    <table width="513" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="129">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
	   <tr>
        <td><div align="right">Nro. Registro</div></td>
        <td height="22" colspan="3"><div align="left"> <input name="txtnroreg" type="text" id="txtnroreg" size="16" onKeyUp="javascript:ue_validarnumero(this);">
        </div></td>
      </tr>
     <tr>
        <td><div align="right">C&oacute;digo Concurso</div></td>
        <td width="144" height="22"><div align="left">          <input name="txtcodcon" size="16" type="text" id="txtcodcon" readonly>
        <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Tipos de Documentos" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
       </div></td>
        <td colspan="3"><input name="txtdescon" type="text" class="sin-borde" id="txtdescon"  size="40"  readonly>  </td>
      </tr>           

    
      <tr>
	  <td><input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
			<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
			<input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="1">
            <input name="hidcontrol" type="hidden" id="hidcontrol" value=""></td>      
       <td width="144"><div align="right"><a href="javascript: Limpiar_busqueda(); "><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
       
       <td width="240"  align="center"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga Click aqu&iacute; para Buscar</a></td>
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