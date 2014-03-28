<?php
/**
 * S.A.S
 *
 * Descripcion: Modelo para el manejo de titulares
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP / E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve) 
 */

Load::models('sistema/usuario', 'personas/persona');

class Titular extends ActiveRecord {
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_one('usuario');
        $this->has_one('persona');

    }
    
    /**
     * Método para setear un Objeto
     * @param string    $method     Método a ejecutar (create, update)
     * @param array     $data       Array para autocargar el objeto
     * @param array     $optData    Array con con datos adicionales para autocargar
     */
    public static function setTitular($method, $data=array(), $optData=array()) {
        $obj = new Titular($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        //Creo otro objeto para comparar si existe
        $old = new Titular($data);
        $check = $old->_getTitularRegistrado('find_first');
        if($check) { //Si existe
            if(empty($obj->id)) {
                $obj->id = $old->id; //Asigno el id del encontrado al nuevo
            } else { //Si se actualiza y existe otro con la misma información
                if($obj->id != $old->id) {
                    DwMessage::info('Lo sentimos, pero ya existe una persona registrada con el mismo número de identificación');
                    return FALSE;
                }
            }
            if($method=='create') { //Si se crea la persona, pero ya está registrada la actualizo
                $method = 'update';
            }
        }
        $rs = $obj->$method();
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método para verificar si una persona ya se encuentra registrada
     * @return obj
     */
    protected function _getTitularRegistrado($method='count') {
        $conditions = "cedula = '$this->cedula'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($method != 'count' && $method !='find_first') {
            $method = 'count';
        }
        return $this->$method("conditions: $conditions");
    }

    /**
     * Callback que se ejecuta antes de guardar/modificar
     */
    public function before_save() {
        $this->tipoempleado_id = Filter::get($this->tipoempleado_id, 'numeric');
        $this->fecha_ingreso = Filter::get($this->fecha_ingreso, 'string'); 
        $this->profesion_id = Filter::get($this->profesion_id, 'numeric');
        $this->departamento_id = Filter::get($this->departamento_id, 'numeric');
        $this->cargo_id = Filter::get($this->cargo_id, 'numeric'); 
        $this->observacion = Filter::get($this->observacion, 'string');
    }    
}
?>
