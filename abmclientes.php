<?php

if(file_exists("clientes.txt")) {
    $jsonClientes = file_get_contents("clientes.txt");
    $aClientes = json_decode($jsonClientes, true); //true = para que lo tome como array
} else {
    $aClientes = array();
}

$pos = isset($_GET["pos"])? $_GET["pos"] : "";


if($_POST) { //Es postback? Esto es, si apretaron el botón de enviar formulario

    //Definicion de variables
    $dni = $_POST["txtDni"];
    $nombre = $_POST["txtNombre"];
    $telefono = $_POST["txtTelefono"];
    $correo = $_POST["txtCorreo"];

    if (isset($_GET["do"]) && $_GET["do"] == "edit") {
    
    // Creo un array con los datos del cliente a editar
    $aClientes[$pos] = array( "dni" => $dni,
                            "nombre" => $nombre,
                            "telefono" => $telefono,
                            "correo" => $correo
                    );                   
                    
    } else {
        
        // Convertir los datos del formulario en un array
        $aClientes[] = array( "dni" => $dni,
                            "nombre" => $nombre,
                            "telefono" => $telefono,
                            "correo" => $correo
                    );

    } 

    // Convertimos el array a json
    $jsonClientes = json_encode($aClientes);          

    // Guardamos el json en el archivo
    file_put_contents("clientes.txt", $jsonClientes);   
}

if (isset($_GET["do"]) && $_GET["do"] == "delete") {
    unset($aClientes[$pos]); // Elimina una variable
    // Guardar en el archivo el nuevo array de clientes modificado
    $jsonClientes = json_encode($aClientes);
    file_put_contents("clientes.txt", $jsonClientes);
} 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ABM Clientes </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row text-center py-3">
            <div class="col-12">
                <h1> <a href="abmclientes.php"> Registro de clientes </h1> </a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-6">
                <form action="" method="POST" enctype="multipart/form-data"> 
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="txtDni"> DNI: </label>
                            <input type="text" name="txtDni" id="txtDni" class="form-control" required value="<?php echo isset($aClientes[$pos]["dni"])? $aClientes[$pos]["dni"] : ""; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="txtNombre"> Nombre y apellido: </label>
                            <input type="text" name="txtNombre" id="txtNombre" class="form-control" required value="<?php echo isset($aClientes[$pos]["nombre"])? $aClientes[$pos]["nombre"] : ""; ?>">                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="txtTelefono"> Telefono: </label>
                            <input type="text" name="txtTelefono" id="txtTelefono" class="form-control" required value="<?php echo isset($aClientes[$pos]["telefono"])? $aClientes[$pos]["telefono"] : ""; ?>">                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="txtCorreo"> Correo: </label>
                            <input type="text" name="txtCorreo" id="txtCorreo" class="form-control" required value="<?php echo isset($aClientes[$pos]["correo"])? $aClientes[$pos]["correo"] : ""; ?>">                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="exampleFormControlFile1"> Archivos: </label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1">                                                      
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <button type="submit" id="btnInsertar" name="btnInsertar" class="btn btn-primary">Insertar</button>                                                     
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <table class="table table-hover border">
                    <tr>
                        <th> DNI </th>
                        <th> Nombre y apellido </th>
                        <th> Correo </th>
                        <th> Acciones </th>
                    </tr>
                    <?php 
                    $pos = 0;
                    foreach ($aClientes as $pos => $cliente) { ?>
                    <tr>
                        <td> <?php echo $cliente["dni"]; ?> </td>
                        <td> <?php echo $cliente["nombre"]; ?> </td>                        
                        <td> <?php echo $cliente["correo"]; ?> </td>
                        <td style="width: 110px;">
                            <a href="?pos=<?php echo $pos; ?>&do=edit"><i class="fas fa-edit"></i></a>
                            <a href="?pos=<?php echo $pos; ?>&do=delete"><i class="fas fa-trash-alt"></i></a>    
                        </td>
                    <?php 
                        $pos++;
                    }  ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>