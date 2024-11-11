<?php

namespace App\ValueObjects;

class LatLng
{
  public function __construct(
    public readonly float $lat,
    public readonly float $lng
  ) {
    $this->validateCoordinates($lat, $lng);
  }

  /**
   * Create a new LatLng instance from an array
   */
  public static function fromArray(array $data): self
  {
    return new self(
      lat: (float) ($data['lat'] ?? 0),
      lng: (float) ($data['lng'] ?? 0)
    );
  }

  /**
   * Convert to array
   */
  public function toArray(): array
  {
    return [
      'lat' => $this->lat,
      'lng' => $this->lng
    ];
  }

  /**
   * Convert to JSON
   */
  public function toJson(): string
  {
    return json_encode($this->toArray());
  }

  /**
   * Validate coordinates
   */
  protected function validateCoordinates(float $lat, float $lng): void
  {
    if ($lat < -90 || $lat > 90) {
      throw new \InvalidArgumentException('Latitude must be between -90 and 90 degrees');
    }

    if ($lng < -180 || $lng > 180) {
      throw new \InvalidArgumentException('Longitude must be between -180 and 180 degrees');
    }
  }

  /**
   * Calculate distance to another point in kilometers
   */
  public function distanceTo(LatLng $point): float
  {
    $earthRadius = 6371; // Earth's radius in kilometers

    $latFrom = deg2rad($this->lat);
    $lonFrom = deg2rad($this->lng);
    $latTo = deg2rad($point->lat);
    $lonTo = deg2rad($point->lng);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

    return $angle * $earthRadius;
  }

  /**
   * Check if point is within given radius (in kilometers)
   */
  public function isWithinRadius(LatLng $center, float $radius): bool
  {
    return $this->distanceTo($center) <= $radius;
  }

  /**
   * Get a point at given distance and bearing from this point
   */
  public function destination(float $distance, float $bearing): self
  {
    $earthRadius = 6371; // Earth's radius in kilometers

    $bearingRad = deg2rad($bearing);
    $latRad = deg2rad($this->lat);
    $lonRad = deg2rad($this->lng);

    $distRatio = $distance / $earthRadius;
    $sinDist = sin($distRatio);
    $cosDist = cos($distRatio);

    $newLat = asin(sin($latRad) * $cosDist +
      cos($latRad) * $sinDist * cos($bearingRad));

    $newLon = $lonRad + atan2(
      sin($bearingRad) * $sinDist * cos($latRad),
      $cosDist - sin($latRad) * sin($newLat)
    );

    // Normalize longitude to -180 to +180 degrees
    $newLon = fmod(($newLon + 3 * M_PI), (2 * M_PI)) - M_PI;

    return new self(
      lat: rad2deg($newLat),
      lng: rad2deg($newLon)
    );
  }
}
