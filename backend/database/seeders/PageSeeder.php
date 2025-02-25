<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Page::truncate();

        // Define the hierarchical page structure
        $pages = [
            [
                'title' => 'Page 1',
                'slug' => 'page1',
                'content' => 'Page 1 Content',
                'children' => [
                    [
                        'title' => 'Page 2',
                        'slug' => 'page2',
                        'content' => 'Page 2 Content',
                        'children' => [
                            [
                                'title' => 'Page 1 Child',
                                'slug' => 'page1-child',
                                'content' => 'Another Content',
                                'children' => []
                            ],
                            [
                                'title' => 'Page 3',
                                'slug' => 'page3',
                                'content' => 'Page 3 Content',
                                'children' => [
                                    [
                                        'title' => 'Page 4',
                                        'slug' => 'page4',
                                        'content' => 'Page 4 Content',
                                        'children' => []
                                    ],
                                    [
                                        'title' => 'Page 5',
                                        'slug' => 'page5-nested',
                                        'content' => 'Nested Page 5',
                                        'children' => []
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Page 5',
                'slug' => 'page5',
                'content' => 'Root Page 5',
                'children' => []
            ]
        ];

        // Recursively insert pages
        $this->createPages($pages);
    }

    private function createPages(array $pages, ?int $parentId = null)
    {
        foreach ($pages as $pageData) {
            $children = $pageData['children'] ?? [];
            unset($pageData['children']);

            $page = Page::create([
                'parent_id' => $parentId,
                'title' => $pageData['title'],
                'slug' => $pageData['slug'],
                'content' => $pageData['content'],
                'is_published' => true
            ]);

            if (!empty($children)) {
                $this->createPages($children, $page->id);
            }
        }
    }
}
