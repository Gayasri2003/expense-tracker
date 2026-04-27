<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AiAnalyticsService;
use Illuminate\Support\Facades\Auth;

class AiSpendingAnalytics extends Component
{
    public $analytics = [];
    public $currency = '$';

    public function mount(AiAnalyticsService $analyticsService)
    {
        $this->currency = Auth::user()->currency ?? '$';
        $this->analytics = $analyticsService->analyzeSpending(Auth::id());
    }

    public function render()
    {
        return view('livewire.ai-spending-analytics');
    }
}
