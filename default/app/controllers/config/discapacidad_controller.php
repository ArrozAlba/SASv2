<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de las discapacidades de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/discapacidad');

class DiscapacidadController extends BackendController {
    
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
        $discapacidad = new Discapacidad();        
        $this->discapacidades = $discapacidad->getListadoDiscapacidad($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Discapacidades';
    }
     /**
     * Método para obtener discapacidades
     */
    
        //accion que busca en las discapacidades y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $discapacidades = Load::model('config/discapacidad')->obtener_discapacidades($busqueda);
            die(json_encode($discapacidades)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }        
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('discapacidad')) {
            if(Discapacidad::setDiscapacidad('create', Input::post('discapacidad'))) {
                DwMessage::valid('La discapacidad se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Discapacidad';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_discapacidad', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $discapacidad = new Discapacidad();
        if(!$discapacidad->getInformacionDiscapacidad($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('discapacidad') && DwSecurity::isValidKey(Input::post('discapacidad_id_key'), 'form_key')) {
            if(Discapacidad::setDiscapacidad('update', Input::post('discapacidad'))){
                DwMessage::valid('La discapacidad se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->discapacidad = $discapacidad;
        $this->page_title = 'Actualizar discapacidad';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_discapacidad', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $discapacidad = new Discapacidad();
        if(!$discapacidad->getInformacionDiscapacidad($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Discapacidad::setDiscapacidad('delete', array('id'=>$discapacidad->id))) {
                DwMessage::valid('La discapacidad se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta discapacidad no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
