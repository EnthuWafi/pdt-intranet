<style>
    .portal-banner {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 68%, #fbf7df 100%);
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        background-color: #2b2b2b;
    }
</style>

<div class="portal-banner border border-gray-200 rounded-2xl shadow-sm p-6 mb-6 flex flex-col lg:flex-row items-center justify-between gap-6">
    
    <div class="flex items-center gap-5">
        <img src="/images/perak.webp" alt="Crest" class="h-24 w-24 object-contain drop-shadow-md">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Portal Intranet PDT Manjung</h1>
            <p class="text-sm font-semibold text-gray-600 mb-2">Pejabat Daerah dan Tanah Manjung</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-300">
                Version 1
            </span>
        </div>
    </div>

    <!-- RIGHT: Profile Card & Action Buttons -->
    <div class="flex items-center gap-4">
        
        <!-- Profile Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm flex items-center gap-4 max-w-sm">
            <div class="h-12 w-12 rounded-full bg-gray-200 border-2 border-yellow-400 overflow-hidden shrink-0">
                <!-- Fallback icon, replace with auth()->user()->avatar_url later -->
                <svg class="w-full h-full text-gray-400 mt-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <strong class="text-sm text-gray-900 truncate block">Hi, {{ auth()->user()->name ?? 'Amirah binti Abdul Rashid' }}</strong>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-50 border border-green-200 text-[10px] font-bold text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> DALAM TALIAN
                    </span>
                </div>
                <p class="text-[10px] text-gray-500 leading-tight">
                    {{ auth()->user()->roles->first()->name ?? 'Pentadbir Utama' }}<br>
                    {{ now()->format('l, d F Y') }}
                </p>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex gap-2">
            <button class="action-btn bg-[#222222] text-white rounded-xl p-3 flex flex-col items-center justify-center w-[72px] h-[80px]">
                <div class="w-8 h-8 rounded-lg bg-yellow-400 text-black flex items-center justify-center mb-1 font-bold">?</div>
                <span class="text-[9px] font-bold tracking-wider">BANTUAN</span>
            </button>
            <button class="action-btn bg-[#222222] text-white rounded-xl p-3 flex flex-col items-center justify-center w-[72px] h-[80px]">
                <div class="w-8 h-8 rounded-lg bg-yellow-400 text-black flex items-center justify-center mb-1 font-bold">≡</div>
                <span class="text-[9px] font-bold tracking-wider">FAQ</span>
            </button>
            <button class="action-btn bg-[#222222] text-white rounded-xl p-3 flex flex-col items-center justify-center w-[72px] h-[80px]">
                <div class="w-8 h-8 rounded-lg bg-yellow-400 text-black flex items-center justify-center mb-1 font-bold">☎</div>
                <span class="text-[9px] font-bold tracking-wider">HUBUNGI</span>
            </button>
        </div>

    </div>
</div>