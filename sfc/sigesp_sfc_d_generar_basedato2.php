<?php
session_start();
set_time_limit (600);
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Generar Respaldo de Base de Datos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<meta http-equiv="imagetoolbar" content="no" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />


<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<style>
<!--
#bar, #barbackground{
position:absolute;
left:0;
top:0;
background-color:blue;
}

#barbackground{
background-color:black;
}

-->
</style>

<script language="JavaScript1.2">

/*
Dynamic Progress Bar- By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/

//1) Set the duration for the progress bar to complete loading (in seconds)
var duration=5

//2) Set post action to carry out inside function below:
function postaction(){
//Example action could be to navigate to a URL, like following:
//window.location="http://www.dynamicdrive.com"
}


///Done Editing/////////////
var clipright=0
var widthIE=0
var widthNS=0

function initializebar(){
if (document.all){
baranchor.style.visibility="visible"
widthIE=bar.style.pixelWidth
startIE=setInterval("increaseIE()",50)
}
if (document.layers){
widthNS=document.baranchorNS.document.barbackgroundNS.clip.width
document.baranchorNS.document.barNS.clip.right=0
document.baranchorNS.visibility="show"

startNS=setInterval("increaseNS()",50)
}
}

function increaseIE(){
bar.style.clip="rect(0 "+clipright+" auto 0)"
window.status="Loading..."
if (clipright<widthIE)
clipright=clipright+(widthIE/(duration*20))
else{
window.status=''
clearInterval(startIE)
postaction()
}
}

function increaseNS(){
if (clipright<202){
window.status="Loading..."
document.baranchorNS.document.barNS.clip.right=clipright
clipright=clipright+(widthNS/(duration*20))
}
else{
window.status=''
clearInterval(startNS)
postaction()
}
}


window.onload=initializebar
</script>


</head>
<body link="#006699" vlink="#006699" alink="#006699" >

 <script language="javascript" src="timerbar.js">

/*
Time-based progress bar- By Brian Gosselin at http://scriptasylum.com/bgaudiodr
Featured on DynamicDrive.com
For full source, visit http://www.dynamicdrive.com
*/

</script>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="493" height="20" class="cd-menu">&nbsp;</td>
    <td width="285" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">
	<a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
           <p>&nbsp;</p>
          

<div id="resultados" align="center">


<form name="form1" method="post" action="">
  <table width="520" height="108" border="0" cellpadding="0" cellspacing="0" class="contorno">
  <tr height="200">
  <td width="492" height="100" align="center" >
  
   <table width="480" border="0" cellpadding="0" cellspacing="0" class="contorno">
               <tr class="titulo-celdanew">
                 <td width="480" height="22" class="titulo-celdanew">Respaldo de Base de datos</td>
               </tr>
               
              
                 <tr>
				  <td colspan="3" align="center"><div align="left"></div></td>
    			</tr>
	<tr>			
 	<td height="83" colspan="3" align="center">
	
	 <table width="465"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                
		<a href="javascript:generarbasedato();"><img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Generar Base de Datos</a>
      
	  </table>
	   </td>
	   </tr>
    
  
      <tr>
                 <td height="13"><div align="right"></div></td>
      </tr>
    </table>
	</td>
	</tr>
    </table>
 
    <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">  
  </form> 
</div>
	     
               <?php



require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");



$io_in      = new sigesp_include();
$con        = $io_in->uf_conectar();
$io_ds      = new class_datastore();

$io_msg     = new class_mensajes();
$io_funcion = new class_funciones(); 
$la_emp     = $_SESSION["la_empresa"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	
   }
else
   {
	 $ls_operacion="";	
   }
  
  

 
 if($ls_operacion=="generar_bd")
	{
	

	  //print "Paso";
		echo shell_exec('/var/script/./respaldo_base.sh');
		
		?>
		<script language="JavaScript1.2">
if (document.all){
document.write('<div id="baranchor" style="position:relative;width:200px;height:20px;visibility:hidden;">')
document.write('<div id="barbackground" style="width:200px;height:20px;z-index:9"></div>')
document.write('<div id="bar" style="width:200px;height:20px;z-index:10"></div>')
document.write('</div>')
}

</script>
<ilayer name="baranchorNS" visibility="hide" width=200 height=20>
<layer name="barbackgroundNS" bgcolor=black width=200 height=20 z-index=10 left=0 top=0></layer>
<layer name="barNS" bgcolor=blue width=200 height=20 z-index=11 left=0 top=0></layer>
</ilayer>
		<?
		
}

?>
 
</body>
<script language="JavaScript">
function generarbasedato()
{
			f=document.form1;
			f.operacion.value="generar_bd";
			
			
			f.action="sigesp_sfc_d_generar_basedato.php";
			f.submit();
			
	    
}






 function inicio(){

var loadedcolor='darkgray' ;       // PROGRESS BAR COLOR
var unloadedcolor='lightgrey';     // COLOR OF UNLOADED AREA
var bordercolor='navy';            // COLOR OF THE BORDER
var barheight=20;                  // HEIGHT OF PROGRESS BAR IN PIXELS
var barwidth=300;                  // WIDTH OF THE BAR IN PIXELS
var waitTime=5;                   // NUMBER OF SECONDS FOR PROGRESSBAR

// THE FUNCTION BELOW CONTAINS THE ACTION(S) TAKEN ONCE BAR REACHES 100%.
// IF NO ACTION IS DESIRED, TAKE EVERYTHING OUT FROM BETWEEN THE CURLY BRACES ({})
// BUT LEAVE THE FUNCTION NAME AND CURLY BRACES IN PLACE.
// PRESENTLY, IT IS SET TO DO NOTHING, BUT CAN BE CHANGED EASILY.
// TO CAUSE A REDIRECT TO ANOTHER PAGE, INSERT THE FOLLOWING LINE:
// window.location="http://redirect_page.html";
// JUST CHANGE THE ACTUAL URL OF COURSE :)

var action=function()
{
alert("El Respaldo de la Base de Datos se realizo con Exito!");
//window.location="http://www.dynamicdrive.com
}

//*****************************************************//
//**********  DO NOT EDIT BEYOND THIS POINT  **********//
//*****************************************************//

var ns4=(document.layers)?true:false;
var ie4=(document.all)?true:false;
var blocksize=(barwidth-2)/waitTime/10;
var loaded=0;
var PBouter;
var PBdone;
var PBbckgnd;
var Pid=0;
var txt='';
if(ns4){
txt+='<table border=0 cellpadding=0 cellspacing=0><tr><td>';
txt+='<ilayer name="PBouter" visibility="hide" height="'+barheight+'" width="'+barwidth+'" onmouseup="hidebar()">';
txt+='<layer width="'+barwidth+'" height="'+barheight+'" bgcolor="'+bordercolor+'" top="0" left="0"></layer>';
txt+='<layer width="'+(barwidth-2)+'" height="'+(barheight-2)+'" bgcolor="'+unloadedcolor+'" top="1" left="1"></layer>';
txt+='<layer name="PBdone" width="'+(barwidth-2)+'" height="'+(barheight-2)+'" bgcolor="'+loadedcolor+'" top="1" left="1"></layer>';
txt+='</ilayer>';
txt+='</td></tr></table>';
}else{
txt+='<div id="PBouter" onmouseup="hidebar()" style="position:relative; visibility:hidden; background-color:'+bordercolor+'; width:'+barwidth+'px; height:'+barheight+'px;">';
txt+='<div style="position:absolute; top:1px; left:1px; width:'+(barwidth-2)+'px; height:'+(barheight-2)+'px; background-color:'+unloadedcolor+'; font-size:1px;"></div>';
txt+='<div id="PBdone" style="position:absolute; top:1px; left:1px; width:0px; height:'+(barheight-2)+'px; background-color:'+loadedcolor+'; font-size:1px;"></div>';
txt+='</div>';
}

document.write(txt);

function incrCount(){
window.status="Loading...";
loaded++;
if(loaded<0)loaded=0;
if(loaded>=waitTime*10){
clearInterval(Pid);
loaded=waitTime*10;
setTimeout('hidebar()',100);
}
resizeEl(PBdone, 0, blocksize*loaded, barheight-2, 0);
}

function hidebar(){
clearInterval(Pid);
window.status='';
//if(ns4)PBouter.visibility="hide";
//else PBouter.style.visibility="hidden";
action();
}

//THIS FUNCTION BY MIKE HALL OF BRAINJAR.COM
function findlayer(name,doc){
var i,layer;
for(i=0;i<doc.layers.length;i++){
layer=doc.layers[i];
if(layer.name==name)return layer;
if(layer.document.layers.length>0)
if((layer=findlayer(name,layer.document))!=null)
return layer;
}
return null;
}

function progressBarInit(){
PBouter=(ns4)?findlayer('PBouter',document):(ie4)?document.all['PBouter']:document.getElementById('PBouter');
PBdone=(ns4)?PBouter.document.layers['PBdone']:(ie4)?document.all['PBdone']:document.getElementById('PBdone');
resizeEl(PBdone,0,0,barheight-2,0);
if(ns4)PBouter.visibility="show";
else PBouter.style.visibility="visible";
Pid=setInterval('incrCount()',95);
}

function resizeEl(id,t,r,b,l){
if(ns4){
id.clip.left=l;
id.clip.top=t;
id.clip.right=r;
id.clip.bottom=b;
}else id.style.width=r+'px';
}


window.onload=progressBarInit;

}

</script>
</html>