<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    public function test_all(): void
    {
        Category::factory(3)->create();
        Post::factory(10)->create();

        $posts = Post::get()->toArray();
        // dd($categories);

        $response = $this->get('/api/post/all');
        // dd($response);
        $response->assertStatus(200);
        $response->assertJson($posts);
    }

    public function test_get_by_id(): void
    {
        Category::factory(3)->create();
        Post::factory(1)->create();
        $post = Post::first();

        $response = $this->get('/api/post/' . $post->id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'content' => $post->content,
            'category_id' => $post->category_id,
            'description' => $post->description,
            'posted' => $post->posted,
            'updated_at' => $post->updated_at->toISOString(),
            'created_at' => $post->created_at->toISOString(),
            'image' => $post->image
        ]);
    }

    public function test_get_by_id_404(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/post/1000');
        $response->assertStatus(404)->assertContent('"Not found"');
        
    }
    public function test_get_by_slug_404(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/post/slug/post-not-exist');

        $response->assertStatus(404)->assertContent('"Not found"');
        
    }

    public function test_post(): void
    {
        Category::factory(1)->create();

        $data = [
            'title' => 'Post 1',
            'slug' => 'post-1',
            'content' => 'Content',
            'description' => 'Description',
            'image' => 'test.png',
            'category_id' => 1,
            'posted' => 'yes'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/post', $data);

        $post = Post::find(1);
        $response->assertStatus(200)->assertJson(
            [
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'category_id' => $post->category_id,
                'description' => $post->description,
                'posted' => $post->posted,
                'updated_at' => $post->updated_at->toISOString(),
                'created_at' => $post->created_at->toISOString(),
                // 'image' => $post->image,
                'id' => $post->id,
            ]
        );
    }

    public function test_post_error_form_title(): void
    {
        $data = [
            'title' => '',
            'slug' => 'post-1',
            'content' => 'Content',
            'description' => 'Description',
            'image' => 'test.png',
            'category_id' => 1,
            'posted' => 'yes'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/api/post', $data);
        $response->assertStatus(422);
        $this->assertStringContainsString("The title field is required.", $response->getContent());
    }
    public function test_post_error_form_slug(): void
    {
        $data = [
            'title' => 'Post 1',
            'slug' => '',
            'content' => 'Content',
            'description' => 'Description',
            'image' => 'test.png',
            'category_id' => 1,
            'posted' => 'yes'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/api/post', $data);
        $response->assertStatus(422);
        $this->assertStringContainsString("The slug field is required.", $response->getContent());
    }

    public function test_post_error_form_slug_unique(): void
    {
        Category::factory(1)->create();
        Post::create(
            [
                'title' => 'Post 1',
                'slug' => 'post-1',
                'content' => 'Content',
                'description' => 'Description',
                // 'image' => 'test.png',
                'category_id' => 1,
                'posted' => 'yes'
            ]
        );

        $data = [
            'title' => 'New Post',
            'slug' => 'post-1',
            'content' => 'Content content',
            'description' => 'Description',
            'category_id' => 1,
            'posted' => 'not'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/api/post', $data);
        $response->assertStatus(422);
        $this->assertStringContainsString("The slug has already been taken.", $response->getContent());
    }

    public function test_put(): void
    {
        Category::factory(3)->create();
        Post::factory(1)->create();

        $postOld = Post::first();

        $dataEdit = [
            'title' => 'Post new 1',
            'slug' => 'post-new-1',
            'content' => 'Content',
            'description' => 'Description',
            // 'image' => 'test.png',
            'category_id' => 1,
            'posted' => 'yes'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->put('/api/post/' . $postOld->id, $dataEdit);

        $response->assertStatus(200)->assertJson($dataEdit);
    }
    public function test_put_error_form_img(): void
    {
        Category::factory(3)->create();
        Post::factory(1)->create();

        $postOld = Post::first();

        $dataEdit = [
            'title' => 'Post new 1',
            'slug' => 'post-new-1',
            'content' => 'Content',
            'description' => 'Description',
            'image' => 'test.png',
            'category_id' => 1,
            'posted' => 'yes'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->put('/api/post/' . $postOld->id, $dataEdit);

        $response->assertStatus(422);
        $this->assertStringContainsString("The image field must be a file of type: jpeg, jpg, png.", $response->getContent());
    }
    public function test_delete(): void
    {
        Category::factory(3)->create();
        Post::factory(1)->create();
        $post = Post::first();

        $response = $this->delete('/api/post/' . $post->id);
        $response->assertStatus(200)
            ->assertContent('"ok"');

        $post = Post::find($post->id);
        // $this->assertEquals($post==null,true);
        $this->assertEquals($post, null);
    }
}
