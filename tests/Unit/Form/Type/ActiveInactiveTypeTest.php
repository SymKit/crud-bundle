<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Type\ActiveInactiveType;

final class ActiveInactiveTypeTest extends TestCase
{
    public function testGetParentReturnsChoiceType(): void
    {
        $type = new ActiveInactiveType();
        self::assertSame(ChoiceType::class, $type->getParent());
    }

    public function testConfigureOptionsSetDefaults(): void
    {
        $type = new ActiveInactiveType();
        $resolver = new OptionsResolver();

        // Set defaults that ChoiceType would normally set
        $resolver->setDefaults([
            'label' => null,
            'choices' => [],
            'choice_icons' => [],
            'searchable' => true,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertSame('Status', $options['label']);
        self::assertSame(['Active' => true, 'Inactive' => false], $options['choices']);
        self::assertFalse($options['searchable']);
        self::assertArrayHasKey(true, $options['choice_icons']);
        self::assertArrayHasKey(false, $options['choice_icons']);
    }

    public function testChoiceIconsHaveCorrectStructure(): void
    {
        $type = new ActiveInactiveType();
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'label' => null,
            'choices' => [],
            'choice_icons' => [],
            'searchable' => true,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertArrayHasKey('name', $options['choice_icons'][true]);
        self::assertArrayHasKey('class', $options['choice_icons'][true]);
        self::assertStringContainsString('check', $options['choice_icons'][true]['name']);
        self::assertStringContainsString('green', $options['choice_icons'][true]['class']);

        self::assertArrayHasKey('name', $options['choice_icons'][false]);
        self::assertArrayHasKey('class', $options['choice_icons'][false]);
        self::assertStringContainsString('x-mark', $options['choice_icons'][false]['name']);
        self::assertStringContainsString('red', $options['choice_icons'][false]['class']);
    }
}
