<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Hoteleiro</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            overflow-x: auto;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .filtros {
            margin-bottom: 20px;
        }

        select {
            padding: 5px;
            font-size: 16px;
        }

        .tabela-reservas {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }

        .tabela-reservas th, .tabela-reservas td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            min-width: 40px;
        }

        .tabela-reservas th {
            background-color: #007bff;
            color: white;
        }

        .quarto {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        .vazio {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Controle de Reservas</h1>

        <div class="filtros">
            <label for="mes">Mês:</label>
            <select id="mes">
                <option value="0">Janeiro</option>
                <option value="1">Fevereiro</option>
                <option value="2">Março</option>
                <option value="3">Abril</option>
                <option value="4">Maio</option>
                <option value="5">Junho</option>
                <option value="6">Julho</option>
                <option value="7">Agosto</option>
                <option value="8">Setembro</option>
                <option value="9" selected>Outubro</option>
                <option value="10">Novembro</option>
                <option value="11">Dezembro</option>
            </select>

            <label for="ano">Ano:</label>
            <select id="ano"></select>

            <button onclick="atualizarTabela()">Atualizar</button>
        </div>

        <table class="tabela-reservas">
            <thead id="cabecalho">
                <tr>
                    <th>Quarto</th>
                </tr>
            </thead>
            <tbody id="corpo"></tbody>
        </table>
    </div>

    <script>

function carregarReservas() {
    fetch('listar_reservas.php')
        .then(response => response.json())
        .then(data => {
            const corpoTabela = document.getElementById("corpo");
            corpoTabela.innerHTML = "";

            const mes = parseInt(document.getElementById("mes").value);
            const ano = parseInt(document.getElementById("ano").value);
            const diasNoMes = obterDiasNoMes(mes, ano);

            let quartos = new Set(data.map(reserva => reserva.numero_quartos));

            quartos.forEach(quarto => {
                let linha = document.createElement("tr");

                // Coluna fixa do nome do quarto
                let celulaQuarto = document.createElement("td");
                celulaQuarto.textContent = "Quarto " + quarto;
                celulaQuarto.classList.add("quarto");
                linha.appendChild(celulaQuarto);

                // Preencher os dias do mês
                for (let dia = 1; dia <= diasNoMes; dia++) {
                    let td = document.createElement("td");
                    td.classList.add("vazio");
                    linha.appendChild(td);
                }

                // Adicionar a linha à tabela
                corpoTabela.appendChild(linha);
            });

            // Preencher os nomes dos hóspedes
            data.forEach(reserva => {
                let checkin = new Date(reserva.data_checkin);
                let checkout = new Date(reserva.data_checkout);
                let quarto = reserva.numero_quartos;
                let nome = reserva.nome_cliente;

                let linhaQuarto = [...corpoTabela.children].find(tr => 
                    tr.children[0].textContent === "Quarto " + quarto
                );

                if (linhaQuarto) {
                    for (let dia = checkin.getDate(); dia < checkout.getDate(); dia++) {
                        let celulaDia = linhaQuarto.children[dia];
                        celulaDia.textContent = nome;
                        celulaDia.style.backgroundColor = "#ffd700"; // Destacar a reserva
                    }
                }
            });
        })
        .catch(error => console.error("Erro ao carregar reservas:", error));
}

    document.addEventListener("DOMContentLoaded", carregarReservas);


        function preencherAnos() {
            const anoSelect = document.getElementById("ano");
            const anoAtual = new Date().getFullYear();
            for (let i = anoAtual - 5; i <= anoAtual + 5; i++) {
                let option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                if (i === anoAtual) option.selected = true;
                anoSelect.appendChild(option);
            }
        }

        function obterDiasNoMes(mes, ano) {
            return new Date(ano, mes + 1, 0).getDate();
        }

        function atualizarTabela() {
            const mes = parseInt(document.getElementById("mes").value);
            const ano = parseInt(document.getElementById("ano").value);
            const diasNoMes = obterDiasNoMes(mes, ano);
            const cabecalho = document.getElementById("cabecalho");
            const corpo = document.getElementById("corpo");

            // Limpa tabela
            cabecalho.innerHTML = "<tr><th>Quarto</th></tr>";
            corpo.innerHTML = "";

            // Adiciona as datas no cabeçalho
            let linhaCabecalho = cabecalho.querySelector("tr");
            for (let dia = 1; dia <= diasNoMes; dia++) {
                let th = document.createElement("th");
                th.textContent = dia + "/" + (mes + 1);
                linhaCabecalho.appendChild(th);
            }

            // Adiciona as linhas de quartos
            for (let i = 1; i <= 30; i++) {
                let linha = document.createElement("tr");
                let celulaQuarto = document.createElement("td");
                celulaQuarto.textContent = "Quarto " + i;
                celulaQuarto.classList.add("quarto");
                linha.appendChild(celulaQuarto);

                for (let j = 1; j <= diasNoMes; j++) {
                    let td = document.createElement("td");
                    td.classList.add("vazio");
                    linha.appendChild(td);
                }

                corpo.appendChild(linha);
            }
        }

        // Inicializa a página
        document.addEventListener("DOMContentLoaded", function () {
            preencherAnos();
            atualizarTabela();
        });
    </script>
</body>
</html>
