<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\App;
use App\Services\Drivers\HealthSystemInterface;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // <--- Importante

class ExameController extends Controller
{
    public function visualizar($tenant_slug, $id)
    {
        if (!Auth::check()) {
            abort(403, 'Acesso negado');
        }

        $driver = App::make(HealthSystemInterface::class);
        $dados = $driver->obterPdfLaudo($id);

        // CASO 1: É HTML vindo do Tasy (Array)
        if (is_array($dados) && isset($dados['tipo']) && $dados['tipo'] === 'html') {
            
            $htmlOriginal = $dados['conteudo'];
            
            // Tenta pegar o logo do Tenant (se tiver upload no Filament)
            // Se não tiver, usa um texto ou base64 padrão
            $logoHtml = '';
            $tenant = \App\Models\Tenant::where('slug', $tenant_slug)->first();
            
            if ($tenant && $tenant->logo) {
                // Para PDF, precisamos do caminho absoluto do arquivo no disco
                $pathLogo = storage_path('app/public/' . $tenant->logo);
                if (file_exists($pathLogo)) {
                    // Converte para Base64 para não dar erro de permissão no PDF
                    $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
                    $data = file_get_contents($pathLogo);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    $logoHtml = "<img src='$base64' style='max-height: 60px;'>";
                }
            }

            $htmlCompleto = "
                <html>
                <head>
                    <style>
                        body { font-family: sans-serif; font-size: 12px; color: #333; }
                        .header { 
                            border-bottom: 2px solid #4f46e5; 
                            padding-bottom: 10px; 
                            margin-bottom: 20px; 
                            display: flex; 
                            justify-content: space-between; 
                            align-items: center; 
                        }
                        .footer {
                            position: fixed; bottom: 0; left: 0; right: 0;
                            font-size: 9px; text-align: center; color: #aaa;
                            border-top: 1px solid #eee; padding-top: 5px;
                        }
                        h1 { font-size: 18px; margin: 0; color: #4f46e5; }
                        .meta { font-size: 10px; color: #666; margin-top: 5px; }
                        /* Limpeza do Tasy */
                        div[data-wate-header] { display: none; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <div>$logoHtml</div>
                        <div style='text-align: right;'>
                            <h1>" . ($dados['titulo'] ?? 'Resultado de Exame') . "</h1>
                            <div class='meta'>Paciente: " . Auth::user()->name . "</div>
                            <div class='meta'>Data: " . date('d/m/Y') . "</div>
                        </div>
                    </div>

                    <div class='laudo-content'>
                        " . $htmlOriginal . "
                    </div>

                    <div class='footer'>
                        Documento gerado digitalmente pelo Portal do Paciente - " . $tenant->name . "
                    </div>
                </body>
                </html>
            ";

            $pdf = Pdf::loadHTML($htmlCompleto);
            return $pdf->stream('laudo-'.$id.'.pdf');
        }
        
        // CASO 2: Arquivo Local (String de caminho)
        if (is_string($dados) && file_exists($dados)) {
            return response()->file($dados, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="laudo-'.$id.'.pdf"'
            ]);
        }

        abort(404, 'Laudo não encontrado.');
    }
}