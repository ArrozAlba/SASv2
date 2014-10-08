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
Load::models('params/hclinicas','params/hreembolso','params/hfarmacias');

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
        
        $this->ct = ($this->codigodd1+$this->codigodd2 * $this->codigodd)/100; 
	
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoRegistroSolicitudServicioEscritorio();
    
        $hreembolso_periodo = new Hreembolso();        
        $this->hreembolso_periodos = $hreembolso_periodo->getPeriodos();

        $hfarmacia_periodo = new Hfarmacias();        
        $this->hfarmacia_periodos = $hfarmacia_periodo->getPeriodos();        
     
        $hclinica_periodo = new Hclinicas();        
        $this->hclinica_periodos = $hclinica_periodo->getPeriodos();     
    
        $datotitular = new Titular();
        $titularid = Session::get('titular_id');
        $this->datostitular = $datotitular->getInformacionTitular($titularid); 
        $cedtitular = $datotitular->getInformacionTitular($titularid); 
        
        $idcedula = $cedtitular->cedula;
        $hreembolso_counttitu = new Hreembolso();        
        $this->hreembolso_countitular = $hreembolso_counttitu->getCountRembolsostitular($idcedula);
        
        $hreembolso_montotitu = new Hreembolso();        
        $this->hreembolso_montotitular = $hreembolso_montotitu->getMontoRembolsostitular($idcedula);

        $hreembolso_countbene = new Hreembolso();        
        $this->hreembolso_countbeneficiario = $hreembolso_countbene->getCountRembolsosbeneficiario($idcedula);

        $hreembolso_montobene = new Hreembolso();        
        $this->hreembolso_montobeneficiario = $hreembolso_montobene->getMontoRembolsosbeneficiario($idcedula);
######################################## Farmacias
        $hfarmacia_counttitu = new Hfarmacias();        
        $this->hfarmacia_countitular = $hfarmacia_counttitu->getCountFarmaciastitular($idcedula);
        
        $hfarmacia_montotitu = new Hfarmacias();        
        $this->hfarmacia_montotitular = $hfarmacia_montotitu->getMontoFarmaciastitular($idcedula);

        $hfarmacia_countbene = new Hfarmacias();        
        $this->hfarmacia_countbeneficiario = $hfarmacia_countbene->getCountFarmaciasbeneficiario($idcedula);

        $hfarmacia_montobene = new Hfarmacias();        
        $this->hfarmacia_montobeneficiario = $hfarmacia_montobene->getMontoFarmaciasbeneficiario($idcedula);                
#################################### clinicas 
        $hclinica_counttitu = new Hclinicas();        
        $this->hclinica_countitular = $hclinica_counttitu->getCountClinicastitular($idcedula);
        
        $hclinica_montotitu = new Hclinicas();        
        $this->hclinica_montotitular = $hclinica_montotitu->getMontoClinicastitular($idcedula);

        $hclinica_countbene = new Hclinicas();        
        $this->hclinica_countbeneficiario = $hclinica_countbene->getCountClinicasbeneficiario($idcedula);

        $hclinica_montobene = new Hclinicas();        
        $this->hclinica_montobeneficiario = $hclinica_montobene->getMontoClinicasbeneficiario($idcedula);                
    
        $beneficiario = new beneficiario();        
        $this->beneficiarios = $beneficiario->getListadoBeneTitular($titularid);         
    
    
    
    
    
    }
}
