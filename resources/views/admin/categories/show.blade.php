<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Details - {{ $category->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .info-section {
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            display: inline-block;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination svg {
            width: 20px;
        }

        .badge {
            background: #e0e7ff;
            color: #4338ca;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Category: {{ $category->name }}</h1>
            <a href="{{ route('categories.index') }}" class="btn btn-back">Back to List</a>
        </div>

        <div class="info-section">
            <p><strong>Category ID:</strong> #{{ $category->id }}</p>
            <p><strong>Total Books:</strong> <span class="badge">{{ $books->total() }} Books</span></p>
        </div>

        <h3>Books in this Category</h3>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td><strong>{{ $book->title }}</strong></td>
                        <td>{{ $book->author ?? 'Unknown' }}</td>
                        <td>
                            @if($book->is_available)
                                <span style="color: green;">Available</span>
                            @else
                                <span style="color: red;">Borrowed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No books found in this category.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $books->links() }}
        </div>
    </div>

</body>

</html>