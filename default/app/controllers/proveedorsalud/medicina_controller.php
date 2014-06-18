<?php
/**
 * Alexis
 *
 * Descripcion: Controlador que se encarga de la gestión de los Medicinas de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('proveedorsalud/medicina');

class MedicinaController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Proveedor de Salud';
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
        $medicina = new Medicina();        
        $this->medicinas = $medicina->getListadoMedicina($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Medicinas';
    }
    
     /**
     * Método para obtener medicinas
     */
    
        //accion que busca en las especialidades y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $medicinas = Load::model('proveedorsalud/medicina')->obtener_medicinas($busqueda);
            die(json_encode($medicinas)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }            
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('medicina')) {
            if(Medicina::setMedicina('create', Input::post('medicina'))) {
                DwMessage::valid('El medicina se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Medicina';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_medicina', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $medicina = new Medicina();
        if(!$medicina->getInformacionMedicina($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('medicina') && DwSecurity::isValidKey(Input::post('medicina_id_key'), 'form_key')) {
            if(Medicina::setMedicina('update', Input::post('medicina'))){
                DwMessage::valid('El medicina se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->medicina = $medicina;
        $this->page_title = 'Actualizar medicina';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_medicina', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $medicina = new Medicina();
        if(!$medicina->getInformacionMedicina($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Medicina::setMedicina('delete', array('id'=>$medicina->id))) {
                DwMessage::valid('La medicina se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Este medicina no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
