<?php

namespace Core\View\Twig;

use Core\Controller\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigAdd extends AbstractExtension
{

    public function getFunctions()
    {
        return array(
            new TwigFunction('currentUrl', array($this, 'currentUrl')),
            new TwigFunction('pathPost', array($this, 'pathPost')),
            new TwigFunction('errors', array($this, 'errors')),
            new TwigFunction('isLoged', array($this, 'isLoged')),
            new TwigFunction('userName', array($this, 'userName')),
            new TwigFunction('userImage', array($this, 'userImage')),
            new TwigFunction('userLevel', array($this, 'userLevel')),
            new TwigFunction('validate', array($this, 'validate')),
            new TwigFunction('authorized', array($this, 'authorized')),
        );
    }


    public function currentUrl(string $url = null)
    {

        $side = filter_input(INPUT_GET, 'side');
        $rubric = filter_input(INPUT_GET, 'rubric');
        $request = filter_input(INPUT_GET, 'request');

        if ($side !== null) {
            $pageCurrent = 'side=' . $side;
        } else {
            $pageCurrent = 'side=public';
        }

        if($rubric !== null)
        {
            $pageCurrent .= '&rubric=' . $rubric;
        }

        if($request !== null)
        {
            $pageCurrent .= '&request=' . $request;
        }

        if ($pageCurrent === $url) {
            return ' active';
        } else {
            return '';
        }
    }

    public function pathPost()
    {

        $side = filter_input(INPUT_GET, 'side');
        $rubric = filter_input(INPUT_GET, 'rubric');
        $request = filter_input(INPUT_GET, 'request');
        $id_path = filter_input(INPUT_GET, 'id');

        if ($side !== null) {
            $pathPost = 'index.php?side=' . $side;
        }

        if($rubric !== null)
        {
            $pathPost .= '&rubric=' . $rubric;
        }

        if($request !== null)
        {
            $pathPost .= '&request=' . $request;
        }

        if ($id_path !== null) {
            $pathPost .= '&id=' . $id_path;
        }

        return $pathPost;
    }

    public function errors(string $page)
    {
        if (isset($_SESSION['error'][$page])) {
            $error = $_SESSION['error'][$page];
            $_SESSION['error'][$page] = '';
            return $error;
        }
    }

    public function validate(string $page)
    {
        if (isset($_SESSION['validate'][$page])) {
            $validate = $_SESSION['validate'][$page];
            $_SESSION['validate'][$page] = '';
            return $validate;
        }
    }

    public function isLoged(string $howto = null)
    {
        if (Session::isLogged() === true) {
            if ($howto === 'toHidde') {
                return 'hidden';
            } else if ($howto === 'toVisible') {
                return '';
            }
        } else {

            if ($howto === 'toHidde') {
                return '';
            } else if ($howto === 'toVisible') {
                return 'hidden';
            }

        }
    }


    public function userName()
    {
        return Session::isLogged() === true ? ucfirst($_SESSION['user']['name']) : '';
    }

    public function userImage()
    {
        return Session::isLogged() === true ? $_SESSION['user']['image'] : '';
    }

    public function userLevel()
    {
        return Session::isLogged() === true ? $_SESSION['user']['level'] : '';
    }

}
