{{-- عرض رسالة خطأ عامة إذا وجدت --}}
@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

<h2>إضافة نوع كتاب جديد</h2>

<form action="{{ route('categories.store') }}" method="POST">
    @csrf

    <div>
        <label for="name">اسم التصنيف:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        
        {{-- عرض خطأ التحقق الخاص بالحقل --}}
        @error('name')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <br>
    <button type="submit">حفظ التصنيف</button>
    <a href="{{ route('categories.index') }}">إلغاء</a>
</form>