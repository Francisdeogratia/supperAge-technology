<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\UserRecord;
use App\Models\AIChatHistory;

class AgeAIController extends Controller
{
    /**
     * Show the AGE AI chat interface
     */
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in to use AGE AI.');
        }
        
        $user = UserRecord::find($userId);
        
        // Get user's chat history (optional)
        $chatHistory = AIChatHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        return view('age-ai.chat', compact('user', 'chatHistory'));
    }
    
    /**
     * Process AI chat message
     */
    public function chat(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);
        
        $userMessage = $request->message;
        
        // Save user message to history
        AIChatHistory::create([
            'user_id' => $userId,
            'role' => 'user',
            'message' => $userMessage
        ]);
        
        // Generate AI response
        $aiResponse = $this->generateAIResponse($userMessage, $userId);
        
        // Save AI response to history
        AIChatHistory::create([
            'user_id' => $userId,
            'role' => 'assistant',
            'message' => $aiResponse
        ]);
        
        return response()->json([
            'success' => true,
            'response' => $aiResponse,
            'timestamp' => now()
        ]);
    }
    
    /**
     * Generate AI response using OpenAI or custom logic
     */
    private function generateAIResponse($message, $userId)
    {
        $user = UserRecord::find($userId);
        
        // Get context about Supperage features
        $context = $this->getSupperageContext();
        
        // Option 1: Use OpenAI API (if you have API key)
        if (env('OPENAI_API_KEY')) {
            return $this->getOpenAIResponse($message, $context, $user);
        }
        
        // Option 2: Use rule-based responses
        return $this->getRuleBasedResponse($message, $user);
    }
    
    /**
     * Get OpenAI response (requires OpenAI API key)
     */
    private function getOpenAIResponse($message, $context, $user)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are AGE AI, a helpful assistant for Supperage platform. {$context} The user's name is {$user->name}. Be friendly, helpful, and concise."
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'I apologize, but I encountered an issue. Please try again.';
            }
            
            return $this->getRuleBasedResponse($message, $user);
            
        } catch (\Exception $e) {
            return $this->getRuleBasedResponse($message, $user);
        }
    }
    
    /**
     * Get rule-based response (fallback)
     */
    private function getRuleBasedResponse($message, $user)
    {
        $lowerMessage = strtolower($message);
        
        // Greetings
        if (preg_match('/\b(hello|hi|hey|greetings)\b/', $lowerMessage)) {
            return "Hello {$user->name}! 👋 I'm AGE AI, your personal assistant on Supperage. How can I help you today?";
        }
        
        // Help requests
        if (preg_match('/\b(help|assist|support)\b/', $lowerMessage)) {
            return "I'm here to help! 😊\n\nI can assist you with:\n\n• **Groups**: Create, join, and manage groups\n• **Messaging**: Chat with friends and send messages\n• **Wallet**: Fund, transfer, and manage your wallet\n• **Tasks**: Complete tasks and earn rewards\n• **Verification**: Apply for blue badge verification\n• **Friends**: Add and manage friends\n• **Marketplace**: Buy and sell products\n\nWhat would you like to know more about?";
        }
        
        // Groups
        if (preg_match('/\b(group|groups|create group)\b/', $lowerMessage)) {
            return "Creating a group on Supperage is easy! 🎉\n\n**To create a group:**\n1. Click the ➕ icon in the header\n2. Select 'Create Group'\n3. Enter group name and description\n4. Choose privacy (Public or Private)\n5. Add an image (optional)\n6. Click 'Create'\n\n**To join a group:**\n• Click the ➕ icon → 'View Groups'\n• Browse available groups\n• Click 'Join' on any group\n\nNeed help with something specific?";
        }
        
        // Wallet & Money
        if (preg_match('/\b(wallet|money|fund|transfer|withdraw|balance)\b/', $lowerMessage)) {
            return "Let me help you with your wallet! 💰\n\n**Wallet Features:**\n• **Fund Wallet**: Add money to your account\n• **Transfer**: Send money to other users\n• **Withdraw**: Cash out your earnings\n• **Balance**: Check your current balance\n• **History**: View all transactions\n\n**To access your wallet:**\nClick the ➕ icon → Select wallet option\n\nWhat wallet action would you like to perform?";
        }
        
        // Earning
        if (preg_match('/\b(earn|money|income|task|reward)\b/', $lowerMessage)) {
            return "Great! Here's how you can earn on Supperage: 💸\n\n**Earning Opportunities:**\n1. **Complete Tasks**: Click ➕ → 'Complete Tasks & Earn'\n2. **Referrals**: Invite friends and earn commissions\n3. **Share Links**: Share platform links and get paid\n4. **Download App**: Earn by downloading our app\n5. **Add Tasks**: Create tasks for others and earn\n6. **Marketplace**: Sell products and services\n\nStart earning today! Which method interests you?";
        }
        
        // Verification
        if (preg_match('/\b(verify|verification|blue badge|verified)\b/', $lowerMessage)) {
            return "Getting verified on Supperage! ✅\n\n**Blue Badge Benefits:**\n• Increased credibility\n• Better visibility\n• Access to premium features\n• Priority support\n\n**To apply:**\n1. Click ➕ icon\n2. Select 'Apply for Blue Badge'\n3. Submit required documents\n4. Wait for approval (1-3 days)\n\n**Requirements:**\n• Active account (30+ days)\n• Profile picture and bio\n• Verified email & phone\n• Good standing (no violations)\n\nReady to apply?";
        }
        
        // Messaging
        if (preg_match('/\b(message|chat|dm|conversation)\b/', $lowerMessage)) {
            return "Messaging on Supperage is powerful! 💬\n\n**Features:**\n• Send text, photos, videos\n• Voice messages\n• Reply to messages\n• Forward messages\n• Group chats\n• Video/audio calls\n\n**To start chatting:**\n• Click 'Messages' icon\n• Select a friend or group\n• Start typing!\n\nWhat would you like to know about messaging?";
        }
        
        // Friends
        if (preg_match('/\b(friend|friends|add friend)\b/', $lowerMessage)) {
            return "Making friends on Supperage! 👥\n\n**To add friends:**\n1. Click ➕ icon → 'Add Friends'\n2. Search by name or username\n3. Send friend request\n4. Wait for acceptance\n\n**Friend Features:**\n• Send messages\n• Video/audio calls\n• See their posts\n• Share content\n• Create groups together\n\nWant to add more friends?";
        }
        
        // Features
        if (preg_match('/\b(feature|features|what can|capabilities)\b/', $lowerMessage)) {
            return "Supperage offers amazing features! 🚀\n\n**Main Features:**\n• 📱 Social networking\n• 💬 Messaging & calls\n• 👥 Groups & communities\n• 💰 Digital wallet\n• 🎯 Tasks & earning\n• 🛒 Marketplace\n• 📹 Live streaming\n• 📅 Events\n• ✅ Verification\n• 📢 Advertising\n\n**All in one platform!** What interests you most?";
        }
        
        // Default response
        return "Thanks for your question, {$user->name}! 😊\n\nYou asked: \"{$message}\"\n\nWhile I'm still learning, I'm here to help you navigate Supperage. Here are some things I can help with:\n\n• Creating and managing groups\n• Using your wallet\n• Earning opportunities\n• Getting verified\n• Making friends\n• Platform features\n\nCould you rephrase your question or ask about one of these topics?";
    }
    
    /**
     * Get Supperage platform context
     */
    private function getSupperageContext()
    {
        return "Supperage is an African-focused social platform offering groups, messaging, wallet services, task completion, verification badges, marketplace, live streaming, events, and earning opportunities. Users can create groups, chat with friends, fund wallets, complete tasks to earn, apply for blue badge verification, sell products, and more.";
    }
    
    /**
     * Clear chat history
     */
    public function clearHistory(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        AIChatHistory::where('user_id', $userId)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Chat history cleared'
        ]);
    }
}

