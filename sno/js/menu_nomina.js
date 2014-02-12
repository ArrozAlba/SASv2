stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,200,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Archivo
stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
//stm_aix("p1i0","p0i0",[0," Cargos   ","","",-1,-1,0,"sigesp_sno_d_cargo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i2","p1i0",[0," Tabulador   ","","",-1,-1,0,"sigesp_sno_d_tabulador.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i4","p1i0",[0," Asignación de Cargos   ","","",-1,-1,0,"sigesp_sno_d_asignacioncargo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0," Asignación de Personal  ","","",-1,-1,0,"sigesp_sno_d_personalnomina.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Constantes   ","","",-1,-1,0,"sigesp_sno_d_constantes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Conceptos   ","","",-1,-1,0,"sigesp_sno_d_concepto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Constantes x Persona   ","","",-1,-1,0,"sigesp_sno_d_persxconst.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Concepto x Persona   ","","",-1,-1,0,"sigesp_sno_d_persxconce.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Proyecto x Persona   ","","",-1,-1,0,"sigesp_sno_d_personaproyecto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Primas de Personal Docente  ","","",-1,-1,0,"sigesp_sno_d_primadocpersonal.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Tipo de Prestamo   ","","",-1,-1,0,"sigesp_sno_d_tipoprestamo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Vacación Concepto   ","","",-1,-1,0,"sigesp_sno_d_vacacionconcepto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0," Prima Concepto   ","","",-1,-1,0,"sigesp_sno_d_primaconcepto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p4","p1",[1,4,0,0,2,3,6,7]);

stm_aix("p4i0","p0i0",[0," Encargadurías ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Seleccionar Conceptos ","","",-1,-1,0,"sigesp_sno_p_conceptosencargaduria.php","_self"]);
stm_aix("p3i0","p1i0",[0," Seleccionar Constantes ","","",-1,-1,0,"sigesp_sno_p_constantesencargaduria.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Registrar ","","",-1,-1,0,"sigesp_sno_p_registrarencargaduria.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Reversar ","","",-1,-1,0,"sigesp_sno_p_reversarencargaduria.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Nómina ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Prenómina ","","",-1,-1,0,"sigesp_sno_p_calcularprenomina.php","_self"]);
stm_aix("p3i0","p1i0",[0," Cálculo ","","",-1,-1,0,"sigesp_sno_p_calcularnomina.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Reverso ","","",-1,-1,0,"sigesp_sno_p_reversarnomina.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Manejo de Períodos   ","","",-1,-1,0,"sigesp_sno_p_manejoperiodo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Vacaciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0," Generar Vacaciones Vencidas ","","",-1,-1,0,"sigesp_sno_p_vacacionvencida.php","_self"]);
stm_aix("p1i4","p1i0",[0," Programar Vacaciones ","","",-1,-1,0,"sigesp_sno_p_vacacionprogramar.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Prestamos   ","","",-1,-1,0,"sigesp_sno_p_prestamo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Cambio Estatus Personal  ","","",-1,-1,0,"sigesp_sno_p_personalcambioestatus.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Aplicar Conceptos Lote ","","",-1,-1,0,"sigesp_sno_p_aplicarconcepto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Ajustes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0," Aportes ","","",-1,-1,0,"sigesp_sno_p_ajustaraporte.php","_self"]);
stm_aix("p1i4","p1i0",[0," Sueldos ","","",-1,-1,0,"sigesp_sno_p_ajustarsueldo.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Importar/Exportar Datos ","","",-1,-1,0,"sigesp_sno_p_impexpdato.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Importar Prestamos ","","",-1,-1,0,"sigesp_sno_p_importarprestamos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Importar Definiciones ","","",-1,-1,0,"sigesp_sno_p_importardefiniciones.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0," Movimiento entre Nóminas ","","",-1,-1,0,"sigesp_sno_p_movimientonominas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p4i0","p1i0",[0," Transferencia de Personal ","","",-1,-1,0,"sigesp_sno_p_transferirpersonal.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

stm_aix("p1i4","p1i0",[0," Ipasme ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0," Importar Cobranza ","","",-1,-1,0,"sigesp_sno_p_ipasme_importar.php","_self"]);
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p0i0",[0," Encargadurías ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Reporte de Encargaduría","","",-1,-1,0,"sigesp_sno_r_reporteencargaduria.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Nómina ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Prenomina ","","",-1,-1,0,"sigesp_sno_r_prenomina.php","_self"]);
stm_aix("p3i0","p1i0",[0," Pago de Nómina ","","",-1,-1,0,"sigesp_sno_r_pagonomina.php","_self"]);
stm_aix("p3i0","p1i0",[0," Pago de Nómina por Unidad Administrativa","","",-1,-1,0,"sigesp_sno_r_pagonominaunidadadmin.php","_self"]);
stm_aix("p3i0","p1i0",[0," Recibo de Pago ","","",-1,-1,0,"sigesp_sno_r_recibopago.php","_self"]);
stm_aix("p3i0","p1i0",[0," Recibo de Pago Beneficiario","","",-1,-1,0,"sigesp_sno_r_recibopago_beneficario.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Listados ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Listado de Conceptos ","","",-1,-1,0,"sigesp_sno_r_listadoconcepto.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Conceptos Personal Militar","","",-1,-1,0,"sigesp_sno_r_listadoconcepto_personal_militar.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Personal cheque ","","",-1,-1,0,"sigesp_sno_r_listadopersonalcheque.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado al Banco ","","",-1,-1,0,"sigesp_sno_r_listadobanco.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Firmas ","","",-1,-1,0,"sigesp_sno_r_listadofirmas.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Proyectos ","","",-1,-1,0,"sigesp_sno_r_listadoproyectos.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Proyectos por Personal ","","",-1,-1,0,"sigesp_sno_r_listadoproyectospersonal.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Beneficiarios ","","",-1,-1,0,"sigesp_sno_r_listadobeneficiario.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Asignación de Cargos ","","",-1,-1,0,"sigesp_sno_r_listadoasignacioncargo.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Aporte Patronal ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Aporte Patronal ","","",-1,-1,0,"sigesp_sno_r_aportepatronal.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Resumenes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Concepto ","","",-1,-1,0,"sigesp_sno_r_resumenconcepto.php","_self"]);
stm_aix("p3i0","p1i0",[0," Concepto x Unidad ","","",-1,-1,0,"sigesp_sno_r_resumenconceptounidad.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Cuadre de Nómina ","","",-1,-1,0,"sigesp_sno_r_cuadrenomina.php","_self"]);
stm_aix("p3i0","p1i0",[0," Cuadre de Conceptos y Aportes ","","",-1,-1,0,"javascript: window.open('reportes/sigesp_sno_rpp_cuadreconceptoaporte.php?tiporeporte=0','Reporte','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Contable de Conceptos ","","",-1,-1,0,"sigesp_sno_r_contableconceptos.php","_self"]);
stm_aix("p3i0","p1i0",[0," Contable de Aportes ","","",-1,-1,0,"sigesp_sno_r_contableaportes.php","_self"]);
stm_aix("p3i0","p1i0",[0," Contable de Ingresos ","","",-1,-1,0,"javascript: window.open('reportes/sigesp_sno_rpp_contableingresos.php','Reporte','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');","_self"]);
stm_aix("p3i0","p1i0",[0," Disponibilidad Financiera ","","",-1,-1,0,"sigesp_sno_r_disponibilidad.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Netos y Deducciones ","","",-1,-1,0,"sigesp_sno_r_cuadre_netos_deduc.php","_self"]);
stm_aix("p3i0","p1i0",[0," Asignaciones por Componente y Rango ","","",-1,-1,0,"sigesp_sno_r_asignacion_comp_ran.php","_self"]);

stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Pagos por Banco ","","",-1,-1,0,"sigesp_sno_r_pagosbanco.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Cuadre Pagos a Terceros  ","","",-1,-1,0,"sigesp_sno_r_pagosterceros.php","_self"]);

stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Vacaciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Relación de Vacaciones ","","",-1,-1,0,"sigesp_sno_r_relacionvacaciones.php","_self"]);
stm_aix("p3i0","p1i0",[0," Programación de Vacaciones ","","",-1,-1,0,"sigesp_sno_r_programacionvacaciones.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Prestamos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Listado de Prestamos ","","",-1,-1,0,"sigesp_sno_r_listadoprestamo.php","_self"]);
stm_aix("p3i0","p1i0",[0," Detalle de Prestamos ","","",-1,-1,0,"sigesp_sno_r_detalleprestamo.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Archivos Fonz03 ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Generar Archivo Fonz03 ","","",-1,-1,0,"sigesp_sno_r_metodo_fonz.php","_self"]);
stm_ep();
stm_ep();

// Menú Principal - Ayuda
stm_aix("p0i8","p0i0",[0," Ayuda "]);
stm_bpx("p10","p1",[]);
stm_ep();

// Menú Principal - Volver
stm_aix("p4i0","p1i0",[0," Volver a Recursos Humanos   ","","",-1,-1,0,"sigespwindow_blank.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_em();
/*
function A()
{
	window.onerror=B
	window.opener.focus();
	window.focus();
}
function B()
{
	var url = document.location.href;
    partes = url.split('/');
    pagina=partes[partes.length-1];
	sistema=partes[partes.length-2];
	alert("No ha iniciado sesión para esta ventana");
	location.href=url.replace(sistema+"/"+pagina,"pagina_blanco.php");
	return true;
} 
A();*/
