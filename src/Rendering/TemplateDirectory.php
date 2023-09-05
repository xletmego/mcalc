<?php declare(strict_types=1);

namespace App\Rendering;

final class TemplateDirectory
{
    private $templateDirectory;

    public function __construct(string $rootDirectory)
    {
        $this->templateDirectory = $rootDirectory . '/templates';
    }

    public function toString():string
    {
        return $this->templateDirectory;
    }
}
