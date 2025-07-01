@extends('layouts.app')

@section('title', 'Posts das Campanhas')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body,
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        height: 100%;
    }

    .main {
        display: flex;
        min-height: 100vh;
        flex-wrap: nowrap;
    }

    .content-area {
        margin-left: 250px; /* Ajuste conforme a largura da sua sidebar */
        width: calc(100% - 250px);
        padding: 30px;
        overflow-y: auto;
    }


   

    h1 {
        font-family: monospace;
        color: #153A90;
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .create-post-btn {
        display: inline-flex;
        align-items: center;
        margin-bottom: 25px;
        padding: 12px 25px;
        background-color: #153A90;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
        font-size: 1rem;
    }

    .create-post-btn:hover {
        background-color: #153A90;
    }

  
   


   

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
        white-space: nowrap;
    }

    .btn i {
        margin-right: 5px;
    }

    .btn-primary {
        background-color: #153A90;
        color: white;
    }

    .btn-primary:hover {
        background-color: #5ca3f2;
    }

    .btn-secondary {
        background-color: #5ca3f2;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #122a66;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #bb2d3b;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background-color: white;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        padding: 20px;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .comment-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        margin-bottom: 10px;
    }

    .comment-user {
        font-weight: bold;
        color: #153A90;
    }
    .comment-img {
        width: 60px;
        height: 60px;
        border-radius: 100px;
    }

    .comment-text {
        margin-top: 5px;
    }

    .comment-date {
        font-size: 0.8rem;
        color: #666;
        margin-top: 5px;
    }

  

    .comments-count {
        background-color: white;
        color: #153A90;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .loading-comments {
        text-align: center;
        padding: 20px;
    }

    .no-comments {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    /* Modal de Exclusão */
    .delete-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1100;
    }

    .delete-modal-overlay.active {
        display: flex;
    }

    .delete-modal-container {
        background-color: white;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        padding: 30px;
        position: relative;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .delete-modal-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .delete-modal-icon {
        background-color: #ffebee;
        color: #f44336;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }

    .delete-modal-title {
        font-size: 1.5rem;
        color: #f44336;
        margin: 0;
        font-weight: bold;
    }

    .delete-modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        transition: transform 0.2s;
    }

    .delete-modal-close:hover {
        transform: rotate(90deg);
    }

    .delete-modal-body {
        margin-bottom: 25px;
        line-height: 1.6;
        color: #555;
    }

    .delete-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .delete-modal-btn {
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .delete-modal-cancel {
        background-color: #f5f5f5;
        color: #333;
        border: none;
    }

    .delete-modal-cancel:hover {
        background-color: #e0e0e0;
    }

    .delete-modal-confirm {
        background-color: #f44336;
        color: white;
        border: none;
    }

    .delete-modal-confirm:hover {
        background-color: #d32f2f;
    }

    /* oq eu mudei */
   
    .post-description {
        color: #333;
        margin-bottom: 15px;
        line-height: 1.4;
        word-break: break-word;
        font-size: 0.9rem;
        flex-grow: 1;
         max-height: 2.8em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
         -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
    }
      .post-description.expanded {
        -webkit-line-clamp: unset;
        display: block;
        max-height: none; 
    }

    .read-more-btn {
        background: none;
        border: none;
        color: #153A90;
        cursor: pointer;
        padding: 5px 0;
        font-size: 0.85rem;
        text-align: left;
        display: inline-block;
         margin-top: 5px;
    }
    .read-more-btn:hover {
        text-decoration: underline;
    }

    .read-more-btn i {
        margin-left: 5px;
        transition: transform 0.3s;
    }

    .read-more-btn.expanded i {
        transform: rotate(180deg);
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .content-area {
            margin-left: 0;
            width: 100%;
            padding: 20px;
        }
        
    }
    .posts-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Cards mais estreitos */
        gap: 20px;
        justify-items: center;
    }

    .post-card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 2px 0px 2px 4px rgba(0, 0, 0, 0.1);
        padding: 15px;
        width: 100%;
        max-width: 350px;
        min-height: auto;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }

    .post-image-container {
        width: 100%;
        margin-bottom: 15px;
    }

    .post-image {
        width: 100%;
        height: 150px;
        box-shadow: 2px 0px 2px 4px #153A90;
        border-radius: 10px;
        object-fit: contain;
    }

    .post-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .post-title {
        font-size: 1.3rem;
        color: #153A90;
        margin-bottom: 10px;
        word-break: break-word;
    }

    .post-description {
        color: #333;
        margin-bottom: 15px;
        line-height: 1.4;
        word-break: break-word;
        font-size: 0.9rem;
        flex-grow: 1;
    }

    .post-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 0.85rem;
    }

  
    .post-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: space-between; /* Alinhamento uniforme */
        align-items: center; /* Centraliza verticalmente */
    }
     .post-actions .btn {
        flex: 1; /* Faz os botões crescerem igualmente */
        min-width: 120px; /* Largura mínima para manter legibilidade */
        text-align: center;
        padding: 8px 5px; /* Padding ajustado */
        font-size: 0.8rem; /* Tamanho de fonte reduzido */
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .post-actions .btn i {
        margin-right: 5px;
    }
    /* Ajustes para telas pequenas */
    @media (max-width: 576px) {
        .post-actions {
            flex-direction: row; /* Mantém em linha mesmo em mobile */
            gap: 6px;
        }
        
        .post-actions .btn {
            min-width: calc(50% - 5px); /* Dois botões por linha */
            font-size: 0.75rem;
            padding: 6px 3px;
        }
    }

    @media (max-width: 400px) {
        .post-actions .btn {
            min-width: 100%; /* Um botão por linha em telas muito pequenas */
        }
    }
     .view-comments-btn {
        background-color: #153A90;
        color: white;
        border: none;
        padding: 10px 15px; /* Mesmo padding dos outros botões */
        border-radius: 5px;
        cursor: pointer;
        margin-top: 0; /* Remove margem superior */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-size: 0.85rem; /* Mesmo tamanho de fonte */
        flex: 1; /* Cresce igual aos outros botões */
        min-width: 120px; /* Mesma largura mínima */
        text-align: center;
        white-space: nowrap;
        transition: background-color 0.3s;
    }

    .view-comments-btn:hover {
        background-color: #122a66; /* Efeito hover consistente */
    }

    /* Ajuste para os botões na mesma linha */
    .post-stats-actions {
        display: flex;
        gap: 8px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    /* Ajuste responsivo */
    @media (max-width: 576px) {
        .view-comments-btn {
            min-width: calc(50% - 5px); /* Dois botões por linha */
            padding: 8px 5px;
        }
    }

    @media (max-width: 400px) {
        .view-comments-btn {
            min-width: 100%; /* Um botão por linha */
        }
    }

    .btn {
        padding: 8px 15px;
        font-size: 0.85rem;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .posts-grid {
            grid-template-columns: 1fr;
        }
        
        .post-card {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .post-stats {
            flex-direction: column;
            gap: 5px;
        }
        
        .post-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .main {
            flex-direction: column;
        }
        .content-area {
            margin-left: 0;
            padding: 20px;
        }
        .post-card {
            flex-direction: column;
        }
        .post-image-container {
            flex: 1 1 100%;
        }
        .post-image {
            height: auto;
            max-height: 300px;
        }
        .posts-container {
            padding: 0 15px;
        }
    }

    @media (max-width: 576px) {
        .post-actions {
            flex-direction: column;
        }
        .btn {
            width: 100%;
            text-align: center;
        }
        .post-stats {
            gap: 10px;
        }
        .posts-container {
            padding: 0 10px;
        }
    }
    .post-category {
    margin-bottom: 10px;
}

.post-category span {
    background-color: #153A90;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    display: inline-block;
}

/* Estilo alternativo para categorias diferentes */
.post-category .castracao {
    background-color: #4CAF50; /* Verde para castração */
}

.post-category .adocao {
    background-color: #FF9800; /* Laranja para adoção */
}
</style>
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="main">
    @include('componentes.sidebar')

    <div class="content-area">
        <div class="posts-container">
            <h1>Suas Campanhas</h1>
            <a href="{{ route('posts.create') }}" class="create-post-btn">
                <i class="fas fa-plus"></i> Criar Novo Post
            </a>

            <div class="posts-grid">
                @foreach($posts as $post)
                <div class="post-card">
                    @if($post->image)
                    <div class="post-image-container">
                        <img src="{{ asset($post->image) }}" alt="Imagem da Campanha" class="post-image">
                    </div>
                    @endif
                    
                    <div class="post-content">
                    @if($post->ong)
                        <div class="ong-info" style="display: flex; align-items: center; margin-bottom: 10px;">
                            @if($post->ong->fotoOng)
                                <img src="{{ asset($post->ong->fotoOng) }}" alt="Foto da ONG" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 1px solid #ddd;">
                            @else
                                 <div style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; background-color: #eee; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #666; border: 1px solid #ddd;">ONG</div>
                            @endif
                            <span style="font-weight: bold; color: #555;">{{ $post->ong->nomeOng }}</span>
                        </div>
                    @endif
                    <h3 class="post-title">{{ $post->title }}</h3>

                    @if($post->categoria)
        <div class="post-category" style="margin-bottom: 10px;">
            <span>
                {{ $post->categoria->categoriaPosts }}
            </span>
        </div>
    @endif

                        <p class="post-description" id="description-{{ $post->id }}">
                            {{ $post->description }}
                        </p>
                        <button class="read-more-btn" onclick="toggleDescription({{ $post->id }})">
                            <span>Ver mais</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div class="post-stats">
                            <span class="post-stat"><i class="fas fa-heart"></i> {{ $post->likes }} curtidas</span>
                            <span class="post-stat"><i class="fas fa-comment"></i> {{ $post->comments_count }} comentários</span>
                        </div>
                        
                       <div class="post-stats-actions">
                            <button class="view-comments-btn" onclick="openCurtidaModal({{ $post->id }})">
                                <i class="fas fa-heart"></i> Curtidas 
                            </button>
                            <button class="view-comments-btn" onclick="openCommentsModal({{ $post->id }})">
                                <i class="fas fa-comments"></i> Comentários 
                            </button>
                        </div>
                        
                          <div class="post-actions">
                            
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button type="button" class="btn btn-danger" onclick="openDeleteModal({{ $post->id }}, '{{ addslashes($post->title) }}')">
                                <i class="fas fa-trash-alt"></i> Excluir
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal de Comentários -->
<div class="modal-overlay" id="commentsModal">
    <div class="modal-container">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h3>Comentários</h3>
        <div id="modalCommentsContent">
            <div class="loading-comments">
                <i class="fas fa-spinner fa-spin"></i> Carregando comentários...
            </div>
        </div>
    </div>
</div>

<!-- Modal de Curtidas -->
<div class="modal-overlay" id="curtidasModal">
    <div class="modal-container">
        <button class="modal-close" onclick="closeModalCurtida()">&times;</button>
        <h3>Usuários que curtiram</h3>
        <div id="modalCurtidaContent">
            <div class="loading-comments">
                <i class="fas fa-spinner fa-spin"></i> Carregando usuários...
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="delete-modal-overlay" id="deleteModal">
    <div class="delete-modal-container">
        <button class="delete-modal-close" onclick="closeDeleteModal()">&times;</button>
        <div class="delete-modal-header">
            <div class="delete-modal-icon">
                <i class="fas fa-exclamation"></i>
            </div>
            <h3 class="delete-modal-title">Confirmar Exclusão</h3>
        </div>
        <div class="delete-modal-body">
            <p>Tem certeza que deseja excluir permanentemente o post "<strong id="deletePostTitle"></strong>"?</p>
            <p>Esta ação não pode ser desfeita e todos os dados associados serão perdidos.</p>
        </div>
        <div class="delete-modal-footer">
            <button class="delete-modal-btn delete-modal-cancel" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <form id="deletePostForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-modal-btn delete-modal-confirm">
                    <i class="fas fa-trash-alt"></i> Confirmar Exclusão
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Variáveis globais
    let currentPostId = null;
    let currentPostIds = null;
const imgSrc = `/img/imgUser/${comment.imgUser || 'default.png'}`;
    // Funções para o modal de comentários
    function openCommentsModal(postId) {
        currentPostId = postId;
        const modal = document.getElementById('commentsModal');
        modal.classList.add('active');
        loadComments(postId);
    }

    function closeModal() {
        const modal = document.getElementById('commentsModal');
        modal.classList.remove('active');
    }

    function loadComments(postId) {
        const contentDiv = document.getElementById('modalCommentsContent');
        contentDiv.innerHTML = '<div class="loading-comments"><i class="fas fa-spinner fa-spin"></i> Carregando comentários...</div>';

        fetch(`/posts/${postId}/comments`)
            .then(response => {
                if (!response.ok) throw new Error('Erro ao carregar comentários');
                return response.json();
            })
            .then(data => {
                if (data.success && data.comments) {
                    renderComments(data.comments);
                } else {
                    showError(data.message || 'Erro ao carregar comentários');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Erro ao carregar comentários');
            });
    }

    function renderComments(comments) {
    const contentDiv = document.getElementById('modalCommentsContent');

    if (!comments || comments.length === 0) {
        contentDiv.innerHTML = '<div class="no-comments"><i class="far fa-comment-dots"></i><p>Nenhum comentário ainda</p></div>';
        return;
    }

    let html = '';
    comments.forEach(comment => {
        const commentDate = new Date(comment.created_at).toLocaleString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const imgSrc = comment.imgUser || '/img/imgUser/default.png';

        html += `
            <div class="comment-item">
                <div class="comment-img2">
                    <img class="comment-img" src="${imgSrc}" alt="Foto do usuário" />
                </div>
                <div class="comment-user">${comment.nomeUser || 'Usuário Anônimo'}</div>
                <div class="comment-text">${comment.comment || 'Comentário sem texto'}</div>
                <div class="comment-date">${commentDate}</div>
            </div>
        `;
    });

    contentDiv.innerHTML = html;
}
    function showError(message) {
        const contentDiv = document.getElementById('modalCommentsContent');
        contentDiv.innerHTML = `
            <div class="no-comments">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
                <button onclick="loadComments(currentPostId)" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fas fa-sync-alt"></i> Tentar novamente
                </button>
            </div>
        `;
    }

    // Funções para o modal de curtidas
    function openCurtidaModal(postId) {
        currentPostIds = postId;
        const modal = document.getElementById('curtidasModal');
        modal.classList.add('active');
        loadCurtidas(postId);
    }

    function closeModalCurtida() {
        const modal = document.getElementById('curtidasModal');
        modal.classList.remove('active');
    }

    function loadCurtidas(postId) {
        const contentDiv = document.getElementById('modalCurtidaContent');
        contentDiv.innerHTML = '<div class="loading-comments"><i class="fas fa-spinner fa-spin"></i> Carregando usuários...</div>';

        fetch(`/posts/${postId}/likes`)
            .then(response => {
                if (!response.ok) throw new Error('Erro ao carregar usuários');
                return response.json();
            })
            .then(data => {
                if (data.success && data.likes) {
                    renderCurtidas(data.likes);
                } else {
                    showErrorCurtidas(data.message || 'Erro ao carregar usuários');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorCurtidas('Erro ao carregar usuários');
            });
    }

    function renderCurtidas(likes) {
        const contentDiv = document.getElementById('modalCurtidaContent');

        if (!likes || likes.length === 0) {
            contentDiv.innerHTML = '<div class="no-comments"><i class="far fa-heart"></i><p>Nenhum usuário curtiu este post ainda</p></div>';
            return;
        }

        let html = '';
        likes.forEach(like => {
            const likeDate = new Date(like.created_at).toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            html += `
                <div class="comment-item">
                    <div class="comment-user">${like.nomeUser || 'Usuário Anônimo'}</div>
                    <div class="comment-date">Curtiu em: ${likeDate}</div>
                </div>
            `;
        });

        contentDiv.innerHTML = html;
    }

    function showErrorCurtidas(message) {
        const contentDiv = document.getElementById('modalCurtidaContent');
        contentDiv.innerHTML = `
            <div class="no-comments">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
                <button onclick="loadCurtidas(currentPostIds)" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fas fa-sync-alt"></i> Tentar novamente
                </button>
            </div>
        `;
    }

    // Funções para o modal de exclusão
    function openDeleteModal(postId, postTitle) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deletePostForm');
        const titleElement = document.getElementById('deletePostTitle');

        form.action = `/posts/${postId}`;
        titleElement.textContent = postTitle;
        modal.classList.add('active');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('active');
    }

    // Event listeners para fechar modais ao clicar fora
    document.getElementById('commentsModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.getElementById('curtidasModal').addEventListener('click', function(e) {
        if (e.target === this) closeModalCurtida();
    });

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Fechar modais com tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeModalCurtida();
            closeDeleteModal();
        }
    });


     // Função para mostrar a descrição
    function toggleDescription(postId) {
        // Encontra apenas os elementos do card clicado
        const description = document.getElementById(`description-${postId}`);
        const button = document.querySelector(`#description-${postId} + .read-more-btn`);
        
        // Alterna apenas o card clicado
        description.classList.toggle('expanded');
        button.classList.toggle('expanded');
        
        // Atualiza o texto do botão
        if (description.classList.contains('expanded')) {
            button.querySelector('span').textContent = 'Ver menos';
        } else {
            button.querySelector('span').textContent = 'Ver mais';
        }
    }

</script>
@endsection