<?php
/**
 * Alexis
 *
 * Descripcion: Controlador que se encarga de la gestión de los Facturas de las olicitudes
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('solicitudes/factura');

class FacturaController extends BackendController {
    
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
        $cargo = new Factura();        
        $this->cargos = $cargo->getListadoFactura($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Factura';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('cargo')) {
            if(Factura::setFactura('create', Input::post('cargo'))) {
                DwMessage::valid('La cargo se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Factura';
    }
     /**
     * Método para obtener cargos
     */
    
        //accion que busca en los cargos y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $cargos = Load::model('config/cargo')->obtener_cargos($busqueda);
            die(json_encode($cargos)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_cargo', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $cargo = new Factura();
        if(!$cargo->getInformacionFactura($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('cargo') && DwSecurity::isValidKey(Input::post('cargo_id_key'), 'form_key')) {
            if(Factura::setFactura('update', Input::post('cargo'))){
                DwMessage::valid('La cargo se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->cargo = $cargo;
        $this->page_title = 'Actualizar cargo';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_cargo', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $cargo = new Factura();
        if(!$cargo->getInformacionFactura($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Factura::setFactura('delete', array('id'=>$cargo->id))) {
                DwMessage::valid('La cargo se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta cargo no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
