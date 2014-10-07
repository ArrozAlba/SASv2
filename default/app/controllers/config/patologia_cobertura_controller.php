<?php
/**
 * @category    
 * @package     Controllers 
 * @author      ALexis Borges
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/patologia','config/cobertura','config/patologia_cobertura');
class PatologiaCoberturaController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de permisos';
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
    public function listar($order='order.modulo.asc', $page='pag.1') { 
        
        if(Input::hasPost('patocobers') OR Input::hasPost('old_patocobers')) {
            if(PatologiaCobertura::setPatologiaCobertura(Input::post('patocobers'), Input::post('old_patocobers'))) {
                DwMessage::valid('Los patologias se han asociado correcatamete a las coberturas!');                
                Input::delete('patocobers');//Para que no queden persistentes
                Input::delete('old_patocobers');
            }
        }
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;                 
        $patologia = new Patologia();
        $this->patologias = $patologia->getListadoPatologia($order, $page);
        $cobertura = new Cobertura();
        $this->coberturas = $cobertura->getListadoCobertura();
        
        $patocober = new PatologiaCobertura();
        $this->patocobers = $patocober->getPatoCoberToArray();
        
        $this->order = $order;             
        $this->page_title = 'Asignacion de Patologias a las Coberturas';
    }   
}

