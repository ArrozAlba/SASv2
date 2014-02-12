<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="SIGESP_SCG/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<?
include("grid_param.php");
$class_grid=new grid_param();

if(array_key_exists("operacion",$_POST))
{
	$operacion=$_POST["operacion"];
	$total=$_POST["totrows"];//Total de filas del grid, se guarda en el oculto totrows
}
else
{
	$operacion="";
	$total=1;   //Total de filas inicial del grid
}
	  //Titulos de la tabla
	  $title[1]="Cuenta"; $title[2]="Denominacion"; 
	  $name="grid1";
	  $titletable="Prueba";
	  $widthtable="500";
	  $object[1][1]="<input name=txtcuenta type=text id=txtcuenta>";
	  $object[1][2]="<select name=select><option value=A>A</option><option value=B>B</option><option value=C>C</option></select>";

?>

<form name="form1" method="post" action="">
  <div align="center">
    <p><a href="javascript: uf_cambio()"><img src="imagebank/tools/nuevo.gif" alt="Insertar fila" width="15" height="15" border="0"></a>
       <a href="javascript: uf_cambio();">Agregar Fila</a><?
		  
	  if($operacion=="")
	  {
		  $class_grid->makegrid($total,$title,$object,$widthtable,$titletable,$name);
	  }	
		
	  
   ?>
    </p>
    <p>
<input name="operacion" type="hidden" id="operacion">      
<input name="totrows" type="hidden" id="totrows" value="<? print $total;?>">
</p>
    <p>
      

</p>
    <p>
      <input name="Submit" type="button" class="boton" value="Bot&oacute;n" onClick="uf_operacion()">
    <p>       
  </div>
</form>

</body>

<script language="javascript">

	function uf_cambio()
	{
		f=document.form1;
		f.action="pruebadegridapie.php";
		f.operacion.value="AGREGAR";
		f.submit();
	}
    function uf_delete_dt(i)
	{
	 f=document.form1;
	 f.action="pruebadegridapie.php";
	 f.operacion.value="DELETE";
	 grid.deleteRow(i);
	 f.submit();
     }
	 	
	function uf_operacion()
	{
		f=document.form1;
		f.action="pruebadegridapie.php";
		f.operacion.value="GUARDAR";
		f.submit();
	}

	
   function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   } 
  function EvaluateText(cadena, obj){ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f"){ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
 	 if (cadena == "%a") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==45)|| (event.keyCode ==47))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   }
    
</script>
</html>
