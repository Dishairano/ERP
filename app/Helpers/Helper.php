<?php

namespace App\Helpers;

class Helper
{
  public static function appClasses()
  {
    $data = [
      'theme' => 'theme-default',
      'isNavbarFixed' => true,
      'isFooterFixed' => false,
      'isMenuCollapsed' => false,
      'hasCustomizer' => true,
      'showDropdownOnHover' => true,
      'displayCustomizer' => true,
      'contentLayout' => 'compact',
      'headerType' => 'fixed',
      'navbarType' => 'fixed',
      'menuFixed' => true,
      'menuCollapsed' => false,
      'footerFixed' => false,
    ];

    return (object) $data;
  }

  public static function initMenu()
  {
    global $menuData;

    // Initialize menu data array
    $menuData = [];

    // Get all JSON files from the resources/menu directory
    $menuFiles = glob(resource_path('menu/*.json'));

    // Merge menu data from all JSON files
    foreach ($menuFiles as $file) {
      $menuContent = json_decode(file_get_contents($file), true);
      if (isset($menuContent['menu'])) {
        if (!isset($menuData['menu'])) {
          $menuData['menu'] = [];
        }
        $menuData['menu'] = array_merge($menuData['menu'], $menuContent['menu']);
      }
    }

    // If no menu data was found, provide a default structure
    if (empty($menuData)) {
      $menuData['menu'] = [
        [
          'menuHeader' => 'Main Menu'
        ],
        [
          'name' => 'Dashboard',
          'icon' => 'menu-icon tf-icons ri-dashboard-line',
          'slug' => 'dashboard',
          'url' => '/'
        ]
      ];
    }

    return $menuData;
  }
}
