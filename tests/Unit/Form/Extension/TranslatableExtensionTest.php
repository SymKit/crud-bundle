<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Extension;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Extension\TranslatableExtension;

final class TranslatableExtensionTest extends TestCase
{
    public function testGetExtendedTypesReturnsTextAndTextareaTypes(): void
    {
        $types = TranslatableExtension::getExtendedTypes();
        self::assertSame([TextType::class, TextareaType::class], [...$types]);
    }

    public function testConfigureOptionsSetsDefaults(): void
    {
        $extension = new TranslatableExtension('en', ['en', 'fr']);
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertFalse($options['translatable']);
        self::assertSame(['en', 'fr'], array_values($options['locales']));
    }

    public function testConfigureOptionsMergesDefaultLocaleIntoEnabledLocales(): void
    {
        $extension = new TranslatableExtension('de', ['en', 'fr']);
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertContains('de', $options['locales']);
        self::assertContains('en', $options['locales']);
        self::assertContains('fr', $options['locales']);
    }

    public function testConfigureOptionsDeduplicatesLocales(): void
    {
        $extension = new TranslatableExtension('en', ['en', 'fr']);
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve();

        // 'en' should not appear twice
        self::assertCount(2, $options['locales']);
    }

    public function testBuildViewDoesNothingWhenTranslatableIsFalse(): void
    {
        $extension = new TranslatableExtension('en', ['en', 'fr']);
        $view = new FormView();
        $view->vars['block_prefixes'] = ['form', 'text'];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, [
            'translatable' => false,
            'locales' => ['en', 'fr'],
        ]);

        self::assertNotContains('translatable_field', $view->vars['block_prefixes']);
        self::assertArrayNotHasKey('translatable', $view->vars);
    }

    public function testBuildViewAddsTranslatableFieldBlockPrefix(): void
    {
        $extension = new TranslatableExtension('en', ['en', 'fr']);
        $view = new FormView();
        $view->vars['block_prefixes'] = ['form', 'text'];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, [
            'translatable' => true,
            'locales' => ['en', 'fr'],
        ]);

        self::assertContains('translatable_field', $view->vars['block_prefixes']);
        self::assertTrue($view->vars['translatable']);
        self::assertSame(['en', 'fr'], $view->vars['locales']);
    }

    public function testBuildViewDoesNotDuplicateBlockPrefix(): void
    {
        $extension = new TranslatableExtension('en', ['en', 'fr']);
        $view = new FormView();
        $view->vars['block_prefixes'] = ['form', 'text', 'translatable_field'];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, [
            'translatable' => true,
            'locales' => ['en', 'fr'],
        ]);

        $count = array_count_values($view->vars['block_prefixes'])['translatable_field'];
        self::assertSame(1, $count);
    }
}
