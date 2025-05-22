<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($books as $book)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $book->title }}</h3>
                                    <p class="text-gray-600 mb-4">{{ Str::limit($book->description, 100) }}</p>
                                    
                                    <div class="mb-4">
                                        <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full">
                                            {{ $book->category->name }}
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Authors:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($book->authors as $author)
                                                <span class="inline-block bg-gray-100 text-gray-800 text-sm px-2 py-1 rounded-full">
                                                    {{ $author->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">ISBN: {{ $book->isbn }}</span>
                                        <span class="text-sm text-gray-500">Available: {{ $book->quantity }}</span>
                                    </div>

                                    <div class="mt-4 flex justify-end space-x-2">
                                        <a href="{{ route('books.show', $book) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            View Details
                                        </a>
                                        <form action="{{ route('books.borrow.request', $book) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Borrow
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No books available</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by adding a new book.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 