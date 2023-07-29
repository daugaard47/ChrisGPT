<?php

namespace App\Http\Livewire\Chat;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Renderer\HtmlRenderer;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;
use Livewire\Component;

class ChatForm extends Component
{
    public $prompt;
    public $generatedText;

    public function generateText()
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
                    ['role' => 'user', 'content' => 'Hello!'],
                    ['role' => 'system', 'content' => 'Format output with Markdown including format code with Markdown triple backticks. ' . $this->prompt],
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

// create a new CommonMark environment
        $environment = new Environment();

// Add the core extension
        $environment->addExtension(new CommonMarkCoreExtension());

// Register the FencedCodeRenderer class with the 'html', 'php', 'js' languages
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer(['html', 'php', 'js']));

// Create a new MarkdownConverter instance
        $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer(['html', 'php', 'js']));

        $markdownConverter = new MarkdownConverter($environment);

        $this->generatedText = (string) $markdownConverter->convert($textForMarkdown);

//        // remove the first and last " from the string.
//        $textForMarkdown = substr($textForMarkdown, 1, -1);
//        // if there is a ``` add a new linebreak before and after it.
//        $textForMarkdown = str_replace('```', "\n```\n", $textForMarkdown);
//        // In $textForMarkdown search for ``` if there are multiple ``` make all the odd ``` into <pre><code> and all the even ``` into </code></pre>.
//        $textForMarkdown = preg_replace_callback('/```/', function ($matches) {
//            static $i = 0;
//
//            return $i++ % 2 ? '</code></pre>' : '<pre><code>';
//        }, $textForMarkdown);
    }
    public function render()
    {
        return view('livewire.chat.chat-form');
    }
}
