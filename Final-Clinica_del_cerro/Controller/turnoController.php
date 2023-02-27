<?php

class turnoController
{
    private $profesionalModel;
    private $turnoModel;

    function __construct()
    {
        $this->profesionalModel = new profesionalModel();
        $this->turnoModel = new turnoModel();
    }

    function agregarTurno()
    {
        if (!$this->authHelper->estaLogueado()) {
            return $this->view->error("No esta logueado");
        }
        $data = $_POST;
        if (empty($data)) {
            return $this->view->error("Hubo un problema con los datos");
        }
        $turnos = $this->turnoModel->getTurnosPorProfesional($data['id_profesional_fk']);
        if (empty($turnos)) {
            $seAgrego = $this->turnoModel->agregarTurno($data['fecha'], $data['dni_paciente'], $data['id_profesional']);
        }
        $turno = $this->turnoModel->getTurnoPorFechaYProfesional($data['fecha'], $data['id_profesional_fk']);
        if (!empty($turno)) {
            return $this->view->error("Ya hay un turno agendado para ese dia");
        }
        $seAgrego = $this->turnoModel->agregarTurno($data['fecha'], $data['dni_paciente'], $data['id_profesional']);
        if (empty($seAgrego)) {
            return $this->view->error("No se pudo agregar con exito");
        }
        return $this->view->exito("Se agrego con exito el turno");
    }
}