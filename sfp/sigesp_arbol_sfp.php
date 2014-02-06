<?php
$li_i=000;
$li_i++; // 001
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definiciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 002
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 003
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 004
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Configuracion";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Fuente de Financimiento";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_fuentefin.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definicion del Plan";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_estprog.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de la Estructura Presupuestaria";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_estprog.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Unidades Administrativas";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_esadmin.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Ubicacion Geográfica";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_ubgeo.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Unidad de Medida";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_unimedida.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Problemas";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_problemas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Formulación de las fuentes de financiamiento";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_planfinan.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Integracón presupuestaria";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_inteprog.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Carga de saldos contables";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_cargasaldos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Formatos del instructivo";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_reportes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Configuración de niveles";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_con_estprog.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Plan de Cuentas";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_conCuentas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Variación Patrimonial";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_variacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;
$gi_total=$li_i;

?>
