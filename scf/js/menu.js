stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Definiciones
stm_ai("p0i0",[0," Procesos ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,7,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0," Comprobante   ","","",-1,-1,0,"sigesp_scf_p_comprobante.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0," Cierre de ejercicio ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0," Mensual ","","",-1,-1,0,"sigesp_scf_p_cierremensual.php","_self"]);
stm_aix("p1i4","p1i0",[0," Anual ","","",-1,-1,0,"sigesp_scf_p_cierreanual.php","_self"]);
stm_ep();
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0," Mayor Analítico ","","",-1,-1,0,"sigesp_scf_r_mayor_analitico.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Balance Comprobación ","","",-1,-1,0,"sigesp_scf_r_balance_comprobacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Balance General Mensual ","","",-1,-1,0,"sigesp_scf_r_balance_general.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Balance General Anual","","",-1,-1,0,"sigesp_scf_r_balance_general_anual.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Comprobantes ","","",-1,-1,0,"sigesp_scf_r_comprobantes.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Listado de Cuentas ","","",-1,-1,0,"sigesp_scf_r_listadocuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Plan Unico de Cuentas ","","",-1,-1,0,"sigesp_scf_r_listadoplanunico.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Hoja de Trabajo ","","",-1,-1,0,"sigesp_scf_r_hoja_trabajo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal- Mantenimiento
stm_ai("p0i0",[0," Mantenimiento ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,7,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0," Configuración   ","","",-1,-1,0,"sigesp_scf_p_configuracion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Ayuda
stm_aix("p0i8","p0i0",[0," Ayuda "]);
stm_bpx("p10","p1",[]);
stm_ep();

// Menú Principal - Ir a Módulo
stm_aix("p4i0","p1i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();
stm_em();