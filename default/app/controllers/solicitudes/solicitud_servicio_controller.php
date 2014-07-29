<?php
/**
 * UPTP - (PNFI Sección 1236) 
 *
 * Descripcion: Controlador que se encarga de la gestión de las profesiones de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP - (PNFI Team) (https://github.com/ArrozAlba/SASv2)
 */
Load::models('solicitudes/solicitud_servicio');
Load::models('config/tiposolicitud');
Load::models('proveedorsalud/proveedor');
Load::models('proveedorsalud/servicio');
Load::models('proveedorsalud/medico');
Load::models('proveedorsalud/especialidad');
Load::models('beneficiarios/titular');
Load::models('beneficiarios/beneficiario');

class SolicitudServicioController extends BackendController {
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Solicitudes';
    }
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('registro');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Solicitudes de Atención Primaria';
    }

    
    /**
     * Método para registro
     */
    public function registro($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoRegistroSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Registro de Solicitudes de Atención Primaria';
    }
    /**
     * Método para aprobacion
     */
    public function aprobacion($order='order.nombre.asc', $page='pag.1') { 
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        	$solicitud_servicio = new SolicitudServicio();        
        	$this->solicitud_servicios = $solicitud_servicio->getListadoAprobacionSolicitudServicio($order, $page);
        	$this->order = $order;        
        	$this->page_title = 'Aprobación de Solicitudes de Atención Primaria';
    }
    /**
     * Método para contabilizar
     */
    public function contabilizar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoContabilizarSolicitudServicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Contabilizar Solicitudes de Atención Primaria';
    }
    /**
     * Método para agregar
     */
    public function agregar() {
        $empresa = Session::get('empresa', 'config');
        $solicitud_servicio = new SolicitudServicio();
        $nroids = $solicitud_servicio->count("tiposolicitud_id = 1");
        $this->codigods=$nroids+1;
		$correlativ= new Tiposolicitud();
        $codigocorrelativo = $correlativ->find("columns: correlativo","conditions: id=1 ", "limit: 1 ");
         foreach ($codigocorrelativo as $cargoa) {
                    $this->cargoas[] = $cargoa->correlativo;
                }
        $this->codigodd=$this->cargoas[0].'00'.$this->codigods;
        $beneficiario = new beneficiario(); 
        $this->beneficiario = $beneficiario->getListBeneficiario();              
        if(Input::hasPost('solicitud_servicio')) {
            if(SolicitudServicio::setSolicitudServicio('create', Input::post('solicitud_servicio'))) {
                DwMessage::valid('La solicitud se ha registrado correctamente!');
                return DwRedirect::toAction('registro');
            }            
        } 
       // $this->personas = Load::model('beneficiarios/titular')->getTitularesToJson();
        $this->page_title = 'Agregar Solicitud de Servicio';
    }
    /**
    *Metodo para aprobar las solicitudes (Cambiar de Estatus)
    */

    public function aprobar($key){
    	if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('aprobacion');
        } 
        //Mejorar esta parte  implementando algodon de seguridad
        $solicitud_servicio = new SolicitudServicio();
        $sol = $solicitud_servicio->getInformacionSolicitudServicio($id);
        $sol->estado_solicitud="A";
        $sol->save();

        //$sol-> codigo_solicitud es para crear el reporte
        $cod = $sol->codigo_solicitud;
        $nro = $sol->celular;
        $nombre = $sol->nombre;
        $apellido = $sol->apellido;
        
        $contenido= "Sr. ".$nombre." ".$apellido." Su solicitud ha sido aprobada Aprobada con el codigo: ".$cod;
        $destinatario=$nro;
        system( '/usr/bin/gammu -c /etc/gammu-smsdrc --sendsms EMS ' . escapeshellarg( $destinatario ) . ' -text ' . escapeshellarg( $contenido ) ); 

        return DwRedirect::toAction('reporte_aprobacion/'.$id);
    }
    /**
    *Metodo para aprobar las solicitudes (Cambiar de Estatus)
    */

    public function reversar_aprobacion($key){
    	if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('aprobacion');
        } 
        //Mejorar esta parte  implementando algodon de seguridad
        $solicitud_servicio = new SolicitudServicio();
        $sol = $solicitud_servicio->getInformacionSolicitudServicio($id);
        $sol->estado_solicitud="R";
        $sol->save();
        return DwRedirect::toAction('aprobacion');
    }
    /**
     * Método para formar el reporte en pdf 
     */
    public function reporte_aprobacion($id) { 
        View::template(NULL);       
       // if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
       //     return DwRedirect::toAction('aprobacion');
       // }

        //Mejorar esta parte  implementando algodon de seguridad
        $solicitud_servicio = new SolicitudServicio();
                if(!$sol = $solicitud_servicio->getReporteSolicitudServicio($id)) {
            DwMessage::get('id_no_found');
        };
        $this->fecha_sol = $solicitud_servicio->fecha_solicitud;
        $this->nombres = strtoupper($solicitud_servicio->nombre1." ".$solicitud_servicio->nombre2);
        $this->apellidos = strtoupper($solicitud_servicio->apellido1." ".$solicitud_servicio->apellido2);
        $this->cedula = $solicitud_servicio->cedula;
        $this->telefono = $solicitud_servicio->telefono;
        $this->celular = $solicitud_servicio->celular;
        $this->nacionalidad = $solicitud_servicio->nacionalidad;        
        $this->sexo = $solicitud_servicio->sexo;        
        $this->upsa = $solicitud_servicio->upsa; 
        //llamada a otra funcion, ya que no logre un solo query para ese reportee! :S
        $titular = new titular();
        $datoslaborales = $titular->getInformacionLaboralTitular($id);
        //$this->upsa = $titular->sucursal;
        $this->direccionlaboral = strtoupper($titular->direccion);
        $this->municipio_laboral = strtoupper($titular->municipios);
        $this->estado_laboral = strtoupper($titular->estados);
        $this->pais_laboral = strtoupper($titular->paiss);
        $this->cargo = strtoupper($titular->cargo);
        //instanciando la clase beneficiario 
        $beneficiario = new beneficiario();
        $this->beneficiarios = $beneficiario->getListadoBeneTitular($id);
    }
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('registro');
        }        
        
        $solicitud_servicio = new SolicitudServicio();
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
        
        if(Input::hasPost('solicitud_servicio') && DwSecurity::isValidKey(Input::post('solicitud_servicio_id_key'), 'form_key')) {
            if(SolicitudServicio::setSolicitudServicio('update', Input::post('solicitud_servicio'), array('id'=>$id))){
                DwMessage::valid('La solicitud se ha actualizado correctamente!');
                return DwRedirect::toAction('contabilizar');
            }
        } 
        $this->solicitud_servicio = $solicitud_servicio;
        $this->page_title = 'Actualizar solicitud';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $solicitud_servicio = new SolicitudServicio();
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(SolicitudServicio::setSolicitudServicio('delete', array('id'=>$solicitud_servicio->id))) {
                DwMessage::valid('La solicitud se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta solicitud no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
