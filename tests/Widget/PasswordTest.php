<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Factory\NotFoundException;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Password;

final class PasswordTest extends TestCase
{
    use TestTrait;

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testImmutability(): void
    {
        $password = Password::widget();
        $this->assertNotSame($password, $password->maxlength(0));
        $this->assertNotSame($password, $password->minlength(4));
        $this->assertNotSame($password, $password->pattern(''));
        $this->assertNotSame($password, $password->placeholder(''));
        $this->assertNotSame($password, $password->readonly());
        $this->assertNotSame($password, $password->size(0));
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testMaxLength(): void
    {
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]" maxlength="16">',
            Password::widget()->for(new TypeForm(), 'string')->maxlength(16)->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testMinLength(): void
    {
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]" minlength="8">',
            Password::widget()->for(new TypeForm(), 'string')->minlength(8)->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testPattern(): void
    {
        $expected = <<<'HTML'
        <input type="password" id="typeform-string" name="TypeForm[string]" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
        HTML;
        $html = Password::widget()
            ->for(new TypeForm(), 'string')
            ->pattern('(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}')
            ->title(
                'Must contain at least one number and one uppercase and lowercase letter, and at ' .
                'least 8 or more characters.'
            )
            ->render();
        $this->assertSame($expected, $html);
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testPlaceholder(): void
    {
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]" placeholder="PlaceHolder Text">',
            Password::widget()
                ->for(new TypeForm(), 'string')
                ->placeholder('PlaceHolder Text')
                ->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testReadOnly(): void
    {
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]" readonly>',
            Password::widget()->for(new TypeForm(), 'string')->readonly()->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testRender(): void
    {
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]">',
            Password::widget()->for(new TypeForm(), 'string')->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testSize(): void
    {
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]" size="3">',
            Password::widget()->for(new TypeForm(), 'string')->size(3)->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password widget must be a string or null value.');
        Password::widget()->for(new TypeForm(), 'array')->render();
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testValueWithFormModel(): void
    {
        $formModel = new TypeForm();
        // Value string `1234??`.
        $formModel->setAttribute('string', '1234??');
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]" value="1234??">',
            Password::widget()->for($formModel, 'string')->render(),
        );

        // Value `null`.
        $formModel->setAttribute('string', null);
        $this->assertSame(
            '<input type="password" id="typeform-string" name="TypeForm[string]">',
            Password::widget()->for($formModel, 'string')->render(),
        );
    }
}
