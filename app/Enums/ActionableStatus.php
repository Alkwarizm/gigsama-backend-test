<?php

namespace App\Enums;

enum ActionableStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}
