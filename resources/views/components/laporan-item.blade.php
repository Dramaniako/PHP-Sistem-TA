<div class="flex justify-between items-center p-3 bg-gray-50 border rounded mb-2">

    <div class="flex items-center space-x-3">
        @php
            $colors = [
                'pdf' => 'bg-red-500',
                'docx' => 'bg-blue-500',
                'csv' => 'bg-green-500'
            ];
        @endphp

        <div class="w-10 h-10 flex items-center justify-center text-white font-bold {{ $colors[$icon] ?? 'bg-gray-400' }}">
            {{ $format }}
        </div>

        <div>
            <p class="font-medium">{{ $filename }}</p>
            <p class="text-xs text-gray-500">{{ $filesize }}</p>
        </div>
    </div>

    <button class="text-gray-500 hover:text-gray-700">
        ⬇
    </button>

</div>
