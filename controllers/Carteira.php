<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carteira extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('CarteiraModel', 'carteira');
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
			
			$data['main_content'] = 'carteira';

			$pageSize = $this->input->get('pageSize') == '' ? 10 : $this->input->get('pageSize');
			$csv = $this->input->get('csv') == '' ? false : true;

			$pgSettings = array(
				'page' => $this->input->get('page') == '' ? 1 : $this->input->get('page'),
				'rowsPerPage' => $pageSize,
				'enabled' => true
			);
			$dataResult = $this->carteira->get_filtered($pgSettings, $usuario["id"], $this->input->get('titulo_id'), $this->input->get('dataIni'), $this->input->get('dataFim'));

			$data['data_rows'] = $dataResult;

			$data['titulo_id'] = $this->input->get('titulo_id');
			$data['dataIni'] = $this->input->get('dataIni');
			$data['dataFim'] = $this->input->get('dataFim');
			$data['pageSize'] = $pageSize;


			$this->load->view('includes/template', $data);
            $this->load->view('carteira/carteira', $data);
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


	public function adicionar() {

		if($this->session->userdata('logged_in'))
		{
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            
            $data['admin'] = ($usuario["perfil"] == "admin");
			$data['main_content'] = 'carteira/adicionar';
			$data['titutos'] = $this->titulo->get_all();
			$this->load->view('includes/template_modal', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}


	public function adicionar_post() {

		$this->load->library('form_validation');

		$id = $this->input->post('id');
		$this->form_validation->set_rules('titulo_id', 'Título', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dt_compra', 'Data Compra', 'trim|required|xss_clean');
		$this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required|xss_clean');
		$this->form_validation->set_rules('preco_compra', 'Preço Compra', 'trim|required|xss_clean');

    	if($this->form_validation->run() == true) {

            $carteira = array(
            	'usuario_id' => $this->session->userdata('logged_in')['id'],
				'titulo_id' => $this->input->post('titulo_id'),
				'quantidade' => $this->input->post('quantidade'),
                'preco_compra' => $this->input->post('preco_compra'),
                'tx_pactuada' => $this->input->post('tx_pactuada'),
                'tx_bvmf' => $this->input->post('tx_bvmf'),
                'tx_corretora' => $this->input->post('tx_corretora'),
                'tx_iof' => $this->input->post('tx_iof'),
                'tx_ir' => $this->input->post('tx_ir')
			);

            $dt_compra = $this->input->post('dt_compra');
            if ($dt_compra != ""){
                $dt_compra = substr($dt_compra, 6, 4)."-".substr($dt_compra, 3, 2)."-".substr($dt_compra, 0, 2).((strlen($dt_compra) > 9)?substr($dt_compra, 10, strlen($dt_compra) - 9):'');
            }
            $carteira["dt_compra"] = $dt_compra; 


            if($this->carteira->insert($carteira)) {

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


	public function editar($id)
	{
        if($this->session->userdata('logged_in'))
        {
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $data['admin'] = ($usuario["perfil"] == "admin");

            $data['details'] = $this->carteira->get($id);
            $data['titutos'] = $this->titulo->get_all();
            $data['main_content'] = 'carteira/editar';
            $this->load->view('includes/template_modal', $data);
        }
        else
        {
            //If no session, redirect to login page
            redirect('account', 'refresh');
        }
	}

	public function editar_post() {

		$this->load->library('form_validation');

		$id = $this->input->post('id');
		$this->form_validation->set_rules('titulo_id', 'Título', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dt_compra', 'Data Compra', 'trim|required|xss_clean');
		$this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required|xss_clean');
		$this->form_validation->set_rules('preco_compra', 'Preço Compra', 'trim|required|xss_clean');;


		if($this->form_validation->run() == true)
		{

            $carteira = array(
            	'usuario_id' => $this->session->userdata('logged_in')['id'],
				'titulo_id' => $this->input->post('titulo_id'),
				'quantidade' => $this->input->post('quantidade'),
                'preco_compra' => $this->input->post('preco_compra'),
                'tx_pactuada' => $this->input->post('tx_pactuada'),
                'tx_bvmf' => $this->input->post('tx_bvmf'),
                'tx_corretora' => $this->input->post('tx_corretora'),
                'tx_iof' => $this->input->post('tx_iof'),
                'tx_ir' => $this->input->post('tx_ir')
			);

            $dt_compra = $this->input->post('dt_compra');
            if ($dt_compra != ""){
                $dt_compra = substr($dt_compra, 6, 4)."-".substr($dt_compra, 3, 2)."-".substr($dt_compra, 0, 2).((strlen($dt_compra) > 9)?substr($dt_compra, 10, strlen($dt_compra) - 9):'');
            }
            $carteira["dt_compra"] = $dt_compra;  

            $this->carteira->update($id, $carteira);

            $data['json'] = '{"message": "Registro alterado com sucesso!", "success": 1}';
            $this->load->view('includes/json_view', $data);

            return;
		}

        $data['json'] = '{"message" : "Erro ao alterar registro!", "success": 0}';
        $this->load->view('includes/json_view', $data);
	}
	
	// carrega modal bootstrap para exclusão de fontes
	public function modal_excluir($id)
	{
		$carteira = $this->carteira->get($id);
		$data['details'] = $carteira;
		$titulo = $this->titulo->get($carteira["titulo_id"]);
        $data['titulo_id'] = $titulo["id"];
		$data['nomeTitulo'] = $titulo["nome"];
		$data['siglaTitulo'] = $titulo["sigla"];

		$data['main_content'] = 'carteira/modal_excluir';
		$this->load->view('includes/template_modal', $data);
	}

	// exclusão permanente de fonte
	public function excluir_post(){
		if ($this->carteira->delete($this->input->post('id'))) {
			$data['json'] = '{"message": "Registro excluído com sucesso!", "success": 1}';
			$this->load->view('includes/json_view', $data);
		} else {
			$data['json'] = '{"message": "Erro ao excluir registro!!!", "success": 0}';
			$this->load->view('includes/json_view', $data);
		}
	}
}
