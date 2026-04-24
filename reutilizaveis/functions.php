<?php
function calcularSaldo($movimentacoes){
    $saldo = 0;

    foreach ($movimentacoes as $transacao){
        if ($transacao['tipo'] == 'receita'){
            $saldo += $transacao['valor'];
        } else {
            $saldo -= $transacao['valor'];
        }
    }

    return $saldo;
}


function calcularTotal($transacoes){
    $total = 0;

    foreach ($transacoes as $transacao){
        if ($transacao['tipo'] == 'despesa'){
            $total += $transacao['valor'];
        }
    }

    return $total;
}

function calcularPorcentagem($valorDespesas, $totalDespesas){
    if ($totalDespesas == 0) {
        return 0;
    }

    return ($valorDespesas / $totalDespesas) * 100;
}

function formatarfazmerir($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}
