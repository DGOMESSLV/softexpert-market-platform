<?php declare(strict_types=1);

namespace Src\Libs\Http;

use \Exception;

/**
 * HTTP Response interface.
 *
 * @author Diego Gomes <dgs190plc@outlook.com>
 * @since 10/11/2022
 */
class Response
{
    /**
     * Stores response content.
     *
     * @property mixed $content
     */
    protected $content = null;

    /**
     * Stores response headers.
     *
     * @property array $headers
     */
    protected array $headers = [];

    /**
     * Stores response code.
     *
     * @property int $statusCode = 200
     */
    public int $statusCode = 200;

    /**
     * Stores response encoding.
     *
     * @property string $encoding = "utf-8";
     */
    public string $encoding = 'UTF-8';

    /**
     * Set response headers.
     *
     * @param array $headers
     *
     * @return void
     */
    public function headers(array $headers): void
    {
        foreach ($headers as $header => $value) {
            $this->headers[$header] = $value;
        }
    }

    /**
     * Static constructor.
     *
     * @static
     *
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Set a unique response header.
     *
     * @param string $header
     * @param string $value
     *
     * @return void
     */
    public function header(string $header, string $value): void
    {
        $this->headers([$header => $value]);
    }

    /**
     * Responds with a redirect.
     *
     * @param string $url
     *
     * @return void
     */
    public function redirect(string $url): void
    {
        $this->header('Location', $url);
        $this->statusCode = 301;
    }

    /**
     * Responds with a plain text response.
     *
     * @param mixed $content
     * @param int [$status = 200]
     *
     * @return void
     */
    public function plainText($content, int $status = 200): void
    {
        $this->header('Content-Type', 'text/plain');
        $this->statusCode = $status;
        $this->content = $content;
    }

    /**
     * Responds with a JSON response.
     *
     * @param array|object $data
     * @param int [$status = 200]
     *
     * @return void
     *
     * @throws libs\Http\Exceptions\InvalidJSON If provided data cannot be cast to JSON.
     */
    public function json($data, int $status = 200): void
    {
        $json = json_encode($data);

        if (!$json) {
            throw new Exception('Invalid JSON passed');
        }

        $this->header('Content-Type', 'application/json');
        $this->statusCode = $status;
        $this->content = $json;
    }

    /**
     * Responds with a var_dump output.
     *
     * @param mixed $variable
     * @param int [$status = 200]
     *
     * @return void
     */
    public function varDump($variable, int $status = 200): void
    {
        ob_start();
        var_dump($variable);

        $content = '<pre>' . ob_get_clean() . '</pre>';

        $this->html($content, $status);
    }

    /**
     * Responds with HTML code.
     *
     * @param string $html
     * @param int [$status = 200]
     *
     * @return void
     */
    public function html(string $html, int $status = 200): void
    {
        $this->header('Content-Type', 'text/html');
        $this->content = $html;
        $this->statusCode = $status;
    }

    /**
     * Sends response and ends request.
     *
     * @return void
     */
    public function ends(): void
    {
        foreach ($this->headers as $header => $value) {
            if (strtolower($header) === 'content-type' && $this->encoding) {
                $value = "{$value}; charset={$this->encoding}";
            }

            header("{$header}: {$value}");
        }

        if ($this->content !== null) {
            echo $this->content;
        }

        http_response_code($this->statusCode);

        die();
    }
}