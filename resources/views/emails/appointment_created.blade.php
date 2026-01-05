<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f4f4f4; padding: 40px 0; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        /* HEADER COM COR DINÂMICA */
        .header { 
            background-color: {{ $appointment->tenant->primary_color ?? '#4f46e5' }}; 
            padding: 30px 20px; 
            text-align: center; 
        }
        
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .logo { max-height: 50px; margin-bottom: 10px; }

        .content { padding: 30px; color: #333; line-height: 1.6; }
        
        /* CARD DE DETALHES */
        .details { 
            background-color: #f8fafc; 
            border-left: 5px solid {{ $appointment->tenant->primary_color ?? '#4f46e5' }}; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 4px; 
        }
        
        .btn { 
            display: inline-block; 
            background-color: {{ $appointment->tenant->primary_color ?? '#4f46e5' }}; 
            color: #ffffff; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: bold; 
            margin-top: 20px;
        }
        
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($appointment->tenant->logo_path)
                <img src="{{ asset('storage/'.$appointment->tenant->logo_path) }}" alt="Logo" class="logo" style="background: white; padding: 5px; border-radius: 4px;">
            @else
                <h1>{{ $appointment->tenant->name }}</h1>
            @endif
        </div>

        <div class="content">
            <p>Olá, <strong>{{ $appointment->user->name }}</strong>!</p>
            <p>Sua consulta foi agendada com sucesso. Confira os detalhes abaixo:</p>

            <div class="details">
                <p style="margin: 5px 0;"><strong>👨‍⚕️ Profissional:</strong> {{ $appointment->medico }}</p>
                <p style="margin: 5px 0;"><strong>🩺 Especialidade:</strong> {{ $appointment->especialidade ?? 'Clínica Geral' }}</p>
                <p style="margin: 5px 0;"><strong>📅 Data:</strong> {{ $appointment->data_agendamento->format('d/m/Y') }}</p>
                <p style="margin: 5px 0;"><strong>⏰ Horário:</strong> {{ $appointment->data_agendamento->format('H:i') }}</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('paciente.login', ['tenant_slug' => $appointment->tenant->slug]) }}" class="btn">
                    Acessar Portal do Paciente
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>{{ $appointment->tenant->name }} - Todos os direitos reservados.</p>
            <p>Não responda a este e-mail.</p>
        </div>
    </div>
</body>
</html>