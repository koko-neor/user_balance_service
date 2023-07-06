<?php

namespace app\controllers;

use app\src\UserBalance\Dto\ResultDto;
use app\src\UserBalance\Dto\UserBalanceTransferDto;
use app\src\UserBalance\Dto\UserBalanceDto;
use app\src\UserBalance\Form\UserBalanceForm;
use app\src\UserBalance\Form\UserBalanceGetForm;
use app\src\UserBalance\Form\UserBalanceTransferForm;
use app\src\UserBalance\UserBalanceService;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class BalanceController extends Controller
{
    public $enableCsrfValidation = false;

    private UserBalanceService $service;

    public function __construct($id, $module, UserBalanceService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                    'increase' => ['POST'],
                    'decrease' => ['POST'],
                    'transfer' => ['POST'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'cors' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],  // На проекте необходимо указать конкретные домены или источники
                    'Access-Control-Request-Method' => ['GET', 'POST'], // Разрешить только GET и POST методы
                    'Access-Control-Request-Headers' => ['*'], // Разрешить все заголовки
                ],
            ],
        ];
    }

    protected function setResponse($message, $status = 400)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        if (is_array($message)) {
            $errors = [];
            foreach ($message as $attribute => $errorMessages) {
                $errors[$attribute] = $errorMessages[0]; // Берем только первое сообщение об ошибке
            }
            $message = $errors;
        }

        $response->data = [
            'message' => $message
        ];
        $response->setStatusCode($status);

        return $response;
    }

    public function actionIndex(): Response
    {
        try {
            $request = new UserBalanceGetForm();
            $request->load(Yii::$app->request->get(), '');

            if (!$request->validate()) {
                return $this->setResponse($request->getErrors(), 422);
            }

            $result = $this->service->getBalance($request->userId, $request->currency);
            return $this->setResponse($result->getMessage());
        } catch (\Exception $exception) {
            return $this->setResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function actionIncrease()
    {
        try {
            $request = new UserBalanceForm();
            $request->load(Yii::$app->request->post(), '');

            if (!$request->validate()) {
                return $this->setResponse($request->getErrors(), 422);
            }

            $result = $this->service->handleIncreaseBalance(
                UserBalanceDto::formUpdate($request)
            );

            return $this->setResponse($result->getMessage(), $result->getStatus());

        } catch (\Exception $exception) {
            return $this->setResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function actionDecrease(): Response
    {
        try {
            $request = new UserBalanceForm();
            $request->load(Yii::$app->request->post(), '');

            if (!$request->validate()) {
                return $this->setResponse($request->errors, 422);
            }

            $result = $this->service->handleDecreaseBalance(
                UserBalanceDto::formUpdate($request)
            );

            return $this->setResponse($result->getMessage(), $result->getStatus());

        } catch (\Exception $exception) {
            return $this->setResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function actionTransfer(): Response
    {
        try {
            $request = new UserBalanceTransferForm();
            $request->load(Yii::$app->request->post(), '');

            if (!$request->validate()) {
                return $this->setResponse($request->errors, 422);
            }

            $result = $this->service->handleTransferBalance(
                UserBalanceTransferDto::formUpdate($request)
            );

            return $this->setResponse($result->getMessage(), $result->getStatus());

        } catch (\Exception $exception) {
            return $this->setResponse($exception->getMessage(), $exception->getCode());
        }
    }
}