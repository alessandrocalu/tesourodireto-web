<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('usuario');
		$this->load->model('CarteiraModel', 'carteira');
		$this->load->model('cotacao');
		$this->load->model('titulo');
	}

	private function getMesDia($date) {
		return date("d/m", strtotime($date));
	}

	private function ordenaDatas($dados) {
		$data = [];
		reset($dados);
		foreach ($dados as $key => $value) {
			$data[] = array("label" => $key, "value" => $value);				
		}
		return $data;
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
		{
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $data['admin'] = ($usuario['perfil'] == "admin");
			$data['main_content'] = 'welcome';

			//inha X,Y (onde X é valor tempo e Y é valor do papel) e as linhas devem contemplar a valorização dos "Carteira vs Cotações”.
			$dados = array();
			$dados_quantidade = array();
			$dados_montante = array();
			$dados_montante_atual = array();
			$dados_atual = array();
			$datas = array();
			$total_comprado = 0;
			$total_venda = 0;

			$carteira = $this->carteira->getCarteira($this->session->userdata('logged_in')['id']);
			if ($carteira != ""){

				$tituloOld = 0;
				foreach ($carteira as $titulo) {
					$data_cotacao = date("Y-m-d", strtotime("-30 days"));
					$cotacoes = $this->cotacao->getCotacoesUlt30Dias($titulo["titulo_id"]);
					$atual = $this->titulo->get($titulo["titulo_id"]);

					while ($data_cotacao <= date("Y-m-d")) {
						if ($data_cotacao >= date("Y-m-d", strtotime($titulo["dt_compra"])) ){
							$valorCotacao = $titulo["preco_compra"];
							reset($cotacoes);
							foreach ($cotacoes as $cotacao) {
								if (date("Y-m-d", strtotime($cotacao["dt_atualizacao"])) > $data_cotacao){
									break;	
								}
								$valorCotacao = 1*$cotacao["preco_compra"];
							}	
							if (!isset($datas[$this->getMesDia($data_cotacao)])) {
								$datas[$this->getMesDia($data_cotacao)] = 0;
							}
							$datas[$this->getMesDia($data_cotacao)] = 
								$datas[$this->getMesDia($data_cotacao)] + ($titulo["quantidade"]*$valorCotacao); 
						}
						$data_cotacao = date("Y-m-d", strtotime("+1 day", strtotime($data_cotacao)));
					}

					if ($tituloOld != $titulo["titulo_id"]) {
						$dados_quantidade[] = array("sigla" => $atual["sigla"], "nome" => $titulo["nome"], "quantidade" => $titulo["quantidade"]);
						
						$dados_montante[] = array("sigla" => $atual["sigla"], "nome" => $titulo["nome"], "montante" => ($titulo["quantidade"]*$titulo["preco_compra"]));
												
						$dados_montante_atual[] = array("sigla" => $atual["sigla"], "nome" => $titulo["nome"], "montante" => ($titulo["quantidade"]*$atual["preco_compra"]));
						
						$tituloOld = $titulo["titulo_id"];
					}
					else
					{
						$dados_quantidade[count($dados_quantidade)-1]["quantidade"] =
							$dados_quantidade[count($dados_quantidade)-1]["quantidade"] + $titulo["quantidade"];	

						$dados_montante[count($dados_montante)-1]["montante"] = 
							$dados_montante[count($dados_montante)-1]["montante"] +
							($titulo["quantidade"]*$titulo["preco_compra"]);

						$dados_montante_atual[count($dados_montante_atual)-1]["montante"] =
							$dados_montante_atual[count($dados_montante_atual)-1]["montante"] +
							($titulo["quantidade"]*$atual["preco_compra"]);  	
					}	

					$total_comprado += ($titulo["quantidade"]*$titulo["preco_compra"]);
					$total_venda += ($titulo["quantidade"]*$atual["preco_compra"]);
					
					$dados_atual[] = array(
						"sigla" => $atual["sigla"], 
						"nome" => $atual["nome"], 
						"compra" => $atual["preco_compra"], 
						"venda" => $atual["preco_venda"], 
						"comprado" => $titulo["preco_compra"], 
						"quantidade" => $titulo["quantidade"]);
				}
			}
			$dados[] = array("sigla" => "Lucro Liquido Total(R$)", 
								 "nome" => "Lucro Liquido Total(R$)", 
								 "dados" => $this->ordenaDatas($datas));	
			$data['grafico_carteira_cotacoes'] = $dados;

			//○ Gráfico pizza, com as porcentagens dos registros que contemplam sua carteira.
			$data['grafico_carteira_quantidade'] = $dados_quantidade;
			$data['grafico_carteira_montante'] = $dados_montante;
			$data['grafico_carteira_montante_atual'] = $dados_montante_atual;
			$data['grafico_carteira_atual'] = $dados_atual;
			$data['total_comprado'] = $total_comprado;
			$data['total_venda'] = $total_venda;

			$this->load->view('includes/template', $data);
			$this->load->view('home', $data);
			$this->load->view('includes/footer', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}

	public function logData() {

		$data['json'] = json_encode($this->tb_log->getDashboardData());
		$this->load->view('includes/json_view', $data);
	}
}
