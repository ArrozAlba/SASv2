stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,200,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);
// Menú Principal- Recepción de Documentos
//stm_aix("p0i3","p0i0",[0," Recepcion de Documentos "]);
//stm_bpx("p2","p1",[1,4,0,0,2,3,6,7]);
stm_ai("p0i0",[0," Recepcion de Documentos ","","",-1,-1,0,"","","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
/*stm_aix("p1i0","p0i0",[0,"Registro","","",-1,-1,0,"","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p0i0",[0,"Normal","","",-1,-1,0,"sigesp_cxp_p_recepcion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i1","p1i0",[0,"Contable","","",-1,-1,0,"sigesp_cxp_p_recepcioncontable.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();*/
stm_aix("p1i0","p0i0",[0,"Registro ","","",-1,-1,0,"sigesp_cxp_p_recepcioncontable.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Aprobación ","","",-1,-1,0,"sigesp_cxp_p_aprobacionrecepcion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Anulación  ","","",-1,-1,0,"sigesp_cxp_p_anulacionrecepcion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
// Menú Principal - Solicitud de Pagos
stm_aix("p0i3","p0i0",[0," Solicitud de Pagos "]);
stm_bpx("p2","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p2i0","p1i0",[0," Registro","","",-1,-1,0,"sigesp_cxp_p_solicitudpago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p2i2","p2i0",[0," Aprobación","","",-1,-1,0,"sigesp_cxp_p_aprobacionsolicitudpago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p2i2","p2i0",[0," Anulacion sin Afectación","","",-1,-1,0,"sigesp_cxp_p_anulacionsolicitudpago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Notas de Credito / Debito
stm_aix("p0i3","p0i0",[0," Notas de Crédito/Débito "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"Registro","","",-1,-1,0,"sigesp_cxp_p_ncnd.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Aprobación ","","",-1,-1,0,"sigesp_cxp_p_aprobacionnotadebcre.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Comprobantes de Retenciòn
stm_aix("p0i3","p0i0",[0,"  Comprobantes de Retención "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p2i2","p2i0",[0," Crear Comprobante  ","","",-1,-1,0,"sigesp_cxp_p_cmp_retencion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p2i2","p2i0",[0," Editar Comprobante ","","",-1,-1,0,"sigesp_cxp_p_modcmpret.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Control de Créditos
stm_aix("p0i3","p0i0",[0,"  Control de Créditos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p2i2","p2i0",[0," Solicitud de Desembolso  ","","",-1,-1,0,"sigesp_cxp_p_solicituddesembolso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0,"   Reportes     ","","",-1,-1,0,"","","","","","",6,0,0,"","",0,0,0,0,1,"#f4f4f4"]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p0i0",[0,"Listados","","",-1,-1,0,"sigesp_cxp_r_listados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Recepciones de Documentos","","",-1,-1,0,"sigesp_cxp_r_recepciones.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Cuentas por Pagar","","",-1,-1,0,"sigesp_cxp_r_solicitudes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Solicitudes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p0i0",[0,"Formato 1","","",-1,-1,0,"sigesp_cxp_r_solicitudesf1.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Formato 2","","",-1,-1,0,"sigesp_cxp_r_solicitudesf2.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i4","p1i0",[0," Retenciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p0i0",[0,"I.S.L.R.","","",-1,-1,0,"sigesp_cxp_r_retencionesislr.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"General","","",-1,-1,0,"sigesp_cxp_r_retencionesgeneral.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Específico","","",-1,-1,0,"sigesp_cxp_r_retencionesespecifico.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"IVA","","",-1,-1,0,"sigesp_cxp_r_retencionesiva.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Declaración Informativa IVA","","",-1,-1,0,"sigesp_cxp_r_retencionesdeclaracioniva.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Municipales","","",-1,-1,0,"sigesp_cxp_r_retencionesmunicipales.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Aporte Social","","",-1,-1,0,"sigesp_cxp_r_retencionesaporte.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"(1 Por Mil)","","",-1,-1,0,"sigesp_cxp_r_retunoxmil.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Listado de Comprobantes del I.V.A","","",-1,-1,0,"sigesp_cxp_r_comp_ret_iva.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p4i0","p0i0",[0,"Relación de Facturas","","",-1,-1,0,"sigesp_cxp_r_relacionfacturas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Relación Consecutiva de Solicitudes","","",-1,-1,0,"sigesp_cxp_r_relacionsolicitudes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"CXP Resumido","","",-1,-1,0,"sigesp_cxp_r_cxpresumido.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Relación de Saldos por Solicitud ","","",-1,-1,0,"sigesp_cxp_r_relacionsaldos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Relación de Notas de Débito/Crédito ","","",-1,-1,0,"sigesp_cxp_r_relacionndnc.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"AR-C","","",-1,-1,0,"sigesp_cxp_r_arc.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Libro de Compra ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p0i0",[0,"General","","",-1,-1,0,"sigesp_cxp_r_librocompra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Resumido","","",-1,-1,0,"sigesp_cxp_r_librocompra_res.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p4i0","p0i0",[0,"Libro I.S.L.R. / Timbre Fiscal","","",-1,-1,0,"sigesp_cxp_r_libro_islr_timbrefiscal.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Ubicación de Recepciones de Documento","","",-1,-1,0,"sigesp_cxp_r_ubicacion_recepciondocumento.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Ubicacion de Solicitudes de Pago","","",-1,-1,0,"sigesp_cxp_r_ubicacionsolicitudes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0,"Declaracion de Salarios y Otras Remuneraciones ","","",-1,-1,0,"sigesp_cxp_r_declaracionxml.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();

stm_aix("p4i0","p1i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules_administracion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();
stm_em();


