# pr_funiglobal
Prueba tecnica, FuniGlobal

Tienes un sistema de ecommerce con tres tablas principales relacionadas con los productos y sus
imágenes. En ocasiones, las imágenes de los productos pueden estar mal vinculadas, ya sea
porque están duplicadas o porque apuntan a archivos incorrectos. Tu tarea es corregir estas
inconsistencias.
1. Escribe una consulta SQL para identificar productos que tienen imágenes duplicadas (es
decir, el mismo image_id asociado a un product_id)
2. Escribe una consulta SQL para eliminar todas las imágenes duplicadas, dejando solo una
imagen única por image_id y product_id
3. Escribe una consulta SQL para verificar que cada producto tiene solo una imagen primaria
(cover = true). Si un producto tiene múltiples imágenes primarias, selecciona la más
reciente basada en product_image_id como la primaria y establece las demás como no
primarias (cover = false)
Ejecuta este script para generar el modelo de datos:
-- DDL
CREATE TABLE products (
product_id INT AUTO_INCREMENT PRIMARY KEY,
product_name VARCHAR(255) NOT NULL,
description TEXT,
price DECIMAL(10, 2),
stock INT
);
CREATE TABLE images (
image_id INT AUTO_INCREMENT PRIMARY KEY,
image_url VARCHAR(255) NOT NULL
);
CREATE TABLE product_images (
product_image_id INT AUTO_INCREMENT PRIMARY KEY,
product_id INT,
image_id INT,
cover BOOLEAN,
FOREIGN KEY (product_id) REFERENCES products(product_id),
FOREIGN KEY (image_id) REFERENCES images(image_id)
);

COMPANY

Funiglobal Development S.L.

Page 3 of 7

DOCUMENT PRUEBA TÉCNICA - IT DEVELOPER JUNIOR
MODIFIED 17/06/2024 17:30

Funiglobal
V1.0 - 10/06/2024

-- Inserciones
INSERT INTO products (product_name, description, price, stock) VALUES
('Producto 1', 'Descripción del producto 1', 10.00, 100),
('Producto 2', 'Descripción del producto 2', 20.00, 50),
('Producto 3', 'Descripción del producto 3', 30.00, 30);
INSERT INTO images (image_url) VALUES
('http://example.com/image1.jpg'),
('http://example.com/image2.jpg'),
('http://example.com/image3.jpg'),
('http://example.com/image4.jpg'),
('http://example.com/image5.jpg');
INSERT INTO product_images (product_id, image_id, cover) VALUES
(1, 1, true),
(1, 1, false),
(1, 2, false),
(2, 3, true),
(2, 4, true),
(3, 5, true);

CASO PRÁCTICO 2: ERROR AGREGANDO PRODUCTOS AL CARRITO

Has recibido múltiples reportes de que la funcionalidad de agregar productos al carrito está
fallando de manera intermitente. A veces, al intentar agregar un producto al carrito, los usuarios
obtienen un error y el producto no se agrega correctamente.
1. Analiza el código proporcionado y describe posibles causas que podrían estar provocando
que la funcionalidad falle de manera intermitente
2. Escribe y explica una versión corregida del código que solucione los problemas
identificados y asegure que la funcionalidad de agregar productos al carrito sea robusta y
confiable.
Este es el fragmento de código:
<?php
session_start();
function addToCart($productId, $quantity) {
if (!isset($_SESSION['cart'])) {
$_SESSION['cart'] = [];
}
if (isset($_SESSION['cart'][$productId])) {
$_SESSION['cart'][$productId] += $quantity;
} else {
$_SESSION['cart'][$productId] = $quantity;
}
}
$productId = $_POST['product_id'];
$quantity = $_POST['quantity'];
if ($quantity <= 0) {
echo "Cantidad inválida.";
return;
}
addToCart($productId, $quantity);
echo "Producto agregado al carrito.";
?>

CASO PRÁCTICO 3: COMENTARIOS NO REGISTRADOS

Recientemente, el sistema de gestión de pedidos sufrió una pérdida de datos que afectó a los
comentarios de los pedidos. Los campos name, email y comment en la tabla comments quedaron
vacíos. Para resolver este problema, se requiere una solución automatizada que consulte una API
externa -https://jsonplaceholder.typicode.com/comments- para recuperar los comentarios de los
pedidos y actualizar la base de datos con la información obtenida.
Para esto realiza en php los siguientes pasos:
1. Obtén de base de datos todos los pedidos que tienen comentarios vacios
2. Obtén mediante REST través de la API indicada en la descripción los comentarios e inserta
la información en la fila correspondiente
3. Captura y maneja los posibles errores
4. Deja trazas -logs- en consola de las acciones más significativas
Ejecuta este script para generar el modelo de datos:
-- DDL
CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
tracking_id VARCHAR(255) NOT NULL,
shipping_status VARCHAR(50),
last_update DATETIME
);
CREATE TABLE comments (
id INT PRIMARY KEY,
order_id INT,
name VARCHAR(255) DEFAULT NULL,
email VARCHAR(255) DEFAULT NULL,
comment TEXT DEFAULT NULL,
FOREIGN KEY (order_id) REFERENCES orders(id)
);
-- Inserciones
INSERT INTO orders (id, tracking_id, shipping_status, last_update) VALUES
(1, '1234567890', 'In Transit', '2023-06-15 10:00:00'),
(2, '0987654321', 'Delivered', '2023-06-14 15:30:00'),
(3, '1122334455', 'In Transit', '2023-06-16 09:45:00'),
(4, '5566778899', 'Delivered', '2023-06-13 11:20:00');
(5, '9999999666', 'Delivered', '2023-06-17 13:40:00');
INSERT INTO comments (id, order_id, name, email, comment) VALUES
(1, 1, NULL, NULL, NULL),
(2, 1, NULL, NULL, NULL),
(3, 2, NULL, NULL, NULL),
(4, 2, NULL, NULL, NULL),
(5, 3, NULL, NULL, NULL),
(6, 3, NULL, NULL, NULL),
(7, 3, NULL, NULL, NULL),
(8, 4, NULL, NULL, NULL),
(9, 4, NULL, NULL, NULL),
(10, 4, NULL, NULL, NULL),
(11, 1, NULL, NULL, NULL),
(12, 2, NULL, NULL, NULL),
(13, 2, NULL, NULL, NULL),
(14, 3, NULL, NULL, NULL),
(15, 3, NULL, NULL, NULL),
(16, 3, NULL, NULL, NULL),
(17, 4, NULL, NULL, NULL),
(18, 4, NULL, NULL, NULL),
(19, 4, NULL, NULL, NULL),
(20, 4, NULL, NULL, NULL),
(21, 1, NULL, NULL, NULL),
(22, 2, NULL, NULL, NULL),
(23, 2, NULL, NULL, NULL),
(24, 3, NULL, NULL, NULL),
(25, 3, NULL, NULL, NULL),
(26, 3, NULL, NULL, NULL),
(27, 4, NULL, NULL, NULL),
(28, 4, NULL, NULL, NULL),
(29, 4, NULL, NULL, NULL),
(30, 4, NULL, NULL, NULL);