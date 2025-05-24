<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Current Borrows</h3>
                    <div class="mt-4">
                        @if($currentBorrows->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($currentBorrows as $borrow)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $borrow->book->title }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Due: {{ $borrow->due_date->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">No current borrows.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Borrow History</h3>
                    <div class="mt-4">
                        @if($borrowHistory->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($borrowHistory as $borrow)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $borrow->book->title }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Returned: {{ $borrow->returned_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">No borrow history.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Overdue Books</h3>
                    <div class="mt-4">
                        @if($overdueBooks->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($overdueBooks as $borrow)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $borrow->book->title }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Due: {{ $borrow->due_date->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">No overdue books.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-ap
