<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; }
        h1 { color: #e53e3e; text-align: center; }
        p { color: #555; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Consulta Cancelada</h1>
        <p>Olá, <strong>{{ $appointment->user->name }}</strong>.</p>
        <p>Confirmamos o cancelamento da sua consulta com <strong>{{ $appointment->medico }}</strong> que estava agendada para {{ $appointment->data_agendamento->format('d/m/Y \à\s H:i') }}.</p>
        <p>Se foi um engano, entre em contato com a clínica para reagendar.</p>
    </div>
</body>
</html>