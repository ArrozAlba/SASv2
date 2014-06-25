<?php
/**
 * Alexis
 *
 * Descripcion: Controlador que se encarga de la gestión de los tiposolicitudes de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/tiposolicitud');

class tiposolicitudController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Configuraciones';
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
        $tiposolicitud = new tiposolicitud();        
        $this->tiposolicitudes = $tiposolicitud->getListadotiposolicitud($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de tiposolicitud';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('tiposolicitud')) {
            if(tiposolicitud::settiposolicitud('create', Input::post('tiposolicitud'))) {
                DwMessage::valid('El tipo solicitud se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Tipo de Solicitud';
    }
     /**
     * Método para obtener tiposolicitudes
     */
    
        //accion que busca en los tiposolicitudes y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $tiposolicitudes = Load::model('config/tiposolicitud')->obtener_tiposolicitudes($busqueda);
            die(json_encode($tiposolicitudes)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_tiposolicitud', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $tiposolicitud = new tiposolicitud();
        if(!$tiposolicitud->getInformacionTiposolicitud($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('tiposolicitud') && DwSecurity::isValidKey(Input::post('tiposolicitud_id_key'), 'form_key')) {
            if(tiposolicitud::settiposolicitud('update', Input::post('tiposolicitud'))){
                DwMessage::valid('La tiposolicitud se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->tiposolicitud = $tiposolicitud;
        $this->page_title = 'Actualizar tiposolicitud';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_tiposolicitud', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $tiposolicitud = new tiposolicitud();
        if(!$tiposolicitud->getInformaciontiposolicitud($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(tiposolicitud::settiposolicitud('delete', array('id'=>$tiposolicitud->id))) {
                DwMessage::valid('El tipo solicitud se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta tipo de solicitud no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
