<?php

if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') { //seguridad
    header('HTTP/1.0 403 Forbidden');
    exit('No tienes permisos para acceder a esta dirección...!!!');
}

/**
 * Description of instalacion
 *
 * @author maguirre
 */
class Instalacion
{

    protected static $_tablas_necesarias = array(
        'usuarios','auditorias','roles','recursos',
        'roles_recursos','roles_usuarios','menus');

    public function __construct()
    {
        Config::read('config');
        Config::read('databases');
    }

    public function entornosConexion()
    {
        return array_keys(Config::get('databases'));
    }

    public function entorno($pos_entorno = 0)
    {
        $entornos = $this->entornosConexion();

        return $entornos[$pos_entorno];
    }

    public function configuracionEntorno($pos_entorno)
    {
        return Config::get("databases.{$this->entorno($pos_entorno)}");
    }

    public function guardarDatabases($pos_entorno, $data)
    {
        $data = $data + array('charset' => 'utf8');
        Config::set("databases.{$this->entorno($pos_entorno)}", $data);
        return MyConfig::save('databases');
    }

    public function obtenerConfig()
    {
        return Configuracion::leer();
    }

    public function guardarConfig($data)
    {
        foreach ($data as $variable => $valor) {
            Configuracion::set($variable, $valor);
        }
        return Configuracion::guardar();
    }

    public function listarTablasExistentes()
    {
        $tablas_existentes = array();
        foreach ((array) Db::factory()->list_tables() as $t) {
            $tablas_existentes[] = $t[0];
        }
        return $tablas_existentes;
    }

    public function verificarConexion()
    {
        try {
            ob_start();
            $con = Db::factory();
            ob_clean();
        } catch (KumbiaException $e) {
            ob_clean();
            Flash::info('No se Pudo Conectar a la Base de datos');
            Flash::error($e->getMessage());
            return FALSE;
        }
        return TRUE;
    }

    public function existeArchivoSql($driver){
        return in_array($driver, array('mysql','mysqli','pgsql'));
    }

    public static function tablasNecesarias()
    {
        return self::$_tablas_necesarias;
    }

    public static function esTablaNecesaria($tabla)
    {
        return in_array($tabla, self::tablasNecesarias());
    }

    /**
     * Verifica que la bd y las tablas necesarias esten instaladas
     * 
     * @param array $tablas_existentes 
     */ 
    public static function instalacionBDCorrecta($tablas_existentes){
        $numTablasNecesarias = count(self::tablasNecesarias());
        $necesariasCreadas = 0;
        foreach($tablas_existentes as $e){
            if ( self::esTablaNecesaria($e) ){
                ++$necesariasCreadas;
            }
        }
        return $necesariasCreadas === $numTablasNecesarias;
    }

}