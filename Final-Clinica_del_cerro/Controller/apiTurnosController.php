<?php

class apiTurnosController
{
    private $turnosModel;
    private $profesionalesModel;

    //inicializo

        function getTurnos($params = [])
        {
            $fecha = $params['fecha'];
            $turnos = $this->turnosModel->getTurnosPorFecha($fecha);
            if (empty($turnos)) {
                return $this->view->responses("No se encontraron turnos", 404);
            }
            $turnosToReturn = array();
            foreach ($turnos as $turno) {
                $profesional = $this->profesionalesModel->getProfesional($turno->id_profesional_fk);
                if (!empty($profesional)) {
                    $info = array(
                        "turno" => $turno,
                        "profesional" => $profesional
                    );
                }
                array_push($turnosToReturn, $info);
            }
            if (empty($turnosToReturn)) {
                return $this->view->responses("No se encontraron turnos", 404);
            }
            return $this->view->responses($turnosToReturn, 200);

        }
}