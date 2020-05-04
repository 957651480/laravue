<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class ArrMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->microGetInt();
        $this->microGetString();
        $this->microGetStringTrim();
        $this->microGetStringAddSlashes();
        $this->microGetStringTrimAddSlashes();
    }

    protected function microGetInt()
    {
        Arr::macro('getInt',function ($array, $key, $default = 0){
            $value = Arr::get($array, $key);
            return $value?(integer)$value:$default;
        });
    }

    protected function microGetString()
    {
        Arr::macro('getString',function ($array, $key, $default = ''){
            $value = Arr::get($array, $key);
            return $value?(string)$value:$default;
        });
    }

    protected function microGetStringTrim()
    {
        Arr::macro('getStringTrim',function ($array, $key, $default = ''){
            $value = Arr::getString($array, $key);
            return $value?trim($value):$default;
        });
    }

    protected function microGetStringAddSlashes()
    {
        Arr::macro('getStringAddSlashes',function ($array, $key, $default = ''){
            $value = Arr::getString($array, $key);
            return $value?addslashes($value):$default;
        });
    }

    protected function microGetStringTrimAddSlashes()
    {
        Arr::macro('getStringTrimAddSlashes',function ($array, $key, $default = ''){
            $value = Arr::getStringTrim($array, $key);
            return $value?addslashes($value):$default;
        });
    }
}
