<?php

include_once "config.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

if($_POST){
    if(isset($_POST["btnGuardar"])){
        if(isset($_GET["id"]) && $_GET["id"] > 0){
              //Actualizo un cliente existente
              $producto->actualizar();
        } else {
            //Es nuevo
            $producto->insertar();
        }
    } else if(isset($_POST["btnBorrar"])){
        $producto->eliminar();
    }
} 
if(isset($_GET["id"]) && $_GET["id"] > 0){
    $producto->obtenerPorId();
}

$tipoProducto = new Tipoproducto();
$aTipoProductos = $tipoProducto->obtenerTodos();

?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>ABM Ventas</title>

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
          <h1 class="h3 mb-4 text-gray-800">Producto</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
                </div>
            </div>
                <div class="row">
                  <div class="col-6 form-group">
                      <label for="txtNombre">Nombre:</label>
                      <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre ?>">
                  </div>
                  <div class="col-6 form-group">
                    <label for="txtTipoProducto">Tipo de producto:</label>
                    <select name="lstTipoProducto" id="lstTipoProducto" class="form-control">
                        <option value="" disabled selected>Seleccionar</option>
                        <?php foreach($aTipoProductos as $tipo): ?>
                          <?php if($producto->fk_idtipoproducto == $tipo->idtipoproducto) :?>
                            <option selected value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                          <?php else: ?>
                            <option value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-2 form-group">
                      <label for="txtCantidad">Cantidad:</label>
                      <input type="number" required class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad ?>">
                  </div>            
                  <div class="col-2 form-group">
                      <label for="txtPrecio">Precio:</label>
                      <input type="number" class="form-control" name="txtPrecio" id="txtPrecio" value="<?php echo $producto->precio ?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 form-group">
                      <label for="txtDescripcion">Descripción:</label>
                      <input type="" class="form-control" name="txtDescripcion" id="txtDescripcion" required value="<?php echo $producto->descripcion ?>">
                  </div>                
                </div>
        </div>
        <!-- /.container-fluid -->

      </div>
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

</form>
</body>
</html>
