<?php
/**
 * 
 *
 * Clase que gestiona lo relacionado con los paises
 *
 * @category    Parámetros
 * @package     Models
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 Arroz del Alba 
 */

class Estado extends ActiveRecord {

    /**
     * Método contructor
     */
    public function initialize() {
        $this->has_many('empresa');
        $this->has_many('persona');
                
    }

    /**
     * Método para listar los tipos de identificación
     * @return array
     */
    public function getListadoEstado() {
        return $this->find('order: nombre ASC');
    }

    public function buscar($pais_id){
        return $this->find("pais_id = $pais_id", 'order: nombre');
    }

}
?>
