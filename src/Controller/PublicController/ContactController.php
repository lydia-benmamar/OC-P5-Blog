<?php

namespace App\Controller\PublicController;

use Core\Controller\FrontController;
use Core\Model\Mail\Mail;
use Core\Model\View\View;

/**
 * Class ContactController
 * @package App\Controller\PublicController
 */
class ContactController extends FrontController
{
    /**
     * @var View
     */
    protected $view;
    /**
     * @var Mail
     */
    protected $mail;

    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->mail = new Mail();
        $this->view->addView();
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $sujet = filter_input(INPUT_POST, 'sujet', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        if ($nom !== null && $email !== null && $sujet !== null && $message !== null) {

            $data = [
                'expediteur' => $email,
                'nom-expediteur' => $nom,
                'objet' => $sujet,
                'message' => $message
            ];

            $this->mail->mailTo($data);
            $this->redirect('/public/contact');

        }
        $response = [ 'path' => 'PublicView/Pages/contact.twig',
            'data' => []
        ];

        return $response;
    }
}
