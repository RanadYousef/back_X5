<div class="container">
    <h2>إنشاء دور جديد</h2>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 20px;">
            <label>اسم الدور :</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <span style="color:red">{{ $message }}</span> @enderror
        </div>

        <hr>

        <h3>الصلاحيات المتاحة:</h3>
        
        <div style="margin-bottom: 15px;">
             <input type="checkbox" id="select-all"> <strong>تحديد الكل</strong>
        </div>

        <div class="permissions-groups">
            @foreach($permissions as $permission)
                <div style="display: inline-block; width: 200px; margin-bottom: 10px;">
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="permission-checkbox">
                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                    </label>
                </div>
            @endforeach
        </div>

        <hr>

        <button type="submit" style="background: green; color: white; padding: 10px 20px; border: none;">حفظ الدور</button>
        <a href="{{ route('roles.index') }}">إلغاء</a>
    </form>
</div>

<script>
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.getElementsByClassName('permission-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>
@if ($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
        <strong>عذراً! هناك بعض المشاكل:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif