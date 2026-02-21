<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Extension;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Extension\UrlExtension;

final class UrlExtensionTest extends TestCase
{
    public function testGetExtendedTypesReturnsUrlType(): void
    {
        $types = UrlExtension::getExtendedTypes();
        self::assertSame([UrlType::class], [...$types]);
    }

    public function testConfigureOptionsSetsDefaultLinkButtonToTrue(): void
    {
        $extension = new UrlExtension();
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertTrue($options['link_button']);
    }

    public function testConfigureOptionsAllowsDisablingLinkButton(): void
    {
        $extension = new UrlExtension();
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve(['link_button' => false]);

        self::assertFalse($options['link_button']);
    }

    public function testBuildViewSetsLinkButtonVar(): void
    {
        $extension = new UrlExtension();
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, ['link_button' => true]);

        self::assertTrue($view->vars['link_button']);
    }

    public function testBuildViewSetsLinkButtonVarToFalse(): void
    {
        $extension = new UrlExtension();
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);

        $extension->buildView($view, $form, ['link_button' => false]);

        self::assertFalse($view->vars['link_button']);
    }
}
