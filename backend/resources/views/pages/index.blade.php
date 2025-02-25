@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Page List</h2>
        <a href="{{ route('adminpages.create') }}" class="btn btn-primary">Create New Page</a>

        <ul class="list-group mt-3">
            @foreach ($pages as $page)
                <li class="list-group-item">
                    <strong>{{ $page->title }}</strong> ({{ $page->slug }})
                    <a href="{{ route('adminpages.edit', $page->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('adminpages.destroy', $page->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    @if ($page->children->count())
                        <ul class="list-group mt-2">
                            @foreach ($page->children as $child)
                                <li class="list-group-item">
                                    ├── {{ $child->title }} ({{ $child->slug }})
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
