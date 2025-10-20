<?php

namespace App\Enums;

enum AccountType: string
{
    case BankAccount = 'Bank Account';
    case CreditCard = 'Credit Card';
    case Cash = 'Cash';
    case EWallet = 'E-Wallet';
    case Savings = 'Savings';
    case Investment = 'Investment';
    case EmergencyFund = 'Emergency Fund';
}
