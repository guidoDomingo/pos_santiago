<?php

	# Incluyendo librerias necesarias #
    require "./code128.php";

    require_once "../../controladores/ventas.controlador.php";
    require_once "../../modelos/ventas.modelo.php";

    require_once "../../controladores/clientes.controlador.php";
    require_once "../../modelos/clientes.modelo.php";

    require_once "../../controladores/usuarios.controlador.php";
    require_once "../../modelos/usuarios.modelo.php";

    require_once "../../controladores/productos.controlador.php";
    require_once "../../modelos/productos.modelo.php";


    //TRAEMOS LA INFORMACIÓN DE LA VENTA

    $itemVenta = "codigo";
    $valorVenta =  $_GET["codigo"];

    $respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

    $fecha = substr($respuestaVenta["fecha"],0,-8);
    $productos = json_decode($respuestaVenta["productos"], true);
    $neto = number_format($respuestaVenta["neto"],2);
    $impuesto = number_format($respuestaVenta["impuesto"],2);
    $total = number_format($respuestaVenta["total"],2);
    $iva = ceil($respuestaVenta["total"] / 11) ;
    $formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    $total_letra = $formatterES->format($respuestaVenta["total"]);

    //TRAEMOS LA INFORMACIÓN DEL CLIENTE

    $itemCliente = "id";
    $valorCliente = $respuestaVenta["id_cliente"];

    $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

    //TRAEMOS LA INFORMACIÓN DEL VENDEDOR

    $itemVendedor = "id";
    $valorVendedor = $respuestaVenta["id_vendedor"];

    $respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

    $pdf = new PDF_Code128('P','mm',array(80,258));
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper("De Santiago Vallejos")),0,'C',false);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0,5,utf8_decode("RUC: 1555729-4"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Direccion Caacupé-Cordillera"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: 0981-593 081"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Email: distribuidora3sgotze@egmail.com"),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,utf8_decode("Fecha: ".$fecha." ".date("h:s A")),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Caja Nro: 1"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Cajero: ".$respuestaVendedor['nombre']),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper("Ticket Nro: ".$valorVenta)),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,utf8_decode("Cliente: ".$respuestaCliente['nombre']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Documento: ".$respuestaCliente['documento']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: 00000000"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Dirección: "),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    # Tabla de productos #

    $pdf->Cell(10,5,utf8_decode("Cant."),0,0,'C');
    $pdf->Cell(19,5,utf8_decode("Precio"),0,0,'C');
    $pdf->Cell(15,5,utf8_decode("Desc."),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("Total"),0,0,'C');

    $pdf->Ln(3);
    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    foreach ($productos as $key => $item) {

        $itemProducto = "descripcion";
        $valorProducto = $item["descripcion"];
        $orden = null;
        
        $respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);
        
        $valorUnitario = number_format($respuestaProducto["precio_venta"], 2);
        
        $precioTotal = number_format($item["total"], 2);

        /*----------  Detalles de la tabla  ----------*/
        $pdf->MultiCell(0,4,utf8_decode($item['descripcion']),0,'C',false);
        $pdf->Cell(10,4,utf8_decode($item['cantidad']),0,0,'C');
        $pdf->Cell(19,4,utf8_decode($valorUnitario),0,0,'C');
        $pdf->Cell(19,4,utf8_decode("$0.00 USD"),0,0,'C');
        $pdf->Cell(28,4,utf8_decode($precioTotal),0,0,'C');
        $pdf->Ln(4);
        // $pdf->MultiCell(0,4,utf8_decode("Garantía de fábrica: 2 Meses"),0,'C',false);
        $pdf->Ln(7);
        /*----------  Fin Detalles de la tabla  ----------*/

    }
    



    



    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

        $pdf->Ln(5);

    # Impuestos & totales #
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("SUBTOTAL"),0,0,'C');
    $pdf->Cell(32,5,utf8_decode("+".$total),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("IVA (10%)"),0,0,'C');
    $pdf->Cell(32,5,utf8_decode("+ ".$iva),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(32,5,utf8_decode($total),0,0,'C');

    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("TOTAL PAGADO"),0,0,'C');
    $pdf->Cell(32,5,utf8_decode($total),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("CAMBIO"),0,0,'C');
    $pdf->Cell(32,5,utf8_decode("$00.00 "),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("USTED AHORRA"),0,0,'C');
    $pdf->Cell(32,5,utf8_decode("$0.00 USD"),0,0,'C');

    $pdf->Ln(10);

    $pdf->MultiCell(0,5,utf8_decode("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"),0,'C',false);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,utf8_decode("Gracias por su compra"),'',0,'C');

    $pdf->Ln(9);

    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),"COD000001V0001",70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,utf8_decode("COD000001V0001"),0,'C',false);
    
    # Nombre del archivo PDF #
    $pdf->Output("I","Ticket_Nro_1.pdf",true);