<?php
/**
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 * */
// @see Controller nuevo controller
require_once CORE_PATH . 'kumbia/controller.php';

class AdminController extends Controller
{

    /**
     * variable que indica si las acciones del controller son protegidas
     * 
     * Por defecto todas las acciones son protegidas
     * para indicar que solo algunas acciones van a ser protegidas debe
     * crearse un array con los nombres de dichas acciones, ejemplo:
     * 
     * <code>
     * 
     * protected $_protected_actions = array(
     *                          'ultimos_envios',
     *                          'editar',
     *                          'eliminar',
     *                          'activar',
     *                      );
     * 
     * </code>
     * 
     * @va boolean|array
     */ 
    protected $_protectedActions = TRUE;

    /**
     * variable que indica si por defecto se hace el chequeo de la autenticación
     * ó si lo hace el usuario manualmente.
     *
     * @var boolean
     **/
    protected $_checkAuthByDefault = TRUE;

    /**
     * Función que hace las veces de contructor de la clase.
     * 
     */ 
    protected function initialize()
    {
        if ( $this->_checkAuthByDefault ){
            if ( $this->_protectedActions === TRUE    || ( is_array($this->_protectedActions) &&
                 in_array($this->action_name , $this->_protectedActions) ) ){  
                return $this->checkAuth();          
            }
        }
    }

    /**
     * Función que hace todos las validaciones necesarias para controladores
     * y acciones protegidas.
     * 
     * Verifica que el usuario esté logueado, si no es así le muestra el form de 
     * logueo.
     * 
     * si está logueado verifica que tenga los permisos necesarios para acceder
     * a la acción correspondiente.
     * 
     * @return boolean devuelve TRUE si tiene acceso a la acción.
     * 
     */ 
    protected function checkAuth(){
        if (MyAuth::es_valido()) {
            return $this->_tienePermiso();
        } elseif (Input::hasPost('login') && Input::hasPost('clave')) {
            return $this->_logueoValido(Input::post('login'), Input::post('clave'));
        } elseif (MyAuth::cookiesActivas()) {
            $data = MyAuth::getCookies();
            return $this->_logueoValido($data['login'], $data['clave'], FALSE);
        } else {
            View::select(NULL, 'backend/logueo');
            return FALSE;
        }

    }

    /**
     * Verifica si el usuario conectado tiene acceso a la acción actual
     * 
     * @return boolean devuelve TRUE si tiene acceso a la acción.
     */
    protected function _tienePermiso()
    {
        View::template('backend/backend');
        $acl = new MyAcl();
        if (!$acl->check()) {
            if ($acl->limiteDeIntentosPasado()) {
                $acl->resetearIntentos();
                return $this->intentos_pasados();
            }
            Flash::error('no posees privilegios para acceder a <b>' . Router::get('route') . '</b>');
            View::select(NULL);
            return FALSE;
        } else {
            $acl->resetearIntentos();
            return TRUE;
        }
    }

    /**
     * Realiza la autenticacón con los datos enviados por formulario
     * 
     * Si se realiza el logueo correctamente, se verifica que tenga permisos
     * para entrar al recurso actual.
     * 
     * @return boolean devuelve TRUE si se pudo loguear y tiene acceso a la acción.
     * 
     */ 
    protected function _logueoValido($user, $pass, $encriptar = TRUE)
    {
        if (MyAuth::autenticar($user, $pass, $encriptar)) {
            Flash::info('Bienvenido al Sistema <b>' . h(Auth::get('nombres')) . '</b>');
            return $this->_tienePermiso();
        } else {
            Input::delete();
            Flash::warning('Datos de Acceso invalidos');
            View::select(NULL, 'backend/logueo');
            return FALSE;
        }
    }

    /**
     * Acción para cerrar sesión en la app
     * 
     * Cualquier controlador que herede de esta clase
     * tiene acceso a esta acción.
     * 
     */ 
    public function logout()
    {
        MyAuth::cerrar_sesion();
        return Router::redirect('/');
    }

    /**
     * Metodo que desloguea al usuario cuando esté sobrepasa el limite de 
     * intentos de acceder a un recurso al que no tiene permisos.
     * 
     */ 
    protected function intentos_pasados()
    {
        Flash::warning('Has Sobrepasado el limite de intentos fallidos al tratar acceder a ciertas partes del sistema');
        return $this->logout();
    }

    /**
     * Método que se ejecuta luego de ejecutada la acción y filtros 
     * del controlador.
     * 
     */ 
    final protected function finalize()
    {

    }

}