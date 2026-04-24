<?php
require_once 'reutilizaveis/sessao.php';
require_once 'reutilizaveis/functions.php';

if(!isset($_SESSION['usuario_logado'])){
    header('Location: login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'nova_transacao') {

    $nome = trim($_POST['nome']);
    $valor = floatval($_POST['valor']);
    $tipo = ($_POST['tipo']);

    if(!empty($nome) && $valor > 0 && in_array($tipo, ['receita', 'despesa'])) {

        $nova_transacao = [
            'nome' => htmlspecialchars($nome),
            'valor' => $valor,
            'tipo' => $tipo,
            'data' => date('d/m/Y H:i')
        ];

        $_SESSION['transacoes'][] = $nova_transacao;
    }

    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'limpar') {
    $_SESSION['transacoes'] = [];
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'limpar'){
    $_SESSION['transacoes'] = [];
    header('Location: index.php');
    exit();
}

$transacoes   = $_SESSION['transacoes'];
$saldo        = calcularSaldo($transacoes);
$totaDespesas = calcularTotal($transacoes);

$totalReceitas = 0;
foreach ($transacoes as $t) {
    if ($t['tipo'] == 'receita') {
        $totalReceitas += $t['valor'];
    }
}
?>

<?php require_once 'reutilizaveis/header.php'; ?>

<h2 class="mb-4">📊 Dashboard</h2>

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h6 class="card-title">💵 Total de Receitas</h6>
                <h4><?php echo formatarfazmerir($totalReceitas); ?></h4>
            </div>
        </div>
    </div>

</div>

<div class="col-md-4">
    <div class="card text-white bg-danger shadow-sm">
        <div class="card-body">
            <h6 class="card-title">💸 Total de Despesas</h6>
            <h4><?php echo formatarfazmerir($totaDespesas); ?></h4>
        </div>
    </div>
</div>

<div class="col-md-4">
        <div class="card text-white shadow-sm <?php echo $saldo >= 0 ? 'bg-primary' : 'bg-dark'; ?>">
            <div class="card-body">
                <h6 class="card-title">💰 Saldo Atual</h6>
                <h4><?php echo formatarfazmerir($saldo); ?></h4>
            </div>
        </div>
    </div>

</div>

<!-- Formulário de nova transação -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <strong>➕ Nova Transação</strong>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php">
            <input type="hidden" name="acao" value="nova_transacao">

            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Nome da Transação</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Salário, Aluguel..." required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valor (R$)</label>
                    <input type="number" name="valor" class="form-control" step="0.01" min="0.01" placeholder="0,00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="receita">📈 Receita</option>
                        <option value="despesa">📉 Despesa</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Adicionar</button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Últimas transações (máximo 5) -->
<?php if (!empty($transacoes)): ?>
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <strong>📋 Últimas Transações</strong>
        <a href="historico.php" class="btn btn-outline-light btn-sm">Ver histórico completo</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Pega as últimas 5 transações (em ordem inversa)
                $ultimas = array_slice(array_reverse($transacoes), 0, 5);
                foreach ($ultimas as $t):
                ?>
                <tr>
                    <td><?php echo $t['nome']; ?></td>
                    <td>
                        <?php if ($t['tipo'] == 'receita'): ?>
                            <span class="badge bg-success">Receita</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Despesa</span>
                        <?php endif; ?>
                    </td>
                    <td class="<?php echo $t['tipo'] == 'receita' ? 'text-success' : 'text-danger'; ?>">
                        <?php echo ($t['tipo'] == 'receita' ? '+' : '-') . formatarfazmerir($t['valor']); ?>
                    </td>
                    <td><small><?php echo $t['data']; ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-info">Nenhuma transação cadastrada ainda. Adicione sua primeira transação acima! 🎉</div>
<?php endif; ?>

<!-- Botão Zerar Mês -->
<?php if (!empty($transacoes)): ?>
<div class="text-end mt-3">
    <form method="POST" action="index.php" onsubmit="return confirm('Tem certeza? Isso apagará todas as transações!')">
        <input type="hidden" name="acao" value="limpar">
        <button type="submit" class="btn btn-outline-danger">🗑️ Zerar Mês</button>
    </form>
</div>
<?php endif; ?>

<?php require_once 'reutilizaveis/footer.php'; ?>