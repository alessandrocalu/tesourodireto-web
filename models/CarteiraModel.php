<?php
class CarteiraModel extends CI_Model {

    public function get_all()
    {
        $this->db->from("carteira");
        $this->db->order_by("dt_compra", "DESC");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_filtered($pgSettings = array(), $usuario = '', $titulo = '', $dataIni = '', $dataFim = '')
    {
        $orderBy = "ORDER BY dt_compra DESC";
        $queryFields = "tb.*, tt.nome as nomeTitulo, tt.sigla as siglaTitulo";

        $sql = "FROM carteira tb inner join titulos tt on (tt.id = tb.titulo_id) WHERE 1 = 1 ";

        if(isset($usuario) && strlen($usuario) > 0) {
            $sql .= " AND (tb.usuario_id = '$usuario')";
        }

        if(isset($titulo) && strlen($titulo) > 0) {
            $sql .= " AND (tb.titulo_id = '$titulo')";
        }

        if(isset($dataIni) && strlen($dataIni) >= 8) {
            $dataIni = substr($dataIni, 6, 4)."-".substr($dataIni, 3, 2)."-".substr($dataIni, 0, 2);
            $sql .= " AND (tb.dt_compra >= '$dataIni')";
        }

        if(isset($dataFim) && strlen($dataFim) > 0) {
            $dataFim = substr($dataFim, 6, 4)."-".substr($dataFim, 3, 2)."-".substr($dataFim, 0, 2);
            $sql .= " AND (tb.dt_compra <= '$dataFim')";
        }

        $total = $this->db->query("SELECT COUNT(*) AS Qtd $sql")->row_array()['Qtd'];

        $page = (int)$pgSettings['page'];
        $rowsPerPage = (int)$pgSettings['rowsPerPage'];

        $a = (($page - 1) * $rowsPerPage);
        $b = $a + $rowsPerPage;

        if($pgSettings['enabled']) {
            $query = $this->db->query("SELECT * FROM (SELECT $queryFields $sql $orderBy) AS Rows $orderBy LIMIT $rowsPerPage OFFSET $a");
        } else {
            $query = $this->db->query("SELECT * FROM (SELECT $rowNumber $queryFields $sql $orderBy) AS Rows $orderBy");
        }
        $data = $query->result_array();

        $pages = (int)($total / $rowsPerPage);
        if($pages * $rowsPerPage < $total) {
            $pages++;
        }

        return array(
            'page' => $pgSettings['page'],
            'rowsPerPage' => $pgSettings['rowsPerPage'],
            'totalRows' => $total,
            'totalPages' =>  $pages,
            'pageRows' => count($data),
            'data' => $data
        );
    }    

    public function get($id) {
        $query = $this->db->get_where('carteira', array('id' => $id));
        return $query->row_array();
    }

    public function getCarteira($usuario_id){
        $where = " usuario_id  =  ".$usuario_id." ";

        $this->db->select('tl.nome, ct.*');
        $this->db->from('carteira ct');
        $this->db->join('titulos tl', 'tl.id = ct.titulo_id');
        $this->db->where($where);
        $this->db->order_by("tl.nome", "ASC");
        $query = $this->db->get();

        if ($query->num_rows()  > 0)
        {    
            return $query->result_array();
        }
        else
        {
            return "";
        }    
    }

    public function update($id, $dados){
        $this->db->where('id', $id);
        $this->db->update('carteira', $dados);
        return ($this->db->affected_rows() > 0);
    }

    public function insert($dados = null){
        if($dados != null){
            $result = $this->db->insert('carteira', $dados);
            if($result) {
                $id = $this->db->insert_id();
                return $id;
            }
            return $result;
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('carteira');
        return $this->db->affected_rows();
    }
}