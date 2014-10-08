<?php
/**
 * infoalex
 * @category
 * @package     Models Factura
 * @subpackage
 * @author      Alexis borges
 * @copyright    
 */
class Hreembolso extends ActiveRecord {

    public function getListadoReembolsoReporte() {
        $columns = 'hreembolso.* ';       
        $join= ' ORDER BY hreembolso.tcedula ';
        return $this->find("columns: $columns", "join: $join");
       }

    public function getListadoHReembolso($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'hreembolso.* ';
        $order = $this->get_order($order, 'hreembolso', array('hreembolso'=>array('ASC'=>'hreembolso.tipo_nomina ASC, hreembolso.titular ASC',
                                                                              'DESC'=>'hreembolso.tipo_nomina DESC, hreembolso.titular DESC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "order: $order", "page: $page");            
        }
    }
    /**
     * Método para buscar Reembolso
     */
    public function getAjaxReembolsos($field, $value, $order='', $page=0) {
        $value = Filter::get($value, 'string');
        if( strlen($value) < 1 OR ($value=='none') ) {
            return NULL;
        }
        $columns = 'hreembolso.* ';
        $order = $this->get_order($order, 'titular', array(                        
            'titular' => array(
                'ASC'=>'hreembolso.titular ASC', 
                'DESC'=>'hreembolso.titular DESC'
            ),
            'paciente' => array(
                'ASC'=>'hreembolso.paciente ASC, hreembolso.titular ASC', 
                'DESC'=>'hreembolso.paciente DESC, hreembolso.titular DESC'
            ),
            'parentesco' => array(
                'ASC'=>'hreembolso.parentesco ASC, hreembolso.titular ASC, hreembolso.paciente ASC', 
                'DESC'=>'hreembolso.parentesco DESC, hreembolso.titular DESC, hreembolso.paciente DESC'
            ),
            'fecha_solicitud' => array(
                'ASC'=>'hreembolso.fecha_solicitud ASC, hreembolso.titular ASC', 
                'DESC'=>'hreembolso.fecha_solicitud DESC, hreembolso.titular DESC'
            ),
            'fecha_recibido' => array(
                'ASC'=>'hreembolso.fecha_recibido ASC, hreembolso.titular ASC', 
                'DESC'=>'hreembolso.fecha_recibido DESC, hreembolso.titular DESC'
            ),
            'sede' => array(
                'ASC'=>'hreembolso.sede ASC', 
                'DESC'=>'hreembolso.sede DESC'
            ),
            'tipo_nomina' => array(
                'ASC'=>'hreembolso.tipo_nomina ASC, hreembolso.titular ASC', 
                'DESC'=>'hreembolso.tipo_nomina DESC, hreembolso.titular DESC'
            ),
            'tipo_gasto' => array(
                'ASC'=>'hreembolso.tipo_gasto ASC, hreembolso.titular ASC', 
                'DESC'=>'hreembolso.tipo_gasto DESC, hreembolso.titular DESC'
            ),
            'monto_pagado' => array(
                'ASC'=>'hreembolso.monto_pagado ASC, hreembolso.titular ASC', 
                'DESC'=>'hreembolso.monto_pagado DESC, hreembolso.titular DESC'
            ),
        ));
        //Defino los campos habilitados para la búsqueda
        $fields = array('titular', 'paciente', 'parentesco','fecha_solicitud', 'fecha_recibido','sede','tipo_nomina','tipo_gasto','monto_pagado');
        //if(!in_array($field, $fields)) {
          //  $field = 'nombre1';
        //}        
        //if(! ($field=='sucursal' && $value=='todas') ) {
          $conditions= " $field LIKE '%$value%'";
        //} 

        if($page) {
            return $this->paginated("columns: $columns", "conditions: $conditions",  "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "conditions: $conditions", "order: $order");
        }  
        //"conditions: $conditions",
    }
     /**
     * Método para buscar Reembolso e imprimr el reportes
     */
    public function getListadoReembolsoFiltrado($field, $value, $order='') {
        $value = Filter::get($value, 'string');
        if( strlen($value) < 1 OR ($value=='none') ) {
            return NULL;
        }
        $columns = 'hreembolso.* ';
        
        //Defino los campos habilitados para la búsqueda
        $fields = array('titular', 'paciente', 'parentesco','fecha_solicitud', 'fecha_recibido','sede','tipo_nomina','tipo_gasto','monto_pagado');
        //if(!in_array($field, $fields)) {
          //  $field = 'nombre1';
        //}        
        //if(! ($field=='sucursal' && $value=='todas') ) {
          $conditions= " $field LIKE '%$value%'";
        //} 

        if($page) {
            return $this->paginated("columns: $columns", "conditions: $conditions","page: $page");
        } else {
            return $this->find("columns: $columns", "conditions: $conditions");
        }  
        //"conditions: $conditions",
    }






}

   