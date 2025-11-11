<?php

require_once 'api/view/JsonView.php';
require_once 'api/models/ReviewModel.php';

class ReviewController{
    private $model;
    private $view;
    private $data;

    public function __construct(){
        $this->model = new ReviewModel();
        $this->view = new JSONView();
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getReviews(){
        $reviews = $this->model->getReviews();

        if(!empty($reviews)){
            $this->view->response($reviews, 200);
        }
        else{
          $this->view->response("No hay reviews cargadas", 404);  
        }
    }

    public function getReview($params=[]){
        $id = $params[':ID'];
        $review = $this->model->getReviewById($id);

        if(!empty($review)){
            $this->view->response($review, 200);
        }
        else{
          $this->view->response("No hay una review con ese id", 404);  
        }
    }

    // POST /reviews
    public function addReview() {
        $data = $this->getData();

        if (empty($data->id_producto) || empty($data->nombre_usuario) || empty($data->comentario) || empty($data->puntuacion)) {
            $this->view->response("Faltan datos obligatorios", 400);
            return;
        }

        $id = $this->model->agregarReview(
            $data->id_producto,
            $data->nombre_usuario,
            $data->comentario,
            $data->puntuacion
        );

        $review = $this->model->getReviewById($id);
        $this->view->response($review, 201);
    }

    // PUT /reviews/:ID
    public function updateReview($params = []) {
        $id = $params[':ID'];
        $review = $this->model->getReviewById($id);

        if (!$review) {
            $this->view->response("La review id=$id no existe", 404);
            return;
        }

        $data = $this->getData();
        $this->model->updateReviewById($id, $data->comentario, $data->puntuacion);
        $this->view->response("La review id=$id se actualizó con éxito", 200);
    }
}