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


class InteractionController extends Controller
{
    public function addComment(CommentRequest $request): void
    {
        $comment = Comment::create($request->validated());
        $receiverId = Quote::find($request->quote_id)->user_id;

        event(new AddComment(new CommentResaurce($comment)));

        if($request->user_id !== $receiverId) $this->sendNotification($request, 'comment');

    }

    public function addLike(LikeRequest $request): void
    {
        $quote = Quote::find($request->quote_id);
        $receiverId = $quote->user_id;
        $quote->likers()->toggle($request->user_id);
        $quote->save();
        $quoteResource = new QuoteResource($quote);
        $liked = $quote->likers()->where('id', $request->user_id)->exists();

        event(new AddLike(['count' => $quoteResource->likers()->count(), 'quote_id' =>  $quoteResource->id]));

        if ($liked && $request->user_id !== $receiverId) $this->sendNotification($request, 'like');

    }



    public function sendNotification($request, $type): void
    {
        $receiverId = Quote::find($request->quote_id)->user_id;

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


    public function show(): ResourceCollection
    {
       return NotificationResource::collection(auth()->user()->notifications->sortByDesc('id'));
    }

    public function markAllSeen(): void
    {
        Notification::where('notifiable_id', auth()->user()->id)->update(['seen' => true]);
    }

    public function notificationSeen(Request $request): NotificationResource
    {
        $notification = Notification::find($request->id)->first();

        $notification->seen = true;
        $notification->save();

        return  new NotificationResource($notification);
    }

}
