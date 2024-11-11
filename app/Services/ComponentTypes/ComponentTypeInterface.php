<?php

namespace App\Services\ComponentTypes;

use App\Models\DashboardComponent;

interface ComponentTypeInterface
{
  /**
   * Process data for the component type
   */
  public function processData(array $data, array $settings): array;

  /**
   * Get default settings for the component type
   */
  public function getDefaultSettings(): array;

  /**
   * Handle component setup
   */
  public function handleSetup(DashboardComponent $component): void;

  /**
   * Handle component update
   */
  public function handleUpdate(DashboardComponent $component): void;
}
