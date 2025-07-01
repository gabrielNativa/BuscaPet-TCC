<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Imagens - {{ $animal->nomeAnimal }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style66.css') }}">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #6c757d;
            --hover-color: #3a5a8f;
            --border-color: #dee2e6;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --required-color: #dc3545;
            --success-color: #28a745;
        }

        .main-content {
            padding: 2rem;
        }

        .card-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px var(--shadow-color);
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
        }

        .image-upload-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .image-upload-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .image-upload-box:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 6px 16px var(--shadow-color);
        }

        .image-preview-container {
            position: relative;
            width: 100%;
            height: 200px;
            margin-bottom: 1rem;
            overflow: hidden;
            border-radius: 8px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            transition: opacity 0.3s;
        }

        .image-actions {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 0.5rem;
        }

        .upload-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            font-size: 0.9rem;
        }

        .upload-btn:hover {
            background: var(--hover-color);
        }

        .remove-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            font-size: 0.9rem;
        }

        .remove-btn:hover {
            background: #5a6268;
        }

        .required-badge {
            color: var(--required-color);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            font-weight: bold;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background: var(--hover-color);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #5a6268;
            color: white;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .image-upload-container {
                grid-template-columns: 1fr;
            }

            .card-table {
                padding: 1rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @include('componentes.sidebar')

        <div class="main-content">
            <div class="card-table">
                <h1>
                    <i class="fas fa-images"></i>
                    {{ isset($animal->imagensAnimal) ? 'Atualizar Fotos' : 'Adicionar Fotos' }}
                    para {{ $animal->nomeAnimal }}
                </h1>

                <form method="POST" action="{{ route('pets.saveImages', $animal->idAnimal) }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($animal->imagensAnimal))
                    @method('PUT')
                    @endif

                    <div class="image-upload-container">
                        @for($i = 1; $i <= 4; $i++)
                            @php
                            $hasImage=isset($animal->imagensAnimal) && $animal->imagensAnimal->{'img'.$i.'Animal'};
                            $imagePath = $hasImage ? asset('img/imgAnimal/'.$animal->imagensAnimal->{'img'.$i.'Animal'}) : asset('img/imgAnimal/perfil.png');
                            @endphp
                            <div class="image-upload-box" id="box-{{$i}}">
                                <div class="image-preview-container">
                                    <img id="preview-{{$i}}"
                                        src="{{ $imagePath }}"
                                        class="image-preview"
                                        @if(!$hasImage) style="opacity: 0.5;" @endif>
                                </div>

                                <div class="image-actions">
                                <input type="file" id="img-{{ $i }}" name="img{{ $i }}Animal"
                                class="hidden" accept="image/*" onchange="previewImage(this, '{{ $i }}')">
                                    <label for="img-{{$i}}" class="upload-btn">
                                        <i class="fas fa-upload"></i>
                                        {{ $hasImage ? 'Alterar Imagem' : 'Selecionar Imagem' }}
                                    </label>

                                    @if($hasImage)
                                    <button type="button" class="remove-btn" onclick="removeImage('{{ $i }}')">
                                        <i class="fas fa-trash"></i> Remover Imagem
                                    </button>
                                    <input type="hidden" id="current-img-{{$i}}" name="current_img{{$i}}Animal"
                                        value="{{ $animal->imagensAnimal->{'img'.$i.'Animal'} }}">
                                    @endif

                                </div>
                            </div>
                            @endfor
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            {{ isset($animal->imagensAnimal) ? 'Atualizar Imagens' : 'Salvar Imagens' }}
                        </button>
                        <a href="{{ route('pets.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview da imagem selecionada
        function previewImage(input, index) {
            const preview = document.getElementById(`preview-${index}`);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.opacity = '1';

                    // Atualiza o texto do botão
                    const label = input.nextElementSibling;
                    label.innerHTML = `<i class="fas fa-upload"></i> Alterar Imagem`;

                    // Mostra o botão de remover se estiver oculto
                    const removeBtn = label.nextElementSibling;
                    if (removeBtn && removeBtn.classList.contains('hidden')) {
                        removeBtn.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        // Remove a imagem selecionada
        function removeImage(index) {
            const inputFile = document.getElementById(`img-${index}`);
            const preview = document.getElementById(`preview-${index}`);
            const currentImgInput = document.getElementById(`current-img-${index}`);
            const label = inputFile.nextElementSibling;

            // Reseta o input de arquivo
            inputFile.value = '';

            // Remove o preview
            preview.src = "{{ asset('img/imgAnimal/perfil.png') }}";
            preview.style.opacity = '0.5';

            // Atualiza o texto do botão
            label.innerHTML = `<i class="fas fa-upload"></i> Selecionar Imagem`;

            // Remove o valor da imagem atual se existir
            if (currentImgInput) {
                currentImgInput.value = '';
            }

            // Oculta o botão de remover
            const removeBtn = label.nextElementSibling;
            if (removeBtn) {
                removeBtn.classList.add('hidden');
            }
        }

        // Verifica se há imagens ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica cada slot de imagem (1 a 4)
            for (let i = 1; i <= 4; i++) {
                const preview = document.getElementById(`preview-${i}`);
                const currentImgInput = document.getElementById(`current-img-${i}`);

                // Se existe um valor no campo hidden, mostra a imagem com opacidade total
                if (currentImgInput && currentImgInput.value) {
                    preview.style.opacity = '1';
                }
            }
        });
    </script>
</body>

</html>