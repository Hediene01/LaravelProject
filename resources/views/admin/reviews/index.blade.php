@extends('layouts.admin')

@section('title', 'Moderate Reviews | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Moderation</span>
            <h1>Reviews</h1>
            <p>Approve, reject, or keep reviews pending before they appear on product pages.</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->product?->name }}</td>
                            <td>{{ $review->user?->name }}</td>
                            <td>{{ $review->rating }}/5</td>
                            <td>{{ $review->title ?: \Illuminate\Support\Str::limit($review->body, 60) }}</td>
                            <td>
                                <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="admin-inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach (['pending', 'approved', 'rejected'] as $status)
                                            <option value="{{ $status }}" @selected($review->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="button button-light">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No reviews available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $reviews->links() }}
        </div>
    </section>
@endsection
