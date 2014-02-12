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

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,1,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Archivo
stm_ai("p0i0",[0," Procesos ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Movimiento de Banco    ","","",-1,-1,0,"sigesp_scb_p_movbanco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0,"Cancelaciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p1","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0,"Programación de Pagos ","","",-1,-1,0,"sigesp_scb_p_progpago.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Desprogramación de Pagos ","","",-1,-1,0,"sigesp_scb_p_desprogpago.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Emisión de Cheques  ","","",-1,-1,0,"sigesp_scb_p_emision_chq.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Eliminación de Cheques no Contabilizados ","","",-1,-1,0,"sigesp_scb_p_elimin_chq.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Pago Directo ","","",-1,-1,0,"sigesp_scb_p_pago_directo.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Orden de Pago Directa ","","",-1,-1,0,"sigesp_scb_p_orden_pago_directo.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Orden de Pago Directa con Compromiso Previo","","",-1,-1,0,"sigesp_scb_p_opd_causapaga.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i2","p0i0",[0,"Carta Orden ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p1","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0,"&Uacute;nica Nota de D&eacute;bito  ","","",-1,-1,0,"sigesp_scb_p_carta_orden.php","_self"]);
stm_aix("p1i4","p1i0",[0,"M&uacute;ltiples Notas de D&eacute;bito  ","","",-1,-1,0,"sigesp_scb_p_carta_orden_mnd.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Eliminación de Carta Orden no Contabilizada ","","",-1,-1,0,"sigesp_scb_p_elimin_carta_orden.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Conciliación Bancaria    ","","",-1,-1,0,"sigesp_scb_p_conciliacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Colocaciones    ","","",-1,-1,0,"sigesp_scb_p_movcol.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Retenciones Municipales ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0,"Crear Comprobante ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_mcp.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Modificar Comprobante ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_mod.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Otros Comprobantes ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_mun_otros.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Transferencia Bancaria    ","","",-1,-1,0,"sigesp_scb_p_transferencias.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Entrega de Cheques    ","","",-1,-1,0,"sigesp_scb_p_entregach.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Reverso de Entrega de Cheques    ","","",-1,-1,0,"sigesp_scb_p_reverso_entregach.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0,"Eliminacion Anulados Monto 0   ","","",-1,-1,0,"sigesp_scb_p_elimin_anulado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Disponibilidad Financiera    ","","",-1,-1,0,"sigesp_scb_r_disponibilidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Listado de Documentos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Documentos ","","",-1,-1,0,"sigesp_scb_r_documentos.php","_self"]);
stm_aix("p1i4","p1i0",[0," Conciliados ","","",-1,-1,0,"sigesp_scb_r_list_doc_conciliados.php","_self"]);
stm_aix("p4i0","p1i0",[0," Documentos en Transito ","","",-1,-1,0,"sigesp_scb_r_list_doc_transito.php","_self"]);
stm_ep();
stm_aix("p4i0","p1i0",[0," Listado de Ordenes de Pago Directa  ","","",-1,-1,0,"sigesp_scb_r_ordenpago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Pagos    ","","",-1,-1,0,"sigesp_scb_r_pagos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Registros Contables   ","","",-1,-1,0,"sigesp_scb_r_reg_contables.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Conciliacion Bancaria   ","","",-1,-1,0,"sigesp_scb_r_conciliacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Estado de Cuenta      ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Formato 1 ","","",-1,-1,0,"sigesp_scb_r_estado_cta.php","_self"]);
stm_aix("p1i4","p1i0",[0," Resumido ","","",-1,-1,0,"sigesp_scb_r_estado_ctares.php","_self"]);
//stm_aix("p1i4","p1i0",[0," Colocaciones ","","",-1,-1,0,"sigesp_scb_r_colocaciones.php","_self"]);
stm_ep();
stm_aix("p1i2","p0i0",[0," Otros  ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Movimientos Presupuestarios por Banco ","","",-1,-1,0,"sigesp_scb_r_spg_x_banco.php","_self"]);
stm_aix("p1i4","p1i0",[0," Listado de Chequeras ","","",-1,-1,0,"sigesp_scb_r_listadochequeras.php","_self"]);
stm_aix("p1i4","p1i0",[0," Relación Selectiva de Cheques ","","",-1,-1,0,"sigesp_scb_r_relacion_sel_chq.php","_self"]);
stm_aix("p1i4","p1i0",[0," Relación Selectiva de Documentos(No incluye Cheques) ","","",-1,-1,0,"sigesp_scb_r_relacion_sel_docs.php","_self"]);
stm_ep();
stm_aix("p4i0","p1i0",[0," Cheques en Custodia  ","","",-1,-1,0,"sigesp_scb_r_chq_custodia_entregados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Libro de Banco   ","","",-1,-1,0,"sigesp_scb_r_libro_banco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Comprobante Retención Municipal   ","","",-1,-1,0,"sigesp_scb_r_comp_ret_mun.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//agregado el 25/08/08
stm_aix("p4i0","p1i0",[0," Listado Cheques Caducados   ","","",-1,-1,0,"sigesp_scb_r_chq_caducados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_ep();

/*stm_aix("p4i0","p1i0",[0," Configuración  ","","",-1,-1,0,"sigesp_scb_config.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();*/

stm_aix("p0i3","p0i0",[0," Configuración "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Formato Nº Orden de Pago    ","","",-1,-1,0,"sigesp_scb_config.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Medidas del Cheque Voucher ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Banco Provincial ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_provincial.php","_self"]);
stm_aix("p1i4","p1i0",[0," Banco Industrial ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_industrial.php","_self"]);
stm_aix("p1i4","p1i0",[0," Banco de Venezuela ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_venezuela.php","_self"]);
stm_aix("p1i4","p1i0",[0," Banco Mercantil ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_mercantil.php","_self"]);
stm_ep();
stm_aix("p1i2","p0i0",[0," Selección Formato Carta Orden ","","",-1,-1,0,"sigesp_scb_p_conf_select_cartaorden.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Configuración Formato Carta Orden ","","",-1,-1,0,"sigesp_scb_p_conf_cartaorden.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i2","p0i0",[0," Graficos ","","",-1,-1,0,"reporte.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();

stm_aix("p0i3","p0i0",[0," Mantenimiento "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Movimientos Descuadrados    ","","",-1,-1,0,"sigesp_scb_p_mant_descuadrados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i2","p0i0",[0," Graficos ","","",-1,-1,0,"reporte.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();

stm_aix("p4i0","p1i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_em();
