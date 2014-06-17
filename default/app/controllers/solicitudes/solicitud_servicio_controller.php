<?php
/**
 * UPTP - (PNFI Sección 1236) 
 *
 * Descripcion: Controlador que se encarga de la gestión de las profesiones de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP - (PNFI Team) (https://github.com/ArrozAlba/SASv2)
 */

Load::models('solicitudes/solicitud_servicio');

class SolicitudServicioController extends BackendController {
    
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
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Solicitudes de Atención Primaria';
    }
/**
     * Método para obtener patologias
     */
    
        //accion que busca en las patologias y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $patologias = Load::model('patologia')->obtener_patologias($busqueda);
            die(json_encode($patologias)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }
    
    /**
     * Método para registro
     */
    public function registro($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoRegistroSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Registro de Solicitudes de Atención Primaria';
    }
    /**
     * Método para aprobacion
     */
    public function aprobacion($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoAprobacionSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Aprobación de Solicitudes de Atención Primaria';
    }
    /**
     * Método para contabilizar
     */
    public function contabilizar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoContabilizarSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Contabilizar Solicitudes de Atención Primaria';
    }


    /**
     * Método para agregar
     */
    public function agregar() {
        $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('solicitud_servicio')) {
            if(SolicitudServicio::setSolicitudServicio('create', Input::post('solicitud_servicio'))) {
                DwMessage::valid('La solicitud se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
       // $this->personas = Load::model('beneficiarios/titular')->getTitularesToJson();
        $this->page_title = 'Agregar Solicitud de Servicio';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('registro');
        }        
        
        $solicitud_servicio = new SolicitudServicio();
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
        
        if(Input::hasPost('solicitud_servicio') && DwSecurity::isValidKey(Input::post('solicitud_servicio_id_key'), 'form_key')) {
            if(SolicitudServicio::setSolicitudServicio('update', Input::post('solicitud_servicio'), array('id'=>$id))){
                DwMessage::valid('La solicitud se ha actualizado correctamente!');
                return DwRedirect::toAction('contabilizar');
            }
        } 
        $this->solicitud_servicio = $solicitud_servicio;
        $this->page_title = 'Actualizar solicitud';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $solicitud_servicio = new SolicitudServicio();
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(SolicitudServicio::setSolicitudServicio('delete', array('id'=>$solicitud_servicio->id))) {
                DwMessage::valid('La solicitud se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta solicitud no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
