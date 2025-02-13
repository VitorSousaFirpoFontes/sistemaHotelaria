<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestão de estoque para hotéis">
    <title>Gestão de Estoque Hoteleiro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="estoque.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="bg-primary text-white py-3 shadow">
        <div class="container">
            <h1 class="mb-0">Gestão de Estoque Hoteleiro</h1>
        </div>
    </header>

    <main class="container flex-grow-1 py-4">
        <div class="alert-container"></div>

        <section aria-labelledby="estoque-title">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 id="estoque-title" class="h4"><i class="bi bi-box-seam"></i> Itens em Estoque</h2>
                <button class="btn btn-sm btn-outline-primary" id="toggle-filters">
                    <i class="bi bi-funnel"></i> Filtros
                </button>
            </div>

            <div class="table-responsive rounded shadow-sm">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="sortable">ID <i class="bi bi-arrow-down-up"></i></th>
                            <th scope="col" class="sortable">Item</th>
                            <th scope="col" class="sortable">Categoria</th>
                            <th scope="col" class="sortable">Quantidade</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="estoque-lista" class="bg-white">
                        <tr class="placeholder-item">
                            <td colspan="5" class="text-center py-5 text-muted">
                                Nenhum item cadastrado. Comece adicionando novos itens abaixo.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mt-5" aria-labelledby="adicionar-item-title">
            <h2 id="adicionar-item-title" class="h4 mb-4"><i class="bi bi-plus-circle"></i> Adicionar Novo Item</h2>
            
            <form id="estoque-form" class="row g-4 needs-validation" novalidate>
                <div class="col-md-5">
                    <label for="nome" class="form-label">Nome do Item</label>
                    <input type="text" id="nome" class="form-control" 
                           placeholder="Ex: Toalha de Banho" required>
                    <div class="invalid-feedback">Por favor, insira o nome do item.</div>
                </div>

                <div class="col-md-4">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select id="categoria" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option>Limpeza</option>
                        <option>Higiene</option>
                        <option>Cozinha</option>
                        <option>Manutenção</option>
                    </select>
                    <div class="invalid-feedback">Selecione uma categoria válida.</div>
                </div>

                <div class="col-md-3">
                    <label for="quantidade" class="form-label">Quantidade</label>
                    <input type="number" id="quantidade" class="form-control" 
                           min="1" max="1000" value="1" required>
                    <div class="invalid-feedback">Quantidade deve ser entre 1 e 1000.</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check2"></i> Adicionar Item
                    </button>
                </div>
            </form>
        </section>
    </main>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja remover este item do estoque?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>