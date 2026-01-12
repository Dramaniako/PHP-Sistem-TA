<div class="flex items-center justify-between py-2 border border-gray-100 hover:border-gray-200 transition">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 flex items-center justify-center rounded-sm {{ $icon_color }} text-white font-bold text-xs">
            {{ $format }}
        </div>

        <div>
            <p class="text-gray-800 font-medium leading-tight">Laporan TA</p>
            <p class="text-xs text-gray-500 leading-tight">{{ $filesize }}</p>
        </div>
    </div>

    <button class="text-blue-500 hover:text-blue-700 p-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
</div>
