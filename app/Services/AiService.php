<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.anthropic.com/v1/messages';
    protected string $model = 'claude-sonnet-4-20250514';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', env('ANTHROPIC_API_KEY', ''));
    }

    public function generateNutritionPlan(array $data): array
    {
        $prompt = "أنت خبير تغذية رياضي. أنشئ نظاماً غذائياً متكاملاً لـ:
الاسم: {$data['name']}، العمر: {$data['age']}، الوزن: {$data['weight']}كغ، الطول: {$data['height']}سم، الهدف: {$data['goal']}، النشاط: {$data['activity_level']}، نسبة الدهون: {$data['body_fat']}%، أيام التمرين: {$data['workout_days']}.
أجب بـ JSON فقط بهذا الشكل:
{\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"meals\":[{\"name\":\"\",\"time\":\"\",\"items\":[\"\"],\"calories\":0}],\"alternatives\":[\"\"],\"notes\":\"\"}
لا تضف أي نص خارج الـ JSON.";
        
        return $this->callAnthropicApi($prompt);
    }

    public function generateWorkoutPlan(array $data): array
    {
        $prompt = "أنت مدرب رياضي خبير. أنشئ خطة تدريبية لـ:
الاسم: {$data['name']}، الهدف: {$data['goal']}، المستوى: {$data['level']}، أيام التمرين: {$data['days']} أيام/أسبوع.
أجب بـ JSON فقط:
{\"days\":[{\"day\":\"\",\"focus\":\"\",\"exercises\":[{\"name\":\"\",\"sets\":\"\",\"reps\":\"\",\"rest\":\"\"}]}],\"tips\":\"\"}
لا تضف أي نص خارج الـ JSON.";
        
        return $this->callAnthropicApi($prompt);
    }

    public function analyzeFood(string $query): array
    {
        $prompt = "أنت خبير تغذية. حلل القيمة الغذائية لـ: \"{$query}\"
أجب بـ JSON فقط بهذا الشكل بدون أي نص خارجه:
{\"food\":\"\",\"amount\":\"\",\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"fiber\":0,\"sugar\":0,\"sodium\":0,\"vitamins\":[{\"name\":\"\",\"amount\":\"\",\"benefit\":\"\"}],\"minerals\":[{\"name\":\"\",\"amount\":\"\",\"benefit\":\"\"}],\"tips\":\"\",\"category\":\"\",\"glycemic\":\"منخفض\"}";
        
        return $this->callAnthropicApi($prompt);
    }

    private function callAnthropicApi(string $prompt, int $maxTokens = 1500): array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'max_tokens' => $maxTokens,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $text = '';
                if (isset($body['content'])) {
                    foreach ($body['content'] as $block) {
                        if (isset($block['text'])) {
                            $text .= $block['text'];
                        }
                    }
                }
                $clean = preg_replace('/```json|```/', '', trim($text));
                return json_decode($clean, true) ?? ['error' => 'Failed to parse AI response'];
            }

            Log::error('Anthropic API error: ' . $response->body());
            return ['error' => 'API request failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('Anthropic API exception: ' . $e->getMessage());
            return ['error' => 'Exception: ' . $e->getMessage()];
        }
    }
}
