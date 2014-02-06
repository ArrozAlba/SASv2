/******************************************************************************************************************/
/*******************************    Menu para el Modulo de Facturacion    *****************************************/
/******************************************************************************************************************/

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Men Principal- Archivo
stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);

// Definiciones - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Ficha Cliente ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Registro    ","","",-1,-1,0,"sigesp_sfc_d_cliente.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Tipo Explotación","","",-1,-1,0,"sigesp_sfc_d_tipo_rubro.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Ciclo","","",-1,-1,0,"sigesp_sfc_d_ciclo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Renglon","","",-1,-1,0,"sigesp_sfc_d_renglon.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Rubro","","",-1,-1,0,"sigesp_sfc_d_rubro.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Clasificación Rubro","","",-1,-1,0,"sigesp_sfc_d_clasificacionrubro.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Tenencia de Tierra","","",-1,-1,0,"sigesp_sfc_d_tenenciatierra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Producto ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);

stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Registro    ","","",-1,-1,0,"sigesp_sfc_d_producto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Tipos de Uso ","","",-1,-1,0,"sigesp_sfc_d_tipo_uso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Actividad ","","",-1,-1,0,"sigesp_sfc_d_actividad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Uso ","","",-1,-1,0,"sigesp_sfc_d_uso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Lineas de Producto ","","",-1,-1,0,"sigesp_sfc_d_clasificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Unidad Operativa de Suministro ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);

stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Registro    ","","",-1,-1,0,"sigesp_sfc_d_tienda.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Caja ","","",-1,-1,0,"sigesp_sfc_d_caja.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Cajero     ","","",-1,-1,0,"sigesp_sfc_d_cajero.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p4i0","p1i0",[0,"Forma de Pago ","","",-1,-1,0,"sigesp_sfc_d_formapago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Entidad Crediticia ","","",-1,-1,0,"sigesp_sfc_d_entidadcrediticia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p3i0","p1i0",[0,"Cotizaciones    ","","",-1,-1,0,"sigesp_sfc_d_cotizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0,"Orden de Entrega","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Generar   ","","",-1,-1,0,"sigesp_sfc_p_ordendeentrega.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Despachar ","","",-1,-1,0,"sigesp_sfc_p_despacho.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Recepción ","","",-1,-1,0,"sigesp_sfc_p_recepcion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p3i0","p1i0",[0,"Factura    ","","",-1,-1,0,"sigesp_sfc_d_factura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0,"Cuentas por Cobrar ","","",-1,-1,0,"sigesp_sfc_d_ctasporcobrar.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0,"Saldos Pendientes ","","",-1,-1,0,"sigesp_sfc_d_ctasporpagar.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0,"Cobranzas    ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Cartas Ordenes por Cobrar    ","","",-1,-1,0,"sigesp_sfc_d_cobranzacartas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Facturas por Cobrar","","",-1,-1,0,"sigesp_sfc_d_cobranza.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p3i0","p1i0",[0,"Devoluciones    ","","",-1,-1,0,"sigesp_sfc_d_devolucion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0,"Cierre de Caja    ","","",-1,-1,0,"sigesp_sfc_d_cierrecaja.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0,"Contabilización Diaria  ","","",-1,-1,0,"sigesp_sfc_d_cierre_diario.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0,"Contabilización Depositos ","","",-1,-1,0,"sigesp_sfc_d_contabilizar_deposito.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Registro de Pedido de Productos    ","","",-1,-1,0,"sigesp_sfc_d_pedido.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_ep();
stm_ep();
stm_ep();

// Men Principal - Reportes
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"Reportes de Ventas ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de Productos Ventas  ","","",-1,-1,0,"sigesp_sfc_d_rep_producto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Libro de Ventas  ","","",-1,-1,0,"sigesp_sfc_d_rep_libroventa.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Resumen General de Ventas por Producto  ","","",-1,-1,0,"sigesp_sfc_d_rep_resumengralventa.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Estadistico de Lineas  ","","",-1,-1,0,"sigesp_sfc_d_rep_productoest.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Informe de Gestión  ","","",-1,-1,0,"sigesp_sfc_d_rep_gestion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Listado de Productos por Clasificacion ","","",-1,-1,0,"sigesp_sfc_d_rep_clasificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Reportes de Procesos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de Facturas  ","","",-1,-1,0,"sigesp_sfc_d_rep_factura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de Cotizaciones  ","","",-1,-1,0,"sigesp_sfc_d_rep_cotizaciones.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de Cartas Ordenes  ","","",-1,-1,0,"sigesp_sfc_d_rep_cartaorden.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Resumen de Cuentas por Cobrar  ","","",-1,-1,0,"sigesp_sfc_d_rep_cuentasxcobrar.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Consolidado Cuentas por Cobrar  ","","",-1,-1,0,"sigesp_sfc_d_rep_resumencuentasxcobrar.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Reporte de Cobros","","",-1,-1,0,"sigesp_sfc_d_rep_listado_cobros.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Detallado de Cobros","","",-1,-1,0,"sigesp_sfc_d_rep_relacion_cobros_factura_inspago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);


stm_ep();


stm_aix("p1i0","p0i0",[0,"Reportes de Existencias ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de Existencias Por Clasificación  ","","",-1,-1,0,"sigesp_sfc_d_rep_existeclasificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_ep();


stm_aix("p1i0","p0i0",[0,"Otros ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de Unidad Operativa de Suministro  ","","",-1,-1,0,"sigesp_sfc_d_rep_tienda.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de Clientes  ","","",-1,-1,0,"sigesp_sfc_d_rep_cliente.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de Precios  ","","",-1,-1,0,"sigesp_sfc_d_rep_precios.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_ep();
stm_ep();


// Men Principal - Mantenimiento
stm_aix("p0i3","p0i0",[0," Mantenimiento "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);

stm_aix("p3i0","p1i0",[0," Generar Archivos de Transferencias","","",-1,-1,0,"sigesp_sfc_d_generar_transforden.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Procesar  Archivos de Transferencias ","","",-1,-1,0,"sigesp_sfc_d_procesar_transferencia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Re-Procesar Movimientos   ","","",-1,-1,0,"sigesp_sfc_d_actualizar_facturas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Generar Respaldo de Base de Datos   ","","",-1,-1,0,"sigesp_sfc_d_generar_basedato.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Actualizar Correlativos de Facturas   ","","",-1,-1,0,"sigesp_sfc_d_actualizar_series.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();

stm_aix("p0i3","p0i0",[0," Configuración "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i2","p0i0",[0," Configuración de la Factura ","","",-1,-1,0,"sigesp_sfc_d_config_factura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();

// Menú Principal - Ir a Módulo
stm_aix("p4i0","p1i0",[0," Ir a Módulos  ","","",-1,-1,0,"../index_modules_comercializacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

/*stm_aix("p8i2","p1i0",[0,"Ventana 3    "]);
stm_aix("p8i3","p1i0",[0,"Ventana 4    "]);
stm_aix("p8i4","p1i0",[0,"Ventana 5    "]);*/
//stm_ep();

// Menú Principal - Exploración
//stm_aix("p0i7","p0i0",[0," Exploración "]);
//stm_bpx("p9","p1",[]);
/*stm_aix("p9i0","p1i0",[0,"Exploración 1    "]);
stm_aix("p9i1","p1i0",[0,"Exploración 1    "]);
stm_aix("p9i2","p1i0",[0,"Exploración 1    "]);
stm_aix("p9i3","p1i0",[0,"Exploración 1    "]);
stm_aix("p9i4","p1i0",[0,"Exploración 1    "]);*/
//stm_ep();

// Menú Principal - Ayuda
//stm_aix("p0i8","p0i0",[0," Ayuda "]);
//stm_bpx("p10","p1",[]);
/*stm_aix("p10i0","p1i0",[0,"Ayuda 1    "]);
stm_aix("p10i1","p1i0",[0,"Ayuda 2    "]);
stm_aix("p10i2","p1i0",[0,"Ayuda 3    "]);
stm_aix("p10i3","p1i0",[0,"Ayuda 4    "]);
stm_aix("p10i4","p1i0",[0,"Ayuda 5    "]);
stm_ep();*/
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
	location.href=url.replace(sistema+"/"+pagina,"index.php");
	return true;
} 
A();