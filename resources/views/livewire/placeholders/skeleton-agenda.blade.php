<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex gap-4">
        <div class="h-4 bg-gray-200 rounded w-1/6 animate-pulse"></div>
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
    </div>

    <div class="divide-y divide-gray-200">
        @foreach(range(1, 4) as $i)
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4 w-1/3">
                <div class="h-10 w-10 bg-gray-200 rounded-lg animate-pulse"></div> <div class="space-y-2 flex-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                    <div class="h-3 bg-gray-100 rounded w-1/2 animate-pulse"></div>
                </div>
            </div>
            <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div> <div class="h-6 bg-gray-200 rounded w-20 animate-pulse"></div> </div>
        @endforeach
    </div>
</div>