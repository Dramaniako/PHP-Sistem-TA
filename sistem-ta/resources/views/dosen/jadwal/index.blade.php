<x-app-layout title="Jadwal Sidang Saya">
    <div class="py-12 px-6" x-data="calendarApp()">

        {{-- KALENDER --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <div id="calendar"></div>
        </div>

        {{-- MODAL DETAIL & REQUEST PERUBAHAN --}}
        <div x-show="isModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
            x-transition>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

                {{-- Header Modal --}}
                <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-800" x-text="selectedEvent.title"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-red-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-6 space-y-4">
                    {{-- Detail Info --}}
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-clock w-5"></i> <span
                                x-text="formatDate(selectedEvent.start) + ' - ' + formatDate(selectedEvent.end)"></span>
                        </p>
                        <p><i class="fas fa-map-marker-alt w-5"></i> <span
                                x-text="selectedEvent.extendedProps?.lokasi || '-'"></span></p>
                        <p><i class="fas fa-user-tie w-5"></i> <span
                                x-text="selectedEvent.extendedProps?.penguji || '-'"></span></p>
                    </div>

                    {{-- FORM PENGAJUAN (Hanya Tampil Jika Tipe Jadwal = SIDANG) --}}
                    <div
                        x-show="selectedEvent.extendedProps?.tipe_jadwal === 'sidang' && selectedEvent.extendedProps?.is_mine">
                        <p class="text-sm text-gray-500 italic">
                            *Tidak dapat melakukan perubahan jadwal sidang yang telah ditetapkan.
                        </p>
                    </div>

                    {{-- Opsi: Pesan untuk Jadwal Bimbingan --}}
                    <div x-show="selectedEvent.extendedProps?.tipe_jadwal === 'bimbingan'"
                        class="mt-6 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500 italic">
                            *Untuk perubahan jadwal bimbingan, silakan hubungi dosen pembimbing secara langsung.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script FullCalendar + Alpine --}}
    {{-- Pastikan Anda sudah include CDN FullCalendar di app.blade.php atau disini --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        function calendarApp() {
            return {
                isModalOpen: false,
                selectedEvent: {},

                init() {
                    let calendarEl = document.getElementById('calendar');
                    let calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,listWeek'
                        },
                        // Ambil data events dari Controller
                        events: @json($events),

                        // Event Click Handler
                        eventClick: (info) => {
                            this.selectedEvent = info.event;
                            // Pastikan props ada agar tidak error null
                            if (!this.selectedEvent.extendedProps) this.selectedEvent.extendedProps = {};

                            this.isModalOpen = true;
                        }
                    });
                    calendar.render();
                },

                closeModal() {
                    this.isModalOpen = false;
                    this.selectedEvent = {};
                },

                formatDate(dateObj) {
                    if (!dateObj) return '-';
                    return new Date(dateObj).toLocaleString('id-ID', {
                        dateStyle: 'medium', timeStyle: 'short'
                    });
                }
            }
        }
    </script>
</x-app-layout>