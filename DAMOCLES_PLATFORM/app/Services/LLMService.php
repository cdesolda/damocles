<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class LLMService
{
    public function generateEmails($numberEmails, $llm, $prompt)
    {
        $subjects = [];
        $bodies = [];
        $errorCount = 0;

        for ($i = 0; $i < $numberEmails && $errorCount < 5; $i++) {
            try {
                $tempContent = $this->generateChatGPTHTTPPost($llm, $prompt);

                $cleanContent = $this->extractJson($tempContent);

                $decodedContent = json_decode($cleanContent, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($decodedContent['subject']) && isset($decodedContent['body']) && isset($decodedContent['explanation'])) {
                    $subjects[] = $decodedContent['subject'];
                    $bodies[] = $decodedContent['body'];
                    $explanations[] = $decodedContent['explanation'];
                    $errorCount = 0;
                } else {
                    $i--;
                    $errorCount++;
                }
            } catch (\Exception) {
                $i--;
                $errorCount++;
            }
        }

        return ['subjects' => $subjects, 'bodies' => $bodies, 'explanations' => $explanations];
    }

    public function generateChatGPTHTTPPost($llm, $prompt)
    {
        $endpoint = $llm->endpoint;
        $model = $llm->model;

        $data = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ]
        ];

        $apiKey = config('services.openai.key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json'
        ])->post($endpoint, $data);

        if ($response->successful()) {
            $responseData = $response->json();

            $choices = $responseData['choices'];
            $content = $choices[0]['message']['content'];

            // Remove any '#' or '*' characters from the content
            // $content = preg_replace('/[#*]/', '', $content);

            return $content;
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->json()['error']['message'] ?? 'Unknown error';

            throw new \Exception('Request failed, error: ' . $statusCode, $statusCode);
        }
    }

    public function extractJson($content)
    {
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            return $matches[0];
        }

        return $content;
    }
}
