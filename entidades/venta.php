<?php

class Venta {
    private $idventa;
    private $fk_idcliente;
    private $fk_idproducto;
    private $fecha;
    private $cantidad;
    private $preciounitario;
    private $importe;

    public function __construct() {
        $this->cantidad = 0;
        $this->preciounitario = 0.0;  
        $this->importe = 0.0;  
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request){
        $this->idventa = isset($request["id"])? $request["id"] : "";
        $this->fk_idcliente = isset($request["lstCliente"])? $request["lstCliente"] : "";
        $this->fk_idproducto = isset($request["lstProducto"])? $request["lstProducto"]: "";
        $this->fecha = isset($request["txtFecha"])? $request["txtFecha"]: "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"] : 0;
        $this->preciounitario = isset($request["txtPrecioUni"])? $request["txtPrecioUni"] : 0;
        $this->importe = isset($request["txtImporte"])? $request["txtImporte"] : 0;
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO ventas (
                    fk_idcliente, 
                    fk_idproducto, 
                    fecha, 
                    cantidad,
                    preciounitario,
                    importe
                ) VALUES (
                    " . $this->fk_idcliente .", 
                    " . $this->fk_idproducto .", 
                    '" . $this->fecha ."', 
                    " . $this->cantidad .",
                    " . $this->preciounitario .",
                    " . $this->importe ."  
                );";
        //Ejecuta la query
        $mysqli->query($sql);
        //Obtiene el id generado por la inserción
        $this->idventa = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE ventas SET
                fk_idcliente = ".$this->fk_idcliente.",
                fk_idproducto = ".$this->fk_idproducto.",
                fecha = '".$this->fecha."',
                cantidad = ".$this->cantidad.",
                preciounitario = ".$this->preciounitario.",
                importe = ".$this->importe."
                WHERE idventa = " . $this->idventa;
          
        $mysqli->query($sql);
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM ventas WHERE idventa = " . $this->idventa;
        //Ejecuta la query
        $mysqli->query($sql);
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT  idventa,
                        fk_idcliente, 
                        fk_idproducto, 
                        fecha, 
                        cantidad, 
                        preciounitario,
                        importe 
                FROM ventas 
                WHERE idventa = " . $this->idventa;
        $resultado = $mysqli->query($sql);

        if($resultado){
            //Convierte el resultado en un array asociativo
            $fila = $resultado->fetch_assoc();
            $this->idventa = $fila["idventa"];
            $this->fk_idcliente = $fila["fk_idcliente"];
            $this->fk_idproducto = $fila["fk_idproducto"];
            $this->fecha = $fila["fecha"];
            $this->cantidad = $fila["cantidad"];
            $this->preciounitario = $fila["preciounitario"];
            $this->importe = $fila["importe"];
        }
        $mysqli->close();

    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT  idventa, 
                        fk_idcliente, 
                        fk_idproducto, 
                        fecha, 
                        cantidad, 
                        preciounitario, 
                        importe 
                FROM ventas";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $ventaAux = new Venta();
                $ventaAux->idventa = $fila["idventa"];
                $ventaAux->fk_idcliente = $fila["fk_idcliente"];
                $ventaAux->fk_idproducto = $fila["fk_idproducto"];
                $ventaAux->fecha = $fila["fecha"];
                $ventaAux->cantidad = $fila["cantidad"];
                $ventaAux->preciounitario = $fila["preciounitario"];
                $ventaAux->importe = $fila["importe"];
                $aResultado[] = $ventaAux;
            }
        }
        return $aResultado;
    }

    public function obtenerFacturacionMensual($mes) {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT SUM(importe) AS total FROM ventas WHERE MONTH(fecha) = $mes";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $fila = $resultado->fetch_assoc();

            return $fila["total"];

    }

    public function obtenerFacturacionAnual($anio) {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT SUM(importe) AS total FROM ventas WHERE YEAR(fecha) = $anio";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $fila = $resultado->fetch_assoc();

            return $fila["total"];

    }    

    public function cargarGrilla() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
                A.idventa,
                A.fecha,
                A.cantidad,
                A.fk_idcliente,
                B.nombre as nombre_cliente,
                A.fk_idproducto,
                A.importe,
                A.preciounitario,
                C.nombre as nombre_producto
            FROM ventas A
            INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
            INNER JOIN productos C ON A.fk_idproducto = C.idproducto
            ORDER BY A.fecha DESC";
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }    
        
        $aResultado = array();
        if($resultado) {

            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Venta();
                $entidadAux->idventa = $fila["idventa"];
                $entidadAux->fk_idcliente = $fila["fk_idcliente"];
                $entidadAux->fk_idproducto = $fila["fk_idproducto"];
                $entidadAux->fecha = $fila["fecha"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->preciounitario = $fila["preciounitario"];
                $entidadAux->nombre_cliente = $fila["nombre_cliente"];
                $entidadAux->nombre_producto = $fila["nombre_producto"];
                $entidadAux->importe = $fila["importe"];
                $aResultado[] = $entidadAux;
            }

        $mysqli->close();
        return $aResultado;
        }
    }

}


?>