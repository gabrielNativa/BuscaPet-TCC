	//***********************************
	//atualizar fotop 
	//***********************************
	document.getElementById("foto").addEventListener("change", readImage, false);
		function readImage() {
			if (this.files && this.files[0]) {
				//FileReader é usado para ler arquivos selecionados pelo usuário.
				var file = new FileReader();
				//Esta linha define um evento que será acionado quando o processo de leitura do arquivo estiver concluído
				file.onload = function(e) {
				//Nesta linha, estamos atribuindo o resultado da leitura do arquivo à propriedade src de um elemento HTML com o ID "preview
					document.getElementById("preview").src = e.target.result;
				};
				// Esta linha inicia a leitura do primeiro arquivo selecionado pelo usuário (representado por this.files[0]). O método readAsDataURL converte o arquivo em uma URL de dados, que será usada na linha anterior para definir a imagem de visualização.
				file.readAsDataURL(this.files[0]);
			}
		}


