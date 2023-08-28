<?php declare(strict_types=1);

namespace Src\Libs\Http;

use \Exception;

/**
 * HTTP Request interface.
 *
 * @author Diego Gomes <dgs190plc@outlook.com>
 * @since 10/11/2022
 */
class Request
{
    /**
     * Stores request params from different methods.
     *
     * @property array $post
     * @property array $get
     * @property array $json
     * @property array $files
     */
    public array $post;
    public array $get;
    public array $json;
    public array $files;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;

        $this->parseJson();
    }

    /**
     * Try to parse JSON inputs.
     *
     * @return void
     */
    private function parseJson(): void
    {
        $rawBody = file_get_contents('php://input');
        $json = json_decode($rawBody, true);

        $this->json = $json ?? [];
    }

    /**
     * Get a index from array inputs.
     *
     * @param string $source
     * @param string $path
     * @param mixed [$defaultValue = null]
     * @param bool [$throwIfNotExists = false]
     *
     * @return mixed
     *
     * @throws App\exceptions\JSONException If $throwIfNotExists is true, this exception is throwed when the requested parameter are not present in source
     */
    protected function getPropertyFromSource(string $source, string $path, $defaultValue = null, bool $throwIfNotExists = false)
    {
        $value = $this->$source;
        $pathExists = true;
        $pathParts = explode('.', $path);

        foreach ($pathParts as $part) {
            if (!isset($value[$part])) {
                $pathExists = false;
                $value = $defaultValue;

                break;
            }

            $value = $value[$part];
        }

        if (!$pathExists && $throwIfNotExists) {
            throw new Exception("{$source} parameter '{$path}' are not provided as part of request.");
        }

        return $value;
    }

    /**
     * Recover a property from GET params using this::getPropertyFromSource function.
     *
     * @param string $path
     * @param mixed [$defaultValue = null]
     * @param bool [$throwIfNotExists = false]
     *
     * @return this::getPropertyFromSource
     */
    public function get(string $path, $defaultValue = null, bool $throwIfNotExists = false)
    {
        return $this->getPropertyFromSource('get', $path, $defaultValue, $throwIfNotExists);
    }

    /**
     * Recover a property from POST params using this::getPropertyFromSource function.
     *
     * @param string $path
     * @param mixed [$defaultValue = null]
     * @param bool [$throwIfNotExists = false]
     *
     * @return this::getPropertyFromSource
     */
    public function post(string $path, $defaultValue = null, bool $throwIfNotExists = false)
    {
        return $this->getPropertyFromSource('post', $path, $defaultValue, $throwIfNotExists);
    }

    /**
     * Recover a property from JSON params using this::getPropertyFromSource function.
     *
     * @param string $path
     * @param mixed [$defaultValue = null]
     * @param bool [$throwIfNotExists = false]
     *
     * @return this::getPropertyFromSource
     */
    public function json(string $path, $defaultValue = null, bool $throwIfNotExists = false)
    {
        return $this->getPropertyFromSource('json', $path, $defaultValue, $throwIfNotExists);
    }

    /**
     * Recover a file if it exists.
     *
     * @param string $filename
     * @param bool [$throwIfNotExists = false]
     *
     * @return ?object
     *
     * @throws App\exceptions\JSONException If $throwIfNotExists is true, this exception is throwed when requested file is not present in request.
     */
    public function file(string $filename, bool $throwIfNotExists = false): ?object
    {
        $file = null;

        if (isset($this->files[$filename])) {
            $uploadedFile = $this->files[$filename];

            $file = (object)[
                'name' => $uploadedFile['full_path'],
                'existsInServer' => file_exists($uploadedFile['tmp_name']),
                'tmpPath' => $uploadedFile['tmp_name'],
                'mimeType' => $uploadedFile['type'],
                'size' => (object)[
                    'bytes' => $uploadedFile['size'],
                    'kb' => round(($uploadedFile['size'] / 1024), 2),
                    'mb' => round((($uploadedFile['size'] / 1024) / 1000), 2),
                    'gb' => round(((($uploadedFile['size'] / 1024) / 1000) / 1000), 2),
                ],
                'base64' => null,
                'dataUri' => null,
                'content' => null,
            ];

            if ($file->existsInServer) {
                $file->content = file_get_contents($file->tmpPath) ?? false;

                if ($file->content) {
                    $file->base64 = base64_encode($file->content);
                    $file->dataUri = "data:{$file->mimeType};data,{$file->base64}";
                }
            }
        }

        if (!$file && $throwIfNotExists) {
            throw new Exception("File '{$filename}' is not sended in request.");
        }

        return $file;
    }

    /**
     * Returns the request method.
     * 
     * @return stirng
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}