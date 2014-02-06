<?php
$li_i=0;

$li_i++; // 001
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Sistemas";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=8;

$li_i++; // 002
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=3;

$li_i++; // 003
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Compras";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 004
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 005
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Solicitudes Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 006
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Notas de Debitos/Créditos Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 007
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Caja y Banco";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=3;

$li_i++; // 008
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Movimientos de Caja y Banco";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="007";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 009
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Colocaciones de Caja y Banco";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="007";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 010
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Orden de Pago Directa de Caja y Banco";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="007";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 011
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Nómina";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 012
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Obras";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 013
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Asignación";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="012";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 014
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contratos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="012";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 015
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Anticipos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="012";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 016
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Valuación";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="012";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 017
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Contabilidad Presupuestaria de Gasto";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 018
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Contabilidad Presupuestaria de Ingreso";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 019
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Activos Fijo";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; // 020
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Depreciaciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="019";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; // 021
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Mantenimiento";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 022
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Recepciones de Documento";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 023
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Control de Créditos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 024
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Inventario";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 025
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Facturación y Cobranzas";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

//--------------------------------------------- SEP -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_sep.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_sep.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Anular Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_sep.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Anulación de Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_anula_sep.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SOC -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Ordenes de Compra ";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_soc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Ordenes de Compra ";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_soc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Anular Ordenes de Compra ";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_soc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Anulación de  Ordenes de Compra ";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_anula_soc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- CXP -----------------------------------------------------------

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Solicitudes de Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_cxp.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Solicitudes de Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_cxp.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Anular Solicitudes de Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_cxp.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reverso Anular de Solicitudes de Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_anula_cxp.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Notas de Crédito/Débito de Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_ncd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Notas de Crédito/Débito de Ordenes de Pago";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_ncd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Recepciones de Documento";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_rd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="022";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Recepciones de Documento";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_rd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="022";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Anular Recepciones de Documento";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_rd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="022";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Anulación Recepciones de Documento";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_anula_rd.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="022";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SCB -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Movimientos Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_scb.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Movimientos Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_scb.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Anulación de Movimientos Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_scb.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reverso Anulación de  Movimientos Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_anula_scb.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Colocaciones Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_scbcol.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="009";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Colocaciones Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_scbcol.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="009";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilización Orden de Pago Directo Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_scbop.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="010";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reverso Orden de Pago Directo Caja y Banco";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_scbop.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="010";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SNO -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilización de Nómina";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_sno.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="011";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reverso de Nómina";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_sno.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="011";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SOB -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Asignación Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_asignacion_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="013";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Asignación Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_asignacion_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="013";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Anular Asignación Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_asignacion_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="013";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Anulación Asignación Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_revanula_asignacion_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="013";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Contrato Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_contrato_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="014";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Contrato Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_contrato_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="014";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Anular Contrato Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_anula_contrato_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="014";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Anulación de Contrato Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_revanula_contrato_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="014";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilización Anticipo Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_anticipo_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="015";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Anticipo Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_anticipo_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="015";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Valuación Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_valuacion_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="016";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Valuación Obras";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_valuacion_sob.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="016";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SPG -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Aprobación de Modificaciones Presupuestarias";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_mp.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="017";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar de Modificaciones Presupuestarias";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reversa_mp.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="017";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SPI -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Aprobación de Modificaciones Presupuestarias Ingreso";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_mp_spi.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="018";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar de Modificaciones Presupuestarias Ingreso";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_mp_spi.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="018";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SAF -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="SAF";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Contabilizar Depreciación Activos Fijos";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_depreciacion_saf.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="020";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="SAF";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reversar Depreciación Activos Fijos";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_depreciacion_saf.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="020";
$arbol["numero_hijos"][$li_i]=0;

//---------------------------------------- CONTROL DE CRÉDITOS ------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Aprobación";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_aprobacioncontrolcreditos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="023";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Cuentas por cobrar";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_cuentasporcobrar.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="023";
$arbol["numero_hijos"][$li_i]=0;

//---------------------------------------- MANTENIMIENTO ------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Configuración";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_configuracion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="021";
$arbol["numero_hijos"][$li_i]=0;
//--------------------------------------------- SIV -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Despachos";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_siv.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="024";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Despachos";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_siv.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="024";
$arbol["numero_hijos"][$li_i]=0;

//--------------------------------------------- SFC -----------------------------------------------------------
$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Cuentas por Cobrar";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_sfc_cxc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Cuentas por Cobrar";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_sfc_cxc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Notas de Crédito";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_sfc_nc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Notas de Crédito";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_sfc_nc.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Deducciones";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_sfc_ded.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Deducciones";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_sfc_ded.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar Pagos";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_contabiliza_sfc_pagos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]="MIS";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Pagos";
$arbol["nombre_fisico"][$li_i]="sigesp_mis_p_reverso_sfc_pagos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="025";
$arbol["numero_hijos"][$li_i]=0;

$gi_total=$li_i;
?>