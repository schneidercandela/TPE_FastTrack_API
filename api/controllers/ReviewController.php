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

    /*public function getReviews(){
        $reviews = $this->model->getReviews();

        if(!empty($reviews)){
            $this->view->response($reviews, 200);
        }
        else{
          $this->view->response("No hay reviews cargadas", 404);  
        }
    }*/
    public function getReviews($params = []) {

        $orden = [];
        //El controlador pasa los parametros de orden al modelo si están presentes
        if (isset($_GET['sort'])) {
            $orden['sort'] = $_GET['sort'];
            if (isset($_GET['order'])) {
                $orden['order'] = $_GET['order'];
            }
        }

        //El controlador pasa los parametros de paginación al modelo si están presentes
        if(isset($_GET['page']) && isset($_GET['limit'])) {
            $orden['page'] = intval($_GET['page']);
            $orden['limit'] = intval($_GET['limit']);
        }

        if(isset($_GET['field']) && isset($_GET['value'])) {
            $orden['field'] = $_GET['field'];
            $orden['value'] = $_GET['value'];
        }

        $reviews = $this->model->getReviews($orden);

        if(!empty($reviews)){
               $this->view->response($reviews, 200);
        }else{
                $this->view->response("No hay reseñas cargadas", 404);
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

        if (empty($data->comentario)  empty($data->puntuacion)) {
            $this->view->response("Faltan datos obligatorios: comentario y puntuacion son requeridos", 400);
            return;
        }

        if (!is_numeric($data->puntuacion)  $data->puntuacion < 1 || $data->puntuacion > 10) {
            $this->view->response("La puntuación debe ser un número entre 1 y 10", 400);
            return;
        }

        $data = $this->getData();
        $this->model->updateReviewById($id, $data->comentario, $data->puntuacion);
        $this->view->response("La review id=$id se actualizó con éxito", 200);
    }
}