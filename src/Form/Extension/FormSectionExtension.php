<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FormSectionExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['section'] = $options['section'];
        $view->vars['section_icon'] = $options['section_icon'];
        $view->vars['section_description'] = $options['section_description'];
        $view->vars['section_full_width'] = $options['section_full_width'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'section' => null,
            'section_icon' => null,
            'section_description' => null,
            'section_full_width' => false,
        ]);

        $resolver->setAllowedTypes('section', ['null', 'string']);
        $resolver->setAllowedTypes('section_icon', ['null', 'string']);
        $resolver->setAllowedTypes('section_description', ['null', 'string']);
        $resolver->setAllowedTypes('section_full_width', ['bool']);
    }
}
