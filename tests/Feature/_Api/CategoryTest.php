<?php
namespace Tests\Feature\Api;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Category;
use App\Models\User;

// use Illuminate\Foundation\Testing\WithFaker;


class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     */
    public function test_all(): void
    {
        Category::factory(10)->create();
        $categories = Category::get()->toArray();
        // dd($categories);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
        ->get('/api/category/all');
        // dd($response);
        $response->assertStatus(200);
        $response->assertJson($categories);
    }
    public function test_get_by_id(): void
    {
        Category::factory(1)->create();
        $category = Category::first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
        ->get('/api/category/' . $category->id);
        //  dd($category);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $category->id,
            'title' => $category->title,
            'slug' => $category->slug
        ]);
    }
    public function test_get_by_slug(): void
    {
        Category::factory(1)->create();
        $category = Category::first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->get('/api/category/slug/' . $category->slug);
        //  dd($category);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $category->id,
            'title' => $category->title,
            'slug' => $category->slug
        ]);
    }
    public function test_post(): void
    {
        $data = ['title' => 'Cate 1', 'slug' => 'cate-2-new'];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->post('/api/category', $data);
        $response->assertStatus(200)->assertJson($data);
    }
    public function test_post_error_form_title(): void
    {
        $data = ['title' => '', 'slug' => 'cate-2-new'];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->post('/api/category', $data);
        $response->assertStatus(422);
        // dd($response->getContent());
        $this->assertStringContainsString("The title field is required.", $response->getContent());

        // $testArray = array("a"=>"value a", "b" =>"value b"); 
        // $value = "value b";  
        // // assert function to test whether 'value' is a value of array 
        // $this->assertContains($value, $testArray) ;

        // $this->assertContains("The title field is required.",['title'=>'["The title field is required."]']);
    }
    public function test_post_error_form_slug(): void
    {
        $data = ['title' => 'cate 3', 'slug' => ''];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->post('/api/category', $data);
        $response->assertStatus(422);
        $this->assertStringContainsString("The slug field is required.", $response->getContent());
    }
    public function test_post_error_form_slug_unique(): void
    {
        Category::create(
            [
                'title' => 'category title',
                'slug' => 'cate-3'
            ]
        );

        $data = ['title' => 'cate 3', 'slug' => 'cate-3'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->post('/api/category', $data);
        $response->assertStatus(422);
        $this->assertStringContainsString("The slug has already been taken.", $response->getContent());
    }

    public function test_get_by_id_404(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])->get('/api/category/1000');
        //  dd($category);
        $response->assertStatus(404)->assertContent('"Not found"');

    }
    public function test_get_by_slug_404(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/category/slug/cate-not-exist');
        //  dd($category);
        $response->assertStatus(404)->assertContent('"Not found"');

    }

    public function test_put(): void
    {
        Category::factory(1)->create();
        $categoryOld = Category::first();

        $dataEdit = ['title' => 'Cate new 1', 'slug' => 'cate-1-new'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->put('/api/category/' . $categoryOld->id, $dataEdit);
        $response->assertStatus(200)->assertJson($dataEdit);
    }
    // public function test_delete(): void
    // {
    //     Category::factory(1)->create();
    //     $category = Category::first();

    //     // $response = $this->withHeaders([
    //     //     'Accept' => 'application/json'
    //     // ])
    //     $response = $this->withHeaders([
    //         'Accept' => 'application/json'
    //     ])
    //         ->delete('/api/category/' . $category->id);
    //     $response->assertStatus(200)
    //         ->assertContent('"ok"');

    //     $category = Category::find($category->id);
    //     // $this->assertEquals($category==null,true);
    //     $this->assertEquals($category, null);
    // }
    public function test_delete_auth(): void
    {
        Category::factory(1)->create();
        $category = Category::first();

        // $response = $this->withHeaders([
        //     'Accept' => 'application/json'
        // ])
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateTokenAuth()
        ])
            ->delete('/api/category/' . $category->id);
        $response->assertStatus(200)
            ->assertContent('"ok"');

        $category = Category::find($category->id);
        // $this->assertEquals($category==null,true);
        $this->assertEquals($category, null);
    }

}
