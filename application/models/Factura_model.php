<?php

class Factura_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerFacturasDelDia() {
        $this->db->select('f.*,c.*,u.firstname,u.lastname, fa.estado');
        $this->db->from('facturacion fa');
        $this->db->join('facturas f', 'f.id_factura = fa.id_factura');
        $this->db->join('clientes c', 'f.id_cliente = c.id_cliente');
        $this->db->join('users u', 'f.id_vendedor = u.user_id');
        $this->db->where('DAY(f.fecha_factura) = DAY(NOW())');
        $this->db->where('fa.estado',1);
        $this->db->group_by("f.id_cliente");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtenerDetalleFacturaPorId($idFactura) {
        $this->db->select('detalle_factura.* , products.nombre_producto');
        $this->db->from('products, detalle_factura, facturas');
        $this->db->where('products.id_producto = detalle_factura.id_producto');
        $this->db->where('detalle_factura.numero_factura = facturas.numero_factura');
        $this->db->where('facturas.id_factura', $idFactura);
        //$sql = $this->db->get_compiled_select();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insertarFactura($factura){
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
        return $last->last+1;
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

    public function insertarDetalleFactura($detalle, $numero_factura){
        $data = array(
            'id_detalle' => NULL,
            'numero_factura' => $numero_factura,
            'id_producto' => $detalle['id_producto'],
            'cantidad' => $detalle['cantidad'],
            'precio_venta' => $detalle['precio_venta'],
        );
        $this->db->insert('detalle_factura', $data);
    }
}