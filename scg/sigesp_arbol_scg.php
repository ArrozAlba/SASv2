<?php
$li_i=0;

$li_i++; // 001
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 002
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; // 003
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Configuración";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 004
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Programación de Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 005
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Programación de Reportes OAF";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 006
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Comprobantes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 007
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes - Comparados";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; // 008
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Comparados - Instructivo 04";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="007";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 009
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reportes - Comparados - Instructivo 07";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="007";
$arbol["numero_hijos"][$li_i]=3;

$li_i++;//010
$arbol["sistema"][$li_i]	   = "SCG";
$arbol["nivel"][$li_i]		   = 1;
$arbol["nombre_logico"][$li_i] = "Reportes - Consolidados";
$arbol["nombre_fisico"][$li_i] = "";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "002";
$arbol["numero_hijos"][$li_i]  = 3;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Comprobantes Contable";
$arbol["nombre_fisico"][$li_i]="sigespwindow_scg_comprobante.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Comprobante Cierre de Ejercicio";
$arbol["nombre_fisico"][$li_i]="sigespwindow_scg_cmp_cierre.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Mensual";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_wproc_progrep.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Trimestral";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_wproc_progrep_trim.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Mensual";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_wproc_prog_oaf.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Trimestral";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_wproc_prog_oaf_trim.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Cuentas Origen y Aplicación de Fondos";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_ctas_oaf.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte - Mayor Analítico";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_mayor_analitico.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte - Balance de Comprobación";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_balance_comprobacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reporte - Comprobante Formato 1";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_comprobante_formato1.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reporte - Comprobante Formato 2";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_comprobante_formato2.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte - Estado de Resultado";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_estado_resultado.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte - Balance General";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_balance_general.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte - Listado de Cuentas";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_cuentas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte - Movimientos del Mes";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_movimientos_mes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reporte - Instructivo 04 Estado de Resultado";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_comparados_est_resultado.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reporte - Instructivo 07 Resumen de Inversiones";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_comparados_forma0714.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="009";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reporte - Instructivo 07 Balance General";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_comparados_balance_general.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="009";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCG";
$arbol["nivel"][$li_i]=3;
$arbol["nombre_logico"][$li_i]="Reporte - Instructivo 07 Origen y Aplicación de Fondos";
$arbol["nombre_fisico"][$li_i]="sigesp_scg_r_comparados_oaf.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="009";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;
$arbol["sistema"][$li_i]	   = "SCG";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Balance General";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_r_balance_general_consolidado.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "010";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;
$arbol["sistema"][$li_i]	   = "SCG";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Estado de Resultado";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_r_estado_resultado_consolidado.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "010";
$arbol["numero_hijos"][$li_i]  = 0;

$li_i++;
$arbol["sistema"][$li_i]	   = "SCG";
$arbol["nivel"][$li_i]		   = 2;
$arbol["nombre_logico"][$li_i] = "Balance de Comprobación";
$arbol["nombre_fisico"][$li_i] = "sigesp_scg_r_balance_comprobacion_consolidado.php";
$arbol["id"][$li_i]			   = $li_i;
$arbol["padre"][$li_i]		   = "010";
$arbol["numero_hijos"][$li_i]  = 0;

$gi_total=$li_i;
?>