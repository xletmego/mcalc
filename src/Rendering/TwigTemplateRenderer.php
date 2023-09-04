<?php declare(strict_types=1);

namespace App\Rendering;

use Twig\Environment;

final class TwigTemplateRenderer implements TemplateRenderer
{
    private Environment $twigEnvironment;

    public function __construct(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }
    public function render(string $template, array $data = []): string
    {
        return $this->twigEnvironment->render($template, $data);
    }
}
