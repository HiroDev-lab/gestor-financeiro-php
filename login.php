<?php
require_once 'reutilizaveis/sessao.php';

if (isset($_SESSION['usuario_logado'])){
    header('Location: index.php');
    exit;
}

$usuario_correto = 'admin';
$senha_hash = password_hash('admin123', PASSWORD_DEFAULT);


$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $usuario_digitado = trim($_POST['usuario']);
    $senha_digitada = trim($_POST['senha']);


    if (empty($usuario_digitado) || empty($senha_digitada)){
    $erro = 'Preencha todos os campos. ';
    } elseif ($usuario_digitado == $usuario_correto && password_verify($senha_digitada, $senha_hash)) {
        $_SESSION['usuario_logado'] = $usuario_correto;
        header('Location: index.php');
        exit();
    } else {
        $erro = 'Usuario ou senha incorretos';
    }
}
?>
<?php require_once'reutilizaveis/header.php';?>

<div class="row justfy-content-center">
    <div class="col-md-4">

        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">🔐 Login</h3>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($erro);?></div>
                <?php endif;?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="usuario class" class="form-label">Usúario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Disgite o usuário" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" placeholder="Digite sua senha"required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <p class="text-center text-muted mt-3">
                    <small>Usuário: <strong>admin</strong> | Senha: <strong>admin123</strong></small>
                </p>
            </div>
        </div>
    </div>
</div>