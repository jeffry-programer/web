<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        $messages = Message::where('conversations_id', $conversation->id)->orderBy('created_at', 'asc')->get();
        $store = Store::find($conversation->stores_id);
        $user = User::find($conversation->users_id);
        $user_store = User::find($store->users_id);
        return response()->json(['messages' => $messages, 'store' => $store, 'user' => $user, 'userStore' => $user_store]);
    }

    public function store(Request $request)
    {
        $email = User::find($request->userId)->email;
        
        $message = new Message();
        $message->conversations_id = $request->id;
        $message->content = $request->content;
        $message->from = $email;
        $message->created_at = Carbon::now();
        $message->save();

        event(new NewMessage($message));

        return response()->json($message);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        $message->content = $request->input('content');
        $message->save();
        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Message::find($id);
        $message->delete();
        return response()->json(['message' => 'Message deleted']);
    }
}
