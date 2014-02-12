<?php
$li_i=000;

$li_i++; // 001
$arbol["sistema"][$li_i]="RCM";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Proceso";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; 
$arbol["sistema"][$li_i]="RCM";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reconversion Monetaria";
$arbol["nombre_fisico"][$li_i]="sigesp_rcm_p_modulos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$gi_total=$li_i;

?>
