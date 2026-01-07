@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-yellow-400">Reviews Management</h2>
        <p class="text-gray-400 text-sm">Manage and moderate book reviews</p>
    </div>

    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">

        <table class="w-full text-sm text-left text-gray-300">
            <thead class="bg-gray-900 text-yellow-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Book</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Comment</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-700">
                @foreach($reviews as $review)
                <tr class="hover:bg-gray-700 transition">
                    <td class="px-6 py-4">{{ $review->id }}</td>
                    <td class="px-6 py-4">{{ $review->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $review->book->title ?? '-' }}</td>
                    <td class="px-6 py-4 text-yellow-400 font-semibold">
                        {{ $review->rating }}/5
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($review->comment, 40) }}</td>

                    <td class="px-6 py-4">
                        <span class="
                            px-3 py-1 rounded-full text-xs font-semibold
                            {{ $review->status === 'approved' ? 'bg-green-600 text-white' : '' }}
                            {{ $review->status === 'pending' ? 'bg-yellow-500 text-black' : '' }}
                            {{ $review->status === 'rejected' ? 'bg-red-600 text-white' : '' }}
                        ">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 flex gap-2">
                        <form method="POST" action="{{ route('reviews.approve', $review) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-3 py-1 bg-green-600 rounded hover:bg-green-700 text-xs">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('reviews.reject', $review) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-3 py-1 bg-yellow-500 rounded hover:bg-yellow-600 text-xs text-black">
                                Reject
                            </button>
                        </form>

                        <form method="POST" action="{{ route('reviews.destroy', $review) }}">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 rounded hover:bg-red-700 text-xs">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
