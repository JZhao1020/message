<?php


namespace message;


use message\Log;

class Message{
    private $config;

    private $gateways;

    /**
     * Pay constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 指定操作网关
     * @param string $gateway
     * @param string $lang
     * @return GatewayInterface
     */
    public function gateway($gateway = 'sms', $lang = 'cn')
    {
        return $this->gateways = $this->createGateway($gateway, $lang);
    }

    /**
     * 创建操作网关
     * @param string $gateway
     * @return mixed
     */
    protected function createGateway($gateway, $lang)
    {
        if (!file_exists(__DIR__ . '/driver/'. ucfirst($gateway) . 'Send.php')) {
            throw new \Exception("Gateway [$gateway] is not supported.");
        }
        $gateway_class = __NAMESPACE__ . '\\driver\\' . ucfirst($gateway) . 'Send';

        $log = new Log();
        if(!$this->config){
            $file = __DIR__ . '/config/'. ucfirst($gateway) . 'Config.php';
            if (!file_exists($file)) {
                throw new \Exception("config [$gateway] is not supported.");
            }

            $this->config = include $file;
        }
        return new $gateway_class($this->config, $lang, $log);
    }
}