<?php

namespace App\Livewire\Chat;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

class FineTune extends Component
{
    use WithFileUploads;

    public $prompt;
    public $maxTokens;
    public $file;
    public $response;
    public $error;
    public function mount()
    {
        $this->maxTokens = 2048;
    }
    public function render()
    {
        return view('livewire.chat.fine-tune');
    }

    public function fineTune()
    {
        // Validate form inputs
        $this->validate([
            'file' => 'required|mimetypes:application/json',
            'prompt' => 'required',
            'maxTokens' => 'required|numeric'
        ]);

        // Handle file uploads and save to the public file system
        $this->file = $this->file->store('fine-tune', 'public');
        // Get the file path
        $filePath = Storage::disk('public')->path($this->file);
        // Get the file contents
        $fileContents = file_get_contents($filePath);

        $client = new Client();
        $apiKey = config('app.openai.api_key');
        try {
            // Create a new engine with the name you want and your dataset
            $create_response = $client->post('https://api.openai.com/v1/engines', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' => [
                    'id' => 'org-USsSVJZMTGDFdNNtej4M6f4b',
                    'dataset' => $fileContents,
                ]
            ]);
        } catch (RequestException $e) {
            $this->error = "1. An error occurred while trying to create the engine: " . $e->getMessage();
        }
        try {
            // Use the newly created engine to fine-tune the model
            $response = $client->post('https://api.openai.com/v1/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' => [
                    'prompt' => $this->prompt,
                    'model' => 'org-USsSVJZMTGDFdNNtej4M6f4b',
                    'max_tokens' => $this->maxTokens,
                    'stop' => "",
                    'temperature' => 0.5,
                ]
            ]);
            $this->response = json_decode($response->getBody()->getContents());

        } catch (RequestException $e) {
            $this->error = "2. An error occurred while trying to fine-tune the model: " . $e->getMessage();
        }
    }

    public function resetProperties()
    {
        parent::reset(['prompt', 'file', 'response', 'error']);
    }

//    public function __destruct()
//    {
//        //Delete the file from the public file system
//        Storage::disk('public')->delete($this->file);
//    }
    public function deleteFile()
    {
        // Delete the file from the public file system
        if ($this->file) {
            Storage::disk('public')->delete($this->file);
        }
    }
}
