@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $book->title }}</h1>
    <div class="mb-3">
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">Edit</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">ISBN: {{ $book->isbn }}</h5>
            <p class="card-text"><strong>Category:</strong> {{ $book->category->name ?? '-' }}</p>
            <p class="card-text"><strong>Authors:</strong> 
                @foreach($book->authors as $author)
                    <span class="badge bg-secondary">{{ $author->name }}</span>
                @endforeach
            </p>
            <p class="card-text"><strong>Quantity:</strong> {{ $book->quantity }}</p>
            <p class="card-text"><strong>Publisher:</strong> {{ $book->publisher }}</p>
            <p class="card-text"><strong>Publication Year:</strong> {{ $book->publication_year }}</p>
            <p class="card-text"><strong>Location:</strong> {{ $book->location }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $book->description }}</p>
        </div>
    </div>
</div>
@endsection 