<?php

$gi_total=28;
$arbol["sistema"][1]="SPS";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Definiciones";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=4;

$arbol["sistema"][2]="SPS";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Articulos";
$arbol["nombre_fisico"][2]="sps_def_articulos.html.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="SPS";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Causas de Retiro";
$arbol["nombre_fisico"][3]="sps_def_causaretiro.html.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SPS";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Tasas de Interes";
$arbol["nombre_fisico"][4]="sps_def_tasainteres.html.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SPS";
$arbol["nivel"][5]=1;
$arbol["nombre_logico"][5]="Carta de Anticipo";
$arbol["nombre_fisico"][5]="sps_def_cartaanticipo.html.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="001";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SPS";
$arbol["nivel"][6]=0;
$arbol["nombre_logico"][6]="Procesos";
$arbol["nombre_fisico"][6]="";
$arbol["id"][6]="006";
$arbol["padre"][6]="000";
$arbol["numero_hijos"][6]=8;

$arbol["sistema"][7]="SPS";
$arbol["nivel"][7]=1;
$arbol["nombre_logico"][7]="Registro de Sueldos";
$arbol["nombre_fisico"][7]="sps_pro_sueldos.html.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="006";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SPS";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Registro Deuda Anterior";
$arbol["nombre_fisico"][8]="sps_pro_deudaanterior.html.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="006";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="SPS";
$arbol["nivel"][9]=1;
$arbol["nombre_logico"][9]="Anticipos";
$arbol["nombre_fisico"][9]="";
$arbol["id"][9]="009";
$arbol["padre"][9]="006";
$arbol["numero_hijos"][9]=2;

$arbol["sistema"][10]="SPS";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Solicitud de Anticipo";
$arbol["nombre_fisico"][10]="sps_pro_anticipos.html.php";
$arbol["id"][10]="0010";
$arbol["padre"][10]="009";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SPS";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Aprobacion Anticipo";
$arbol["nombre_fisico"][11]="sps_pro_aprobacionanticipos.html.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="009";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SPS";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Antiguedad";
$arbol["nombre_fisico"][12]="";
$arbol["id"][12]="012";
$arbol["padre"][12]="006";
$arbol["numero_hijos"][12]=2;

$arbol["sistema"][13]="SPS";
$arbol["nivel"][13]=2;
$arbol["nombre_logico"][13]="Calculo de Antiguedad";
$arbol["nombre_fisico"][13]="sps_pro_antiguedad.html.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="012";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="SPS";
$arbol["nivel"][14]=2;
$arbol["nombre_logico"][14]="Antiguedad por Nomina";
$arbol["nombre_fisico"][14]="sps_pro_antig_nomina.html.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="012";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SPS";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Liquidacion";
$arbol["nombre_fisico"][15]="";
$arbol["id"][15]="015";
$arbol["padre"][15]="006";
$arbol["numero_hijos"][15]=2;

$arbol["sistema"][16]="SPS";
$arbol["nivel"][16]=2;
$arbol["nombre_logico"][16]="Liquidacion";
$arbol["nombre_fisico"][16]="sps_pro_liquidaciones.html.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="015";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="SPS";
$arbol["nivel"][17]=2;
$arbol["nombre_logico"][17]="Aprobacion Liquidacion";
$arbol["nombre_fisico"][17]="sps_pro_aprobacionliquidacion.html.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="015";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SPS";
$arbol["nivel"][18]=0;
$arbol["nombre_logico"][18]="Reportes";
$arbol["nombre_fisico"][18]="";
$arbol["id"][18]="018";
$arbol["padre"][18]="000";
$arbol["numero_hijos"][18]=5;

$arbol["sistema"][19]="SPS";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Detalle Antiguedad";
$arbol["nombre_fisico"][19]="sps_rep_detalle_antiguedad.html.php";
$arbol["id"][19]="019";
$arbol["padre"][19]="018";
$arbol["numero_hijos"][19]=0;

$arbol["sistema"][20]="SPS";
$arbol["nivel"][20]=1;
$arbol["nombre_logico"][20]="Liquidacion de P. Social";
$arbol["nombre_fisico"][20]="sps_rep_liquidacion.html.php";
$arbol["id"][20]="020";
$arbol["padre"][20]="018";
$arbol["numero_hijos"][20]=0;

$arbol["sistema"][21]="SPS";
$arbol["nivel"][21]=1;
$arbol["nombre_logico"][21]="Anticipo de P. Social";
$arbol["nombre_fisico"][21]="sps_rep_anticipo.html.php";
$arbol["id"][21]="021";
$arbol["padre"][21]="018";
$arbol["numero_hijos"][21]=0;

$arbol["sistema"][22]="SPS";
$arbol["nivel"][22]=1;
$arbol["nombre_logico"][22]="Sueldos Historicos";
$arbol["nombre_fisico"][22]="sps_rep_detalle_sueldos.html.php";
$arbol["id"][22]="022";
$arbol["padre"][22]="018";
$arbol["numero_hijos"][22]=0;

$arbol["sistema"][23]="SPS";
$arbol["nivel"][23]=1;
$arbol["nombre_logico"][23]="Deuda por P. Social";
$arbol["nombre_fisico"][23]="sps_rep_deuda_ps.html.php";
$arbol["id"][23]="023";
$arbol["padre"][23]="018";
$arbol["numero_hijos"][23]=0;

$arbol["sistema"][24]="SPS";
$arbol["nivel"][24]=0;
$arbol["nombre_logico"][24]="Mantenimiento";
$arbol["nombre_fisico"][24]="";
$arbol["id"][24]="024";
$arbol["padre"][24]="000";
$arbol["numero_hijos"][24]=1;

$arbol["sistema"][25]="SPS";
$arbol["nivel"][25]=1;
$arbol["nombre_logico"][25]="Configuracion";
$arbol["nombre_fisico"][25]="sps_def_configuracion.html.php";
$arbol["id"][25]="025";
$arbol["padre"][25]="024";
$arbol["numero_hijos"][25]=0;

$arbol["sistema"][26]="SPS";
$arbol["nivel"][26]=1;
$arbol["nombre_logico"][26]="Convertidor Data IPSFA";
$arbol["nombre_fisico"][26]="";
$arbol["id"][26]="026";
$arbol["padre"][26]="024";
$arbol["numero_hijos"][26]=2;

$arbol["sistema"][27]="SPS";
$arbol["nivel"][27]=2;
$arbol["nombre_logico"][27]="Convertir Antiguedad";
$arbol["nombre_fisico"][27]="sps_conv_prestaciones.html.php";
$arbol["id"][27]="027";
$arbol["padre"][27]="026";
$arbol["numero_hijos"][27]=0;

$arbol["sistema"][28]="SPS";
$arbol["nivel"][28]=2;
$arbol["nombre_logico"][28]="Convertir Anticipos";
$arbol["nombre_fisico"][28]="sps_conv_anticipos.html.php";
$arbol["id"][28]="028";
$arbol["padre"][28]="026";
$arbol["numero_hijos"][28]=0;

?>
