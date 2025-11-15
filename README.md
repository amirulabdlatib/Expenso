# Expenso

A full-stack expense tracking application built with modern web technologies.

## 🚧 Status

**Under Development** - This project is currently in active development.

## 🛠️ Tech Stack

### Backend (API)

-   **Laravel 12** - PHP framework for building the RESTful API

### Frontend (Client)

-   **Nuxt.js 4** - Vue.js framework for building the user interface

## 📁 Project Structure

```
Expenso/
├── backend/          # Laravel 12 API backend
└── frontend/       # Nuxt.js 4 frontend client
```

## 📋 Todo

-   [ ] Analytics page
-   [ ] Toggle hide and unhide money number
-   [ ] Update profile
-   [ ] Monthly/Yearly statement report
-   [ ] Zakat
-   [ ] Income tax
-   [ ] Tax relieve
-   [ ] Refund flow
-   [ ] KWSP/EPF/SOCSO
-   [ ] Upload receipt and save to google drive api
-   [ ] Dark mode
-   [ ] Smart receipt reader and categorization
-   [ ] Smart expense advisor

## � Getting Started

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js 18 or higher
-   npm or yarn

### Backend Setup (Laravel API)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### Frontend Setup (Nuxt.js Client)

```bash
cd frontend
npm install
npm run dev
```

## 📝 Features (Available)

### Account Management

-   Create and manage multiple financial accounts (bank accounts, e-wallets, cash, etc.)
-   Track real-time account balances
-   Set initial balance for each account
-   Active/inactive account status toggle
-   View total balance across all accounts
-   Separate balance tracking for active and inactive accounts
-   Authorization-protected account operations

### Transaction Management

-   **Income Tracking** - Record income with automatic balance increase
-   **Expense Tracking** - Record expenses with automatic balance deduction
-   **Account Transfers** - Transfer money between accounts with paired transactions
-   View complete transaction history sorted by date
-   Detailed transaction information including date, amount, and description
-   Monthly income and expense summaries
-   Total transaction count tracking
-   Edit transactions (income and expenses only)
-   Delete transactions with automatic balance reversion
-   Authorization-protected transaction operations

### Category Management

-   Create custom categories for income and expenses
-   Organize categories with parent-child hierarchy (2-level depth)
-   Visual customization with icons and colors
-   Type-based categorization (income/expense)
-   Prevention of nested child categories (max 2 levels)
-   View categorized transactions

### Security & Data Protection

-   User authentication and authorization
-   Policy-based access control for accounts and transactions
-   User-specific data isolation
-   Secure API endpoints with Laravel Sanctum

## 🤝 Contributing

This project is currently under development. Contribution guidelines will be added soon.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
