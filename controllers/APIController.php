<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }
    public static function guardar(){
        //almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $idCita = $resultado['id'];

        //crear el arreglo con los id de los servicios
        $idServicios = explode(',',$_POST['servicios']); //explode es igual al split de javascript

        //almacena cada servicio en su propia row 
        foreach ($idServicios as $idServicio ) {
            $args = [
                'citaId' => $idCita,
                'servicioId' => $idServicio,
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }


        echo json_encode(['resultado' => $resultado]); //aca pasamos el resultado como un tipo json
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location: '. $_SERVER['HTTP_REFERER']);
        }

    }
}