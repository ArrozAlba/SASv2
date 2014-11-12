<?php
/**
 * S.A.S
 *
 * Descripcion: Modelo para el manejo de beneficiarioes
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP / E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve) 
 */

Load::models('sistema/usuario', 'sistema/acceso', 'sistema/configuracion');

class UsuarioClave extends ActiveRecord {
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_many('usuario');
    }

    /**
     * Método para validar la fecha de la clave
     */
    public static function clave_valida($idusuario) {    
        $usuario_clave = new UsuarioClave();
        $fecha = $usuario_clave->find("columns: id,usuario_id,fecha_fin","conditions: usuario_id='".$idusuario."'","order: fecha_fin DESC","limit: 1 ");
        $fecha1 = strtotime($fecha[0]->fecha_fin);
        $fechaHoy = date('Y-m-d');
        $fechaHoy2 = strtotime($fechaHoy);
        if($fecha1 >= $fechaHoy2){
                    return 1;
        }
                    return 0;
    }
    public static function fecha_final($fechainicio,$diasadicionales) {    
       $nuevafecha = strtotime ( '+'.$diasadicionales.' day' , strtotime ( $fechainicio ) ) ;
       $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
       $fecha_fin = $nuevafecha;
       return Flash::error('Fecha Inicial: '.$fechainicio.' Fecha Final: '.$fecha_fin.' Dias Caducidad:'.$diasadicionales.' ');
    }
    public static function diasadicionales() {    
        $configseg = new Configuracion();
        $configseg1 = $configseg->getInformacionConfiguracion();
            return $configseg1[1]->dias_caducidad_clave;
    }
    
    public function cambiar_clave($usuario_id, $clave, $clave2) {
    $ffinal =UsuarioClave::fecha_final();
    return false;
        if ($clave == $clave2) {
            if (strlen($clave) >= 6) {
                $usuario_clave = $this->find("columns: id,usuario_id,password,fecha_fin","conditions: usuario_id='".$usuario_id."'","order: fecha_fin DESC","limit: 1 ");
                if ($usuario_clave) {
                    $usuario_clave[0]->usuario_id = $usuario_id;
                    $usuario_clave[0]->fecha_inicio = date('Y-m-d');
                    //$ffinal =UsuarioClave::fecha_final($usuario_clave[0]->fecha_inicio);
                    //return Flash::error('dias caducidad: '.var_dump($ffinal).' ');
                    $configseg = new Configuracion();
                    $configseg1 = $configseg->getInformacionConfiguracion();
                    $nuevafecha = strtotime ( '+'.$configseg1->dias_caducidad_clave.' day' , strtotime ( $obj->fecha_inicio ) ) ;
                    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                   // $obj->fecha_fin = $nuevafecha;


                    //$nuevafecha = strtotime ( '+'.$configseg1->dias_caducidad_clave.' day' , strtotime ( $usuario_clave[0]->fecha_inicio ) ) ;
                    //$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                    $usuario_clave[0]->fecha_fin = $nuevafecha;
                    $usuario_clave[0]->password = sha1($clave);
                    //return Flash::error('dias caducidad: '.$configseg0.' fecha inicio:'.$usuario_clave[0]->fecha_inicio.' fecha fin: '.$usuario_clave[0]->fecha_fin.' var nuevafecha: '.$nuevafecha.'');
                    if ($usuario_clave[0]->create()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    throw new KumbiaException('El usuario no existe');
                }
            
            }
                Flash::error(' La clave debe tener al menos seis (6) caracteres');
            return false;
        } else {
            throw new KumbiaException('Las claves no coinciden');
        }
    }
    /**
     * Método para crear/modificar un objeto de base de datos
     * 
     * @param string $medthod: create, update
     * @param array $data: Data para autocargar el modelo
     * @param array $otherData: Data adicional para autocargar
     * 
     * @return object ActiveRecord
     */
    public static function setClave($method, $data, $optData=null) {
        $obj = new UsuarioClave($data);
        if($optData) {
            $obj->dump_result_self($optData);
        }
        if(!empty($obj->id)) { //Si va a actualizar
            $old = new UsuarioClave();
            $old->find_first($obj->id);
            if(!empty($obj->oldpassword)) { //Si cambia de claves
                if(empty($obj->password) OR empty($obj->repassword)) {
                    DwMessage::error("Indica la nueva contraseña");
                    return false;
                }
                $obj->oldpassword = md5(sha1($obj->oldpassword));
                if($obj->oldpassword !== $old->password) {
                    DwMessage::error("La contraseña anterior no coincide con la registrada. Verifica los datos e intente nuevamente");
                    return false;
                }
            }                       
        }
        //Verifico si las contraseñas coinciden (password y repassword)
        if( (!empty($obj->password) && !empty($obj->repassword) ) OR ($method=='create')  ) { 
            if($method=='create' && (empty($obj->password))) {
                DwMessage::error("Indica la contraseña para el inicio de sesión");
                return false;
            }
            $obj->password = md5(sha1($obj->password));
            //$obj->repassword = md5(sha1($obj->repassword)); mientras luego borrar lo de abajo 
            $obj->repassword = $obj->password;            
            if($obj->password !== $obj->repassword) {
                DwMessage::error('Las contraseñas no coinciden. Verifica los datos e intenta nuevamente.');
                return 'cancel';
            }
        } else {
            if(isset($obj->id)) { //Mantengo la contraseña anterior                    
                $obj->password = $old->password;                                
            }
        }
        $obj->fecha_inicio = date('Y-m-d');
        $configseg = new Configuracion();
        $configseg1 = $configseg->getInformacionConfiguracion();
        $nuevafecha = strtotime ( '+'.$configseg1->dias_caducidad_clave.' day' , strtotime ( $obj->fecha_inicio ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        $obj->fecha_fin = $nuevafecha;

        //return DwMessage::error('La configuracion es: '.$configseg1->dias_caducidad_clave.' la fecha inicio es: '.$obj->fecha_inicio.' la fecha final es: '.$nuevafecha.'.');
        //$fecha = date('Y-m-j');
        //$nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
        //$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        //echo $nuevafecha;
        
        $rs = $obj->$method();
        if($rs) {
            ($method == 'create') ? DwAudit::debug("Se ha registrado el usuario $obj->usuario_id en el sistema") : DwAudit::debug("Se ha modificado la información del usuario $obj->usuario_id");
        }
        return ($rs) ? $obj : FALSE;
    }
              
        /**
     * Método para obtener la información de un usuario
     * @return type
     */
    public function getInformacionUsuarioClave($usuario) {
        $usuario = Filter::get($usuario, 'int');
        if(!$usuario) {
            return NULL;
        }
        $columnas = 'usuario_clave.* ';
        $join = ' ';
        $condicion = "usuario_clave.id = $usuario";        
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
}
?>
