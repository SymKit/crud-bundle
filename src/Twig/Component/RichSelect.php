<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Twig\Component;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('RichSelect', template: '@SymkitCrud/components/RichSelect.html.twig')]
final class RichSelect
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $searchQuery = '';

    #[LiveProp]
    public array $choices = [];

    #[LiveProp(writable: true)]
    public ?string $value = null;

    #[LiveProp]
    public bool $searchable = true;

    #[LiveProp]
    public string $name = '';

    #[LiveProp]
    public string $placeholder = 'Select an option...';

    #[LiveProp]
    public bool $required = false;

    #[LiveProp]
    public array $choiceIcons = [];

    public function getFilteredChoices(): array
    {
        if (!$this->searchable || '' === $this->searchQuery) {
            return $this->choices;
        }

        $filtered = [];
        $query = mb_strtolower($this->searchQuery);

        foreach ($this->choices as $label => $value) {
            if (false !== mb_strpos(mb_strtolower((string) $label), $query)) {
                $filtered[$label] = $value;
            }
        }

        return $filtered;
    }

    public function getSelectedLabel(): ?string
    {
        if (null === $this->value) {
            return null;
        }

        foreach ($this->choices as $label => $val) {
            if ((string) $val === (string) $this->value) {
                return (string) $label;
            }
        }

        return null;
    }
}
