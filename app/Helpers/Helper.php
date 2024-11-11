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
}
