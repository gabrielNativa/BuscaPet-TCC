<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor ADM - Campanhas</title>
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/campanha.css') }}">

</head>

<body>
    <div class="container">
        @include('componentes.menuAdm')
        @include('componentes.headerAdm')

        <div class="content">
            <div class="card-table-container">
                <div class="card-table">
                    <h1>
                        <i class="material-icons-sharp">campaign</i>
                        Painel de Campanhas
                    </h1>

                    <div class="campaigns-container">
                        @foreach($posts as $post)
                        <div class="campaign-card">
                            <div class="campaign-header">
                                <div>
                                    <div class="campaign-title">{{ \Illuminate\Support\Str::limit($post->title, 50, '...') }}</div>
                                    <div class="campaign-author">
                                        <img src="{{ asset($post->ong->fotoOng) }}" alt="Logo da ONG" style="width: 25px; height: 25px; margin-right: 5px; border-radius: 50%;">
                                        {{ $post->ong->nomeOng }}
                                    </div>
                                </div>
                            </div>

                            @if($post->image)
                            <div class="post-image-container">
                                <img src="{{ asset($post->image) }}" alt="Imagem da Campanha" class="campaign-image">
                            </div>
                            @endif

                            <div class="campaign-description">
                                {{ \Illuminate\Support\Str::limit($post->description, 100, '...') }}
                            </div>

                            <div class="campaign-stats">
                                <div class="stat-item likes-count">
                                    <i class="material-icons-sharp">favorite</i>
                                    <span>{{ $post->likes ?? 0 }}</span>
                                </div>
                                <div class="stat-item comments-count">
                                    <i class="material-icons-sharp">comment</i>
                                    <span>{{ $post->comments->count() ?? 0 }}</span>
                                </div>
                            </div>

                            <div class="comments-preview">
                                <div class="comments-header">
                                    <i class="material-icons-sharp">comment</i>
                                    Últimos comentários
                                </div>
                                @if($post->comments->count() > 0)
                                    @foreach($post->comments->take(3) as $comment)
                                        <div class="comment-item">
                                            <span class="comment-author">{{ $comment->user->nomeUser ?? 'Anônimo' }}:</span> 
                                            {{ $comment->comment }}
                                        </div>
                                    @endforeach
                                @else
                                    <div class="comment-item">
                                        Nenhum comentário ainda
        </div>
    @endif
</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pagination-container">
                        <div class="pagination">
                            @if ($posts->onFirstPage())
                            <span class="disabled"><i class="material-icons-sharp">chevron_left</i></span>
                            @else
                            <a href="{{ $posts->previousPageUrl() }}" class="page-link"><i class="material-icons-sharp">chevron_left</i></a>
                            @endif

                            @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                            @if ($page == $posts->currentPage())
                            <span class="active">{{ $page }}</span>
                            @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                            @endif
                            @endforeach

                            @if ($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" class="page-link"><i class="material-icons-sharp">chevron_right</i></a>
                            @else
                            <span class="disabled"><i class="material-icons-sharp">chevron_right</i></span>
                            @endif
                        </div>

                        <div class="pagination-info">
                            Mostrando {{ $posts->firstItem() }} a {{ $posts->lastItem() }} de {{ $posts->total() }} registros
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/dark.js') }}"></script>
    
</body>

</html>