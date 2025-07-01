<div class="right">

    <header class="header-adm">
        <div class="top">
            <button id="menu-btn">
                <span class="material-icons-sharp">menu</span>
            </button>
            <div class="theme-toggler">
                <span class="material-icons-sharp active" id="light">light_mode</span>
                <span class="material-icons-sharp" id="dark">dark_mode</span>
            </div>
            <div class="profile">
                @php
                    $ong = Auth::user();
                @endphp
                    <div class="info">
                        <p>Olá, <b>{{ $ong->nomeOng }}</b></p>
                        <small class="text-muted">ONG</small>
                    </div>
                </div>
        </div>
    </header>

    <script src=".././js/dark.js"></script>