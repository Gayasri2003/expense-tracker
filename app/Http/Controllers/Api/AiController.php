<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiAnalyticsService;
use App\Services\AiBudgetService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected $analyticsService;
    protected $budgetService;

    public function __construct(AiAnalyticsService $analyticsService, AiBudgetService $budgetService)
    {
        $this->analyticsService = $analyticsService;
        $this->budgetService = $budgetService;
    }

    public function getAnalytics(Request $request)
    {
        return response()->json($this->analyticsService->analyzeSpending($request->user()->id));
    }

    public function handleChat(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $response = $this->analyticsService->handleChatQuery(
            $request->user()->id, 
            $request->get('query'), 
            $request->user()->currency ?? 'Rs'
        );

        return response()->json(['response' => $response]);
    }

    public function generateBudget(Request $request)
    {
        return response()->json($this->budgetService->optimizeBudget($request->user()->id));
    }
}
