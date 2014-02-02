<?php

/**
 * Backend - KumbiaPHP Backend
 * PHP version 5
 * LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Libs
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
 */
class DataTable {

    /**
     * Titulos del las columnas de la tabla
     *
     * @var array
     */
    protected $_cabeceras = array();
    /**
     * Campos a mostrar de la tabla
     *
     * @var array
     *
     * <code>
     *  array(
     *      'field' => 'id',
     *      'boolean_field' => 'activo',
     *      'options' => array(
     *          0 => '<a href="/usuarios/editar/%s">Editar</a>'
     *      )
     * )
     * </code>
     */
    protected $_campos = array();
    /**
     * Url para el paginador
     *
     * @var string
     */
    protected $_url = NULL;
    /**
     * Array de modelos AR
     *
     * @var array ActiveRecord
     */
    protected $_model = NULL;
    /**
     * Paginador si se usa
     *
     * @var Paginator
     */
    protected $_paginator = NULL;
    /**
     * Tipo de paginador a usar
     *
     * @var string
     */
    protected $_type_paginator = NULL;
    /**
     * Indica si se va a crear una tabla con los campos por defecto del modelo
     *
     * @var boolean
     */
    protected $_use_default_fields = TRUE;
    /**
     * Nombre del campo con clave primaria del modelo
     *
     * @var string
     */
    protected $_primary_key = 'id';

    /**
     * Constructor de la Clase
     *
     * @param array $model Resultado de una consulta ActiveRecord
     */
    public function __construct($model) {
        if (isset($model->items)) {
            $this->_paginator = $model;
            $model = $model->items;
        }
        if (sizeof($model)) {
            $this->_primary_key = current($model)->primary_key[0];
        }
        $this->_model = $model;
    }

    /**
     * Establece|añade los nombres de las cabeceras de las columnas de la tabla
     *
     *
     * Ejemplo:
     *
     * <code>
     *  $obj->addHeaders("nombres","apellidos","cedula");
     *  $obj->addHeaders(array("direccion","telefono"));
     * </code>
     *
     */
    public function addHeaders() {
        $params = Util::getParams(func_get_args());
        if (isset($params[0]) && is_array($params[0])) {
            $params = $params[0];
        }
        $this->_cabeceras = array_merge($this->_cabeceras, $params);
    }

    /**
     * Establece|añade los campos del modelo a mostrar en la tabla
     *
     *
     * Ejemplo:
     *
     * <code>
     *  $obj->addFields("nombres","apellidos","cedula");
     *  $obj->addFields("nombres","apellidos","activo: Activo|Inactivo");
     *  $obj->addFields('nombres: tu nombre es : %s','apellidos');
     * </code>
     *
     *
     */
    public function addFields() {
        $params = Util::getParams(func_get_args());
        if (isset($params[0]) && is_array($params[0])) { //aqui solo debe entrar si son links, imagenes, etc
            $this->_campos = array_merge($this->_campos, $params);
        } else {
            foreach ($params as $field => $options) {
                if (is_numeric($field)) {
                    $data['field'] = $options;
                    $data['boolean_field'] = FALSE;
                    $data['options'] = array('%s');
                } else {
                    $data['field'] = $field;
                    $options = $options = explode('|', $options);
                    if (count($options) > 1) {
                        $data['boolean_field'] = $data['field'];
                    } else {
                        $data['boolean_field'] = FALSE;
                    }
                    $data['options'] = $options;
                }
                $this->_campos[] = $data;
                $this->_use_default_fields = FALSE;
            }
        }
    }

    /**
     * Genera una tabla con los datos del modelo, y muestra la informacion
     * previamente establecida con los metodos headers, fields, etc...
     *
     * @param string $attrs atributos opcionales para la tabla (opcional)
     * @return string tabla generada
     */
    public function render($attrs = NULL) {
        $model = $this->_model;
        if ($this->_use_default_fields) {
            $this->_getTableSchema($model);
        }
        $table = "<table $attrs>";
//      head de la tabla
        $table .= '<thead>';
        $table .= '<tr style="text-align:center;font-weight:bold;">';
        foreach ($this->_cabeceras as $e) {
            $table .= "<th>$e</th>";
        }
        $table .= '</tr>';
        $table .= '</thead>';
//       foot de la tabla
        if ($this->_paginator && $this->_type_paginator !== FALSE) {
            $table .= '   <tfoot><tr><th colspan="100">';
            $table .= $this->_paginator();
            $table .= '</th></tr></tfoot>';
        } else {
            $table .= '   <tfoot><tr><th colspan="100">';
            $table .= '<span style="float:right;margin-right:20px;"><b>Total registros: ' . count($model) . '</b></span>';
            $table .= '</th></tr></tfoot>';
        }
//      body de la tabla
        $table .= '<tbody>';
        if (sizeof($model)) {
            foreach ($model as $model) {
                $table .= '<tr>';
                foreach ($this->_campos as $field) {
                    if (method_exists($model, $field['field'])) { //si es un metodo lo llamamos
                        $value = h($model->$field['field']());
                    } else {
                        $value = h($model->$field['field']);
                    }
                    if ($field['boolean_field']) {
                        $option = $field['options'][$model->$field['boolean_field']];
                    } else {
                        $option = $field['options'][0];
                    }
                    $table .= '<td>' . vsprintf($option, array($value, $value, $value)) . '</td>';
                }
            }
            $table .= '</tr>';
        } else {
            $table .= '<tr><td colspan="100">La Consulta no Arrojó Ningun Registro</td></tr>';
        }
        $table .= '</tbody>';
        $table .= "</table>";
        return $table;
    }

    /**
     *  agrega un check a la tabla
     *
     * @param string $field_name nombre del check
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la si se muestra ó no el check (opcional)
     */
    public function check($field_name, $boolean_field = NULL) {
        $this->addFields(array(
            'field' => $this->_primary_key,
            'boolean_field' => $boolean_field,
            'options' => array(Form::check("$field_name.%s", '%s'), NULL)
        ));
    }

    /**
     * Crea un link en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar en el link separados por |
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public function link($action, $text, $boolean_field = FALSE) {
        $action = explode('|', $action);
        $text = explode('|', $text);
        isset($action[1]) || $action[1] = $action[0];
        isset($text[1]) || $text[1] = $text[0];
        $this->addFields(array(
            'field' => $this->_primary_key,
            'boolean_field' => $boolean_field,
            'options' => array(
                Html::link("$action[0]/%s", $text[0]),
                Html::link("$action[1]/%s", $text[1]),
            )
        ));
    }

    /**
     * Crea un link con una imagen en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $url_img direccion ó direcciones donde se encuentra la imagen separados por |
     * @param string $text texto ó textos a mostrar al lado de la imagen separados por | (opcional)
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public function img($url_img, $text = NULL, $boolean_field = FALSE) {
        $url_img = explode('|', $url_img);
        $text = explode('|', $text);
        isset($action[1]) || $action[1] = $action[0];
        isset($text[1]) || $text[1] = $text[0];
        $this->addFields(array(
            'field' => $this->_primary_key,
            'boolean_field' => $boolean_field,
            'options' => array(
                Html::img($action[0], $text[0]),
                Html::img($action[1], $text[1]),
            )
        ));
    }

    /**
     * Crea un link con una imagen en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $url_img direccion ó direcciones donde se encuentra la imagen separados por |
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar al lado de la imagen separados por | (opcional)
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public function imgLink($url_img, $action, $text = NULL, $boolean_field = NULL) {
        $url_img = explode('|', $url_img);
        $action = explode('|', $action);
        $text = explode('|', $text);
        isset($url_img[1]) || $url_img[1] = $url_img[0];
        isset($action[1]) || $action[1] = $action[0];
        isset($text[1]) || $text[1] = $text[0];
        $this->addFields(array(
            'field' => $this->_primary_key,
            'boolean_field' => $boolean_field,
            'options' => array(
                Html::link("$action[0]/%s", Html::img($url_img[0]) . " $text[0]"),
                Html::link("$action[1]/%s", Html::img($url_img[1]) . " $text[1]"),
            )
        ));
    }

    /**
     * Crea un link en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar en el link separados por |
     * @param string $confirm pregunta/s a mostrar en el link separados por |
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public function linkConfirm($action, $text, $confirm = '¿Esta Seguro?', $boolean_field = FALSE) {
        $action = explode('|', $action);
        $text = explode('|', $text);
        $confirm = explode('|', $confirm);
        isset($action[1]) || $action[1] = $action[0];
        isset($text[1]) || $text[1] = $text[0];
        $this->addFields(array(
            'field' => $this->_primary_key,
            'boolean_field' => $boolean_field,
            'options' => array(
                Js::link("$action[0]/%s", $text[0], $confirm[0]),
                Js::link("$action[1]/%s", $text[1], $confirm[1]),
            )
        ));
    }

    /**
     * Crea un link con una imagen en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $url_img direccion ó direcciones donde se encuentra la imagen separados por |
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar al lado de la imagen separados por | (opcional)
     * @param string $confirm pregunta/s a mostrar en el link separados por |
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public function imgLinkConfirm($url_img, $action, $text = NULL, $confirm = '¿Esta Seguro?', $boolean_field = NULL) {
        $url_img = explode('|', $url_img);
        $action = explode('|', $action);
        $text = explode('|', $text);
        $confirm = explode('|', $confirm);
        isset($url_img[1]) || $url_img[1] = $url_img[0];
        isset($action[1]) || $action[1] = $action[0];
        isset($text[1]) || $text[1] = $text[0];
        isset($confirm[1]) || $confirm[1] = $confirm[0];
        $this->addFields(array(
            'field' => $this->_primary_key,
            'boolean_field' => $boolean_field,
            'options' => array(
                Js::link("$action[0]/%s", Html::img($url_img[0]) . " $text[0]", $confirm[0]),
                Js::link("$action[1]/%s", Html::img($url_img[1]) . " $text[1]", $confirm[1]),
            )
        ));
    }

    /**
     * Establece la url para el paginador, si no se estable usa el
     * modulo/controlador/accion actual.
     *
     * Ejemplo:
     *
     * <code>
     *      $obj->url('usuarios/index');
     * </code>
     *
     * @param string $url
     */
    public function url($url) {
        $this->_url = "$url/";
    }

    /**
     * Establece el paginador de kumbia a utilizar en la tabla,
     * si no se estable utiliza uno interno del helper
     *
     * @param string $paginator
     */
    public function typePaginator($paginator) {
        $this->_type_paginator = $paginator;
    }

    protected function _paginator() {
        if (!$this->_url) {
            if (Router::get('module'))
                $this->_url = Router::get('module') . '/' . Router::get('controller') . '/' . Router::get('action') . '/';
            else
                $this->_url = Router::get('controller') . '/' . Router::get('action') . '/';
        }
        if (!$this->_type_paginator) {
            $html = '<div class="paginador-tabla">';
            if ($this->_paginator->count > $this->_paginator->per_page) {
                if ($this->_paginator->prev) {
                    $html .= Html::link($this->_url . $this->_paginator->prev, 'Anterior', 'title="Ir a la p&aacute;g. anterior"');
                    $html .= '&nbsp;&nbsp;';
                }
                for ($x = 1; $x <= $this->_paginator->total; ++$x) {
                    $html .= $this->_paginator->current == $x ? '<b>' . $x . '</b>' : Html::link($this->_url . $x, $x);
                    $html .= '&nbsp;&nbsp;';
                }
                if ($this->_paginator->next) {
                    $html .= Html::link($this->_url . $this->_paginator->next, 'Siguiente', 'title="Ir a la p&aacute;g. siguiente"');
                }
            }
            $html .= '<span style="float:right;margin-right:20px;"><b>Total registros: ' . $this->_paginator->count . '</b></span></div>';
            return $html;
        } else {
            $parametros = array(
                'page' => $this->_paginator,
                'url' => substr($this->_url, 0, strlen($this->_url) - 1)
            );
            ob_start();
            KumbiaView::partial('paginators/' . $this->_type_paginator, false, $parametros);
            $paginador = ob_get_contents();
            ob_get_clean();
            return $paginador;
        }
    }

    /**
     * Indica los nombres de las columnas y los campos a mostrar por defecto
     * del Modelo si no se especifican ningunos en ninun momento
     *
     * @param ActiveRecord $model modelo del que se hará la lista
     */
    protected function _getTableSchema($model) {
        if ($model) {
            $temp_campos = $this->_campos;
            $temp_cabeceras = $this->_cabeceras;
            $this->_campos = array();
            $this->_cabeceras = array();
            call_user_func_array(array($this, 'addFields'), current($model)->fields);
            call_user_func_array(array($this, 'addHeaders'), current($model)->alias);
            $this->_campos = array_merge($this->_campos, $temp_campos);
            $this->_cabeceras = array_merge($this->_cabeceras, $temp_cabeceras);
        }
    }

}