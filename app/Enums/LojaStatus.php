<?php

namespace App\Enums;

enum LojaStatus: string
{
    case Trial = 'trial';
    case Ativa = 'ativa';
    case Inadimplente = 'inadimplente';
    case Suspensa = 'suspensa';
}
