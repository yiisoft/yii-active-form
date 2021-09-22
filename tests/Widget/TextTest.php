<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\Text;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class TextTest extends TestCase
{
    private TypeForm $formModel;

    public function testDirname(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value dirname="test.dir">',
            Text::widget()->config($this->formModel, 'string')->dirname('test.dir')->render(),
        );
    }

    public function testDirnameException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value cannot be empty.');
        Text::widget()->config($this->formModel, 'string')->dirname('')->render();
    }

    public function testForm(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value form="form-id">',
            Text::widget()->config($this->formModel, 'string')->form('form-id')->render(),
        );
    }

    public function testImmutability(): void
    {
        $text = Text::widget();
        $this->assertNotSame($text, $text->dirname('test.dir'));
        $this->assertNotSame($text, $text->form(''));
        $this->assertNotSame($text, $text->maxlength(0));
        $this->assertNotSame($text, $text->placeholder(''));
        $this->assertNotSame($text, $text->pattern(''));
        $this->assertNotSame($text, $text->readOnly());
        $this->assertNotSame($text, $text->size(0));
    }

    public function testMaxLength(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value maxlength="10">',
            Text::widget()->config($this->formModel, 'string')->maxlength(10)->render(),
        );
    }

    public function testMinLength(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value minlength="4">',
            Text::widget()->config($this->formModel, 'string')->minlength(4)->render(),
        );
    }

    public function testPattern(): void
    {
        $expected = <<<'HTML'
        <input type="text" id="typeform-string" name="TypeForm[string]" value title="Only accepts uppercase and lowercase letters." pattern="[A-Za-z]">
        HTML;
        $html = Text::widget()
            ->config($this->formModel, 'string', ['title' => 'Only accepts uppercase and lowercase letters.'])
            ->pattern('[A-Za-z]')
            ->render();
        $this->assertSame($expected, $html);
    }

    public function testPlaceholder(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="PlaceHolder Text">',
            Text::widget()->config($this->formModel, 'string')->placeholder('PlaceHolder Text')->render(),
        );
    }

    public function testReadOnly(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value readonly>',
            Text::widget()->config($this->formModel, 'string')->readOnly()->render(),
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value>',
            Text::widget()->config($this->formModel, 'string')->render(),
        );
    }

    public function testSize(): void
    {
        $this->assertSame(
            '<input type="text" id="typeform-string" name="TypeForm[string]" value size="10">',
            Text::widget()->config($this->formModel, 'string')->size(10)->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Text widget must be a string.');
        $html = Text::widget()->config($this->formModel, 'array')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
