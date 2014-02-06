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
stm_aix("p4i0","p1i0",[0," Tipo Explotaci&oacute;n","","",-1,-1,0,"sigesp_sfc_d_tipo_rubro.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Ciclo","","",-1,-1,0,"sigesp_sfc_d_ciclo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Rengl&oacute;n","","",-1,-1,0,"sigesp_sfc_d_renglon.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Rubro","","",-1,-1,0,"sigesp_sfc_d_rubro.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Clasificaci&oacute;n Rubro","","",-1,-1,0,"sigesp_sfc_d_clasificacionrubro.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Tenencia de Tierra","","",-1,-1,0,"sigesp_sfc_d_tenenciatierra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Articulo ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);

stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Registro    ","","",-1,-1,0,"sigesp_sfc_d_producto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Tipos de Uso ","","",-1,-1,0,"sigesp_sfc_d_tipo_uso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Actividad ","","",-1,-1,0,"sigesp_sfc_d_actividad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Uso ","","",-1,-1,0,"sigesp_sfc_d_uso.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Lineas de Articulo ","","",-1,-1,0,"sigesp_sfc_d_clasificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Tienda ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);

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
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Registro de Pedido de Articulos    ","","",-1,-1,0,"sigesp_sfc_d_pedido.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Generar Archivo de Transferencia ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Orden de Compra    ","","",-1,-1,0,"sigesp_sfc_d_cobranzacartas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Transferencia entre Almacenes","","",-1,-1,0,"sigesp_sfc_d_generar_transf_almacen.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();
stm_ep();


// Men Principal - Reportes
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p0i0",[0,"Reportes de Ventas ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de productos ventas  ","","",-1,-1,0,"sigesp_sfc_d_rep_producto.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Estadistico de Lineas  ","","",-1,-1,0,"sigesp_sfc_d_rep_productoest.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Informe de Gesti&oacute;n  ","","",-1,-1,0,"sigesp_sfc_d_rep_gestion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Listado de Productos por Clasificaci&oacute;n ","","",-1,-1,0,"sigesp_sfc_d_rep_clasificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Reportes de Procesos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de Facturas  ","","",-1,-1,0,"sigesp_sfc_d_rep_factura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Resumen de Cuentas por Cobrar  ","","",-1,-1,0,"sigesp_sfc_d_rep_cuentasxcobrar.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i0","p0i0",[0,"Otros ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0," Listado de Tiendas  ","","",-1,-1,0,"sigesp_sfc_d_rep_tienda.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de Clientes  ","","",-1,-1,0,"sigesp_sfc_d_rep_cliente.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_ep();


// Men Principal - Sigesp
stm_aix("p4i0","p1i0",[0,"Sigesp  ","","",-1,-1,0,"","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_aix("p4i0","p1i0",[0," Modulo Inventario  ","","",-1,-1,0,"../siv/sigespwindow_blank.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modulo Seguridad  ","","",-1,-1,0,"../sss/sigespwindow_blank.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Modulo Compras  ","","",-1,-1,0,"../soc/sigespwindow_blank.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

stm_em();
