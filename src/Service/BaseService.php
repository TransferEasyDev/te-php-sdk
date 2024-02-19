<?php
declare(strict_types=1);
namespace Transfereasy\Pay\Service;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\SignException;
use Transfereasy\Pay\Util\Ticket;

class BaseService
{
    protected array $config;
    protected $domain;

    public function __construct(array $config = [])
    {
        $this->domain = $config['domain'];
        $this->config = $config;

    }


    /**
     * @throws CustomerException|SignException
     */
    public function getSign(array $data, $timestamp): string
    {
        return Ticket::generateSignature($data, $this->config['m_private_key_path'], $timestamp);
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
     * @throws SignException|CustomerException
     */
    public function notify(string $params, string $signature, $timestamp):array
    {
        //验签
        if (!Ticket::getVerifyStr($params, $timestamp, $signature, $this->config['t_public_key_path'])) {
            throw new SignException();
        }

        return json_decode($params, true);
    }

}