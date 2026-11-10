<?php

namespace App\Http\Controllers\MessageController;

use App\Http\Controllers\Controller\Controller;
use App\Models\QuickReplyTemplate;
use Illuminate\Http\Request;

class QuickReplyController extends Controller
{
    public function index(Request $request)
    {
        $templates = QuickReplyTemplate::userTemplates($request->user()->id)
            ->orderBy('is_system', 'desc')
            ->orderBy('title')
            ->get();

        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        $template = QuickReplyTemplate::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'is_system' => false,
        ]);

        return response()->json($template, 201);
    }
}
