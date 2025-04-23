<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Get email and password from command line or use defaults
$email = $argv[1] ?? 'danuja@danuja.me';
$password = $argv[2] ?? 'danuja';

// Check database connection
try {
    DB::connection()->getPdo();
    echo "Database connected successfully: " . DB::connection()->getDatabaseName() . "\n";
} catch (\Exception $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

// Find the user
$user = User::where('email', $email)->first();

if ($user) {
    // Display user info for debugging
    echo "User found: ID={$user->id}, Email={$user->email}\n";
    
    // Update the password
    $user->password = Hash::make($password);
    $user->save();
    
    // Verify the password was saved correctly
    $updatedUser = User::find($user->id);
    $passwordCheck = Hash::check($password, $updatedUser->password);
    
    echo "Password updated successfully!\n";
    echo "Password hash verification: " . ($passwordCheck ? "PASSED" : "FAILED") . "\n";
    echo "New password hash: " . $updatedUser->password . "\n";
} else {
    echo "User not found with email: {$email}\n";
    
    // List some users for debugging
    $someUsers = User::take(3)->get(['id', 'email']);
    if ($someUsers->count() > 0) {
        echo "Some users in the database:\n";
        foreach ($someUsers as $u) {
            echo "- ID={$u->id}, Email={$u->email}\n";
        }
    } else {
        echo "No users found in the database.\n";
    }
}
