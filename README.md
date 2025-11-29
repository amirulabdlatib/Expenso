# Expenso

A full-stack expense tracking application built with modern web technologies.

## 🛠️ Tech Stack

### Backend (API)

-   **Laravel 12** - PHP framework for building the RESTful API
-   **MySQL/PostgreSQL** - Relational database for data persistence
-   **Sanctum** - API token authentication

### Frontend (Client)

-   **Nuxt.js 4** - Vue.js framework for building the user interface
-   **Tailwind CSS** - Utility-first CSS framework for styling
-   **Pinia** - State management

## 📁 Project Structure

```
Expenso/
├── backend/          # Laravel 12 API backend
└── frontend/       # Nuxt.js 4 frontend client
```

## ✅ Completed Features

### Core Functionality

-   [x] **User Authentication** - Secure login and registration
-   [x] **Category Management** - Create, read, update, and delete expense/income categories
-   [x] **Transaction Management** - Record and track income and expenses
-   [x] **Account Management** - Manage multiple financial accounts
-   [x] **Budget Management** - Set and track monthly budgets by category

### Budget Management Features

-   [x] **Monthly Budgets** - Set spending limits by category per month
-   [x] **Budget Tracking** - Real-time spending vs budget tracking
-   [x] **Smart Alerts** - Get notified when approaching budget limits
-   [x] **Budget Projections** - Forecast month-end spending
-   [x] **Spending Analytics** - Visualize budget vs actual spending
-   [x] **Transaction History** - View all transactions within a budget

### Receipt Management

-   [x] Private receipt storage for transactions
-   [x] Support for image and PDF uploads
-   [x] Secure file access with authentication
-   [x] Preview functionality for uploaded receipts

### Future Enhancements

-   [ ] Dark mode
-   [ ] Analytics dashboard
-   [x] Toggle money number visibility
-   [x] Profile update functionality
-   [x] Receipt upload
-   [ ] Loan repayment progress module
-   [ ] Monthly/Yearly statement reports
-   [ ] Income tax calculation
-   [ ] Tax relief management
-   [ ] KWSP/EPF/SOCSO calculation
-   [ ] Zakat calculation
-   [ ] Refund flow
-   [ ] Calendar ui that represent total expense/income
-   [ ] Smart receipt reader (OCR) and categorization
-   [ ] AI-powered financial advisor

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

-   **Multiple Accounts** - Manage various financial accounts (bank, e-wallets, cash)
-   **Real-time Balance** - Automatic balance updates with transactions
-   **Account Status** - Toggle accounts between active and inactive
-   **Total Balance** - Consolidated view of all account balances
-   **Transaction History** - Complete record of all account activities
-   **Secure Access** - Role-based access control for all operations

### Transaction Management

-   **Income & Expenses** - Track all financial transactions
-   **Automatic Categorization** - Smart transaction categorization
-   **Transfer Between Accounts** - Seamless money movement
-   **Search & Filter** - Find transactions quickly with advanced filters
-   **Receipt Attachment** - Store digital receipts with transactions
-   **Transaction Notes** - Add context to your transactions

### Budget Management

-   **Monthly Budgets** - Set spending limits by category
-   **Visual Progress** - Track spending with intuitive progress bars
-   **Smart Alerts** - Get notified about budget status
-   **Spending Analysis** - Compare budgeted vs actual spending
-   **Forecasting** - Predict month-end spending patterns
-   **Historical Data** - View past budget performance

### Receipt Management

-   **Secure Storage** - Private receipt storage with user-specific access control
-   **Multiple Formats** - Support for both image (JPG, PNG) and PDF uploads
-   **Preview** - Built-in preview for uploaded receipts
-   **Transaction Association** - Directly attach receipts to transactions
-   **Download** - Download original receipt files
-   **Automatic Cleanup** - Receipts are automatically removed when transactions are deleted

### Security & Data Protection

-   User authentication and authorization
-   Policy-based access control for accounts and transactions
-   User-specific data isolation
-   Secure API endpoints with Laravel Sanctum
-   Protected file storage with authentication checks

## 🤝 Contributing

This project is currently under development. Contribution guidelines will be added soon.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
