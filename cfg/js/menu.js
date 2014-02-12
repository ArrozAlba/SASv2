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

/*stm_bm(["menu08dd",430,"","imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Archivo
stm_ai("p0i0",[0," Archivo ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Nuevo    ","","",-1,-1,0,"javascript:ue_nuevo()","","","","imagebank/tools20/nuevo.gif","imagebank/tools20/nuevo-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Guardar    ","","",-1,-1,0,"javascript:ue_guardar()","","","","imagebank/tools20/grabar.gif","imagebank/tools20/grabar-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Eliminar   ","","",-1,-1,0,"javascript:ue_eliminar()","","","","imagebank/tools20/eliminar.gif","imagebank/tools20/eliminar-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Buscar   ","","",-1,-1,0,"javascript:ue_buscar()","","","","imagebank/tools20/buscar.gif","imagebank/tools20/buscar-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Cerrar   ","","",-1,-1,0,"sigespwindow_blank.php","","","","imagebank/tools20/salir.gif","imagebank/tools20/salir-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();


// Menú Principal - Definiciones
stm_aix("p0i2","p0i0",[0," Definiciones "]);
stm_bpx("p4","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Deducciones   ","","",-1,-1,0,"sigesp_cxp_w_def_deduc.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i2","p1i0",[0," Otros Créditos ","","",-1,-1,0,"sigesp_cxp_w_def_otroscre.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i4","p1i0",[0," Documentos         ","","",-1,-1,0,"sigesp_cxp_w_def_doc.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i6","p1i0",[0,"Clasificador ","","",-1,-1,0,"sigesp_cxp_w_def_clasi.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"Recepción de Documentos ","","",-1,-1,0,"sigesp_cxp_w_recep_docume.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Aprobación de Recepción de Documentos ","","",-1,-1,0,"sigesp_cxp_w_aprob_rd.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Solicitud de Pagos ","","",-1,-1,0,"sigesp_cxp_w_proc_sol.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i6","p1i0",[0,"Aprobación de Solicitud de Pagos ","","",-1,-1,0,"sigesp_cxp_w_aprob_sol.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i8","p1i0",[0,"Nota de Crédito / Débito ","","",-1,-1,0,"sigesp_cxp_w_NC.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
//Reportes - Opciones de Segundo Nivel
stm_aix("p6i2","p1i0",[0,"Listados    ","","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
stm_bpx("p6","p1",[1,2,0,0,2,3,0]);
// Reportes - Opciones de Tercer Nivel Para Listados
stm_aix("p3i0","p1i0",[0,"  Otros Créditos  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p3i1","p1i0",[0,"  Deducciones  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p3i1","p1i0",[0,"  Documentos  ","","",-1,-1,0,"","_self","","","","",0]);
stm_ep();
stm_ep();


//Menu Principal de Informes 
stm_aix("p0i3","p0i0",[0," Informes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p6i0","p1i0",[0,"  Recepciones  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p6i1","p3i0",[0,"  CXP Detallado  ","","",-1,-1,0,"http://www.google.com/"]);
stm_aix("p6i2","p3i0",[0,"  Solicitudes de Pago "]);
stm_aix("p6i2","p1i0",[0,"  Retenciones    ","","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
stm_bpx("p6","p1",[1,2,0,0,2,3,0]);
stm_aix("p6i0","p1i0",[0,"  Comprobante  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p6i1","p1i0",[0,"  Listado General  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p6i1","p1i0",[0,"  Listado Específico  ","","",-1,-1,0,"","_self","","","","",0]);
stm_ep();
stm_aix("p6i4","p3i0",[0,"  CXP Resumido  "]);
stm_aix("p6i2","p3i0",[0,"  Relacion de Facturas "]);
stm_aix("p6i3","p3i0",[0,"  Relación de Saldos por Solicitud"]);
stm_aix("p6i4","p3i0",[0,"  Análisis de Vencimiento  "]);
stm_ep();



// Menú Principal - Mantenimiento
stm_aix("p0i5","p0i0",[0," Mantenimiento "]);
stm_bpx("p7","p1",[1,4,0,0,2,3,6,7]);
//Mantenimiento - Opciones de Segundo Nivel
stm_aix("p7i0","p1i0",[0,"Configuración    "]);
stm_aix("p7i2","p1i0",[0,"Mantenimientos    ","","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
stm_bpx("p7","p1",[1,2,0,0,2,3,0]);
// Mantenimiento - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0,"  Libro de Compras  ","","",-1,-1,0,"","_self","","","","",0]);
stm_ep();
stm_ep();
stm_ep();
stm_em();*/
