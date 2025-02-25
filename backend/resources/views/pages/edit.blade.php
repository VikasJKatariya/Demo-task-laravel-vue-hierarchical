@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Page</h2>
        <form action="{{ route('adminpages.update', $adminpage) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $adminpage->title }}" required>
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ $adminpage->slug }}" required>
            </div>

            <div class="form-group">
                <label>Parent Page</label>
                <select name="parent_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($pages as $page)
                        <option value="{{ $page->id }}" {{ $page->id == $adminpage->parent_id ? 'selected' : '' }}>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control">{{ $adminpage->content }}</textarea>
            </div>

            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" {{ $adminpage->is_published ? 'checked' : '' }}>
                <label>Published</label>
            </div>

            <button type="submit" class="btn btn-success mt-2">Update</button>
            <a href="{{ route('adminpages.index') }}" class="btn btn-secondary mt-2">Cancel</a>
        </form>
    </div>
@endsection
