<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PATCH') <input type="text" name="name" value="{{ old('name', $role->name) }}">

    @foreach($permissions as $permission)
        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
        {{ $permission->name }}
    @endforeach

    <button type="submit">تحديث</button>
</form>