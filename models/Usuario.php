<?php

Class Usuario extends CI_Model {

    public function get($id) {
        $query = $this->db->get_where('usuarios', array('id' => $id));
        return $query->row_array();
    }

    public function getByLogin($login) {
        $query = $this->db->query("SELECT usu.* FROM usuarios usu WHERE usu.email = ?", array($login));
        return $query->row_array();
    }

    public function get_all() {
        $query = $this->db->get('usuarios');
        return $query->result_array();
    }
    
    public function get_filtered($pgSettings = array(), $filter = '', $status = '') {
        //$rowNumber = "ROW_NUMBER() OVER  ORDER BY id DESC AS RowNum,";
        $orderBy = "ORDER BY email DESC";
        $queryFields = "usu.*";

        $sql = "FROM usuarios usu WHERE 1 = 1 ";

        if (isset($filter) && strlen($filter) > 0) {
            $sql .= " AND (usu.email like '%$filter%' OR usu.nome like '%$filter%')";
        }

        if (isset($status) && $status != '') {
            if ($status == '0') {
                $sql .= " AND (IFNULL(usu.ativo, 0) = 0)";
            } else if ($status == '1') {
                $sql .= " AND (IFNULL(usu.ativo, 0) = 1)";
            }
        } 

        $total = $this->db->query("SELECT COUNT(*) AS Qtd $sql")->row_array()['Qtd'];

        $page = (int) $pgSettings['page'];
        $rowsPerPage = (int) $pgSettings['rowsPerPage'];

        $a = (($page - 1) * $rowsPerPage);
        $b = $a + $rowsPerPage;
        
        $query = $this->db->query("SELECT * FROM (SELECT $queryFields $sql $orderBy) AS Rows LIMIT $rowsPerPage OFFSET $a");

        return array(
            'page' => $pgSettings['page'],
            'rowsPerPage' => $pgSettings['rowsPerPage'],
            'totalRows' => $total,
            'totalPages' => ceil($total / $rowsPerPage),
            'data' => $query->result_array()
        );
    }
    
    public function isAdmin($id) {
        $query = $this->db->query("SELECT perfil from usuarios where id = ?", array($id));
        $data = $query->row_array();
        return $data['perfil'] == 'admin';
        
    }
    
    public function login($login, $senha) {
        $this->db->select('id, nome, email, senha, perfil');
        $this->db->from('usuarios');
        $this->db->where('email', $login);
        $this->db->where('ativo', 1);
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $ret = $query->row();
            //print_r($ret);

            if (trim($ret->senha) == md5(trim($senha))) {
                return $ret;
            }
        }
        return false;
    }

    public function login_chave($chave, $login) {
        $data_integracao = date("Y-m-d H:i:s", strtotime("-10 minutes"));
        $this->db->select('id, email, senha');
        $this->db->from('usuarios');
        $this->db->where('chave_integracao', $chave);
        $this->db->where('email', $login);
        $this->db->where('data_integracao <', $data_integracao);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $ret = $query->row();
            return $ret;
        }

        return false;
    }

    public function getByChave($chave) {
        $this->db->from("usuarios");
        $this->db->where("chave_session", $chave);
        $this->db->where("data_session >", date("Y-m-d H:i:s",strtotime("now")));
        $query = $this->db->get();
        return $query->row(0);
    }    

    public function update($id, $dados) {
        $this->db->where('id', $id);
        $this->db->update('usuarios', $dados);
        return ($this->db->affected_rows() > 0);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('usuarios', $dados);
        return ($this->db->affected_rows() > 0);
    }
    
    public function alterar_senha($email, $senha) {
        $this->db->where('email', $email);
        $this->db->update('usuarios', array(
            'senha' => md5($senha)
        ));
    }

    public function ativar($id) {
        $this->db->where('id', $id);
        $this->db->update('usuarios', array('ativo' => 1));

        return $this->db->affected_rows() > 0;
    }

    public function desativar($id) {
        $this->db->where('id', $id);
        $this->db->update('usuarios', array('ativo' => 0));

        return $this->db->affected_rows() > 0;
    }

    public function insert($dados = null, $perfis = null) {
        if ($dados != null) {
            $result = $this->db->insert('usuarios', $dados);
            $id = $this->db->insert_id();
            return $id;
        }
        return 0;
    }

    public function login_existe($login) {
        $query = $this->db->get_where('usuarios', array('email' => $login));
        if ($query->num_rows() > 0){
            return $query->row(0);
        }
        else
        {
            return '';
        }    
    }
    
    public function getHashRecuperacaoSenha($email) {
        return md5(strtolower($email) . '_recuperacao_senha');
    }

}
