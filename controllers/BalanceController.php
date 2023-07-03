<?php

namespace app\controllers;

use app\src\UserBalance\Dto\UserBalanceTransferDto;
use app\src\UserBalance\Dto\UserBalanceDto;
use app\src\UserBalance\Form\UserBalanceForm;
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = [
            'message' => $message,
        ];
        Yii::$app->response->setStatusCode($status);
        return Yii::$app->response;
    }

    public function actionIndex(int $userId): Response
    {
        try {
            $result = $this->service->getBalance($userId);
            return $this->setResponse($result->getMessage());
        } catch (\Exception $exception) {
            return $this->setResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function actionIncrease(): Response
    {
        try {
            $request = new UserBalanceForm();
            $request->load(Yii::$app->request->post(), '');

            if (!$request->validate()) {
                return $this->setResponse($request->errors, 422);
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