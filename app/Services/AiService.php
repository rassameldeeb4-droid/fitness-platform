<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';
    protected string $model = 'gemini-flash-latest';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY', ''));
    }

    public function generateNutritionPlan(array $data): array
    {
        $prompt = "أنت خبير تغذية رياضي. أنشئ نظاماً غذائياً متكاملاً لـ:
الاسم: {$data['name']}، العمر: {$data['age']}، الوزن: {$data['weight']}كغ، الطول: {$data['height']}سم، الهدف: {$data['goal']}، النشاط: {$data['activity_level']}، نسبة الدهون: {$data['body_fat']}%، أيام التمرين: {$data['workout_days']}.
أجب بـ JSON فقط بهذا الشكل:
{\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"meals\":[{\"name\":\"\",\"time\":\"\",\"items\":[\"\"],\"calories\":0}],\"alternatives\":[\"\"],\"notes\":\"\"}
لا تضف أي نص خارج الـ JSON.";

        return $this->callGeminiApi($prompt);
    }

    public function generateWorkoutPlan(array $data): array
    {
        $prompt = "أنت مدرب رياضي خبير. أنشئ خطة تدريبية لـ:
الاسم: {$data['name']}، الهدف: {$data['goal']}، المستوى: {$data['level']}، أيام التمرين: {$data['days']} أيام/أسبوع.
أجب بـ JSON فقط:
{\"days\":[{\"day\":\"\",\"focus\":\"\",\"exercises\":[{\"name\":\"\",\"sets\":\"\",\"reps\":\"\",\"rest\":\"\"}]}],\"tips\":\"\"}
لا تضف أي نص خارج الـ JSON.";

        return $this->callGeminiApi($prompt);
    }

    public function analyzeFood(string $query): array
    {
        $prompt = "أنت خبير تغذية. حلل القيمة الغذائية لـ: \"{$query}\"
أجب بـ JSON فقط بهذا الشكل بدون أي نص خارجه:
{\"food\":\"\",\"amount\":\"\",\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"fiber\":0,\"sugar\":0,\"sodium\":0,\"vitamins\":[{\"name\":\"\",\"amount\":\"\",\"benefit\":\"\"}],\"minerals\":[{\"name\":\"\",\"amount\":\"\",\"benefit\":\"\"}],\"tips\":\"\",\"category\":\"\",\"glycemic\":\"منخفض\"}";

        return $this->callGeminiApi($prompt);
    }

    private function callGeminiApi(string $prompt, int $maxTokens = 1500): array
    {
        try {
            $response = Http::withHeaders(['X-goog-api-key' => $this->apiKey])->post($this->apiUrl, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => $maxTokens,
                ],
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $text = '';
                if (isset($body['candidates'][0]['content']['parts'])) {
                    foreach ($body['candidates'][0]['content']['parts'] as $part) {
                        if (isset($part['text'])) {
                            $text .= $part['text'];
                        }
                    }
                }
                $clean = preg_replace('/```json|```|```JSON/', '', trim($text));
                return json_decode($clean, true) ?? ['error' => 'Failed to parse AI response'];
            }

            Log::error('Gemini API error: ' . $response->body());
            return ['error' => 'API request failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
            return ['error' => 'Exception: ' . $e->getMessage()];
        }
    }
}
