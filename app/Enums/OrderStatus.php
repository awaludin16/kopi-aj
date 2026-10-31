<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}
