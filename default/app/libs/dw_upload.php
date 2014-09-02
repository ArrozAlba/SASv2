<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que se utiliza para la carga de archivos e imágenes
 *
 * @category    
 * @package     Libs
 * @author      Iván D. Meléndez
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

Load::lib('wideimage/WideImage');

class DwUpload {
    
    /**
     * Nombre del input del archivo
     *
     * @var string
     */
    public $file;
    
    /**
     * Extensión del achivo
     *
     * @var string
     */
    public $file_ext;
    
    /**
     * Nombre con el que se guardará el archivo
     *
     * @var string
     */
    public $name;
    /**
     * Ruta estática de las imágenes
     *
     * @var string
     */
    public $path;
    
    /**
     * Tipos de archivo definidos
     * 
     * @var string
     */    
    public $allowedTypes = '*';
    
    /**
     * Indica si cambia el nombre por un md5
     */
    public $encryptName = FALSE;
    
    /**
     * Permitir cambiar el tamaño de la imagen
     *
     * @var boolean
     */
    public $resize = FALSE;
    
    /**
     * Indica si al reescalar se mantiene la imagen anterior o no
     */
    public $removeOld = FALSE;
    
    /**
     * Ancho de la imagen reescalada
     *
     * @var boolean
     */
    public $resizeWidth = 170;

    /**
     * Alto de la imagen reescalada
     *
     * @var boolean
     */
    public $resizeHeight = 200;

    /**
     * Tamaño maximo del archivo
     *
     * @var string
     */
    public $maxSize = "2MB";
    
    /**
     * Variable con los mensajes de error
     *
     * @var string
     */
    protected $_error;
    
    /**
     * Constructor de la clase
     * @param string $input Nombre del input enviado
     * @param string $path Ruta donde se alojará el archivo
     */
    public function  __construct($input='', $path='') {  
        $this->file = $input;
        $this->path = dirname(APP_PATH) . '/public/'.trim($path, '/');        
    }        
    
    /**
     * Función que se encarga de gestionar el archivo
     * @param strin $rename Nombre con el que se guardará el archivo
     */
    public function save($rename='') {
        
        if(!$this->isUploaded()) { //Verifico si está cargado el archivo            
            return FALSE;
        }                
        if (!$this->isWritable()) { //Permite escrituras la ruta?            
            //Los errores son cargados en el método 
            return FALSE;
        }                
        if(!$this->isSizeValid()) { //El tamaño es válido?            
            return FALSE;
        }                              
        if (!$this->isAllowedFiletype()) {// Verifico si el tipo de archivo es permitido             
            return FALSE;
        }         
        //Tomo el nomnbre del nuevo archivo
        $this->name = $this->_setFileName($rename);
        //Tomo el archivo temporal
        $file_tmp = $_FILES[$this->file]['tmp_name'];                
        //Verifico si se sube guarda el archivo
        if(move_uploaded_file($file_tmp, "$this->path/$this->name")) {  
            //Verifico si reescala el archivo
            if($this->resize) {
                if(is_file("$this->path/$this->name")) { //Verifico si existe el archivo
                    @chmod("$this->path/$this->name", 0777);
                    try {
                        if($this->removeOld === TRUE) {
                            WideImage::load($this->path.'/'.$this->name)->resize($this->resizeWidth, $this->resizeHeight)->saveToFile($this->path.'/'.$this->name);
                        } else {
                            WideImage::load($this->path.'/'.$this->name)->resize($this->resizeWidth, $this->resizeHeight)->saveToFile($this->path.'/min_'.$this->name);
                            @chmod("$this->path/min_$this->name", 0777);
                        }                           
                    } catch(Exception $e) {
                        $this->setError('Se ha producido un error al intentar reescalar la imagen. <br />Verifica si el archivo es una imagen.');
                        return FALSE;
                    }
                }
            }
            unset($_FILES[$this->file]);
            return array('path'=>$this->path, 'name'=>$this->name);
        }                
        $this->setError('No se pudo copiar el archivo al servidor. Intenta nuevamente.');
        return FALSE;
                
    }
    
    /**
     * Método para identificar si está cargado el archivo
     * @return type
     */
    public function isUploaded($file='') {
        $file = (empty($file)) ? $this->file : $file;
        if(! (isset($_FILES[$file]) && is_uploaded_file($_FILES[$file]['tmp_name'])) ) {
            $this->setError('El archivo no se ha podidio cargar en el servidor.');
            return FALSE;
        } 
        //Verifico si el archivo cargado contiene errores
        if ($_FILES[$file]['error'] > 0) {            
            $this->setError('El archivo cargado contiene errores. Intenta nuevamente.');
            return FALSE;
        }        
        return TRUE;
    }
    
    /**
     * Método para verificar si se puede escribir sobre un directorio
     */
    public function isWritable($path='') {
        $path = empty($path) ? $this->path : $path;
        if(!file_exists($path)) {
            $this->setError('No fué posible ubicar el directorio de carga del archivo.');
            return FALSE;
        }
        if(!is_writable($path)) {            
            $this->setError('El directorio donde se alojará el archivo no tiene permisos de escritura. '.$path);
            return FALSE;
        }  
        return TRUE;
    }
    
    /**
     * Método para verificar el tamaño del archivo
     */
    public function isSizeValid($file='') {
        $file = empty($file) ? $this->file : $file;
        $total_bytes = ($this->maxSize) ? $this->_toBytes($this->maxSize) : 0;        
        $file_size = $_FILES[$file]['size'];        
        if($this->maxSize !== NULL && ( $file_size > $total_bytes ) ) {
            $this->setError("No se admiten archivos superiores a $this->maxSize");
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Método para verificar si el tipo de archivo es permitido
     * @param string $file Nombre del archivo cargado     
     */
    public function isAllowedFiletype($file='') {          
        $file = empty($file) ? $_FILES[$this->file] : $_FILES[$file];
        $ext = $this->getExtension($file['name']);
        //Verifico el tipo permitido
        if($this->allowedTypes == '*') {
            return TRUE;
        }        
        if (count($this->allowedTypes) == 0 OR ! is_array($this->allowedTypes)) {
            $this->setError('No se ha especificado los tipos de archivos permitidos en el servidor.');
            return FALSE;
        }           
        
        //Verifico si la extensión está permitida
        if(!in_array($ext, $this->allowedTypes)) {
            $this->setError('El tipo de archivo subido es incorrecto.');
            return FALSE;
        }
        //Verifico si son imágenes        
        $types = array('gif', 'jpg', 'jpeg', 'png', 'jpe');
        if (in_array($ext, $types)) {
            if (!in_array($file['type'], array('image/jpeg' , 'image/pjpeg' , 'image/gif' , 'image/png'))) {
                $this->setError('Solo se admiten imagenes JPEG, PNG y GIF.');
                return FALSE;
            }
            if (getimagesize($file['tmp_name']) === FALSE) {
                $this->setError('Oops! al parecer la imagen no es correcta.');
                return FALSE;
            }
        }        
        return TRUE;        
    }
    
    /**
     * Método para registrar los tipos de archivo disponibles          
     */
    public function setAllowedTypes($types) {
        if ( ! is_array($types) && $types == '*')  {
            $this->allowedTypes = '*';
            return;
        }
        $this->allowedTypes = explode('|', $types);
    }
    
    /**
     * Método para extraer la extension del archivo
     * @return string    
     */
    public function getExtension($filename) {
        $file = explode('.', $filename);        
        $this->file_ext = end($file);
        return $this->file_ext;
    }
    
    /**
     * Método para definir si utiliza un md5 como nombre
     */
    public function setEncryptName($encrypt) {
        $this->encryptName = $encrypt;
    }
    
    /**
     * Método para cambiar el tamaño de la imagen
     * @param numeric $width Ancho de la imagen a reescalar
     * @param numeric $height Alto de la imagen a reescalar
     * @param boolean $removeOld Indica si mantiene la original al reescalar o no. La imagen reescalada mantiene el prefijo min_
     */
    public function setSize($width=0, $height=0, $removeOld=FALSE) {
        if($width >0 && $height > 0) {
            $this->resize = TRUE;
            $this->resizeWidth = $width;
            $this->resizeHeight = $height;
            $this->removeOld = ($removeOld === TRUE) ? TRUE : FALSE;
        }
    }

    /**
     * Método para cargar un error
     * @param type $error
     */
    public function setError($error) {
        $this->_error = $error;
    }
    
    /**
     * Método para obtener el error
     * @return string
     */
    public function getError() {
        return $this->_error;
    }
    
    /**
     * Modo para renombrar el archivo
     */
    protected function _setFileName($rename) {        
        if($this->encryptName) {
            $name = md5(uniqid().time()).'.'.$this->file_ext;
        } else {
            $name = empty($rename) ? $_FILES[$this->file]['name'] : $rename.'.'.$this->file_ext;
        }
        return $name;
    }


    /**
     * Método que devuelve el valor en bytes del archivo
     * @param type $size
     * @return int
     */
    protected function _toBytes($size) {
        if(preg_match('/([KMGTP]?B)/', $size, $matches)) {
            $bytes_array = array('B' => 1, 'KB' => 1024, 'MB' => 1024 * 1024, 'GB' => 1024 * 1024 * 1024, 'TB' => 1024 * 1024 * 1024 * 1024, 'PB' => 1024 * 1024 * 1024 * 1024 * 1024);
            $size = floatval($size) * $bytes_array[$matches[1]];
        }
        return intval(round($size, 2));
    }
}

?>
