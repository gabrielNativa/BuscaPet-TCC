<div class="sidebar">
  <img src="{{ asset('img/logo 3.png') }}" alt="BuscaPet Logo">

  <div class="menu-group">
    <a href="{{ route('ong.dashboard') }}" class="{{ request()->routeIs('ong.dashboard') ? 'active' : '' }}">
      <i class="fas fa-home icon"></i> Home
    </a>
    <a href="{{ route('ong.profile') }} " class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
      <i class="fas fa-user icon"></i> 
      Perfil</a>


    <a href="{{ route('pets.index') }}" class="{{ request()->routeIs('pets.*') ? 'active' : '' }}">
      <i class="fas fa-paw icon"></i> Pets
    </a>
  
    <form action="{{ route('ong.logout') }}" method="POST" style="width: 100%;">
      @csrf
      <button type="submit" class="btn-link-style">
        <i class="fas fa-sign-out-alt icon"></i> Sair
      </button>
    </form>
  </div>
</div>