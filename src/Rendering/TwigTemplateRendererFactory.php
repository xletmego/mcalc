<?php declare(strict_types=1);

namespace App\Rendering;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigTemplateRendererFactory
{
    private $templateDirectory;

    public function __construct(TemplateDirectory $templateDirectory)
    {
        $this->templateDirectory = $templateDirectory;
    }

    public function create(): TwigTemplateRenderer
    {
        $templateDirectory = $this->templateDirectory->toString();
        $loader = new FilesystemLoader(array($templateDirectory));
        $environment = new Environment($loader, array());

        return new TwigTemplateRenderer($environment);
    }
}
