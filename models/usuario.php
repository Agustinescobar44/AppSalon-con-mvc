<?php

namespace Model;

class usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? "";
        $this->apellido = $args['apellido'] ?? "";
        $this->email = $args['email'] ?? "";
        $this->password = $args['password'] ?? "";
        $this->telefono = $args['telefono'] ?? "";
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['admin'] ?? 0;
        $this->token = $args['token'] ?? "";
        
    }

    // errores al crear la cuenta
    public function validarDatos(){
        if(!$this->nombre){
            self::$alertas['error'][] = "La cuenta debe tener nombre!";
        }
        if(!$this->apellido){
            self::$alertas['error'][] = "La cuenta debe tener apellido!";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if(!$this->telefono ){
            self::$alertas['error'][] = "El telefono es obligatorio";
        }
        else if(strlen($this->telefono) < 10){
            self::$alertas['error'][]= "Numero de telefono invalido";
        }
        if(!$this->password){
            self::$alertas['error'][] = "El password es obligatorio";
        }
        else if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = "El password es demasiado corto";
        }
        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "Se requiere un email";
        }
        if(!$this->password){
            self::$alertas['error'][] = "Se requiere un password";
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = "Se requiere un email";
        }
        
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password) self::$alertas['error'][] = 'el password es obligatorio';

        if(strlen($this->password)<6) self::$alertas['error'][] = 'el password es demasiado corto!';

        return self::$alertas;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM ". self::$tabla ." WHERE email='" . $this->email."' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }
        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password , PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordYVerificado($password){
        //password verify toma (el password ingresado, el password que esta almacenado en el objeto)
        $ret = password_verify($password, $this->password) && $this->confirmado == '1';

        if(!$ret) self::$alertas['error'][] = 'usuario no confirmado o password incorrecto';

        return $ret;
    }
}