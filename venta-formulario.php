<?php

include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

if($_GET){
    if(isset($_GET["id"]) && $_GET["id"]> 0){
        $id = $_GET["id"];
        $venta->obtenerPorId($id);
    }

    if(isset($_GET["do"]) && $_GET["do"] == "buscarPrecio"){
        $idProducto = $_GET["id"];
        $producto = new Producto();
        $producto->idproducto = $idProducto;
        $producto->obtenerPorId();
        echo json_encode($producto->precio);
        exit;
    }
    
}

if($_POST){
    if(isset($_POST["btnGuardar"])){
        if(isset($_GET["id"]) && $_GET["id"] > 0){
              //Actualizo un cliente existente
              $venta->actualizar();
        } else {
            //Es nuevo
            $venta->insertar();
        }
    } else if(isset($_POST["btnBorrar"])){
        $venta->eliminar();
    }
} 
if(isset($_GET["id"]) && $_GET["id"] > 0){
    $venta->obtenerPorId();
}

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

$producto = new Producto();
$aProductos = $producto->obtenerTodos();

$aVentas = $venta->obtenerTodos();


?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title> ABM VENTAS </title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <form action="" method="POST">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- <?php include_once("menu.php"); ?> 

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Venta</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnNuevo" name="btnNuevo">Borrar</button>
                </div>
            </div>
            <div class="row">
              <div class="col-6 form-group">
                  <label for="txtFecha">Fecha:</label>
                  <input type="date" required="" class="form-control" name="txtFecha" id="txtFecha" value="<?php echo date_format(date_create($venta->fecha), "Y-m-d"); ?>">
              </div>
              <div class="col-6 form-group">
                  <label for="txtHora">Hora:</label>
                  <input type="time" required="" class="form-control" name="txtHora" id="txtHora" value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>">
              </div>
            </div>
            <div class="row">
              <div class="col-6 form-group">
                  <label for="lstCliente">Cliente:</label>
                  <select class="form-control" name="lstCliente" id="lstCliente">
                      <option value="" disabled selected>Seleccionar</option>
                      <?php foreach($aClientes as $cliente): ?>
                        <?php if($cliente->idcliente == $venta->fk_idcliente) :?>
                          <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                        <?php else: ?>
                          <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>                  
                  </select>
              </div>            
              <div class="col-6 form-group">
                  <label for="lstProducto">Producto:</label>
                  <select required="" class="form-control" name="lstProducto" id="lstProducto" onchange="fBuscarPrecio()">
                      <option value="" disabled selected>Seleccionar</option>
                      <?php foreach($aProductos as $producto): ?>
                        <?php if($producto->idproducto == $venta->fk_idproducto) :?>
                          <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                        <?php else: ?>
                          <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>  
                  </select>
              </div>
            </div>
            <div class="row">
              <div class="col-6 form-group">
                  <label for="txtPrecioUni">Precio Unitario:</label>
                  <input type="text" class="form-control" name="txtPrecioUni" id="txtPrecioUni" value="<?php echo $venta->preciounitario ?>">
              </div> 
              <div class="col-6 form-group">
                  <label for="txtCantidad">Cantidad:</label>
                  <input type="text" class="form-control" name="txtCantidad" id="txtCantidad" onchange="fCalcularTotal();" value="<?php echo $venta->cantidad ?>">
              </div>
            </div>             
            <div class="row">
              <div class="col-6 form-group">
                  <label for="txtImporte">Importe:</label>
                  <input type="text" class="form-control" name="txtImporte" id="txtImporte" value="<?php echo $venta->importe ?>">
              </div>
            </div>      
        <!-- /.container-fluid -->

      </div>
     
  </form>
      <!-- End of Main Content -->

      <!-- Footer -->

      <?php include_once("footer.php"); ?>
      
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <script>
    function fBuscarPrecio(){
            idProducto = $("#lstProducto option:selected").val();
            $.ajax({
	            type: "GET",
	            url: "venta-formulario.php?do=buscarPrecio",
	            data: { id:idProducto },
	            async: true,
	            dataType: "json",
	            success: function (respuesta) {
                
              $("#txtPrecioUni").val(respuesta); 
	            }
	        });
      }

    function fCalcularTotal(){
        precio = $("#txtPrecioUni").val();
        cantidad = $("#txtCantidad").val();
        resultado = precio * cantidad;
        $("#txtImporte").val(resultado);
    }

  </script>

</body>

</html>