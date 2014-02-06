<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<form action="" method="post" name="form1" target="mainFrame">
  <table width="169" border="0" cellpadding="0" cellspacing="0" frame="border">
    <tr>
      <td height="17">&nbsp;</td>
      <td class="titulo-celdanew">Men&uacute; del Sistema </td>
    </tr>
    <tr>
      <td width="14">&nbsp;</td>
      <td width="169">
        <?php


require_once("sigesp_arbol_config.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
	
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql= new class_SQL($con);

	$i=1;
	$ls_sistema="Configuracion";
	$li_total=$gi_total;
	$ls_ventana="";
	$ls_nombresist="N/A";
	print("<table border=0 cellpadding='1' cellspacing=1>");
	print("<tr>");
	print("<td width='16'><input type=hidden name=hidsis$i id=hidsis$i value='$ls_nombresist' ><a id='x".$i."' href=javascript:cambiar('".$i."','$ls_ventana','$ls_sistema',parent.leftFrame.document.form1.hidsis$i);><img src='../shared/imagenes/folder.gif' width='18' height='18' hspace='0' vspace='0' border='0'></a></td>");
	print("<td><b>".$ls_sistema." </b>");
	print("</table>");
	print("<div id='".$i."' style='display: none; margin-left: 2em;'>");

	for($j=1; $j <= $li_total; $j++)
	{
		$ls_nomlog=$arbol["nombre_logico"][$j];
		$li_nivel=$arbol["nivel"][$j];
		$ls_padre=$arbol["padre"][$j];
		$ls_id=$arbol["id"][$j];
		$ls_nomfis=$arbol["nombre_fisico"][$j];
		$li_hijos=$arbol["numero_hijos"][$j];
		if ($ls_padre=="000")
		{	
			if($li_hijos < 0)
			{
				$ls_ventana=$ls_nomlog;
			}
			else
			{
				$ls_ventana="";
			}
			print("<table border=0 cellpadding=1 cellspacing=1>");
			print("<tr>");
			if($li_hijos > 0)
			{
				print("<td width=16><input type=hidden name=hidsis$ls_id id=hidsis$ls_id value='$ls_nombresist' ><a  href=javascript:cambiar('".$i.$ls_id."','$ls_ventana','$ls_sistema',parent.leftFrame.document.form1.hidsis$j);  id='x".$i.$ls_id."'><img src=../shared/imagenes/folder.gif width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
			}
			else
			{
				print("<td width=16><input type=hidden name=oculto$ls_id id=oculto$ls_id value='$ls_nomlog' ><input type=hidden name=oculto1$ls_id id=oculto1$ls_id value='$ls_nombresist' ><a  href=javascript:uf_cambiar('".$i.$ls_id."',document.form1.oculto$j,'$ls_sistema','$ls_nombresist',document.form1.oculto1$j);  id='x".$i.$ls_id."'><img src=../shared/imagenes/empty.png width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
			}
			print("<td><b>".$ls_nomlog." </b>");
			print("</table>");
			print("<div id='".$i.$ls_id."' style='display: none; margin-left: 2em;'>");
		}
		
		if($li_hijos > 0)
		{
			for($k=1; $k <= $li_total; $k++)
			{
				$ls_padre1=$arbol["padre"][$k];
				$ls_id1=$arbol["id"][$k];
				$li_nivel1=$arbol["nivel"][$k];				
				$ls_nomhijo=$arbol["nombre_logico"][$k];
				$ls_nomfis1=$arbol["nombre_fisico"][$k];
  			    $li_hijos1=$arbol["numero_hijos"][$k];
				
				if (($ls_padre1==$ls_id)&&($li_nivel1==1))
				{
					print("<table border=0 cellpadding=1 cellspacing=1>");
					print("<tr>");

					if($li_hijos1 > 0)
					{
						$ls_ventana="";
						print("<td width=16><input type=hidden name=hidsis$ls_id1 id=hidsis$ls_id1 value='$ls_nombresist' ><a  href=javascript:cambiar('".$i.$j.$ls_id1."','$ls_ventana','$ls_sistema',parent.leftFrame.document.form1.hidsis$ls_id1);  id='x".$i.$j.$ls_id1."'><img src=../shared/imagenes/folder.gif width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
					}
					else
					{
						print("<td width=16><input type=hidden name=oculto$ls_id1 id=oculto$ls_id1 value='$ls_nomhijo' ><input type=hidden name=oculto1$ls_id1 id=oculto1$ls_id1 value='$ls_nombresist' ><a  href=javascript:uf_cambiar('".$i.$j.$ls_id1."',document.form1.oculto$ls_id1,'$ls_sistema',document.form1.oculto1$ls_id1,'$ls_nomfis1');  id='x".$i.$j.$ls_id1."'><img src=../shared/imagenes/empty.png width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
					}

					print("<td><b>".$ls_nomhijo." </b>");
					print("</table>");
					print("<div id='".$i.$j.$ls_id1."' style='display: none; margin-left: 2em;'>");

					if($li_hijos1 > 0)
					{
						for($z=1; $z <= $li_total; $z++)
						{
							$ls_padre2=$arbol["padre"][$z];
							$ls_id2=$arbol["id"][$z];
							$li_nivel2=$arbol["nivel"][$z];				
							$ls_nomhijo1=$arbol["nombre_logico"][$z];
							$ls_nomfis2=$arbol["nombre_fisico"][$z];
							$li_hijos2=$arbol["numero_hijos"][$z];
 							if (($ls_padre2==$ls_id1)&&($li_nivel2==2))
							{
								print("<table border=0 cellpadding=1 cellspacing=1>");
								print("<tr>");
								if($li_hijos2 > 0)
								{
									print("<td width=16><input type=hidden name=hidsis$ls_id2 id=hidsis$ls_id2 value='$ls_nombresist' ><a  href=javascript:cambiar('".$i.$j.$k.$ls_id2."','$ls_ventana','$ls_sistema',parent.leftFrame.document.form1.hidsis$ls_id2);  id='x".$i.$j.$k.$ls_id2."'><img src=../shared/imagenes/folder.gif width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
								}
								else
								{
									
									print("<td width=16><input type=hidden name=oculto$ls_id2 id=oculto$ls_id2 value='$ls_nomhijo1' ><input type=hidden name=oculto1$ls_id2 id=oculto1$ls_id2 value='$ls_nombresist' ><a  href=javascript:uf_cambiar('".$i.$j.$k.$ls_id2."',document.form1.oculto$ls_id2,'$ls_sistema',document.form1.oculto1$ls_id2,'$ls_nomfis2');  id='x".$i.$j.$k.$ls_id2."'><img src=../shared/imagenes/empty.png width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
								}
								print("<td><b>".$ls_nomhijo1." </b>");
								print("</table>");
								print("<div id='".$i.$j.$k.$ls_id2."' style='display: none; margin-left: 2em;'>");
					if($li_hijos2 > 0)
					{
						for($x=1; $x <= $li_total; $x++)
						{
							$ls_padre3=$arbol["padre"][$x];
							$ls_id3=$arbol["id"][$x];
							$li_nivel3=$arbol["nivel"][$x];	
							$ls_nomhijo3=$arbol["nombre_logico"][$x];
							$ls_nomfis3=$arbol["nombre_fisico"][$x];
							$li_hijos3=$arbol["numero_hijos"][$x];
 							if (($ls_padre3==$ls_id2)&&($li_nivel3==3))
							{
								print("<table border=0 cellpadding=1 cellspacing=1>");
								print("<tr>");
								if($li_hijos3 > 0)
								{
									print("<td width=16><input type=hidden name=hidsis$ls_id3 id=hidsis$ls_id3 value='$ls_nombresist' ><a  href=javascript:cambiar('".$i.$j.$k.$z.$ls_id3."','$ls_ventana','$ls_sistema',parent.leftFrame.document.form1.hidsis$ls_id3);  id='x".$i.$j.$k.$z.$ls_id3."'><img src=../shared/imagenes/folder.gif width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
								}
								else
								{
									
									print("<td width=16><input type=hidden name=oculto$ls_id3 id=oculto$ls_id3 value='$ls_nomhijo3' ><input type=hidden name=oculto1$ls_id3 id=oculto1$ls_id3 value='$ls_nombresist' ><a  href=javascript:uf_cambiar('".$i.$j.$k.$z.$ls_id3."',document.form1.oculto$ls_id3,'$ls_sistema',document.form1.oculto1$ls_id3,'$ls_nomfis3');  id='x".$i.$j.$k.$z.$ls_id3."'><img src=../shared/imagenes/empty.png width=18 height=18 hspace=0 vspace=0 border=0></a></td>");
								}
								print("<td><b>".$ls_nomhijo3." </b>");
								print("</table>");
								print("<div id='".$i.$j.$k.$z.$ls_id3."' style='display: none; margin-left: 2em;'>");
								print("</div>");
							}
							
						}//end if($li_hijos2 > 0)
					}

								print("</div>");
							}
						}//end if($li_hijos1 > 0)
					}

					print("</div>");  //<div id='".$i.$j.$k.>
				}//if (($ls_padre1==$ls_id)&&($li_nivel1==1))
			}//for($k=1; $k <= $li_total; $k++)
		}//if($li_hijos > 0)
		if($ls_padre=="000")
		{
			print("</div>");
		}
	}//for j
	print("</div>");//Div principal
?>
      </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}


function cambiar(item,ventana,sistema)
 {
   obj=document.getElementById(item);
   visible=(obj.style.display!="none")
   key=document.getElementById("x" + item);
  f=document.form1;
   if (visible) 
   {
     obj.style.display="none";
     key.innerHTML="<img src='../shared/imagenes/folder.gif' width='16' height='16' hspace='0' vspace='0' border='0'>";
     window.open("sigespwindow_blank.php","mainFrame","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   }
   else 
   {
      obj.style.display="block";
      key.innerHTML="<img src='../shared/imagenes/folderopen.gif' width='16' height='16' hspace='0' vspace='0' border='0'>";
   }
}


function uf_cambiar(item,ventana,sistema,nombresist,nombrefis)
 {
   obj=document.getElementById(item);
   visible=(obj.style.display!="none")
   key=document.getElementById("x" + item);
   if (visible) 
      {
        obj.style.display="none";
        window.open(nombrefis,"mainFrame","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
      }
   else 
     {  
       obj.style.display="block";
  	   window.open(nombrefis,"mainFrame","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
     }
}
</script> 
</html>
