<?php
/**
 * S.A.S
 *
 * Descripcion: Controlador que se encarga de la gestión de los titulares del sistema
 *
 * @category    
 * @package     Controllers 
 * @author      Javier León (jel1284@gmail.com) Alexis Borges (tuaalexis@gmail.com)
 * @copyright   Copyright (c) 2014 E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve)
 */

Load::models('beneficiarios/titular','sistema/usuario', 'beneficiarios/beneficiario');
Load::models('params/pais', 'params/estado', 'params/municipio', 'params/parroquia');
load::models('config/sucursal', 'config/departamento', 'config/discapacidad', 'beneficiarios/discapacidad_titular');

class TitularController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Titular';
    }
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para buscar
     */
    public function buscar($field='nombre1', $value='none', $order='order.id.asc', $page=1) {        
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $field = (Input::hasPost('field')) ? Input::post('field') : $field;
        $value = (Input::hasPost('field')) ? Input::post('value') : $value;
        $titular = new Titular();
        $titulares = $titular->getAjaxTitular($field, $value, $order, $page);        
        if(empty($titulares->items)) {
            DwMessage::info('No se han encontrado registros');
        }
        $this->titulares = $titulares;
        $this->order = $order;
        $this->field = $field;
        $this->value = $value;
        $this->page_title = 'Búsqueda de titulares del sistema';        
    }
/**
     * Método para obtener titulares
     */
    
        //accion que busca en los titulares y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $titulares = Load::model('beneficiarios/titular')->obtener_titulares($busqueda);
            die(json_encode($titulares)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }
/**
     * Método para obtener patologias
     */
    
        //accion que busca en las patologias y devuelve el json con los datos
    public function autocomplete2() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $patologias = Load::model('patologia')->obtener_patologias($busqueda);
            die(json_encode($patologias)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }
    /**
     * Método para listar
     */
    public function listar($order='order.id.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $titular = new Titular();
        $this->titulares = $titular->getListadoTitular('todos', $order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de titulares del sistema';
    }
    /**
     * Método para agregar
     */
    public function agregar(){
        $pais = new Pais(); 
        $estado = new Estado(); 
        $municipio = new Municipio();
        $sucursal = new Sucursal();
        $discapacidad = new Discapacidad();
        if(Input::hasPost('titular')){
            ActiveRecord::beginTrans();
            $titu = Titular::setTitular('create', Input::post('titular'));
            if($titu){
                if (DiscapacidadTitular::setDiscapacidadTitular(Input::post('discapacidad'),$titu->id)){
                    ActiveRecord::commitTrans();
                    DwMessage::valid('El titular se ha creado correctamente.');
                    return DwRedirect::toAction('listar');
                }
                else{
                    ActiveRecord::rollbackTrans();
                }
            }
            unset($titu);
            $this->pais = $pais->getListadoPais();           
            $this->estado = $estado->getListadoEstado(); 
            $this->municipio = $municipio->getListadoMunicipio(); 
            $this->sucursal = $sucursal->getListadoSucursal(); 
        }


    $this->discapacidad = $discapacidad->getListadoDiscapacidad();
    $this->page_title = 'Agregar';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_titular', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $titular = new Titular();
        if(!$titular->getInformacionTitular($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }
        $titularh = new Titular();
        $titularh->getInformacionDireccionTitular($id);
        if(Input::hasPost('titular')) {
            if(DwSecurity::isValidKey(Input::post('titular_id_key'), 'form_key')) {
                ActiveRecord::beginTrans();
                $titular = Titular::setETitular('update', Input::post('titular'));
                if($titular){
                        ActiveRecord::commitTrans();
                        DwMessage::valid('El titular se ha actualizado correctamente.');
                        return DwRedirect::toAction('listar');
                } else {
                    ActiveRecord::rollbackTrans();
                } 
            }
        }        
        $this->temas = DwUtils::getFolders(dirname(APP_PATH).'/public/css/backend/themes/');
        $this->titular = $titular;
        $this->page_title = 'Actualizar titular';
    }
 
    /**
     * Método para inactivar/reactivar
     */
    public function estado($tipo, $key) {
        if(!$id = DwSecurity::isValidKey($key, $tipo.'_usuario', 'int')) {
            return DwRedirect::toAction('listar');
        } 
        
        $usuario = new Usuario();
        if(!$usuario->getInformacionUsuario($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }
        if($tipo == 'reactivar' && $usuario->estado_usuario == EstadoUsuario::ACTIVO) {
            DwMessage::info('El usuario ya se encuentra activo.');
            return DwRedirect::toAction('listar');
        } else if($tipo == 'bloquear' && $usuario->estado_usuario == EstadoUsuario::BLOQUEADO) {
            DwMessage::info('El usuario ya se encuentra bloqueado.');
            return DwRedirect::toAction('listar');
        }  
        
        if(Input::hasPost('estado_usuario')) {            
            if(EstadoUsuario::setEstadoUsuario($tipo, Input::post('estado_usuario'), array('usuario_id'=>$usuario->id))) { 
                ($tipo=='reactivar') ? DwMessage::valid('El usuario se ha reactivado correctamente!') : DwMessage::valid('El usuario se ha bloqueado correctamente!');
                return DwRedirect::toAction('listar');
            }
        }  
        
        $this->page_title = ($tipo=='reactivar') ? 'Reactivación de usuario' : 'Bloqueo de usuario';
        $this->usuario = $usuario;
    }
//FUNCION PARA CALCULAR EDAD 
    public function tiempo_transcurrido($fecha_nacimiento){
           // $fecha_actual = $fecha_control;
           $fecha_actual = date('d/m/Y');
           
           if(!strlen($fecha_actual))
           {
              $fecha_actual = date('d/m/Y');
           }
           // separamos en partes las fechas 
           $array_nacimiento = explode ( "/", $fecha_nacimiento ); 
           $array_actual = explode ( "/", $fecha_actual );

           $anos =  $array_actual[2] - $array_nacimiento[2]; // calculamos años 
           $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
           $dias =  $array_actual[0] - $array_nacimiento[0]; // calculamos días 

           //ajuste de posible negativo en $días 
           if ($dias < 0) 
           { 
              --$meses; 

              //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
              switch ($array_actual[1]) { 
                 case 1: 
                    $dias_mes_anterior=31;
                    break; 
                 case 2:     
                    $dias_mes_anterior=31;
                    break; 
                 case 3:  
                    if ($this->bisiesto($array_actual[2])) 
                    { 
                       $dias_mes_anterior=29;
                       break; 
                    } 
                    else 
                    { 
                       $dias_mes_anterior=28;
                       break; 
                    } 
                 case 4:
                    $dias_mes_anterior=31;
                    break; 
                 case 5:
                    $dias_mes_anterior=30;
                    break; 
                 case 6:
                    $dias_mes_anterior=31;
                    break; 
                 case 7:
                    $dias_mes_anterior=30;
                    break; 
                 case 8:
                    $dias_mes_anterior=31;
                    break; 
                 case 9:
                    $dias_mes_anterior=31;
                    break; 
                 case 10:
                    $dias_mes_anterior=30;
                    break; 
                 case 11:
                    $dias_mes_anterior=31;
                    break; 
                 case 12:
                    $dias_mes_anterior=30;
                    break; 
              } 

              $dias=$dias + $dias_mes_anterior;

              if ($dias < 0)
              {
                 --$meses;
                 if($dias == -1)
                 {
                    $dias = 30;
                 }
                 if($dias == -2)
                 {
                    $dias = 29;
                 }
              }
           }

           //ajuste de posible negativo en $meses 
           if ($meses < 0) 
           { 
              --$anos; 
              $meses=$meses + 12; 
           }

           $tiempo[0] = $anos;
           $tiempo[1] = $meses;
           $tiempo[2] = $dias;
           return $tiempo;
        }

        public function bisiesto($anio_actual){ 
           $bisiesto=false; 
           //probamos si el mes de febrero del año actual tiene 29 días 
             if (checkdate(2,29,$anio_actual)) 
             { 
              $bisiesto=true; 
           } 
           return $bisiesto; 
        } 

    
    /**
     * Método para formar el reporte en pdf 
     */
    public function reportetitular($key) { 
        View::template(NULL);       
        if(!$id = DwSecurity::isValidKey($key, 'shw_titular', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $titular=new Titular();
        if(!$titular->getInformacionTitular($id)) {
            DwMessage::get('id_no_found');
        }
        $this->nombres = strtoupper($titular->nombre1." ".$titular->nombre2);
        $this->apellidos = strtoupper($titular->apellido1." ".$titular->apellido2);
        if($titular->nacionalidad=="V"){ $this->nacionalidad = "VENEZOLANO"; } else { $this->nacionalidad ="EXTRANJERO"; }
        $this->cedula = $titular->cedula;
        $this->sexo = $titular->sexo;
        $this->fecha_nac = $titular->fecha_nacimiento;
        $this->estado = strtoupper($titular->estado);
        $this->municipio = strtoupper($titular->municipio);
        $this->estado_civil = strtoupper($titular->estado_civil);
        switch ($this->estado_civil) {
            case 'S':
                $this->estado_civil="SOLTERO(A)";
                break;
            case 'C':
                $this->estado_civil="CASADO(A)";
                break;
            case 'D':
                $this->estado_civil="DIVORCIADO(A)";
                break;
            case 'c':
                $this->estado_civil="CONCUBINATO";
                break;
            case 'V':
                $this->estado_civil="VIUDO(A)";
                break;
        }
        $this->telefono = $titular->telefono;
        $this->celular = $titular->celular;
        $this->direccion = strtoupper($titular->direccion_habitacion);
        $this->observacion = strtoupper($titular->observacion);
        $this->correo_electronico = strtoupper($titular->correo_electronico);
        if (strlen($this->fecha_nac)==10)
        {
            $elDia=substr($this->fecha_nac,8,2);
            $elMes=substr($this->fecha_nac,5,2);
            $elYear=substr($this->fecha_nac,0,4);
            $FechaNac=$elDia."/".$elMes."/".$elYear;        
        }
        $this->edadA = $this->tiempo_transcurrido($FechaNac);
        $this->edad = $this->edadA[0];

        
        //llamada a otra funcion, ya que no logre un solo query para ese reportee! :S
        $datosdireccion = $titular->getInformacionDireccionTitular($id);
        $this->hestado = strtoupper($titular->hestado);
        $this->hparroquia = strtoupper($titular->hparroquia);
        $this->hpais = strtoupper($titular->hpais);

        //llamada a otra funcion, ya que no logre un solo query para ese reportee! :S
        $datoslaborales = $titular->getInformacionLaboralTitular($id);

        $this->upsa = $titular->sucursal;
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
     * Método para subir imágenes
     */
    public function upload() {     
        $upload = new DwUpload('fotografia', 'img/upload/personas/');
        $upload->setAllowedTypes('png|jpg|gif|jpeg');
        $upload->setEncryptName(TRUE);
        $upload->setSize(170, 200, TRUE);
        if(!$data = $upload->save()) { //retorna un array('path'=>'ruta', 'name'=>'nombre.ext');
            $data = array('error'=>$upload->getError());
        }
        sleep(1);//Por la velocidad del script no permite que se actualize el archivo
        View::json($data);
    }


    public function getEstadoPais(){
       View::response('view'); 
       $this->pais_id=Input::post('pais_id');
    }

    public function getMunicipioEstado(){
       View::response('view'); 
       $this->estado_id=Input::post('estado_id');
    }

     public function getParroquiaMunicipio(){
       View::response('view'); 
       $this->municipio_id=Input::post('municipio_id');
    }

    public function getDepartamento(){
       View::response('view'); 
       $this->sucursal_id=Input::post('sucursal_id');
    }
    //Funciones para listar los paises estdos, municipios, etc de la direccion de habitacion 

    public function getHEstadoPais(){
       View::response('view'); 
       $this->pais_id=Input::post('pais_id');
    }

    public function getHMunicipioEstado(){
       View::response('view'); 
       $this->estado_id=Input::post('estado_id');
    }

     public function getHParroquiaMunicipio(){
       View::response('view'); 
       $this->municipio_id=Input::post('municipio_id');
    }
}
