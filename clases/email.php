<?php

namespace clases;

use PHPMailer\PHPMailer\PHPMailer;

class email{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email , $nombre , $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        // Crear el objeto de mail
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '26ac97a2c1d1af';
        $mail->Password = '9eb2a7626f01c5';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon');
        $mail->Subject='confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido= '<html>';
        $contenido .= '<p><strong>Hola '.$this->nombre.' </strong> Has creado tu cuenta en appSalon, solo debes confirmarla presionando el siguiente enlace</p>';
        $contenido .= "<p>Presiona aquí para confirmar: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= '<p> Si tu no solicitaste esta cuenta, puedes desestimar este mensaje</p>';
        $contenido .='</html>';
        $mail ->Body = $contenido;
        //enviar el email
        $mail->send();
    }

    public function enviarRecuperacion(){

        // Crear el objeto de mail
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '26ac97a2c1d1af';
        $mail->Password = '9eb2a7626f01c5';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon');
        $mail->Subject='Recuperacion de password';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido= '<html>';
        $contenido .= '<p><strong>Hola '.$this->nombre.' </strong> Parece que has solicitado reestablecer tu password.</p>';
        $contenido .= "<p>Presiona aquí para crear un nuevo password: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= '<p> Si tu no solicitaste este cambio, puedes desestimar este mensaje</p>';
        $contenido .='</html>';
        $mail ->Body = $contenido;
        //enviar el email
        $mail->send();
    }
}