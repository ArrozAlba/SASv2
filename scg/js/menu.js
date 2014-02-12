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

// Menú Principal- Archivo
stm_ai("p0i0",[0," Procesos ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);

// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Comprobante Contable ","","",-1,-1,0,"sigespwindow_scg_comprobante.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Comprobante Cierre de Ejercicio ","","",-1,-1,0,"sigespwindow_scg_cmp_cierre.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"Programación de reportes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i5","p1i0",[0," Mensual  ","","",-1,-1,0,"sigesp_scg_wproc_progrep.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Trimestral  ","","",-1,-1,0,"sigesp_scg_wproc_progrep_trim.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Programación de Reportes OAF","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel

stm_aix("p1i5","p1i0",[0," Mensual  ","","",-1,-1,0,"sigesp_scg_wproc_prog_oaf.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Trimestral  ","","",-1,-1,0,"sigesp_scg_wproc_prog_oaf_trim.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0," Mayor Analitico ","","",-1,-1,0,"sigesp_scg_r_mayor_analitico.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0," Balance de Comprobaci&oacute;n ","","",-1,-1,0,"sigesp_scg_r_balance_comprobacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i2",[0," Comprobantes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p2","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Formato 1  ","","",-1,-1,0,"sigesp_scg_r_comprobante_formato1.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Formato 2  ","","",-1,-1,0,"sigesp_scg_r_comprobante_formato2.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i2","p1i0",[0," Estado de Resultado ","","",-1,-1,0,"sigesp_scg_r_estado_resultado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0," Balance General ","","",-1,-1,0,"sigesp_scg_r_balance_general.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Listado de Cuentas ","","",-1,-1,0,"sigesp_scg_r_cuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Movimientos del Mes ","","",-1,-1,0,"sigesp_scg_r_movimientos_mes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_ep();
stm_aix("p1i2","p1i2",[0," Comparados ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p2","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i2","p1i2",[0," Instructivo 04 ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p2","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Estado de Resultado Forma 0406  ","","",-1,-1,0,"sigesp_scg_r_comparados_est_resultado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i2","p1i2",[0," Instructivo 07 ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p2","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Inversiones  Forma 0714","","",-1,-1,0,"sigesp_scg_r_comparados_forma0714.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Balance General Forma 0718","","",-1,-1,0,"sigesp_scg_r_comparados_balance_general.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Origen y Aplicación de Fondos Forma 0719","","",-1,-1,0,"sigesp_scg_r_comparados_oaf.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();
stm_aix("p1i2","p1i2",[0," Consolidados ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p2","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Balance General          ","","",-1,-1,0,"sigesp_scg_r_balance_general_consolidado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Estado de Resultado      ","","",-1,-1,0,"sigesp_scg_r_estado_resultado_consolidado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Balance de Comprobación  ","","",-1,-1,0,"sigesp_scg_r_balance_comprobacion_consolidado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i3","p0i0",[0," Configuración "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0," Configuración Origen y Aplicación de Fondos ","","",-1,-1,0,"sigesp_scg_ctas_oaf.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

stm_aix("p4i0","p1i0",[0," Ir a M&oacute;dulos  ","","",-1,-1,0,"../index_modules_administracion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
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
