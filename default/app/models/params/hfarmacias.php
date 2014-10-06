<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que gestiona lo relacionado con los tipos de identificacion
 *
 * @category    Parámetros
 * @package     Models
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class Hfarmacias extends ActiveRecord {

    /**
     * Método contructor
     */
    public function initialize() {
     //   $this->has_many('empresa');
       // $this->has_many('persona');
    }

    /**
     * Método para listar los tipos de identificación
     * @return array
     */
    public function getPeriodos() {
        return $this->find_all_by_sql("SELECT date_part('month'::text, w.ffactura)
as mes, count(w.ffactura) as total_farmacias  FROM hfarmacias w  
 GROUP BY  date_part('month'::text, w.ffactura)
 order by mes");
    }
}
?>
