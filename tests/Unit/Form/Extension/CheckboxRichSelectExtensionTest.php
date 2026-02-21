<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Extension;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symkit\CrudBundle\Form\Extension\CheckboxRichSelectExtension;

final class CheckboxRichSelectExtensionTest extends TestCase
{
    public function testGetExtendedTypesReturnsCheckboxType(): void
    {
        $types = CheckboxRichSelectExtension::getExtendedTypes();
        self::assertSame([CheckboxType::class], [...$types]);
    }

    public function testBuildViewAddsRichCheckboxBlockPrefix(): void
    {
        $extension = new CheckboxRichSelectExtension();
        $view = new FormView();
        $view->vars['block_prefixes'] = ['form', 'checkbox'];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, []);

        self::assertContains('rich_checkbox', $view->vars['block_prefixes']);
    }

    public function testBuildViewSetsChoicesData(): void
    {
        $extension = new CheckboxRichSelectExtension();
        $view = new FormView();
        $view->vars['block_prefixes'] = [];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, []);

        self::assertSame(['Yes' => 1, 'No' => 0], $view->vars['choices_data']);
    }

    public function testBuildViewSetsChoiceIcons(): void
    {
        $extension = new CheckboxRichSelectExtension();
        $view = new FormView();
        $view->vars['block_prefixes'] = [];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, []);

        self::assertArrayHasKey(1, $view->vars['choice_icons']);
        self::assertArrayHasKey(0, $view->vars['choice_icons']);
        self::assertArrayHasKey('name', $view->vars['choice_icons'][1]);
        self::assertArrayHasKey('class', $view->vars['choice_icons'][1]);
    }

    public function testBuildViewSetsSearchableAndPlaceholder(): void
    {
        $extension = new CheckboxRichSelectExtension();
        $view = new FormView();
        $view->vars['block_prefixes'] = [];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, []);

        self::assertFalse($view->vars['searchable']);
        self::assertSame('Select...', $view->vars['placeholder']);
    }
}
