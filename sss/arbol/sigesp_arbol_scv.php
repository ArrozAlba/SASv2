<?php
$li_i=000;

$li_i++; // 001
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definiciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 002
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 003
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Configuración de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_config.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Categorías de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_categorias.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definicion de Rutas";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_rutas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definicion de Misiones";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_misiones.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Regiones";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_regiones.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definicion de Distancias entre Ciudades";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_distancias.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Registro de Tarifas de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_tarifas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Registro de Tarifas de Transporte";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_transporte.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Registro de Tarifas por Distancias";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_tarifasxdistancias.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Otras Asignaciones de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_d_otrasasignaciones.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_p_solicitudviaticos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Calculo de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_p_calcularviaticos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reverso de Calculo de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_p_revcalcularviaticos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud de Viaticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_r_solicitudes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud de Pago de Viaticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_r_solicitudespagoviatico.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Listado de Solicitudes de Viaticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_r_listadosolicitudes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SCV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Anulación de Solicitud de Viáticos";
$arbol["nombre_fisico"][$li_i]="sigesp_scv_p_anulacionsolicitud.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$gi_total=$li_i;

?>
