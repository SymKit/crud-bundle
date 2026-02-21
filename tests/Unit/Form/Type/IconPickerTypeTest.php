<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Form\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\CrudBundle\Form\Type\IconPickerType;
use Symkit\CrudBundle\Service\HeroiconProvider;

final class IconPickerTypeTest extends TestCase
{
    public function testGetParentReturnsChoiceType(): void
    {
        $provider = new HeroiconProvider();
        $type = new IconPickerType($provider);
        self::assertSame(ChoiceType::class, $type->getParent());
    }

    public function testConfigureOptionsPopulatesChoicesFromProvider(): void
    {
        $provider = new HeroiconProvider();
        $type = new IconPickerType($provider);

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'choices' => [],
            'choice_icons' => [],
            'placeholder' => null,
            'searchable' => false,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertNotEmpty($options['choices']);
        self::assertNotEmpty($options['choice_icons']);
        self::assertSame('Select an icon...', $options['placeholder']);
        self::assertTrue($options['searchable']);
    }

    public function testChoicesAreFlippedIcons(): void
    {
        $provider = new HeroiconProvider();
        $type = new IconPickerType($provider);

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'choices' => [],
            'choice_icons' => [],
            'placeholder' => null,
            'searchable' => false,
        ]);

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        // Choices should map label => icon key
        $icons = $provider->getAllIcons();
        $flipped = array_flip($icons);

        self::assertSame($flipped, $options['choices']);
    }
}
