<?php
/**
 * UPTP - (PNFI Sección 1236) 
 *
 * Descripcion: Controlador que se encarga de la gestión de las profesiones de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      ALexis Borges (tuaalexis@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP - (PNFI Team) (https://github.com/ArrozAlba/SASv2)
 */
Load::models('solicitudes/factura', 'solicitudes/factura_dt');
Load::models('config/tiposolicitud');
Load::models('proveedorsalud/proveedor');
Load::models('proveedorsalud/servicio');
Load::models('proveedorsalud/medico');
Load::models('proveedorsalud/especialidad');
Load::models('beneficiarios/titular');
Load::models('beneficiarios/beneficiario', 'solicitudes/solicitud_servicio');
Load::models('config/patologia', 'solicitudes/solicitud_servicio_patologia', 'solicitudes/solicitud_servicio_factura');

class SolicitudReembolsoController extends BackendController {
    /**
     * Constante para definir el tipo de solicitud
     */
    const TPS = 7;
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Solicitudes';
    }
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('registro');
    }
    /**
     * Método para listar
     */
    public function listar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_reembolso = new SolicitudServicio();        
        $this->solicitud_reembolsos = $solicitud_reembolso->getListadoReembolso($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Solicitudes de Atención Primaria';
    }
    /**
     * Método para registro
     */
    public function registro($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_reembolso = new SolicitudServicio();        
        $this->solicitud_reembolsos = $solicitud_reembolso->getListadoRegistroSolicitudServicio($order, $page, $tps=self::TPS);
        $this->order = $order;        
        $this->page_title = 'Registro de Solicitudes de Atención Primaria';
    }
    /**
     * Método para aprobacion
     */
    public function aprobacion($order='order.nombre.asc', $page='pag.1') { 
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        	$solicitud_reembolso = new SolicitudServicio();        
        	$this->solicitud_reembolsos = $solicitud_reembolso->getListadoAprobacionReembolso($order, $page);
        	$this->order = $order;        
        	$this->page_title = 'Aprobación de Solicitudes de Atención Primaria';
    }
    /**
     * Método para contabilizar
     */
    public function contabilizar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_reembolso = new SolicitudServicio();        
        $this->solicitud_reembolsos = $solicitud_reembolso->getListadoContabilizarSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Contabilizar Solicitudes de Atención Primaria';
    }
    /**
     * Método para agregar
     */
    public function agregar() {
        $solicitud_reembolso = new SolicitudServicio();
        $nroids = $solicitud_reembolso->count("tiposolicitud_id = ".self::TPS);
        $this->codigods=$nroids+1;
		$correlativ= new Tiposolicitud();
        $codigocorrelativo = $correlativ->find("columns: correlativo","conditions: id=".self::TPS." ", "limit: 1 ");
         foreach ($codigocorrelativo as $cargoa) {
                    $this->cargoas[] = $cargoa->correlativo;
                }
        $this->codigodd=$this->cargoas[0].'00'.$this->codigods;
        $beneficiario = new beneficiario(); 
        $this->beneficiario = $beneficiario->getListBeneficiario();              
        if(Input::hasPost('solicitud_servicio')&&(Input::hasPost('factura')) ) {
            ActiveRecord::beginTrans();
            $sol_reembolso = SolicitudServicio::setSolicitudServicio('create', Input::post('solicitud_servicio'));
            if($sol_reembolso){
                if(SolicitudServicioPatologia::setSolServicioPatolgia(Input::post('patologia_id'), $sol_reembolso->id)){
                    $factu = Factura::setFactura('create', Input::post('factura'));
                    if($factu){
                        if(FacturaDt::setFacturaDt(Input::post('descripcion'), Input::post('cantidad'), Input::post('monto'), Input::post('exento'), $factu->id)) {
                            $solfactura = SolicitudServicioFactura::setSolicitudServicioFactura($factu->id, $sol_reembolso->id);
                            if($solfactura){
                                if(Input::post('multifactura')){ //para saber si va a cargar multiples facturas sobre esa solicitud 
                                    //$solser = $solicitud_servicio->getInformacionSolicitudServicio($sol_reembolso->id);
                                    $sol_reembolso->estado_solicitud="G"; //estado G parcialmente facturada 
                                    $sol_reembolso->save();
                                    ActiveRecord::commitTrans();
                                    DwMessage::valid('Se ha cargado la factura exitosamente!');
                                    $key_upd = DwSecurity::getKey($sol_reembolso->id, 'upd_solicitud_servicio'); 
                                    return DwRedirect::toAction('facturar/'.$key_upd);   //retorna a la misma visata de facturacion 
                                } //cierre de postmiltifactura
                                else{
                                    //$solser = $solicitud_servicio->getInformacionSolicitudServicio($sol_reembolso->id);
                                    $sol_reembolso->estado_solicitud="F";
                                    $sol_reembolso->save();
                                    ActiveRecord::commitTrans();
                                    DwMessage::valid('Se ha cargado la factura exitosamente!');
                                    return DwRedirect::toAction('registro');     
                                }
                            }//cierre solfactura
                            else{
                                ActiveRecord::rollbackTrans();
                                DwMessage::error('No se pudo enviar a cargar multiples facturas!');
                            }
                        }//cierre de facturaDT
                        else{
                            ActiveRecord::rollbackTrans();
                            DwMessage::error('Los detalles de la Factura no se han cargado correctamente Intente de nuevo!');
                        }
                    }//cierrre factura
                    else{
                        ActiveRecord::rollbackTrans();
                        DwMessage::error('La Factura ha dao peos!');
                    }
            } //cierre de solicitudpatolgoia           
        } //cierre sol_reembolso, que es donde se carga la solicitud
    }//cierre del condicional del Input(post)
        $this->page_title = 'Agregar Solicitud deReembolso';
    }//CIERRE DE la funcion agregar

    /**
    * Método para cargar las facturas en caso que de los reeembolso tengan mas de una
    */
    public function facturar($key){
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('registro');
        }
        $solicitud_servicio = new SolicitudServicio();
        $obj = new SolicitudServicioPatologia();
        //$factura = new Factura();
        $factura_dt = new FacturaDt();
        $this->sol =  $obj->getInformacionSolicitudServicioPatologia($id);
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
        if(Input::hasPost('factura')) {
            ActiveRecord::beginTrans();
            $factu = Factura::setFactura('create', Input::post('factura'));
            if($factu){
                if(FacturaDt::setFacturaDt(Input::post('descripcion'), Input::post('cantidad'), Input::post('monto'), Input::post('exento'), $factu->id)) {
                    $solfactura = SolicitudServicioFactura::setSolicitudServicioFactura($factu->id, $id);
                    if($solfactura){
                        if(Input::post('multifactura')){ //para saber si va a cargar multiples facturas sobre esa solicitud 
                            $solser = $solicitud_servicio->getInformacionSolicitudServicio($id);
                            $solser->estado_solicitud="G"; //estado G parcialmente facturada 
                            $solser->save();
                            ActiveRecord::commitTrans();
                            DwMessage::valid('Se ha cargado la factura exitosamente!');
                            $key_upd = DwSecurity::getKey($id, 'upd_solicitud_servicio'); 
                            return DwRedirect::toAction('facturar/'.$key_upd);   //retorna a la misma visata de facturacion 
                        }
                        else{
                            $solser = $solicitud_servicio->getInformacionSolicitudServicio($id);
                            $solser->estado_solicitud="F";
                            $solser->save();
                            ActiveRecord::commitTrans();
                            DwMessage::valid('Se ha cargado la factura exitosamente!');
                          return DwRedirect::toAction('facturacion');     
                        }

                    }
                    else{
                        ActiveRecord::rollbackTrans();
                        DwMessage::error('No se pudo enviar a cargar multiples facturas!');
                    }

                }
                else{
                    ActiveRecord::rollbackTrans();
                    DwMessage::error('Los detalles de la Factura no se han cargado correctamente Intente de nuevo!');
                }
            }
            else{
                ActiveRecord::rollbackTrans();
                DwMessage::error('La Factura ha dao peos!');
            }
        }
        $this->solicitud_servicio = $solicitud_servicio;
        $this->page_title = 'Cargar Facturas a la solicitud';        
    }
    
  
    /**
    *Metodo para aprobar las solicitudes (Cambiar de Estatus)
    */

    public function reversar_aprobacion($key){
    	if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('aprobacion');
        } 
        //Mejorar esta parte  implementando algodon de seguridad
        $solicitud_reembolso = new SolicitudServicio();
        $sol = $solicitud_reembolso->getInformacionSolicitudServicio($id);
        $sol->estado_solicitud="R";
        $sol->save();
        return DwRedirect::toAction('aprobacion');
    }
    /**
     * Método para formar el reporte en pdf 
     */
    public function reporte_reembolso($id) { 
        View::template(NULL);       
       // if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
       //     return DwRedirect::toAction('aprobacion');
       // }

        //Mejorar esta parte  implementando algodon de seguridad
        $solicitud_reembolso = new SolicitudServicio();
                if(!$sol = $solicitud_reembolso->getReporteSolicitudServicio($id)) {
            DwMessage::get('id_no_found');
        };
        $this->fecha_sol = $solicitud_reembolso->fecha_solicitud;
        $this->nombres = strtoupper($solicitud_reembolso->nombre1." ".$solicitud_reembolso->nombre2);
        $this->apellidos = strtoupper($solicitud_reembolso->apellido1." ".$solicitud_reembolso->apellido2);
        $this->cedula = $solicitud_reembolso->cedula;
        $this->telefono = $solicitud_reembolso->telefono;
        $this->celular = $solicitud_reembolso->celular;
        $this->nacionalidad = $solicitud_reembolso->nacionalidad;        
        $this->sexo = $solicitud_reembolso->sexo;  
        $this->idtitular = $solicitud_reembolso->idtitular;
        $this->bene = $solicitud_reembolso->beneficiario_id;
        $this->medico = strtoupper($solicitud_reembolso->nombrem1." ".$solicitud_reembolso->nombrem2." ".$solicitud_reembolso->apellidom1." ".$solicitud_reembolso->apellidom2);
        $this->clinica = strtoupper($solicitud_reembolso->proveedor);
        $this->servicio = strtoupper($solicitud_reembolso->servicio);
        $this->direccion = $solicitud_reembolso->direccionp;

        //llamada a otra funcion, ya que no logre un solo query para ese reportee! :S
        $titular = new titular();
        $datoslaborales = $titular->getInformacionLaboralTitular($this->idtitular);
        $this->upsa = $titular->sucursal;
        $this->direccionlaboral = strtoupper($titular->direccion);
        $this->municipio_laboral = strtoupper($titular->municipios);
        $this->estado_laboral = strtoupper($titular->estados);
        $this->pais_laboral = strtoupper($titular->paiss);
        $this->cargo = strtoupper($titular->cargo);
        //instanciando la clase beneficiario 
        
        $beneficiarios = new beneficiario();
        $beneficiarios->getInformacionbeneficiario($this->bene);
        $this->nombresb = strtoupper($beneficiarios->nombre1." ".$beneficiarios->nombre2);
        $this->apellidosb = strtoupper($beneficiarios->apellido1." ".$beneficiarios->apellido2);
        $this->cedulab = $beneficiarios->cedula;
        $this->parentesco = $beneficiarios->parentesco;
    }
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('registro');
        }        
        
        $solicitud_reembolso = new SolicitudServicio();
        if(!$solicitud_reembolso->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
        
        if(Input::hasPost('solicitud_reembolso') && DwSecurity::isValidKey(Input::post('solicitud_servicio_id_key'), 'form_key')) {
            if(SolicitudServicio::setSolicitudServicio('update', Input::post('solicitud_reembolso'), array('id'=>$id))){
                DwMessage::valid('La solicitud se ha actualizado correctamente!');
                return DwRedirect::toAction('contabilizar');
            }
        } 
        $this->solicitud_reembolso = $solicitud_reembolso;
        $this->page_title = 'Actualizar solicitud';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $solicitud_reembolso = new SolicitudServicio();
        if(!$solicitud_reembolso->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(SolicitudServicio::setSolicitudServicio('delete', array('id'=>$solicitud_reembolso->id))) {
                DwMessage::valid('La solicitud se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta solicitud no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}

