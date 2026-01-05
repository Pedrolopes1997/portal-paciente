<?php

namespace App\Services\Drivers;

use Illuminate\Support\Collection;

interface HealthSystemInterface
{
    public function buscarPaciente(string $cpf, string $nascimento);
    public function validarPaciente($cpf, $dataNascimento); // Método auxiliar para validação
    
    public function buscarAgendamentos($idPaciente);
    public function buscarExames($idPaciente);
    public function obterPdfLaudo($idExame);

    public function buscarUltimoConvenio($idPaciente);

    public function buscarDetalhesPaciente($idPaciente);
}