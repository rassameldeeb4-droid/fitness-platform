<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected string $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY', ''));
    }

    public function generateNutritionPlan(array $data): array
    {
        $extra = '';
        if (!empty($data['wrist'])) $extra .= "محيط الرصغ: {$data['wrist']}سم، ";
        if (!empty($data['waist'])) $extra .= "محيط الوسط: {$data['waist']}سم، ";
        if (!empty($data['job'])) $extra .= "الوظيفة: {$data['job']}، ";
        if (!empty($data['activity_level'])) $extra .= "مستوى النشاط البدني: {$data['activity_level']}، ";

        $prompt = "أنت خبير تغذية رياضي. أنشئ نظاماً غذائياً متكاملاً لـ:
الاسم: {$data['name']}، العمر: {$data['age']}، الوزن: {$data['weight']}كغ، الطول: {$data['height']}سم، {$extra}الهدف: {$data['goal']}، نسبة الدهون: {$data['body_fat']}%، أيام التمرين: {$data['workout_days']}.

أجب بـ JSON فقط بهذا الشكل (كل وجبة بكارت منفصل):
{\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"fiber\":0,\"meals\":[{\"name\":\"\",\"time\":\"\",\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"items\":[\"100غ صدر دجاج\",\"50غ أرز\"],\"vitamins\":[{\"name\":\"فيتامين ب6\",\"amount\":\"0.5ملغ\",\"benefit\":\"للايض\"}],\"minerals\":[{\"name\":\"حديد\",\"amount\":\"2ملغ\",\"benefit\":\"للدم\"}]}],\"alternatives\":[\"\"],\"notes\":\"\",\"water\":\"2-3 لتر\"}
لكل وجبة اذكر السعرات والبروتين والكارب والدهون بوضوح. لا تضف أي نص خارج الـ JSON.";

        return $this->callOpenAiApi($prompt, 1000);
    }

    public function generateWorkoutPlan(array $data): array
    {
        $prompt = "أنت مدرب رياضي خبير. أنشئ خطة تدريبية لـ:
الاسم: {$data['name']}، الهدف: {$data['goal']}، المستوى: {$data['level']}، أيام التمرين: {$data['days']} أيام/أسبوع.
أجب بـ JSON فقط:
{\"days\":[{\"day\":\"\",\"focus\":\"\",\"exercises\":[{\"name\":\"\",\"sets\":\"\",\"reps\":\"\",\"rest\":\"\"}]}],\"tips\":\"\"}
لا تضف أي نص خارج الـ JSON.";

        return $this->callOpenAiApi($prompt);
    }

    public function analyzeFood(string $query): array
    {
        $prompt = "أنت خبير تغذية. حلل القيمة الغذائية لـ: \"{$query}\"
أجب بـ JSON فقط بهذا الشكل بدون أي نص خارجه:
{\"food\":\"\",\"amount\":\"\",\"calories\":0,\"protein\":0,\"carbs\":0,\"fat\":0,\"fiber\":0,\"sugar\":0,\"sodium\":0,\"vitamins\":[{\"name\":\"\",\"amount\":\"\",\"benefit\":\"\"}],\"minerals\":[{\"name\":\"\",\"amount\":\"\",\"benefit\":\"\"}],\"tips\":\"\",\"category\":\"\",\"glycemic\":\"منخفض\"}";

        return $this->callOpenAiApi($prompt);
    }

    private function callOpenAiApi(string $prompt, int $maxTokens = 500): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => $maxTokens,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $text = $body['choices'][0]['message']['content'] ?? '';
                $jsonStart = strpos($text, '{');
                $jsonEnd = strrpos($text, '}');
                $clean = '';
                if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                    $clean = substr($text, $jsonStart, $jsonEnd - $jsonStart + 1);
                }
                $decoded = json_decode($clean, true);
                if ($decoded) return $decoded;
                Log::error('OpenAI parse error', ['response' => $text]);
                return ['error' => 'Failed to parse AI response', 'raw' => mb_substr($text, 0, 1000)];
            }

            Log::error('OpenAI API error: ' . $response->body());
            $status = $response->status();
            if ($status === 429) {
                return ['error' => 'حد الاستخدام تجاوز الحد المسموح. حاول بعد دقيقة'];
            }
            return ['error' => 'API request failed: ' . $status];
        } catch (\Exception $e) {
            Log::error('OpenAI API exception: ' . $e->getMessage());
            return ['error' => 'Exception: ' . $e->getMessage()];
        }
    }
}
