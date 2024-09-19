<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class MessageController extends Controller
{
    public function index($conversationId, $userEmail)
    {
        $conversation = Conversation::find($conversationId);
        $messages = Message::where('conversations_id', $conversation->id)->orderBy('created_at', 'asc')->get();

        $store = User::find($conversation->stores_id)->store;

        if ($userEmail == User::find($conversation->users_id)->email) {
            $user = User::find($conversation->stores_id);
        } else {
            $user = User::find($conversation->users_id);
        }

        if ($user->store) {
            $user->name = $user->store->name;
            $user->image = $user->store->image;
        }

        if ($user->image == null || $user->image == '') {
            $letter = strtoupper($user->name[0]);
            $user->image = 'https://ui-avatars.com/api/?name=' . $letter . '&amp;color=7F9CF5&amp;background=EBF4FF';
        } else {
            $user->image = $user->image;
        }

        $messages = $conversation->messages;

        // Iterar sobre los mensajes y actualizar su estado a true
        foreach ($messages as $message) {
            if ($message->from != $userEmail) {
                $message->status = true;
                $message->save();
            }

            $message->content = Crypt::decryptString($message->content);
        }

        return response()->json(['messages' => $messages, 'store' => $store, 'user' => $user, 'userEmail' => $userEmail]);
    }

    public function store(Request $request)
    {
        $user = User::find($request->userId);
        $conversationId = $request->id;
        $content = $request->content;

        $message = new Message();
        $message->conversations_id = $conversationId;
        $message->content = Crypt::encryptString($content);
        $message->from = $user->email;
        $message->status = false;
        $message->save();

        $message->content = $content;

        //event(new NewMessage($message));

        $conversation = Conversation::find($conversationId);
        $user1 = User::find($conversation->users_id);
        $store = User::find($conversation->stores_id)->store;
        $user2 = User::find($store->users_id);

        if ($user1->email == $user->email) {
            $token = $user2->token;
            $name = $user1->name;
        } else {
            $token = $user1->token;
            $name = $store->name;
        }

        if (strlen($token) > 10) {
            $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

            // Obtener el servicio de mensajería
            $messaging = $firebase->createMessaging();

            // Crear el mensaje
            $message = CloudMessage::fromArray([
                'token' => $token,  // El token del dispositivo que recibirá la notificación
                'notification' => [
                    'title' => $name,
                    'body' => $content,
                ],
                'data' => [ // Datos adicionales para manejar la redirección
                    'click_action' => 'OPEN_URL',
                    'url' => '/chat/' . $request->id,  // Ruta donde quieres redirigir al usuario
                ],
                'android' => [  // Mover el bloque de Android fuera de 'data'
                    'priority' => 'high',
                ],
            ]);

            // Enviar el mensaje
            $messaging->send($message);
        }
        
        return response()->json(['success' => true, 'message' => 'Notificación enviada con éxito', 'name' => $name, 'content' => $content]);
    }

    public function changeStatusMessage(Request $request)
    {
        $message = Message::find($request->id);
        $message->status = true;
        $message->save();
        return response()->json($message);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        $message->content = Crypt::encryptString($request->input('content'));
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
