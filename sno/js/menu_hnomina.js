stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,200,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Archivo
stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0," Asignación de Personal   ","","",-1,-1,0,"sigesp_sno_d_hpersonalnomina.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Conceptos   ","","",-1,-1,0,"sigesp_sno_d_hconcepto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p0i0",[0," Nómina ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Prenomina ","","",-1,-1,0,"sigesp_sno_r_hprenomina.php","_self"]);
stm_aix("p3i0","p1i0",[0," Pago de Nómina ","","",-1,-1,0,"sigesp_sno_r_hpagonomina.php","_self"]);
stm_aix("p3i0","p1i0",[0," Recibo de Pago ","","",-1,-1,0,"sigesp_sno_r_hrecibopago.php","_self"]);
stm_aix("p3i0","p1i0",[0," Recibo de Pago de beneficiario","","",-1,-1,0,"sigesp_sno_r_hrecibopago_beneficiario.php","_self"]);
stm_aix("p3i0","p1i0",[0," Recibo de Pago por Unidad Administrativa ","","",-1,-1,0,"sigesp_sno_r_hpagonominaunidadadmin.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Listados ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Listado de Conceptos ","","",-1,-1,0,"sigesp_sno_r_hlistadoconcepto.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Conceptos Personal Militar","","",-1,-1,0,"sigesp_sno_r_hlistadoconcepto_personal_militar.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Personal cheque ","","",-1,-1,0,"sigesp_sno_r_hlistadopersonalcheque.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado al Banco ","","",-1,-1,0,"sigesp_sno_r_hlistadobanco.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Firmas ","","",-1,-1,0,"sigesp_sno_r_hlistadofirmas.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Proyectos ","","",-1,-1,0,"sigesp_sno_r_hlistadoproyectos.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Proyectos por Personal ","","",-1,-1,0,"sigesp_sno_r_hlistadoproyectospersonal.php","_self"]);
stm_aix("p3i0","p1i0",[0," Listado de Asignación de Cargos ","","",-1,-1,0,"sigesp_sno_r_hlistadoasignacioncargo.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Aporte Patronal ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Aporte Patronal ","","",-1,-1,0,"sigesp_sno_r_haportepatronal.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Resumenes ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Concepto ","","",-1,-1,0,"sigesp_sno_r_hresumenconcepto.php","_self"]);
stm_aix("p3i0","p1i0",[0," Concepto x Unidad ","","",-1,-1,0,"sigesp_sno_r_hresumenconceptounidad.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Cuadre de Nómina ","","",-1,-1,0,"sigesp_sno_r_hcuadrenomina.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Monto Ejecutado por Tipo de Cargos ","","",-1,-1,0,"javascript: window.open('reportes/sigesp_sno_rpp_monejetipocargo.php?tiporeporte=0','Reporte','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');","_self"]);
stm_aix("p3i0","p1i0",[0," Monto Ejecutado Pensionados, Jubilados y sobrevivientes ","","",-1,-1,0,"javascript: window.open('reportes/sigesp_sno_rpp_monejepensionado.php?tiporeporte=0','Reporte','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Contable de Conceptos ","","",-1,-1,0,"sigesp_sno_r_hcontableconceptos.php","_self"]);
stm_aix("p3i0","p1i0",[0," Contable de Aportes ","","",-1,-1,0,"sigesp_sno_r_hcontableaportes.php","_self"]);
stm_aix("p3i0","p1i0",[0," Cuadre de Conceptos y Aportes ","","",-1,-1,0,"javascript: window.open('reportes/sigesp_sno_rpp_cuadreconceptoaporte.php?tiporeporte=0','Reporte','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');","_self"]);
stm_aix("p3i0","p1i0",[0," Contable de Ingresos ","","",-1,-1,0,"javascript: window.open('reportes/sigesp_sno_rpp_contableingresos.php','Reporte','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Vacaciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Relación de Vacaciones ","","",-1,-1,0,"sigesp_sno_r_hrelacionvacaciones.php","_self"]);
stm_aix("p3i0","p1i0",[0," Programación de Vacaciones ","","",-1,-1,0,"sigesp_sno_r_hprogramacionvacaciones.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p0i0",[0," Prestamos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Listado de Prestamos ","","",-1,-1,0,"sigesp_sno_r_hlistadoprestamo.php","_self"]);
stm_aix("p3i0","p1i0",[0," Detalle de Prestamos ","","",-1,-1,0,"sigesp_sno_r_hdetalleprestamo.php","_self"]);
stm_ep();
stm_ep();

// Menú Principal - Mantenimiento
stm_aix("p0i5","p0i0",[0," Mantenimiento "]);
stm_bpx("p7","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Modificar Nómina ","","",-1,-1,0,"sigesp_sno_p_hmodificarnomina.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modificar Unidad Administrativa ","","",-1,-1,0,"sigesp_sno_p_hunidadadmin.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modificar Proyecto ","","",-1,-1,0,"sigesp_sno_p_hproyecto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modificar Personal ","","",-1,-1,0,"sigesp_sno_p_hmodificarpersonalnomina.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modificar Conceptos ","","",-1,-1,0,"sigesp_sno_p_hmodificarconcepto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Ajustar Contabilización ","","",-1,-1,0,"sigesp_sno_p_hajustarcontabilizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
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
A();
