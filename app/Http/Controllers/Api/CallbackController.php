<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Midtrans\CallbackService;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;



class CallbackController extends Controller
{

    public function sendNofiticationToUser($userId, $message)
    {
        $user = User::find($userId);
        $token = $user->fcm_token;


        $messaging = app('firebase.messaging');
        $notification = Notification::create('Order Terbayar', $message);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }
    
    public function callback()
    {
        $callback = new CallbackService;

        // if ($callback->isSignatureKeyVerified()) {
        $notification = $callback->getNotification();
        $order = $callback->getOrder();

        if ($callback->isSuccess()) {
            Order::where('id', $order->id)->update([
                'payment_status' => 2,
            ]);
        }

        if ($callback->isExpire()) {
            Order::where('id', $order->id)->update([
                'payment_status' => 3,
            ]);
        }

        if ($callback->isCancelled()) {
            Order::where('id', $order->id)->update([
                'payment_status' => 3,
            ]);
        }

        $this->sendNofiticationToUser($order->user_id, $notification->status_message);

        return response()
            ->json([
                'success' => true,
                'message' => 'Notification successfully processed',
            ]);
        // } else {
        //     return response()
        //         ->json([
        //             'error' => true,
        //             'message' => 'Signature key not verified',
        //         ], 403);
        // }
    }
}
