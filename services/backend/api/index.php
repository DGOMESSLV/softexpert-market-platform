<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Src\Libs\App\Endpoint;
use Src\Libs\Http\Request;
use Src\Libs\Http\Response;

Endpoint::get(function (Request $request, Response $response) {
    $response->json([
        'app_env' => $_ENV['APP_ENV'],
        'version' => '1.0.0',
        'authors' => [
            [
                'name' => 'Diego Gomes',
                'contacts' => [
                    'email' => 'dgs190plc@outlook.com',
                    'phone' => '+5511958378163',
                ],
            ],
        ],
    ]);

    return $response;
});
