<?php

namespace App\Rendering;

interface TemplateRenderer
{
    public function render(string $template, array $data = []): string;
}
