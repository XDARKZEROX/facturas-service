<?php

class Perfil_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerPerfil() {
        $this->db->select('*');
        $this->db->from('perfil');
        $this->db->where('id_perfil',1);
        $query = $this->db->get();
        return $query->row();
    }
}