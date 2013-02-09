<?php
/**
 * canvas.php
 *
 * Classe para manipulação de imagens
 * Interface para GD
 *
 * @author     Davi Ferreira <contato@daviferreira.com>
 * @version    1.0 $ 2010-05-21 19:56:33 $
 *
 * TODO:
*/

class canvas {

	// arquivos
	protected $origem, $img, $img_temp;
	// dimensões
	protected $largura, $altura, $nova_largura, $nova_altura, $tamanho_html;
	// dados do arquivo
	protected $formato, $extensao, $tamanho, $arquivo, $diretorio;
	// cor de fundo para preenchimento
	protected $rgb;
	// posicionamento do crop
	protected $posicao_crop;

	/**
	 * Construtor
	 * @param $string caminho da imagem a ser carregada
	 * @return void
	*/
	public function __construct( $origem = '' )
	{

		$this->origem					= $origem;

		if ( $this->origem )
		{
			$this->dados();
		}

		// RGB padrão -> branco
		$this->rgb( 255, 255, 255 );
	} // fim construtor

	/**
	 * Retorna dados da imagem
	 * @param
	 * @return void
	*/
	protected function dados()
	{

		// verifica se imagem existe
		if ( is_file( $this->origem ) )
		{
	
			// dados do arquivo
			$this->dadosArquivo();

			// verifica se é imagem
			if ( !$this->eImagem() )
			{
				trigger_error( 'Erro: Arquivo '.$this->origem.' não é uma imagem!', E_USER_ERROR );
			}
			else
			{
				// pega dimensões
				$this->dimensoes();

				// cria imagem para php
				$this->criaImagem();
			}
		}
		else
		{
			trigger_error( 'Erro: Arquivo de imagem não encontrado!', E_USER_ERROR );
		}

	} // fim dadosImagem

	/**
	 * Carrega uma nova imagem, fora do construtor
	 * @param String caminho da imagem a ser carregada
	 * @return Object instância atual do objeto, para métodos encadeados
	*/
	public function carrega( $origem = '' )
	{
		$this->origem			= $origem;
		$this->dados();
		return $this;
	} // fim carrega

	/**
	 * Busca dimensões e formato real da imagem
	 * @param
	 * @return void
	*/
	protected function dimensoes()
	{
			$dimensoes 				= getimagesize( $this->origem );
			$this->largura 	 		= $dimensoes[0];
			$this->altura	 		= $dimensoes[1];
			// 1 = gif, 2 = jpeg, 3 = png, 6 = BMP
			// http://br2.php.net/manual/en/function.exif-imagetype.php
			$this->formato			= $dimensoes[2];
			$this->tamanho_html		= $dimensoes[3];
	} // fim dimensoes

	/**
	 * Busca dados do arquivo
	 * @param
	 * @return void
	*/
	protected function dadosArquivo()
	{
		// imagem de origem
		$pathinfo 			= pathinfo( $this->origem );
		$this->extensao 	= strtolower( $pathinfo['extension'] );
		$this->arquivo		= $pathinfo['basename'];
		$this->diretorio	= $pathinfo['dirname'];
	} // fim dadosArquivo

	/**
	 * Verifica se o arquivo indicado é uma imagem
	 * @return Boolean true/false
	*/
	protected function eImagem()
	{
		// filtra extensão
 		$valida = getimagesize( $this->origem );
		if ( !is_array( $valida ) || empty( $valida ) )
		{
			return false;
		}
		else
		{
			return true;
		}
	} // fim validaImagem

	/**
	 * Cria uma nova imagem para ser trabalhada com textos, etc.
	 * OBS: a cor da imagem deve ser passada antes, via rgb() ou hex()
	 * @param String $largura da imagem a ser criada
	 * @param String $altura da imagem a ser criada
	 * @return Object instância atual do objeto, para métodos encadeados
	 */
	public function novaImagem( $largura, $altura )
	{
		$this->largura 	= $largura;
		$this->altura	= $altura; 
		$this->img = imagecreatetruecolor( $this->largura, $this->altura );
		$cor_fundo = imagecolorallocate( $this->img, $this->rgb[0], $this->rgb[1], $this->rgb[2] );
		imagefill( $this->img, 0, 0, $cor_fundo );
		$this->extensao = 'jpg';
		return $this;
    } // fim novaImagem

    /**
     * Carrega uma imagem via URL
     * @param String $url endereço da imagem
	 * @return Object instância atual do objeto, para métodos encadeados
     **/
	public function carregaUrl( $url )
	{
		$this->origem = $url;
		$pathinfo = pathinfo( $this->origem );
		$this->extensao = strtolower( $pathinfo['extension'] );
		switch( $this->extensao )
		{
			case 'jpg':
			case 'jpeg':
				$this->formato = 2;
				break;
			case 'gif':
				$this->formato = 1;
				break;
			case 'png':
				$this->formato = 3;
				break;
			case 'bmp':
				$this->formato = 6;
				break;
			default:
				$this->formato = 3;
				break;
        }
		$this->criaImagem();
        $this->largura  = imagesx( $this->img );
		$this->altura   = imagesy( $this->img );
		return $this;
	} // fim carregaUrl

	/**
	 * Cria objeto de imagem para manipulação no GD
	 * @param
	 * @return void
	*/
	protected function criaImagem()
	{
		switch ( $this->formato )
		{
			case 1:
				$this->img = imagecreatefromgif( $this->origem );
				$this->extensao = 'gif';
				break;
			case 2:
				$this->img = imagecreatefromjpeg( $this->origem );
				$this->extensao = 'jpg';
				break;
			case 3:
				$this->img = imagecreatefrompng( $this->origem );
				$this->extensao = 'png';
				break;
			case 6:
				$this->img = imagecreatefrombmp( $this->origem );
				$this->extensao = 'bmp';
				break;
			default:
				trigger_error( 'Arquivo inválido!', E_USER_ERROR );
				break;
		}
	} // fim criaImagem

	/**
	 * Armazena os valores RGB para redimensionamento com preenchimento
	 * @param Valores R, G e B
	 * @return Object instância atual do objeto, para métodos encadeados
	*/
	public function rgb( $r, $g, $b )
	{
		$this->rgb = array( $r, $g, $b );
		return $this;
	} // fim rgb
	
	/**
	 * Converte hexadecimal para RGB
	 * @param String $cor cor hexadecimal
	 * @return Object instância atual do objeto, para métodos encadeados
	 */
	public function hexa( $cor )
	{
		$cor = str_replace( '#', '', $cor );
		
		if( strlen( $cor ) == 3 ) $cor .= $cor; /* #fff, #000 etc. */
		
		$this->rgb = array(
		  hexdec( substr( $cor, 0, 2 ) ),
		  hexdec( substr( $cor, 2, 2 ) ),
		  hexdec( substr( $cor, 4, 2 ) ),
		);
		return $this;
	}  // fim hexa
	
	/**
	 * Armazena posições x e y para crop
	 * @param Array valores x e y
	 * @return Object instância atual do objeto, para métodos encadeados
	*/
	public function posicaoCrop( $x, $y )
	{
		$this->posicao_crop = array( $x, $y, $this->largura, $this->altura );
		return $this;
	} // fim posicao_crop

	/**
	 * Redimensiona imagem
	 * @param Int $nova_largura valor em pixels da nova largura da imagem
	 * @param Int $nova_altura valor em pixels da nova altura da imagem
	 * @param String $tipo método para redimensionamento (padrão [vazio], preenchimento ou crop)
	 * @return Object instância atual do objeto, para métodos encadeados
	*/
	public function redimensiona( $nova_largura = 0, $nova_altura = 0, $tipo = '' )
	{

		// seta variáveis passadas via parâmetro
		$this->nova_largura		= $nova_largura;
		$this->nova_altura		= $nova_altura;

		// verifica se passou altura ou largura como porcentagem
		// largura %
		$pos = strpos( $this->nova_largura, '%' );
		if( $pos !== false && $pos > 0 )
		{
			$porcentagem			= ( ( int ) str_replace( '%', '', $this->nova_largura ) ) / 100;
			$this->nova_largura		= round( $this->largura * $porcentagem );
		}
		// altura %
		$pos = strpos( $this->nova_altura, '%' );
		if( $pos !== false && $pos > 0 )
		{
			$porcentagem			= ( ( int ) str_replace( '%', '', $this->nova_altura ) ) / 100;
			$this->nova_altura		= $this->altura * $porcentagem;
		}

		// define se só passou nova largura ou altura
		if ( !$this->nova_largura && !$this->nova_altura )
		{
			return false;
		}
		// só passou altura
		elseif ( !$this->nova_largura )
		{
			$this->nova_largura = $this->largura / ( $this->altura/$this->nova_altura );
		}
		// só passou largura
		elseif ( !$this->nova_altura )
		{
			$this->nova_altura = $this->altura / ( $this->largura/$this->nova_largura );
		}

		// redimensiona de acordo com tipo
		switch( $tipo )
		{
			case 'crop':
				$this->redimensionaCrop();
				break;
			case 'preenchimento':
				$this->redimensionaPreenchimento();
				break;
			default:
				$this->redimensionaSimples();
				break;
		}

		// atualiza dimensões da imagem
		$this->altura 	= $this->nova_altura;
		$this->largura	= $this->nova_largura;

		return $this;
	} // fim redimensiona

	/**
	 * Redimensiona imagem, modo padrão, sem crop ou preenchimento 
	 * (distorcendo caso tenha passado ambos altura e largura)
	 * @param
	 * @return void
	*/
	protected function redimensionaSimples()
	{
		// cria imagem de destino temporária
		$this->img_temp	= imagecreatetruecolor( $this->nova_largura, $this->nova_altura );

		imagecopyresampled( $this->img_temp, $this->img, 0, 0, 0, 0, $this->nova_largura, $this->nova_altura, $this->largura, $this->altura );
		$this->img	= $this->img_temp;
	} // fim redimensiona()

	/**
	 * Adiciona cor de fundo à imagem
	 * @param
	 * @return void
	*/
	protected function preencheImagem()
	{
		$cor_fundo = imagecolorallocate( $this->img_temp, $this->rgb[0], $this->rgb[1], $this->rgb[2] );
		imagefill( $this->img_temp, 0, 0, $cor_fundo );
	} // fim preencheImagem

	/**
	 * Redimensiona imagem sem cropar, proporcionalmente,
	 * preenchendo espaço vazio com cor rgb especificada
	 * @param
	 * @return void
	*/
	protected function redimensionaPreenchimento()
	{
		// cria imagem de destino temporária
		$this->img_temp	= imagecreatetruecolor( $this->nova_largura, $this->nova_altura );

		// adiciona cor de fundo à nova imagem
		$this->preencheImagem();

		// salva variáveis para centralização
		$dif_x = $dif_w = $this->nova_largura;
		$dif_y = $dif_h = $this->nova_altura;
		
		/**
		 * Verifica altura e largura
		 * Cálculo corrigido por Leonardo <leonardomascara@gmail.com>
		 */
		if ( $this->nova_largura >= $this->nova_altura )
		{
			$dif_w = ( ( $this->largura * $this->nova_altura ) / $this->altura );
		}
		else
		{
			$dif_h = ( ( $this->altura * $this->nova_largura ) / $this->largura );
		}

		// copia com o novo tamanho, centralizando
		$dif_x = ( $dif_x - $dif_w ) / 2;
		$dif_y = ( $dif_y - $dif_h ) / 2;
		imagecopyresampled( $this->img_temp, $this->img, $dif_x, $dif_y, 0, 0, $dif_w, $dif_h, $this->largura, $this->altura );
		$this->img	= $this->img_temp;
	} // fim redimensionaPreenchimento()

	/**
	 * Calcula a posição do crop
	 * Os índices 0 e 1 correspondem à posição x e y do crop na imagem
	 * Os índices 2 e 3 correspondem ao tamanho do crop
	 * @param
	 * @return void
	*/
	protected function calculaPosicaoCrop()
	{
		// média altura/largura
		$hm	= $this->altura / $this->nova_altura;
		$wm	= $this->largura / $this->nova_largura;

		// 50% para cálculo do crop
		$h_height = $this->nova_altura / 2;
		$h_width  = $this->nova_largura / 2;

		// calcula novas largura e altura
		if( !is_array( $this->posicao_crop ) )
		{
			if ( $wm > $hm )
			{
				$this->posicao_crop[2] 	= $this->largura / $hm;
				$this->posicao_crop[3]  = $this->nova_altura;
				$this->posicao_crop[0]  = ( $this->posicao_crop[2] / 2 ) - $h_width;
				$this->posicao_crop[1]	= 0;
			}
			// largura <= altura
			elseif ( ( $wm <= $hm ) )
			{
				$this->posicao_crop[2] 	= $this->nova_largura;
				$this->posicao_crop[3]  = $this->altura / $wm;
				$this->posicao_crop[0]  = 0;
				$this->posicao_crop[1]	= ( $this->posicao_crop[3] / 2 ) - $h_height;
			}
		}
	} // fim calculaPosicaoCrop

	/**
	 * Redimensiona imagem, cropando para encaixar no novo tamanho, sem sobras
	 * baseado no script original de Noah Winecoff
	 * http://www.findmotive.com/2006/12/13/php-crop-image/
	 * atualizado para receber o posicionamento X e Y do crop na imagem
	 * @return void
	*/
	protected function redimensionaCrop()
	{
		// calcula posicionamento do crop
		$this->calculaPosicaoCrop();

		// cria imagem de destino temporária
		$this->img_temp	= imagecreatetruecolor( $this->nova_largura, $this->nova_altura );

		// adiciona cor de fundo à nova imagem
		$this->preencheImagem();

		imagecopyresampled( $this->img_temp, $this->img, -$this->posicao_crop[0], -$this->posicao_crop[1], 0, 0, $this->posicao_crop[2], $this->posicao_crop[3], $this->largura, $this->altura );

		$this->img	= $this->img_temp;
	} // fim redimensionaCrop

	/**
	 * flipa/inverte imagem
	 * baseado no script original de Noah Winecoff
	 * http://www.php.net/manual/en/ref.image.php#62029
	 * @param String $tipo tipo de espelhamento: h - horizontal, v - vertical
	 * @return Object instância atual do objeto, para métodos encadeados
	*/
	public function flip( $tipo = 'h' )
	{
		$w = imagesx( $this->img );
		$h = imagesy( $this->img );

		$this->img_temp = imagecreatetruecolor( $w, $h );

		// vertical
		if ( 'v' == $tipo )
		{
			for ( $y = 0; $y < $h; $y++ )
			{
				imagecopy( $this->img_temp, $this->img, 0, $y, 0, $h - $y - 1, $w, 1 );
			}
		}
		// horizontal
		elseif ( 'h' == $tipo )
		{
			for ( $x = 0; $x < $w; $x++ )
			{
				imagecopy( $this->img_temp, $this->img, $x, 0, $w - $x - 1, 0, 1, $h );
			}
		}

		$this->img	= $this->img_temp;
		
		return $this;
	} // fim flip

	/**
	 * gira imagem
	 * @param Int $graus grau para giro
	 * @return Object instância atual do objeto, para métodos encadeados
	*/
	public function gira( $graus )
	{
		$cor_fundo	= imagecolorallocate( $this->img, $this->rgb[0], $this->rgb[1], $this->rgb[2] );
		$this->img	= imagerotate( $this->img, $graus, $cor_fundo );
		imagealphablending( $this->img, true );
		imagesavealpha( $this->img, true );
		$this->largura = imagesx( $this->img );
		$this->altura = imagesx( $this->img );
		return $this;
	} // fim girar

	/**
	 * adiciona texto à imagem
	 * @param String $texto texto a ser inserido
	 * @param Int $tamanho tamanho da fonte
	 *		  Ver: http://br2.php.net/imagestring
	 * @param Int $x posição x do texto na imagem
	 * @param Int $y posição y do texto na imagem
	 * @param Array/String $cor_fundo array com cores RGB ou string com cor hexadecimal
	 * @param Boolean $truetype true para utilizar fonte truetype, false para fonte do sistema
	 * @param String $fonte nome da fonte truetype a ser utilizada
	 * @return void
	*/
	public function legenda( $texto, $tamanho = 5, $x = 0, $y = 0, $cor_fundo = '', $truetype = false, $fonte = '' )
	{
		$cor_texto = imagecolorallocate( $this->img, $this->rgb[0], $this->rgb[1], $this->rgb[2] );
		
		/**
		 * Define tamanho da legenda para posições fixas e fundo da legenda
		 */
		if( $truetype  === true )
		{
			$dimensoes_texto 	= imagettfbbox( $tamanho, 0, $fonte, $texto );
			$largura_texto 		= $dimensoes_texto[4];
			$altura_texto 		= $tamanho;
		}
		else
		{
			if( $tamanho > 5 ) $tamanho = 5;
			$largura_texto	= imagefontwidth( $tamanho ) * strlen( $texto );
			$altura_texto	= imagefontheight( $tamanho );
		}
		
		if( is_string( $x ) && is_string( $y ) )
		{
			list( $x, $y ) = $this->calculaPosicaoLegenda( $x . '_' . $y, $largura_texto, $altura_texto );
		}
		
		/**
		 * Cria uma nova imagem para usar de fundo da legenda
		 */
		if( $cor_fundo )
		{
			if( is_array( $cor_fundo ) )
			{
				$this->rgb = $cor_fundo;
			}
			elseif( strlen( $cor_fundo ) > 3 )
			{
				$this->hexa( $cor_fundo );
			}
			
			$this->img_temp = imagecreatetruecolor( $largura_texto, $altura_texto );
			$cor_fundo = imagecolorallocate( $this->img_temp, $this->rgb[0], $this->rgb[1], $this->rgb[2] );
			imagefill( $this->img_temp, 0, 0, $cor_fundo );
			
			imagecopy( $this->img, $this->img_temp, $x, $y, 0, 0, $largura_texto, $altura_texto );
		}

		// truetype ou fonte do sistema?
		if ( $truetype === true )
		{
			$y = $y + $tamanho;
			imagettftext( $this->img, $tamanho, 0, $x, $y, $cor_texto, $fonte, $texto );
		}
		else
		{
			imagestring( $this->img, $tamanho, $x, $y, $texto, $cor_texto );
		}
		return $this;
	} // fim legenda
	
	protected function calculaPosicaoLegenda( $posicao, $largura, $altura )
	{
		
		// define X e Y para posicionamento
		switch( $posicao )
		{
			case 'topo_esquerda':
				$x = 0;
				$y = 0;
				break;
			case 'topo_centro':
				$x = ( $this->largura - $largura ) / 2;
				$y = 0;
				break;
			case 'topo_direita':
				$x = $this->largura - $largura;
				$y = 0;
				break;
			case 'meio_esquerda':
				$x = 0;
				$y = ( $this->altura / 2 ) - ( $altura / 2 );
				break;
			case 'meio_centro':
				$x = ( $this->largura - $largura ) / 2;
				$y = ( $this->altura - $altura ) / 2 ;
				break;
			case 'meio_direita':
				$x = $this->largura - $largura;
				$y = ( $this->altura / 2) - ( $altura / 2 );
				break;
			case 'baixo_esquerda':
				$x = 0;
				$y = $this->altura - $altura;
				break;
			case 'baixo_centro':
				$x = ( $this->largura - $largura ) / 2;
				$y = $this->altura - $altura;
				break;
			case 'baixo_direita':
				$x = $this->largura - $largura;
				$y = $this->altura - $altura;
				break;
			default:
				return false;
				break;
		} // end switch posicao
		
		return array( $x, $y );
	} // fim calculaPosicaoLegenda

	/**
	 * adiciona imagem de marca d'água
	 * @param String $imagem caminho da imagem de marca d'água
	 * @param Int/String $x posição x da marca na imagem ou constante para marcaFixa()
	 * @param Int/Sring $y posição y da marca na imagem ou constante para marcaFixa()
	 * @return Boolean true/false dependendo do resultado da operação
	 * @param Int $alfa valor para transparência (0-100)
	 			  -> se utilizar alfa, a função imagecopymerge não preserva
				  -> o alfa nativo do PNG
	* @return Object instância atual do objeto, para métodos encadeados
	 */
	public function marca( $imagem, $x = 0, $y = 0, $alfa = 100 )
	{
		// cria imagem temporária para merge
		if ( $imagem ) {
			
			if( is_string( $x ) && is_string( $y ) )
			{
				return $this->marcaFixa( $imagem, $x . '_' . $y, $alfa );
			}
			
			$pathinfo = pathinfo( $imagem );
			switch( strtolower( $pathinfo['extension'] ) )
			{
				case 'jpg':
				case 'jpeg':
					$marcadagua = imagecreatefromjpeg( $imagem );
					break;
				case 'png':
					$marcadagua = imagecreatefrompng( $imagem );
					break;
				case 'gif':
					$marcadagua = imagecreatefromgif( $imagem );
					break;
				case 'bmp':
					$marcadagua = imagecreatefrombmp( $imagem );
					break;
				default:
					trigger_error( 'Arquivo de marca d\'água inválido.', E_USER_ERROR );
					return false;
			}
		}
		else
		{
			return false;
		}
		// dimensões
		$marca_w	= imagesx( $marcadagua );
		$marca_h	= imagesy( $marcadagua );
		// retorna imagens com marca d'água
		if ( is_numeric( $alfa ) && ( ( $alfa > 0 ) && ( $alfa < 100 ) ) ) {
			imagecopymerge( $this->img, $marcadagua, $x, $y, 0, 0, $marca_w, $marca_h, $alfa );
		} else {
			imagecopy( $this->img, $marcadagua, $x, $y, 0, 0, $marca_w, $marca_h );
		}
		return $this;
	} // fim marca

	/**
	 * adiciona imagem de marca d'água, com valores fixos
	 * ex: topo_esquerda, topo_direita etc.
	 * Implementação original por Giolvani <inavloig@gmail.com>
	 * @param String $imagem caminho da imagem de marca d'água
	 * @param String $posicao posição/orientação fixa da marca d'água
	 *        [topo, meio, baixo] + [esquerda, centro, direita]
	 * @param Int $alfa valor para transparência (0-100)
	 * @return void
	*/
	protected function marcaFixa( $imagem, $posicao, $alfa = 100 )
	{

		// dimensões da marca d'água
		list( $marca_w, $marca_h ) = getimagesize( $imagem );

		// define X e Y para posicionamento
		switch( $posicao )
		{
			case 'topo_esquerda':
				$x = 0;
				$y = 0;
				break;
			case 'topo_centro':
				$x = ( $this->largura - $marca_w ) / 2;
				$y = 0;
				break;
			case 'topo_direita':
				$x = $this->largura - $marca_w;
				$y = 0;
				break;
			case 'meio_esquerda':
				$x = 0;
				$y = ( $this->altura / 2 ) - ( $marca_h / 2 );
				break;
			case 'meio_centro':
				$x = ( $this->largura - $marca_w ) / 2;
				$y = ( $this->altura / 2 ) - ( $marca_h / 2 );
				break;
			case 'meio_direita':
				$x = $this->largura - $marca_w;
				$y = ( $this->altura / 2) - ( $marca_h / 2 );
				break;
			case 'baixo_esquerda':
				$x = 0;
				$y = $this->altura - $marca_h;
				break;
			case 'baixo_centro':
				$x = ( $this->largura - $marca_w ) / 2;
				$y = $this->altura - $marca_h;
				break;
			case 'baixo_direita':
				$x = $this->largura - $marca_w;
				$y = $this->altura - $marca_h;
				break;
			default:
				return false;
				break;
		} // end switch posicao

		// cria marca
		$this->marca( $imagem, $x, $y, $alfa );
		return $this;
	} // fim marcaFixa

    /**
	 * Aplica filtros avançados como brilho, contraste, pixelate, blur
	 * Requer o GD compilado com a função imagefilter()
	 * http://br.php.net/imagefilter
	 * @param String $filtro constante/nome do filtro
	 * @param Integer $quantidade número de vezes que o filtro deve ser aplicado
	 *		  utilizado em blur, edge, emboss, pixel e rascunho
	 * @param $arg1, $arg2 e $arg3 - ver manual da função imagefilter
	 * @return Object instância atual do objeto, para métodos encadeados
     **/
    public function filtra( $filtro, $quantidade = 1, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL )
    {
        switch( $filtro )
        {
            case 'blur':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_GAUSSIAN_BLUR );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_GAUSSIAN_BLUR );
				}
                break;
            case 'blur2':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_SELECTIVE_BLUR );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_SELECTIVE_BLUR );
				}
                break;
			case 'brilho':
				imagefilter( $this->img, IMG_FILTER_BRIGHTNESS, $arg1 );
				break;
            case 'cinzas':
                imagefilter( $this->img, IMG_FILTER_GRAYSCALE );
                break;				
			case 'colorir':
				imagefilter( $this->img, IMG_FILTER_COLORIZE, $arg1, $arg2, $arg3, $arg4 );
				break;
			case 'contraste':
				imagefilter( $this->img, IMG_FILTER_CONTRAST, $arg1 );
				break;
			case 'edge':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_EDGEDETECT );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_EDGEDETECT );
				}
				break;
			case 'emboss':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_EMBOSS );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_EMBOSS );
				}
				break;

            case 'negativo':
                imagefilter( $this->img, IMG_FILTER_NEGATE );
                break;
            case 'ruido':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_MEAN_REMOVAL );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_MEAN_REMOVAL );
				}                
                break;
			case 'suave':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_SMOOTH, $arg1 );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_SMOOTH, $arg1 );
				}                
				break;
			/* SOMENTE 5.3 ou superior */
			case 'pixel':
				if( is_numeric( $quantidade ) && $quantidade > 1 )
				{
					for( $i = 1; $i <= $quantidade; $i++ )
	                {
	                    imagefilter( $this->img, IMG_FILTER_PIXELATE, $arg1, $arg2 );
	                }
				}
				else
				{
					imagefilter( $this->img, IMG_FILTER_PIXELATE, $arg1, $arg2 );
				}
				break;
            default:
                break;
        }
		return $this;
    } // fim filtrar


//------------------------------------------------------------------------------
// gera imagem de saída

	/**
	 * retorna saída para tela ou arquivo
	 * @param String $destino caminho e nome do arquivo a serem criados
	 * @param Int $qualidade qualidade da imagem no caso de JPEG (0-100)
	 * @return void
	*/
	public function grava( $destino='', $qualidade = 100 )
	{
		// dados do arquivo de destino
		if ( $destino )
		{
			$pathinfo 			= pathinfo( $destino );
			$dir_destino		= $pathinfo['dirname'];
			$extensao_destino 	= strtolower( $pathinfo['extension'] );

			// valida diretório
			if ( !is_dir( $dir_destino ) )
			{
				trigger_error( 'Diretório de destino inválido ou inexistente', E_USER_ERROR );
			}

		}

		if ( !isset( $extensao_destino ) )
		{
			$extensao_destino = $this->extensao;
		}

		switch( $extensao_destino )
		{
			case 'jpg':
			case 'jpeg':
			case 'bmp':
				if ( $destino )
				{
					imagejpeg( $this->img, $destino, $qualidade );
				}
				else
				{
					header( "Content-type: image/jpeg" );
					imagejpeg( $this->img, NULL, $qualidade );
					imagedestroy( $this->img );
					exit;
				}
				break;
			case 'png':
				if ( $destino )
				{
					imagepng( $this->img, $destino );
				}
				else
				{
					header( "Content-type: image/png" );
					imagepng( $this->img );
					imagedestroy( $this->img );
					exit;
				}
				break;
			case 'gif':
				if ( $destino )
				{
					imagegif( $this->img, $destino );
				}
				else
				{
					header( "Content-type: image/gif" );
					imagegif( $this->img );
					imagedestroy( $this->img );
					exit;
				}
				break;
			default:
				return false;
				break;
		}

	} // fim grava

//------------------------------------------------------------------------------
// fim da classe
}

//------------------------------------------------------------------------------
// suporte para a manipulação de arquivos BMP

/*********************************************/
/* Function: ImageCreateFromBMP              */
/* Author:   DHKold                          */
/* Contact:  admin@dhkold.com                */
/* Date:     The 15th of June 2005           */
/* Version:  2.0B                            */
/*********************************************/
if(!function_exists('imagecreatefrombmp')){
	function imagecreatefrombmp($filename) {
	 //Ouverture du fichier en mode binaire
	   if (! $f1 = fopen($filename,"rb")) return FALSE;

	 //1 : Chargement des ent?tes FICHIER
	   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
	   if ($FILE['file_type'] != 19778) return FALSE;

	 //2 : Chargement des ent?tes BMP
	   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
					 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
					 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
	   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
	   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
	   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
	   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
	   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
	   $BMP['decal'] = 4-(4*$BMP['decal']);
	   if ($BMP['decal'] == 4) $BMP['decal'] = 0;

	 //3 : Chargement des couleurs de la palette
	   $PALETTE = array();
	   if ($BMP['colors'] < 16777216)
	   {
		$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
	   }

	 //4 : Cr?ation de l'image
	   $IMG = fread($f1,$BMP['size_bitmap']);
	   $VIDE = chr(0);

	   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
	   $P = 0;
	   $Y = $BMP['height']-1;
	   while ($Y >= 0)
	   {
		$X=0;
		while ($X < $BMP['width'])
		{
		 if ($BMP['bits_per_pixel'] == 24)
			$COLOR = @unpack("V",substr($IMG,$P,3).$VIDE);
		 elseif ($BMP['bits_per_pixel'] == 16)
		 {
			$COLOR = @unpack("n",substr($IMG,$P,2));
			$COLOR[1] = $PALETTE[$COLOR[1]+1];
		 }
		 elseif ($BMP['bits_per_pixel'] == 8)
		 {
			$COLOR = @unpack("n",$VIDE.substr($IMG,$P,1));
			$COLOR[1] = $PALETTE[$COLOR[1]+1];
		 }
		 elseif ($BMP['bits_per_pixel'] == 4)
		 {
			$COLOR = @unpack("n",$VIDE.substr($IMG,floor($P),1));
			if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
			$COLOR[1] = $PALETTE[$COLOR[1]+1];
		 }
		 elseif ($BMP['bits_per_pixel'] == 1)
		 {
			$COLOR = @unpack("n",$VIDE.substr($IMG,floor($P),1));
			if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
			elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
			elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
			elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
			elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
			elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
			elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
			elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
			$COLOR[1] = $PALETTE[$COLOR[1]+1];
		 }
		 else
			return FALSE;
		 imagesetpixel($res,$X,$Y,$COLOR[1]);
		 $X++;
		 $P += $BMP['bytes_per_pixel'];
		}
		$Y--;
		$P+=$BMP['decal'];
	   }

	 //Fermeture du fichier
	   fclose($f1);

	 return $res;

	} // fim function image from BMP
	
	
}

/*------------------------------------------------------------------------------
** File:		gPoint.php
** Description:	PHP class to convert Latitude & Longitude coordinates into
**				UTM & Lambert Conic Conformal Northing/Easting coordinates. 
** Version:		1.3
** Author:		Brenor Brophy
** Email:		brenor dot brophy at gmail dot com
** Homepage:	brenorbrophy.com 
**------------------------------------------------------------------------------
** COPYRIGHT (c) 2005, 2006, 2007, 2008 BRENOR BROPHY
**
** The source code included in this package is free software; you can
** redistribute it and/or modify it under the terms of the GNU General Public
** License as published by the Free Software Foundation. This license can be
** read at:
**
** http://www.opensource.org/licenses/gpl-license.php
**
** This program is distributed in the hope that it will be useful, but WITHOUT 
** ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
** FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. 
**------------------------------------------------------------------------------
**
** Code for datum and UTM conversion was converted from C++ code written by
** Chuck Gantz (chuck dot gantz at globalstar dot com) from
** http://www.gpsy.com/gpsinfo/geotoutm/ This URL has many other references to
** useful information concerning conversion of coordinates.
**
** Rev History
** -----------------------------------------------------------------------------
** 1.0	08/25/2005	Initial Release
** 1.1	05/15/2006	Added software license language to header comments
**					Fixed an error in the convertTMtoLL() method. The latitude
**					calculation had a bunch of variables without $ symbols.
**					Fixed an error in convertLLtoTM() method, The $this-> was
**					missing in front of a couple of variables. Thanks to Bob
**					Robins of Maryland for catching the bugs.
** 1.2	05/18/2007	Added default of NULL to $LongOrigin arguement in convertTMtoLL()
**					and convertLLtoTM() to eliminate warning messages when the
**					methods are called without a value for $LongOrigin.
** 1.3	02/21/2008	Fixed a bug in the distanceFrom method, where the input parameters
**					were not being converted to radians prior to calculating the
**					distance. Thanks to Enrico Benco for finding pointing it out.
*/
define ("meter2nm", (1/1852));
define ("nm2meter", 1852);

/*------------------------------------------------------------------------------
** class gPoint		... for Geographic Point
**
** This class encapsulates the methods for representing a geographic point on the
** earth in three different coordinate systema. Lat/Long, UTM and Lambert Conic
** Conformal.
*/
class gPoint
{
/* Reference ellipsoids derived from Peter H. Dana's website- 
** http://www.colorado.edu/geography/gcraft/notes/datum/datum_f.html
** email: pdana@pdana.com, web page: www.pdana.com
**
** Source:
** Defense Mapping Agency. 1987b. DMA Technical Report: Supplement to Department
** of Defense World Geodetic System 1984 Technical Report. Part I and II.
** Washington, DC: Defense Mapping Agency
*/
	var $ellipsoid = array(//Ellipsoid name, Equatorial Radius, square of eccentricity	
	"Airy"					=>array (6377563, 0.00667054),
	"Australian National"	=>array	(6378160, 0.006694542),
	"Bessel 1841"			=>array	(6377397, 0.006674372),
	"Bessel 1841 Nambia"	=>array	(6377484, 0.006674372),
	"Clarke 1866"			=>array	(6378206, 0.006768658),
	"Clarke 1880"			=>array	(6378249, 0.006803511),
	"Everest"				=>array	(6377276, 0.006637847),
	"Fischer 1960 Mercury"	=>array (6378166, 0.006693422),
	"Fischer 1968"			=>array (6378150, 0.006693422),
	"GRS 1967"				=>array	(6378160, 0.006694605),
	"GRS 1980"				=>array	(6378137, 0.00669438),
	"Helmert 1906"			=>array	(6378200, 0.006693422),
	"Hough"					=>array	(6378270, 0.00672267),
	"International"			=>array	(6378388, 0.00672267),
	"Krassovsky"			=>array	(6378245, 0.006693422),
	"Modified Airy"			=>array	(6377340, 0.00667054),
	"Modified Everest"		=>array	(6377304, 0.006637847),
	"Modified Fischer 1960"	=>array	(6378155, 0.006693422),
	"South American 1969"	=>array	(6378160, 0.006694542),
	"WGS 60"				=>array (6378165, 0.006693422),
	"WGS 66"				=>array (6378145, 0.006694542),
	"WGS 72"				=>array (6378135, 0.006694318),
	"WGS 84"				=>array (6378137, 0.00669438));

	// Properties
	var $a;										// Equatorial Radius
	var	$e2;									// Square of eccentricity
	var	$datum;									// Selected datum
	var	$Xp, $Yp;								// X,Y pixel location
	var $lat, $long;							// Latitude & Longitude of the point
	var $utmNorthing, $utmEasting, $utmZone;	// UTM Coordinates of the point
	var	$lccNorthing, $lccEasting;				// Lambert coordinates of the point
	var $falseNorthing, $falseEasting;			// Origin coordinates for Lambert Projection
	var $latOfOrigin;							// For Lambert Projection
	var $longOfOrigin;							// For Lambert Projection
	var $firstStdParallel;						// For lambert Projection
	var $secondStdParallel;						// For lambert Projection

	// constructor
	function gPoint($datum='WGS 84')			// Default datum is WGS 84
	{
		$this->a = $this->ellipsoid[$datum][0];		// Set datum Equatorial Radius
		$this->e2 = $this->ellipsoid[$datum][1];	// Set datum Square of eccentricity
		$this->datum = $datum;						// Save the datum
	}
	//
	// Set/Get X & Y pixel of the point (used if it is being drawn on an image)
	//
	function setXY($x, $y)
	{
		$this->Xp = $x; $this->Yp = $y;
	}
	function Xp() { return $this->Xp; }
	function Yp() { return $this->Yp; }
	//
	// Set/Get/Output Longitude & Latitude of the point
	//
	function setLongLat($long, $lat)
	{
		$this->long = $long; $this->lat = $lat;
	}
	function Lat() { return $this->lat; }
	function Long() { return $this->long; }
	function printLatLong() { printf("Latitude: %1.10f Longitude: %1.10f",$this->lat, $this->long); }
	//
	// Set/Get/Output Universal Transverse Mercator Coordinates
	//
	function setUTM($easting, $northing, $zone='')	// Zone is optional
	{
		$this->utmNorthing = $northing;
		$this->utmEasting = $easting;
		$this->utmZone = $zone;
	}
	function N() { return $this->utmNorthing; }
	function E() { return $this->utmEasting; }
	function Z() { return $this->utmZone; }
	function printUTM() { print( "Northing: ".$this->utmNorthing.", Easting: ".$this->utmEasting.", Zone: ".$this->utmZone); }
	//
	// Set/Get/Output Lambert Conic Conformal Coordinates
	//
	function setLambert($easting, $northing)
	{
		$this->lccNorthing = $northing;
		$this->lccEasting = $easting;
	}
	function lccN() { return $this->lccNorthing; }
	function lccE() { return $this->lccEasting; }
	function printLambert() { print( "Northing: ".(int)$this->lccNorthing.", Easting: ".(int)$this->lccEasting); }

//------------------------------------------------------------------------------
//
// Convert Longitude/Latitude to UTM
//
// Equations from USGS Bulletin 1532 
// East Longitudes are positive, West longitudes are negative. 
// North latitudes are positive, South latitudes are negative
// Lat and Long are in decimal degrees
// Written by Chuck Gantz- chuck dot gantz at globalstar dot com, converted to PHP by
// Brenor Brophy, brenor dot brophy at gmail dot com
//
// UTM coordinates are useful when dealing with paper maps. Basically the
// map will can cover a single UTM zone which is 6 degrees on longitude.
// So you really don't care about an object crossing two zones. You just get a
// second map of the other zone. However, if you happen to live in a place that
// straddles two zones (For example the Santa Babara area in CA straddles zone 10
// and zone 11) Then it can become a real pain having to have two maps all the time.
// So relatively small parts of the world (like say California) create their own
// version of UTM coordinates that are adjusted to conver the whole area of interest
// on a single map. These are called state grids. The projection system is the
// usually same as UTM (i.e. Transverse Mercator), but the central meridian
// aka Longitude of Origin is selected to suit the logitude of the area being
// mapped (like being moved to the central meridian of the area) and the grid
// may cover more than the 6 degrees of lingitude found on a UTM map. Areas
// that are wide rather than long - think Montana as an example. May still
// have to have a couple of maps to cover the whole state because TM projection
// looses accuracy as you move further away from the Longitude of Origin, 15 degrees
// is usually the limit.
//
// Now, in the case where we want to generate electronic maps that may be
// placed pretty much anywhere on the globe we really don't to deal with the
// issue of UTM zones in our coordinate system. We would really just like a
// grid that is fully contigious over the area of the map we are drawing. Similiar
// to the state grid, but local to the area we are interested in. I call this
// Local Transverse Mercator and I have modified the function below to also
// make this conversion. If you pass a Longitude value to the function as $LongOrigin
// then that is the Longitude of Origin that will be used for the projection.
// Easting coordinates will be returned (in meters) relative to that line of
// longitude - So an Easting coordinate for a point located East of the longitude
// of origin will be a positive value in meters, an Easting coordinate for a point
// West of the longitude of Origin will have a negative value in meters. Northings
// will always be returned in meters from the equator same as the UTM system. The
// UTMZone value will be valid for Long/Lat given - thought it is not meaningful
// in the context of Local TM. If a NULL value is passed for $LongOrigin
// then the standard UTM coordinates are calculated.
//
	function convertLLtoTM($LongOrigin = NULL)
	{
		$k0 = 0.9996;
		$falseEasting = 0.0;

		//Make sure the longitude is between -180.00 .. 179.9
		$LongTemp = ($this->long+180)-(integer)(($this->long+180)/360)*360-180; // -180.00 .. 179.9;
		$LatRad = deg2rad($this->lat);
		$LongRad = deg2rad($LongTemp);

		if (!$LongOrigin)
		{ // Do a standard UTM conversion - so findout what zone the point is in
			$ZoneNumber = (integer)(($LongTemp + 180)/6) + 1;
			// Special zone for South Norway
			if( $this->lat >= 56.0 && $this->lat < 64.0 && $LongTemp >= 3.0 && $LongTemp < 12.0 ) // Fixed 1.1
				$ZoneNumber = 32;
			// Special zones for Svalbard
			if( $this->lat >= 72.0 && $this->lat < 84.0 ) 
			{
				if(      $LongTemp >= 0.0  && $LongTemp <  9.0 ) $ZoneNumber = 31;
				else if( $LongTemp >= 9.0  && $LongTemp < 21.0 ) $ZoneNumber = 33;
				else if( $LongTemp >= 21.0 && $LongTemp < 33.0 ) $ZoneNumber = 35;
				else if( $LongTemp >= 33.0 && $LongTemp < 42.0 ) $ZoneNumber = 37;
			}
			$LongOrigin = ($ZoneNumber - 1)*6 - 180 + 3;  //+3 puts origin in middle of zone
			//compute the UTM Zone from the latitude and longitude
			$this->utmZone = sprintf("%d%s", $ZoneNumber, $this->UTMLetterDesignator());
			// We also need to set the false Easting value adjust the UTM easting coordinate
			$falseEasting = 500000.0;
		}
		$LongOriginRad = deg2rad($LongOrigin);

		$eccPrimeSquared = ($this->e2)/(1-$this->e2);

		$N = $this->a/sqrt(1-$this->e2*sin($LatRad)*sin($LatRad));
		$T = tan($LatRad)*tan($LatRad);
		$C = $eccPrimeSquared*cos($LatRad)*cos($LatRad);
		$A = cos($LatRad)*($LongRad-$LongOriginRad);

		$M = $this->a*((1	- $this->e2/4		- 3*$this->e2*$this->e2/64	- 5*$this->e2*$this->e2*$this->e2/256)*$LatRad 
							- (3*$this->e2/8	+ 3*$this->e2*$this->e2/32	+ 45*$this->e2*$this->e2*$this->e2/1024)*sin(2*$LatRad)
												+ (15*$this->e2*$this->e2/256 + 45*$this->e2*$this->e2*$this->e2/1024)*sin(4*$LatRad) 
												- (35*$this->e2*$this->e2*$this->e2/3072)*sin(6*$LatRad));
	
		$this->utmEasting = ($k0*$N*($A+(1-$T+$C)*$A*$A*$A/6
						+ (5-18*$T+$T*$T+72*$C-58*$eccPrimeSquared)*$A*$A*$A*$A*$A/120)
						+ $falseEasting);

		$this->utmNorthing = ($k0*($M+$N*tan($LatRad)*($A*$A/2+(5-$T+9*$C+4*$C*$C)*$A*$A*$A*$A/24
					 + (61-58*$T+$T*$T+600*$C-330*$eccPrimeSquared)*$A*$A*$A*$A*$A*$A/720)));
		if($this->lat < 0)
			$this->utmNorthing += 10000000.0; //10000000 meter offset for southern hemisphere
	}
//
// This routine determines the correct UTM letter designator for the given latitude
// returns 'Z' if latitude is outside the UTM limits of 84N to 80S
// Written by Chuck Gantz- chuck dot gantz at globalstar dot com, converted to PHP by
// Brenor Brophy, brenor dot brophy at gmail dot com
//
	function UTMLetterDesignator()
	{	
		if((84 >= $this->lat) && ($this->lat >= 72)) $LetterDesignator = 'X';
		else if((72 > $this->lat) && ($this->lat >= 64)) $LetterDesignator = 'W';
		else if((64 > $this->lat) && ($this->lat >= 56)) $LetterDesignator = 'V';
		else if((56 > $this->lat) && ($this->lat >= 48)) $LetterDesignator = 'U';
		else if((48 > $this->lat) && ($this->lat >= 40)) $LetterDesignator = 'T';
		else if((40 > $this->lat) && ($this->lat >= 32)) $LetterDesignator = 'S';
		else if((32 > $this->lat) && ($this->lat >= 24)) $LetterDesignator = 'R';
		else if((24 > $this->lat) && ($this->lat >= 16)) $LetterDesignator = 'Q';
		else if((16 > $this->lat) && ($this->lat >= 8)) $LetterDesignator = 'P';
		else if(( 8 > $this->lat) && ($this->lat >= 0)) $LetterDesignator = 'N';
		else if(( 0 > $this->lat) && ($this->lat >= -8)) $LetterDesignator = 'M';
		else if((-8 > $this->lat) && ($this->lat >= -16)) $LetterDesignator = 'L';
		else if((-16 > $this->lat) && ($this->lat >= -24)) $LetterDesignator = 'K';
		else if((-24 > $this->lat) && ($this->lat >= -32)) $LetterDesignator = 'J';
		else if((-32 > $this->lat) && ($this->lat >= -40)) $LetterDesignator = 'H';
		else if((-40 > $this->lat) && ($this->lat >= -48)) $LetterDesignator = 'G';
		else if((-48 > $this->lat) && ($this->lat >= -56)) $LetterDesignator = 'F';
		else if((-56 > $this->lat) && ($this->lat >= -64)) $LetterDesignator = 'E';
		else if((-64 > $this->lat) && ($this->lat >= -72)) $LetterDesignator = 'D';
		else if((-72 > $this->lat) && ($this->lat >= -80)) $LetterDesignator = 'C';
		else $LetterDesignator = 'Z'; //This is here as an error flag to show that the Latitude is outside the UTM limits

		return($LetterDesignator);
	}

//------------------------------------------------------------------------------
//
// Convert UTM to Longitude/Latitude
//
// Equations from USGS Bulletin 1532 
// East Longitudes are positive, West longitudes are negative. 
// North latitudes are positive, South latitudes are negative
// Lat and Long are in decimal degrees. 
// Written by Chuck Gantz- chuck dot gantz at globalstar dot com, converted to PHP by
// Brenor Brophy, brenor dot brophy at gmail dot com
//
// If a value is passed for $LongOrigin then the function assumes that
// a Local (to the Longitude of Origin passed in) Transverse Mercator
// coordinates is to be converted - not a UTM coordinate. This is the
// complementary function to the previous one. The function cannot
// tell if a set of Northing/Easting coordinates are in the North
// or South hemesphere - they just give distance from the equator not
// direction - so only northern hemesphere lat/long coordinates are returned.
// If you live south of the equator there is a note later in the code
// explaining how to have it just return southern hemesphere lat/longs.
//
	function convertTMtoLL($LongOrigin = NULL)
	{
		$k0 = 0.9996;
		$e1 = (1-sqrt(1-$this->e2))/(1+sqrt(1-$this->e2));
		$falseEasting = 0.0;
		$y = $this->utmNorthing;

		if (!$LongOrigin)
		{ // It is a UTM coordinate we want to convert
			sscanf($this->utmZone,"%d%s",$ZoneNumber,$ZoneLetter);
			if($ZoneLetter >= 'N')
				$NorthernHemisphere = 1;//point is in northern hemisphere
			else
			{
				$NorthernHemisphere = 0;//point is in southern hemisphere
				$y -= 10000000.0;//remove 10,000,000 meter offset used for southern hemisphere
			}
			$LongOrigin = ($ZoneNumber - 1)*6 - 180 + 3;  //+3 puts origin in middle of zone
			$falseEasting = 500000.0;
		}

//		$y -= 10000000.0;	// Uncomment line to make LOCAL coordinates return southern hemesphere Lat/Long
		$x = $this->utmEasting - $falseEasting; //remove 500,000 meter offset for longitude

		$eccPrimeSquared = ($this->e2)/(1-$this->e2);

		$M = $y / $k0;
		$mu = $M/($this->a*(1-$this->e2/4-3*$this->e2*$this->e2/64-5*$this->e2*$this->e2*$this->e2/256));

		$phi1Rad = $mu	+ (3*$e1/2-27*$e1*$e1*$e1/32)*sin(2*$mu) 
					+ (21*$e1*$e1/16-55*$e1*$e1*$e1*$e1/32)*sin(4*$mu)
					+(151*$e1*$e1*$e1/96)*sin(6*$mu);
		$phi1 = rad2deg($phi1Rad);

		$N1 = $this->a/sqrt(1-$this->e2*sin($phi1Rad)*sin($phi1Rad));
		$T1 = tan($phi1Rad)*tan($phi1Rad);
		$C1 = $eccPrimeSquared*cos($phi1Rad)*cos($phi1Rad);
		$R1 = $this->a*(1-$this->e2)/pow(1-$this->e2*sin($phi1Rad)*sin($phi1Rad), 1.5);
		$D = $x/($N1*$k0);

		$tlat = $phi1Rad - ($N1*tan($phi1Rad)/$R1)*($D*$D/2-(5+3*$T1+10*$C1-4*$C1*$C1-9*$eccPrimeSquared)*$D*$D*$D*$D/24
						+(61+90*$T1+298*$C1+45*$T1*$T1-252*$eccPrimeSquared-3*$C1*$C1)*$D*$D*$D*$D*$D*$D/720); // fixed in 1.1
		$this->lat = rad2deg($tlat);

		$tlong = ($D-(1+2*$T1+$C1)*$D*$D*$D/6+(5-2*$C1+28*$T1-3*$C1*$C1+8*$eccPrimeSquared+24*$T1*$T1)
						*$D*$D*$D*$D*$D/120)/cos($phi1Rad);
		$this->long = $LongOrigin + rad2deg($tlong);
	}

//------------------------------------------------------------------------------
// Configure a Lambert Conic Conformal Projection
//
// falseEasting & falseNorthing are just an offset in meters added to the final
// coordinate calculated.
//
// longOfOrigin & LatOfOrigin are the "center" latitiude and longitude of the
// area being projected. All coordinates will be calculated in meters relative
// to this point on the earth.
//
// firstStdParallel & secondStdParallel are the two lines of longitude (that
// is they run east-west) that define where the "cone" intersects the earth.
// Simply put they should bracket the area being projected. 
//
// google is your friend to find out more
//
	function configLambertProjection ($falseEasting, $falseNorthing,
									  $longOfOrigin, $latOfOrigin,
									  $firstStdParallel, $secondStdParallel)
	{
		$this->falseEasting = $falseEasting;
		$this->falseNorthing = $falseNorthing;
		$this->longOfOrigin = $longOfOrigin;
		$this->latOfOrigin = $latOfOrigin;
		$this->firstStdParallel = $firstStdParallel;
		$this->secondStdParallel = $secondStdParallel;
	}

//------------------------------------------------------------------------------
//
// Convert Longitude/Latitude to Lambert Conic Easting/Northing
//
// This routine will convert a Latitude/Longitude coordinate to an Northing/
// Easting coordinate on a Lambert Conic Projection. The configLambertProjection()
// function should have been called prior to this one to setup the specific
// parameters for the projection. The Northing/Easting parameters calculated are
// in meters (because the datum used is in meters) and are relative to the
// falseNorthing/falseEasting coordinate. Which in turn is relative to the
// Lat/Long of origin The formula were obtained from URL:
// http://www.ihsenergy.com/epsg/guid7_2.html.
// Code was written by Brenor Brophy, brenor dot brophy at gmail dot com
//
	function convertLLtoLCC()
	{
		$e = sqrt($this->e2);

		$phi 	= deg2rad($this->lat);						// Latitude to convert
		$phi1	= deg2rad($this->firstStdParallel);			// Latitude of 1st std parallel
		$phi2	= deg2rad($this->secondStdParallel);		// Latitude of 2nd std parallel
		$lamda	= deg2rad($this->long);						// Lonitude to convert
		$phio	= deg2rad($this->latOfOrigin);				// Latitude of  Origin
		$lamdao	= deg2rad($this->longOfOrigin);				// Longitude of  Origin

		$m1 = cos($phi1) / sqrt(( 1 - $this->e2*sin($phi1)*sin($phi1)));
		$m2 = cos($phi2) / sqrt(( 1 - $this->e2*sin($phi2)*sin($phi2)));
		$t1 = tan((pi()/4)-($phi1/2)) / pow(( ( 1 - $e*sin($phi1) ) / ( 1 + $e*sin($phi1) )),$e/2);
		$t2 = tan((pi()/4)-($phi2/2)) / pow(( ( 1 - $e*sin($phi2) ) / ( 1 + $e*sin($phi2) )),$e/2);
		$to = tan((pi()/4)-($phio/2)) / pow(( ( 1 - $e*sin($phio) ) / ( 1 + $e*sin($phio) )),$e/2);
		$t  = tan((pi()/4)-($phi /2)) / pow(( ( 1 - $e*sin($phi ) ) / ( 1 + $e*sin($phi ) )),$e/2);
		$n	= (log($m1)-log($m2)) / (log($t1)-log($t2));
		$F	= $m1/($n*pow($t1,$n));
		$rf	= $this->a*$F*pow($to,$n);
		$r	= $this->a*$F*pow($t,$n);
		$theta = $n*($lamda - $lamdao);

		$this->lccEasting = $this->falseEasting + $r*sin($theta);
		$this->lccNorthing = $this->falseNorthing + $rf - $r*cos($theta);
	}
//------------------------------------------------------------------------------
//
// Convert Easting/Northing on a Lambert Conic projection to Longitude/Latitude
//
// This routine will convert a Lambert Northing/Easting coordinate to an
// Latitude/Longitude coordinate.  The configLambertProjection() function should
// have been called prior to this one to setup the specific parameters for the
// projection. The Northing/Easting parameters are in meters (because the datum
// used is in meters) and are relative to the falseNorthing/falseEasting
// coordinate. Which in turn is relative to the Lat/Long of origin The formula
// were obtained from URL http://www.ihsenergy.com/epsg/guid7_2.html. Code
// was written by Brenor Brophy, brenor dot brophy at gmail dot com
//
	function convertLCCtoLL()
	{
		$e = sqrt($this->e2);

		$phi1	= deg2rad($this->firstStdParallel);			// Latitude of 1st std parallel
		$phi2	= deg2rad($this->secondStdParallel);		// Latitude of 2nd std parallel
		$phio	= deg2rad($this->latOfOrigin);				// Latitude of  Origin
		$lamdao	= deg2rad($this->longOfOrigin);				// Longitude of  Origin
		$E		= $this->lccEasting;
		$N		= $this->lccNorthing;
		$Ef		= $this->falseEasting;
		$Nf		= $this->falseNorthing;

		$m1 = cos($phi1) / sqrt(( 1 - $this->e2*sin($phi1)*sin($phi1)));
		$m2 = cos($phi2) / sqrt(( 1 - $this->e2*sin($phi2)*sin($phi2)));
		$t1 = tan((pi()/4)-($phi1/2)) / pow(( ( 1 - $e*sin($phi1) ) / ( 1 + $e*sin($phi1) )),$e/2);
		$t2 = tan((pi()/4)-($phi2/2)) / pow(( ( 1 - $e*sin($phi2) ) / ( 1 + $e*sin($phi2) )),$e/2);
		$to = tan((pi()/4)-($phio/2)) / pow(( ( 1 - $e*sin($phio) ) / ( 1 + $e*sin($phio) )),$e/2);
		$n	= (log($m1)-log($m2)) / (log($t1)-log($t2));
		$F	= $m1/($n*pow($t1,$n));
		$rf	= $this->a*$F*pow($to,$n);
		$r_	= sqrt( pow(($E-$Ef),2) + pow(($rf-($N-$Nf)),2) );
		$t_	= pow($r_/($this->a*$F),(1/$n));
		$theta_ = atan(($E-$Ef)/($rf-($N-$Nf)));

		$lamda	= $theta_/$n + $lamdao;
		$phi0	= (pi()/2) - 2*atan($t_);
		$phi1	= (pi()/2) - 2*atan($t_*pow(((1-$e*sin($phi0))/(1+$e*sin($phi0))),$e/2));
		$phi2	= (pi()/2) - 2*atan($t_*pow(((1-$e*sin($phi1))/(1+$e*sin($phi1))),$e/2));
		$phi	= (pi()/2) - 2*atan($t_*pow(((1-$e*sin($phi2))/(1+$e*sin($phi2))),$e/2));
		
		$this->lat 	= rad2deg($phi);
		$this->long = rad2deg($lamda);
	}

//------------------------------------------------------------------------------
// This is a useful function that returns the Great Circle distance from the
// gPoint to another Long/Lat coordinate
//
// Result is returned as meters
//
	function distanceFrom($lon1, $lat1)
	{ 
		$lon1 = deg2rad($lon1); $lat1 = deg2rad($lat1); // Added in 1.3
		$lon2 = deg2rad($this->Long()); $lat2 = deg2rad($this->Lat());
 
		$theta = $lon2 - $lon1;
		$dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));

//		Alternative formula supposed to be more accurate for short distances
//		$dist = 2*asin(sqrt( pow(sin(($lat1-$lat2)/2),2) + cos($lat1)*cos($lat2)*pow(sin(($lon1-$lon2)/2),2)));
		return ( $dist * 6366710 ); // from http://williams.best.vwh.net/avform.htm#GCF
	}

//------------------------------------------------------------------------------
// This function also calculates the distance between two points. In this case
// it just uses Pythagoras's theorm using TM coordinates.
//
	function distanceFromTM(&$pt)
	{ 
		$E1 = $pt->E(); 	$N1 = $pt->N();
		$E2 = $this->E(); 	$N2 = $this->N();
 
		$dist = sqrt(pow(($E1-$E2),2)+pow(($N1-$N2),2));
		return $dist; 
	}

//------------------------------------------------------------------------------
// This function geo-references a geoPoint to a given map. This means that it
// calculates the x,y pixel coordinate that coresponds to the Lat/Long value of
// the geoPoint. The calculation is done using the Transverse Mercator(TM)
// coordinates of the gPoint with respect to the TM coordinates of the center
// point of the map. So this only makes sense if you are using Local TM
// projection.
//
// $rX & $rY are the pixel coordinates that corespond to the Northing/Easting
// ($rE/$rN) coordinate it is to this coordinate that the point will be
// geo-referenced. The $LongOrigin is needed to make sure the Easting/Northing
// coordinates of the point are correctly converted.
//
	function gRef($rX, $rY, $rE, $rN, $Scale, $LongOrigin)
	{
		$this->convertLLtoTM($LongOrigin);
		$x = (($this->E() - $rE) / $Scale)		// The easting in meters times the scale to get pixels
												// is relative to the center of the image so adjust to
			+ ($rX);							// the left coordinate.
		$y = $rY -  							// Adjust to bottom coordinate.
			(($rN - $this->N()) / $Scale);		// The northing in meters
												// relative to the equator. Subtract center point northing
												// to get relative to image center and convert meters to pixels
		$this->setXY((int)$x,(int)$y);			// Save the geo-referenced result.
	}
} // end of class gPoint
?>
