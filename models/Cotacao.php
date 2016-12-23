<?php
class Cotacao extends CI_Model {


    public function get_all()
    {
        $this->db->from("cotacoes");
        $this->db->order_by("dt_atualizacao", "DESC");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_filtered($pgSettings = array(), $titulo = '', $dataIni = '', $dataFim = '')
    {
        $orderBy = "ORDER BY dt_atualizacao DESC";
        $queryFields = "tb.*";

        $sql = "FROM cotacoes tb WHERE 1 = 1 ";

        if(isset($titulo) && strlen($titulo) > 0) {
            $sql .= " AND (tb.titulo_id = '$titulo')";
        }

        if(isset($dataIni) && strlen($dataIni) >= 8) {
            $sql .= " AND (tb.dt_atualizacao >= '$dataIni')";
        }

        if(isset($dataFim) && strlen($dataFim) > 0) {
            $sql .= " AND (tb.dt_atualizacao <= '$dataFim')";
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
        $query = $this->db->get_where('cotacoes', array('id' => $id));
        return $query->row_array();
    }


    public function getCotacao($titulo_id, $dt_atualizacao){
        $where = " titulo_id  =  ".$titulo_id." and  dt_atualizacao <= '".$dt_atualizacao."' ";

        $this->db->select('*');
        $this->db->from('cotacoes');
        $this->db->where($where);
        $this->db->order_by('dt_atualizacao', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows()  > 0)
        {    
            return $query->row_array();
        }
        else
        {
            return "";
        }    
    }

    public function getCotacoesMes($titulo_id){
        $where = " (titulo_id  =  ".$titulo_id.") and (dt_atualizacao >= '".date("Y-m-01")."') ";

        $this->db->select('*');
        $this->db->from('cotacoes');
        $this->db->where($where);
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

    public function getCotacoesUlt30Dias($titulo_id){
        $where = " (titulo_id  =  ".$titulo_id.") and (dt_atualizacao >= '".date("Y-m-d", strtotime("-30 days"))."') ";
        $this->db->select('*');
        $this->db->from('cotacoes');
        $this->db->where($where);
        $this->db->order_by("dt_atualizacao", "ASC");
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
        $this->db->update('cotacoes', $dados);
        return ($this->db->affected_rows() > 0);
    }

    public function insert($dados = null){
        if($dados != null){
            $result = $this->db->insert('cotacoes', $dados);
            if($result) {
                $id = $this->db->insert_id();
                return $id;
            }
            return $result;
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('cotacoes');
        return $this->db->affected_rows();
    }
}