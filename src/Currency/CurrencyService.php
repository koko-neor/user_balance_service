<?php


namespace app\src\Currency;

use Yii;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class CurrencyService
{
    private Client $httpClient;
    private string $baseUri;


    public function __construct()
    {
        $this->httpClient = new Client();
        $this->baseUri = $_ENV['EXCHANGE_RATE_HOST'] . '/v4/latest/';
    }

    /**
     * Получает данные о курсе валюты.
     *
     * @param string $baseCurrency Базовая валюта, относительно которой нужно получить курсы.
     * @return array|null Массив данных о курсах валют или null, если запрос не удался.
     * @throws Exception
     */
    private function fetchExchangeRates(string $baseCurrency): ?array
    {
        $response = $this->httpClient
            ->get($this->baseUri . $baseCurrency)
            ->setOptions(['timeout' => 3])
            ->send();

        if ($response->getIsOk()) {
            return $response->getData();
        }

        return null;
    }

    /**
     * Получает данные о курсе валюты.
     *
     * @param string $baseCurrency   Валюта, которую нужно конвертировать.
     * @param string $targetCurrency Валюта, в которую нужно конвертировать.
     * @return float|null Курс обмена между валютами или null, если запрос не удался.
     * @throws \Exception Если возникла ошибка при выполнении запроса.
     */
    public function getExchangeRate(string $baseCurrency, string $targetCurrency): ?float
    {
        try {
            $exchangeRates = $this->fetchExchangeRates($baseCurrency);

            if ($exchangeRates && isset($exchangeRates['rates'][$targetCurrency])) {
                return $exchangeRates['rates'][$targetCurrency];
            }

            return null;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}