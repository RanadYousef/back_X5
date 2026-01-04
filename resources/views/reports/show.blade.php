<style>
    .star-gold { color: #FFD700; font-size: 1.2rem; }
    .star-gray { color: #D3D3D3; font-size: 1.2rem; }
    .stat-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #f9f9f9; }
    .grid-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
</style>

<div class="container">
    <h2>التقرير للمكتبة</h2>

    <div class="grid-container">
        <div class="stat-card">
            <h4>متوسط التقييم</h4>
            <p>{{ number_format($avgRating, 1) }} / 5</p>
        </div>
        <div class="stat-card">
            <h4>متوسط الاستعارة</h4>
            <p>{{ number_format($avgBorrowingCount, 2) }} عملية</p>
        </div>
    </div>

    <h3> استعارات لم تُرجع بعد (نشطة)</h3>
    <table border="1" width="100%">
        <thead>
            <tr><th>المستعير</th><th>الكتاب</th><th>تاريخ الاستعارة</th></tr>
        </thead>
        <tbody>
            @foreach($activeBorrowings as $active)
                <tr>
                    <td>{{ $active->user->name }}</td>
                    <td>{{ $active->book->title }}</td>
                    <td>{{ $active->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="grid-container">
        <div>
            <h3>الكتب الأعلى تقييما</h3>
            <ul>
                @foreach($topRatedBooks as $book)
                    <li>
                        {{ $book->title }} 
                        @for($i=1; $i<=5; $i++)
                            <span class="{{ $i <= round($book->overall_rating) ? 'star-gold' : 'star-gray' }}">★</span>
                        @endfor
                    </li>
                @endforeach
            </ul>
        </div>
        <div>
            <h3>الكتب الأكثر استعارة</h3>
            <ul>
                @foreach($mostBorrowed as $book)
                    <li>{{ $book->title }} ({{ $book->borrowings_count }} مرة)</li>
                @endforeach
            </ul>
        </div>
    </div>

    <h3>الزبائن الأكثر استعارة</h3>
    <ul>
        @foreach($topCustomers as $customer)
            <li>{{ $customer->name }} - قام باستعارة {{ $customer->borrowings_count }} كتاب</li>
        @endforeach
    </ul>

    <h3>الكتب المتوفرة و الجاهزة للاستعارة</h3>
    <p> الكتب المتوفرة: <strong>{{ $availableBooks->count() }}</strong> كتاب</p>
</div>