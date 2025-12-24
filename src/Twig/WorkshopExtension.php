<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WorkshopExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('capacity_badge_class', [$this, 'getCapacityBadgeClass']),
        ];
    }

    /**
     * Get the Bootstrap badge class based on workshop capacity
     * 
     * @param int $currentCount Current number of registered participants
     * @param int $maxCapacity Maximum capacity of the workshop
     * @return string Bootstrap badge class (bg-success, bg-warning, or bg-danger)
     */
    public function getCapacityBadgeClass(int $currentCount, int $maxCapacity): string
    {
        if ($currentCount >= $maxCapacity) {
            return 'bg-danger';
        }
        
        $fillPercentage = ($currentCount / $maxCapacity);
        
        if ($fillPercentage >= 0.8) {
            return 'bg-warning';
        }
        
        return 'bg-success';
    }
}
