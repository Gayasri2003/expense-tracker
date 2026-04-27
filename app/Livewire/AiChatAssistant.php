<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AiAnalyticsService;
use Illuminate\Support\Facades\Auth;

class AiChatAssistant extends Component
{
    public $query = '';
    public $messages = [];
    public $isOpen = false;

    public function mount()
    {
        $this->messages = [
            ['type' => 'ai', 'text' => "Hi there! I'm your AI financial assistant. Ask me about your spending, for example: 'How much did I spend on food?'"]
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function ask()
    {
        if (trim($this->query) === '') return;

        // Add user message
        $this->messages[] = ['type' => 'user', 'text' => $this->query];

        $service = app(AiAnalyticsService::class);
        $response = $service->handleChatQuery(Auth::id(), $this->query, Auth::user()->currency ?? 'Rs');

        // Add AI message
        $this->messages[] = ['type' => 'ai', 'text' => $response];

        $this->query = '';
    }

    public function render()
    {
        return view('livewire.ai-chat-assistant');
    }
}
