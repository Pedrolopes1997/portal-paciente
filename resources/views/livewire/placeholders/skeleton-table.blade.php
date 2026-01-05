<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex gap-4">
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
    </div>

    <div class="divide-y divide-gray-100">
        @foreach(range(1, 5) as $i)
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="space-y-2 w-full">
                <div class="h-4 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                <div class="h-3 bg-gray-100 rounded w-1/6 animate-pulse"></div>
            </div>
            <div class="h-8 bg-gray-200 rounded w-24 animate-pulse"></div>
        </div>
        @endforeach
    </div>
</div>