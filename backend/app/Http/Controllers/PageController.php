<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class PageController extends Controller
{
    public function index(): JsonResponse {
        try {
            $pages = Page::whereNull('parent_id')->with('children')->get();
            return response()->json($pages);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showData($path)
    {
        $slugs = explode('/', $path);
        $parent = null;

        foreach ($slugs as $slug) {
            $page = Page::where('slug', $slug)
                ->where('parent_id', $parent)
                ->first();

            if (!$page) {
                return response()->json(['error' => 'Page not found'], 404);
            }

            $parent = $page->id;
        }

        return response()->json([
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
            'parent_id' => $page->parent_id,
        ]);
    }
}
