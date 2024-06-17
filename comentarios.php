<?php
include 'conexion.php';
function updateComentariosVacios(){
    global $conn;

    // Paso 1: Obtener todos los pedidos que tienen comentarios vacíos
    $sql = "SELECT id, order_id FROM comments WHERE name IS NULL AND email IS NULL AND comment IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Procesar cada fila de comentarios vacíos
        while ($row = $result->fetch_assoc()) {
            $comment_id = $row['id'];
            $order_id = $row['order_id'];

            // Paso 2: Obtener comentarios desde la API externa
            $api_url = "https://jsonplaceholder.typicode.com/comments?postId=$order_id";
            $comments_json = file_get_contents($api_url);

            if ($comments_json) {
                $comments = json_decode($comments_json, true);

                // Solo consideramos el primer comentario (asumiendo que la API devuelve en orden)
                if (isset($comments[0])) {
                    $comment_data = $comments[0];

                    // Actualizar la fila de la base de datos con los datos obtenidos
                    $name = $comment_data['name'];
                    $email = $comment_data['email'];
                    $comment_text = $comment_data['body'];

                    $update_sql = "UPDATE comments SET name='$name', email='$email', comment='$comment_text' WHERE id=$comment_id";

                    if ($conn->query($update_sql) === TRUE) {
                        echo "Comentario actualizado para el comentario con ID $comment_id.\n";
                        // Paso 4: Registrar en el log
                        error_log("Comentario actualizado para el comentario con ID $comment_id.");
                    } else {
                        echo "Error al actualizar el comentario: " . $conn->error . "\n";
                        // Paso 3: Manejar errores
                        error_log("Error al actualizar el comentario en la base de datos: " . $conn->error);
                    }
                } else {
                    echo "No se encontraron comentarios para el pedido con ID $order_id desde la API.\n";
                    // Paso 4: Registrar en el log
                    error_log("No se encontraron comentarios para el pedido con ID $order_id desde la API.");
                }
            } else {
                echo "Error al obtener datos desde la API.\n";
                // Paso 3: Manejar errores
                error_log("Error al obtener datos desde la API: $api_url");
            }
        }
    } else {
        echo "No hay comentarios vacíos para actualizar.\n";
    }
}
?>
