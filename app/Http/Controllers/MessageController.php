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
    public function index($conversationId, $userId)
    {
        // Cargar la conversación con los mensajes y el store relacionado
        $conversation = Conversation::with(['messages', 'store'])->findOrFail($conversationId);

        // Determinar el usuario que está en la conversación
        $user = User::with('store')->findOrFail(($userId == User::find($conversation->users_id)->id)
            ? $conversation->stores_id
            : $conversation->users_id
        );

        // Asignar el nombre e imagen del store al usuario
        $user->name = $user->store->name ?? $user->name; // Mantener el nombre si no hay store
        $user->image = $user->store->image ?? 'https://ui-avatars.com/api/?name=' . strtoupper($user->name[0]) . '&color=7F9CF5&background=EBF4FF';

        // Actualizar el estado de los mensajes y desencriptar el contenido
        foreach ($conversation->messages as $message) {
            if($message->from != $user->email){
                $message->status = true;
                $message->save();
            }
            $message->content = Crypt::decryptString($message->content);
            $message->from = Crypt::decryptString($message->from);
        }

        $store = $user->store ?? null;

        return response()->json([
            'messages' => $conversation->messages,
            'store' => $store,
            'user' => $user
        ]);
    }


    public function store(Request $request)
    {
        $user = User::find($request->userId);
        $conversationId = $request->id;
        $content = $request->content;
        $from = $user->email;

        $message = new Message();
        $message->conversations_id = $conversationId;
        $message->content = Crypt::encryptString($content);
        $message->from = Crypt::encryptString($user->email);
        $message->status = false;
        $message->save();

        $message->content = $content;
        $message->from = $from;

        event(new NewMessage($message));

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
