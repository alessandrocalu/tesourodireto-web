<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class ApiVesti extends REST_Controller {


	function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        parent::__construct();

        $this->load->model('tb_usuario');
        $this->load->model('tb_empresa');
        $this->load->model('tb_produto');
        $this->load->model('tb_smtp');
        $this->load->model('tb_forma_pagamento');
        $this->load->model('tb_pedido');
        $this->load->model('tb_lista');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['registros_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['registros_post']['limit'] = 500; // 100 requests per hour per user/key
    }


	public function autenticar_post() {
        $login = $this->post('login');
        $senha = $this->post('senha');
        //$chave = $this->post('chave');
        
        $result = $this->tb_usuario->login($login, $senha);
        if($result /*&& $result->empresa_id == $cliente['id']*/) {
            
            $result = $this->tb_usuario->get($result->id);
            if($result) {
                $this->response([
                    'id' => intval($result['id']),
                    'nome' => $result['nome'],
                    'email' => $result['email'],
                    'celular' => $result['celular'],
                    'empresa' => $result['empresa'],
                    'empresa_cnpj' => $result['empresa_cnpj'],
                    'imagem_exibicao' => (isset($result['imagem_exibicao']) && $result['imagem_exibicao'] != '') ? base_url() . $result['imagem_exibicao'] : '',
                    'imagem_exibicao_thumb1' => (isset($result['imagem_exibicao_thumb1']) && $result['imagem_exibicao_thumb1'] != '') ? base_url() . $result['imagem_exibicao_thumb1'] : '',
                ], REST_Controller::HTTP_OK);
            }
             
        } else {
            $this->response(['id' => 0,
                'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
        }        
    }
    
    public function consulta_usuario_post() {
        $id = $this->post('id');
        
        $result = $this->tb_usuario->get($id);
        if($result) {
            $this->response([
                'id' => intval($result['id']),
                'nome' => $result['nome'],
                'email' => $result['email'],
                'celular' => $result['celular'],
                'empresa' => $result['empresa'],
                'empresa_cnpj' => $result['empresa_cnpj'],
                'imagem_exibicao' =>  (isset($result['imagem_exibicao']) && $result['imagem_exibicao'] != '') ? base_url() . $result['imagem_exibicao'] : '',
                'imagem_exibicao_thumb1' => (isset($result['imagem_exibicao_thumb1']) && $result['imagem_exibicao_thumb1'] != '') ? base_url() . $result['imagem_exibicao_thumb1'] : '',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response(['id' => 0,
                'message' => 'Usuário não existe.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }
    
    public function enviar_foto_usuario_post() {
        $login = $this->post('login');
        //echo $login; exit;
        //$senha = $this->input->post('senha');
        //$chave = $this->post('chave');
        
        $result = $this->tb_usuario->getByLogin($login);
        if($result /*&& $result->empresa_id == $cliente['id']*/) {
            
            $uploadfile = '';
            $thumbFile = '';
            if(isset($_FILES['foto'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $fileName = md5(uniqid(rand(), true));
                $uploadfile = 'uploads/' . $fileName . '.' . $ext;
                $thumbFile = 'uploads/100x100/' . $fileName . '.' . $ext;                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], FCPATH . $uploadfile)) {
                    createThumbImage($uploadfile, $thumbFile, 100, 100, $ext);                    
                }
            }
            
            if(strlen($uploadfile) > 0) {
                $usuario['imagem_exibicao'] = $uploadfile;
            }
            
            if(strlen($thumbFile) > 0) {
                $usuario['imagem_exibicao_thumb1'] = $thumbFile;
            }
            
            $this->tb_usuario->update($result['id'], $usuario, null);
            
            $result = $this->tb_usuario->get($result['id']);
            if($result) {
                $this->response([
                    'id' => intval($result['id']),
                    'imagem_exibicao' =>  (isset($result['imagem_exibicao']) && $result['imagem_exibicao'] != '') ? base_url() . $result['imagem_exibicao'] : '',
                    'imagem_exibicao_thumb1' => (isset($result['imagem_exibicao_thumb1']) && $result['imagem_exibicao_thumb1'] != '') ? base_url() . $result['imagem_exibicao_thumb1'] : '',
                    'message' => 'Atualizado com sucesso!'
                ], REST_Controller::HTTP_OK);
            
                return;
            } else {
                $this->response(['id' => 0,
                    'message' => 'Usuário não encontrado.'], REST_Controller::HTTP_BAD_REQUEST);
                    
                return;
            }
             
        } else {
            $this->response(['id' => 0,
                'message' => 'Usuário não encontrado.'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function atualizar_usuario_post() {
        $login = $this->post('login');
        $senha = $this->post('senha');
        //$chave = $this->post('chave');
        
        $result = $this->tb_usuario->getByLogin($login);
        if($result /*&& $result->empresa_id == $cliente['id']*/) {
            
            /*$uploadfile = '';
            if(isset($_FILES['foto'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $uploadfile = 'uploads/' . md5(uniqid(rand(), true)) . '.' . $ext;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], FCPATH . $uploadfile)) {
                    //ok
                }
            }*/
            
            $empresa_id = $this->post('empresa_id');
            $nome = $this->post('nome');
            $email = $this->post('email');
            $celular = $this->post('celular');
            $nova_senha = $this->post('nova_senha');
            $rep_nova_senha = $this->post('rep_nova_senha');
            
            $usuario = array();
            
            if(isset($empresa_id)) {
                $usuario['empresa_id'] = $empresa_id;
            }
            
            if(isset($nome)) {
                $usuario['nome'] = $nome;
            }
            
            if(isset($email)) {
                $usuario['email'] = $email;
            }
            
            if(isset($celular)) {
                $usuario['celular'] = $celular;
            }
            
            if(isset($nova_senha) && isset($rep_nova_senha)) {
                if($nova_senha == $rep_nova_senha) {
                    
                    if (trim($ret['senha']) == md5(trim($senha))) {
                        $usuario['senha'] = md5($senha);
                    } else {
                        $this->response(['id' => 0,
                            'message' => 'Nova senha não confere.'], REST_Controller::HTTP_BAD_REQUEST);
                        return;
                    }                    
                } else {
                    $this->response(['id' => 0,
                        'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }
            }
            
            $this->tb_usuario->update($result['id'], $usuario, null);
            
            $result = $this->tb_usuario->get($result['id']);
            if($result) {
                $this->response([
                    'id' => intval($result['id']),
                    'nome' => $result['nome'],
                    'email' => $result['email'],
                    'celular' => $result['celular'],
                    'empresa' => $result['empresa'],
                    'empresa_cnpj' => $result['empresa_cnpj'],
                    'imagem_exibicao' =>  (isset($result['imagem_exibicao']) && $result['imagem_exibicao'] != '') ? base_url() . $result['imagem_exibicao'] : '',
                    'imagem_exibicao_thumb1' => (isset($result['imagem_exibicao_thumb1']) && $result['imagem_exibicao_thumb1'] != '') ? base_url() . $result['imagem_exibicao_thumb1'] : '',
                    'message' => 'Atualizado com sucesso!'
                ], REST_Controller::HTTP_OK);
            
                return;
            } else {
                $this->response(['id' => 0,
                    'message' => 'Usuário não encontrado.'], REST_Controller::HTTP_BAD_REQUEST);
                    
                return;
            }            
             
        } else {
            $this->response(['id' => 0,
                'message' => 'Usuário não encontrado.'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function consulta_produtos_get() {
        
        $produto_id = $this->input->get('ids');
        $pos = strpos($produto_id, '|');
        if(!($pos === false)) {
            $produto_id = explode('|', $produto_id);
        }
        $produtos = $this->tb_produto->get_all($produto_id);
        
        $result = array();        
        
        foreach ($produtos as $item) {
            
            $possui_grade_cores = $this->tb_produto->possui_grade_cores($item['id']);
            $p = array(
                'id' => $item['id'],
                'cod_produto' => $item['codigo'],
                'nome' => $item['nome'],
                'descricao' => $item['descricao'],
                'marca_id' => $item['marca_id'],
                'preco' => $item['preco'],
                'composicao' => $item['composicao'],  
                'grade_tem_cores' => ($possui_grade_cores ? 1 : 0),
                'fabricante' => $item['fornecedor_nome'],
                'marca' => $item['marca_nome'],              
            );
            
            $barras = $this->tb_produto->getCodigosBarras($item['id']);
            $cores = $this->tb_produto->getCores($item['id']);
            $grades = $this->tb_produto->getGradesApi($item['id']);
            $fotos = $this->tb_produto->getFotos($item['id']);
            
            $p['codigo_barras'] = array();
            foreach($barras as $barra) {
                $p['codigo_barras'][] = $barra['codigo'];
            }
            
            $p['cores'] = array();
            foreach($cores as $cor) {
                $p['cores'][] = array(
                    'id' => $cor['cor_id'],
                    'hex' => $cor['codigo'],
                    'nome' => $cor['nome']
                );
            }
            
            $p['grades'] = $grades;
            
            $p['fotos'] = array();
            foreach($fotos as $foto) {
                $f = array();
                
                $f['original'] = base_url() . $foto['imagem'];
                if(isset($foto['thumb70']) && strlen($foto['thumb70']) > 0) {
                    $f['thumb_70'] = base_url() . $foto['thumb70'];
                }
                if(isset($foto['thumb400']) && strlen($foto['thumb400']) > 0) {
                    $f['thumb_400'] = base_url() . $foto['thumb400'];
                }                
                
                $p['fotos'][] = $f;
            }
            
            $p['fabricante'] = array(
                'id' => $item['fornecedor_id'],
                'nome' => $item['fornecedor_nome'],                
                'img' => ''
            );      
            
            $p['marca'] = array(
                'id' => $item['marca_id'],
                'nome' => $item['marca_nome'],
            );                  
            
            $result[] = $p;
        }
        
        $this->response($result, REST_Controller::HTTP_OK);
    }
    
    public function consultar_produto_lojista_get() {
        
        $produto_id = $this->input->get('id');
        $pos = strpos($produto_id, '|');
        if(!($pos === false)) {
            $produto_id = explode('|', $produto_id);
        }
        $produtos = $this->tb_produto->get_all($produto_id);
        
        $result = array();
        
        foreach ($produtos as $item) {
            $possui_grade_cores = $this->tb_produto->possui_grade_cores($item['id']);
            $p = array(
                'Produto' => array(
                    'id' => $item['id'],
                    'fabricante' => $item['fornecedor_nome'],
                    'marca' => $item['marca_nome'],                    
                    'titulo' => $item['nome'],
                    'informacoes' => $item['descricao'],
                    'preco' => $item['preco'],
                    'codigo' => $item['codigo'],
                    'composicao' => $item['composicao'],   
                    'grade_tem_cores' => ($possui_grade_cores ? 1 : 0),                  
                )               
            );
            
            $barras = $this->tb_produto->getCodigosBarras($item['id']);
            $cores = $this->tb_produto->getCores($item['id']);
            $grades = $this->tb_produto->getGradesApi_2($item['id']);
            $fotos = $this->tb_produto->getFotos($item['id']);
            
            /*$p['codigo_barras'] = array();
            foreach($barras as $barra) {
                $p['codigo_barras'][] = $barra['codigo'];
            }*/
            
            $p['Cores'] = array();
            foreach($cores as $cor) {
                $p['Cores'][] = $cor['nome'];/*array(
                    'id' => $cor['cor_id'],
                    'hex' => $cor['codigo'],
                    'nome' => $cor['nome']
                );*/
            }
            
            $p['grades'] = $grades;
            /*$p['Grades'] = array(
                0 => array(
                    34 => 1,
                    36 => 1,
                    38 => 1,
                    40 => 1,
                    42 => 1,
                    44 => 1,                    
                ),
                1 => array(
                    34 => 3,
                    36 => 6,
                    38 => 2,
                    40 => 1,
                    42 => 3,
                    44 => 2,
                )
            );*/
            
            $p['Fotos'] = array();
            foreach($fotos as $foto) {
                $f = array();
                
                $f['original'] = base_url() . $foto['imagem'];
                if(isset($foto['thumb70']) && strlen($foto['thumb70']) > 0) {
                    $f['thumb_70'] = base_url() . $foto['thumb70'];
                }
                if(isset($foto['thumb400']) && strlen($foto['thumb400']) > 0) {
                    $f['thumb_400'] = base_url() . $foto['thumb400'];
                }                
                
                $p['Fotos'][] = $f;
            }
            
            $p['Fabricante'] = array(
                'id' => $item['fornecedor_id'],
                'nome' => $item['fornecedor_nome'],                
                'img' => ''
            );
            
            $p['Marca'] = array(
                'id' => $item['marca_id'],
                'nome' => $item['marca_nome'],
            );              
            
            $result[] = $p;
        }
        
        $this->response($result, REST_Controller::HTTP_OK);
    }    
    
    public function enviar_foto_produto_post() {
        $produto_id = intval($this->input->post('produto_id'));
        
        $novas_fotos = array();
        
        if(isset($_FILES['fotos'])) {
            if(is_array($_FILES['fotos']['name'])) {
                for($i = 0; $i < count($_FILES['fotos']['name']); $i++) {				
                    $ext = pathinfo($_FILES['fotos']['name'][$i], PATHINFO_EXTENSION);
                    $fileName = md5(uniqid(rand(), true));
                    $uploadfile = 'uploads/' . $fileName . '.' . $ext;
                    $thumbFile1 = 'uploads/70x70/' . $fileName . '.' . $ext;
				    $thumbFile2 = 'uploads/400x400/' . $fileName . '.' . $ext;
                    if (move_uploaded_file($_FILES['fotos']['tmp_name'][$i], FCPATH . $uploadfile)) {
                        
                        createThumbImage($uploadfile, $thumbFile1, 70, 70, $ext);
					    createThumbImage($uploadfile, $thumbFile2, 400, 400, $ext);
                        
                        $novas_fotos[] = array(0 => $uploadfile, 1 => $thumbFile1, 2 => $thumbFile2);
                    }					
                }
            } else {
                $ext = pathinfo($_FILES['fotos']['name'], PATHINFO_EXTENSION);
                $fileName = md5(uniqid(rand(), true));
                $uploadfile = 'uploads/' . $fileName . '.' . $ext;
                $thumbFile1 = 'uploads/70x70/' . $fileName . '.' . $ext;
				$thumbFile2 = 'uploads/400x400/' . $fileName . '.' . $ext;
                if (move_uploaded_file($_FILES['fotos']['tmp_name'], FCPATH . $uploadfile)) {
                    
                    createThumbImage($uploadfile, $thumbFile1, 70, 70, $ext);
					createThumbImage($uploadfile, $thumbFile2, 400, 400, $ext);
                        
                    $novas_fotos[] = array(0 => $uploadfile, 1 => $thumbFile1, 2 => $thumbFile2);
                }
            }			
        } else {
            $this->response(['sucesso' => 0,
                'message' => 'Não foi enviada nenhuma foto.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        
        if($this->tb_produto->adicionar_fotos($produto_id, $novas_fotos)) {
             $this->response(array('sucesso' => 1), REST_Controller::HTTP_OK);
        } else {
            $this->response(['sucesso' => 0,
                'message' => 'Não foi possível completar a operação.'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function recuperar_senha_get() {
        $email = $this->input->get('email');
        
        if(isset($email)) {
            
            $usuario = $this->tb_usuario->getByLogin($email);
            if($usuario) {
                
                $hash = $this->tb_usuario->getHashRecuperacaoSenha($email);
                
                $assunto = "Recuperação de e-mail";
                $mensagem = "Olá " . $usuario['nome'] . ".\r\n\r\nAcesse " . base_url() . 'index.php/recuperar_senha?email=' . $email . '&chave=' . $hash .' para criar uma nova senha.';
                
                $this->tb_smtp->enviar_email($email, $assunto, $mensagem); 
                
                $this->response(array('sucesso' => 1), REST_Controller::HTTP_OK);
                return;
            }            
        }
        
        $this->response(array('sucesso' => 0, 'mensagem' => 'Não foi possível recuperar sua senha.'), REST_Controller::HTTP_OK);
    }
    
    public function consulta_empresas_get() {
        $ids = $this->input->get('ids');
        $pos = strpos($ids, '|');
        if(!($pos === false)) {
            $ids = explode('|', $ids);
        }
        $empresas = $this->tb_empresa->get_all($ids);
        
        $result = array();
        
        foreach ($empresas as $item) {
            $p = array(
                'id' => $item['id'],
                'cnpj' => $item['cnpj'],
                'razao_social' => $item['razao_social'],
                'fantasia' => $item['fantasia'],
                'endereco' => $item['endereco'],                
            );
            
            $result[] = $p;
        }
        
        $this->response($result, REST_Controller::HTTP_OK);
    }
    
    public function adicionar_item_ao_carrinho_post() {
        // Parametros: login, senha, identificador, grade, quantidade, device_id
        
        //$login = $this->input->post('login');
        //$senha = $this->input->post('senha');
        $identificador = $this->post('identificador');
        $grade_id = $this->post('grade_id');
        $quantidade = $this->post('quantidade');
        $device_id = $this->post('device_id');
        
        if(!$this->tb_produto->existe_grade($grade_id)) {
            $this->response(['sucesso' => 0,
                'message' => 'Grade não encontrada.'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        
        $pedido = array();
        $id_pedido = 0;
        
        if(isset($identificador)) {
            $pedido = $this->tb_pedido->buscar_pedido($identificador);
            if(!isset($pedido)) {
                $id_pedido = $this->tb_pedido->novo_pedido();
                $pedido = $this->tb_pedido->get($id_pedido);
                $identificador = $pedido['identificador'];
            } else {
                $id_pedido = $pedido['id'];
            }
        }
        
        $this->tb_pedido->adicionar_item($id_pedido, $grade_id, $quantidade);
        $this->response(array(
            'sucesso' => 1,
            'identificador' => $identificador
        ), REST_Controller::HTTP_OK);
    }
    
    public function adicionar_usuario_post() {
        // Parametros: nome, email, empresa_id, senha, confirmar_senha, foto, celular, documento
        $nome = $this->input->post('nome');
        $email = $this->input->post('email');
        $empresa_id = $this->input->post('empresa_id');
        $celular = $this->input->post('celular');
        $senha = $this->input->post('senha');
        $confirmar_senha = $this->input->post('confirmar_senha');
        
        $uploadfile = '';
        $thumbFile = '';
        if(isset($_FILES['foto'])) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $fileName = md5(uniqid(rand(), true));
            $uploadfile = 'uploads/' . $fileName . '.' . $ext;
            $thumbFile = 'uploads/100x100/' . $fileName . '.' . $ext;                
            if (move_uploaded_file($_FILES['foto']['tmp_name'], FCPATH . $uploadfile)) {
                createThumbImage($uploadfile, $thumbFile, 100, 100, $ext);                    
            }
        }
        
        if(strcmp($senha, $confirmar_senha) != 0) {
            $this->response(['id' => 0, 'message' => 'Senhas não conferem.'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        
        if($empresa_id == -1) {
            
            $documento = preg_replace('/\D/', '', $this->input->post('documento'));
            if(!validar_cpf_cnpj($documento)) {
                $this->response(['id' => 0, 'message' => 'O CNPJ/CPF fornecido é inválido.'], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }
            
			$cliente = array(
				'razao_social' => $nome,
				'fantasia' => $nome,
				'endereco' => 'N/A',
				'cnpj' => $documento,
                'chave' => getGUID(),
			);
            
            $empresa_id = $this->tb_empresa->insert($cliente);
        }
        
        $usuario = array(
            'empresa_id' => $empresa_id,
            'nome' => $nome,
            'email' => $email,
            'celular' => $celular,
            'senha' => $senha,
            'ativo' => 1
        );
        
        if(strlen($uploadfile) > 0) {
            $usuario['imagem_exibicao'] = $uploadfile;
        }
        if(strlen($thumbFile) > 0) {
            $usuario['imagem_exibicao_thumb1'] = $thumbFile;
        }
        
        $res = $this->tb_usuario->insert($usuario);
        if($res) {

            $this->response(array('id' => $res, 'sucesso' => 1), REST_Controller::HTTP_OK);

            return;
        }
        
        $this->response(array('id' => 0, 'sucesso' => 0), REST_Controller::HTTP_OK);
    }
    
    public function verificar_existencia_documento_get() {
        // Parametros: documento
        
        $documento = preg_replace('/\D/', '', $this->input->get('documento'));
        if($this->tb_usuario->documento_existe($documento)) {
            $this->response(array('documento_existe' => 1), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('documento_existe' => 0), REST_Controller::HTTP_OK);            
        }
    }
    
    public function consultar_formas_de_pagamento_get() {
        // Parametros: ids
        
        $ids = $this->input->get('ids');
        $pos = strpos($ids, '|');
        if(!($pos === false)) {
            $ids = explode('|', $ids);
        }
        $regs = $this->tb_forma_pagamento->get_all($ids);
        
        $result = array();
        foreach($regs as $item) {
            $result[] = array(
                'id' => $item['id'],
                'nome' => $item['nome']
            );
        }
        
        $this->response($result, REST_Controller::HTTP_OK);
    }
    
    public function consultar_pedidos_get() {
        // Parametros: login, senha, ids
        
        $login = $this->input->get('login');
        $senha = $this->input->get('senha');
        $ids = $this->input->get('ids');
        
        $usuario_id = NULL;
        
        if(isset($login) && isset($senha)) {
            $result = $this->tb_usuario->login($login, $senha);
            if($result) {
                $usuario_id = $result->id;
            }
        }
        
        $result = array();
        $regs = $this->tb_pedido->getAll($ids, $usuario_id);
        foreach($regs as $item) {
            $p = array();
            $p['identificador'] = $item['identificador'];
            $p['status'] = $item['status'];
            $p['itens'] = array();
            
            $itens_pedido = $this->tb_pedido->get_itens($item['id']);
            foreach($itens_pedido as $itemPed) {
                $i = array(
                    'grade' => $itemPed['produto_grade_id'],
                    'quantidade' => $itemPed['quantidade']
                );
                
                $p['itens'][] = $i;
            }
            
            $result[] = $p;
        }
        
        $this->response($result, REST_Controller::HTTP_OK);
    }
    
    public function confirmar_pedido_post() {
        // Parametros: login, senha, fornecedor_id, device_id, observacoes, identificador, itens
        
        $login = $this->post('login');
        $senha = $this->post('senha');
        
        $identificador = $this->post('identificador');
        $grade_id = $this->post('grade_id');
        $quantidade = $this->post('quantidade');
        $device_id = $this->post('device_id');
        $observacoes = $this->post('observacoes');
        $itens = $this->post('itens');
        
        $usuario_id = NULL;
        
        if(isset($login) && isset($senha)) {
            $result = $this->tb_usuario->login($login, $senha);
            if($result) {
                $usuario_id = $result->id;
            } else {
                $this->response(['sucesso' => 0,
                    'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['sucesso' => 0,
                'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
        }        
        
        $pedido = array();
        $id_pedido = 0;
        
        if(isset($identificador)) {
            $pedido = $this->tb_pedido->buscar_pedido($identificador);
            if(!isset($pedido)) {
                $id_pedido = $this->tb_pedido->novo_pedido();
                $pedido = $this->tb_pedido->get($id_pedido);
                $identificador = $pedido['identificador'];
            } else {
                $id_pedido = $pedido['id'];
            }
        }
        
        if(isset($itens) && is_array($itens)) {
            $this->tb_pedido->limpar_itens($id_pedido);
            
            foreach($itens as $item) {
                $this->tb_pedido->adicionar_item($id_pedido, $item['grade_id'], $item['quantidade']);
            }
        }
        
        $this->tb_pedido->update($id_pedido, array(
           'status' => 1,
           'observacoes' => $observacoes 
        ));
        
        $assunto = "Confirmação de pedido";
        $mensagem = "Olá.\r\nSegue o resumo do seu pedido:\r\n" . base_url() . "index.php/pedidos/$id_pedido";
        
        $usuario = $this->tb_usuario->get($usuario_id);
        
        $this->tb_smtp->enviar_email($usuario['email'], $assunto, $mensagem); 
                
        $this->response(array(
            'sucesso' => 1,
            'identificador' => $identificador
        ), REST_Controller::HTTP_OK);
        
        $this->response(array('sucesso' => 1), REST_Controller::HTTP_OK);
    }


    public function criar_lista_post() {
        // Parametros: itens
        
        //$login = $this->post('login');
        //$senha = $this->post('senha');

        $itens = $this->post('itens');
       
        $usuario_id = NULL;
        if(isset($login) && isset($senha)) {
            $result = $this->tb_usuario->login($login, $senha);
            if($result) {
                $usuario_id = $result->id;
            } else {
                $this->response(['sucesso' => 0,
                    'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } 

/*
        else {
            $this->response(['sucesso' => 0,
                'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
        }        
*/       

        //verifica produtos existentes
        if(isset($itens) && is_array($itens)) 
        {
            reset($itens);
            foreach($itens as $item) {
                $produto = $this->tb_produto->get($item['produto_id']); 
                if( (!isset($produto->id)) ){
                    $this->response(['sucesso' => 0,
                        'message' => 'Produto inexistente('.$item['produto_id'].')'], REST_Controller::HTTP_BAD_REQUEST);
                } 
            }    
        }
        else
        {
            $this->response(['sucesso' => 0,
                    'message' => 'Produtos inexistentes.'], REST_Controller::HTTP_BAD_REQUEST);
        }    


        //Cadastra Lista
        $lista = array();
        $id_lista = 0;
        $id_lista = $this->tb_lista->nova_lista();
        $lista = $this->tb_lista->get($id_lista);
        

        //Cadastra Itens
        if(isset($itens) && is_array($itens)) {
            $this->tb_lista->limpar_itens($id_lista);
            
            reset($itens);
            foreach($itens as $item) {
                $this->tb_lista->adicionar_item($id_lista, $item['produto_id']);
            }
        }
       
                
        $this->response(array(
            'sucesso' => 1,
            'id' => $id_lista,
            'link' => base_url('index.php/listas?id='.$id_lista)
        ), REST_Controller::HTTP_OK);
    }




    public function criar_lista_get() {
        // Parametros: itens
        
        //$login = $this->get('login');
        //$senha = $this->get('senha');

        $itens = $this->get('ids');
      
        $usuario_id = NULL;
        if(isset($login) && isset($senha)) {
            $result = $this->tb_usuario->login($login, $senha);
            if($result) {
                $usuario_id = $result->id;
            } else {
                $this->response(['sucesso' => 0,
                    'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } 

/*
        else {
            $this->response(['sucesso' => 0,
                'message' => 'Credenciais inválidas.'], REST_Controller::HTTP_BAD_REQUEST);
        }        
*/       
     
        //verifica produtos existentes
        $itens = explode('|', $itens);

        if(isset($itens) && is_array($itens)) 
        {
            reset($itens);
            foreach($itens as $item) {
                $produto = $this->tb_produto->get($item); 
                if( (!isset($produto->id)) ){
                    $this->response(['sucesso' => 0,
                        'message' => 'Produto inexistente('.$item.')'], REST_Controller::HTTP_BAD_REQUEST);
                } 
            }    
        }
        else
        {
            $this->response(['sucesso' => 0,
                    'message' => 'Produtos inexistentes.'], REST_Controller::HTTP_BAD_REQUEST);
        }    


        //Cadastra Lista
        $lista = array();
        $id_lista = 0;
        $id_lista = $this->tb_lista->nova_lista();
        $lista = $this->tb_lista->get($id_lista);
        
        

        //Cadastra Itens
        if(isset($itens) && is_array($itens)) {
            $this->tb_lista->limpar_itens($id_lista);
            
            reset($itens);
            foreach($itens as $item) {
                $this->tb_lista->adicionar_item($id_lista, $item);
            }
        }

        //echo base_url('index.php/api/ApiVesti/lista?id='.$id_lista);

        //$this->response(array("link"=>base_url('api/ApiVesti/lista?id='.$id_lista)), REST_Controller::HTTP_OK);

        $this->response(array(
            'sucesso' => 1,
            'id' => $id_lista,
            'link' => base_url('index.php/listas?id='.$id_lista)
        ), REST_Controller::HTTP_OK);
    }


}