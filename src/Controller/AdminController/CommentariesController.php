<?php


namespace App\Controller\AdminController;


use App\Model\AdminModel\CommentariesModel;

class CommentariesController
{
    protected $data;
    protected $database;

    public function __construct()
    {
        $this->database = new CommentariesModel();
    }

    public function indexAction()
    {
        $this->data = $this->database->innerJoin();

        $response = [ 'path' => 'AdminView/Pages/commentaries.twig',
            'data' => ['commentaire' => $this->data],
        ];

        return $response;
    }
}