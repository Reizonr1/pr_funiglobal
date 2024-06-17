<?php
include 'conexion.php';

function deleteDuplicates() {
    global $conn;
    
    // creamos la consulta sql para eliminar las imagenes duplicadas.
    $sql = 'DELETE pi1
        FROM product_images pi1
        JOIN (
            SELECT MIN(product_image_id) AS min_id, product_id, image_id
            FROM product_images
            GROUP BY product_id, image_id
        ) AS pi2
        ON pi1.product_image_id > pi2.min_id
        AND pi1.product_id = pi2.product_id
        AND pi1.image_id = pi2.image_id';

    // ejecutamos la consulta sql y obtenemos el resultado.
    $result = mysqli_query($conn, $sql);

    // Comprobar si la consulta tuvo éxito
    if (!$result) {
        echo "Error en la consulta: " . mysqli_error($conn);
        return;
    }

    // Obtener el número de filas afectadas
    $affectedRows = mysqli_affected_rows($conn);

    // Comprobar si se encontraron y eliminaron duplicados
    if ($affectedRows > 0) {
        echo "Se encontraron y se eliminaron imágenes duplicadas.";
    } else {
        echo "No se encontraron imágenes duplicadas.";
    }
}

// funcion para identificar productos que tienen imagens duplicadas
function searchDuplicated() {
    global $conn;
    
    // creamos la consulta sql para buscar las imagenes duplicadas.
    $sql = 'SELECT img.image_id, GROUP_CONCAT(img.product_id) AS product_ids, COUNT(*) AS num_products
            FROM product_images img
            GROUP BY img.image_id
            HAVING COUNT(*) > 1';

    // ejecutamos la consulta sql y obtenemos el resultado.
    $result = mysqli_query($conn, $sql);


    // comprobamos si la consulta tuvo éxito.
    if (!$result) {
        echo "Error en la consulta: " . mysqli_error($conn);
        return;
    }

    // comprobamos si la consulta tuvo resultados.
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Se encontraron duplicados: Image ID: " . $row['image_id'] . " - Product IDs: " . $row['product_ids'] . " - Number of Products: " . $row['num_products'] . "<br>";

            // eliminamos las imagenes duplicadas.
            deleteDuplicates();
        }
    } else {
        echo "No se encontraron imágenes duplicadas.";
    }
}

// funcion para verificar que cada producto tenga una imagen primaria. si tienen mulitples imágenes primarias y estable lo demas como no primarias.
function fixMultiplePrimaryImages() {
    global $conn;

    // Primero, identificar productos con múltiples imágenes primarias y mantener solo la más reciente
    $sql_select = 'SELECT product_id, MAX(product_image_id) AS latest_primary_id
                   FROM product_images
                   WHERE cover = true
                   GROUP BY product_id
                   HAVING COUNT(*) > 1';

    $result_select = mysqli_query($conn, $sql_select);

    if (!$result_select) {
        echo mysqli_error($conn);
        return;
    }

    // Actualizar todas las imágenes primarias adicionales a no primarias
    $sql_update = 'UPDATE product_images pi
                   JOIN (
                       SELECT product_id, product_image_id
                       FROM product_images
                       WHERE cover = true
                       AND product_image_id NOT IN (
                           SELECT MAX(product_image_id)
                           FROM product_images
                           WHERE cover = true
                           GROUP BY product_id
                       )
                   ) AS duplicates
                   ON pi.product_id = duplicates.product_id
                   AND pi.product_image_id = duplicates.product_image_id
                   SET pi.cover = false';

    $result_update = mysqli_query($conn, $sql_update);

    if (!$result_update) {
        echo "Error en la consulta UPDATE: " . mysqli_error($conn);
        return;
    }

    $affectedRows = mysqli_affected_rows($conn);

    if ($affectedRows > 0) {
        echo "Se corrigieron imágenes primarias duplicadas.";
    } else {
        echo "No se encontraron imágenes primarias duplicadas.";
    }
}

searchDuplicated();
fixMultiplePrimaryImages();
?>