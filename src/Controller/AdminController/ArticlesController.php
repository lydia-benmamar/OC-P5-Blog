<?php

namespace App\Controller\AdminController;

use App\Controller\ErrorsController\ErrorsController;
use App\Model\AdminModel\ArticlesModel;
use Core\Controller\FrontController;


use \date;

/**
 * Class ArticlesController
 * @package App\Controller\AdminController
 */
class ArticlesController extends FrontController
{

    /**
     * @var
     */
    protected $session;
    /**
     * @var ArticlesModel
     */
    protected $database;
    /**
     * @var array
     */
    protected $response;
    /**
     * ArticlesController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->database = new ArticlesModel();
        $errors = new ErrorsController();

        if ($this->cookies->dataJWT('user', 'level') > 2 || $this->cookies->dataJWT('user', 'level') === false) {
            $this->response = ['path' => $errors->unauthorized(),
                'data' => []
            ];
        }
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        if (!isset($this->response)) {
            $articles_categories = $this->database->innerJoin();

            $this->response = ['path' => 'AdminView/Pages/articles.twig',
                'data' => ['articles' => $articles_categories],
            ];
        }

        return $this->response;
    }

    /**
     * @return array
     */
    public function updateAction()
    {

        if (!isset($this->response)) {
            $id_article = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

            $inputName = filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_STRING);
            $inputChapo = filter_input(INPUT_POST, 'inputChapo', FILTER_SANITIZE_STRING);
            $inputType = filter_input(INPUT_POST, 'inputType', FILTER_SANITIZE_STRING);
            $inputContenue = filter_input(INPUT_POST, 'inputContenue', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($inputName !== null && $inputChapo !== null
                && $inputType !== null && $inputContenue !== null) {

                $filename = $this->upload('photoarticle');

                $data = [
                    'nom_article' => $inputName,
                    'chapo_article' => $inputChapo,
                    'auteur_article' => $this->cookies->dataJWT('user', 'name'),
                    'id_categories' => $inputType,
                    'contenue_article' => $inputContenue,
                    'image' => 'img\\\\photoarticle\\\\' . $filename,
                    'date_maj' => date("Y-m-d H:i:s")
                ];

                $this->database->update('articles', $id_article, $data, 'id');

                $this->cookies->setCookies('articles', 'V - Article mis à jour');
                $this->redirect('/admin/articles');
            }

            $articles = $this->database->read('articles', $id_article, 'id', false);
            $categories = $this->database->read('categories');

            $this->response = ['path' => 'AdminView/Pages/updateArticles.twig',
                'data' => ['articles' => $articles,
                    'types' => $categories],
            ];
        }
        return $this->response;
    }

    /**
     * @return array
     */
    public function createAction()
    {
        if (!isset($this->response)) {
            $categories = $this->database->read('categories');

            $inputName = filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_STRING);
            $inputChapo = filter_input(INPUT_POST, 'inputChapo', FILTER_SANITIZE_STRING);
            $inputType = filter_input(INPUT_POST, 'inputType', FILTER_SANITIZE_STRING);
            $inputContenue = filter_input(INPUT_POST, 'inputContenue', FILTER_SANITIZE_SPECIAL_CHARS);
            $nomAuteur = $this->cookies->dataJWT('user', 'name');
            $id_auteur = $this->database->read('users', $nomAuteur, 'username', false);

            if ($inputName !== null && $inputChapo !== null
                && $inputType !== null && $inputContenue !== null) {

                $filename = $this->upload('photoarticle');

                $data = [
                    'nom_article' => $inputName,
                    'chapo_article' => $inputChapo,
                    'auteur_article' => $this->cookies->dataJWT('user', 'name'),
                    'id_auteur' => $id_auteur[0]['id'],
                    'id_categories' => $inputType,
                    'contenue_article' => $inputContenue,
                    'image' => 'img\\\\photoarticle\\\\' . $filename,
                    'date_creation' => date('Y-m-d H:i:s')
                ];

                $this->database->create('articles', $data);

                $this->cookies->setCookies('articles', 'V - Article mis en ligne !');
                $this->redirect('/admin/articles');
            }


            $this->response = ['path' => 'AdminView/Pages/createArticles.twig',
                'data' => ['types' => $categories]
            ];
        }
        return $this->response;
    }

    /**
     * @return array
     */
    public function deleteAction()
    {
        if (!isset($this->response)) {
            $id_article = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

            $id_commentaire = $this->database->read('commentaire', $id_article, 'id_article', false);

            foreach ($id_commentaire as $todelete) {
                $this->database->delete('commentaire', $todelete['id']);
            }
            $this->database->delete('articles', $id_article);
        }

        $this->cookies->setCookies('articles', 'V - Article supprimer !');
        $this->redirect('/admin/articles');

        return $this->indexAction();
    }

}
