<?php

use app\src\Currency\CurrencyService;
use app\src\UserBalance\UserBalanceService;
use app\src\UserBalance\Repository\UserBalanceRepository;
use yii\di\Container;

$container = new Container();

$container->setSingleton(CurrencyService::class);
$container->set(UserBalanceRepository::class);
$container->set(UserBalanceService::class, function ($container) {
    return new UserBalanceService(
        $container->get(UserBalanceRepository::class),
        $container->get(CurrencyService::class)
    );
});

return $container;