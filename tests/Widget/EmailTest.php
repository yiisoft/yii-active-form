<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\Email;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class EmailTest extends TestCase
{
    private TypeForm $formModel;

    public function testImmutability(): void
    {
        $email = Email::widget();
        $this->assertNotSame($email, $email->maxlength(0));
        $this->assertNotSame($email, $email->minlength(0));
        $this->assertNotSame($email, $email->multiple());
        $this->assertNotSame($email, $email->pattern(''));
        $this->assertNotSame($email, $email->placeholder(''));
        $this->assertNotSame($email, $email->size(0));
    }

    public function testMaxLength(): void
    {
        $this->assertSame(
            '<input type="email" id="typeform-string" name="TypeForm[string]" value maxlength="10">',
            Email::widget()->config($this->formModel, 'string')->maxlength(10)->render(),
        );
    }

    public function testMinLength(): void
    {
        $this->assertSame(
            '<input type="email" id="typeform-string" name="TypeForm[string]" value minlength="4">',
            Email::widget()->config($this->formModel, 'string')->minlength(4)->render(),
        );
    }

    public function testMultiple(): void
    {
        $this->formModel->setAttribute('string', 'email1@example.com;email2@example.com;');
        $this->assertSame(
            '<input type="email" id="typeform-string" name="TypeForm[string]" value="email1@example.com;email2@example.com;" multiple>',
            Email::widget()->config($this->formModel, 'string')->multiple()->render(),
        );
    }

    public function testPattern(): void
    {
        $expected = <<<'HTML'
        <input type="email" id="typeform-string" name="TypeForm[string]" value pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-zA-Z]{2,4}">
        HTML;
        $html = Email::widget()
            ->config($this->formModel, 'string')
            ->pattern('[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-zA-Z]{2,4}')
            ->render();
        $this->assertSame($expected, $html);
    }

    public function testPlaceholder(): void
    {
        $this->assertSame(
            '<input type="email" id="typeform-string" name="TypeForm[string]" value placeholder="PlaceHolder Text">',
            Email::widget()->config($this->formModel, 'string')->placeholder('PlaceHolder Text')->render(),
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<input type="email" id="typeform-string" name="TypeForm[string]" value>',
            Email::widget()->config($this->formModel, 'string')->render(),
        );
    }

    public function testSize(): void
    {
        $this->assertSame(
            '<input type="email" id="typeform-string" name="TypeForm[string]" value size="20">',
            Email::widget()->config($this->formModel, 'string')->size(20)->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email widget must be a string.');
        Email::widget()->config($this->formModel, 'int')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
