<?php
$li_i=0;

$li_i++;//001
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "CIARA";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 9;

$li_i++;//002
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Empresa";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_empresa.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//003
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Procedencias";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_procedencia.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//004
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Ubicación Geográfica";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 5;

$li_i++;//005
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Paises";
$arbol["nombre_fisico"][$li_i] = "sigesp_rpc_d_pais.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//006
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Estados";
$arbol["nombre_fisico"][$li_i] = "sigesp_rpc_d_estado.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//007
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Municipios";
$arbol["nombre_fisico"][$li_i] = "sigesp_rpc_d_municipio.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//008
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Parroquias";
$arbol["nombre_fisico"][$li_i] = "sigesp_rpc_d_parroquia.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//009
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Ciudad";
$arbol["nombre_fisico"][$li_i] = "sigesp_scv_d_ciudad.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//010
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Comunidad";
$arbol["nombre_fisico"][$li_i] = "sigesp_rpc_d_comunidad.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//011
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Control Número";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_ctrl_numero.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//012
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Unidad Tributaria";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_unidad_tributaria.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//013
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Consolidación";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_consolidacion.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//014
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Moneda";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_moneda.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//015
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Correo Electrónico";
$arbol["nombre_fisico"][$li_i] = "sigesp_cfg_d_correo.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//016
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Contabilidad Patrimonial/Fiscal";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 4;

$li_i++;//017
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas Patrimoniales";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_d_plan_unico.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "016";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//018
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Catálogo de Recursos y Egresos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_d_plan_unicore.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "016";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//019
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_d_plan_ctas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "016";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//020
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Casamiento Presupuesto";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_d_casamientopresupuesto.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "016";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//021
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Presupuesto de Ingreso";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 1;

$li_i++;//022
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i] 		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas";
$arbol["nombre_fisico"][$li_i] = "sigesp_spi_d_planctas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "021";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//023
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Presupuesto de Gasto";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 12;

$li_i++;//024
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Fuente de Financiamiento";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_fuentfinan.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//025
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Estructura Presupuestaria 1";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_estprog1.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//026
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Estructura Presupuestaria 2";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_estprog2.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//027
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Estructura Presupuestaria 3";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_estprog3.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//028
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Estructura Presupuestaria 4";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_estprog4.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//029
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Estructura Presupuestaria 5";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_estprog5.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//030
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Casamiento Estructura Presupuestaria - Fuentes de Financiamiento";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_codestpro_codfuefin.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//031
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_planctas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//032
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Unidad Administradora";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_uniadm.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//033
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Unidad Ejecutora";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_unidad.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//034
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Validación Presupuestaria";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_validaciones.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//035
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Modificaciones Presupuestarias ";
$arbol["nombre_fisico"][$li_i] = "sigesp_spg_d_tipomodificaciones.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "023";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//036
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Cuentas Por Pagar";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 4;

$li_i++;//037
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Deducciones";
$arbol["nombre_fisico"][$li_i] = "sigesp_cxp_d_deducciones.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "036";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//038
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Otros Créditos";
$arbol["nombre_fisico"][$li_i] = "sigesp_cxp_d_otroscreditos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "036";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//039
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Documentos";
$arbol["nombre_fisico"][$li_i] = "sigesp_cxp_d_documentos.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "036";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//040
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Clasificador";
$arbol["nombre_fisico"][$li_i] = "sigesp_cxp_d_clasificador.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "036";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//041
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "000";
$arbol["numero_hijos"][$li_i]  = 2;

$li_i++;//042
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo";
$arbol["nombre_fisico"][$li_i] = "sigesp_sep_d_tipo.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "041";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//043
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Concepto";
$arbol["nombre_fisico"][$li_i] = "sigesp_sep_d_concepto.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "041";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//044
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Ordenes de Compras";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 1;

$li_i++;//045
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Servicios";
$arbol["nombre_fisico"][$li_i] = "sigesp_soc_d_tiposer.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//046
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Servicios";
$arbol["nombre_fisico"][$li_i] = "sigesp_soc_d_servicio.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//047
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Clausulas";
$arbol["nombre_fisico"][$li_i] = "sigesp_soc_d_clausulas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//048
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Modalidad de Clausulas";
$arbol["nombre_fisico"][$li_i] = "sigesp_soc_d_modcla.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//049
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Banco";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 8;

$li_i++;//050
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Bancos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_banco.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//051
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Cuenta";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_tipocta.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//052
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Cuenta Banco";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_ctabanco.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//053
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Chequera";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_chequera.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//054
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Colocación";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_tipocolocacion.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//055
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Colocación";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_colocacion.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//056
$arbol["sistema"][$li_i]	   = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Conceptos de Movimientos";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_conceptos.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//057
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Agencias";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_agencia.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//058
$arbol["sistema"][$li_i]       = "CFG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Fondo de Anticipo";
$arbol["nombre_fisico"][$li_i] = "sigesp_scb_d_tipofondo.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "049";
$arbol["numero_hijos"][$li_i]  = 0;

$gi_total=$li_i;
?>