<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Twig\Component;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Twig\Component\PasswordField;

final class PasswordFieldTest extends TestCase
{
    public function testGetStrengthWithEmptyPasswordReturnsZeroScore(): void
    {
        $field = new PasswordField();
        $field->password = '';

        $result = $field->getStrength();

        self::assertSame(0, $result['score']);
        self::assertFalse($result['rules']['length']);
        self::assertFalse($result['rules']['uppercase']);
        self::assertFalse($result['rules']['number']);
        self::assertFalse($result['rules']['special']);
    }

    public function testGetStrengthWithAllRulesMetReturnsMaxScore(): void
    {
        $field = new PasswordField();
        $field->password = 'StrongP@ss1';
        $field->minLength = 8;
        $field->requireUppercase = true;
        $field->requireNumbers = true;
        $field->requireSpecial = true;

        $result = $field->getStrength();

        self::assertSame(4, $result['score']);
        self::assertTrue($result['rules']['length']);
        self::assertTrue($result['rules']['uppercase']);
        self::assertTrue($result['rules']['number']);
        self::assertTrue($result['rules']['special']);
    }

    public function testGetStrengthWithOnlyLengthMetReturnsPartialScore(): void
    {
        $field = new PasswordField();
        $field->password = 'longenough';
        $field->minLength = 8;
        $field->requireUppercase = true;
        $field->requireNumbers = true;
        $field->requireSpecial = true;

        $result = $field->getStrength();

        self::assertTrue($result['rules']['length']);
        self::assertFalse($result['rules']['uppercase']);
        self::assertFalse($result['rules']['number']);
        self::assertFalse($result['rules']['special']);
        self::assertSame(1, $result['score']);
    }

    public function testGetStrengthWithoutRequirementsAllRulesPass(): void
    {
        $field = new PasswordField();
        $field->password = 'simple';
        $field->minLength = 4;
        $field->requireUppercase = false;
        $field->requireNumbers = false;
        $field->requireSpecial = false;

        $result = $field->getStrength();

        self::assertSame(4, $result['score']);
        self::assertTrue($result['rules']['length']);
        self::assertTrue($result['rules']['uppercase']);
        self::assertTrue($result['rules']['number']);
        self::assertTrue($result['rules']['special']);
    }

    public function testGetStrengthTooShortPasswordFailsLength(): void
    {
        $field = new PasswordField();
        $field->password = 'Ab1!';
        $field->minLength = 8;
        $field->requireUppercase = true;
        $field->requireNumbers = true;
        $field->requireSpecial = true;

        $result = $field->getStrength();

        self::assertFalse($result['rules']['length']);
        self::assertTrue($result['rules']['uppercase']);
        self::assertTrue($result['rules']['number']);
        self::assertTrue($result['rules']['special']);
        self::assertSame(3, $result['score']);
    }

    public function testGetStrengthHalfRulesMetReturnsScore2(): void
    {
        $field = new PasswordField();
        $field->password = 'ABCDEFGH';
        $field->minLength = 8;
        $field->requireUppercase = true;
        $field->requireNumbers = true;
        $field->requireSpecial = true;

        $result = $field->getStrength();

        self::assertTrue($result['rules']['length']);
        self::assertTrue($result['rules']['uppercase']);
        self::assertFalse($result['rules']['number']);
        self::assertFalse($result['rules']['special']);
        self::assertSame(2, $result['score']);
    }

    public function testDefaultPropertyValues(): void
    {
        $field = new PasswordField();

        self::assertSame('', $field->password);
        self::assertSame('', $field->name);
        self::assertSame('crud.component.password_field.placeholder', $field->placeholder);
        self::assertTrue($field->showStrength);
        self::assertSame(8, $field->minLength);
        self::assertFalse($field->requireUppercase);
        self::assertFalse($field->requireNumbers);
        self::assertFalse($field->requireSpecial);
    }
}
