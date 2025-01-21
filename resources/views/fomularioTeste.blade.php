<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Formulário de Cadastro</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="/api/product" method="POST">
            @csrf <!-- Token de segurança obrigatório -->
            <div class="mb-3">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" name="name" class="form-control" placeholder="Digite seu nome">
            </div>

            <div class="mb-3">
                <label for="preco" class="form-label">preco:</label>
                <input type="number" id="preco" name="price" class="form-control" placeholder="Digite o valor">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</body>

</html>
