@extends('layouts.app')

@section('title', 'Editar Post')

@section('styles')
<style>
  body {
    background-color: #f9f9f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    padding: 20px;
  }

  .post-form-container {
    background-color: #fff;
    border-radius: 12px;
    box-shadow:  2px 0px 2px 4px #122a66;
    padding: 25px;
    width: 100%;
    max-width: 800px;
    display: flex;
    align-items: flex-start;
    gap: 20px;
    border: 1px solid #ddd;
    transition: all 0.3s ease-in-out;
  }

  .post-image {
    width: 400px;
    height: 230px;
    flex-shrink: 0;
    box-shadow:  2px 0px 2px 4px #122a66;
    border-radius: 10px;
    justify-content: center;
    align-items: center;
    display: flex;
    overflow: hidden;
    border: 1px solid #ddd;
  }

  .post-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .form-container {
    flex-grow: 1;
    width: 100%;
  }

  .post-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 20px;
  }

  .post-header h2 {
    font-size: 22px;
    color: #222;
    margin: 0;
  }

  .input-container, .select-container {
    margin-bottom: 18px;
  }

  .input-container label,
  .select-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
  }

  .input-container input[type="text"],
  .input-container textarea,
  .select-container select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
    background-color: #fdfdfd;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .input-container input:focus,
  .input-container textarea:focus,
  .select-container select:focus {
    border-color: #122a66;
    box-shadow: 0 0 0 3px rgba(243, 115, 36, 0.2);
    outline: none;
  }

  textarea {
    height: 50px;
    resize: vertical;
  }

  .file-input {
    margin-bottom: 18px;
  }

  .file-input input[type="file"] {
    width: 100%;
    padding: 8px;
    border-radius: 10px;
    border: 1px solid #ccc;
    background-color: #fff;
  }

  .submit-btn {
    width: 100%;
    padding: 12px;
    background-color: #122a66;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .submit-btn:hover {
    background-color: #122a66;
  }

  .back-link {
    display: inline-flex;
    align-items: center;
    margin-top: 20px;
    color: #122a66;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
  }

  .back-link i {
    margin-right: 8px;
  }

  .back-link:hover {
    color: #122a66;
  }

  .image-preview {
    margin-top: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    background-color: #f5f5f5;
    max-width: 400px;
    max-height: 230px;
  }

  .image-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 10px;
  }
</style>
@endsection

@section('content')
<div class="post-form-container">
  <div class="post-image">
    <div class="image-preview" id="imagePreview">
      @if($post->image)
        <img src="{{ asset($post->image) }}" alt="Imagem da Campanha">
      @endif
    </div>
  </div>

  <div class="form-container">
    <div class="post-header">
      <h2>Editar Campanha</h2>
    </div>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="file-input">
        <label for="image">Nova Imagem (opcional)</label>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
      </div>

      <div class="input-container">
        <label for="title">Título da Campanha</label>
        <input type="text" id="title" name="title" value="{{ $post->title }}" required>
      </div>

      <div class="input-container">
        <label for="description">Descrição</label>
        <textarea id="description" name="description" required>{{ $post->description }}</textarea>
      </div>

      <div class="select-container">
        <label for="idCategoriaPosts">Categoria</label>
        <select id="idCategoriaPosts" name="idCategoriaPosts" required>
          <option value="">Selecione uma categoria</option>
          @foreach($categorias as $categoria)
            <option value="{{ $categoria->idCategoriaPosts }}" 
              {{ $post->idCategoriaPosts == $categoria->idCategoriaPosts ? 'selected' : '' }}>
              {{ $categoria->categoriaPosts }}
            </option>
          @endforeach
        </select>
      </div>

      <button type="submit" class="submit-btn">Atualizar Campanha</button>
    </form>

    <a href="{{ route('posts.index') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Voltar para todas as campanhas
    </a>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function previewImage(event) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';

    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function () {
        const imgElement = document.createElement('img');
        imgElement.src = reader.result;
        previewContainer.appendChild(imgElement);
      }
      reader.readAsDataURL(file);
    }
  }
</script>
@endsection