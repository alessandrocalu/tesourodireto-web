<?php
class Titulo extends CI_Model {

    public function get_all()
    {
        $this->db->from("titulos");
        $this->db->order_by("nome", "ASC");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_filtered($pgSettings = array(), $filter = '')
    {
        $orderBy = "ORDER BY nome ASC";
        $queryFields = "tb.*";

        $sql = "FROM titulos tb WHERE 1 = 1 ";

        if(isset($filter) && strlen($filter) > 0) {
            $sql .= " AND (tb.nome like '%$filter%')";
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
        $query = $this->db->get_where('titulos', array('id' => $id));
        return $query->row_array();
    }

    public function get_sigla_titulo($sigla) {
        $query = $this->db->get_where('titulos', array('sigla' => $sigla));

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
        $this->db->update('titulos', $dados);
        return ($this->db->affected_rows() > 0);
    }

    public function insert($dados = null){
        if($dados != null){
            $result = $this->db->insert('titulos', $dados);
            if($result) {
                $id = $this->db->insert_id();
                return $id;
            }
            return $result;
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('titulos');
        return $this->db->affected_rows();
    }
}