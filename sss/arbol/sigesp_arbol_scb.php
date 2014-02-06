<?php
$li_i=0;

$li_i++;//1
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Procesos";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 11;

$li_i++;//2
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Movimiento de Banco";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_movbanco.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//3
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Cancelaciones";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 12;//Calcular Número de Hijos.

$li_i++;//4
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Programación de Pagos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_progpago.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//5
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Desprogramación de Pagos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_desprogpago.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//6
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Emisión de Cheques";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_emision_chq.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//7
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Eliminación de Cheques No Contabilizados";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_elimin_chq.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//8
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Pago Directo";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_pago_directo.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//9
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Orden de Pago Directa";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_orden_pago_directo.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//10
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Orden de Pago Directa Con Compromiso Previo";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_opd_causapaga.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//11
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Carta Orden";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 2;

$li_i++;//12
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 3;
$arbol["nombre_logico"][$li_i] = "Carta Orden Única Nota de Débito";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_carta_orden.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "011";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//13
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 3;
$arbol["nombre_logico"][$li_i] = "Carta Orden Múltiples Notas de Débito";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_carta_orden_mnd.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "011";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//14
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Eliminación de Carta Orden no Contabilizada";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_elimin_carta_orden.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//15
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Liquidación de Créditos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_liquidacion_creditos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//16
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Cobranza - Recuperación de Créditos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_recuperacion_creditos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//17
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Pagos Anticipo (Caso Vialidad y Construcción SUCRE)";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_pago_anticipo.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "003";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//18
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Conciliación Bancaria";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conciliacion.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//19
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Colocaciones";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_movcol.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//20
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Retenciones Iva";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 2;

$li_i++;//21
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Crear Comprobante";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_cmp_retencion.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "020";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//22
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Modificar Comprobante";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_modcmpret.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "020";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//23
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Retenciones Municipales";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 3;

$li_i++;//24
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Crear Comprobante";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_cmp_ret_mcp.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//25
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Modificar Comprobante";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_cmp_ret_mod.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//26
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Otros Comprobantes";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_cmp_ret_mun_otros.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//27
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Transferencia Bancaria";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_transferencias.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//28
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Entrega de Cheques";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_entregach.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//29
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Reverso de Entrega de Cheques";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_reverso_entregach.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//30
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Eliminación Anulados Monto 0";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_elimin_anulado.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//31
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Error de Banco";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_concilerror.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//32
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Reportes";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 11;

$li_i++;//33
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Disponibilidad Financiera";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_disponibilidad.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//34
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Listado de Documentos";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 3;

$li_i++;//35
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Documentos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_documentos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "034";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//36
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Conciliados";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_list_doc_conciliados.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "034";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//37
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Documentos en Tránsito";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_list_doc_transito.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "034";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//38
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Listado de Ordenes de Pago Directa";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_ordenpago.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//39
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Pagos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_pagos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//40
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Registros Contables";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_reg_contables.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//41
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Conciliación Bancaria";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_conciliacion.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//42
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Estado de Cuenta";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 2;

$li_i++;//43
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Formato 1";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_estado_cta.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "042";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//44
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Resumido";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_estado_ctares.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "042";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//45
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Otros";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 4;

$li_i++;//46
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Movimientos Presupuestarios Por Banco";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_spg_x_banco.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "045";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//47
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Listado de Chequeras";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_listadochequeras.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "045";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//48
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Relación Selectiva de Cheques";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_relacion_sel_chq.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "045";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//49
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Relación Selectiva de Documentos (No Incluye Cheques)";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_relacion_sel_docs.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "045";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//50
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Cheques en Custodia";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_chq_custodia_entregados.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//51
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Libro de Banco";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_libro_banco.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//52
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Comprobante Retención Municipal";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_comp_ret_mun.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//53
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Listado Cheques Caducados";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_chq_caducados.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//54
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Listado Cheques por Anticipos o Amortizados";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_pagos_anticipos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//55
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Comprobantes de Retención";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 2;

$li_i++;//56
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "IVA ";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_retencionesiva.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "055";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//57
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "I.S.L.R.";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_retencionesislr.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "055";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//58
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Declaracion de Salario y Otras Remuneraciones";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_r_declaracionxml.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "032";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//59
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Configuración";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 6;

$li_i++;//60
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Formato N° Orden de Pago";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_config.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "058";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//61
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Medidas de Cheque Voucher";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "058";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//62
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Otras Configuraciones del Cheque Voucher";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "058";
$arbol["numero_hijos"][$li_i]  = 12;

$li_i++;//63
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Banesco";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_banesco.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//64
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco BOD";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_bod.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//65
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Bancoro";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_bancoro.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//66
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Banfoandes";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_banfoandes.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//67
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Caroní";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_caroni.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//68
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Central";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_central.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//69
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Del Tesoro";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_tesoro.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//70
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Federal";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_federal.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//71
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Industrial";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_industrial.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//72
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Mercantil";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_mercantil.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//73
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Provincial";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_provincial.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//74
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Banco Venezuela";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_voucher_venezuela.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "061";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//75
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Selección Formato Carta Orden";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_select_cartaorden.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "058";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//76
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Configuración Formato Carta Orden";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_cartaorden.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "058";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//77
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Validación Disponibilidad Financiera";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_conf_disponibilidad.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "058";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//78
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Mantenimiento";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 1;

$li_i++;//79
$arbol["sistema"][$li_i]       = "SCB";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Movimientos Descuadrados";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_p_mant_descuadrados.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "077";
$arbol["numero_hijos"][$li_i]  = 0;

$gi_total= $li_i;
?>