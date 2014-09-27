<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador para el panel principal de los usuarios logueados
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */
Load::models('solicitudes/solicitud_servicio');
Load::models('beneficiarios/titular');
Load::models('beneficiarios/beneficiario');

class IndexController extends BackendController {
    
    public $page_title = 'Escritorio';
    
    public $page_module = 'Escritorio';
    
    public function index() {
        
        $ctitulares = new Titular();
        $nrotitularids = $ctitulares->count();
        $this->codigodd1=$nrotitularids;

        $cbeneficiarios = new Beneficiario();
        $nrobeneficiarioids = $cbeneficiarios->count();
        $this->codigodd2=$nrobeneficiarioids;		
        
        $csolicitud_servicio = new SolicitudServicio();
        $nroids = $csolicitud_servicio->count();
        $this->codigodd=$nroids;
        
        $this->ct = ($this->codigodd1+$this->codigodd2 * $this->codigodd)/1000; 
	
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoRegistroSolicitudServicio();
    
    }
}
