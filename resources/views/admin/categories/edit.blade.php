<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f9f9f9; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { text-decoration: none; padding: 10px 20px; border-radius: 4px; font-size: 14px; cursor: pointer; border: none; display: inline-block; }
        .btn-update { background-color: #007bff; color: white; }
        .btn-back { background-color: #6c757d; color: white; margin-right: 10px; }
        .error-message { color: #dc3545; font-size: 13px; margin-top: 5px; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Edit Category</h1>
    </div>

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" placeholder="Enter category name">
            
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('categories.index') }}" class="btn btn-back">Cancel</a>
            <button type="submit" class="btn btn-update">Update Category</button>
        </div>
    </form>
</div>

</body>
</html>