<?php

namespace Src\Libs\App;

use \Closure;
use \Throwable;
use Dotenv\Dotenv;
use Src\Libs\Http\Request;
use Src\Libs\Http\Response;

/**
 * This class serves the necessary methods and wrappers for easy creation of endpoints.
 * 
 * @author Diego Gomes <dgs190plc@outlook.com>
 * @since 08/27/2023
 */
class Endpoint
{
    /**
     * Stores endpoint method.
     * 
     * @property string
     */
    protected static ?string $method = null;

    /**
     * Stores request helper instance.
     * 
     * @property Src\Libs\Http\Request $request
     */
    protected static Request $request;

    /**
     * Stores response helper instance.
     * 
     * @property Src\Libs\Http\Response $response
     */
    protected static Response $response;

    /**
     * Static constructor.
     * 
     * @return void
     */
    protected static function __staticConstruct(): void
    {
        self::$request = new Request();
        self::$response = new Response();

        (Dotenv::createImmutable(__DIR__ . '/../../..'))->load();

        self::$response->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => 'Access-Control-Allow-Origin, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Accept, Accept-Language, Authorization, Content-Type', 
        ]);
    }

    /**
     * Wrraper for run the endpoint.
     * 
     * @param Closure $action
     * 
     * @return void
     */
    public static function handler(Closure $action): void
    {
        self::__staticConstruct();

        if (self::$method !== null && !in_array(self::$request->method(), [self::$method, 'options'])) {
            self::$response->statusCode = 405;
            self::$response->ends();
        }

        try {
            $response = $action(self::$request, self::$response);
            $response->ends();
        } catch (Throwable $e) {
            $errorData = [];

            if ($_ENV['APP_ENV'] === 'local') {
                $errorData = [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'backtrace' => $e->getTrace(),
                ];
            } else {
                $errorData['success'] = false;
                $errorData['message'] = 'An unknown error occurred.';
            }

            self::$response->json($errorData, 500);
            self::$response->ends();
        }
    }

    /**
     * Wrapper for run the endpoint only in GET requests.
     * 
     * @param Closure $action
     * 
     * @return void
     */
    public static function get(Closure $action): void
    {
        self::$method = 'get';
        
        self::handler($action);
    }

    /**
     * Wrapper for run the endpoint only in POST requests.
     * 
     * @param Closure $action
     * 
     * @return void
     */
    public static function post(Closure $action): void
    {
        self::$method = 'post';
        
        self::handler($action);
    }

    /**
     * Wrapper for run the endpoint only in PUT requests.
     * 
     * @param Closure $action
     * 
     * @return void
     */
    public static function put(Closure $action): void
    {
        self::$method = 'put';
        
        self::handler($action);
    }

    /**
     * Wrapper for run the endpoint only in PATCH requests.
     * 
     * @param Closure $action
     * 
     * @return void
     */
    public static function patch(Closure $action): void
    {
        self::$method = 'patch';
        
        self::handler($action);
    }

    /**
     * Wrapper for run the endpoint only in DELETE requests.
     * 
     * @param Closure $action
     * 
     * @return void
     */
    public static function delete(Closure $action): void
    {
        self::$method = 'delete';
        
        self::handler($action);
    }
}