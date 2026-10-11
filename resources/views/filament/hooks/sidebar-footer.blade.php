@php
    $lastLogin = auth()->user()?->last_login_at ?? now();
@endphp

<section 
    x-data 
    :class="{ 'collapsed': !$store.sidebar.isOpen }"
    class="portal-sidebar-last-login" 
    aria-label="Log Masuk Terakhir"
    title="Log Masuk Terakhir: {{ \Carbon\Carbon::parse($lastLogin)->translatedFormat('d M Y, h:i:s A') }}"
>
    <div class="portal-sidebar-login-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="9"></circle>
            <polyline points="12 7 12 12 15 14"></polyline>
        </svg>
    </div>
    
    <div class="portal-sidebar-login-copy" x-show="$store.sidebar.isOpen" x-cloak>
        <small>LOG MASUK TERAKHIR</small>
        <strong>{{ \Carbon\Carbon::parse($lastLogin)->translatedFormat('d M Y') }}</strong>
        <span>{{ \Carbon\Carbon::parse($lastLogin)->format('h:i:s A') }}</span>
    </div>
</section>