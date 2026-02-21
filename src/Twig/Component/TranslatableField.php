<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Twig\Component;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('TranslatableField', template: '@SymkitCrud/components/TranslatableField.html.twig')]
final class TranslatableField
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public array $translations = [];

    #[LiveProp]
    public array $locales = ['fr', 'en'];

    #[LiveProp(writable: true)]
    public string $activeLocale = '';

    #[LiveProp]
    public string $name = '';

    #[LiveProp]
    public string $type = 'text'; // 'text' or 'textarea'

    #[LiveProp]
    public string $placeholder = '';

    public function mount(array $locales = ['fr', 'en'], mixed $translations = null, ?string $activeLocale = null): void
    {
        $this->locales = $locales;
        $this->translations = \is_array($translations) ? $translations : [];

        // Ensure all locales have a value (even empty)
        foreach ($this->locales as $locale) {
            if (!isset($this->translations[$locale])) {
                $this->translations[$locale] = '';
            }
        }

        $this->activeLocale = $activeLocale ?? ($this->locales[0] ?? 'en');
    }
}
