<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{

    public function testCreateComment()
    {
        $comment = new Comment();
        $comment->email = "email@email.com";
        $comment->title = "okok";
        $comment->comment = "comment";
        $comment->save();

        $this->assertNotNull($comment->id);
        $this->assertNotNull($comment->created_at);
        $this->assertNotNull($comment->updated_at);
    }
}
