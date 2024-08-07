<?php
use App\Models\Category;

test('test all', function () {

    Category::factory(10);
    $categories = Category::get()->toArray();

    $this->get(
        '/api/category/all',
        [
            'Authorization' => 'Bearer ' . generateTokenAuth()
        ]
    )->assertOk()->assertJson($categories);
});

it("test get by id", function () {
    Category::factory(1)->create();
    $category = Category::first();

    $this->get('/api/category/' . $category->id, [
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(200)->assertJson([
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug
            ]);
});

it("test get by slug", function () {
    Category::factory(1)->create();
    $category = Category::first();

    $this->get('/api/category/slug/' . $category->slug, [
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(200)->assertJson([
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug
            ]);
});

it("test post", function () {
    $data = ['title' => 'Cate 1', 'slug' => 'cate-2-new'];
    $this->post('/api/category', $data, [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(200)->assertJson($data);
});

it("test post error form title", function () {
    $data = ['title' => '', 'slug' => 'cate-2-new'];
    $response = $this->post('/api/category', $data, [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(422);

    // $this->assertStringContainsString("The title field is required.", $response->getContent());
    $this->assertMatchesRegularExpression("/The title field is required./", $response->getContent());
    // $testArray = array("a"=>"value a", "b"=>"value b"); 
    // $value = "value b";  
    // // assert function to test whether 'value' is a value of array 
    // $this->assertContains($value, $testArray) ;

    // $this->assertContains("The title field is required.",['title'=>'["The title field is required."]']);
});

it("test post error form slug", function () {
    $data = ['title' => 'cate 3', 'slug' => ''];
    $response = $this->post('/api/category', $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])->assertStatus(422);

    // $response->assertStringContainsString("The slug field is required.", $response->getContent());
    $this->assertMatchesRegularExpression("/The slug field is required./", $response->getContent());
});
it("test post error form slug unique", function () {
    Category::create(
        [
            'title' => 'category title',
            'slug' => 'cate-3'
        ]
    );

    $data = ['title' => 'cate 3', 'slug' => 'cate-3'];

    $response = $this->post('/api/category', $data, [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(422);

    $this->assertStringContainsString("The slug has already been taken.", $response->getContent());
});

it("test get by id 404", function () {
    $this->get('/api/category/1000', [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(404)->assertContent('"Not found"');

});
it("test get by slug 404", function () {
    $this->get('/api/category/slug/cate-not-exist', [
        'Accept' => 'application/json',
    ])->assertStatus(404)->assertContent('"Not found"');
});

it("test put", function () {
    Category::factory(1)->create();
    $categoryOld = Category::first();

    $dataEdit = ['title' => 'Cate new 1', 'slug' => 'cate-1-new'];

    $this->put('/api/category/' . $categoryOld->id, $dataEdit, [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(200)->assertJson($dataEdit);
});

it("test delete auth", function () {
    Category::factory(1)->create();
    $category = Category::first();

    $this->delete('/api/category/' . $category->id,[], [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $this->generateTokenAuth()
    ])->assertStatus(200)->assertContent('"ok"');

    $category = Category::find($category->id);
    $this->assertEquals($category, null);
});
