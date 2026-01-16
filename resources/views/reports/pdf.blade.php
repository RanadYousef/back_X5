<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; text-align: right; font-size: 12px; }
        .section-title { background: #eee; padding: 5px; margin-top: 20px; border-right: 5px solid #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        .highlight { color: #2c3e50; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">تقرير المكتبة الشامل (بيانات ديناميكية)</h2>
    <p style="text-align:center;">الفترة: {{ $start }} إلى {{ $end }}</p>

    <div class="section-title">إحصائيات عامة</div>
    <p>متوسط التقييم العام: <span class="highlight">{{ number_format($avgRating, 1) }} ⭐</span></p>
    <p>معدل الاستعارة لكل كتاب: <span class="highlight">{{ number_format($avgBorrowingCount, 2) }}</span></p>
    <p>عدد الكتب المتاحة حالياً: <span class="highlight">{{ $availableBooks->count() }}</span></p>

    <div class="section-title">أعلى الكتب تقييماً vs الأقل تقييماً</div>
    <table>
        <tr><th>الكتاب (الأعلى)</th><th>التقييم</th><th>الكتاب (الأقل)</th><th>التقييم</th></tr>
        @foreach($topRatedBooks as $key => $book)
        <tr>
            <td>{{ $book->title }}</td><td>{{ number_format($book->reviews_avg_rating, 1) }}</td>
            <td>{{ $lowestRatedBooks[$key]->title ?? '-' }}</td><td>{{ number_format($lowestRatedBooks[$key]->reviews_avg_rating ?? 0, 1) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="section-title">أكثر الكتب استعارة في هذه الفترة</div>
    <table>
        <thead><tr><th>اسم الكتاب</th><th>عدد الاستعارات</th></tr></thead>
        <tbody>
            @foreach($mostBorrowed as $book)
            <tr><td>{{ $book->title }}</td><td>{{ $book->borrows_count }} مرة</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">أفضل 5 مستخدمين (أكثر استعارة)</div>
    <table>
        <thead><tr><th>المستخدم</th><th>إجمالي الاستعارات</th></tr></thead>
        <tbody>
            @foreach($topCustomers as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->borrowings_count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">الاستعارات النشطة حالياً (لم تُرجع بعد)</div>
    <table>
        <thead><tr><th>المستعير</th><th>الكتاب</th><th>تاريخ الاستعارة</th></tr></thead>
        <tbody>
            @foreach($activeBorrowings as $borrow)
            <tr><td>{{ $borrow->user->name }}</td><td>{{ $borrow->book->title }}</td><td>{{ $borrow->created_at->format('Y-m-d') }}</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>