<?php

namespace App\Enums;

enum LoanType: string
{
    case Borrow = 'borrow';
    case Lent = 'lent';
}
