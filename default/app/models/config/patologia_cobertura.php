<?php
/**
 * infoalex
 * @category
 * @package     Models
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */
class PatologiaCobertura extends ActiveRecord {

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('patologia');
    }  
        
    public function getPatoCoberToArray() {
        $data = array();
        $patocobers = $this->find();
        foreach($patocobers as $patocober) {
            $data[] = $patocober->patologia_id.'-'.$patocober->cobertura_id;
        }        
        return $data;
    }
    /**
    * Método para registrar las patologias coberturtas xD
    */
    public static function setPatologiaCobertura($patocobers, $old_patocobers=NULL) {
        $obj = new PatologiaCobertura();
        $obj->begin();
        //Elimino los antiguos patocobers
        if(!empty($old_patocobers)) {
            $items = explode(',', $old_patocobers);
            foreach($items as $value) {
                $data = explode('-', $value); //el formato es 1-4 = recurso-rol
                    if(!$obj->delete("patologia_id = $data[0] AND cobertura_id = $data[1]")){                    
                        $obj->rollback();
                        return FALSE;
                    }                
            }                        
        } 
        if(!empty($patocobers)) {
            foreach($patocobers as $value) {                 
                $data = explode('-', $value); //el formato es 1-4 = recurso_id-perfil_id
                $obj->patologia_id = $data[0];
                $obj->cobertura_id = $data[1];
                if($obj->exists("patologia_id=$obj->patologia_id AND cobertura_id=$obj->cobertura_id")){
                    continue;
                }
                if(!$obj->create()) {            
                    $obj->rollback();
                    return FALSE;
                }
            }
        }
        $obj->commit();
        return TRUE;
    }
    
    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
       
    }
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
        
    }
}
