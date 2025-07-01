@extends('layouts.app')

@section('title', $post->title)

@section('styles')
<style>
  body {
    background-color: #f4f7fc;
    font-family: 'Arial', sans-serif;
  }
  .post-detail-container {
    max-width: 500px; /* Reduzido o tamanho do card */
    margin: 0 auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow:  2px 0px 2px 4px #122a66;
  
   
  }



 
  .post-header {
    display: flex;
    align-items: center;
    gap: 15px; /* Reduzido o gap */
    margin-bottom: 25px;
    color: #333;
  }
  .post-header img {
    width: 50px; /* Tamanho da logo reduzido */
    height: 50px;
    
    border-radius: 50%;
  }
  .post-header h1 {
    font-size: 28px; /* Tamanho da fonte ajustado */
    font-weight: 600;
    color: #333;
    letter-spacing: -0.5px;
  }
  .post-title {
    font-size: 24px; /* Tamanho ajustado */
    color: #122a66;
    margin-bottom: 15px;
    font-weight: bold;
    letter-spacing: -0.5px;
  }
  .post-image {
    width: 100%; /* Mantido como 100% */
    height: 250px; /* Altura ajustada */
    box-shadow:  2px 0px 2px 4px #f37324;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
  .post-description {
    font-size: 16px; /* Fonte reduzida */
    line-height: 1.6;
    color: #444;
    margin-bottom: 20px;
    letter-spacing: 0.5px;
  }
  .post-stats {
    display: flex;
    gap: 20px; /* Ajustado o espaçamento */
    margin-bottom: 25px;
    color: #555;
  }
  .post-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px; /* Tamanho ajustado */
  }
  .post-actions {
    display: flex;
    gap: 10px; /* Ajustado o gap */
    margin-top: 20px;
  }
  .btn {
    padding: 10px 20px; /* Ajustado o tamanho do botão */
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s;
  }
  .btn-primary {
    background-color: #f37324;
    color: white;
    border: none;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
  }
  .btn-primary:hover {
    background-color: #d95700;
    transform: translateY(-2px);
  }
  .btn-secondary {
    background-color: #1c3f94;
    color: white;
    border: none;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
  }
  .btn-secondary:hover {
    background-color: #122a66;
    transform: translateY(-2px);
  }
  .back-link {
    display: inline-block;
    margin-top: 25px; /* Ajustado o espaço do link */
    color: #122a66;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px; /* Fonte reduzida */
  }
  .back-link:hover {
    text-decoration: underline;
    transform: translateX(-3px);
  }
</style>
@endsection

@section('content')
<div class="post-detail-container">
  <div class="post-header">
  
    <h1>Post da Campanha</h1>
  </div>
  
  <h2 class="post-title">{{ $post->title }}</h2>
  
  @if($post->image)
  <img src="{{ asset($post->image) }}" alt="Imagem da Campanha" class="post-image">      @endif
  
  <p class="post-description">{{ $post->description }}</p>
  
  <div class="post-stats">
    <span class="post-stat"><i class="fas fa-eye"></i> {{ $post->views }} visualizações</span>
    <span class="post-stat"><i class="fas fa-heart"></i> {{ $post->likes }} curtidas</span>
    <span class="post-stat"><i class="fas fa-bookmark"></i> {{ $post->saves }} salvos</span>
  </div>
  
  <div class="post-actions">
    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">Editar</a>
    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este post?')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-primary">Excluir</button>
    </form>
  </div>
  
  <a href="{{ route('posts.index') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Voltar para todas as campanhas
  </a>
</div>
@endsection
