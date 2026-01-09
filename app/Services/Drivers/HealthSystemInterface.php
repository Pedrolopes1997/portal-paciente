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

    /**
     * Retorna lista de especialidades disponíveis
     */
    public function buscarEspecialidades(string $tipo = 'consulta');

    /**
     * Retorna lista de médicos (opcionalmente filtrado por especialidade)
     */
    public function buscarMedicos($idEspecialidade = null);

    /**
     * O Core do sistema: Retorna os slots de tempo livres
     */
    public function buscarHorariosDisponiveis($idMedico, string $data);

    /**
     * Salva o agendamento
     */
    public function criarAgendamento(array $dados);
}