<?php

use App\User;
use App\Video;
use App\Channel;
use App\Comment;
use App\Subscription;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user1 = factory(User::class)->create(['email' => 'mohamed@atar.com']);
        $user2 = factory(User::class)->create(['email' => 'john@doe.com']);

        $channel1 = factory(Channel::class)->create(['user_id' => $user1->id]);
        $channel2 = factory(Channel::class)->create(['user_id' => $user2->id]);

        $channel1->subscriptions()->create(['user_id' => $user2->id]);
        $channel2->subscriptions()->create(['user_id' => $user1->id]);

        factory(Subscription::class, 100)->create(['channel_id' => $channel1->id]);
        factory(Subscription::class, 100)->create(['channel_id' => $channel2->id]);

        $video = factory(Video::class)->create(['channel_id' => $channel1->id]);

        factory(Comment::class, 50)->create(['video_id' => $video->id]);
        $comment = Comment::first();
        factory(Comment::class, 50)->create(
            [
                'video_id' => $video->id,
                'comment_id' => $comment->id,
            ]
        );
    }
}