<?php
require_once('./Model/encomiendasModel.php');

class encomiendasController{
    private $model;
    private $view;

    function __construct(){
        $this->model = new encomiendasModel();
        $this->view = new encomiendasView();
    }

    function agregarEncomienda(){
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

    //Consultar la cantidad de encomiendas entregadas en una ciudad dada en una fecha dada
    function getCantEncomiendasEntregadas($ciudad,$fecha){
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
}