<?php

namespace App\Providers;
use App\Http\Middleware\LocaleMiddleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  
  public function register()
  {
    //
  }

 
  public function boot()
  {
    
    //    return file_get_contents(base_path('resources/menu/verticalMenu.blade.php'));
    //    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    /*$availLocale = ['en' => 'en', 'ar' => 'ar'];
//      app()->setLocale("ar");
    if (session()->has('locale') && array_key_exists(session()->get('locale'), $availLocale)) {
      // Set the Laravel locale
      app()->setLocale(session()->get('locale'));
    }*/
    
//    dd(config('app.locale'));

//    dd(session()->get('locale'));
    
    $verticalMenuJson = include base_path("resources/menu/verticalMenu.blade.php");
    $verticalMenuData = json_decode($verticalMenuJson);
    $horizontalMenuJson = file_get_contents(base_path("resources/menu/horizontalMenu.json"));
    $horizontalMenuData = json_decode($horizontalMenuJson);
    
    // Share all menuData to all the views
    \View::share("menuData", [$verticalMenuData, $horizontalMenuData]);
    
  }
  public function provides(): array
  {
    return [RouteServiceProvider::class];
  }
}
