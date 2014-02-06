// JavaScript Document
function pintarBarra(botones)
{
	var imagenes = '../../../shared/imagenes/';
	
	if (botones.indexOf('n', 0) != -1)
      document.write("<a href='javascript:ue_nuevo();'><img src='"+imagenes+"nuevo.gif' width='20' height='20'></a>");	
    if (botones.indexOf('g', 0) != -1)
	  document.write("<a href='javascript:ue_guardar();'><img src='"+imagenes+"grabar.gif' width='20' height='20'></a>");	
    if (botones.indexOf('b', 0) != -1)
	  document.write("<a href='javascript:ue_buscar();'><img src='"+imagenes+"buscar.gif' width='20' height='20'></a>");
    if (botones.indexOf('e', 0) != -1)
	  document.write("<a href='javascript:ue_eliminar();'><img src='"+imagenes+"eliminar.gif' width='20' height='20'></a>");
    if (botones.indexOf('c', 0) != -1)
	  document.write("<a href='javascript:ue_cancelar();'><img src='"+imagenes+"deshacer.gif' width='20' height='20'></a>");
    if (botones.indexOf('s', 0) != -1)
	  document.write("<a href='javascript:ue_salir();'><img src='"+imagenes+"salir.gif' width='20' height='20'></a>");
}

function ue_salir()
{
	location.href = "sej_window_blank.html";
}