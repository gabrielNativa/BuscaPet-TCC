<div class="right">

    <header class="header-adm">
        <div class="top">
            <button id="menu-btn">
                <span class="material-icons-sharp">menu</span>
            </button>
           
            <div class="profile">
                @php
                    $admin = Auth::user(); 
                @endphp

            @if($admin)
                <div class="info">
                    <p>Olá, <b>{{ $admin->nomeAdm }}</b></p>
                    <small class="text-muted">Admin</small>
                </div>
                <div class="profile-photo">
                @if($admin->imgAdm)
                <img src="{{ asset('img/imgAdm/' . $admin->imgAdm) }}" alt="Foto do Admin">
                @else
                    <img src="{{ asset('img/imgAdm/perfil.png') }}" alt="Foto Padrão">
                @endif
            @endif
                </div>
            </div>
        </div>
    </header>

    <script src=".././js/dark.js"></script>