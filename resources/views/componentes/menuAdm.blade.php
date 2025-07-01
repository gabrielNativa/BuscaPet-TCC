<aside>
    <div class="top">
        <div class="logo">
            <h2>Busca <span class="danger">Pet</span></h2>
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp">close</span>
        </div>
    </div>
  

    <div class="sidebar">
        
        <!-- Dashboard -->
        <a href="/home" id="link-dash" class="{{ request()->is('home') ? 'active' : '' }}">
            <span class="material-icons-sharp">grid_view</span>
            <h3>Dashboard</h3>
        </a>

        <!-- Administração -->
        <a href="/admin" id="link-adm" class="{{ request()->is('admin') || request()->is('admin/*') && !request()->is('admin/ongs*') ? 'active' : '' }}">
            <span class="material-icons-sharp">person_outline</span>
            <h3>Adm</h3>
        </a>
        <!-- Usuário -->
        <a href="/user" id="link-usu" class="{{ request()->is('user*') ? 'active' : '' }}">
            <span class="material-icons-sharp">person_outline</span>
            <h3>Usuário</h3>
        </a>
       
        <!--Campanhas-->

        <a href="/campanha" id="link-usu" class="{{ request()->is('campanha*') ? 'active' : '' }}">
            <span class="material-icons-sharp">campaign</span>
            <h3>Campanhas</h3>
        </a>

        <a href="/denuncias" id="link-usu" class="{{ request()->is('denuncia*') ? 'active' : '' }}">
            <span class="material-icons-sharp">notification_important</span>
            <h3>Denúncias</h3>
        </a>
       

        <a href="{{ route('admin.ongs.pendente') }}"
            class="{{ request()->is('admin/ongs*') ? 'active' : '' }}">
            <span class="material-icons-sharp">check_circle</span>
            <h3>Aprovar ONGs</h3>
            @if(isset($pendingOngsCount) && $pendingOngsCount > 0)
            <span class="badge bg-danger">{{ $pendingOngsCount }}</span>
            @endif
        </a>


        <!-- Logout -->
        <a href="#" class="logout-btn">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-button">
                    <span class="material-icons-sharp">logout</span>
                    <h3 style="margin-left: 8px; font-size: 16px;">Logout</h3>
                </button>
            </form>
        </a>
    </div>
</aside>

<!-- Scripts -->
<script src="{{ asset('js/adm.js') }}"></script>
<script src="{{ asset('js/notifica.js') }}"></script>