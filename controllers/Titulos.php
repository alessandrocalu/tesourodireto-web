<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titulos extends CI_Controller {

	function __construct()
	{
		parent::__construct();
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
			$data['main_content'] = 'titulos/index';

			$pageSize = $this->input->get('pageSize') == '' ? 10 : $this->input->get('pageSize');
			$csv = $this->input->get('csv') == '' ? false : true;

			$pgSettings = array(
				'page' => $this->input->get('page') == '' ? 1 : $this->input->get('page'),
				'rowsPerPage' => $pageSize,
				'enabled' => true
			);
			$dataResult = $this->titulo->get_filtered($pgSettings, $this->input->get('filtro'));

			$data['data_rows'] = $dataResult;

			$data['filtro'] = $this->input->get('filtro');
			$data['pageSize'] = $pageSize;


			$this->load->view('includes/template', $data);
            $this->load->view('titulos/index', $data);
            $this->load->view('includes/footer', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}

	public function adicionar() {

		if($this->session->userdata('logged_in'))
		{
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $data['admin'] = ($usuario["perfil"] == "admin");
			$data['main_content'] = 'titulos/adicionar';
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
		$this->form_validation->set_rules('nome', 'Título', 'trim|required|xss_clean');

    	if($this->form_validation->run() == true) {

            $titulo = array(
				'nome' => $this->input->post('nome'),
				'descricao' => $this->input->post('descricao'),
                'sigla' => $this->input->post('sigla'),
                'tx_compra' => $this->input->post('tx_compra'),
                'tx_venda' => $this->input->post('tx_venda'),
                'preco_compra' => $this->input->post('preco_compra'),
                'preco_venda' => $this->input->post('preco_venda')
			);

            $dt_ult_alteracao = $this->input->post('dt_ult_alteracao');
            if ($dt_ult_alteracao != ""){
                $dt_ult_alteracao = substr($dt_ult_alteracao, 6, 4)."-".substr($dt_ult_alteracao, 3, 2)."-".substr($dt_ult_alteracao, 0, 2).((strlen($dt_ult_alteracao) > 9)?substr($dt_ult_alteracao, 10, strlen($dt_ult_alteracao) - 9):'');
            }
            $titulo["dt_ult_alteracao"] = $dt_ult_alteracao; 


            $dt_vencimento = $this->input->post('dt_vencimento');
            if ($dt_vencimento != ""){
                $dt_vencimento = substr($dt_vencimento, 6, 4)."-".substr($dt_vencimento, 3, 2)."-".substr($dt_vencimento, 0, 2);
            }
            $titulo["dt_vencimento"] = $dt_vencimento;
            

            if($this->titulo->insert($titulo)) {

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
            
            $data['details'] = $this->titulo->get($id);
            
            $data['main_content'] = 'titulos/editar';
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
        
		$this->form_validation->set_rules('nome', 'Título', 'trim|required|xss_clean');


		if($this->form_validation->run() == true)
		{

            $titulo = array(
				'nome' => $this->input->post('nome'),
				'descricao' => $this->input->post('descricao'),
                'sigla' => $this->input->post('sigla'),
                'tx_compra' => $this->input->post('tx_compra'),
                'tx_venda' => $this->input->post('tx_venda'),
                'preco_compra' => $this->input->post('preco_compra'),
                'preco_venda' => $this->input->post('preco_venda')
			);

            $dt_ult_alteracao = $this->input->post('dt_ult_alteracao');
            if ($dt_ult_alteracao != ""){
                $dt_ult_alteracao = substr($dt_ult_alteracao, 6, 4)."-".substr($dt_ult_alteracao, 3, 2)."-".substr($dt_ult_alteracao, 0, 2).((strlen($dt_ult_alteracao) > 9)?substr($dt_ult_alteracao, 10, strlen($dt_ult_alteracao) - 9):'');
            }
            $titulo["dt_ult_alteracao"] = $dt_ult_alteracao; 


            $dt_vencimento = $this->input->post('dt_vencimento');
            if ($dt_vencimento != ""){
                $dt_vencimento = substr($dt_vencimento, 6, 4)."-".substr($dt_vencimento, 3, 2)."-".substr($dt_vencimento, 0, 2);
            }
            $titulo["dt_vencimento"] = $dt_vencimento;
            
            $this->titulo->update($id, $titulo);

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
		$data['details'] = $this->titulo->get($id);

		$data['main_content'] = 'titulos/modal_excluir';
		$this->load->view('includes/template_modal', $data);
	}

	// exclusão permanente de fonte
	public function excluir_post(){
		if ($this->titulo->delete($this->input->post('id'))) {
			$data['json'] = '{"message": "Registro excluído com sucesso!", "success": 1}';
			$this->load->view('includes/json_view', $data);
		} else {
			$data['json'] = '{"message": "Erro ao excluir registro!!!", "success": 0}';
			$this->load->view('includes/json_view', $data);
		}
	}
}
