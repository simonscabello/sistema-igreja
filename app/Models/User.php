<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Generate a temporary password based on user name
     */
    public static function generateTemporaryPassword(string $name): string
    {
        // Remove special characters and spaces, keep only letters
        $cleanName = preg_replace('/[^a-zA-Z]/', '', $name);
        
        // Take first 4 characters of name (at least 2)
        $namePrefix = substr($cleanName, 0, max(4, min(strlen($cleanName), 2)));
        
        // Add random numbers and symbols to complete 8 characters
        $remaining = 8 - strlen($namePrefix);
        $suffix = '';
        
        // Add numbers
        for ($i = 0; $i < $remaining - 1; $i++) {
            $suffix .= rand(0, 9);
        }
        
        // Add one special character
        $specialChars = '!@#$%&*';
        $suffix .= $specialChars[rand(0, strlen($specialChars) - 1)];
        
        return $namePrefix . $suffix;
    }
}
