<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-500/15 via-primary-500/5 to-transparent p-8 border border-primary-500/30 shadow-md">
        
        {{-- Dekorasi Cahaya --}}
        <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-primary-500/10 blur-3xl"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center gap-8">
            
            {{-- Ikon Toga Diperbesar --}}
            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-3xl bg-primary-500/20 text-primary-600 shadow-[0_0_20px_rgba(var(--primary-500),0.4)] dark:text-primary-400">
                <x-filament::icon
                    icon="heroicon-o-academic-cap"
                    class="h-16 w-16" 
                />
            </div>

            {{-- Bagian Teks & Instruksi --}}
            <div class="flex-1">
                <h2 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white mb-1">
                    Panduan Evaluasi Kinerja Dosen
                </h2>
                <p class="text-lg font-medium text-primary-600 dark:text-primary-400 mb-6">
                    Sistem Penunjang Keputusan Metode TOPSIS
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Item 1 --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-white/5 border border-white/30 backdrop-blur-md">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-600 text-lg font-bold text-white shadow-lg">1</span>
                        <span class="text-md font-bold text-gray-800 dark:text-gray-200 leading-tight">Skala Penilaian <br> 1 - 5</span>
                    </div>

                    {{-- Item 2 --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-white/5 border border-white/30 backdrop-blur-md">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-600 text-lg font-bold text-white shadow-lg">2</span>
                        <span class="text-md font-bold text-gray-800 dark:text-gray-200 leading-tight">Wajib Isi Semua <br> Kriteria</span>
                    </div>

                    {{-- Item 3 --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-white/40 dark:bg-white/5 border border-white/30 backdrop-blur-md">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-600 text-lg font-bold text-white shadow-lg">3</span>
                        <span class="text-md font-bold text-gray-800 dark:text-gray-200 leading-tight">Berikan Penilaian <br> Objektif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>