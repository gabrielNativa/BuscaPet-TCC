<aside>
    <div class="top">
        <div class="logo">

            <h2>Busca<span class="danger">Ong</span> </h2>
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp">close</span>
        </div>
    </div>

    <div class="sidebar">
        <a href="{{ route('ong.dashboard') }}" class="{{ request()->routeIs('ong.dashboard') ? 'active' : '' }}">
            <span class="material-icons-sharp">home</span>
            <h3>Dashboard</h3>

        </a>
        <a href="{{ route('ong.profile') }} " class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">person</span>
            <h3>Perfil</h3>
        </a>

        <a href="{{ route('pets.index') }}" class="{{ request()->routeIs('pets.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">pets</span>
            <h3>Pets</h3>
        </a>


  <a href="{{ route('races.index') }}" class="{{ request()->routeIs('races.*') ? 'active' : '' }}">
       <span class="material-icons-sharp">pets</span>
            <h3>Raças</h3>
    </a>


        <a href="#" class="logout-btn">
            <form action="{{ route('ong.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-button">
                    <span class="material-icons-sharp">logout</span>
                    <h3 style="margin-left: 8px; font-size: 16px;">Logout</h3>
                </button>
            </form>
        </a>

    </div>
</aside>

<script src="./../js/adm.js"></script>