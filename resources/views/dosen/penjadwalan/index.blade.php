@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Kalender Sidang</h2>

    {{-- Keterangan Warna --}}
    <div class="flex gap-4 mb-4 text-sm">
        <div class="flex items-center gap-2"><div class="w-4 h-4 bg-blue-500 rounded"></div> Tersedia</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 bg-yellow-500 rounded"></div> Menunggu</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 bg-green-500 rounded"></div> Disetujui</div>
    </div>

    {{-- Area Kalender --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <div id="calendar"></div>
    </div>
</div>

{{-- Load Script FullCalendar dari CDN --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Tampilan Default Bulanan
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($events), // Menerima data JSON dari Controller

            // Event ketika jadwal diklik (Opsional)
            eventClick: function(info) {
                alert('Detail: ' + info.event.title);
                // Disini anda bisa buat logic untuk membuka Modal Detail
            }
        });

        calendar.render();
    });
</script>
@endsection