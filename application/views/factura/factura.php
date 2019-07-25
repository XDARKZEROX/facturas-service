<html>
<header>
<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
.midnight-blue{
	background:#2c3e50;
	padding: 4px 4px 4px;
	color:white;
	font-weight:bold;
	font-size:12px;
}
.silver{
	background:white;
	padding: 3px 4px 3px;
}
.clouds{
	background:#ecf0f1;
	padding: 3px 4px 3px;
}
.border-top{
	border-top: solid 1px #bdc3c7;
	
}
.border-left{
	border-left: solid 1px #bdc3c7;
}
.border-right{
	border-right: solid 1px #bdc3c7;
}
.border-bottom{
	border-bottom: solid 1px #bdc3c7;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
}
-->
</style>

</header>
<body>  
    <page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        <table cellspacing="0" style="width: 100%;">
            <tr>
                <td style="width: 25%; color: #444444;">
                    <img style="height: 50px;" src="https://clicdominio.com/facturas/img/1478792451_google30.png" alt="Logo">  
                </td>
                <td style="width: 50%; color: #34495e;font-size:12px;text-align:center">
                    <span style="color: #34495e;font-size:14px;font-weight:bold"><?php echo $perfil->nombre_empresa;?></span>
                    <br><?php echo $perfil->direccion.', '.$perfil->ciudad.' '.$perfil->estado;?><br> 
                    Teléfono: <?php echo $perfil->telefono;?><br>
                    Email: <?php echo $perfil->email;?>
                </td>
                <td style="width: 25%;text-align:right">
                    FACTURA Nº <?php echo 'FAC'.$factura['numero_factura'];?>
                </td>
                
            </tr>
        </table>    
        <br>
        <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
            <tr>
               <td style="width:50%;" class='midnight-blue'>FACTURAR A</td>
            </tr>
            <tr>
               <td style="width:50%;">
               <?php 
				echo $factura['nombre_cliente'];
                echo "<br>";
                echo $factura['direccion_cliente'];
                echo "<br><br> <b>CIF/NIF:</b>";
                echo $factura['identificacion_fiscal'];
                echo "<br> <b>Teléfono:</b>";
                echo $factura['telefono_cliente'];
                echo "<br> <b>Email:</b>";
                echo $factura['email_cliente'];
			    ?>
               </td>
               <td style="width:50%;text-align: center"><h1>
                <?php
                if ($factura['estado_factura']==1){echo "<h1 style='color: #5cb85c'>PAGADA</h1>";}
                elseif ($factura['estado_factura']==2){echo "<h1 style='color: #f0ad4e'>PENDIENTE</h1>";}
                ?>
                </h1>
                </td>
            </tr>
        </table>
        
           <br>
            <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
            <tr>
            <td style="width:40%;" class='midnight-blue'>VENDEDOR</td>
            <td style="width:20%;" class='midnight-blue'>FECHA</td>
		   <td style="width:20%;" class='midnight-blue'>FORMA DE PAGO</td>
            <td style="width:20%;" class='midnight-blue'>FECHA VENCIMIENTO</td>
            </tr>
            <tr>
                <td style="width:40%;">
                    <?php echo $factura['firstname']." ".$factura['lastname'];?>
                </td>
                <td style="width:20%;"><?php echo date("d/m/Y");?></td>
                <td style="width:20%;" >
                <?php 
				if ($factura['condiciones']==1){echo "Efectivo";}
				elseif ($factura['condiciones']==2){echo "Transferencia bancaria";}
				elseif ($factura['condiciones']==3){echo "Tarjeta de Crédito/Débito";}
				elseif ($factura['condiciones']==4){echo "Paypal";}
                elseif ($factura['condiciones']==5){echo "Domiciliación Bancaria";}
				?>
                </td>
                <td style="width:20%;" ><?php echo date("d/m/Y", strtotime($factura['fecha_vencimiento']));?></td>
            </tr>
            
        </table>
        <br>
      
        <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
            <tr>
                <th style="width: 10%;text-align:center" class='midnight-blue'>CANT.</th>
                <th style="width: 60%" class='midnight-blue'>DESCRIPCION</th>
                <th style="width: 15%;text-align: right" class='midnight-blue'>PRECIO UNIT.</th>
                <th style="width: 15%;text-align: right" class='midnight-blue'>PRECIO TOTAL</th>
                
            </tr>
    

            <?php 
            $nums = 1;
            $sumador_total=0;
            foreach($factura['detalle'] as $detalle){ 
            if ($nums%2==0){
		        $clase="clouds";
	        } else {
		        $clase="silver";
	        }

            $precio_total= number_format($detalle['precio_venta']*$detalle['cantidad'],2);   
            $precio_total_r=str_replace(",","",$precio_total);
	        $sumador_total+=$precio_total_r;//Sumador
            ?>
            <tr>
                <td class='<?php echo $clase;?>' style="width: 10%; text-align: center"><?php echo $detalle['cantidad']; ?></td>
                <td class='<?php echo $clase;?>' style="width: 60%; text-align: left"><?php echo $detalle['nombre_producto']; ?></td>
                <td class='<?php echo $clase;?>' style="width: 15%; text-align: right"><?php echo number_format($detalle['precio_venta'],2); ?></td>
                <td class='<?php echo $clase;?>' style="width: 15%; text-align: right"><?php echo $precio_total; ?></td>      
            </tr>
            <?php 
            $nums++;
            } 
            $subtotal=number_format($sumador_total,2,'.','');
            $total_iva=($subtotal * $perfil->impuesto )/100;
	        $total_iva=number_format($total_iva,2,'.','');
            ?>

            <tr>
                <td colspan="3" style="widtd: 85%; text-align: right;">SUBTOTAL <?php echo $perfil->moneda;?></td>
                <td style="widtd: 15%; text-align: right;"><?php echo number_format($subtotal,2);?></td>
            </tr>
            <tr>
                <td colspan="3" style="widtd: 85%; text-align: right;">IVA (<?php echo $perfil->impuesto; ?>)% <?php echo $perfil->moneda;?> </td>
                <td style="widtd: 15%; text-align: right;"><?php echo number_format($total_iva,2);?></td>
            </tr><tr>
                <td colspan="3" style="widtd: 85%; text-align: right;">TOTAL <?php echo $perfil->moneda;?></td>
                <td style="widtd: 15%; text-align: right;"><?php echo $factura['total_venta'];?></td>
            </tr>
        </table>
        
        <br>
        <div style="font-size:11pt;text-align:center;font-weight:bold">Gracias por su compra!</div>
    </page>
    </body>
</html>    
