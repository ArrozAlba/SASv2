<?php

Load::lib('phpmailer/class.phpmailer');
Load::lib('phpmailer/class.smtp');

class Correos {

    protected $_mail = NULL;

    public function __construct() {
        $this->_mail = new PHPMailer();
        $this->_mail->IsSMTP();
        $this->_mail->SMTPAuth = TRUE;
        $this->_mail->SMTPSecure = 'ssl';
        $this->_mail->Host = "smtp.gmail.com";
        $this->_mail->Port = 465;
        $this->_mail->Username = "";//escribir el correo
        $this->_mail->Password = "";//escribir la clave
        $this->_mail->FROM = ""; //escribir el remitente
        $this->_mail->FromName = "Manuel Aguirre";
    }

    /**
     * Envia un correo de registro exitoso al usuario.
     * 
     * @param  Usuarios $usuario 
     * @return boolean        
     */
    public function enviarRegistro(Usuarios $usuario) {
        /*$mensaje = "Felicidades tu cuenta en " . Config::get('config.application.name');
        $mensaje .= " ha sido creada Exitosamente...!!! ";
        $mensaje .= "<ul><li>Usuario: " . h($data['login']) . "</li>";
        $mensaje .= "<li>Contraseña: " . h($data['clave']) . "</li></ul>";
        $mensaje .= "<p>Para activar tu cuenta visita el siguiente link: ";
        $mensaje .= Html::link("registro/activar/{$data['id']}/{$data['hash']}", $data['hash']);

        $this->_mail->Subject = "Tu cuenta ha sido creada con exito - " . Config::get('config.application.name');
        $this->_mail->AltBody = strip_tags($mensaje);
        $this->_mail->MsgHTML($mensaje);
        $this->_mail->IsHTML(TRUE);

        $this->_mail->AddAddress($data['email'], $data['nombres']);
        return $this->_enviar();*/
    }

    protected function _enviar(){
        ob_start();
        $res = $this->_mail->Send();
        ob_clean();
        return $res;
    }

}

