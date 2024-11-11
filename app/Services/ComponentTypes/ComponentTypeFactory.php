<?php

namespace App\Services\ComponentTypes;

use InvalidArgumentException;

class ComponentTypeFactory
{
  private array $types = [];

  public function __construct()
  {
    $this->registerDefaultTypes();
  }

  public function register(string $type, string $class): void
  {
    if (!class_exists($class)) {
      throw new InvalidArgumentException("Class {$class} does not exist");
    }

    if (!is_subclass_of($class, ComponentTypeInterface::class)) {
      throw new InvalidArgumentException("Class {$class} must implement ComponentTypeInterface");
    }

    $this->types[$type] = $class;
  }

  public function create(string $type): ComponentTypeInterface
  {
    if (!isset($this->types[$type])) {
      throw new InvalidArgumentException("Unsupported component type: {$type}");
    }

    $class = $this->types[$type];
    return new $class();
  }

  private function registerDefaultTypes(): void
  {
    $this->types = [
      'chart' => ChartComponentType::class,
      // Register other component types as they are created
      // 'table' => TableComponentType::class,
      // 'metric' => MetricComponentType::class,
      // 'list' => ListComponentType::class,
      // 'calendar' => CalendarComponentType::class,
      // 'map' => MapComponentType::class,
    ];
  }

  public function getAvailableTypes(): array
  {
    return array_keys($this->types);
  }
}
