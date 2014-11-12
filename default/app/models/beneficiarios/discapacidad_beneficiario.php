<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que gestiona todo lo relacionado con los
 * recursos de los usuarios con su respectivo grupo
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Alexis Borges 
 * @copyright     
 */

class DiscapacidadBeneficiario extends ActiveRecord {
    
    //Se desabilita el logger para no llenar el archivo de "basura"
    public $logger = FALSE;
        
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('discapacidad');
    }

    /**
     * Método que retorna los recursos asignados a un perfil de usuario
     * @param int $perfil Identificador el perfil del usuario
     * @return array object ActieRecord
     */
    public function getDiscapacidadBeneficiario($id) {
        $id = Filter::get($id,'numeric');
        $columnas = 'discapacidad_beneficiario.* ';
        $condicion = "beneficiario_id = '$id'";
        if($id) {
            return $this->find("columns: $columnas", "conditions: $condicion");
        }
        return false;                                
    }
    
    /**
     * Método para listar los privilegios y compararlos con los recursos y perfiles
     * @return array
     */
    public function getPrivilegiosToArray() {
        $data = array();
        $privilegios = $this->find();
        foreach($privilegios as $privilegio) {
            $data[] = $privilegio->recurso_id.'-'.$privilegio->perfil_id;
        }        
        return $data;
    }
    
    /**
     * Método para registrar los privilegios a los perfiles
     */
    public static function setDiscapacidadBeneficiario($datos,$idbene){
        $obj = new DiscapacidadBeneficiario();
        $obj->begin();
        if(!empty($datos)) {
            foreach($datos as $value) {                 
                $data = explode('-', $value); //el formato es 1-4 = recurso_id-perfil_id
                $obj->discapacidad_id = $data[0];
                $obj->beneficiario_id = $idbene;
                if($obj->exists("discapacidad_id=$obj->discapacidad_id AND beneficiario_id=$obj->beneficiario_id")){
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
}
?>