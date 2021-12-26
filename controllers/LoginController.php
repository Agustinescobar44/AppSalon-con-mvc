<?php

namespace Controllers;

use clases\email;
use Model\usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new usuario($_POST);
            
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //ver si el usuario existe
                $usuario = usuario::where('email', $auth->email);
                if($usuario){
                    //comproar password
                    $verificacion = $usuario->comprobarPasswordYVerificado($auth->password);
                    if($verificacion){
                        // Autenticar el usuario
                        if(!$_SESSION) session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " ". $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else{
                            header('Location: /cita');
                        }
                    }
                } else {
                    usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = usuario::getAlertas();
        $router->render('auth/login',[
            'alertas'=> $alertas,

        ]);
    }

    public static function logout(){
        if(!isset($_SESSION))session_start();
        
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $auth = new usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado ==='1'){
                    //generar el token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // TODO: enviar email
                    $email = new email($usuario->email, $usuario->nombre , $usuario->token);
                    
                    $email->enviarRecuperacion();

                    usuario::setAlerta('exito', 'Recuperacion enviada al email');

                } else {
                    usuario::setAlerta('error', 'no existe o no esta confirmado');
                }   
            }
        }
        $alertas = usuario::getAlertas();
        $router->render('auth/olvide',[
            'alertas' => $alertas,

        ]);
    }
    
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        $usuario = usuario::where('token' , $token);

        if(!$usuario){
            usuario::setAlerta('error','Token no valido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $password = new usuario($_POST);
            $alertas = $password->validarPassword();
            if(empty($alertas)){
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado) header('Location: /');
            }
        }
        
        $alertas = usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        
        $usuario = new usuario();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarDatos();

            if(empty($alertas)){
                // Verificar que el usario no este registrado
                $resultado= $usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas = usuario::getAlertas();
                } else {
                    //hashear password
                    $usuario->hashPassword();

                    //generar el token unico
                    $usuario->crearToken();
                    // Enviar el email
                    $email = new email($usuario->email, $usuario->nombre , $usuario->token);
                    
                    $email->enviarConfirmacion();

                    // Crear el usuario 
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'usuario' => $usuario,
            'alertas' => $alertas,

        ]);
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = usuario::where('token' , $token);

        if(empty($usuario)){
            usuario::setAlerta('error','Token no valido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = "";
            $usuario->guardar();
            usuario::setAlerta('exito', 'Cuenta creada correctamente');
        }

        $alertas = usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas,

        ]);
    }

    public static function mensaje(Router $router){
        
        $router->render('auth/mensaje', []);
    }
}
