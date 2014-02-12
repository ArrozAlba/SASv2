// stm_aix("p1i2","p1i0",[0,"Opción 2    ","","",-1,-1,0,""]);
// stm_aix("p1i0","p0i0",[0,"Opción 1    ","","",-1,-1,0,"tablas.htm","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

//-----------------------//
// Línea de separación
// Para inlcuir líneas de separación entre las opciones, incoporar la siguiente instrucción, entre las opciones a separar
// stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

//-----------------------//
// Menúes de Tercer Nivel
// Para hacer submenúes, incluir las siguientes líneas de código
// stm_bpx("pn","p1",[1,4,0,0,2,3,6,7]);   debajo de la línea de código de la opción principal stm_aix("p0in","p0i0",[0," Opción Menú "]);
// luego, buscar la opción del menú bajo la cual se abrirá el submenú y agregar al final de esa línea de código, los siguientes atributos:
// ,"","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
// y justo debajo de esa línea agregar las siguientes líneas de código.
// stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
// stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// stm_aix("p3i2","p3i0",[0,"  Menu Item 3  "]);
// stm_aix("p3i3","p3i0",[0,"  Menu Item 4  "]);
// stm_aix("p3i4","p3i0",[0,"  Menu Item 5  "]);
// stm_ep();
// Luego cambiar las opciones "Menu Item 5", por el nombre de la opción que corresponda en cada caso.

//-----------------------//
// Hipervínculos
// Para incluir los enlaces correspondientes a cada opción del menú, se procede de la siguiente manera:
// En aquellas intrucciones, cuyo código es similare a esto:
// stm_aix("p1i0","p0i0",[0,"Opción 1    ","","",-1,-1,0,"","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
// agregar el enlace dentro de las comillas, justo delante de "_self"
// En aquellas intrucciones, cuyo código es similare a esto:
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// agregar al final de esta línea de código, los siguientes parámetros:
// ,"","",-1,-1,0,"","_self","","","","",0]);
// y luego incorporar el enlace en las comillas que está justo antes de "_self"

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
stm_aix("p1i0","p0i0",[0,"Método de Rotulación    ","","",-1,-1,0,"sigesp_saf_d_rotulacion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0,"Condición del Activo    ","","",-1,-1,0,"sigesp_saf_d_condicion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p0i0",[0,"Causas de Movimiento    ","","",-1,-1,0,"sigesp_saf_d_movimientos.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i6","p0i0",[0,"Activos                 ","","",-1,-1,0,"sigesp_saf_d_activossigecof.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i8","p0i0",[0,"Categoria CGR        ","","",-1,-1,0,"sigesp_saf_d_grupo.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i8","p0i0",[0,"Configuracion de Activos        ","","",-1,-1,0,"sigesp_saf_d_configuracion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i8","p0i0",[0,"Estructuras Predominantes de los Inmuebles   ","","",-1,-1,0,"sigesp_saf_d_materiales.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p1i0",[0,"Movimientos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i0","p1i0",[0," Incorporaciones    ","","",-1,-1,0,"sigesp_saf_p_incorporaciones.php","_self"]);
stm_aix("p1i0","p1i0",[0," Desincorporaciones ","","",-1,-1,0,"sigesp_saf_p_desincorporaciones.php","_self"]);
stm_aix("p1i0","p1i0",[0," Incorporaciones por Lote ","","",-1,-1,0,"sigesp_saf_p_incorporacioneslote.php","_self"]);
stm_aix("p1i0","p1i0",[0," Incorporaciones General ","","",-1,-1,0,"sigesp_saf_p_incorporacioneslotegeneral.php","_self"]);
stm_ep();
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p0i0",[0,"Cambio de Responsable","","",-1,-1,0,"sigesp_saf_p_cambioresponsable.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p0i0",[0,"Entrega de Unidad","","",-1,-1,0,"sigesp_saf_p_entregaunidad.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i6","p1i0",[0,"Depreciación de Activos ","","",-1,-1,0,"sigesp_saf_p_depreciacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i6","p1i0",[0,"Acta de Préstamos       ","","",-1,-1,0,"sigesp_saf_p_actaprestamo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i6","p1i0",[0,"Autorización de Salida  ","","",-1,-1,0,"sigesp_saf_p_autorizacionsalida.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i6","p1i0",[0,"Entrega de Activos      ","","",-1,-1,0,"sigesp_saf_p_entregas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p1i0",[0," Activos                      ","","",-1,-1,0,"sigesp_saf_r_activo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0," Incorporación                ","","",-1,-1,0,"sigesp_saf_r_incorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0," Desincorporación             ","","",-1,-1,0,"sigesp_saf_r_desincorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0," Depreciación                 ","","",-1,-1,0,"sigesp_saf_r_depreciacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0," Depreciacion Mensual         ","","",-1,-1,0,"sigesp_saf_r_depmensual.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0," Inventario de Bienes Muebles BM-1    ","","",-1,-1,0,"sigesp_saf_r_activo_bien.php","_self"]);
stm_aix("p1i0","p1i0",[0," Relación del Movimiento de Bienes Muebles BM-2    ","","",-1,-1,0,"sigesp_saf_r_relmovbm2.php","_self"]);
stm_aix("p1i0","p1i0",[0," Relación de Bienes Muebles Faltantes BM-3    ","","",-1,-1,0,"sigesp_saf_r_relbmf3.php","_self"]);
stm_aix("p1i0","p1i0",[0," Resumen de la Cuenta de Bienes Muebles BM-4    ","","",-1,-1,0,"sigesp_saf_r_resctabm4.php","_self"]);
stm_aix("p1i0","p1i0",[0," Inventario General de Bienes    ","","",-1,-1,0,"sigesp_saf_r_invgenbie.php","_self"]);
stm_aix("p1i0","p1i0",[0," Resumen de Bienes Muebles por Grupo    ","","",-1,-1,0,"sigesp_saf_r_resbiegru.php","_self"]);
stm_aix("p1i0","p1i0",[0," Incorporaciones y Desincorporacion por Departamento    ","","",-1,-1,0,"sigesp_saf_r_incdesinc.php","_self"]);
stm_aix("p1i0","p1i0",[0," Bienes por Cuenta Contable   ","","",-1,-1,0,"sigesp_saf_r_biemuectacont.php","_self"]);
stm_aix("p1i0","p1i0",[0," Rendición Mensual de Cuenta    ","","",-1,-1,0,"sigesp_saf_r_rendmen.php","_self"]);
stm_aix("p1i0","p1i0",[0," Tipos de Adquisición de Bienes    ","","",-1,-1,0,"sigesp_saf_r_tipos_bien.php","_self"]);
stm_aix("p1i0","p1i0",[0," Adquisición de Bienes General    ","","",-1,-1,0,"sigesp_saf_r_bien_general.php","_self"]);
stm_aix("p1i0","p1i0",[0,"Acta de Incorporación ","","",-1,-1,0,"sigesp_saf_r_actaincorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0,"Acta de Desincorporación ","","",-1,-1,0,"sigesp_saf_r_actadesincorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0,"Acta de Reasignación ","","",-1,-1,0,"sigesp_saf_r_actareasignacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p1i0",[0," Inventario de Bienes por Unidad Organizativa    ","","",-1,-1,0,"sigesp_saf_r_bien_uniadm.php","_self"]);
stm_ep();
stm_ep();

// Menú Principal - Ir a Módulo
stm_aix("p0i5","p0i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_ep();
stm_ep();

stm_ep();
stm_em();
function ue_abrir(ventana)
{
	window.open(ventana,"catalogo","menubar=no,toolbar=no,scrollbars=no,resizable=no,width=400,height=230,left=150,top=150,location=no,resizable=yes");
}

function ue_abrir_usuario(sistema)
{
	window.open("sigesp_c_seleccionar_usuario.php?sist="+sistema,"catalogo","menubar=no,toolbar=no,scrollbars=no,resizable=no,width=400,height=230,left=150,top=150,location=no,resizable=yes");
}

function ue_actulizar_ventana()
{
	window.open("sigesp_c_Actualizar_ventanas.php","catalogo","menubar=no,toolbar=no,scrollbars=no,resizable=no,width=400,height=230,left=150,top=150,location=no,resizable=yes");
}