stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,200,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);
stm_ai("p0i0",[0,"   Procesos   ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
stm_aix("p1i0","p0i0",[0,"  Registro  ","","",-1,-1,0,"sigesp_sep_p_solicitud.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Aprobación  ","","",-1,-1,0,"sigesp_sep_p_aprobacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Anulacion   ","","",-1,-1,0,"sigesp_sep_p_anulacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p0i4","p0i0",[0,"   Reportes   "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"  Ejecución Presupuestaria   ","","",-1,-1,0,"sigesp_sep_r_solicitudes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"  Ubicacion de Solicitudes   ","","",-1,-1,0,"sigesp_sep_r_ubicacionsolicitudes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p0i4","p0i0",[0,"   Créditos   "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"  Aprobación   ","","",-1,-1,0,"sigesp_sep_p_aprobacioncreditos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p0i8","p0i0",[0,"   Ayuda   "]);
stm_bpx("p10","p1",[]);
stm_ep();
stm_aix("p4i0","p1i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules_comp_alm_act.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
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