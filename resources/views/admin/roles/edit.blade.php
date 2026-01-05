<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Role</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f9f9f9; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 10px; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .permissions-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; background: #f4f4f4; padding: 15px; border-radius: 5px; }
        .checkbox-item { display: flex; align-items: center; font-size: 14px; }
        .checkbox-item input { margin-right: 8px; }
        .btn-update { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Role: {{ $role->name }}</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf 
        @method('PATCH')
        
        <div class="form-group">
            <label>Role Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}">
            @error('name') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Permissions</label>
            <div class="permissions-grid">
                @foreach($permissions as $permission)
                <div class="checkbox-item">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                        id="p-{{ $permission->id }}"
                        @if(in_array($permission->id, $rolePermissions)) checked @endif>
                    <label for="p-{{ $permission->id }}" style="font-weight: normal; margin-bottom: 0;">{{ $permission->name }}</label>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn-update">Update Role</button>
        <a href="{{ route('roles.index') }}" style="margin-left: 10px; color: #666;">Cancel</a>
    </form>
</div>
</body>
</html>