<?php
/**
 * UPTP - (PNFI Sección 1236) 
 *
 * @category    
 * @package     Controllers 
 * @author      Alexis Borges (jel1284@gmail.com)
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
Load::models('config/patologia', 'solicitudes/solicitud_servicio_patologia');

class SolicitudServicioController extends BackendController {
    /**
     * Constante para definir el tipo de solicitud
     */
    const TPS = 2;
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
        $this->solicitud_servicios = $solicitud_servicio->getListadoRegistroSolicitudServicio($order, $page,$tps=self::TPS);
        $this->order = $order;        
        $this->page_title = 'Registro de Solicitudes de Atención Primaria';
    }
    /**
     * Método para aprobacion
     */
    public function aprobacion($order='order.nombre.asc', $page='pag.1') { 
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        	$solicitud_servicio = new SolicitudServicio();        
        	$this->solicitud_servicios = $solicitud_servicio->getListadoAprobacionSolicitudServicio($order, $page,$tps=self::TPS);
        	$this->order = $order;        
        	$this->page_title = 'Aprobación de Solicitudes de Atención Primaria';
    }
    /**
     * Método para cargar las solicitudes siniestradas para mandar a facturar
     */
    public function facturacion($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoSiniestrosSolicitudServicio($order, $page,$tps=self::TPS);
        $this->order = $order;        
        $this->page_title = 'Cargar Facturas a las solicitudes de Atención Primaria';
    }

     /**
     * Método para 
     */
    public function aprobadas($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $solicitud_servicio = new SolicitudServicio();        
        $this->solicitud_servicios = $solicitud_servicio->getListadoContabilizarSolicitudServicio($order, $page,$tps=self::TPS);
        $this->order = $order;        
        $this->page_title = 'Contabilizar Solicitudes de Atención Primaria';
    }
     /**
     * Método para cargar los siniestros
     */
    public function siniestro($key) { 
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('registro');
        }        
        $solicitud_servicio = new SolicitudServicio();
        $solicitud_servicio_patologia = new SolicitudServicioPatologia();
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
        if(Input::hasPost('solicitud_servicio')) {
            ActiveRecord::beginTrans();
            if(SolicitudServicioPatologia::setSolServicioPatolgia(Input::post('patologia_id'), $id)) {
                $sol = $solicitud_servicio->getInformacionSolicitudServicio($id);
                //Input::post('diagnostico') cambie el nombre del campo para poder tomar el valor revisar en el view 
                $sol->diagnostico = strtoupper(Input::post('diagnostico'));
                $sol->motivo = strtoupper(Input::post('motivo'));
                $sol->estado_solicitud="S";
                $sol->save();               
                ActiveRecord::commitTrans();    
                DwMessage::valid('La solicitud se ha contabilizado correctamente!');
                 return DwRedirect::toAction('facturacion');
            }else{
                ActiveRecord::rollbackTrans();
                DwMessage::error('La solicitud ha dao peos!');
                return DwRedirect::toAction('aprobadas');
            }
        } 
        $this->solicitud_servicio = $solicitud_servicio;
        $this->page_title = 'Cargar Siniestro';        
    }
     /**
     * Método para cargar las facturas
     */
    public function facturar($key){
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('registro');
        }
        $solicitud_servicio = new SolicitudServicio();
        $obj = new SolicitudServicioPatologia();
        $this->sol =  $obj->getInformacionSolicitudServicioPatologia($id);
        if(!$solicitud_servicio->getInformacionSolicitudServicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
      /*  if(Input::hasPost('solicitud_servicio') && DwSecurity::isValidKey(Input::post('solicitud_servicio_id_key'), 'form_key')) {
            if(SolicitudServicio::setSolicitudServicio('update', Input::post('solicitud_servicio'), array('id'=>$id))){
                DwMessage::valid('La solicitud se ha contabilizado correctamente!');
                return DwRedirect::toAction('registro');
            }
        }*/ 
        $this->solicitud_servicio = $solicitud_servicio;
        $this->page_title = 'Cargar Facturas a la solicitud';        
    }

    /**
     * Método para agregar
     */
    public function agregar() {
        $empresa = Session::get('empresa', 'config');
        $solicitud_servicio = new SolicitudServicio();
        $nroids = $solicitud_servicio->count("tiposolicitud_id = '".self::TPS."'");
        $this->codigods=$nroids+1;
		$correlativ= new Tiposolicitud();
        $codigocorrelativo = $correlativ->find("columns: correlativo","conditions: id=".self::TPS."", "limit: 1 ");
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
        $cod = $sol->codigo_solicitud;
        $nro = $sol->celular;
        $nombre = $sol->nombre;
        $apellido = $sol->apellido;
        $contenido= "Sr. ".$nombre." ".$apellido." Su solicitud ha sido aprobada Aprobada con el codigo: ".$cod;
        $destinatario=$nro;
        system( '/usr/bin/gammu -c /etc/gammu-smsdrc --sendsms EMS ' . escapeshellarg( $destinatario ) . ' -text ' . escapeshellarg( $contenido ) ); 
        return DwRedirect::toAction('reporte_aprobacion/'.$id);
    }

    /** Metodo para rechazar con motivo una solicitud **/
    public function rechazar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_servicio', 'int')) {
            return DwRedirect::toAction('aprobacion');
        }
        $solicitud_servicio = new SolicitudServicio();
        $sol = $solicitud_servicio->getInformacionSolicitudServicio($id);
        if(!$sol) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('registro');
        }
        if(Input::hasPost('solicitud_servicio')) {
            $es = "E";
            //$motivo = $_POST['solicitud_servicio'];
            if(SolicitudServicio::setSolicitudServicio('update', Input::post('solicitud_servicio'), array('estado_solicitud'=>$es))){
                DwMessage::valid('La solicitud se ha rechazado correctamente!');
                return DwRedirect::toAction('registro');
            }       
        } 
        $this->solicitud_servicio = $sol;
        $this->page_title = 'Rechazar solicitud';        
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
        $this->idtitular = $solicitud_servicio->idtitular;
        $this->bene = $solicitud_servicio->beneficiario_id;
        $this->medico = strtoupper($solicitud_servicio->nombrem1." ".$solicitud_servicio->nombrem2." ".$solicitud_servicio->apellidom1." ".$solicitud_servicio->apellidom2);
        $this->clinica = strtoupper($solicitud_servicio->proveedor);
        $this->servicio = strtoupper($solicitud_servicio->servicio);
        $this->direccion = $solicitud_servicio->direccionp;

        //llamada a otra funcion, ya que no logre un solo query para ese reportee! :S
        $titular = new titular();
        $datoslaborales = $titular->getInformacionLaboralTitular($this->idtitular);
        $this->upsa = $titular->sucursal;
        $this->direccionlaboral = strtoupper($titular->direccion);
        $this->municipio_laboral = strtoupper($titular->municipios);
        $this->estado_laboral = strtoupper($titular->estados);
        $this->pais_laboral = strtoupper($titular->paiss);
        $this->cargo = strtoupper($titular->cargo);
        //instanciando la clase beneficiario 
        
        $beneficiarios = new beneficiario();
        $beneficiarios->getInformacionbeneficiario($this->bene);
        $this->nombresb = strtoupper($beneficiarios->nombre1." ".$beneficiarios->nombre2);
        $this->apellidosb = strtoupper($beneficiarios->apellido1." ".$beneficiarios->apellido2);
        $this->cedulab = $beneficiarios->cedula;
        $this->parentesco = $beneficiarios->parentesco;
 


    }
    /*
     Método para editar solicitudes que estan registradas solamente (ya que el metodo de modificar es para afectar aquellas que fueron rechazads 
     y se van a actualizar)
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
                return DwRedirect::toAction('registro');
            }
        } 
        $this->solicitud_servicio = $solicitud_servicio;
        $this->page_title = 'Actualizar solicitud';        
    }
    /*
        Metodo para modificar las solicitudes de modificacion
    */
    public function modificar($key) {        
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
                return DwRedirect::toAction('registro');
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
