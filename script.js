document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('estoque-form');
    const alertContainer = document.querySelector('.alert-container');
    const estoqueLista = document.getElementById('estoque-lista');

    // Carregar itens ao iniciar
    carregarItens();

    // Formulário de envio
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        const formData = {
            action: 'create',
            nome: document.getElementById('nome').value,
            categoria: document.getElementById('categoria').value,
            quantidade: document.getElementById('quantidade').value
        };

        try {
            const response = await fetch('crud.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams(formData)
            });
            
            const result = await response.json();
            mostrarAlerta(result.success ? 'success' : 'danger', result.message);
            
            if (result.success) {
                form.reset();
                form.classList.remove('was-validated');
                carregarItens();
            }
        } catch (error) {
            mostrarAlerta('danger', 'Erro na comunicação com o servidor');
        }
    });

    // Carregar itens do estoque
    async function carregarItens() {
        try {
            const response = await fetch('crud.php?action=read');
            const result = await response.json();
            
            if (result.success) {
                estoqueLista.innerHTML = result.data.length > 0 
                    ? gerarLinhasTabela(result.data) 
                    : '<tr class="placeholder-item"><td colspan="5" class="text-center py-5 text-muted">Nenhum item cadastrado</td></tr>';
                adicionarEventosBotoes();
            }
        } catch (error) {
            console.error('Erro ao carregar itens:', error);
        }
    }

    // Gerar linhas da tabela
    function gerarLinhasTabela(data) {
        return data.map(item => `
            <tr data-id="${item.id}">
                <td>${item.id}</td>
                <td>${item.nome}</td>
                <td>${item.categoria}</td>
                <td>${item.quantidade}</td>
                <td>
                    <button class="btn btn-sm btn-warning me-2 editar-item">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger excluir-item">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Função para mostrar alertas
    function mostrarAlerta(type, message) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    // Adicionar eventos aos botões de editar/excluir
    function adicionarEventosBotoes() {
        document.querySelectorAll('.excluir-item').forEach(btn => {
            btn.addEventListener('click', async () => {
                const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                const confirmDelete = document.getElementById('confirmDelete');
                
                modal.show();
                
                confirmDelete.onclick = async () => {
                    const id = btn.closest('tr').dataset.id;
                    try {
                        const response = await fetch('crud.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: new URLSearchParams({action: 'delete', id})
                        });
                        
                        const result = await response.json();
                        mostrarAlerta(result.success ? 'success' : 'danger', result.message);
                        if (result.success) carregarItens();
                    } catch (error) {
                        mostrarAlerta('danger', 'Erro ao excluir item');
                    }
                    modal.hide();
                };
            });
        });
    }
});