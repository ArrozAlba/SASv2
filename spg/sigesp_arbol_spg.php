<?php
$gi_total=43;
$arbol["sistema"][1]="SPG";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=4;

$arbol["sistema"][2]="SPG";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Comprobantes";
$arbol["nombre_fisico"][2]="";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=1;

$arbol["sistema"][3]="SPG";
$arbol["nivel"][3]=2;
$arbol["nombre_logico"][3]="Ejecución Financiera";
$arbol["nombre_fisico"][3]="sigesp_spg_p_comprobante.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="002";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SPG";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Apertura";
$arbol["nombre_fisico"][4]="";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=2;

$arbol["sistema"][5]="SPG";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Mensual";
$arbol["nombre_fisico"][5]="sigesp_spg_p_apertura.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="004";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SPG";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Trimestral";
$arbol["nombre_fisico"][6]="sigesp_spg_p_apertura_trim.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="004";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="SPG";
$arbol["nivel"][7]=1;
$arbol["nombre_logico"][7]="Modificaciones Presupuestarias";
$arbol["nombre_fisico"][7]="";
$arbol["id"][7]="007";
$arbol["padre"][7]="001";
$arbol["numero_hijos"][7]=4;

$arbol["sistema"][8]="SPG";
$arbol["nivel"][8]=2;
$arbol["nombre_logico"][8]="Rectificaciones";
$arbol["nombre_fisico"][8]="sigesp_spg_p_rectificaciones.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="007";
$arbol["numero_hijos"][8]=0;


$arbol["sistema"][9]="SPG";
$arbol["nivel"][9]=2;
$arbol["nombre_logico"][9]="Insubsistencias";
$arbol["nombre_fisico"][9]="sigesp_spg_p_insubsistencias.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="007";
$arbol["numero_hijos"][9]=0;

$arbol["sistema"][10]="SPG";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Traspasos";
$arbol["nombre_fisico"][10]="sigesp_spg_p_traspaso.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="007";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SPG";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Credito/Ingreso Adicional";
$arbol["nombre_fisico"][11]="sigesp_spg_p_adicional.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="007";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SPG";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Programación de Reportes";
$arbol["nombre_fisico"][12]="";
$arbol["id"][12]="012";
$arbol["padre"][12]="001";
$arbol["numero_hijos"][12]=2;

$arbol["sistema"][13]="SPG";
$arbol["nivel"][13]=2;
$arbol["nombre_logico"][13]="Mensual";
$arbol["nombre_fisico"][13]="sigesp_spg_p_progrep.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="012";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="SPG";
$arbol["nivel"][14]=2;
$arbol["nombre_logico"][14]="Trimestral";
$arbol["nombre_fisico"][14]="sigesp_spg_p_progrep_trim.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="012";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SPG";
$arbol["nivel"][15]=0;
$arbol["nombre_logico"][15]="Reportes";
$arbol["nombre_fisico"][15]="";
$arbol["id"][15]="015";
$arbol["padre"][15]="000";
$arbol["numero_hijos"][15]=1;

$arbol["sistema"][16]="SPG";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Acumulado por Cuentas";
$arbol["nombre_fisico"][16]="sigesp_spg_r_acum_x_cuentas.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="015";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="SPG";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Mayor Analitico";
$arbol["nombre_fisico"][17]="sigesp_spg_r_mayor_analitico.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="015";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SPG";
$arbol["nivel"][18]=1;
$arbol["nombre_logico"][18]="Listado de Apertura";
$arbol["nombre_fisico"][18]="sigesp_spg_r_listado_apertura.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="015";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]="SPG";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Modificaciones Presupuestarias No Aprobadas";
$arbol["nombre_fisico"][19]="sigesp_spg_r_modificaciones_presupuestarias.php";
$arbol["id"][19]="019";
$arbol["padre"][19]="015";
$arbol["numero_hijos"][19]=0;

$arbol["sistema"][20]="SPG";
$arbol["nivel"][20]=1;
$arbol["nombre_logico"][20]="Comprobante Formato 1";
$arbol["nombre_fisico"][20]="sigesp_spg_r_comprobante_formato1.php";
$arbol["id"][20]="020";
$arbol["padre"][20]="015";
$arbol["numero_hijos"][20]=0;

$arbol["sistema"][21]="SPG";
$arbol["nivel"][21]=1;
$arbol["nombre_logico"][21]="Comprobante Formato 2";
$arbol["nombre_fisico"][21]="sigesp_spg_r_comprobante_formato2.php";
$arbol["id"][21]="021";
$arbol["padre"][21]="015";
$arbol["numero_hijos"][21]=0;

$arbol["sistema"][22]="SPG";
$arbol["nivel"][22]=1;
$arbol["nombre_logico"][22]="Disponibilidad Presupuestaria";
$arbol["nombre_fisico"][22]="sigesp_spg_r_disponibilidad.php";
$arbol["id"][22]="022";
$arbol["padre"][22]="015";
$arbol["numero_hijos"][22]=0;

$arbol["sistema"][23]="SPG";
$arbol["nivel"][23]=1;
$arbol["nombre_logico"][23]="Listado de Cuentas Presupuestarias";
$arbol["nombre_fisico"][23]="sigesp_spg_r_cuentas.php";
$arbol["id"][23]="023";
$arbol["padre"][23]="015";
$arbol["numero_hijos"][23]=0;

$arbol["sistema"][24]="SPG";
$arbol["nivel"][24]=1;
$arbol["nombre_logico"][24]="Resumen del Presupuesto de Gasto Por Partida(0704)";
$arbol["nombre_fisico"][24]="sigesp_spg_r_comparados_forma0704.php";
$arbol["id"][24]="024";
$arbol["padre"][24]="015";
$arbol["numero_hijos"][24]=0;

$arbol["sistema"][25]="SPG";
$arbol["nivel"][25]=1;
$arbol["nombre_logico"][25]="Resumen del Presupuesto (0705)";
$arbol["nombre_fisico"][25]="sigesp_spg_r_comparados_forma0705.php";
$arbol["id"][25]="025";
$arbol["padre"][25]="015";
$arbol["numero_hijos"][25]=0;

$arbol["sistema"][26]="SPG";
$arbol["nivel"][26]=1;
$arbol["nombre_logico"][26]="Ejecución Financiera  del Presupuesto de Gastos(0707)";
$arbol["nombre_fisico"][26]="sigesp_spg_r_comparados_ejecucion_financiera_formato3.php";
$arbol["id"][26]="026";
$arbol["padre"][26]="015";
$arbol["numero_hijos"][26]=0;

$arbol["sistema"][27]="SPG";
$arbol["nivel"][27]=1;
$arbol["nombre_logico"][27]="Ejecución Financiera Mensual del Presupuesto de Gastos(0402)";
$arbol["nombre_fisico"][27]="sigesp_spg_r_comparados_forma0402.php";
$arbol["id"][27]="027";
$arbol["padre"][27]="015";
$arbol["numero_hijos"][27]=0;

$arbol["sistema"][28]="SPG";
$arbol["nivel"][28]=1;
$arbol["nombre_logico"][28]="Ejecución Financiera de los Proyectos del Ente(0413)";
$arbol["nombre_fisico"][28]="sigesp_spg_r_comparados_forma0413.php";
$arbol["id"][28]="028";
$arbol["padre"][28]="015";
$arbol["numero_hijos"][28]=0;

$arbol["sistema"][29]="SPG";
$arbol["nivel"][29]=1;
$arbol["nombre_logico"][29]="Ejecución Financiera de las Acciones Centralizadas del Ente(0414)";
$arbol["nombre_fisico"][29]="sigesp_spg_r_comparados_forma0414.php";
$arbol["id"][29]="029";
$arbol["padre"][29]="015";
$arbol["numero_hijos"][29]=0;

$arbol["sistema"][30]="SPG";
$arbol["nivel"][30]=1;
$arbol["nombre_logico"][30]="Ejecución Financiera de las Acciones Especificas(0415)";
$arbol["nombre_fisico"][30]="sigesp_spg_r_comparados_forma0415.php";
$arbol["id"][30]="030";
$arbol["padre"][30]="015";
$arbol["numero_hijos"][30]=0;

$arbol["sistema"][31]="SPG";
$arbol["nivel"][31]=1;
$arbol["nombre_logico"][31]="Presupuesto de Caja (0717)";
$arbol["nombre_fisico"][31]="sigesp_spg_r_comparados_forma0717.php";
$arbol["id"][31]="031";
$arbol["padre"][31]="015";
$arbol["numero_hijos"][31]=0;

$arbol["sistema"][32]="SPG";
$arbol["nivel"][32]=1;
$arbol["nombre_logico"][32]="Distribucion Mensual del Presupuesto";
$arbol["nombre_fisico"][32]="sigesp_spg_r_distribucion_mensual_presupuesto.php";
$arbol["id"][32]="032";
$arbol["padre"][32]="015";
$arbol["numero_hijos"][32]=0;

$arbol["sistema"][33]="SPG";
$arbol["nivel"][33]=1;
$arbol["nombre_logico"][33]="Unidades Ejecutoras";
$arbol["nombre_fisico"][33]="sigesp_spg_r_unidades_ejecutoras.php";
$arbol["id"][33]="033";
$arbol["padre"][33]="015";
$arbol["numero_hijos"][33]=0;

$arbol["sistema"][34]="SPG";
$arbol["nivel"][34]=1;
$arbol["nombre_logico"][34]="Ejecucion de Compromisos";
$arbol["nombre_fisico"][34]="sigesp_spg_r_ejecucion_compromisos.php";
$arbol["id"][34]="034";
$arbol["padre"][34]="015";
$arbol["numero_hijos"][34]=0;

$arbol["sistema"][35]="SPG";
$arbol["nivel"][35]=1;
$arbol["nombre_logico"][35]="Compromisos no Causados";
$arbol["nombre_fisico"][35]="sigesp_spg_r_compromisos_no_causados.php";
$arbol["id"][35]="035";
$arbol["padre"][35]="015";
$arbol["numero_hijos"][35]=0;

$arbol["sistema"][36]="SPG";
$arbol["nivel"][36]=1;
$arbol["nombre_logico"][36]="Causados Parcialmente";
$arbol["nombre_fisico"][36]="sigesp_spg_r_compromisos_causados_parcialmente.php";
$arbol["id"][36]="036";
$arbol["padre"][36]="015";
$arbol["numero_hijos"][36]=0;

$arbol["sistema"][37]="SPG";
$arbol["nivel"][37]=1;
$arbol["nombre_logico"][37]="Compromisos Causados no Pagados";
$arbol["nombre_fisico"][37]="sigesp_spg_r_compromisos_causados_no_pagados.php";
$arbol["id"][37]="037";
$arbol["padre"][37]="015";
$arbol["numero_hijos"][37]=0;

$arbol["sistema"][38]="SPG";
$arbol["nivel"][38]=1;
$arbol["nombre_logico"][38]="Operacion por Especifica";
$arbol["nombre_fisico"][38]="sigesp_spg_r_operacion_por_especifica.php";
$arbol["id"][38]="038";
$arbol["padre"][38]="015";
$arbol["numero_hijos"][38]=0;

$arbol["sistema"][39]="SPG";
$arbol["nivel"][39]=1;
$arbol["nombre_logico"][39]="Ejecutado por Partida";
$arbol["nombre_fisico"][39]="sigesp_spg_r_ejecutado_por_partida.php";
$arbol["id"][39]="039";
$arbol["padre"][39]="015";
$arbol["numero_hijos"][39]=0;

$arbol["sistema"][40]="SPG";
$arbol["nivel"][40]=1;
$arbol["nombre_logico"][40]="Operación por Banco";
$arbol["nombre_fisico"][40]="sigesp_spg_r_operacion_por_banco.php";
$arbol["id"][40]="040";
$arbol["padre"][40]="015";
$arbol["numero_hijos"][40]=0;

$arbol["sistema"][41]="SPG";
$arbol["nivel"][41]=1;
$arbol["nombre_logico"][41]="Resumen Proveedor/Beneficiario";
$arbol["nombre_fisico"][41]="sigesp_spg_r_resumen_prov_bene.php";
$arbol["id"][41]="041";
$arbol["padre"][41]="015";
$arbol["numero_hijos"][41]=0;

$arbol["sistema"][42]="SPG";
$arbol["nivel"][42]=1;
$arbol["nombre_logico"][42]="Modificaciones Presupuestarias Aprobadas";
$arbol["nombre_fisico"][42]="sigesp_spg_r_modificaciones_presupuestarias_aprobadas.php";
$arbol["id"][42]="042";
$arbol["padre"][42]="015";
$arbol["numero_hijos"][42]=0;

$arbol["sistema"][43]="SPG";
$arbol["nivel"][43]=1;
$arbol["nombre_logico"][43]="Disponibilidad Presupuestaria Formato # 2";
$arbol["nombre_fisico"][43]="sigesp_spg_r_disponibilidad_formato2.php";
$arbol["id"][43]="043";
$arbol["padre"][43]="015";
$arbol["numero_hijos"][43]=0;
?>