<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Type\SitemapPriorityType;

final class SitemapPriorityTypeTest extends TestCase
{
    public function testGetParentReturnsChoiceType(): void
    {
        $type = new SitemapPriorityType();
        self::assertSame(ChoiceType::class, $type->getParent());
    }

    public function testConfigureOptionsSetsDefaults(): void
    {
        $type = new SitemapPriorityType();
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'label' => null,
            'choices' => [],
            'searchable' => true,
            'placeholder' => null,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertSame('Sitemap Priority', $options['label']);
        self::assertFalse($options['searchable']);
        self::assertSame('Default priority (0.5)', $options['placeholder']);
    }

    public function testPriorityChoicesContainTenValues(): void
    {
        $type = new SitemapPriorityType();
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'label' => null,
            'choices' => [],
            'searchable' => true,
            'placeholder' => null,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertCount(10, $options['choices']);
    }

    public function testPriorityChoicesRangeFromOneToZeroPointOne(): void
    {
        $type = new SitemapPriorityType();
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'label' => null,
            'choices' => [],
            'searchable' => true,
            'placeholder' => null,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        $values = array_values($options['choices']);
        self::assertSame(1.0, $values[0]);
        self::assertSame(0.1, $values[\count($values) - 1]);
    }

    public function testPriorityChoiceLabelsAreFormatted(): void
    {
        $type = new SitemapPriorityType();
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'label' => null,
            'choices' => [],
            'searchable' => true,
            'placeholder' => null,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        $labels = array_keys($options['choices']);
        self::assertSame('1.0', $labels[0]);
        self::assertSame('0.5', $labels[5]);
    }
}
