<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Service\HeroiconProvider;

final class HeroiconProviderTest extends TestCase
{
    private HeroiconProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new HeroiconProvider();
    }

    public function testGetAllIconsDefaultStyleReturns20Solid(): void
    {
        $icons = $this->provider->getAllIcons();

        self::assertNotEmpty($icons);

        foreach ($icons as $key => $label) {
            self::assertStringContainsString('-20-solid', $key);
            self::assertStringEndsWith('(solid 20)', $label);
        }
    }

    public function testGetAllIconsOutlineStyle(): void
    {
        $icons = $this->provider->getAllIcons('outline');

        self::assertNotEmpty($icons);

        foreach ($icons as $key => $label) {
            self::assertStringStartsWith('heroicons:', $key);
            self::assertStringEndsNotWith('-solid', $key);
            self::assertStringEndsNotWith('-16-solid', $key);
            self::assertStringEndsNotWith('-20-solid', $key);
            self::assertStringEndsWith('(outline)', $label);
        }
    }

    public function testGetAllIconsSolidStyle(): void
    {
        $icons = $this->provider->getAllIcons('solid');

        self::assertNotEmpty($icons);

        foreach ($icons as $key => $label) {
            self::assertStringEndsWith('-solid', $key);
            self::assertStringEndsWith('(solid)', $label);
        }
    }

    public function testGetAllIcons16SolidStyle(): void
    {
        $icons = $this->provider->getAllIcons('16-solid');

        self::assertNotEmpty($icons);

        foreach ($icons as $key => $label) {
            self::assertStringContainsString('-16-solid', $key);
            self::assertStringEndsWith('(mini)', $label);
        }
    }

    public function testGetAllIconsNullReturnsAllStyles(): void
    {
        $icons = $this->provider->getAllIcons(null);

        self::assertNotEmpty($icons);

        $hasOutline = false;
        $hasSolid = false;
        $has20Solid = false;
        $has16Solid = false;

        foreach ($icons as $key => $label) {
            if (str_ends_with($label, '(outline)')) {
                $hasOutline = true;
            } elseif (str_ends_with($label, '(solid 20)')) {
                $has20Solid = true;
            } elseif (str_ends_with($label, '(mini)')) {
                $has16Solid = true;
            } elseif (str_ends_with($label, '(solid)')) {
                $hasSolid = true;
            }
        }

        self::assertTrue($hasOutline, 'Should contain outline icons');
        self::assertTrue($hasSolid, 'Should contain solid icons');
        self::assertTrue($has20Solid, 'Should contain 20-solid icons');
        self::assertTrue($has16Solid, 'Should contain 16-solid icons');
    }

    public function testIconsAreSortedByKey(): void
    {
        $icons = $this->provider->getAllIcons();
        $keys = array_keys($icons);
        $sorted = $keys;
        sort($sorted);

        self::assertSame($sorted, $keys);
    }

    public function testAllIconsStartWithHeroiconsPrefix(): void
    {
        $icons = $this->provider->getAllIcons(null);

        foreach (array_keys($icons) as $key) {
            self::assertStringStartsWith('heroicons:', $key);
        }
    }

    public function testKnownIconExists(): void
    {
        $icons = $this->provider->getAllIcons('20-solid');

        self::assertArrayHasKey('heroicons:academic-cap-20-solid', $icons);
        self::assertSame('academic-cap (solid 20)', $icons['heroicons:academic-cap-20-solid']);
    }
}
