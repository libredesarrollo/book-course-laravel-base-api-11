<?php

use App\Models\Category;
use App\Models\Post;

it("test all", function () {
    Category::factory(3)->create();
    Post::factory(10)->create();
    $posts = Post::get()->toArray();

    $this->get('/api/post/all')
        ->assertStatus(200)
        ->assertJson($posts);
});

it("test get by id", function () {
    Category::factory(3)->create();
    Post::factory(1)->create();
    $post = Post::first();

    $this->get('/api/post/' . $post->id)->assertStatus(200)->assertJson([
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
});

it("test get by id 404", function () {
    $this->get('/api/post/1000', [
        'Accept' => 'application/json'
    ])->assertStatus(404)->assertContent('"Not found"');
});

it("test get by slug 404", function () {
    $this->get('/api/post/slug/post-not-exist', [
        'Accept' => 'application/json'
    ])->assertStatus(404)->assertContent('"Not found"');
});

it("test post", function () {
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

    $response = $this->post('/api/post', $data, [
        'Accept' => 'application/json'
    ]);

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
});
it("test post error form title", function () {
    $data = [
        'title' => '',
        'slug' => 'post-1',
        'content' => 'Content',
        'description' => 'Description',
        'image' => 'test.png',
        'category_id' => 1,
        'posted' => 'yes'
    ];


    $response = $this->post('/api/post', $data, [
        'Accept' => 'application/json'
    ])->assertStatus(422);
    $this->assertMatchesRegularExpression("/The title field is required./", $response->getContent());
});

it("test post error form slug", function () {
    $data = [
        'title' => 'Post 1',
        'slug' => '',
        'content' => 'Content',
        'description' => 'Description',
        'image' => 'test.png',
        'category_id' => 1,
        'posted' => 'yes'
    ];


    $response = $this->post('/api/post', $data, [
        'Accept' => 'application/json'
    ])->assertStatus(422);

    $this->assertMatchesRegularExpression("/The slug field is required./", $response->getContent());
});

it("test post error form slug unique", function () {
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

    $response = $this->post('/api/post', $data, [
        'Accept' => 'application/json'
    ])->assertStatus(422);

    $this->assertMatchesRegularExpression("/The slug has already been taken./", $response->getContent());
});

it("test put", function () {
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

    $this
        ->put('/api/post/' . $postOld->id, $dataEdit, [
            'Accept' => 'application/json'
        ])->assertStatus(200)->assertJson($dataEdit);
});

it("test put error form img", function () {
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

    $response = $this->put('/api/post/' . $postOld->id, $dataEdit, [
            'Accept' => 'application/json'
        ]);

    $response->assertStatus(422);
    $this->assertMatchesRegularExpression("/The image field must be a file of type: jpeg, jpg, png./", $response->getContent());
});

it("test delete", function () {
    Category::factory(3)->create();
    Post::factory(1)->create();
    $post = Post::first();

    $this->delete('/api/post/' . $post->id)->assertStatus(200)->assertContent('"ok"');

    $post = Post::find($post->id);
    $this->assertEquals($post, null);
});

