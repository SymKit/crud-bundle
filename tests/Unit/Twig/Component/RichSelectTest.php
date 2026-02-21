<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Twig\Component;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Twig\Component\RichSelect;

final class RichSelectTest extends TestCase
{
    public function testGetFilteredChoicesReturnsAllWhenNoSearchQuery(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana'];
        $component->searchQuery = '';

        self::assertSame(['Apple' => 'apple', 'Banana' => 'banana'], $component->getFilteredChoices());
    }

    public function testGetFilteredChoicesReturnsAllWhenNotSearchable(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana'];
        $component->searchable = false;
        $component->searchQuery = 'apple';

        self::assertSame(['Apple' => 'apple', 'Banana' => 'banana'], $component->getFilteredChoices());
    }

    public function testGetFilteredChoicesFiltersBySearchQuery(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana', 'Apricot' => 'apricot'];
        $component->searchQuery = 'ap';

        self::assertSame(['Apple' => 'apple', 'Apricot' => 'apricot'], $component->getFilteredChoices());
    }

    public function testGetFilteredChoicesIsCaseInsensitive(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana'];
        $component->searchQuery = 'APPLE';

        self::assertSame(['Apple' => 'apple'], $component->getFilteredChoices());
    }

    public function testGetFilteredChoicesReturnsEmptyWhenNoMatch(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana'];
        $component->searchQuery = 'cherry';

        self::assertSame([], $component->getFilteredChoices());
    }

    public function testGetSelectedLabelReturnsNullWhenValueIsNull(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple'];
        $component->value = null;

        self::assertNull($component->getSelectedLabel());
    }

    public function testGetSelectedLabelReturnsMatchingLabel(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana'];
        $component->value = 'banana';

        self::assertSame('Banana', $component->getSelectedLabel());
    }

    public function testGetSelectedLabelReturnsNullWhenNoMatch(): void
    {
        $component = new RichSelect();
        $component->choices = ['Apple' => 'apple', 'Banana' => 'banana'];
        $component->value = 'cherry';

        self::assertNull($component->getSelectedLabel());
    }

    public function testGetSelectedLabelComparesAsStrings(): void
    {
        $component = new RichSelect();
        $component->choices = ['Option 1' => 1, 'Option 2' => 2];
        $component->value = '1';

        self::assertSame('Option 1', $component->getSelectedLabel());
    }

    public function testDefaultPropertyValues(): void
    {
        $component = new RichSelect();

        self::assertSame('', $component->searchQuery);
        self::assertSame([], $component->choices);
        self::assertNull($component->value);
        self::assertTrue($component->searchable);
        self::assertSame('', $component->name);
        self::assertSame('Select an option...', $component->placeholder);
        self::assertFalse($component->required);
        self::assertSame([], $component->choiceIcons);
    }
}
