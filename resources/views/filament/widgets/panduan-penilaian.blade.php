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

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    {{-- Item 1 --}}
                    <div class="flex flex-col gap-3 p-5 rounded-3xl bg-white/80 dark:bg-slate-900/95 border border-slate-200/70 dark:border-slate-700 shadow-lg">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-500 text-base font-semibold text-white shadow-md">1.</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">Skala Penilaian 1 - 5</span>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Nilai setiap sub-kriteria dengan jujur dan konsisten.</p>
            
                    </div>
 
                    {{-- Item 2 --}}
                    <div class="flex flex-col gap-3 p-5 rounded-3xl bg-white/80 dark:bg-slate-900/95 border border-slate-200/70 dark:border-slate-700 shadow-lg">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500 text-base font-semibold text-white shadow-md">2.</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">Wajib Isi Semua Sub-Kriteria</span>
                              <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Setiap sub-kriteria dosen harus dinilai agar hasil lebih akurat.</p>
                        
                    </div>

                    {{-- Item 3 --}}
                    <div class="flex flex-col gap-3 p-5 rounded-3xl bg-white/80 dark:bg-slate-900/95 border border-slate-200/70 dark:border-slate-700 shadow-lg">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-500 text-base font-semibold text-white shadow-md">3.</span>
                        
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">Berikan Penilaian Objektif</span>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Nilai berdasarkan kenyataan dan performa dosen.</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>