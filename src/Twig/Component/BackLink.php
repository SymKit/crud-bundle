<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('BackLink', template: '@SymkitCrud/components/BackLink.html.twig')]
final class BackLink
{
    public string $route;
    public array $routeParams = [];
    public string $label = 'Back';
    public string $icon = 'heroicons:arrow-left-20-solid';
}
