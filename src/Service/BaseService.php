<?php
declare(strict_types=1);
namespace Transfereasy\Pay\Service;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Transfereasy\Pay\Exception\SignException;

class BaseService
{
    protected $config;
    protected $domain;

    public function __construct(array $config = [])
    {
        $this->domain = $config['domain'];
        $this->config = $config;
    }


    public function success():ResponseInterface
    {
        return new Response(200, [], 'success');
    }

    public function fail(int $code, string $fail_message):ResponseInterface
    {
        $fail_array = [
            'code' => 'FAIL',
            'message' => $fail_message
        ];
        return new Response($code, [], json_encode($fail_array));
    }

    /**
     * 异步通知
     * @param string $params 传入TE响应的body
     * @param string $signature 传入
     * @return void
     * @throws SignException
     */
    public function notify(string $params, string $signature, $timestamp):array
    {
        //验签
        if (base64_decode($signature) != Ticket::getVerifyStr($params, $timestamp, $this->config['t_public_key_path'])) {
            throw new SignException();
        }

        return json_decode($params, true);
    }

}