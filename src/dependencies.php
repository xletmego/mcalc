<?php declare(strict_types=1);

use Auryn\Injector;

use App\Data\Dbal\DatabaseUrl;
use App\Data\Dbal\ConnectionFactory;
use Doctrine\DBAL\Connection;

$injector = new Injector();

$injector->define(\App\Configuration::class, array(':path' => ROOT_DIR . '/config.php'));
$config = $injector->make(\App\Configuration::class);
$injector->share($config);


$injector->define(
    DatabaseUrl::class,
    array(':url' => 'sqlite:///' . ROOT_DIR . DIRECTORY_SEPARATOR . $config->get('sqlite_file'))
);
$injector->delegate(
    Connection::class,
    function () use ($injector) {
        $factory = new ConnectionFactory($injector->make(DatabaseUrl::class));
        return $factory->create();
    }
);

//renderer
$templateDirectory = $injector->make(\App\Rendering\TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);
$injector->delegate(
    \App\Rendering\TemplateRenderer::class,
    function () use ($templateDirectory): \App\Rendering\TemplateRenderer {
        $factory = new \App\Rendering\TwigTemplateRendererFactory($templateDirectory);
        return $factory->create();
    }
);


return $injector;
