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

class Municipio extends ActiveRecord {

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
    public function getListadoMunicipio() {
        return $this->find('order: nombre ASC');
    }

}
?>
