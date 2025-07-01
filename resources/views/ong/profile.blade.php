@extends('layouts.app')

@section('title', 'Perfil da ONG')

@section('styles')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', Arial, sans-serif;
  }
  body {
    display: flex;
    height: 100vh;
    background-color: #f7f9fc;
    overflow-x: hidden;
  }
  .main {
    flex-grow: 1;
    padding: 30px;
    margin-left: 250px;
    width: calc(100% - 250px);
    overflow-y: auto;
    height: 100vh;
  }
  .main h2 {
    font-size: 32px;
    color: #1c3f94;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 15px;
    font-weight: 600;
  }
  .profile-container {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
  }
  .profile-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    flex: 1;
  }
  .profile-header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    gap: 25px;
  }
  .profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #f0f0f0;
  }
  .profile-info h3 {
    font-size: 24px;
    color: #1c3f94;
    margin-bottom: 5px;
  }
  .profile-info p {
    color: #666;
    margin-bottom: 10px;
  }
  .status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .status-pendente {
    background-color: #FFF3CD;
    color: #856404;
  }
  .status-ativo {
    background-color: #D4EDDA;
    color: #155724;
  }
  .status-inativo {
    background-color: #F8D7DA;
    color: #721C24;
  }
  .profile-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
  .detail-item {
    margin-bottom: 15px;
  }
  .detail-item label {
    display: block;
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
    font-size: 14px;
  }
  .detail-item p {
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #333;
    font-size: 15px;
  }
  .edit-btn {
    background: linear-gradient(135deg, #1c3f94, #0d2b66);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 20px;
  }
  .edit-btn:hover {
    background: linear-gradient(135deg, #0d2b66, #1c3f94);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(28, 63, 148, 0.3);
  }
  .top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
  }
  .search-box {
    border: 2px solid #1c3f94;
    padding: 10px 20px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
  }
  .search-box:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  }
  .search-box input {
    border: none;
    outline: none;
    margin-left: 10px;
    background: transparent;
    font-size: 16px;
    width: 200px;
  }
  .search-box i {
    color: #1c3f94;
  }
  .icon2 {
    font-size: 28px;
    color: #1c3f94;
  }
</style>
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
@endsection

@section('content')
@include('componentes.sidebar')

<div class="main">
  <div class="top-row">
    <h2><i class="fas fa-user-circle icon2"></i> Perfil da ONG</h2>
  
  </div>

  <div class="profile-container">
    <div class="profile-card">
      <div class="profile-header">
      <img class="profile-image" src="{{ asset($ong->fotoOng) }}" alt="Foto da ONG" >
        <div class="profile-info">
          <h3>{{ $ong->nomeOng }}</h3>
          <p>{{ $ong->emailOng }}</p>
          <span class="status-badge status-{{ strtolower($ong->status) }}">
            {{ ucfirst($ong->status) }}
          </span>
        </div>
      </div>

      <div class="profile-details">
        <div class="detail-item">
          <label>CNPJ</label>
          <p>{{ $ong->cnpjOng }}</p>
        </div>
        <div class="detail-item">
          <label>Telefone</label>
          <p>{{ $ong->telOng }}</p>
        </div>
        <div class="detail-item">
          <label>CEP</label>
          <p>{{ $ong->cepOng }}</p>
        </div>
        <div class="detail-item">
          <label>Endereço</label>
          <p>{{ $ong->lograOng }}, {{ $ong->numLograOng ?? 'S/N' }}</p>
        </div>
      
      </div>

      <button class="edit-btn" onclick="window.location.href='{{ route('ong.edit', $ong->idOng) }}'">
        <i class="fas fa-edit"></i> Editar Perfil
      </button>
    </div>
  </div>
</div>
@endsection