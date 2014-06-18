<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de los departamentos  de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Alexdis Borges
 * @copyright   Copyright (c) 2013 
 */

Load::models('config/departamento');

class DepartamentoController extends BackendController {
    
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
        $departamento = new Departamento();        
        $this->departamentos = $departamento->getListadoDepartamento($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Departamentos';
    }
     /**
     * Método para obtener departamentos
     */
    
        //accion que busca en los departamentos y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $departamentos = Load::model('config/departamento')->obtener_departamentos($busqueda);
            die(json_encode($departamentos)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }        
    /**
     * Método para agregar
     */
    public function agregar() {
        //$empresa = Session::get('empresa', 'config');
        if(Input::hasPost('departamento')) {
            if(Departamento::setDepartamento('create', Input::post('departamento'))){
                DwMessage::valid('El departamento se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Departamento';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_departamento', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $departamento = new Departamento();
        if(!$departamento->getInformacionDepartamento($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('departamento') && DwSecurity::isValidKey(Input::post('sucursal_id_key'), 'form_key')) {
            if(Departamento::setDepartamento('update', Input::post('departamento'), array('id'=>$id, 'empresa_id'=>$departamento->empresa_id, 'ciudad'=>Input::post('ciudad')))) {
                DwMessage::valid('La departamento se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        $this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->departamento = $departamento;
        $this->page_title = 'Actualizar departamento';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_sucursal', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $departamento = new Departamento();
        if(!$departamento->getInformacionDepartamento($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Departamento::setDepartamento('delete', array('id'=>$departamento->id))) {
                DwMessage::valid('La departamento se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta departamento no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
