<?php

namespace App\Livewire\Chat;

use GuzzleHttp\Client;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ChatForm extends Component
{
    #[Rule('required|min:3', message: 'Please enter a prompt with at least 3 characters.')]
    public string $prompt;
    public mixed $generatedText = '';
    public array $chatLog = [];
    public bool $streaming = false;

    public function validatePrompt(): void
    {
        $this->validate();
        // Append the user's question to the chat log.
        $this->chatLog[] = ['role' => 'user', 'content' => $this->prompt];

        $this->js('$wire.generateText()');
    }

    public function generateText(): void
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('app.openai.api_key'),
            ],
            'json' => [
                'model' => config('app.openai.model'),
                'messages' => [
                    ['role' => 'user', 'content' => 'You are a laravel assistant, but can answer questions about anything.'],
                    ['role' => 'system', 'content' => 'Format output with Markdown.' . $this->prompt],
                ],
                'temperature' => (int) config('app.openai.temperature'),
                'max_tokens' => (int) config('app.openai.max_tokens'),
                'top_p' => (int) config('app.openai.top_p'),
                'frequency_penalty' => (int) config('app.openai.frequency_penalty'),
                'presence_penalty' => (int) config('app.openai.presence_penalty'),
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);
        $textForMarkdown = $responseBody['choices'][0]['message']['content'];
        // Trim any starting or trailing whitespace on $textForMarkdown.
        $textForMarkdown = trim($textForMarkdown);

        $partials = str_split($textForMarkdown, 1); // Just an example to split the text into partials.

        foreach ($partials as $partial) {
            $this->stream('generatedText', $partial);
            $this->streaming = true;
            // if streaming create a value boolean to check if the streaming is finished.
            usleep(10); // Optional delay to simulate streaming effect.
        }
        // This will keep it displayed in the view after the streaming has finished.
        $this->generatedText = $textForMarkdown;
        if ($this->generatedText)
        {
            $this->streaming = false;
        }
        $this->prompt = '';

        // Append the AI's answer to the chat log.
        $this->chatLog[] = ['role' => 'ai', 'content' => $this->generatedText];
    }

    public function render()
    {
        return view('livewire.chat.chat-form');
    }
}
