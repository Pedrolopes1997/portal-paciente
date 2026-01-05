<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .details { background-color: #f9fafb; padding: 15px; border-radius: 5px; border-left: 4px solid #4f46e5; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; }
        h1 { color: #333; }
        p { color: #555; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Consulta Confirmada! ✅</h1>
            <p>{{ $appointment->tenant->name }}</p>
        </div>

        <p>Olá, <strong>{{ $appointment->user->name }}</strong>.</p>
        <p>Seu agendamento foi realizado com sucesso. Abaixo estão os detalhes:</p>

        <div class="details">
            <p><strong>👨‍⚕️ Profissional:</strong> {{ $appointment->medico }}</p>
            <p><strong>🩺 Especialidade:</strong> {{ $appointment->especialidade ?? 'Geral' }}</p>
            <p><strong>📅 Data:</strong> {{ $appointment->data_agendamento->format('d/m/Y') }}</p>
            <p><strong>⏰ Horário:</strong> {{ $appointment->data_agendamento->format('H:i') }}</p>
        </div>

        <p>Por favor, chegue com 15 minutos de antecedência.</p>
        
        <div class="footer">
            <p>Este é um e-mail automático, não responda.</p>
            <p><a href="{{ url('/') }}">Acessar Portal do Paciente</a></p>
        </div>
    </div>
</body>
</html>