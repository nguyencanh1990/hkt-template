<?php

namespace App\Providers;

use App\Macros\StrMixins;
use Illuminate\Support\ServiceProvider;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\Impl\BaseRepository;
use App\Repositories\Impl\UserRepository;
use App\Services\UserService;
use App\Services\BaseService;
use App\Services\Impl\BaseServiceImpl;
use App\Services\Impl\UserServiceImpl;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindings($this->registerRepositories());
        $this->bindings($this->registerServices());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindingMacros($this->registerMacros());
    }

    /**
     * Register services for binding
     *
     * @return string[]
     */
    private function registerServices(): array
    {
        return [
            BaseService::class => BaseServiceImpl::class,
            UserService::class => UserServiceImpl::class,
        ];
    }

    /**
     * Register repositories for binding
     *
     * @return string[]
     */
    private function registerRepositories(): array
    {
        return [
            BaseRepositoryInterface::class => BaseRepository::class,
            UserRepositoryInterface::class => UserRepository::class,
        ];
    }

    /**
     * Loop all register to binding
     *
     * @param  array  $classes
     */
    private function bindings(array $classes)
    {
        foreach ($classes as $interface => $implement) {
            $this->app->bind($interface, $implement);
        }
    }

    private function registerMacros(): array
    {
        return [
            Str::class => [StrMixins::class]
        ];
    }

    /**
     * Binding macros' helper utils
     * @param array $classes
     */
    private function bindingMacros(array $classes)
    {
        foreach ($classes as $interface => $implements) {
            $interfaceClass = app($interface);
            foreach ($implements as $implementClass) {
                $interfaceClass::mixin(new $implementClass());
            }
        }
    }
}
