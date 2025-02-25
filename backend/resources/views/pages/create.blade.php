@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Page</h2>
        <form action="{{ route('adminpages.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Parent Page</label>
                <select name="parent_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($pages as $page)
                        <option value="{{ $page->id }}">{{ $page->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control"></textarea>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" checked>
                <label>Published</label>
            </div>
            <button type="submit" class="btn btn-success mt-2">Create</button>
        </form>
    </div>
@endsection
