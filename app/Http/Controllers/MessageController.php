<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;

class MessageController extends Controller

{
    public function index($conversationId, $userEmail)
    {
        $conversation = Conversation::find($conversationId);
        $messages = Message::where('conversations_id', $conversation->id)->orderBy('created_at', 'asc')->get();
        $store = Store::find($conversation->stores_id);
        $user = User::find($conversation->users_id);
        $user_store = User::find($store->users_id);
        $messages = $conversation->messages;
        // Iterar sobre los mensajes y actualizar su estado a true
        foreach ($messages as $message) {
            if ($message->from != $userEmail) {
                $message->status = true;
                $message->save();
            }
        }
        return response()->json(['messages' => $messages, 'store' => $store, 'user' => $user, 'userStore' => $user_store]);
    }

    public function store(Request $request)
    {
        $user = User::find($request->userId);
        $conversationId = $request->id;
        $content = $request->content;

        $message = new Message();
        $message->conversations_id = $conversationId;
        $message->content = $content;
        $message->from = $user->email;
        $message->status = false;
        $message->save();

        event(new NewMessage($message));

        $conversation = Conversation::find($conversationId);
        $user1 = User::find($conversation->users_id);
        $store = Store::find($conversation->stores_id);
        $user2 = User::find($store->users_id);

        if ($user1->email == $user->email) {
            $token = $user2->token;
            $name = $user1->name;
        } else {
            $token = $user1->token;
            $name = $store->name;
        }

        fcm()->to([$token])->priority('high')->timeToLive(0)->notification([
            'title' => $name,
            'body' => $content,
        ])->send();

        return response()->json(['success' => true, 'message' => 'Notificación enviada con éxito']);
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
