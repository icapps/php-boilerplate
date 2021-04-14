<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_env', [$this, 'getEnvironmentVariable']),
        ];
    }

    /**
     * Return the value of the requested environment variable.
     *
     * @param String $varName
     * @return String
     */
    public function getEnvironmentVariable(string $varName): string
    {
        return $_ENV[$varName];
    }
}
