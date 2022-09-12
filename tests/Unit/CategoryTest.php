<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Check if public profile api is accessible or not.
     *
     * @return void
     */
    public function test_can_access_public_category_api()
    {
        $response = $this->get('/api/categories/view/all');

        $response->assertStatus(200);
    }

    /**
     * Check if category list is private. only user can see his categories.
     *
     * @return void
     */
    public function test_can_not_access_private_category_api()
    {
        $response = $this->get('/api/categories');

        $response->assertStatus(401);
    }

    /**
     * Test if category is creatable.
     *
     * @return void
     */
    public function test_can_create_category()
    {
        // Login the user first.
        Auth::login(User::where('email', 'admin@example.com')->first());
        $productRepository = new CategoryRepository();

        // First count total number of categories
        $totalCategories = Category::get('id')->count();

        $product = $productRepository->create([
            'name'       => 'Hello',
            'type'       => 'Goodbye',
            'status'     => 1,
        
        ]);

        $this->assertDatabaseCount('categories', $totalCategories + 1);

        // Delete the category as need to keep it in database for other tests
        $category = Category::where('name', 'Hello')->where('type', 'Goodbye')->first();
        $category->delete();
    }
}
