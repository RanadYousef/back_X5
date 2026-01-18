@extends('layouts.admin')

@section('content')

<style>
.card-glass{
    background: rgba(30,30,30,0.85);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    border: 1px solid rgba(255,193,7,0.3);
    transition: all 0.3s ease;
}
.card-glass:hover{
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(255,193,7,0.25);
}

h4{
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-select.bg-dark{
    transition: all 0.3s;
}
.form-select.bg-dark:focus{
    border-color: #ffc107;
    box-shadow: 0 0 8px rgba(255,193,7,0.4);
}

.btn-warning{
    transition: all 0.25s ease;
}
.btn-warning:hover{
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,193,7,0.3);
}

.btn-outline-warning{
    transition: all 0.25s ease;
}
.btn-outline-warning:hover{
    background-color: rgba(255,193,7,0.1);
    transform: translateY(-2px);
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card-glass fade-in">

                <h4 class="mb-4">
                    <i class="bi bi-plus-circle text-warning"></i> محادثة جديدة
                </h4>

                <form action="{{ route('chat.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="employee_id" class="form-label text-light">
                            <i class="bi bi-person-fill"></i> اختر المستخدم
                        </label>

                        <select name="employee_id"
                                id="employee_id"
                                class="form-select bg-dark text-light border-warning @error('employee_id') is-invalid @enderror"
                                required>
                            <option value="">-- اختر مستخدماً --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('employee_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>

                        @error('employee_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1">
                            <i class="bi bi-check-circle"></i> إنشاء محادثة
                        </button>

                        <a href="{{ route('chat.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-left"></i> رجوع
                        </a>
                    </div>

                </form>

                @if($users->count() == 0)
                    <div class="alert alert-info mt-4 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle"></i>
                        لا يوجد مستخدمين آخرين لإنشاء محادثة معهم
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection