<?php

namespace App\Enums;

enum OrdemServicoStatus: string
{
    case Aguardando = 'aguardando';
    case EmAndamento = 'em_andamento';
    case Concluido = 'concluido';
    case Entregue = 'entregue';
    case Cancelado = 'cancelado';
}
