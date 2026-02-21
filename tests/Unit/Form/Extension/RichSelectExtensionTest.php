<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Extension;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Extension\RichSelectExtension;

final class RichSelectExtensionTest extends TestCase
{
    public function testGetExtendedTypesReturnsChoiceType(): void
    {
        $types = RichSelectExtension::getExtendedTypes();
        self::assertSame([ChoiceType::class], [...$types]);
    }

    public function testConfigureOptionsSetsDefaults(): void
    {
        $extension = new RichSelectExtension();
        $resolver = new OptionsResolver();
        $resolver->setDefined(['required']);
        $resolver->setDefault('required', false);

        $extension->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertTrue($options['searchable']);
        self::assertSame([], $options['choice_icons']);
    }

    public function testBuildViewSetsVars(): void
    {
        $extension = new RichSelectExtension();
        $view = new FormView();
        $view->vars['block_prefixes'] = ['form', 'choice'];
        $form = $this->createMock(FormInterface::class);

        $choiceIcons = ['red' => ['name' => 'heroicons:flag', 'class' => 'text-red-500']];

        $extension->buildView($view, $form, [
            'searchable' => false,
            'choice_icons' => $choiceIcons,
            'required' => true,
        ]);

        self::assertContains('rich_select', $view->vars['block_prefixes']);
        self::assertFalse($view->vars['searchable']);
        self::assertSame($choiceIcons, $view->vars['choice_icons']);
        self::assertTrue($view->vars['required']);
    }
}
