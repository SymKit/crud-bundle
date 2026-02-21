<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Type\FormSectionType;

final class FormSectionTypeTest extends TestCase
{
    public function testGetParentReturnsFormType(): void
    {
        $type = new FormSectionType();
        self::assertSame(FormType::class, $type->getParent());
    }

    public function testConfigureOptionsSetDefaults(): void
    {
        $type = new FormSectionType();
        $resolver = new OptionsResolver();

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertNull($options['section']);
        self::assertNull($options['section_icon']);
        self::assertNull($options['section_description']);
        self::assertFalse($options['section_full_width']);
    }

    public function testConfigureOptionsWithCustomValues(): void
    {
        $type = new FormSectionType();
        $resolver = new OptionsResolver();

        $type->configureOptions($resolver);
        $options = $resolver->resolve([
            'section' => 'General',
            'section_icon' => 'heroicons:cog-20-solid',
            'section_description' => 'General settings',
            'section_full_width' => true,
        ]);

        self::assertSame('General', $options['section']);
        self::assertSame('heroicons:cog-20-solid', $options['section_icon']);
        self::assertSame('General settings', $options['section_description']);
        self::assertTrue($options['section_full_width']);
    }

    public function testBuildViewSetsVars(): void
    {
        $type = new FormSectionType();
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);

        $options = [
            'section' => 'Details',
            'section_icon' => 'heroicons:information-circle',
            'section_description' => 'Detail section',
            'section_full_width' => true,
        ];

        $type->buildView($view, $form, $options);

        self::assertSame('Details', $view->vars['section']);
        self::assertSame('heroicons:information-circle', $view->vars['section_icon']);
        self::assertSame('Detail section', $view->vars['section_description']);
        self::assertTrue($view->vars['section_full_width']);
    }
}
