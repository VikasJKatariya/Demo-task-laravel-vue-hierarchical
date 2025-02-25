<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminPageController extends Controller
{
    public function index()
    {
        $pages = Page::whereNull('parent_id')->with('children')->get();
        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        $pages = Page::all();
        return view('pages.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
        ]);

        DB::beginTransaction();
        try {
            Page::create([
                'title' => $request->title,
                'slug' => Str::slug($request->slug),
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'is_published' => $request->has('is_published') ? 1 : 0
            ]);

            DB::commit();
            return redirect()->route('adminpages.index')->with('success', 'Page created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create page. Please try again.');
        }
    }

    public function edit(Page $adminpage)
    {
        $pages = Page::where('id', '!=', $adminpage->id)->get();
        return view('pages.edit', compact('adminpage', 'pages'));
    }

    public function update(Request $request, Page $adminpage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $adminpage->id,
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
        ]);

        DB::beginTransaction();
        try {
            $adminpage->update([
                'title' => $request->title,
                'slug' => Str::slug($request->slug),
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'is_published' => $request->has('is_published') ? 1 : 0
            ]);

            DB::commit();
            return redirect()->route('adminpages.index')->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update page. Please try again.');
        }
    }

    public function destroy(Page $adminpage)
    {
        DB::beginTransaction();
        try {
            $adminpage->delete();
            DB::commit();
            return redirect()->route('adminpages.index')->with('success', 'Page deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete page. Please try again.');
        }
    }
}
