<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de las profesiones de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/profesion');

class ProfesionController extends BackendController {
    
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
        $profesion = new Profesion();        
        $this->profesiones = $profesion->getListadoProfesion($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Profesiones';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('profesion')) {
            if(Profesion::setProfesion('create', Input::post('profesion'))) {
                DwMessage::valid('La profesion se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Profesion';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_profesion', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $profesion = new Profesion();
        if(!$profesion->getInformacionProfesion($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('profesion') && DwSecurity::isValidKey(Input::post('profesion_id_key'), 'form_key')) {
            if(Profesion::setProfesion('update', Input::post('profesion'))){
                DwMessage::valid('La profesion se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->profesion = $profesion;
        $this->page_title = 'Actualizar profesion';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_profesion', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $profesion = new Profesion();
        if(!$profesion->getInformacionProfesion($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Profesion::setProfesion('delete', array('id'=>$profesion->id))) {
                DwMessage::valid('La profesion se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta profesion no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}