<?php
require_once 'reutilizaveis/sessao.php';
require_once 'reutilizaveis/functions.php';

// PROTEÇÃO DE ACESSO
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$transacoes    = $_SESSION['transacoes'];
$totalDespesas = calcularTotal($transacoes);
$saldo         = calcularSaldo($transacoes);
?>
<?php require_once 'reutilizaveis/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📜 Histórico Completo</h2>
    <a href="index.php" class="btn btn-secondary btn-sm">← Voltar ao Dashboard</a>
</div>

<?php if (empty($transacoes)): ?>

    <div class="alert alert-warning">
        Nenhuma transação registrada ainda. <a href="index.php">Adicione uma agora!</a>
    </div>

<?php else: ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Impacto no Saldo</th>
                        <th>% das Despesas</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    $saldo_acumulado = 0;

                    foreach ($transacoes as $t):
                        if ($t['tipo'] == 'receita') {
                            $saldo_acumulado += $t['valor'];
                            $impacto = '+' . formatarfazmerir($t['valor']);
                            $cor_impacto = 'text-success';
                            $porcentagem = '—'; // Receitas não têm porcentagem de despesa
                        } else {
                            $saldo_acumulado -= $t['valor'];
                            $impacto = '-' . formatarfazmerir($t['valor']);
                            $cor_impacto = 'text-danger';
                            $pct = calcularPorcentagem($t['valor'], $totalDespesas);
                            $porcentagem = number_format($pct, 1) . '%';
                        }
                    ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo $t['nome']; ?></td>
                        <td>
                            <?php if ($t['tipo'] == 'receita'): ?>
                                <span class="badge bg-success">Receita</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Despesa</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo formatarfazmerir($t['valor']); ?></td>
                        <td class="<?php echo $cor_impacto; ?>"><?php echo $impacto; ?></td>
                        <td><?php echo $porcentagem; ?></td>
                        <td><small><?php echo $t['data']; ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">SALDO FINAL:</td>
                        <td colspan="3" class="fw-bold <?php echo $saldo >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo formatarfazmerir($saldo); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Botão Zerar Mês -->
    <div class="text-end mt-3">
        <form method="POST" action="index.php" onsubmit="return confirm('Apagar todo o histórico?')">
            <input type="hidden" name="acao" value="limpar">
            <button type="submit" class="btn btn-outline-danger">🗑️ Limpar Histórico</button>
        </form>
    </div>

<?php endif; ?>

<?php require_once 'reutilizaveis/footer.php'; ?>