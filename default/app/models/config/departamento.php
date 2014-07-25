<?php
/**
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Javier León
 * @copyright   Copyright (c) 2014  
 */

class Departamento extends ActiveRecord {

    /**
     * Método que se ejecuta antes de inicializar cualquier acción
     */
    public function initialize() {        
       // $this->has_many('sucursal');        
       // $this->validates_presence_of('parroquia', 'message: Ingresa el nombre de la parroquia');        
    }

    /**
     * Método para setear
     * 
     * @param array $data
     * @return
     */
     public static function setDepartamento($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Departamento($data);
        //Se verifica si contiene una data adicional para autocargar
        if ($optData) {
            $obj->dump_result_self($optData);
        }   
        /*if($method!='delete') {
            $obj->ciudad_id = Ciudad::setCiudad($obj->ciudad)->id;        
        }*/
        $rs = $obj->$method();
        
        return ($rs) ? $obj : FALSE;
    }
    /**
     * Método para obtener departamentos
     * @return obj
     */
   public function obtener_departamentos($departamento) {
        if ($departamento != '') {
            $departamento = stripcslashes($departamento);
            $res = $this->find('columns: nombre', "nombre like '%{$departamento}%'");
            if ($res) {
                foreach ($res as $departamento) {
                    $departamentos[] = $departamento->nombre;
                }
                return $departamentos;
            }
        }
        return array('no hubo coincidencias');
    }
    /**
     * Método que devuelve los departamentos paginadas o para un select
     * @param int $pag Número de página a mostrar.
     * @return ActiveRecord
     */
    public function getListadoDepartamento($order='order.nombre.asc', $page=0) {        
        $order = $this->get_order($order, 'nombre');
        $columns = 'sucursal.id, sucursal.sucursal, departamento.id, departamento.nombre, departamento.observacion';
        $join= 'INNER JOIN sucursal ON sucursal.id = departamento.sucursal_id ';           
           
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join");
        }    
    }
    //Funcion que realiza la busqueda del los departamentos en funcion de las sucursales
    public function buscar($sucursal_id){
        return $this->find("sucursal_id = $sucursal_id", 'order: nombre');
    }
}
