<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Modelo encargado de registrar las personas en el sistema
 *
 * @category
 * @package     Models
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2012 Dailyscript Team (http://www.dailyscript.com.co)
 * @revision    1.0
 */

Load::models('sistema/usuario','beneficiarios/titular');

class Persona extends ActiveRecord {

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function initialize() {
        $this->has_one('usuario');
        $this->has_one('titular');
        $this->has_one('beneficiario');

        //$this->belongs_to('tipo_nuip');
    }

    /**
     * Método para setear un Objeto
     * @param string    $method     Método a ejecutar (create, update)
     * @param array     $data       Array para autocargar el objeto
     * @param array     $optData    Array con con datos adicionales para autocargar
     */
    public static function setPersona($method, $data=array(), $optData=array()) {
        $obj = new Persona($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        //Creo otro objeto para comparar si existe
        $old = new Persona($data);
        $check = $old->_getPersonaRegistrada('find_first');
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
     * Método para obtener titulares
     * @return obj
     */
   public function obtener_personas($persona) {
        if ($persona != '') {
            $persona = stripcslashes($persona);
            $res = $this->find('columns: id,cedula,nombre1,nombre2,apellido1,apellido2', "nombre1 like '%{$persona}%' or apellido1 like '%{$persona}%' or nombre2 like '%{$persona}%' or apellido2 like '%{$persona}%' or cedula like '%{$persona}%'");
            if ($res) {
                foreach ($res as $persona) {
                    $personas[] =  array('id'=>$persona->id,'value'=>$persona->nombre1.' '.$persona->nombre2.' '.$persona->apellido1.' '.$persona->apellido2);
                }
                return $personas;
            }
        }
        return array('no hubo coincidencias');
    }
    /**
     * Método para verificar si una persona ya se encuentra registrada
     * @return obj
     */
    protected function _getPersonaRegistrada($method='count') {
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
        $this->cedula = Filter::get($this->cedula, 'string');
		$this->nacionalidad = Filter::get($this->nacionalidad, 'string');
        $this->nombre1 = Filter::get($this->nombre1, 'string');
        $this->nombre2 = Filter::get($this->nombre2, 'string');        
        $this->apellido1 = Filter::get($this->apellido1, 'string');
        $this->apellido2 = Filter::get($this->apellido2, 'string');        
        $this->sexo = Filter::get($this->sexo, 'string'); 
        $this->fecha_nacimiento = Filter::get($this->fecha_nacimiento, 'string'); 
        $this->pais_id = Filter::get($this->pais_id, 'numeric');
        $this->estado_id = Filter::get($this->estado_id, 'numeric');
        $this->municipio_id = Filter::get($this->municipio_id, 'numeric');
        $this->parroquia_id = Filter::get($this->parroquia_id, 'numeric');                        
        $this->direccion_habitacion = Filter::get($this->direccion_habitacion, 'string'); 
        $this->estado_civil = Filter::get($this->estado_civil, 'string'); 
        $this->celular = Filter::get($this->celular, 'numeric');
        $this->telefono = Filter::get($this->telefono, 'numeric');
        $this->correo_electronico = Filter::get($this->correo_electronico, 'string'); 
        $this->grupo_sanguineo = Filter::get($this->grupo_sanguineo, 'string'); 
        $this->fotografia = Filter::get($this->fotografia, 'string'); 
    }

}
