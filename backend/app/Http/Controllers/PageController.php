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

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:pages,id',
            'slug' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $page = DB::transaction(function () use ($data) {
                return Page::create($data);
            });
            return response()->json($page, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse {
        try {
            $page = Page::with('children')->findOrFail($id);
            return response()->json($page);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:pages,id',
            'slug' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $page = DB::transaction(function () use ($data, $id) {
                $page = Page::findOrFail($id);
                $page->update($data);
                return $page;
            });
            return response()->json($page);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse {
        try {
            DB::transaction(function () use ($id) {
                Page::findOrFail($id)->delete();
            });
            return response()->json(['message' => 'Page deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resolvePage(...$slugs): JsonResponse {
        try {
            $parent = null;
            foreach ($slugs as $slug) {
                $query = Page::where('slug', $slug);
                $query = $parent ? $query->where('parent_id', $parent->id) : $query->whereNull('parent_id');
                $parent = $query->firstOrFail();
            }
            return response()->json($parent);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
