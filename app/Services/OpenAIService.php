<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function analyzePostContent($content, $userInfo)
    {
        $prompt = $this->generatePrompt($content, $userInfo);
        \Log::info('antes de enviar');
        \Log::info($prompt);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, [
            'model' => 'gpt-4o-mini',
            'messages' => [['role'=> 'user', 'content'=> $prompt]],
            'max_tokens' => 1500,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            \Log::info($response->json());
            return $response->json();
        }
dd($response);
        return ['error' => 'Error al analizar el contenido con OpenAI'];
    }

    private function generatePrompt($content, $userInfo)
    {
        return "Analiza el siguiente contenido de un usuario considerando su información personal. \n\n" .
               "Información del usuario: \n" .
               "Edad: {$userInfo['age']} años\n" .
               "Altura: {$userInfo['height']} cm\n" .
               "Peso: {$userInfo['weight']} kg\n" .
               "Enfermedades preexistentes: " . implode(', ', $userInfo['diseases']) . "\n\n" .
               "Post publicado: \n\n" . $content . "\n\n" .
               "Proporcione una evaluación calórica aproximada de la comida mencionada, ajuste sugerido para lograr un déficit calórico y considere las intolerancias o alergias si las hay.";
    }
}
