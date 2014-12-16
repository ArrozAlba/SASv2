<?php
/**
 * S.A.S
 *
 * Descripcion: Controlador para resetear claves rapidito
 *
 * @category
 * @package     Controllers
 * @subpackage
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP / E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve) 
 */

Load::models('beneficiarios/titular', 'config/sucursal', 'sistema/usuario_clave', 'sistema/configuracion');

class ResetearClaveController extends BackendController {
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Reseteo de Claves';
    }
    /**
    * Método para cambiar clave
    */
    public function resetear_clave() {
        
    }
}