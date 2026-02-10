<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppBot extends Model
{
    use HasFactory;

    protected $table = 'whats_app_bots';

    protected $fillable = [
        'name',
        'phone_number',
        'api_token',
        'webhook_verify_token',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
