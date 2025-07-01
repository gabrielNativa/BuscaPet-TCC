@extends('layouts.app')

@section('title', 'Criar Post')

@section('styles')
<style>
  body {
    background-color: #f9f9f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    padding: 20px;
  }

  .post-form-container {
    background-color: #fff;
    border-radius: 12px;
    box-shadow:  2px 0px 2px 4px #122a66;
    padding: 25px;
    width: 100%;
    max-width: 800px; /* Reduzido o tamanho do card */   
    display: flex;
    align-items: flex-start;
    gap: 20px;
    border: 1px solid #ddd;
    transition: all 0.3s ease-in-out;
  }

  .post-image {
    width: 400px; /* Tamanho ajustado */
    height: 230px; /* Tamanho ajustado */
    flex-shrink: 0;
    box-shadow:  2px 0px 2px 4px #122a66;
    border-radius: 10px;
    overflow: hidden;
    align-items: center;
    justify-content: flex-end;
    display: flex;
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
    font-size: 22px; /* Tamanho reduzido */
    color: #222;
    margin: 0;
  }

  .input-container {
    margin-bottom: 18px; /* Reduzido o espaçamento */
  }

  .input-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
  }

  .input-container input[type="text"],
  .input-container textarea {
    width: 100%;
    padding: 12px; /* Reduzido o padding */
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px; /* Tamanho da fonte reduzido */
    background-color: #fdfdfd;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .input-container input:focus,
  .input-container textarea:focus {
    border-color: #122a66;
    box-shadow: 0 0 0 3px rgba(243, 115, 36, 0.2);
    outline: none;
  }

  textarea {
    height: 50px; /* Diminuído a altura da textarea */
    resize: vertical;
  }

  .file-input {
    margin-bottom: 18px; /* Ajustado o espaçamento */
  }

  .file-input input[type="file"] {
    width: 100%;
    padding: 8px; /* Ajustado o padding */
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
    font-size: 16px; /* Tamanho da fonte reduzido */
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
    max-width: 400px;
    max-height: 230px;
    background-color: #f5f5f5; /* Fundo para quando a imagem não preencher todo o espaço */
    border-radius: 10px;
}

  .image-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Mantém a proporção e mostra a imagem inteira */
    border-radius: 10px;
    border: 1px solid #eee;
    background-color: #f5f5f5; /* Fundo para imagens com transparência ou não preencherem todo o espaço */
}
.select-container {
    margin-bottom: 18px;
  }
  
  .select-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
  }
  
  .select-container select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
    background-color: #fdfdfd;
    transition: border-color 0.3s, box-shadow 0.3s;
  }
  
  .select-container select:focus {
    border-color: #122a66;
    box-shadow: 0 0 0 3px rgba(243, 115, 36, 0.2);
    outline: none;
  }
</style>
@endsection

@section('content')
<div class="post-form-container">
  <div class="post-image">
    <div class="image-preview" id="imagePreview"></div>
  </div>

  <div class="form-container">
    <div class="post-header">
      <h2>Publicar Nova Campanha</h2>
    </div>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="file-input">
        <label for="image">Imagem da Campanha</label>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
      </div>
      
      <div class="input-container">
        <label for="title">Título da Campanha</label>
        <input type="text" id="title" name="title" required>
      </div>

      <div class="input-container">
        <label for="description">Descrição</label>
        <textarea id="description" name="description" required></textarea>
      </div>

      <div class="select-container">
        <label for="idCategoriaPosts">Categoria</label>
        <select id="idCategoriaPosts" name="idCategoriaPosts" required>
          <option value="">Selecione uma categoria</option>
          @foreach($categorias as $categoria)
            <option value="{{ $categoria->idCategoriaPosts }}">{{ $categoria->categoriaPosts }}</option>
          @endforeach
        </select>
      </div>

      <button type="submit" class="submit-btn">📢 Publicar Post</button>
    </form>

    <a href="{{ route('posts.index') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Voltar para todos os posts
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