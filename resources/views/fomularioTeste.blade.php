<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h2 class="text-center mb-4">Cadastro de Produto</h2>

                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="/api/product" method="POST">
                        @csrf <!-- Token de segurança obrigatório -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Produto:</label>
                            <input type="text" name="name" class="form-control" placeholder="Digite o nome do produto" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Preço:</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" id="price" name="price" class="form-control" placeholder="0,00" pattern="\d+(,\d{2})?" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
