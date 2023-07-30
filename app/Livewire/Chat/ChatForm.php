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
//        $this->js('$wire.generateFakeText()');
    }

    public function generateFakeText(): void
    {
        $this->streaming = true;

        $fakeText = "This is a fake response to simulate the streaming feature. This text will be split into smaller chunks and streamed to the front end. Each chunk will be sent as a separate event.";

        $partials = str_split($fakeText, 1); // Split the text into chunks of 20 characters.

        foreach ($partials as $partial) {
            // Append the partial text to the existing text.
            $this->generatedText .= $partial;

            // Send the entire text.
            $this->stream('generatedText', $this->generatedText, true);

            usleep(100); // Delay to simulate streaming effect. Adjust as needed.
        }

        // Append the AI's answer to the chat log.
        $this->chatLog[] = ['role' => 'ai', 'content' => $this->generatedText];

        $this->streaming = false;
        $this->prompt = '';
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
            // Append the partial text to the existing text.
            $this->generatedText .= $partial;

            // Send the entire text.
            $this->stream('generatedText', $this->generatedText, true);

            usleep(100); // Delay to simulate streaming effect. Adjust as needed.
        }

//        foreach ($partials as $partial) {
//            $this->stream('generatedText', $partial, true);
//            usleep(10); // Optional delay to simulate streaming effect.
//        }
        // This will keep it displayed in the view after the streaming has finished.
        $this->generatedText = $textForMarkdown;

        // Append the AI's answer to the chat log.
        $this->chatLog[] = ['role' => 'ai', 'content' => $this->generatedText];

        $this->prompt = '';
    }

    public function render()
    {
        return view('livewire.chat.chat-form');
    }
}
