<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que se encarga de todo lo relacionado con la información de la empresa
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 * 
 * Ajustada Información para adaptarla a los requerimientos del S.A.S.
 * 
 */

class Empresa extends ActiveRecord {

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {        
        //$this->belongs_to('tipo_nuip');
        $this->has_many('sucursal');                                
        $this->validates_presence_of('razon_social', 'message: Ingresa el Nombre de la Empresa');
        $this->validates_presence_of('rif', 'message: Ingresa el RIF de la Empresa');        
        $this->validates_presence_of('pais_id', 'message: Ingresa el Pais de Origen de la Empresa');
        $this->validates_presence_of('estado_id', 'message: Ingresa el Estado de Origen de la empresa');
        $this->validates_presence_of('municipio_id', 'message: Ingresa el Municipio de Origen de la empresa');
        $this->validates_presence_of('parroquia_id', 'message: Ingresa la Parroquia de la empresa');
        $this->validates_presence_of('representante_legal', 'message: Ingresa el nombre del representante legal.');
        $this->validates_presence_of('pagina_web', 'message: Ingresa la pagina Web de la empresa');
        $this->validates_presence_of('telefono', 'message: Ingresa el nombre de la empresa');
        $this->validates_presence_of('fax', 'message: Ingresa el nombre de la empresa');
        $this->validates_presence_of('celular', 'message	: Ingresa el nombre de la empresa');
        $this->validates_presence_of('email', 'message: Ingresa el Correo Electronico de la empresa');        
        $this->validates_email_in('email', 'message: El Correo Electrónico es incorrecto.');
    }

    /**
     * Método para obtener la información de la empresa
     * @return obj
     */
    public function getInformacionEmpresa() {
        $columnas = 'empresa.*';
        $join = '';
        return $this->find_first("columns: $columnas", "join: $join", 'conditions: empresa.id IS NOT NULL', 'order: empresa.fecha_registro DESC');
    }    
    
    /**
     * Método para registrar y modificar los datos de la empresa
     * 
     * @param string $method Método para guardar en la base de datos (create, update)
     * @param array $data Array de datos para la autocarga de objetos
     * @param arraty $other Se utiliza para autocargar datos adicionales al objeto
     * @return Empresa
     */
    public static function setEmpresa($method, $data, $optData=null) {
        $obj = new Empresa($data);
        if($optData) {
            $obj->dump_result_self($optData);
        }
        $rs = $obj->$method();
        return ($rs) ? $obj : NULL;            
    }
    
    public function after_save() {
        Session::delete('empresa', 'config');
        //Si no está habilitado para el manejo de sucursal
        //registro la ubicación de la empresa como sucursal
        if(!APP_OFFICE && Input::hasPost('sucursal')) {             
            Sucursal::setSucursal('save', Input::post('sucursal'), array('sucursal'=>'Oficina Principal', 'parroquia_id'=>Input::post('parroquia_id'), 'empresa_id'=>$this->id));
        }
    }

    /**
     * Método para filtrar la información de la empresa
     */
    public function getFiltradoEmpresa() {        
        $this->razon_social = Filter::get($this->razon_social, 'string');
        $this->rif = Filter::get($this->rif, 'string');
        $this->pais_id = Filter::get($this->pais_id, 'numeric');        
        $this->estado_id = Filter::get($this->estado_id, 'numeric');
        $this->municipio_id = Filter::get($this->municipio_id, 'numeric');
        $this->parroquia_id = Filter::get($this->parroquia_id, 'numeric');
        $this->representante_legal = Filter::get($this->representante_legal, 'string');
        $this->pagina_web = Filter::get($this->pagina_web, 'string');
        $this->telefono = Filter::get($this->telefono, 'string');        
        $this->fax = Filter::get($this->fax, 'string');
        $this->celular = Filter::get($this->celular, 'string');
        $this->email = Filter::get($this->email, 'string');
    }
    
}
?>
