<?php declare(strict_types=1);

namespace App\Page;

use App\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Controller
{
    private TemplateRenderer $renderer;

    public function __construct(TemplateRenderer $render)
    {
        $this->renderer = $render;
    }

    public function home(Request $request): Response
    {

        return $this->showErrorPage("Not found", 404);
    }

    public function showErrorPage(string $message, int $code): Response
    {
        return new Response(
            $this->renderer->render(
                'Error.html.twig',
                array(
                    'message' => $message
                )
            ),
            $code
        );
    }
}
