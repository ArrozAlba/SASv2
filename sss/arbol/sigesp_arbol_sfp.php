<?php
$li_i=000;
$li_i++; // 001


$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definiciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=9;

$li_i++; // 002
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=3;

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
$arbol["numero_hijos"][$li_i]=3;



$li_i++; // 005
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definción menú Empresa";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=3;


$li_i++; // 006
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos menú Empresa";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Empresa";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_empresa.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Plan General de Cuentas Integradas";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_integraciongeneral.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Cuentas de Variacion Patrimonial";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_variacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Formulación de Presupuesto";
$arbol["nombre_fisico"][$li_i]="sigesp_formulacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="006";
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
$arbol["nombre_logico"][$li_i]="Integración de Estructura";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_integracion.php";
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
$arbol["nombre_logico"][$li_i]="Definicion de Metas";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_metas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Naturaleza del Indicador";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_tipoindi.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definicion de Indicador";
$arbol["nombre_fisico"][$li_i]="sigesp_sfp_indicador.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Formulación de Ingresos";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_planfinan.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SFP";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Formulación de Gastos";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_formGasto.php";
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
$arbol["nombre_logico"][$li_i]="Carga de saldos de años anteriores";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_saldosant.php";
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
$arbol["nombre_logico"][$li_i]="Formatos del POA";
$arbol["nombre_fisico"][$li_i]="sigesp_spe_reportespoa.php";
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
$arbol["nombre_logico"][$li_i]="Transferencia de Datos";
$arbol["nombre_fisico"][$li_i]="sigesp_traspaso_form.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;


$gi_total=$li_i;

?>
