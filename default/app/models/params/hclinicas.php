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

class Hclinicas extends ActiveRecord {

    /**
     * Método contructor
     */
    public function initialize() {
       // $this->has_many('empresa');
       // $this->has_many('persona');
    }

    /**
     * Método para listar los tipos de identificación
     * @return array
     */
    public function getPeriodos() {
        return $this->find_all_by_sql("SELECT date_part('month'::text, w.fsiniestro)
as mes, count(w.fsiniestro) as total_clinicas  FROM hclinicas w  
 GROUP BY  date_part('month'::text, w.fsiniestro)
 order by mes");
    }
    public function getCountClinicastitular($id) {
        return $this->find_all_by_sql("SELECT CASE WHEN COUNT(1) = 0
THEN 0
ELSE count(tcedula) END
from hclinicas where tcedula ='".$id."'  and pcedula = '".$id."'  
");
    }
    public function getCountClinicasbeneficiario($id) {
        return $this->find_all_by_sql("SELECT CASE WHEN COUNT(1) = 0
THEN 0
ELSE count(tcedula) END
from hclinicas where tcedula ='".$id."' and pcedula <> '".$id."' 
");
    }


    public function getMontoClinicastitular($id) {
        return $this->find_all_by_sql("SELECT CASE WHEN count(1) = 0
THEN 0
ELSE sum(monto_egreso)  END
from hclinicas where tcedula ='".$id."' and pcedula = '".$id."'");
    }
    public function getMontoClinicasbeneficiario($id) {
        return $this->find_all_by_sql("SELECT CASE WHEN count(1) = 0
THEN 0
ELSE sum(monto_egreso)  END
from hclinicas where tcedula ='".$id."' and pcedula <> '".$id."'");
    }
}
?>
