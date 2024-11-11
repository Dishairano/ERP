<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class MenuServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public function boot()
  {
    // Share menu data with all views
    View::composer('*', function ($view) {
      $view->with('menuData', $this->loadMenu());
    });
  }

  protected function loadMenu()
  {
    $mainMenu = json_decode(file_get_contents(resource_path('menu/verticalMenu.json')), true);
    $menu = ['menu' => []];

    foreach ($mainMenu['menu'] as $item) {
      if (isset($item['include'])) {
        $includedMenu = json_decode(file_get_contents(resource_path('menu/' . $item['include'])), true);
        $menu['menu'] = array_merge($menu['menu'], $includedMenu['menu']);
      } else {
        $menu['menu'][] = $item;
      }
    }

    return $menu;
  }
}
