<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de las patologias de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/patologia');

class PatologiaController extends BackendController {
    
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
        $patologia = new Patologia();        
        $this->patologias = $patologia->getListadoPatologia($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Patologias';
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
            $patologias = Load::model('config/patologia')->obtener_patologias($busqueda);
            die(json_encode($patologias)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }    
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('patologia')) {
            if(Patologia::setPatologia('create', Input::post('patologia'))) {
                DwMessage::valid('La patologia se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Patologia';
    }
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_patologia', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $patologia = new Patologia();
        if(!$patologia->getInformacionPatologia($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('patologia') && DwSecurity::isValidKey(Input::post('patologia_id_key'), 'form_key')) {
            if(Patologia::setPatologia('update', Input::post('patologia'))){
                DwMessage::valid('La patologia se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->patologia = $patologia;
        $this->page_title = 'Actualizar patologia';        
    }
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_patologia', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $patologia = new Patologia();
        if(!$patologia->getInformacionPatologia($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Patologia::setPatologia('delete', array('id'=>$patologia->id))) {
                DwMessage::valid('La patologia se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta patologia no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
