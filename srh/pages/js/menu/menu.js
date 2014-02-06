
stm_bm(["menu08dd",430,"","../../../public/imagenes/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);


// Menú Principal- Definiciones
stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,7,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
/*stm_aix("p1i0","p0i0",[0,"Unidades Administrativas","","",-1,-1,0,"sigesp_srh_construccion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);*/

//stm_aix("p1i0","p0i0",[0,"Unidad VIPLADIN","","",-1,-1,0,"sigesp_srh_d_uni_vipladin.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"Organigrama","","",-1,-1,0,"sigesp_srh_d_organigrama.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p1i0","p0i0",[0,"Unidad VIPLADIN","","",-1,-1,0,"sigesp_srh_d_uni_vipladin.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"Gerencias","","",-1,-1,0,"sigesp_srh_d_gerencia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_aix("p1i0","p0i0",[0,"Departamentos","","",-1,-1,0,"sigesp_srh_d_departamento.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"Secciones","","",-1,-1,0,"sigesp_srh_d_seccion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);



stm_aix("p4i0","p1i0",[0,"Evaluaciones    ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel

stm_aix("p3i2","p1i0",[0,"  Escala de Evaluación","","",-1,-1,0,"sigesp_srh_d_escalageneral.php","_self","","","","",0]);
stm_aix("p3i0","p3i2",[0,"  Tipo de Evaluaci&oacute;n   ","","",-1,-1,0,"sigesp_srh_d_tipoevaluacion.php"]);
stm_aix("p3i0","p3i2",[0,"  Aspectos a Evaluar ","","",-1,-1,0,"sigesp_srh_d_aspectos.php"]);
stm_aix("p3i1","p3i0",[0,"  Items a Evaluar","","",-1,-1,0,"sigesp_srh_d_items.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);


stm_aix("p4i0","p1i0",[0,"Adiestramiento    ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i2","p1i0",[0,"Causas de Adiestramiento   ","","",-1,-1,0,"sigesp_srh_d_causa_adiestramiento.php","_self","","","","",0]);
stm_aix("p3i0","p3i2",[0,"Competencias Genéricas de Adiestramiento ","","",-1,-1,0,"sigesp_srh_d_competencia_adiestramiento.php"]);

stm_ep()


//----


//---
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p4i0","p1i0",[0,"Bono por Mérito   ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);

stm_bpx("p3","p1",[1,2,0,0,2,3,0]);


stm_aix("p3i1","p3i0",[0,"Tipo de Personal            ","","",-1,-1,0,"sigesp_srh_d_tipopersonal.php"]);
stm_aix("p3i1","p3i0",[0,"Puntuación Bono Mérito","","",-1,-1,0,"sigesp_srh_d_puntuacion_bono_merito.php"]);
stm_aix("p3i1","p3i0",[0,"Puntuación por Unidad Tributaria ","","",-1,-1,0,"sigesp_srh_d_tablapuntosbonomerito.php"]);
stm_ep();

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);


stm_aix("p3i1","p3i0",[0,"Causas Llamada de Atención / Amonestación","","",-1,-1,0,"sigesp_srh_d_causa_llamada_atencion.php"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i1","p3i0",[0,"Constancias de Trabajo","","",-1,-1,0,"sigespwindow_blank.php?opener=constancia_trab"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);


stm_aix("p3i0","p3i2",[0,"Tabulador ","","",-1,-1,0,"sigespwindow_blank.php?opener=tabulador"]);
stm_aix("p3i1","p3i0",[0,"Asignacion de Cargos     ","","",-1,-1,0,"sigespwindow_blank.php?opener=asignacion_cargo"]);


stm_aix("p4i0","p1i0",[0,"Cargos    ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);

stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel

stm_aix("p3i2","p1i0",[0,"  Cargos ","","",-1,-1,0,"sigesp_srh_d_cargo.php","_self","","","","",0]);
stm_aix("p3i1","p3i0",[0,"  Tipo de Requerimientos     ","","",-1,-1,0,"sigesp_srh_d_tiporequerimiento.php"]);
stm_aix("p3i0","p3i2",[0,"  Requerimientos ","","",-1,-1,0,"sigesp_srh_d_requerimiento.php"]);
stm_aix("p3i0","p3i2",[0,"  Requerimientos por Cargos   ","","",-1,-1,0,"sigesp_srh_d_requerimiento_cargo.php"]);


stm_ep();

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p4i0","p1i0",[0,"Areas de Desempeño   ","","",-1,-1,0,"sigesp_srh_d_area.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Profesión               ","","",-1,-1,0,"sigesp_srh_d_profesion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i1","p3i0",[0,"Nivel de Selección","","",-1,-1,0,"sigesp_srh_d_nivelseleccion.php"]);

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);



stm_aix("p4i0","p1i0",[0,"Concursos    ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel

stm_aix("p3i2","p1i0",[0,"  Registro de Concurso","","",-1,-1,0,"sigesp_srh_d_concurso.php","_self","","","","",0]);
stm_aix("p3i0","p3i2",[0,"  Requisitos de Concurso ","","",-1,-1,0,"sigesp_srh_d_requisitos_concurso.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);








stm_aix("p3i1","p3i0",[0,"Grupo de Movimientos del Personal","","",-1,-1,0,"sigesp_srh_d_grupomovimiento.php"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i1","p3i0",[0,"Tipos Deducción","","",-1,-1,0,"sigesp_srh_d_tipodeduccion.php"]);
stm_aix("p3i2","p3i0",[0,"Configuración Deducción ","","",-1,-1,0,"sigesp_srh_d_configuracion_deduccion.php"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i1","p3i0",[0,"Tipos de Contratos","","",-1,-1,0,"sigesp_srh_d_tipocontrato.php"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i1","p3i0",[0,"Tipos de Accidentes ","","",-1,-1,0,"sigesp_srh_d_tipoaccidente.php"]);
stm_aix("p3i1","p3i0",[0,"Tipos de Enfermedades ","","",-1,-1,0,"sigesp_srh_d_tipoenfermedad.php"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i1","p3i0",[0,"Tipos de Documentos Legales","","",-1,-1,0,"sigesp_srh_d_tipodocumento.php"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i1","p3i0",[0,"Causas de Retiro","","",-1,-1,0,"sigespwindow_blank.php?opener=causas_ret"]);




stm_ep();

// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"Solicitud de Empleo ","","",-1,-1,0,"sigesp_srh_p_solicitud_empleo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p1i0","p0i0",[0,"Inscripción a Concurso ","","",-1,-1,0,"sigesp_srh_p_inscripcion_concurso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Elegibles - Opciones de Tercer Nivel
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Evaluación de Aspirantes ","","",-1,-1,0,"","","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",0,0,0,0,1,"#ffffff"]);

stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0,"  Requisitos Mínimos y Educación ","","",-1,-1,0,"sigesp_srh_p_requisitos_minimos.php","_self"]);
stm_aix("p1i4","p1i0",[0,"  Evaluación Psicológica ","","",-1,-1,0,"sigesp_srh_p_evaluacion_psicologica.php","_self"]);
stm_aix("p1i4","p1i0",[0,"  Entrevista Técnica","","",-1,-1,0,"sigesp_srh_p_entrevista_tecnica.php","_self"]);
stm_aix("p1i4","p1i0",[0,"  Resultados de Evaluación","","",-1,-1,0,"sigesp_srh_p_resultados_evaluacion_aspirante.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);


stm_aix("p1i4","p1i0",[0,"Ascenso","","",-1,-1,0,"","","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);

stm_aix("p3i1","p3i0",[0,"  Registro ","","",-1,-1,0,"sigesp_srh_p_registro_ascenso.php"]);
stm_aix("p3i1","p3i0",[0,"  Resultados de Evaluacion ","","",-1,-1,0,"sigesp_srh_p_evaluacion_ascenso.php"]);


stm_ep();

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p6i2","p1i0",[0,"Ganadores por Concurso","","",-1,-1,0,"sigesp_srh_p_ganadores_concurso.php","_self","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p6i2","p1i0",[0,"Pasantías   ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);

stm_aix("p3i0","p1i0",[0,"  Incorporación de Pasantes  ","","",-1,-1,0,"sigesp_srh_p_pasantias.php","_self","","","","",0]);
stm_aix("p3i1","p3i0",[0,"  Evaluación de Pasantías  ","","",-1,-1,0,"sigesp_srh_p_evaluacion_pasantias.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i2","p1i0",[0,"Registros del Personal","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i2","p3i0",[0,"  Ingresos (Expediente) ","","",-1,-1,0,"sigesp_srh_d_personal.php"]);

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i2","p1i0",[0,"  Beneficios","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i2","p3i0",[0,"  Registro de Contratos","","",-1,-1,0,"sigesp_srh_p_contratos.php"]);
stm_aix("p3i2","p3i0",[0,"  Configuración Contrato ","","",-1,-1,0,"sigesp_srh_d_defcontrato.php"]);
stm_aix("p3i2","p3i0",[0,"  Documentos Legales ","","",-1,-1,0,"sigesp_srh_p_documentos.php"]);
//stm_aix("p3i2","p3i0",[0,"  Consulta Documentos Legales ","","",-1,-1,0,"sigesp_srh_construccion.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i2","p3i0",[0,"  Movimientos de Personal  ","","",-1,-1,0,"sigesp_srh_p_movimiento_personal.php"]);
stm_aix("p3i2","p3i0",[0,"  Accidentes  ","","",-1,-1,0,"sigesp_srh_p_accidentes.php"]);
stm_aix("p3i2","p3i0",[0,"  Enfermedades  ","","",-1,-1,0,"sigesp_srh_p_enfermedades.php"]);
stm_aix("p3i2","p3i0",[0,"  Cambio Estatus Personal  ","","",-1,-1,0,"sigespwindow_blank.php?opener=cambio_estatus"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i2","p1i0",[0,"Adiestramientos","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i2","p3i0",[0,"  Necesidad de Adiestramiento","","",-1,-1,0,"sigesp_srh_p_necesidad_adiestramiento.php"]);
stm_aix("p3i2","p3i0",[0,"  Solicitud de Adiestramiento","","",-1,-1,0,"sigesp_srh_p_solicitud_adiestramiento.php"]);
stm_aix("p3i2","p3i0",[0,"  Evaluar Adiestramientos","","",-1,-1,0,"sigesp_srh_p_evaluacion_adiestramiento.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i2","p1i0",[0,"Evaluación de Personal ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);


stm_aix("p6i2","p1i0",[0,"  Objetivos de Desempeño Individual","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);

stm_aix("p3i2","p3i0",[0,"  Registro       ","","",-1,-1,0,"sigesp_srh_p_odi.php"]);
stm_aix("p3i2","p3i0",[0,"  Revisiones   ","","",-1,-1,0,"sigesp_srh_p_revisiones_odi.php"]);
stm_ep();
stm_aix("p3i1","p3i0",[0,"  Evaluación de Desempeño ","","",-1,-1,0,"sigesp_srh_p_evaluacion_desempeno.php"]);
stm_aix("p3i1","p3i0",[0,"  Evaluación de Eficiencia ","","",-1,-1,0,"sigesp_srh_p_evaluacion_eficiencia.php"]);
stm_aix("p3i1","p3i0",[0,"  Bonos por Mérito","","",-1,-1,0,"sigesp_srh_p_bono_merito.php"]);
stm_aix("p6i2","p1i0",[0,"  Evaluación de Desempeño por Metas","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);

stm_bpx("p3","p1",[1,2,0,0,2,3,0]);

stm_aix("p3i2","p3i0",[0,"  Registro Metas       ","","",-1,-1,0,"sigesp_srh_p_registro_metas.php"]);
stm_aix("p3i2","p3i0",[0,"  Revision Metas   ","","",-1,-1,0,"sigesp_srh_p_revision_metas.php"]);

stm_ep();

stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0," Amonestación / Llamadas de Atención ","","",-1,-1,0,"sigesp_srh_p_llamada_atencion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);


stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0," Consulta Organigrama ","","",-1,-1,0,"sigesp_srh_p_consulta_organigrama.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
//stm_aix("p6i0","p1i0",[0,"Reportes 1    "]);
stm_aix("p6i2","p1i0",[0,"Concursos    ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0,"  Listado de Concursos    ","","",-1,-1,0,"sigesp_srh_r_listado_concurso.php","_self","","","","",0]);
stm_aix("p3i1","p3i0",[0,"  Participantes por Concurso ","","",-1,-1,0,"sigesp_srh_r_listado_concursantes.php"]);
stm_aix("p3i1","p3i0",[0,"  Ganadores por Concurso ","","",-1,-1,0,"sigesp_srh_r_participantes_concurso.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0,"Solicitud de Empleo","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Cuarto Nivel
stm_aix("p3i0","p1i0",[0,"  Listado de Solicitudes     ","","",-1,-1,0,"sigesp_srh_r_listado_solicitudes_empleo.php","_self","","","","",0]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0,"Evaluación de Aspirantes     ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Cuarto Nivel
stm_aix("p3i0","p1i0",[0,"  Listado Evaluación de Requisitos Mínimos  ","","",-1,-1,0,"sigesp_srh_r_listado_evaluacion_reqmin.php"]);
stm_aix("p3i0","p1i0",[0,"  Listado Evaluación Psicológica  ","","",-1,-1,0,"sigesp_srh_r_listado_evaluacionpsicologica.php"]);

stm_aix("p3i0","p1i0",[0,"  Listado Evaluación Entrevista Técnica  ","","",-1,-1,0,"sigesp_srh_r_listado_entrevista_tecnica.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0,"Ascensos     ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Cuarto Nivel
stm_aix("p3i0","p1i0",[0,"  Listado de Ascensos ","","",-1,-1,0,"sigesp_srh_r_listado_ascensos.php"]);
stm_aix("p3i0","p1i0",[0,"  Resultado Evaluación de Ascenso (Baremo)  ","","",-1,-1,0,"sigesp_srh_r_resultado_ascensos.php"]);


stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i0","p1i0",[0,"Registros del Personal     ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Cuarto Nivel
stm_aix("p3i0","p1i0",[0,"  Listado de Personal    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_1"]);
stm_aix("p3i0","p1i0",[0,"  Listado de Personal Contratado  ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_2"]);
stm_aix("p3i0","p1i0",[0,"  Listado de Personal con Unidades Administrativas    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_3"]);
stm_aix("p3i0","p1i0",[0,"  Listado de Personal con Unidades VIPLADIN    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_12"]);
stm_aix("p3i0","p1i0",[0,"  Listado de Personal Genérico    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_4"]);
stm_aix("p3i0","p1i0",[0,"  Listado de Personal por Componente   ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_5"]);
stm_aix("p3i0","p1i0",[0,"  Ficha de Personal    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_6"]);
stm_aix("p3i0","p1i0",[0,"  Cumpleañeros    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_7"]);
stm_aix("p3i0","p1i0",[0,"  Familiares   ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_8"]);
stm_aix("p3i0","p1i0",[0,"  Vacaciones    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_9"]);
stm_aix("p3i0","p1i0",[0,"  Credenciales   ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_10"]);
stm_aix("p3i0","p1i0",[0,"  Antiguedad    ","","",-1,-1,0,"sigespwindow_blank.php?opener=listado_personal_11"]);



stm_aix("p3i4","p3i0",[0,"  Contratos         ","","",-1,-1,0,"sigesp_srh_r_contratos.php"]);
stm_aix("p3i4","p3i0",[0,"  Constancia de Trabajo      ","","",-1,-1,0,"sigespwindow_blank.php?opener=rep_constancia_trab"]);
stm_aix("p3i4","p3i0",[0,"  Constancia de Trabajo para el I.V.S.S. (14-100)    ","","",-1,-1,0,"sigespwindow_blank.php?opener=rep_constancia_trab_sso"]);
stm_aix("p3i4","p3i0",[0,"  Listado de Deducciones por Personal  ","","",-1,-1,0,"sigesp_srh_r_listado_deducciones_personal.php"]);
stm_aix("p3i4","p3i0",[0,"  Listado de Movimientos de Personal  ","","",-1,-1,0,"sigesp_srh_r_listado_movimiento_personal.php"]);
stm_aix("p3i4","p3i0",[0,"  Listado de Accidentes                 ","","",-1,-1,0,"sigesp_srh_r_listado_accidentes.php"]);
stm_aix("p3i4","p3i0",[0,"  Listado de Enfermedades               ","","",-1,-1,0,"sigesp_srh_r_listado_enfermedades.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i2","p1i0",[0,"Pasantía","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i2","p3i0",[0,"  Listado de Pasantes","","",-1,-1,0,"sigesp_srh_r_listado_pasantes.php"]);
stm_aix("p3i2","p3i0",[0,"  Resultado de Evaluación por Pasante","","",-1,-1,0,"sigesp_srh_r_resultado_evaluacion_pasante.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p6i2","p1i0",[0,"Adiestramiento","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i2","p3i0",[0,"  Listado de Soliciutdes de Adiestramiento","","",-1,-1,0,"sigesp_srh_r_listado_solicitud_adiestramiento.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Evaluación de Adiestramiento","","",-1,-1,0,"sigesp_srh_r_listado_evaluacion_adiestramiento.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p6i2","p1i0",[0,"Evaluación de Personal","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i2","p3i0",[0,"  Listado Revisiones de ODI   ","","",-1,-1,0,"sigesp_srh_r_listado_revisionesODI_personal.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Evaluaciones de Desemepeño   ","","",-1,-1,0,"sigesp_srh_r_listado_evaluacion_desempeno.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Evaluaciones de Eficiencia    ","","",-1,-1,0,"sigesp_srh_r_listado_evaluacioneficiencia.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Bono por Mérito    ","","",-1,-1,0,"sigesp_srh_r_listado_bono_x_merito.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Pagos Bono por Mérito     ","","",-1,-1,0,"sigesp_srh_r_listado_pago_bono_x_merito.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Evaluaciones por Meta   ","","",-1,-1,0,"sigesp_srh_r_listado_evaluaciones_meta.php"]);
stm_aix("p3i2","p3i0",[0,"  Listado de Revisiones de Metas de Personal   ","","",-1,-1,0,"sigesp_srh_r_listado_revisiones_metas_x_personal.php"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i0","p1i0",[0,"Amonestación / Llamada de Atención","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Cuarto Nivel
stm_aix("p3i0","p1i0",[0,"  Listado de Amonestación / Llamadas de Atención     ","","",-1,-1,0,"sigesp_srh_r_listado_amonestaciones.php","_self","","","","",0]);
stm_ep();

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p3i0","p1i0",[0,"Reportes Estadísticos","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Cuarto Nivel
stm_aix("p3i0","p1i0",[0,"  Resultados Evaluación Desempeño     ","","",-1,-1,0,"sigesp_srh_r_reporte_estadistico.php","_self","","","","",0]);
stm_ep();

stm_ep();

/*
// Menú Principal - Mantenimiento
stm_aix("p0i5","p0i0",[0," Mantenimiento "]);
stm_bpx("p7","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p7i0","p1i0",[0,"Mantenimiento 1    ","","",-1,-1,0,""]);
stm_aix("p7i1","p1i0",[0,"Mantenimiento 2    ","","",-1,-1,0,""]);
stm_aix("p7i2","p1i0",[0,"Mantenimiento 3    ","","",-1,-1,0,"","_self","","","","",6,0,0,"../../../public/imagenes/arrow.gif","../../../public/imagenes/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p3i1","p3i0",[0,"  Menu Item 2  ","","",-1,-1,0,""]);
stm_aix("p3i2","p3i0",[0,"  Menu Item 3  ","","",-1,-1,0,""]);
stm_aix("p3i3","p3i0",[0,"  Menu Item 4  ","","",-1,-1,0,""]);
stm_aix("p3i4","p3i0",[0,"  Menu Item 5  ","","",-1,-1,0,""]);
stm_ep();
stm_aix("p7i3","p1i0",[0,"Mantenimiento 4    ","","",-1,-1,0,""]);
stm_aix("p7i4","p1i0",[0,"Mantenimiento 5    ","","",-1,-1,0,""]);

stm_ep();*/

// Menú Principal - Ventana
//stm_aix("p0i6","p0i0",[0," Ventana "]);
//stm_bpx("p8","p1",[]);
/*stm_aix("p8i0","p1i0",[0,"Ventana 1    ","","",-1,-1,0,""]);
stm_aix("p8i1","p1i0",[0,"Ventana 2    ","","",-1,-1,0,""]);
stm_aix("p8i2","p1i0",[0,"Ventana 3    ","","",-1,-1,0,""]);
stm_aix("p8i3","p1i0",[0,"Ventana 4    ","","",-1,-1,0,""]);
stm_aix("p8i4","p1i0",[0,"Ventana 5    ","","",-1,-1,0,""]);
*/
//stm_ep();

// Menú Principal - Exploración
//("p0i7","p0i0",[0," Exploración "]);
//stm_bpx("p9","p1",[]);
/*stm_aix("p9i0","p1i0",[0,"Exploración 1    ","","",-1,-1,0,""]);
stm_aix("p9i1","p1i0",[0,"Exploración 1    ","","",-1,-1,0,""]);
stm_aix("p9i2","p1i0",[0,"Exploración 1    ","","",-1,-1,0,""]);
stm_aix("p9i3","p1i0",[0,"Exploración 1    ","","",-1,-1,0,""]);
stm_aix("p9i4","p1i0",[0,"Exploración 1    ","","",-1,-1,0,""]);
*/
//stm_ep();

// Menú Principal - Ayuda

stm_aix("p0i8","p0i0",[0," Ayuda "]);
stm_bpx("p10","p1",[]);
stm_aix("p10i0","p1i0",[0,"Guía del Modulo","","",-1,-1,0,"../../../ayuda/GUIA SRH.pdf","_blank","","","","",0]);
stm_ep();
stm_ep();
stm_aix("p4i0","p1i0",[0," Ir a M&oacute;dulos  ","","",-1,-1,0,"../../../../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();
stm_em();

//stm_em();
