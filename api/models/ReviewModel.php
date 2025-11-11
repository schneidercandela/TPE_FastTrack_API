<?php

require_once 'BaseModel.php';

class ReviewModel extends BaseModel{

   public function getReviews() {
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