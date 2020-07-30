<?php


return [
    \App\Repositories\Contracts\IDrinkRepository::class => DI\autowire(App\Repositories\DrinkRepository::class),
    \App\Services\Contracts\IDrinkService::class => DI\autowire(App\Services\DrinkService::class),
    \App\Services\Contracts\IAuthService::class => DI\create(App\Services\AuthService::class),
];