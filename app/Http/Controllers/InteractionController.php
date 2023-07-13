<?php

namespace App\Http\Controllers;

use App\Events\AddComment;
use App\Events\AddLike;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Like\LikeRequest;
use App\Events\GetNotifications;
use App\Http\Resources\CommentResaurce;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\QuoteResource;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;


class InteractionController extends Controller
{
    public function addComment(CommentRequest $request): void
    {
        $comment = Comment::create($request->validated());
        $receiverId = Quote::where('id', $request->input('quote_id'))->first()->user_id;

        event(new AddComment(new CommentResaurce($comment)));

        if($request->input('user_id') !== $receiverId) $this->sendNotification($request, 'comment');

    }

    public function addLike(LikeRequest $request): void
    {
        $quote = Quote::where('id', $request->input('quote_id'))->first();
        $receiverId = $quote->user_id;
        $quote->likers()->toggle($request->input('user_id'));
        $quote->save();
        $quoteResource = new QuoteResource($quote);
        $liked = $quote->likers()->where('id', $request->input('user_id'))->exists();

        event(new AddLike(['count' => $quoteResource->likers()->count(), 'quote_id' =>  $quoteResource->id]));

        if ($liked && $request->input('user_id') !== $receiverId) $this->sendNotification($request, 'like');

    }



    public function sendNotification($request, $type): void
    {
        $receiverId = Quote::where('id', $request->input('quote_id'))->first()->user_id;

        $notification = Notification::create([...$request->validated(),
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $receiverId,
            'type' =>  $type,
            'seen' => false
        ]);

        event(new GetNotifications((object)[
            'notification' =>  new NotificationResource($notification),
            'id' => $receiverId
        ]));

    }


    public function getNotifications(): ResourceCollection
    {
       return NotificationResource::collection(Auth::user()->notifications->sortByDesc('id'));
    }

    public function MarkAllSeen(): void
    {
        Notification::where('notifiable_id', Auth::user()->id)->update(['seen' => true]);
    }

    public function notificationSeen(Request $request): NotificationResource
    {
        $notification = Notification::where('id', $request->id)->first();

        $notification->seen = true;
        $notification->save();

        return  new NotificationResource($notification);
    }

}
