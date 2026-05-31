<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerWhatsAppConfig extends Model
{
    protected $table = 'trainer_whatsapp_configs';

    protected $fillable = [
        'trainer_id',
        'server_url',
        'api_key',
        'phone_number',
        'is_connected',
        'notify_nutrition',
        'notify_workout',
        'notify_progress',
    ];

    protected function casts(): array
    {
        return [
            'is_connected' => 'boolean',
            'notify_nutrition' => 'boolean',
            'notify_workout' => 'boolean',
            'notify_progress' => 'boolean',
        ];
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function sendMessage(string $to, string $message): array
    {
        if (!$this->server_url || !$this->is_connected) {
            return ['success' => false, 'error' => 'WhatsApp غير متصل'];
        }
        $url = rtrim($this->server_url, '/') . '/send';
        $payload = [
            'to' => $to,
            'message' => $message,
            'api_key' => $this->api_key,
        ];
        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
            ]);
            $resp = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode === 200) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => $resp ?: "HTTP $httpCode"];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
