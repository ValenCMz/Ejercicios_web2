// Se desea implementar una API REST para exponer los datos de la plataforma a terceros.
// 1. Defina los endpoints necesarios para dar soporte por API REST a las tablas AEROLINEA y
// VUELO. No es necesario implementarlos.
// 2. Siguiendo el patrón MVC implemente la API REST solo para el siguiente requerimiento.
// No implemente los MODELOS. Puede usar la Vista de API REST brindada por la cátedra.
// Listar todos los vuelos y listar uno solo determinado por su ID.
// ACLARACIÓN: No es necesario implementar el router del sistema ni el archivo .htaccess

1. endpoints AEROLINEA

    vol.ar/api/aerolinea GET
    vol.ar/api/aerolinea/id GET de una aerolinea especifica
    vol.ar/api/aerolinea/id DELETE de una aerolinea especifica
    vol.ar/api/aerolinea/id PUT de una aerolinea especifica
    vol.ar/api/aerolinea POST
    
    endpoints VUELO
    vol.ar/api/vuelo GET
    vol.ar/api/vuelo/id GET de una vuelo especifico
    vol.ar/api/vuelo/id DELETE de una vuelo especifico
    vol.ar/api/vuelo/id PUT de una vuelo especifico
    vol.ar/api/vuelo POST
<?php

class apiController{

    private $model;
    private $view;

    function getVuelos(){
        $vuelos = $this->model->getVuelos();
        if(empty($vuelos)){
            return $this->view->responses("No se obtuvieron los vuelos", 404);
        }
        return $this->view->responses($vuelos,200);
    }

    function getVuelosPorId($params = []){
        $id = $params[':ID'];
        $vuelo = $this->model->getVuelo($id);
        if(empty($vuelo)){
            return $this->view->responses("No se obtuvo el vuelo",404);
        }
        return $this->view->responses($vuelo,200);
    }

}
