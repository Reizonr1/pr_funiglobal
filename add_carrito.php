<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar al carrito</title>
</head>
<body>
    <h2>Agregar al carrito</h2>
    <form action="http://localhost/prueba_tecnica/checkCarrito.php" method="post">
        <label for="product_id">ID del producto:</label>
        <input type="text" id="product_id" name="product_id" required>
        <br><br>
        <label for="quantity">Cantidad:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
        <br><br>
        <button type="submit">Agregar al carrito</button>
    </form>
</body>
</html>