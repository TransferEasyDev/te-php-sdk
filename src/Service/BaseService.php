<?php
declare(strict_types=1);
namespace Transfereasy\Pay\Service;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Transfereasy\Pay\Exception\SignException;

class BaseService
{
    protected $request_header;
    protected $domain;
    public function __construct(array $config = [])
    {
        $domain = $config['domain'];
        $request_header = Config::load($config);
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
     * @param string $params 传入TE响应的body string信息即可
     * @return void
     * @throws SignException
     */
    public function notify(string $params):array
    {
        //验签
        if (!Ticket::verify($params)) {
            throw new SignException();
        }
        return json_decode($params, true);
    }

}