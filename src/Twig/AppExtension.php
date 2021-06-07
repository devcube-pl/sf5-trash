<?php

namespace App\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter(
                'format_post_date',
                [$this, 'formatPostDate'],
                ['needs_environment' => true]
            )
        ];
    }

    public function formatPostDate(
        Environment $env,
        \DateTime $date,
        string $format = 'd.m.Y H:i'
    ) {
        // Przyklad odwolania sie do wbudowanego filtra upper
        return twig_upper_filter(
            $env,
            $date->format($format)
        );
    }
}
