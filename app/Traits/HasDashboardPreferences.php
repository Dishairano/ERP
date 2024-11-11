<?php

namespace App\Traits;

trait HasDashboardPreferences
{
  public function getDashboardPreferences(): array
  {
    // For now, return default preferences
    // In a real implementation, this would likely be stored in the database
    return [
      'theme' => 'light',
      'menuCollapsed' => false,
      'layout' => 'vertical',
      'style' => 'default'
    ];
  }

  public function setDashboardPreferences(array $preferences): bool
  {
    // TODO: Implement saving preferences to database
    // For now, just return true
    return true;
  }
}
