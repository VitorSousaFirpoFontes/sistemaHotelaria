<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Reserva de Hotel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" />
</head>
<body>
    <section class="container mt-5">
        <h1 class="text-center mb-4">Preencha os campos para a reserva</h1>
        <form action="inserir.php" method="POST" class="form-centralizado">

            <div class="form-group">
                <label for="nome_cliente">Nome do cliente:</label>
                <input class="form-control" type="text" id="nome_cliente" name="nome_cliente" required>
            </div>

            <div class="form-group">
                <label for="data_checkin">Data de Check-in:</label>
                <input class="form-control" type="date" id="data_checkin" name="data_checkin" required>
            </div>

            <div class="form-group">
                <label for="data_checkout">Data de Check-out:</label>
                <input class="form-control" type="date" id="data_checkout" name="data_checkout" required>
            </div>

            <div class="form-group">
                <label for="numero_quartos">Número de quartos:</label>
                <input class="form-control" type="number" id="numero_quartos" name="numero_quartos" required min="1" max="10">
            </div>

            <div class="form-group">
                <label for="tipo_quarto">Tipo de quarto:</label>
                <select id="tipo_quarto" name="tipo_quarto" class="form-control" required>
                    <option value="standard">Standard</option>
                    <option value="luxo">Luxo</option>
                    <option value="suite">Suíte</option>
                    <option value="familia">Familiar</option>
                </select>
            </div>

            <div class="form-group">
                <label for="observacoes">Observações (opcional):</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Cadastrar Reserva</button>
        </form>
    </section>

    <script>
        document.getElementById('data_checkout').addEventListener('change', function() {
            const checkin = new Date(document.getElementById('data_checkin').value);
            const checkout = new Date(this.value);
            
            if (checkout <= checkin) {
                alert('A data de Check-out deve ser posterior à data de Check-in.');
                this.value = ''; // Limpa o campo se a validação falhar
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
