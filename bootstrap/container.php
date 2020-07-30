<?php

return [
    App\Repositories\Contracts\IDrinkRepository::class => DI\autowire(App\Repositories\DrinkRepository::class),
    App\Repositories\Contracts\IUserRepository::class => DI\autowire(App\Repositories\UserRepository::class),
    App\Services\Contracts\IAuthService::class => DI\create(App\Services\AuthService::class),
    App\Services\Contracts\IDrinkService::class => DI\autowire(App\Services\DrinkService::class),
    App\Services\Contracts\IUserService::class => DI\autowire(App\Services\UserService::class),
    App\Resources\UserResource::class => DI\autowire()
];
