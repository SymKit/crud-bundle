<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SitemapPriorityType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Sitemap Priority',
            'choices' => $this->getPriorityChoices(),
            'searchable' => false,
            'placeholder' => 'Default priority (0.5)',
        ]);
    }

    private function getPriorityChoices(): array
    {
        $choices = [];

        for ($i = 10; $i >= 1; --$i) {
            $val = $i / 10;
            $label = number_format($val, 1);
            $choices[$label] = (float) $val;
        }

        return $choices;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
