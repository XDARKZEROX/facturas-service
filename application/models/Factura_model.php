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

    public function insertarNuevaFactura($factura){
        $date=date("Y-m-d H:i:s");
        $time_input = strtotime(date($factura['fecha_vencimiento']));
        $day = date('d', $time_input);
        $last = $this->obtenerUltimoNumeroDeFacturaGenerada();
        $data = array(
            'id_factura' => NULL,
            'numero_factura' => $last->last+1,
            'fecha_factura' => date("Y-m-d H:i:s"),
            'id_cliente' => $factura['id_cliente'],
            'id_vendedor' => $factura['id_vendedor'],
            'condiciones' => $factura['condiciones'],
            'total_venta' => $factura['total_venta'],
            'estado_factura' => $factura['estado_factura'],
            'fecha_vencimiento' => date('Y-m-'.$day.' H:i:s',$time_input)
        );

        $this->db->insert('facturas', $data);
        //$sql = $this->db->set($data)->get_compiled_insert('facturas');
    }

    public function obtenerUltimoNumeroDeFacturaGenerada(){
        $this->db->select('LAST_INSERT_ID(numero_factura) as last');
        $this->db->from('facturas');
        $this->db->order_by('id_factura','DESC');
        $this->db->limit(1,0);
        $query = $this->db->get();
        return $query->row();
    }

    public function crearFacturaDetalle(){

    }


}