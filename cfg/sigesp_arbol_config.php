<?php
$li_i = 0;

$li_i++;//1
$arbol["sistema"][$li_i] 	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "CIARA";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 9;

$li_i++;//2
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Empresa";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_empresa.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//3
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Procedencias";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_procedencia.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//4
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Ubicación Geográfica";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 5;

$li_i++;//5
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Paises";
$arbol["nombre_fisico"][$li_i] = "../cfg/rpc/sigesp_rpc_d_pais.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//6
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Estados";
$arbol["nombre_fisico"][$li_i] = "../cfg/rpc/sigesp_rpc_d_estado.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//7
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Municipios";
$arbol["nombre_fisico"][$li_i] = "../cfg/rpc/sigesp_rpc_d_municipio.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//8
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]	       = 2;
$arbol["nombre_logico"][$li_i] = "Parroquias";
$arbol["nombre_fisico"][$li_i] = "../cfg/rpc/sigesp_rpc_d_parroquia.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//9
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 2;
$arbol["nombre_logico"][$li_i] = "Ciudades";
$arbol["nombre_fisico"][$li_i] = "../cfg/rpc/sigesp_scv_d_ciudad.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "004";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//10
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Comunidades";
$arbol["nombre_fisico"][$li_i] = "../cfg/rpc/sigesp_rpc_d_comunidad.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//11
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Control Número";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_ctrl_numero.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//12
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Unidad Tributaria";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_unidad_tributaria.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//13
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Consolidación";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_consolidacion.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//14
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Moneda";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_moneda.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//15
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Contabilidad Patrimonial/Fiscal";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 4;

$li_i++;//16
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas Patrimoniales";
$arbol["nombre_fisico"][$li_i] = "../cfg/scg/sigesp_scg_d_plan_unico.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "015";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//17
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Catálogo de Recursos Y Egresos";
$arbol["nombre_fisico"][$li_i] = "../cfg/scg/sigesp_scg_d_plan_unicore.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "015";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//18
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas";
$arbol["nombre_fisico"][$li_i] = "../cfg/scg/sigesp_scg_d_plan_ctas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "015";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//19
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Casamiento Presupuesto";
$arbol["nombre_fisico"][$li_i] = "../cfg/scg/sigesp_scg_d_casamientopresupuesto.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "015";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//20
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Presupuesto de Ingreso";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 1;

$li_i++;//21
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas";
$arbol["nombre_fisico"][$li_i] = "../cfg/spi/sigesp_spi_d_planctas.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "020";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//22
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Presupuesto de Gasto";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 8;

$li_i++;//23
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Fuente de Financiamiento";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_fuentfinan.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//24
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Estructura Presupuestaria";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_estprog1.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//25
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Casamiento Estructura Presupuestaria - Fuentes de Financiamiento";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_codestpro_codfuefin.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//26
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Plan de Cuentas";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_planctas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//27
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Unidad Administradora";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_uniadm.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//28
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Unidad Ejecutora";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_unidad.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//29
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Validaciones Presupuestarias";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_validaciones.php";
$arbol["id"][$li_i]            = $li_i;
$arbol["padre"][$li_i]         = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//30
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Modificaciones Presupuestarias";
$arbol["nombre_fisico"][$li_i] = "../cfg/spg/sigesp_spg_d_tipomodificaciones.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "022";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//31
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Cuentas Por Pagar";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 4;

$li_i++;//32
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Deducciones";
$arbol["nombre_fisico"][$li_i] = "../cfg/cxp/sigesp_cxp_d_deducciones.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "031";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//33
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Otros Créditos";
$arbol["nombre_fisico"][$li_i] = "../cfg/cxp/sigesp_cxp_d_otroscreditos.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "031";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//34
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Documentos";
$arbol["nombre_fisico"][$li_i] = "../cfg/cxp/sigesp_cxp_d_documentos.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "031";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//35
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Clasificador";
$arbol["nombre_fisico"][$li_i] = "../cfg/cxp/sigesp_cxp_d_clasificador.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "031";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//36
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 2;

$li_i++;//37
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo";
$arbol["nombre_fisico"][$li_i] = "../cfg/sep/sigesp_sep_d_tipo.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "036";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//38
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Concepto";
$arbol["nombre_fisico"][$li_i] = "../cfg/sep/sigesp_sep_d_concepto.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "036";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//39
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 0;
$arbol["nombre_logico"][$li_i] = "Ordenes de Compras";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 4;

$li_i++;//40
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Servicios";
$arbol["nombre_fisico"][$li_i] = "../cfg/soc/sigesp_soc_d_tiposer.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "039";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//41
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Servicios";
$arbol["nombre_fisico"][$li_i] = "../cfg/soc/sigesp_soc_d_servicio.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "039";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//42
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Clausulas ";
$arbol["nombre_fisico"][$li_i] = "../cfg/soc/sigesp_soc_d_clausulas.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "039";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//43
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Modalidad de Clausulas";
$arbol["nombre_fisico"][$li_i] = "../cfg/soc/sigesp_soc_d_modcla.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "039";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//44
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]         = 0;
$arbol["nombre_logico"][$li_i] = "Banco";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "000";
$arbol["numero_hijos"][$li_i]  = 8;

$li_i++;//45
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Banco";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_banco.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//46
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Cuenta";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_tipocta.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//47
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Cuenta Banco";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_ctabanco.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//48
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Chequera";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_chequera.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//49
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Tipo de Colocación";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_tipocolocacion.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]         = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//50
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Colocación";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_colocacion.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//51
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Conceptos de Movimientos";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_conceptos.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//52
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Agencias";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_agencia.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//53
$arbol["sistema"][$li_i]	   = "Sistemas";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Correo Electronico";
$arbol["nombre_fisico"][$li_i] = "../cfg/sigesp_cfg_d_correo.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "001";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;//54
$arbol["sistema"][$li_i]       = "Sistemas";
$arbol["nivel"][$li_i]         = 1;
$arbol["nombre_logico"][$li_i] = "Tipos de Fondos en Avance";
$arbol["nombre_fisico"][$li_i] = "../cfg/scb/sigesp_scb_d_tipofondo.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "044";
$arbol["numero_hijos"][$li_i]  = 0;


$gi_total = $li_i;
?>