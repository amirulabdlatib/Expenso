<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        $defaultCategories = [
            // Income Categories
            ['name' => 'Salary', 'type' => 'income', 'icon' => 'heroicons:currency-dollar', 'color' => 'green', 'parent_id' => null],
            ['name' => 'Dividend', 'type' => 'income', 'icon' => 'heroicons:building-office', 'color' => 'purple', 'parent_id' => null],
            ['name' => 'Gift', 'type' => 'income', 'icon' => 'heroicons:gift', 'color' => 'sky', 'parent_id' => null],

            // Expense Categories - Food
            ['name' => 'Food', 'type' => 'expense', 'icon' => 'heroicons:shopping-cart', 'color' => 'fuchsia', 'parent_id' => null],

            // Expense Categories - Transportation
            ['name' => 'Transportation', 'type' => 'expense', 'icon' => 'heroicons:truck', 'color' => 'amber', 'parent_id' => null],

            // Expense Categories - Utilities
            ['name' => 'Utilities Bill', 'type' => 'expense', 'icon' => 'heroicons:bolt', 'color' => 'teal', 'parent_id' => null],

            // Expense Categories - Housing
            ['name' => 'Rent', 'type' => 'expense', 'icon' => 'heroicons:home', 'color' => 'purple', 'parent_id' => null],

            // Health
            ['name' => 'Health', 'type' => 'expense', 'icon' => 'heroicons:bolt', 'color' => 'red', 'parent_id' => null],

            // Entertainment
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => 'heroicons:musical-note', 'color' => 'green', 'parent_id' => null],

            // Shopping
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => 'heroicons:credit-card', 'color' => 'cyan', 'parent_id' => null],

            // Education
            ['name' => 'Education', 'type' => 'expense', 'icon' => 'heroicons:building-office', 'color' => 'sky', 'parent_id' => null],

            // Loan
            ['name' => 'Loan', 'type' => 'expense', 'icon' => 'heroicons:light-bulb', 'color' => 'lime', 'parent_id' => null],

            // Subscription
            ['name' => 'Subscription', 'type' => 'expense', 'icon' => 'heroicons:device-phone-mobile', 'color' => 'teal', 'parent_id' => null],
        ];

        foreach ($defaultCategories as $category) {
            $createdCategory = $user->categories()->create($category);

            $this->createSubcategories($user, $createdCategory);
        }
    }

    private function createSubcategories(User $user, $parentCategory)
    {
        $subcategories = [
            'Food' => [
                ['name' => 'Snacking', 'icon' => 'heroicons:shopping-bag', 'color' => 'cyan'],
                ['name' => 'Work Lunches', 'icon' => 'heroicons:heart', 'color' => 'purple'],
                ['name' => 'Dinner', 'icon' => 'heroicons:credit-card', 'color' => 'blue'],
                ['name' => 'Breakfast', 'icon' => 'heroicons:heart', 'color' => 'cyan'],
            ],
            'Transportation' => [
                ['name' => 'Fuel', 'icon' => 'heroicons:truck', 'color' => 'teal'],
                ['name' => 'Maintenance', 'icon' => 'heroicons:wrench-screwdriver', 'color' => 'pink'],
            ],
            'Utilities Bill' => [
                ['name' => 'Wifi', 'icon' => 'heroicons:wifi', 'color' => 'blue'],
                ['name' => 'Water', 'icon' => 'heroicons:wrench-screwdriver', 'color' => 'blue'],
                ['name' => 'Electricity', 'icon' => 'heroicons:currency-dollar', 'color' => 'cyan'],
                ['name' => 'Prepaid', 'icon' => 'heroicons:bolt', 'color' => 'yellow'],
            ],
            'Salary' => [
                ['name' => 'Full-Time', 'icon' => 'heroicons:light-bulb', 'color' => 'teal'],
                ['name' => 'Part-Time', 'icon' => 'heroicons:banknotes', 'color' => 'emerald'],
                ['name' => 'Freelance', 'icon' => 'heroicons:building-office', 'color' => 'teal'],
                ['name' => 'One-off', 'icon' => 'heroicons:wifi', 'color' => 'blue'],
            ],
            'Dividend' => [
                ['name' => 'Asnb', 'icon' => 'heroicons:light-bulb', 'color' => 'teal'],
                ['name' => 'Tng', 'icon' => 'heroicons:banknotes', 'color' => 'emerald'],
                ['name' => 'Tabung Haji', 'icon' => 'heroicons:building-office', 'color' => 'teal'],
                ['name' => 'EPF', 'icon' => 'heroicons:wifi', 'color' => 'blue'],
            ],
            'Gift' => [
                ['name' => 'Cash Gift', 'icon' => 'heroicons:currency-dollar', 'color' => 'emerald'],
                ['name' => 'Birthday Gift', 'icon' => 'heroicons:gift', 'color' => 'rose'],
                ['name' => 'Wedding Gift', 'icon' => 'heroicons:sparkles', 'color' => 'fuchsia'],
                ['name' => 'Festive Gift', 'icon' => 'heroicons:star', 'color' => 'yellow'],
            ],

            'Health' => [
                ['name' => 'Medicine/Pharmacy', 'icon' => 'heroicons:heart', 'color' => 'fuchsia'],
                ['name' => 'Clinic', 'icon' => 'heroicons:heart', 'color' => 'blue'],
            ],
            'Entertainment' => [
                ['name' => 'Netflix', 'icon' => 'heroicons:film', 'color' => 'teal'],
                ['name' => 'Games', 'icon' => 'heroicons:tv', 'color' => 'blue'],
            ],
            'Shopping' => [
                ['name' => 'Clothing', 'icon' => 'heroicons:banknotes', 'color' => 'cyan'],
                ['name' => 'Sports and Attire', 'icon' => 'heroicons:computer-desktop', 'color' => 'teal'],
            ],
            'Education' => [
                ['name' => 'Books', 'icon' => 'heroicons:device-phone-mobile', 'color' => 'teal'],
                ['name' => 'Stationary', 'icon' => 'heroicons:light-bulb', 'color' => 'indigo'],
                ['name' => 'Courses and Upskilling', 'icon' => 'heroicons:computer-desktop', 'color' => 'blue'],
            ],
            'Loan' => [
                ['name' => 'TKWBNS', 'icon' => 'heroicons:light-bulb', 'color' => 'blue'],
            ],
            'Subscription' => [
                ['name' => 'Claude', 'icon' => 'heroicons:musical-note', 'color' => 'teal'],
            ],
            'Rent' => [
                ['name' => 'House Rent', 'icon' => 'heroicons:home-modern', 'color' => 'purple'],
                ['name' => 'Room Rent', 'icon' => 'heroicons:home', 'color' => 'indigo'],
                ['name' => 'Deposit', 'icon' => 'heroicons:banknotes', 'color' => 'amber'],
            ],
        ];

        if (isset($subcategories[$parentCategory->name])) {
            foreach ($subcategories[$parentCategory->name] as $sub) {
                $user->categories()->create([
                    'name' => $sub['name'],
                    'type' => $parentCategory->type,
                    'icon' => $sub['icon'],
                    'color' => $sub['color'],
                    'parent_id' => $parentCategory->id,
                ]);
            }
        }
    }
}
