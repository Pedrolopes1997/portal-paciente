<?php

namespace App\Services\Tasy;

use Illuminate\Support\Facades\DB;
use Exception;

class TasyPacienteService
{
    /**
     * Busca dados cadastrais do paciente
     */
    public function buscarPaciente(string $cpf)
    {
        // Remove pontuação do CPF
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

        return DB::connection('oracle')
            ->table('PESSOA_FISICA')
            ->select(
                'CD_PESSOA_FISICA as id',
                'NM_PESSOA_FISICA as nome',
                'DT_NASCIMENTO as nascimento',
                'NR_CPF as cpf'
                // Adicione outros campos se precisar: NR_CARTEIRINHA, etc.
            )
            ->where('NR_CPF', $cpfLimpo)
            ->first();
    }

    /**
     * Busca lista de exames liberados
     */
    public function buscarExames($cdPessoaFisica)
    {
        return DB::connection('oracle')
            ->table('LAUDO_PACIENTE')
            ->select(
                'NR_SEQUENCIA as id_exame',
                
                // --- VERIFIQUE O NOME CORRETO NO SQL ---
                'DS_TITULO_LAUDO as descricao', // Se der erro de novo, tente DS_PROCEDIMENTO
                // ---------------------------------------
                
                'DT_LIBERACAO as data',
                'DS_LAUDO as texto_resultado',
                'IE_STATUS_LAUDO as status' // <--- Corrigido conforme você me passou
            )
            ->where('CD_PESSOA_FISICA', $cdPessoaFisica)
            // Filtra apenas Liberados (Geralmente 'L' ou 'LIB')
            // Se não aparecer nada, comente essa linha abaixo para testar
            ->where('IE_STATUS_LAUDO', 'LD') 
            ->orderBy('DT_LIBERACAO', 'desc')
            ->limit(50)
            ->get();
    }

    /**
     * Recupera o PDF do Laudo (BLOB)
     */
    public function obterPdfLaudo(int $idExame)
    {
        $laudo = DB::connection('oracle')
            ->table('LAUDO_PACIENTE')
            ->select('OB_LAUDO_PDF') // Verifique se o nome da coluna BLOB é OB_LAUDO_PDF, OB_LAUDO ou IM_LAUDO
            ->where('NR_SEQUENCIA', $idExame)
            ->first();

        if (!$laudo || empty($laudo->ob_laudo_pdf)) {
            return null;
        }

        return $laudo->ob_laudo_pdf;
    }

    /**
     * Valida as credenciais do paciente (Login)
     */
    public function autenticarPaciente($cpf, $dataNascimento)
    {
        // Limpa o CPF (deixa só números)
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

        // Busca no Tasy
        // ATENÇÃO: O formato da data no Oracle é chato. 
        // Se der erro, tentaremos usar TO_DATE. Por enquanto vamos no padrão Laravel.
        return DB::connection('oracle')
            ->table('PESSOA_FISICA')
            ->select('CD_PESSOA_FISICA', 'NM_PESSOA_FISICA', 'NR_CPF', 'DT_NASCIMENTO')
            ->where('NR_CPF', $cpfLimpo)
            ->whereDate('DT_NASCIMENTO', $dataNascimento) // Compara apenas a data, ignora hora
            ->first();
    }
}
