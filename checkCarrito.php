<?php
session_start();
require_once 'conexion.php'; 

// posibles problemas que veo en este codigo:
// 1. No hacemos una verificacion del product_id.
// 2. No verificamos que la cantidad sea un numero.
// 3. No verificamos que la cantidad sea mayor a 0.
// 4. No comprobamos si product_id existe en la base de datos.
// 5. Los datos de $_POST, no son sanitizados (usaré un filer_input para poder verificar si el id existe en la base dse datos.)
// 6. Se podría trabajar un modelo de vista controlador. (no lo he hecho por respetar el archivo del codigo del ejercicio)

function addToCart($productId, $quantity) {
    global $conn;
    // verificamos que el producto exista en la base de datos.
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // verificar si existe:
    if (mysqli_num_rows($result) <= 0){
        echo "El producto no existe en la base de datos.";
        return;
    }

    // validamos valores de entrada validos.
    if (!is_int($productId) ||!is_int($quantity) || $quantity <= 0) {
        echo "Datos de entrada inválidos.";
        return;
    }
    // esta parte de codigo inizializa el carrito, si no existe.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    echo "Producto agregado al carrito.";
}

// agregamomos un filter_input para sanitizar los datos..
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

// verificamos $product_id
if (!$productId) {
    die("ID de producto inválido.");
}

// agregamomos un filter_input para sanitizar los datos..
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

// verificamos $quantity
if (!$quantity || $quantity <= 0) {
    die("Cantidad inválida.");
}

// llamamos a la funcion para agregar el producto al carrito.
addToCart($productId, $quantity);
// En vez de dar el echo de succesfull aquí lo damos en el codigo, ya que si hay un error este se mostraria igualmente y podría confundir al usuario.
?>