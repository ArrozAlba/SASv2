stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- DEFINICIONES
stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
stm_aix("p1i0","p0i0",[0,"Categorías de Partidas     ","","",-1,-1,0,"sigesp_sob_d_categoriapartida.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Organismos Ejecutores ","","",-1,-1,0,"sigesp_sob_d_organismo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Tipo de Unidades  ","","",-1,-1,0,"sigesp_sob_d_tipounidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Unidades  ","","",-1,-1,0,"sigesp_sob_d_unidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Partidas     ","","",-1,-1,0,"sigesp_sob_d_partida.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Tipos de Contratos  ","","",-1,-1,0,"sigesp_sob_d_tipocontrato.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Sistemas Constructivos   ","","",-1,-1,0,"sigesp_sob_d_sistemaconstructivo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Tenencia   ","","",-1,-1,0,"sigesp_sob_d_tenencia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Tipos de Estructuras ","","",-1,-1,0,"sigesp_sob_d_tipoestructura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Tipos de Obras ","","",-1,-1,0,"sigesp_sob_d_tipoobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Documentos ","","",-1,-1,0,"sigesp_sob_d_documentos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - PROCESOS
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p3i0","p1i0",[0," Obras    ","","",-1,-1,0,"sigesp_sob_d_obra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Asignación","","",-1,-1,0,"","","","","","",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0,"Carta Asignación / Pto de Cuenta","","",-1,-1,0,"sigesp_sob_d_asignacion.php","_self"]);
		stm_aix("p3i0","p1i0",[0,"Aprobación/Reverso","","",-1,-1,0,"sigesp_sob_p_aprobacion_asignacion.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Contratos","","",-1,-1,0,"","","","","","",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0,"Contratos","","",-1,-1,0,"sigesp_sob_d_contrato.php","_self"]);
		stm_aix("p3i0","p1i0",[0,"Aprobación/Reverso","","",-1,-1,0,"sigesp_sob_p_aprobacion_contrato.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Anticipos","","",-1,-1,0,"","","","","","",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0,"Anticipos","","",-1,-1,0,"sigesp_sob_d_anticipo.php","_self"]);
		stm_aix("p3i0","p1i0",[0,"Aprobación/Reverso","","",-1,-1,0,"sigesp_sob_p_aprobacion_anticipo.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Valuaciones","","",-1,-1,0,"","","","","","",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0,"Valuaciones","","",-1,-1,0,"sigesp_sob_d_valuacion.php","_self"]);
		stm_aix("p3i0","p1i0",[0,"Aprobación/Reverso","","",-1,-1,0,"sigesp_sob_p_aprobacion_valuacion.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Variaciones del Contrato ","","",-1,-1,0,"","","","","","",20,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
		stm_bpx("p3","p1",[1,2,0,0,2,3,0]); 
		stm_aix("p3i0","p1i0",[0,"Variaciones","","",-1,-1,0,"sigesp_sob_d_variacion.php","_self"]);
		stm_aix("p3i0","p1i0",[0,"Aprobación/Reverso","","",-1,-1,0,"sigesp_sob_p_aprobacion_variacion.php","_self"]);
stm_ep();
stm_ai("p3i0",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Actas ","","",-1,-1,0,"sigesp_sob_d_acta.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();
stm_ep();


// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Obras  ","","",-1,-1,0,"sigesp_sob_r_reporteobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Partidas por Obras  ","","",-1,-1,0,"sigesp_sob_r_reportepartidasobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Asignaciones por Obras  ","","",-1,-1,0,"sigesp_sob_r_reporteasignacionesobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Seguimiento de Obras  ","","",-1,-1,0,"sigesp_sob_r_reporteseguimientoobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Documentos  ","","",-1,-1,0,"sigesp_sob_r_documentos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

stm_aix("p4i0","p1i0",[0,"Ir a Módulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_em();
