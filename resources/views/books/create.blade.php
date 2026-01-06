@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Add Book</h3>

    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Category --}}
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        {{-- Author --}}
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" name="author" class="form-control" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        {{-- Publish Year --}}
        <div class="mb-3">
            <label class="form-label">Publish Year</label>
            <input type="number" name="publish_year" class="form-control" required>
        </div>

        {{-- Language --}}
        <div class="mb-3">
            <label class="form-label">Language</label>
            <input type="text" name="language" class="form-control" required>
        </div>

        {{-- Copies Number --}}
        <div class="mb-3">
            <label class="form-label">Copies Number</label>
            <input type="number" name="copies_number" class="form-control" min="0" required>
        </div>

        {{-- Cover Image --}}
        <div class="mb-3">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save Book</button>
    </form>
</div>
@endsection