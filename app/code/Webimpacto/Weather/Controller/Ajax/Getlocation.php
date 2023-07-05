<?php
namespace Webimpacto\Weather\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Request\Http;

class Getlocation extends Action
{
    protected $jsonResultFactory;

    protected $request;

    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        Http $request,
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
        $response = $this->jsonResultFactory->create();

        $latitude = $this->request->getParam('latitude');
        $longitude = $this->request->getParam('longitude');

        
        if ($latitude && $longitude) {

            $apiKey = 'd9316ab57a7a8a9570801647ca187557';
            $url = "http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$apiKey}&units=metric";
            $weatherData = json_decode(file_get_contents($url));


            if ($weatherData && $weatherData->cod === 200) {

                $temperature = round($weatherData->main->temp, 0, PHP_ROUND_HALF_UP);
                $humidity = $weatherData->main->humidity;

                // Devolver los datos del clima
                return $response->setData([
                    'status' => 'ok',
                    'temperature' => $temperature,
                    'humidity' => $humidity
                ]);
            }
        }

        return $response->setData([
            'response' => 'error'
        ]);
    }
}