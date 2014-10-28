<?php
 
class IndexController extends BackendController {
 
    //accion con la vista del input autocomplete
    public function index() {
 
    }
 
    //accion que busca en los estados y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $estados = Load::model('params/estado')->obtener_estados($busqueda);
            die(json_encode($estados)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }
}
?>
