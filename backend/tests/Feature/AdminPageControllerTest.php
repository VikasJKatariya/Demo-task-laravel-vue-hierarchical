<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Page;

class AdminPageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_pages()
    {
        Page::factory()->create(['title' => 'Test Page']);

        $response = $this->get(route('adminpages.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Page');
    }

    public function test_store_creates_page()
    {
        $pageData = [
            'title' => 'New Page',
            'slug' => 'new-page',
            'content' => 'This is a test page.',
            'parent_id' => null,
            'is_published' => true,
        ];

        $response = $this->post(route('adminpages.store'), $pageData);

        $response->assertRedirect(route('adminpages.index'));
        $this->assertDatabaseHas('pages', ['title' => 'New Page']);
    }

    public function test_update_modifies_page()
    {
        $page = Page::factory()->create(['title' => 'Old Title']);

        $updatedData = [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'content' => 'Updated content',
            'parent_id' => null,
            'is_published' => true,
        ];

        $response = $this->put(route('adminpages.update', $page->id), $updatedData);

        $response->assertRedirect(route('adminpages.index'));
        $this->assertDatabaseHas('pages', ['title' => 'Updated Title']);
    }

    public function test_destroy_deletes_page()
    {
        $page = Page::factory()->create();

        $response = $this->delete(route('adminpages.destroy', $page->id));

        $response->assertRedirect(route('adminpages.index'));
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }
}
