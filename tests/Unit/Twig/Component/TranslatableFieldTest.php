<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Twig\Component;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Twig\Component\TranslatableField;

final class TranslatableFieldTest extends TestCase
{
    public function testMountInitializesAllLocalesWithEmptyValues(): void
    {
        $component = new TranslatableField();
        $component->mount(['fr', 'en', 'de']);

        self::assertSame(['fr' => '', 'en' => '', 'de' => ''], $component->translations);
        self::assertSame(['fr', 'en', 'de'], $component->locales);
    }

    public function testMountPreservesExistingTranslations(): void
    {
        $component = new TranslatableField();
        $component->mount(['fr', 'en'], ['fr' => 'Bonjour', 'en' => 'Hello']);

        self::assertSame(['fr' => 'Bonjour', 'en' => 'Hello'], $component->translations);
    }

    public function testMountFillsMissingLocalesWithEmptyStrings(): void
    {
        $component = new TranslatableField();
        $component->mount(['fr', 'en', 'de'], ['fr' => 'Bonjour']);

        self::assertSame('Bonjour', $component->translations['fr']);
        self::assertSame('', $component->translations['en']);
        self::assertSame('', $component->translations['de']);
    }

    public function testMountSetsActiveLocaleFromParameter(): void
    {
        $component = new TranslatableField();
        $component->mount(['fr', 'en'], null, 'en');

        self::assertSame('en', $component->activeLocale);
    }

    public function testMountDefaultsActiveLocaleToFirstLocale(): void
    {
        $component = new TranslatableField();
        $component->mount(['de', 'fr', 'en']);

        self::assertSame('de', $component->activeLocale);
    }

    public function testMountWithNonArrayTranslationsUsesEmptyArray(): void
    {
        $component = new TranslatableField();
        $component->mount(['fr', 'en'], 'not-an-array');

        self::assertSame(['fr' => '', 'en' => ''], $component->translations);
    }

    public function testMountWithNullTranslationsUsesEmptyArray(): void
    {
        $component = new TranslatableField();
        $component->mount(['fr', 'en'], null);

        self::assertSame(['fr' => '', 'en' => ''], $component->translations);
    }

    public function testMountWithEmptyLocalesFallsBackToEnForActiveLocale(): void
    {
        $component = new TranslatableField();
        $component->mount([]);

        self::assertSame('en', $component->activeLocale);
    }

    public function testDefaultPropertyValues(): void
    {
        $component = new TranslatableField();

        self::assertSame([], $component->translations);
        self::assertSame(['fr', 'en'], $component->locales);
        self::assertSame('', $component->activeLocale);
        self::assertSame('', $component->name);
        self::assertSame('text', $component->type);
        self::assertSame('', $component->placeholder);
    }
}
