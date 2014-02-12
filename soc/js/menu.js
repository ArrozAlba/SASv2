// stm_aix("p1i2","p1i0",[0,"Opción 2    ","","",-1,-1,0,""]);
// stm_aix("p1i0","p0i0",[0,"Opción 1    ","","",-1,-1,0,"tablas.htm","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

//-----------------------//
// Línea de separación
// Para inlcuir líneas de separación entre las opciones, incoporar la siguiente instrucción, entre las opciones a separar
// stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

//-----------------------//
// Menúes de Tercer Nivel
// Para hacer submenúes, incluir las siguientes líneas de código
// stm_bpx("pn","p1",[1,4,0,0,2,3,6,7]);   debajo de la línea de código de la opción principal stm_aix("p0in","p0i0",[0," Opción Menú "]);
// luego, buscar la opción del menú bajo la cual se abrirá el submenú y agregar al final de esa línea de código, los siguientes atributos:
// ,"","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
// y justo debajo de esa línea agregar las siguientes líneas de código.
// stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
// stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// stm_aix("p3i2","p3i0",[0,"  Menu Item 3  "]);
// stm_aix("p3i3","p3i0",[0,"  Menu Item 4  "]);
// stm_aix("p3i4","p3i0",[0,"  Menu Item 5  "]);
// stm_ep();
// Luego cambiar las opciones "Menu Item 5", por el nombre de la opción que corresponda en cada caso.

//-----------------------//
// Hipervínculos
// Para incluir los enlaces correspondientes a cada opción del menú, se procede de la siguiente manera:
// En aquellas intrucciones, cuyo código es similare a esto:
// stm_aix("p1i0","p0i0",[0,"Opción 1    ","","",-1,-1,0,"","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
// agregar el enlace dentro de las comillas, justo delante de "_self"
// En aquellas intrucciones, cuyo código es similare a esto:
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// agregar al final de esta línea de código, los siguientes parámetros:
// ,"","",-1,-1,0,"","_self","","","","",0]);
// y luego incorporar el enlace en las comillas que está justo antes de "_self"

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,200,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);
// Menú Principal- Cotizaciones
stm_ai("p0i0",[0,"   Cotizaciones   ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
stm_aix("p1i0","p0i0",[0," Solicitud de Cotizaci&oacute;n    ","","",-1,-1,0,"sigesp_soc_p_solicitud_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Registro de Cotizaci&oacute;n     ","","",-1,-1,0,"sigesp_soc_p_registro_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Análisis de Cotizaciones     ","","",-1,-1,0,"sigesp_soc_p_analisis_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Aprobación de Análisis de Cotizaciones  ","","",-1,-1,0,"sigesp_soc_p_aprobacion_analisis_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Generación de Ordenes de Compra    ","","",-1,-1,0,"sigesp_soc_p_generar_orden_analisis.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
// Menú Principal- Orden de Compra

stm_aix("p1i0","p0i0",[0," Orden de Compra     ","","",-1,-1,0,"","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"    Registro de Ordenes de Compra ","","",-1,-1,0,"sigesp_soc_p_registro_orden_compra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"    Aprobaci&oacute;n de Ordenes de Compra ","","",-1,-1,0,"sigesp_soc_p_aprobacion_orden_compra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"    Anulaci&oacute;n de Ordenes de Compra  ","","",-1,-1,0,"sigesp_soc_p_anulacion_orden_compra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"    Aceptaci&oacute;n/Reverso de Servicios   ","","",-1,-1,0,"sigesp_soc_p_aceptacion_servicio.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0,"   Reportes   "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"  Orden de Compra   ","","",-1,-1,0,"sigesp_soc_r_orden_compra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Solicitud de Cotizaciones   ","","",-1,-1,0,"sigesp_soc_r_solicitud_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Registro  de Cotizaciones   ","","",-1,-1,0,"sigesp_soc_r_registro_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  An&aacute;lisis  de Cotizaciones   ","","",-1,-1,0,"sigesp_soc_r_analisis_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Acta de Aceptaci&oacute;n de Servicios   ","","",-1,-1,0,"sigesp_soc_r_aceptacion_servicios.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Ubicacion de Orden de Compra   ","","",-1,-1,0,"sigesp_soc_r_orden_ubicacioncompra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);stm_ep();
// Menú Principal - Ayuda
stm_aix("p0i8","p0i0",[0,"   Ayuda   "]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_aix("p1i0","p0i0",[0," Ir a M&oacute;dulos  ","","",-1,-1,0,"../index_modules_comp_alm_act.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_em();


function A()
{
	window.onerror=B
	window.opener.focus();
	window.focus();
}
function B()
{
	var url = document.location.href;
    partes = url.split('/');
    pagina=partes[partes.length-1];
	sistema=partes[partes.length-2];
	alert("No ha iniciado sesión para esta ventana");
	location.href=url.replace(sistema+"/"+pagina,"pagina_blanco.php");
	return true;
} 
A();