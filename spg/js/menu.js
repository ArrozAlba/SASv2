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

// Menú Principal- Recepción de Documentos
stm_ai("p0i0",[0,"   Procesos    ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Apertura ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,7]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i5","p1i0",[0," Mensual  ","","",-1,-1,0,"sigesp_spg_p_apertura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Trimestral  ","","",-1,-1,0,"sigesp_spg_p_apertura_trim.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

stm_aix("p1i2","p1i0",[0,"Comprobantes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i5","p1i0",[0," Ejecución Financiera  ","","",-1,-1,0,"sigesp_spg_p_comprobante.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Modificaciones Presupuestarias ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i5","p1i0",[0," Rectificaciones  ","","",-1,-1,0,"sigesp_spg_p_rectificaciones.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Insubsistencias  ","","",-1,-1,0,"sigesp_spg_p_insubsistencias.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Traspasos  ","","",-1,-1,0,"sigesp_spg_p_traspaso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Credito/Ingreso Adicional  ","","",-1,-1,0,"sigesp_spg_p_adicional.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Transferencia Intercompañia","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i5","p1i0",[0," Modificaciones Presupuestarias Aprobadas  ","","",-1,-1,0,"sigesp_spg_p_transferir_traspasos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Asientos Presupuestarios y Contables ","","",-1,-1,0,"sigesp_spg_p_trans_asipre.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i5","p1i0",[0,"Reverso/Cierre de Presupuesto  ","","",-1,-1,0,"sigesp_spg_p_cerrarpre.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i5","p1i0",[0,"Eliminar Comprobantes  ","","",-1,-1,0,"sigesp_spg_p_eliminar_comprobante.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i5","p1i0",[0,"Programaci&oacute;n de reportes  ","","",-1,-1,0,"sigesp_spg_p_progrep.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
//stm_aix("p1i5","p1i0",[0," Mensual  ","","",-1,-1,0,"sigesp_spg_p_progrep.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i5","p1i0",[0," Trimestral  ","","",-1,-1,0,"sigesp_spg_p_progrep_trim.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Modificación del Presupuesto Programado","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i5","p1i0",[0,"Mensual ","","",-1,-1,0,"sigesp_spg_p_modprog.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0,"Trimestral ","","",-1,-1,0,"sigesp_spg_p_modprog_trimestral.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i5","p1i0",[0," Estandar   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Acumulado por Cuentas    ","","",-1,-1,0,"sigesp_spg_r_acum_x_cuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Mayor Analitico ","","",-1,-1,0,"sigesp_spg_r_mayor_analitico.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado Apertura ","","",-1,-1,0,"sigesp_spg_r_listado_apertura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modificaciones Presupuestarias No Aprobadas ","","",-1,-1,0,"sigesp_spg_r_modificaciones_presupuestarias.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modificaciones Presupuestarias Aprobadas ","","",-1,-1,0,"sigesp_spg_r_modificaciones_presupuestarias_aprobadas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0,"Comprobantes","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Formato 1  ","","",-1,-1,0,"sigesp_spg_r_comprobante_formato1.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Formato 2  ","","",-1,-1,0,"sigesp_spg_r_comprobante_formato2.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p4i0","p1i0",[0," Disponibilidad Presupuestarias ","","",-1,-1,0,"sigesp_spg_r_disponibilidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Disponibilidad Presupuestarias Formato # 2","","",-1,-1,0,"sigesp_spg_r_disponibilidad_formato2.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de  Cuentas Presupuestarias  ","","",-1,-1,0,"sigesp_spg_r_cuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Resumen de Ejecucion Financiera de Presupuesto de Gasto  ","","",-1,-1,0,"sigesp_spg_r_resumen_ejecucion_financiera.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Ejecucion Financiera de Presupuesto de Gasto  ","","",-1,-1,0,"sigesp_spg_r_ejecucion_financiera.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de  Fuentes de Financiamiento  ","","",-1,-1,0,"sigesp_spg_r_fuente.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i5","p1i0",[0," Otros   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Distribucion Mensual del Presupuesto  ","","",-1,-1,0,"sigesp_spg_r_distribucion_mensual_presupuesto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Distribucion Trimestral del Presupuesto  ","","",-1,-1,0,"sigesp_spg_r_distribucion_trimestral_presupuesto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Unidades Ejecutoras  ","","",-1,-1,0,"sigesp_spg_r_unidades_ejecutoras.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Ejecucion de Compromisos ","","",-1,-1,0,"sigesp_spg_r_ejecucion_compromisos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Compromisos no Causados ","","",-1,-1,0,"sigesp_spg_r_compromisos_no_causados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Compromisos Causados Parcialmente","","",-1,-1,0,"sigesp_spg_r_compromisos_causados_parcialmente.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Compromisos Causados no Pagados","","",-1,-1,0,"sigesp_spg_r_compromisos_causados_no_pagados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Operacion por Especifica","","",-1,-1,0,"sigesp_spg_r_operacion_por_especifica.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Ejecutado por Partida","","",-1,-1,0,"sigesp_spg_r_ejecutado_por_partida.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Operacion por Bancos","","",-1,-1,0,"sigesp_spg_r_operacion_por_banco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Resumen Proveedor/Beneficiario","","",-1,-1,0,"sigesp_spg_r_resumen_prov_bene.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Cuadro Resumen de Fideicomisos","","",-1,-1,0,"sigesp_spg_r_resumen_fideicomiso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i5","p1i0",[0," Comparados","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0,"   Instructivos 07   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Resumen del Presupuesto de Gasto Por Partida(0704) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0704.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Resumen del Presupuesto de Gasto a Nivel de Proyectos y Acciones Centralizadas(0705) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0705.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera  del Presupuesto de Gastos(0707) ","","",-1,-1,0,"sigesp_spg_r_comparados_ejecucion_financiera_formato3.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Presupuesto de Caja (0717) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0717.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i5","p1i0",[0,"   Instructivos 04   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Presupuesto de Caja (0402) ","","",-1,-1,0,"sigesp_spg_r_comparados_formato0402.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de los Proyectos / Acciones Centralizadas del Ente(0405) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0405.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera Trimetral del Presupuesto de Gastos(0407) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0407.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i5","p1i0",[0,"   Instructivos 05   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0,"  Ejecuci&oacute;n Financiera Mensual del Presupuesto de Gastos(0503)   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Estandar ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0503.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Acumulado ","","",-1,-1,0,"sigesp_spg_r_comparados_forma_acum_cuenta.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de los Proyectos del Ente(0514) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0514.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de los Proyectos del Ente(0516) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0516.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de las Acciones Centralizadas del Ente(0517) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0517.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de las Acciones Especificas(0518) ","","",-1,-1,0,"sigesp_spg_r_comparados_forma0518.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p1i0",[0," Instructivo 06 - 2008","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de los Proyectos / Acciones Centralizadas del Organo","","",-1,-1,0,"sigesp_spg_r_instructivo_06_ejec_fin_pry_acc.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Financiera de las Acciones Espec&iacute;ficas del Organo","","",-1,-1,0,"sigesp_spg_r_instructivo_06_ejec_fin_acc_esp.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Informaci&oacute;n Mensual de la Ejecuci&oacute;n Financiera","","",-1,-1,0,"sigesp_spg_r_instructivo_06_inf_men_eje_fin.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p1i0",[0," Instructivo 07 - 2008","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Trimestral de Gastos y Aplicaciones Financiera (Resumen Institucional)","","",-1,-1,0,"sigesp_spg_r_ejecucion_trimestral.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p1i0",[0," Consolidado de Ejecuci&oacute;n Trimestral de Gastos y Aplicaciones Financieras ","","",-1,-1,0,"sigesp_spg_r_instructivo_consolidado_ejecucion_trimestral.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
<!-- stm_aix("p1i5","p1i0",[0," Ejecuci&oacute;n Trimestral de Gastos y Aplicaciones Financieras por Programatica ","","",-1,-1,0,"sigesp_spg_r_instructivo_ejecucion_trimestral_x_programatica.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]); -->
stm_aix("p1i5","p1i0",[0," Estado de Resultados ","","",-1,-1,0,"sigesp_spg_r_instructivo_estado_resultado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);stm_ep();
stm_ep();
stm_aix("p1i5","p1i0",[0," Traspasos Intercompañia  ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Resultado de Traspasos Presupuestarios Intercompañia  ","","",-1,-1,0,"sigesp_spg_r_traspasos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_ep();
stm_aix("p1i5","p1i0",[0," Modificaciones Presupuestarias   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Detallado por Fuentes Financiamiento   ","","",-1,-1,0,"sigesp_spg_r_modif_fuente_finan.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);


stm_ep();
stm_aix("p4i0","p1i0",[0," Modificaciones al Programado  ","","",-1,-1,0,"sigesp_spg_r_modif_programado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();
stm_ep();
/*stm_aix("p0i4","p0i0",[0," Mantenimiento "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Configuraci&oacute;n  ","","",-1,-1,0,"sigesp_spg_d_configuracion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();*/

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
