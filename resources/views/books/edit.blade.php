@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Book</h3>

    <form method="POST"
          action="{{ route('books.update', $book) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Category --}}
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $book->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title', $book->title) }}"
                   required>
        </div>

        {{-- Author --}}
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text"
                   name="author"
                   class="form-control"
                   value="{{ old('author', $book->author) }}"
                   required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description"
                      class="form-control"
                      rows="4"
                      required>{{ old('description', $book->description) }}</textarea>
        </div>

        {{-- Publish Year --}}
        <div class="mb-3">
            <label class="form-label">Publish Year</label>
            <input type="number"
                   name="publish_year"
                   class="form-control"
                   value="{{ old('publish_year', $book->publish_year) }}"
                   required>
        </div>

        {{-- Language --}}
        <div class="mb-3">
            <label class="form-label">Language</label>
            <input type="text"
                   name="language"
                   class="form-control"
                   value="{{ old('language', $book->language) }}"
                   required>
        </div>

        {{-- Copies Number --}}
        <div class="mb-3">
            <label class="form-label">Copies Number</label>
            <input type="number"
                   name="copies_number"
                   class="form-control"
                   min="0"
                   value="{{ old('copies_number', $book->copies_number) }}"
                   required>
        </div>

        {{-- Current Cover --}}
        @if($book->cover_image)
            <div class="mb-3">
                <label class="form-label">Current Cover</label><br>
                <img src="{{ asset('storage/' . $book->cover_image) }}"
                     width="120"
                     class="mb-2">
            </div>
        @endif

        {{-- Replace Cover --}}
        <div class="mb-3">
            <label class="form-label">Replace Cover Image</label>
            <input type="file"
                   name="cover_image"
                   class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">
            Update Book
        </button>
    </form>
</div>
@endsection