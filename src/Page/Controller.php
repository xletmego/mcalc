<?php declare(strict_types=1);

namespace App\Page;

use App\Configuration;
use App\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class Controller
{
    private TemplateRenderer $renderer;
    private Configuration $configuration;

    public function __construct(Configuration $configuration, TemplateRenderer $render)
    {
        $this->renderer = $render;
        $this->configuration = $configuration;
    }

    public function isAuth(SessionInterface $session){
        return $session->get('auth', false);
    }

    public function authentication(Request $request): Response
    {

        $login = $request->get('login', '');
        $password = $request->get('password','');
        if($this->configuration->get('login') === $login && $this->configuration->get('password') === $password){
            $session = $request->getSession();
            $session->set('auth', true);
            return $this->home($request);
        }


        return new Response($this->renderer->render('Authentication.twig'));
    }

    public function home(Request $request): Response
    {
        return new Response($this->renderer->render('Home.twig'));
    }

    public function showErrorPage(string $message, int $code): Response
    {
        return new Response(
            $this->renderer->render(
                'Error.twig',
                array(
                    'message' => $message
                )
            ),
            $code
        );
    }
}
