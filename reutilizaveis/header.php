<!DOCTYPE html>
<html lang="pt=br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <titleGestor>Financeiro</title>
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background-color: white;
        }

        .navbar {
            background-color: black;
        }

        .saldo-positivo {
            color: green;
            font-weight: bold;
        }
        .saldo-negativo {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-4 py-3">
    <a class="navbar-brand fw-bold fs-4" href="index.php">💰 Gestor Financeriro</a>

    <?php if (isset($_SESSION['usuario_logado'])): ?>
        <div class="d-flex gap-3">
        <a href="index.php" class="btn btn-outline-light btn-sm">Dashboard</a>
        <a href="historico.php" class="btn btn-outline-light btn-sm">Histórico</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="container mt-4">