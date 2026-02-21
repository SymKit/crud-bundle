<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('DeleteForm', template: '@SymkitCrud/components/DeleteForm.html.twig')]
final class DeleteForm
{
    public string $route;
    public array $routeParams = [];
    public string|int $entityId;
    public string $formId = 'delete-form';
    public string $confirmMessage = 'Are you sure you want to delete this item?';
    public string $csrfTokenId = 'delete';

    public function getCsrfTokenName(): string
    {
        return $this->csrfTokenId.$this->entityId;
    }
}
