@extends('layouts.app')

@section('title', 'Editar ONG')

@section('styles')
<style>
  /* mesmo CSS usado na tela de perfil */
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
  .form-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 0 auto;
  }
  .form-group {
    margin-bottom: 20px;
  }
  .form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
  }
  .form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
  }
  .submit-btn {
    background: linear-gradient(135deg, #1c3f94, #0d2b66);
    color: white;
    border: none;
    padding: 14px 30px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .submit-btn:hover {
    background: linear-gradient(135deg, #0d2b66, #1c3f94);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(28, 63, 148, 0.3);
  }
</style>
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
@endsection

@section('content')
@include('componentes.sidebar')

<div class="main">
  <h2><i class="fas fa-edit icon2"></i> Editar Perfil da ONG</h2>

  <div class="form-card">
    <form method="POST" action="{{ route('ong.update', $ong->idOng) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="fotoOng">Foto da ONG</label>
        <input type="file" name="fotoOng">
      </div>
      <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" name="nome" value="{{ $ong->nomeOng }}" required>
      </div>

      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" name="email" value="{{ $ong->emailOng }}" required>
      </div>

      <div class="form-group">
        <label for="cnpj">CNPJ</label>
        <input type="text" name="cnpj" value="{{ $ong->cnpjOng }}" required>
      </div>

      <div class="form-group">
        <label for="celular">Telefone</label>
        <input type="text" name="celular" value="{{ $ong->telOng }}" required>
      </div>

      <div class="form-group">
        <label for="cep">CEP</label>
        <input type="text" name="cep" value="{{ $ong->cepOng }}" required>
      </div>

      <div class="form-group">
        <label for="endereco">Endereço</label>
        <input type="text" name="endereco" value="{{ $ong->lograOng }}" required>
      </div>

      <div class="form-group">
      <label for="password">Nova Senha (deixe em branco para manter a atual)</label>
      <input type="password" name="password" id="password">
    </div>

    <div class="form-group">
      <label for="password_confirmation">Confirmar Nova Senha</label>
      <input type="password" name="password_confirmation" id="password_confirmation">
    </div>

      

      <button type="submit" class="submit-btn">Salvar Alterações</button>
    </form>
  </div>
</div>
@endsection
