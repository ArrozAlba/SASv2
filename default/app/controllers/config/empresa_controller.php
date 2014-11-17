<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de la empresa
 *
 * @category
 * @package     Controllers
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/empresa', 'config/sucursal');
Load::models('params/pais', 'params/estado', 'params/municipio', 'params/parroquia');

class EmpresaController extends BackendController {

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Configuraciones';
    }

    /**
     * Método principal
     */
    public function index() {
        $pais = new Pais(); 
        $estado = new Estado(); 
        $municipio = new Municipio();

        if(Input::hasPost('empresa')) {
            if(DwSecurity::isValidKey(Input::post('empresa_id_key'), 'form_key')) {
                if(Empresa::setEmpresa('save', Input::post('empresa'))) {
                    DwMessage::valid('Los datos se han actualizado correctamente');
                } else {
                    DwMessage::get('error_form');
                }
            }
        }

        $empresa = new Empresa();
        if(!$empresa->getInformacionEmpresa()) {
            DwMessage::get('id_no_found');
            return DwRedirect::toRoute('module: dashboard', 'controller: index');
        }

        if(!APP_OFFICE) {
            $sucursal = new Sucursal();
            $this->sucursal = $sucursal->getInformacionSucursal(1);
            $this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        }

        $this->empresa = $empresa;
        $this->pais = $pais->getListadoPais();           
        $this->estado = $estado->getListadoEstado(); 
        $this->municipio = $municipio->getListadoMunicipio();
        $this->page_title = 'Información de la empresa';
    }

    /**
     * Método para subir imágenes
     */
    public function upload() {
        $upload = new DwUpload('logo', 'img/upload/empresa');
        $upload->setAllowedTypes('png|jpg|gif|jpeg');
        $upload->setEncryptName(TRUE);
        $upload->setSize(200, 50, TRUE);
        if(!$data = $upload->save()) { //retorna un array('path'=>'ruta', 'name'=>'nombre.ext');
            $data = array('error'=>$upload->getError());
        }
        sleep(1);//Por la velocidad del script no permite que se actualize el archivo
        View::json($data);
    }
    public function getEstadoPais(){
       View::response('view'); 
       $this->pais_id=Input::post('pais_id');
    }

    public function getMunicipioEstado(){
       View::response('view'); 
       $this->estado_id=Input::post('estado_id');
    }

     public function getParroquiaMunicipio(){
       View::response('view'); 
       $this->municipio_id=Input::post('municipio_id');
    }

}

