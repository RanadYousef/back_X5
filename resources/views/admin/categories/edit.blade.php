{{-- عرض رسالة خطأ عامة إذا وجدت --}}
@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

<h2>تعديل التصنيف: {{ $category->name }}</h2>

<form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT') 

    <div>
        <label for="name">الاسم الجديد:</label>
        {{-- نستخدم  لتذكر القيمة الجديدة في حال فشل الفحص أوالقيمة الأصلية من القاعدة :old --}}
        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
        
        @error('name')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <br>
    <button type="submit">تحديث البيانات</button>
    <a href="{{ route('categories.index') }}">رجوع</a>
</form>