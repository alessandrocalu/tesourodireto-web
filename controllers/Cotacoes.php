<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotacoes extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('cotacao');
		$this->load->model('titulo');
		$this->load->model('usuario');
		$this->load->helper(array('form', 'site_helper'));
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
		{
        	$usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
			$data['admin'] = ($usuario['perfil'] == "admin");
			$data['main_content'] = 'welcome';
			$this->load->view('includes/template', $data);
			$this->load->view('cotacoes', $data);
			$this->load->view('includes/footer', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account/login', 'refresh');
		}
	}

	public function logData() {

		$data['json'] = json_encode($this->tb_log->getDashboardData());
		$this->load->view('includes/json_view', $data);
	}

	public function historico($titulo_id)
	{
		if($this->session->userdata('logged_in'))
		{
			$usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
			$data['admin'] = ($usuario['perfil'] == "admin");
			$titulo = $this->titulo->get($titulo_id);
			
			$data['main_content'] = 'cotacoes/historico';

			$pageSize = $this->input->get('pageSize') == '' ? 10 : $this->input->get('pageSize');
			$csv = $this->input->get('csv') == '' ? false : true;

			$pgSettings = array(
				'page' => $this->input->get('page') == '' ? 1 : $this->input->get('page'),
				'rowsPerPage' => $pageSize,
				'enabled' => true
			);


			$dataIni = $this->input->get('dataIni');
            if ($dataIni != ""){
                $dataIni = substr($dataIni, 6, 4)."-".substr($dataIni, 3, 2)."-".substr($dataIni, 0, 2);
            }

            $dataFim = $this->input->get('dataFim');
            if ($dataFim != ""){
                $dataFim = substr($dataFim, 6, 4)."-".substr($dataFim, 3, 2)."-".substr($dataFim, 0, 2);
            }

			$dataResult = $this->cotacao->get_filtered($pgSettings, $titulo_id, $dataIni, $dataFim);

			$data['data_rows'] = $dataResult;

			$data['titulo_id'] = $titulo_id;
			$data['nomeTitulo'] = $titulo["nome"];
			$data['siglaTitulo'] = $titulo["sigla"];
			$data['dataIni'] = $this->input->get('dataIni');
			$data['dataFim'] = $this->input->get('dataFim');
			$data['pageSize'] = $pageSize;


			$this->load->view('includes/template', $data);
            $this->load->view('cotacoes/historico', $data);
            $this->load->view('includes/footer', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}

	public function adicionar($titulo_id) {

		if($this->session->userdata('logged_in'))
		{
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $titulo = $this->titulo->get($titulo_id);

            $data['admin'] = ($usuario["perfil"] == "admin");
            $data['titulo_id'] = $titulo_id;
			$data['nomeTitulo'] = $titulo["nome"];
			$data['main_content'] = 'cotacoes/adicionar';
			$this->load->view('includes/template_modal', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}


	public function adicionar_post() {

		//falta verificar se é uma cotação mais atual e colocar em em tabela de titulo mais atual

		$this->load->library('form_validation');

		$id = $this->input->post('id');
		$this->form_validation->set_rules('titulo_id', 'Título', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dt_atualizacao', 'Data', 'trim|required|xss_clean');

    	if($this->form_validation->run() == true) {

            $cotacao = array(
				'titulo_id' => $this->input->post('titulo_id'),
                'tx_compra' => $this->input->post('tx_compra'),
                'tx_venda' => $this->input->post('tx_venda'),
                'preco_compra' => $this->input->post('preco_compra'),
                'preco_venda' => $this->input->post('preco_venda')
			);

            $dt_atualizacao = $this->input->post('dt_atualizacao');
            if ($dt_atualizacao != ""){
                $dt_atualizacao = substr($dt_atualizacao, 6, 4)."-".substr($dt_atualizacao, 3, 2)."-".substr($dt_atualizacao, 0, 2).((strlen($dt_atualizacao) > 9)?substr($dt_atualizacao, 10, strlen($dt_atualizacao) - 9):'');
            }
            $cotacao["dt_atualizacao"] = $dt_atualizacao; 


            $dt_vencimento = $this->input->post('dt_vencimento');
            if ($dt_vencimento != ""){
                $dt_vencimento = substr($dt_vencimento, 6, 4)."-".substr($dt_vencimento, 3, 2)."-".substr($dt_vencimento, 0, 2);
            }
            $cotacao["dt_vencimento"] = $dt_vencimento;
            

            if($this->cotacao->insert($cotacao)) {

                $data['json'] = '{"message": "Registro adicionado com sucesso!", "success": 1}';
                $this->load->view('includes/json_view', $data);

                return;
            }
		}

        $erro = "Erro ao adicionar registro!";
        $list_erros = $this->form_validation->error_array();
        if (isset($list_erros) && is_array($list_erros) && count($list_erros)){
            reset($list_erros);
            $erro = "";
            foreach ($list_erros as $strErro) {
                if ($erro != ""){
                    $erro .= "<br> ";    
                }
                $erro .= $strErro;
            }
        }
        $data['json'] = '{"message" : "'.$erro.'", "success": 0}';
        $this->load->view('includes/json_view', $data);

	}
	
	// carrega modal bootstrap para exclusão de fontes
	public function modal_excluir($id)
	{
		$cotacao = $this->cotacao->get($id);
		$data['details'] = $cotacao;
		$titulo = $this->titulo->get($cotacao["titulo_id"]);
        $data['titulo_id'] = $titulo["id"];
		$data['nomeTitulo'] = $titulo["nome"];

		$data['main_content'] = 'cotacoes/modal_excluir';
		$this->load->view('includes/template_modal', $data);
	}

	// exclusão permanente de fonte
	public function excluir_post(){
		if ($this->cotacao->delete($this->input->post('id'))) {
			$data['json'] = '{"message": "Registro excluído com sucesso!", "success": 1}';
			$this->load->view('includes/json_view', $data);
		} else {
			$data['json'] = '{"message": "Erro ao excluir registro!!!", "success": 0}';
			$this->load->view('includes/json_view', $data);
		}
	}

	// API para consulta de cotações
	public function getcotacaoapi(){
		if($this->session->userdata('logged_in'))
		{

        	$titulo_id = $this->input->get("titulo_id");
        	$data = $this->input->get("data");

            if ($data != ""){
                $data = substr($data, 6, 4)."-".substr($data, 3, 2)."-".substr($data, 0, 2).((strlen($data) > 9)?substr($data, 10, strlen($data) - 9):'');
            }

            $cotacoes = $this->cotacao->getCotacao($titulo_id, $data);

            if ($cotacoes == ""){
         		echo "Cotação não encontrada.";  
         		return; 	
            }

            echo $cotacoes["preco_compra"];
            return;
        }
        else
        {
        	echo "Acesso negado!";
            return;
        }	


	}


	//API para carga de cotações
	public function setcotacaoapi(){

		$erros_message = "";

		$sigla = $this->input->get('sigla');
		if ($sigla == ""){
			$erros_message .= " Campo sigla é obrigatório. ";
		}


		$nome = $this->input->get('nome');
		if ($nome == ""){
			$erros_message .= " Campo nome é obrigatório. ";
		}

		$descricao = $this->input->get('descricao');
		if ($descricao == ""){
			$erros_message .= " Campo descricao é obrigatório. ";
		}

		$tx_compra = $this->input->get('tx_compra');
		if ($tx_compra == ""){
			$erros_message .= " Campo tx_compra é obrigatório. ";
		}

		$tx_venda = $this->input->get('tx_venda');
		if ($tx_venda == ""){
			$erros_message .= " Campo tx_venda é obrigatório. ";
		}

		$preco_compra = $this->input->get('preco_compra');
		if ($preco_compra == ""){
			$erros_message .= " Campo preco_compra é obrigatório. ";
		}

		$preco_venda = $this->input->get('preco_venda');
		if ($preco_venda == ""){
			$erros_message .= " Campo preco_venda é obrigatório. ";
		}

		$dt_atualizacao = $this->input->get('dt_atualizacao');
		if ($dt_atualizacao == ""){
			$erros_message .= " Campo dt_atualizacao(AAAA-MM-DD) é obrigatório. ";
		}

		$dt_vencimento = $this->input->get('dt_vencimento');
		if ($dt_vencimento == ""){
			$erros_message .= " Campo dt_vencimento(AAAA-MM-DD) é obrigatório. ";
		}


		if($erros_message == "") {

			$sigla = $this->input->get('sigla');

			$titulo = array(
				'nome' => $nome,
				'descricao' => $descricao,
	            'sigla' => $sigla,
	            'tx_compra' => $tx_compra,
	            'tx_venda' => $tx_venda,
	            'preco_compra' => $preco_compra,
	            'preco_venda' => $preco_venda,
	            'dt_ult_alteracao' => $dt_atualizacao,
	            'dt_vencimento' => $dt_vencimento
			);


			$tituloAtual = $this->titulo->get_sigla_titulo($sigla);
			
			if ($tituloAtual != ""){
				$titulo_id = $tituloAtual[0]["id"];
				if ($tituloAtual[0]["dt_ult_alteracao"] < $dt_atualizacao){
					$this->titulo->update($titulo_id, $titulo);
				}	
			}
			else
			{
				$titulo_id = $this->titulo->insert($titulo);
			}	


			$cotacao = array(
				'titulo_id' => $titulo_id,
	            'tx_compra' => $tx_compra,
	            'tx_venda' => $tx_venda,
	            'preco_compra' => $preco_compra,
	            'preco_venda' => $preco_venda,
	            'dt_atualizacao' => $dt_atualizacao,
	            'dt_vencimento' => $dt_vencimento
			);

			$cotacaoAtual =  $this->cotacao->getCotacao($titulo_id, $dt_atualizacao);

			if ($cotacaoAtual != ""){
				$cotacao_id = $cotacaoAtual["id"];
				$this->cotacao->update($cotacao_id, $cotacao);
			}
			else
			{
				$titulo_id = $this->cotacao->insert($cotacao);
			}	

			echo "OK";
			return;
		}

		echo "ERRO ".$erros_message;
		return;	

	}	
}
