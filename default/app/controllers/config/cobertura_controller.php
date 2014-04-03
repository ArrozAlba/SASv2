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

Load::models('config/cobertura');

class CoberturaController extends BackendController {
    
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
        $cobertura = new Cobertura();        
        $this->coberturas = $cobertura->getListadoCobertura($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Coberturas';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
        $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('cobertura')) {
            if(Cobertura::setCobertura('create', Input::post('cobertura'), array('empresa_id'=>$empresa->id, 'ciudad'=>Input::post('ciudad')))) {
                DwMessage::valid('La cobertura se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Cobertura';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_cobertura', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $cobertura = new Cobertura();
        if(!$cobertura->getInformacionCobertura($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('cobertura') && DwSecurity::isValidKey(Input::post('cobertura_id_key'), 'form_key')) {
            if(Cobertura::setCobertura('update', Input::post('cobertura'), array('id'=>$id))){
                DwMessage::valid('La cobertura se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        $this->cobertura = $cobertura;
        $this->page_title = 'Actualizar cobertura';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_cobertura', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $cobertura = new Cobertura();
        if(!$cobertura->getInformacionCobertura($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Cobertura::setCobertura('delete', array('id'=>$cobertura->id))) {
                DwMessage::valid('La cobertura se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta cobertura no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
    
}