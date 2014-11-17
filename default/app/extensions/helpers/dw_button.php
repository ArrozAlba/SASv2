<?php
/**
 * Dailyscript - Web | App | media
 *
 * Extension para el manejo de botones de formularios
 *
 * @category    Helpers
 * @author      Iván D. Meléndez
 * @package     Helpers
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

class DwButton {

    /**
     * Contador de mensajes
     * @var int
     */
    protected static $_counter = 1;

    /**
     * Método para crear un botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @param type $attrs Atributos adicionales
     * @param type $text Texto a mostrar
     * @return type
     */
    public static function save($title='Guardar registro', $icon='save', $attrs=NULL, $text='guardar') {
        if (is_array($attrs) OR empty($attrs)) {
            $attrs['class'] = (empty($attrs['class'])) ? 'btn-success' : 'btn-success '.$attrs['class'];
            $attrs['title'] = $title;
        }
        return self::showButton($icon, $attrs, $text, 'submit');
    }

    /**
     * Método para resetear un formulario
     * @param type $form ID del formulario
     * @param type $formUpdate Indica si el formulario es de modificación o creación
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function reset($form='formulario', $formUpdate=FALSE, $icon='undo') {
        $title = (!$formUpdate) ? 'Limpiar formulario' : 'Retomar valores por defecto';
        $attrs = array();
        $attrs['class'] = 'btn-info';
        $attrs['title'] = $title;
        $attrs['onclick'] = "document.getElementById('$form').reset();";
        return self::showButton($icon, $attrs, 'limpiar', 'button');
    }

    /**
     * Método para cancelar un formulario
     * @param type $redir Página a redirigir al presionar el botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function cancel($redir=NULL, $title='', $icon='ban-circle') {
        $attrs = array();
        $attrs['class'] = 'btn-danger';
        $attrs['title'] = empty($title) ? 'Cancelar operación' : $title;
        if(empty($redir)) {
            $attrs['class'].= ' btn-back';
            return self::showButton($icon, $attrs, 'cancelar', 'button');
        } else {
            return DwHtml::button($redir, 'CANCELAR', $attrs, $icon);
        }
    }

    /**
     * Método para crear un botón para regresar a la página anterior
     * @param type $redir Página a redirigir al presionar el botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function back($redir=NULL, $title='', $icon='backward') {
        $attrs = array();
        $attrs['class'] = 'btn-info';
        $attrs['title'] = empty($title) ? 'Regresar' : $title;
        if(empty($redir)) {
            $attrs['class'].= ' btn-back';
            return self::showButton($icon, $attrs, 'regresar', 'button');
        } else {
            return DwHtml::button($redir, 'REGRESAR', $attrs, $icon);
        }
    }

    /**
     * Método para crear un botón para imprimir reportes
     * @param type $path Ruta del controlador del módulo de reporte
     * @param type $file Tipos de formato de reporte
     * @param type $title (opcional) Titulo del botón
     * @param type $text (opcional) Texto a mostrar en el botón
     * @return type
     */
    public static function report($path, $files='html', $title='', $text='') {
        $path = '/reporte/'.trim($path, '/').'/';
        //Verifico los tipos de archivo para llevar un orden específico
        $types = array();
        //Verifico si tiene el formato de impresora fiscal
        if(preg_match("/\bticket\b/i", $files)) {
            $types[] = 'ticket';
        }
        //Verifico si tiene el formato html
        if(preg_match("/\bhtml\b/i", $files)) {
            $types[] = 'html';
        }
        //Verifico si tiene el formato pdf
        if(preg_match("/\bpdf\b/i", $files)) {
            $types[] = 'pdf';
        }
        //Verifico si tiene el formato xls
        if(preg_match("/\bxls\b/i", $files)) {
            $types[] = 'xls';
        }
        //Verifico si tiene el formato xlsx
        if(preg_match("/\bxlsx\b/i", $files)) {
            $types[] = 'xlsx';
        }
        //Verifico si tiene el formato doc
        if(preg_match("/\bdoc\b/i", $files)) {
            $types[] = 'doc';
        }
        //Verifico si tiene el formato docx
        if(preg_match("/\bdocx\b/i", $files)) {
            $types[] = 'docx';
        }
        //Verifico si tiene el formato xml
        if(preg_match("/\bxml\b/i", $files)) {
            $types[] = 'xml';
        }
        //Verifico si tiene el formato docx
        if(preg_match("/\bcsv\b/i", $files)) {
            $types[] = 'csv';
        }
        //Uno los tipos
        $files = join('|', $types);

        $attrs = array();
        $attrs['class'] = 'btn-info js-report no-load';
        $attrs['title'] = 'Imprimir reporte';
        $attrs['data-report-title'] = (empty($title)) ? 'Imprimir reporte' : $title;
        $attrs['data-report-format'] = $files;
        if(empty($text)) {
            return DwHtml::button($path, '', $attrs, 'print');
        } else {
            return DwHtml::button($path, strtoupper($text), $attrs, 'print');
        }
    }

    /**
     * Método para crear un botón para envío de formularios
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @param type $attrs Atributos adicionales
     * @param type $text Texto a mostrar
     * @return type
     */
    public static function submit($title='Guardar registro', $icon='save', $attrs=NULL, $text='guardar') {
        return self::save($title, $icon, $attrs, $text);
    }


    /**
     * Método que se encarga de crear el botón
     * @param type $icon
     * @param type $attrs
     * @param type $text
     * @param type $type
     * @return type
     */
    public static function showButton($icon='', $attrs = array(), $text='', $type='button') {
        $text = strtoupper($text);
        $attrs['class'] = 'btn '.$attrs['class'];
        if(!preg_match("/\bdw-text-bold\b/i", $attrs['class'])) {
            $attrs['class'] = $attrs['class'].' dw-text-bold';
        }
        $attrs = Tag::getAttrs($attrs);
        $text = (!empty($text) && $icon) ? '<span class="hidden-phone">'.strtoupper($text).'</span>' : strtoupper($text);
        if($icon) {
            $text = '<i class="btn-icon-only icon-'.$icon.'"></i> '.$text;
        }
        return "<button type=\"$type\" $attrs>$text</button>";
    }
}
