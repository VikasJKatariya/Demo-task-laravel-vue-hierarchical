<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Page;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_parent_pages()
    {
        $parentPage = Page::factory()->create(['parent_id' => null]);
        $childPage = Page::factory()->create(['parent_id' => $parentPage->id]);

        $response = $this->getJson(route('pages.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $parentPage->id])
            ->assertJsonMissing(['id' => $childPage->id]); // Ensure only parent pages are listed
    }

    /** @test */
    public function it_returns_page_data_for_valid_nested_slug()
    {
        $parentPage = Page::factory()->create(['slug' => 'parent', 'parent_id' => null]);
        $childPage = Page::factory()->create(['slug' => 'child', 'parent_id' => $parentPage->id]);

        $response = $this->getJson(route('pages.showData', ['path' => 'parent/child']));

        $response->assertStatus(200)
            ->assertJson([
                'id' => $childPage->id,
                'title' => $childPage->title,
                'slug' => 'child',
                'parent_id' => $parentPage->id,
            ]);
    }

    /** @test */
    public function it_returns_404_for_invalid_page_path()
    {
        $response = $this->getJson(route('pages.showData', ['path' => 'nonexistent-page']));

        $response->assertStatus(404)
            ->assertJson(['error' => 'Page not found']);
    }
}
