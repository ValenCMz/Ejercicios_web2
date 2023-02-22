<?php
require_once('./Model/encomiendasModel.php');
require_once('./Model/comisionistaModel.php');


class encomiendasController{
    private $comisionistaModel;
    private $encomiendaModel;
    private $view;

    function __construct(){
        $this->comisionistaModel = new comisionistaModel();
        $this->encomiendaModel = new encomiendaModel();
        $this->view = new encomiendasView();
    }

    //Al momento de cargar una nueva encomienda, el sistema debe verificar
    //antes si el comisionista tiene disponibilidad(que no supere la capacidad del vehiculo),
    //en otro caso, el sistema emite un error y la encomienda debe agendarse para otro dia

    //BASE DE DATOS
    //ENCOMIENDA(id_encomienda: int; peso: float; destinatario: string, id_comisionista(FK): int;
    //idTracking: string; estado: int; fecha: date)

    //COMISIONISTA(id_comisionista: int; nombre: string; capacidad_vehiculo: float; 
    //ciudad_destino: string)

    function agregarEncomienda(){
        //asumo que los datos llegan por el metodo POST
        $data = $_POST; 
        //chequeo que lleguen bien los datos
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        //voy a buscar un comisionista disponible a ese peso
        $comisionista = $this->comisionistaModel->getComisionistaDisponiblePorPeso($data['peso']);   
        //chequeo que exista un comisionista con esta condicion
        if(empty($comisionista)){
            return $this->view->error("No hay comisionista que su vehiculo soporte el peso");
        }
        //tengo q ver q el comisionista no tenga una encomienda ese dia
        $encomienda = $this->encomiendaModel->getEncomiendaPorComisionistaYFecha($comisionista->id_comisionista,$data['fecha']);
        //si existe una encomienda ese dia el comisionista esta ocupado
        if(!empty($encomienda)){
            return $this->view->error("El comisionista tiene ocupado este dia, vuelva a ingrear una fecha por favor");
        }
        //genero el id_tracking con uniqid
        $id_tracking = uniqid('idTracking_');
        //la inserto y me traigo el ultimo id insertado
        $seInserto = $this->encomiendaModel->agregarEncomienda($data['peso'], $data['destinatario'], $comisionista->id_comisionista, $id_tracking, $data['estado'], $data['fecha']);
        //si no trae nada es xq  no se inserto
        if(empty($seInserto)){
            return $this->view->error("La encomienda no se pudo insertar");
        }
        return $this->view->existo("La encomienda se inserto con exito" , $id_tracking);
    }

    function getCantEncomiendasEntregadasEnUnaCiudadYFechaDada(){
        //asumo q los datos llegan por post
        $data = $_POST;
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        //voy a buscar todos los comisionistas q tengan como destino la ciudad dada
        $comisionistas = $this->comisionistaModel->getComisionistasPorCiudad($data['ciudad']);
        if(empty($comisionistas)){
            return $this->view->error("No se realizaron entregas en esa ciudad");
        }
        $cantidadEntregas = 0;
        foreach ($comisionistas as $comisionista){
            //se asume q el estado 4 es entregado
            $cantidadEntregas += $this->encomiendaModel->getCantidadEncomiendasPorComisionistaYFecha($comisionista->id_comisionista, $data['fecha'], 4);
        }
        if($cantidadEntregas>0){
            return $this->view->exito("Se encontraron entregas", $cantidadEntregas);
        }
        return $this->view->error("No se encontraron entregas");
    }
}


function agregarEncomiendaVieja(){
    $peso = $_POST['peso'];
        $fecha = $_POST['fecha'];
        if(!empty($peso) && !empty($_POST['destinatario']) && !empty($_POST['estado']) && !empty($fecha)){
            $comisionistas = $this->model->getComisionistasPeso($peso);
            $i = 0;
            $comisionistaDisponible = false;
            while($comisionistaDisponible == false && $i < count($comisionistas)){
                $comisionista = $this->verificarEncomiendaDeComicionistaPorFecha($comisionistas[$i], $fecha);
                if(!empty($comisionista))
                    $comisionistaDisponible = true;
            }
            if(!empty($comisionista)){//si existe
                $id_tracking = uniqid('id_tracking_');
                $id_encomienda = $this->model->agregarEncomienda($peso,$_POST['destinatario'],$comisionista->id_comisionista,$id_tracking,$_POST['estado'],$_POST['fecha']);
                $this->view->mostrarMensaje("Se agrego la $id_encomienda con exito");
            }else{//si no existe
                $this->view->mostrarMensaje("No hay un comisionista disponible para esa fecha, ingrese una nueva fecha");
            }
        }
}

function verificarEncomiendaDeComicionistaPorFecha($comisionista, $fecha){  
    //traigo todas las encomiendas de ese comisionistas
    $encomiendas = $this->model->getEncomiendas($comisionista->id_comisionista, $fecha);
    if(empty($encomiendas)){
            return $comisionista; 
    }

}


function getCantEncomiendasEntregadasVieja($ciudad,$fecha){
    $cantidad = 0;
    $comisionistas = $this->model->getComisionistasPorCiudad($ciudad);
    if(!empty($comisionistas)){
        foreach ($comisionistas as $comisionista) {
            $cantidad += $this->model->getCantEncomiendasPorCiudadYFecha($comisionista->id_comisionista,$fecha,4);//aca asumimos q el etado 4 es entregado
        }
    }
    if($cantidad > 0){
        $this->view->mostrarMensaje("Hay entregas");
        return $cantidad;
    }else{
        $this->view->mostrarMensaje("No se encontraron entregas con esa ciudad de destino");
    }
}