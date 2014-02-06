stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Archivo
stm_ai("p0i0",[0,"   Procesos   ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,7,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Apertura de B.D. Nueva                 ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("pli0","p0i0",[0,"    Sistemas Básicos     ","","",-1,-1,0,"sigesp_apr_basicos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ai("p1i4",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0,"Asociar Cuentas                ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i3","p0i0",[0,"Cuentas Contables","","",-1,-1,0,"sigesp_apr_actscgcuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i3","p0i0",[0,"Cuentas Presupuestarias","","",-1,-1,0,"sigesp_apr_actspgcuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i3","p0i0",[0,"Estructuras Presupuestarias","","",-1,-1,0,"sigesp_apr_actestructura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i3","p0i0",[0,"Procesar Cuentas","","",-1,-1,0,"sigesp_apr_procesarcuentas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i5","p0i0",[0,"Asiento de Apertura de Contabilidad ","","",-1,-1,0,"sigesp_apr_salcontables.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i5","p0i0",[0,"Traspaso Saldos Bancos y Movimientos en Tránsito ","","",-1,-1,0,"sigesp_apr_banco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i3","p0i0",[0,"Movimiento Inicial de Existencias ","","",-1,-1,0,"sigesp_apr_inventario.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i3","p0i0",[0,"Traspaso Solicitudes Cuentas por Pagar ","","",-1,-1,0,"sigesp_apr_traspasa_sol_cxp.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();

// Menú Principal - Ayuda
stm_aix("p0i8","p0i0",[0,"   Ayuda   "]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_aix("p4i0","p1i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules_herramientas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_ep();
stm_em();
