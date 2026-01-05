<?php

namespace App\Services\Drivers;

interface HealthSystemInterface
{
    public function buscarPaciente(string $cpf, string $nascimento);
    public function buscarExames(string $idPaciente);
    public function buscarAgendamentos($idPaciente);
    public function obterPdfLaudo($idExame);
    public function validarPaciente($cpf, $dataNascimento);
}