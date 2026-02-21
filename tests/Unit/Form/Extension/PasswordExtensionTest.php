<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Extension;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Extension\PasswordExtension;

final class PasswordExtensionTest extends TestCase
{
    public function testGetExtendedTypesReturnsPasswordType(): void
    {
        $types = PasswordExtension::getExtendedTypes();
        self::assertSame([PasswordType::class], [...$types]);
    }

    public function testConfigureOptionsSetsDefaults(): void
    {
        $extension = new PasswordExtension();
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertTrue($options['show_strength']);
        self::assertSame(8, $options['min_length']);
        self::assertTrue($options['require_uppercase']);
        self::assertTrue($options['require_numbers']);
        self::assertTrue($options['require_special']);
    }

    public function testConfigureOptionsAllowsCustomValues(): void
    {
        $extension = new PasswordExtension();
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve([
            'show_strength' => false,
            'min_length' => 12,
            'require_uppercase' => false,
            'require_numbers' => false,
            'require_special' => false,
        ]);

        self::assertFalse($options['show_strength']);
        self::assertSame(12, $options['min_length']);
        self::assertFalse($options['require_uppercase']);
        self::assertFalse($options['require_numbers']);
        self::assertFalse($options['require_special']);
    }

    public function testBuildViewSetsVars(): void
    {
        $extension = new PasswordExtension();
        $view = new FormView();
        $view->vars['block_prefixes'] = ['form', 'password'];
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, [
            'show_strength' => true,
            'min_length' => 10,
            'require_uppercase' => true,
            'require_numbers' => false,
            'require_special' => true,
        ]);

        self::assertContains('password_premium', $view->vars['block_prefixes']);
        self::assertTrue($view->vars['show_strength']);
        self::assertSame(10, $view->vars['min_length']);
        self::assertTrue($view->vars['require_uppercase']);
        self::assertFalse($view->vars['require_numbers']);
        self::assertTrue($view->vars['require_special']);
    }
}
