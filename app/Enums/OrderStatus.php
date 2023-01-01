<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case SETTLEMENT = 'settlement';
    case DENY = 'deny';
    case CANCEL = 'cancel';
    case EXPIRE = 'expire';
}
