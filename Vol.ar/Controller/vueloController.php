<?php
require_once('./Model/vueloModel.php');
require_once('./Model/pasajeModel.php');


// AEROLINEA(id: int, nombre: string)
//     VUELO(id: int, origen: string, destino: string, fecha: string, estado: string, capacidad: int, internacional: bool,
//     id_aerolinea: int)
//     Además, nos brinda una tabla donde se almacena la información de pasajes vendidos.
//     PASAJE(id: int, fecha_venta: string, clase: int, equipaje: int, id_vuelo: int, id_usuario: int)
//     Donde clase es un número entre el 1 y el 3, y equipaje es el peso en kilos.

class vueloController{
    private $vueloModel;
    private $pasajeModel;
    private $view;

    function __construct(){
        $this->vueloModel = new vueloModel();
        $this->pasajeModel = new pasajeModel(); 
        $this->view = new view();
    }

    function updateFechaVuelo(){
        $data = $_POST;//asumo q los datos llegan por post
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        $seActualizo = $this->vueloModel->updateFechaVuelo($data['id'],$data['fecha']);
        if(empty($seActualizo)) 
            return $this->view->error("No se pudo actualizar la fecha del vuelo con exito");
        return $this->view->exito("Se actualizo el vuelo con exito");
    }

    function eliminarVuelosSinVentas(){
        if(!$this->authHelper->estaLogueado()){
            return $this->view->error("El usuario no esta logueado");
        }
        //voy a buscar todos los vuelos 
        $vuelos = $this->vueloModel->getVuelos();
        if(empty($vuelos)){
            return $this->view->error("No hay vuelos");
        }

        $eliminados = 0;
        //recorro todos los vuelos
        foreach($vuelos as $vuelo){
            $cantidad = $this->pasajeModel->getCantidadDePasajes($vuelo->id);
            if(count($cantidad) == 0){
                $seElimino = $this->vueloModel->deleteVuelo($vuelo->id);
                //esto es mas que nada para comprobar mas adelante q algo se elimino y hacerlo mas generico
                if(!empty($seElimino)){
                    $eliminados++;
                }
            }
        }
        if($eliminados > 0){
            $this->view->exito("Se eliminaron $eliminados vuelos sin ventas de pasajes");
        }
    }

// -Se debe verificar que el vuelo tenga disponibilidad suficiente para la solicitud.
//como se si el vuelo tiene disponibilidad?
    //yo quiero comprar 3 pasajes para un vuelo
        //voy a buscar la cantidad de pasajes para ese vuelo
        //voy a buscar ese vuelo en particular
        //si resto la capacidad - la cantidad de pasajes vendidos
            //y este nuemero es mayor que la cantidad de pasajes que quiero comprar
                //lo compro
    function comprarPasajes(){
        if(!$this->authHelper->estaLogueado()){
            return $this->view->error("El usuario no esta logueado");
        }

        $id_user = $this->authHelper->getIdUser();
        if(empty($id_user)){
            return $this->view->error("No se pudo obtener el id del usuario");
        }

        $data = $_POST;
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }

        $cantidad_pasajes_a_comprar = $data['cantidad'];
        //voy a buscar los pasajes vendidos para ese vuelo
        $cantidad_pasajes_vendidos = $this->pasajeModel->getCantidadDePasajes($data['id_vuelo']);
        //si no hay pasajes vendidos para este vuelo, significa que hay lugar en el vuelo
        if(empty($cantidad_pasajes_vendidos)){
            //voy a comprar la cantidad de pasajes, 1 por persona
            for ($i=0; $i < $cantidad_pasajes_a_comprar; $i++) { 
                $seComproExito = $this->pasajeModel->insertarPasaje($data['fecha_venta'],$data['clase'],$data['equipaje'],$data['id_vuelo'],$id_user);
            }
        }
        //voy a buscar el vuelo 
        $vuelo = $this->vueloModel->getVuelo($data['id_vuelo']);
        if(empty($vuelo)){
            return $this->view->error("Vuelo no disponible");
        }

        if($cantidad_pasajes_a_comprar < $vuelo->cantidad){
            //con esto consigo la cantidad de pasajes disponibles
            $diferencia = $vuelo->capacidad - count($cantidad_pasajes_vendidos);
            //si alcanzan para la cantidad de personas q quieren comprar
            if($diferencia > $cantidad_pasajes_a_comprar){
                //los compro
                //voy a comprar la cantidad de pasajes, 1 por persona
                for ($i=0; $i < $cantidad_pasajes_a_comprar; $i++) { 
                    $seComproExito = $this->pasajeModel->insertarPasaje($data['fecha_venta'],$data['clase'],$data['equipaje'],$data['id_vuelo'],$id_user);
                }
            }
        }
       
        if($seComproExito == $cantidad_pasajes_a_comprar){
            return $this->view->exito("Se compraron todos los pasajes con exito");
        }
        return $this->view->error("No habia pasajes disponibles");
    }

    //HACER ESTO ES COMO HACER UN DOBLE FILTRO
    function buscarVuelos(){
        $data = $_POST;
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        $vuelos = $this->vueloModel->getVuelos();
        if(empty($vuelos)){
            return $this->view->error("No hay vuelos disponibles");
        }
        $vuelosNacionales = array();
        foreach($vuelos as $vuelo){
            if($vuelo->internacional != true){
                array_push($vuelosNacionales, $vuelo);
            }
        }
        if(empty($vuelosNacionales)){
            return $this->view->error("No hay vuelos nacionales");
        }
        $vuelosToReturn = array();
        foreach ($vuelosNacionales as $vueloNacional) {
            $pasajeroPrimeraClase = false;
            $pasajes = $this->pasajeModel->getPasajesPorVuelo($vueloNacional->id);
            foreach ($pasajes as $pasaje) {
                if($pasaje->clase == 3){
                    $pasajeroPrimeraClase = true;
                    break;
                }
            }
            //TENER MUY EN CUENTA ALGO ASI
            $infoVuelo = array(
                'vuelo' => $vueloNacional,
                'tiene_pasajero_primera_clase' => $pasajeroPrimeraClase
            );
            array_push($vuelosToReturn, $infoVuelo);
        }
        if(!empty($vuelosToReturn)){
            return $this->view->exito($vuelosToReturn);
        }
    }


    
    
// AEROLINEA(id: int, nombre: string)
//     VUELO(id: int, origen: string, destino: string, fecha: string, estado: string, capacidad: int,
// internacional: bool,  id_aerolinea: int)
//     Además, nos brinda una tabla donde se almacena la información de pasajes vendidos.
//     PASAJE(id: int, fecha_venta: string, clase: int, equipaje: int, id_vuelo: int, id_usuario: int)
//     Donde clase es un número entre el 1 y el 3, y equipaje es el peso en kilos.

}    

