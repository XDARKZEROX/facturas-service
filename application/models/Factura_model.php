<?php

class Factura_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerFacturasDelDia() {
        $this->db->select('f.*,c.*,u.firstname,u.lastname');
        $this->db->from('facturas f');
        $this->db->join('clientes c', 'f.id_cliente = c.id_cliente');
        $this->db->join('users u', 'f.id_vendedor = u.user_id');
        //$this->db->where("DATE(fecha_factura) = CURDATE()");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtenerDetalleFacturaPorId($idFactura) {
        $this->db->select('d.id_detalle, d.id_producto, d.cantidad, d.numero_factura, d.precio_venta, p.nombre_producto');
        $this->db->from('detalle_factura d');
        $this->db->join('products p', 'd.id_producto = p.id_producto');
        $this->db->where('d.numero_factura', $idFactura);
        $query = $this->db->get();
        return $query->result_array();
    }
}