<?php
$li_i=0;
$li_i++; // 001
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Recepción de Documentos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 002
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Solicitud de Pagos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 003
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Notas de Crédito/Débito";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 004
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Comprobantes de Retención";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 005
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=19;

$li_i++;  // 006
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Crear Comprobantes";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_cmp_retencion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 007
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Editar Comprobantes";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_modcmpret.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; // 008
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Control de Créditos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=19;
/*
$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Recepción de Documentos Normal";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_recepcion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;
*/
$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Recepción de Documentos";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_recepcioncontable.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Aprobación de Recepción de Documentos";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_aprobacionrecepcion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Anulación de Recepción de Documentos";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_anulacionrecepcion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Registro de Solicitud de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_solicitudpago.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Aprobación de Solicitudes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_aprobacionsolicitudpago.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Anulación sin Afectacion";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_anulacionsolicitudpago.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Nota de Crédito/Débito";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_ncnd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Aprobación";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_aprobacionnotadebcre.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Listados";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_listados.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Recepciones de Documentos";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_recepciones.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Cuentas por Pagar";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_solicitudes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Solicitudes Formato 1";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_solicitudesf1.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Solicitudes Formato 2";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_solicitudesf2.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Relacion de Facturas";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_relacionfacturas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Retenciones I.S.L.R.";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesislr.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Retenciones General";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesgeneral.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Retenciones Especifico";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesespecifico.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Retenciones IVA";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesiva.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Declaracion Informativa Retenciones IVA";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesdeclaracioniva.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Retenciones Municipales";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesmunicipales.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Retenciones Aporte Social";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_retencionesaporte.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Relacion Consecutiva de Solicitudes";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_relacionsolicitudes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Cuentas por Pagar Resumido";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_cxpresumido.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Relación de Saldos por Solicitud";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_relacionsaldos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Relación de Notas de Débito y Crédito";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_relacionndnc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - AR-C";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_arc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Libro Compra General";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_librocompra.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Libro Compra Resumido";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_librocompra_res.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud de Desembolso";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_p_solicituddesembolso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Libro I.S.R.L. / Timbre Fiscal";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_libro_islr_timbrefiscal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Ubicacion de Recepciones de Documentos";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_ubicacion_recepciondocumento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Ubicacion de Solicitudes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_ubicacionsolicitudes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 
$arbol["sistema"][$li_i]="CXP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Declaracion XML";
$arbol["nombre_fisico"][$li_i]="sigesp_cxp_r_declaracionxml.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$gi_total=$li_i;
?>