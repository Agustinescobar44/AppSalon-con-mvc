<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function admin(Router $router){
        if(!isset($_SESSION)) session_start();

        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        $fechaArreglo= explode('-', $fecha);

        if(!checkdate($fechaArreglo[1], $fechaArreglo[2], $fechaArreglo[0])){
            header('Location: /404');
        }        

        // Consultar la base de datos
        $query = "SELECT citas.id, citas.hora ,CONCAT( usuarios.nombre , ' ', usuarios.apellido ) as nombreUsuario, ";
        $query .= " usuarios.email, usuarios.telefono, servicios.nombre as nombreServicio, servicios.precio ";
        $query .= " FROM citas ";
        $query .= " LEFT OUTER JOIN usuarios ";
        $query .= " ON citas.usuarioId = usuarios.id ";
        $query .= " LEFT OUTER JOIN citasservicios ";
        $query .= " ON citasservicios.citaId = citas.id ";
        $query .= " LEFT OUTER JOIN servicios ";
        $query .= " ON servicios.id = citasservicios.servicioId ";
        $query .= " WHERE fecha = '${fecha}' ";

        $citas = AdminCita::SQL($query);
        
        $router->render('admin/index' , [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}