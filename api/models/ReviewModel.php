<?php

require_once 'BaseModel.php';

class ReviewModel extends BaseModel{

    //Una white list (lista blanca) es una técnica para permitir solo ciertos valores que uno
    // define como válidos. Se usa para asegurarse de que el usuario 
    // solo pueda ordenar por columnas que realmente existen en la base de datos, y 
    // en direcciones (ASC o DESC) que sean válidas.
    
    private function white_list(&$value, $allowed, $message) {
        if ($value === null) {
            return $allowed[0];
        }
        $key = array_search($value, $allowed, true);
        if ($key === false) { 
            throw new InvalidArgumentException($message); 
        } else {
            return $value;
        }
    }

    public function getReviews($orden = []) {
        $order = 'puntuacion';
        $direction = 'ASC';
        $page = 1;
        $limit = 10;
        $offset = 0;
        
        $field = null;
        $value = null;

        $sql = "SELECT * FROM review WHERE 1=1";

        //Orden 
        if (isset($orden['sort'])) {
            $order = $orden['sort'];
            if (isset($orden['order'])) {
                $direction = $orden['order'];
            }
        }

        $order = $this->white_list($order, ['id_review','id_producto','nombre_usuario', 'comentario', 'puntuacion', 'fecha'], 'Campo de orden no válido');
        $direction = strtoupper($direction);//poner el mayuscula  
        $direction = $this->white_list($direction, ['ASC', 'DESC'], 'Dirección de orden no válida');

        //Paginación
        if (isset($orden['page']) && isset($orden['limit'])) {
            $page = (int)$orden['page'];
            $limit = (int)$orden['limit'];
            $offset = ($page - 1) * $limit;
        }

        //Filtro
        if (isset($orden['field']) && isset($orden['value']) && $orden['value'] !== '') {
            $field = $this->white_list($orden['field'], ['id_review','id_producto','nombre_usuario','comentario','puntuacion','fecha'], 'Campo de filtro no válido');
            $value = $orden['value'];
            $sql .= " AND $field LIKE $value";
        }

        $sql .= " ORDER BY $order $direction LIMIT $limit OFFSET $offset";
    
        $db = $this->crearConexion();
        $query = $db->prepare("SELECT r.*, p.nombre AS producto_nombre FROM review r JOIN producto p ON r.id_producto = p.id_producto");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getReviewById($id){
        $db = $this->crearConexion();
        $query = $db->prepare("SELECT * FROM review WHERE id_review = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);

    }

    public function agregarReview($id_producto, $nombre_usuario, $comentario, $puntuacion) {
        $db = $this->crearConexion();
        $query = $db->prepare("INSERT INTO review (id_producto, nombre_usuario, comentario, puntuacion) VALUES (?, ?, ?, ?)");
        $query->execute([$id_producto, $nombre_usuario, $comentario, $puntuacion]);
        return $db->lastInsertId();
    }

    public function updateReviewById($id, $comentario, $puntuacion) {
        $db = $this->crearConexion();
        $query = $db->prepare("UPDATE review SET comentario = ?, puntuacion = ? WHERE id_review = ?");
        $query->execute([$comentario, $puntuacion, $id]);
    }
}