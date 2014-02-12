<?php

$li_i=000;

$li_i++; //001
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definiciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 002
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 003
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //004
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Históricos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; //005
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Pantalla en Construccion";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_construccion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=0;


//DEFINICIONES

$li_i++; //006
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Departamentos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_departamento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //007
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Secciones";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_seccion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 008
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Evaluaciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //009
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Puntuación Bono Mérito";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_puntuacion_bono_merito.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //010
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Causas llamadas de Atencion";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_causa_llamada_atencion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 011
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Cargos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 012
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Areas de Desempeño";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_area.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //013
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Profesión";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_profesion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //014
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipo de Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipopersonal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //015
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //016
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Niveles de Selección";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_nivelseleccion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //017
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Grupos de Movimientos del Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_grupomovimiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //018
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipos de Movimientos del Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_tipomovimientos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //019
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipos de Contratos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipocontrato.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //020
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipos de Accidentes";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipoaccidente.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //021
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipo de Enfermedades";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipoenfermedad.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //022
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipos de Documentos Legales";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipodocumento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //023
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Tipo Evaluación";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipoevaluacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //024
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Aspectos de la Evaluación";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_aspectos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //025
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Items a Evaluar";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_items.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++;  //026
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Escala de Evaluacion General";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_escalageneral.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //027
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Rango de Evaluacion";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_rango_evaluacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="008";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //028
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Tipos de Requerimientos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tiporequerimiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="011";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //029
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Definición de Requerimientos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_requerimiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="011";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //030
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Cargo";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_cargo.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="011";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //031
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Requerimiento por Cargo";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_requerimiento_cargo.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="011";
$arbol["numero_hijos"][$li_i]=0;

//PROCESOS

$li_i++; //032
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud de Empleo";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_solicitud_empleo.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 033
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar personas a Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_asignar_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 034
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Ganadores Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_ganadores_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; //035
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Evaluación Aspirantes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; //036
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Ascenso";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //037
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Pasantias";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //038
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Registro del Personal";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //039
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Adiestramientos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //040
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Evaluación de Personal";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=4;

$li_i++; //041
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Llamada de Atencion";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_llamada_atencion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //042
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Amonestación";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_amonestacion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //043
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Requisitos Minimos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_requisitos_minimos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="035";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //044
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Evaluacion Psicológica";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_evaluacion_psicologica.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="035";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //045
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Entrevista Técnica";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_entrevista_tecnica.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="035";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //046
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Resultados Evaluación Aspirante";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_resultados_evaluacion_aspirante.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="035";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //047
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Registro de Postulado para Ascenso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_registro_ascenso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="036";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //048
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Resultados Evaluación Ascenso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_evaluacion_ascenso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="036";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //049
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Incorporación de Pasantes";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_pasantias.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="037";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //050
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Evaluacion de Pasantias";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_evaluacion_pasantias.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="037";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //051
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Ingreso (Expediente)";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //052
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Movimientos del Personal ";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_movimiento_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //053
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Registro de Enfermedades";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_enfermedades.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //054
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Registro de Accidentes";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_accidentes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //055
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contratos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_contratos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; // 056
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Registro de Documentos Legales";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_documentos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //057
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Solicitud de Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_solicitud_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="039";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //058
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Detección de Necesidad de Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_necesidad_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="039";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //059
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Evaluación de Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_evaluacion_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="039";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //060
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Registro de ODIS";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_odi.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //061
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Revisiones ODIS";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_revisiones_odi.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //062
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Evaluación de Desempeño";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_evaluacion_desempeno.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //063
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Evaluación de Eficiencia";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_evaluacion_eficiencia.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //064
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Bono por Mérito";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_bono_merito.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //065
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Registro de Metas";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_registro_metas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //066
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Revisión de Metas";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_revision_metas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="040";
$arbol["numero_hijos"][$li_i]=0;

//REPORTES

$li_i++; // 067
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Concursos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 068
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Solicitud Empleo";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 069
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Evaluación de Aspirantes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; // 070
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Ascenso";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 071
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Registro Personal";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 072
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Pasantía";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 073
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Adiestramiento";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 074
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Evaluación Personal";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //075
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Llamadas de Atención";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; //076
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Amonestación";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;


$li_i++; //077
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Concursos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="067";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //078
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Participantes por Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_participantes_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="067";
$arbol["numero_hijos"][$li_i]=0;



$li_i++; //079
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Solicitudes de Empleo";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_solicitudes_empleo.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="068";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; //080
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Llamadas de Atención";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_llamadas_atencion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="075";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //081
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Amonestaciones";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_amonestaciones.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="076";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //082
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Pasantes";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_pasantes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="072";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //083
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Ascensos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_ascensos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="070";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; //084
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Evaluación de Desempeño";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_evaluacion_desempeno.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; //085
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Entrevista Técnica";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_entrevista_tecnica.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="069";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //086
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Evaluación Psicológica";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_evaluacionpsicologica.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="069";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //087
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Evaluación Requisitos Mínimos";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_evaluacion_reqmin.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="069";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; //088
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Bono por Merito";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_bono_x_merito.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //089
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Evaluación Por Metas";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_evaluaciones_meta.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //090
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Evaluación Eficiencia";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_evaluacioneficiencia.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //091
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Revisiones ODI";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_revisionesODI_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //092
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Revisiones Metas por Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_revisiones_metas_x_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //093
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="071";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //094
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Resultado de Evaluación de Ascenso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_resultado_ascensos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="070";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //095
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Accidentes";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_accidentes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="071";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //096
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Enfermedades";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_enfermedades.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="071";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //097
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Movimientos de Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_movimiento_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="071";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //098
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Resultado Evaluación de Pasantes";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_resultado_evaluacion_pasante.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="072";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //099
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Solicitud Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_solicitud_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="072";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; //100
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Evaluación Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_evaluacion_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="072";
$arbol["numero_hijos"][$li_i]=0;

////// OTRAS DEFINICIONES //////

//101
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipo Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipoconcurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

//102
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipo Deducción";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tipodeduccion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

//103
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Configuración de Deducción";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_configuracion_deduccion.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

//104
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Unidad VIPLADIN";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_uni_vipladin.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

//105
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Adiestramiento";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=2;

//106
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Causas de Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_causa_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="105";
$arbol["numero_hijos"][$li_i]=0;

//107
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Competencias Genéricas de Adiestramiento";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_competencia_adiestramiento.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="105";
$arbol["numero_hijos"][$li_i]=0;

////// OTROS REPORTES //////

//108
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reportes Estadísticos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=1;

//109
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Resultados Evaluación Desempeño";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_reporte_estadistico.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="108";
$arbol["numero_hijos"][$li_i]=0;

//110
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Deducciones por Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_deducciones_personal.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="071";
$arbol["numero_hijos"][$li_i]=0;

//111
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contratos de Personal";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_contratos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="071";
$arbol["numero_hijos"][$li_i]=0;

////// OTROS PROCESOS //////

//112
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Configuración Contrato";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_defcontrato.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="038";
$arbol["numero_hijos"][$li_i]=0;

////// OTRAS DEFINICIONES //////

//113
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Puntuacion por Unidad Tributaria";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_tablapuntosbonomerito.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

////// OTROS REPORTES //////

$li_i++; //114
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado Pago Bono por Merito";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_pago_bono_x_merito.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="074";
$arbol["numero_hijos"][$li_i]=0;

////// OTROS PROCESOS //////

//115
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Inscripcion Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_inscripcion_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

////// OTRAS DEFINICIONES //////

//116
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Requisitos por Concurso";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_requisitos_concurso.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

////// OTRO REPORTE //////

//117
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Listado de Concursante";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_r_listado_concursantes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="067";
$arbol["numero_hijos"][$li_i]=0;

////// OTRA DEFINICIÓN //////

$li_i++; //118
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Organigrama";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_organigrama.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

////// OTRO PROCESO //////

$li_i++; //119
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Consulta de Organigrama";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_p_consulta_organigrama.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;


//120
$li_i++; 
$arbol["sistema"][$li_i]="SRH";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definicion de Gerencias";
$arbol["nombre_fisico"][$li_i]="sigesp_srh_d_gerencia.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$gi_total=$li_i;


?>
