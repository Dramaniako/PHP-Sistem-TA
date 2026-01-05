<x-app-layout title="Jadwal Sidang Saya">
    <div class="py-12 px-6" x-data="calendarApp()">
        
        {{-- KALENDER --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <div id="calendar"></div>
        </div>

        {{-- MODAL DETAIL & REQUEST PERUBAHAN --}}
        <div x-show="isModalOpen" 
             style="display: none;"
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
                        <p><i class="fas fa-clock w-5"></i> <span x-text="formatDate(selectedEvent.start) + ' - ' + formatDate(selectedEvent.end)"></span></p>
                        <p><i class="fas fa-map-marker-alt w-5"></i> <span x-text="selectedEvent.extendedProps?.lokasi || '-'"></span></p>
                        <p><i class="fas fa-user-tie w-5"></i> <span x-text="selectedEvent.extendedProps?.penguji || '-'"></span></p>
                    </div>

                    {{-- FORM PENGAJUAN (Hanya Tampil Jika Tipe Jadwal = SIDANG) --}}
                    <div x-show="selectedEvent.extendedProps?.tipe_jadwal === 'sidang' && selectedEvent.extendedProps?.is_mine" 
                        class="mt-6 pt-4 border-t border-gray-100">
                        
                        <h4 class="font-bold text-blue-600 text-sm mb-3">Ajukan Perubahan Jadwal Sidang</h4>
                        
                        {{-- Form Action Dinamis --}}
                        <form :action="'{{ url('/mahasiswa/sidang') }}/' + (selectedEvent.extendedProps?.sidang_id) + '/ajukan'" method="POST">
                            @csrf
                            
                            <div class="space-y-4">
                                {{-- 1. Input Tanggal Baru --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700">Saran Tanggal Baru</label>
                                    <input type="date" 
                                        name="tanggal_baru_saran" 
                                        required 
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="text-[10px] text-gray-500 mt-1">*Pilih tanggal setelah hari ini</p>
                                </div>

                                {{-- 2. Input Jam Baru --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700">Saran Jam Baru</label>
                                    <input type="time" 
                                        name="jam_baru_saran" 
                                        required 
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                {{-- 3. Input Alasan --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700">Alasan Perubahan</label>
                                    <textarea name="alasan_perubahan" 
                                            rows="3" 
                                            required 
                                            minlength="10"
                                            placeholder="Jelaskan alasan Anda (minimal 10 karakter)..."
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl transition shadow-lg">
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Opsi: Pesan untuk Jadwal Bimbingan --}}
                    <div x-show="selectedEvent.extendedProps?.tipe_jadwal === 'bimbingan'" class="mt-6 pt-4 border-t border-gray-100">
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
                            if(!this.selectedEvent.extendedProps) this.selectedEvent.extendedProps = {};
                            
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
                    if(!dateObj) return '-';
                    return new Date(dateObj).toLocaleString('id-ID', { 
                        dateStyle: 'medium', timeStyle: 'short' 
                    });
                }
            }
        }
    </script>
</x-app-layout>