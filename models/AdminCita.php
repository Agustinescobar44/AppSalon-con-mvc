<?php

namespace Model;

class AdminCita extends ActiveRecord{

    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id','hora','nombreUsuario' , 'email' , 'telefono' , 'nombreServicio' , 'precio'];

    public $id;
    public $hora;
    public $nombreUsuario;
    public $email;
    public $telefono;
    public $nombreServicio;
    public $precio;


    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->hora = $args['hora'] ?? "";
        $this->nombreUsuario = $args['nombreUsuario'] ?? "";
        $this->email = $args['email'] ?? "";
        $this->telefono = $args['telefono'] ?? "";
        $this->nombreServicio = $args['nombreServicio'] ?? "";
        $this->precio = $args['precio'] ?? "";
    }

}