<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\File;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FileTest extends TestCase
{
    private TypeForm $formModel;

    public function testAccept(): void
    {
        $this->assertSame(
            '<input type="file" id="typeform-tonull" name="TypeForm[toNull]" accept="image/*">',
            File::widget()->config($this->formModel, 'toNull')->accept('image/*')->render(),
        );
    }

    public function testForceUncheckedValue(): void
    {
        $expected = <<<'HTML'
        <input type="hidden" name="TypeForm[toNull]" value><input type="file" id="typeform-tonull" name="TypeForm[toNull]">
        HTML;
        $html = File::widget()
            ->config($this->formModel, 'toNull', ['forceUncheckedValue' => ''])
            ->render();
        $this->assertSame($expected, $html);
    }

    public function testHiddenAttributes(): void
    {
        $expected = <<<'HTML'
        <input type="hidden" id="test-id" name="TypeForm[toNull]" value><input type="file" id="typeform-tonull" name="TypeForm[toNull]">
        HTML;
        $html = File::widget()
            ->config(
                $this->formModel,
                'toNull',
                [
                    'forceUncheckedValue' => '',
                    'hiddenAttributes' => ['id' => 'test-id'],
                ]
            )
            ->render();
        $this->assertSame($expected, $html);
    }

    public function testImmutability(): void
    {
        $fileInput = File::widget();
        $this->assertNotSame($fileInput, $fileInput->accept(''));
        $this->assertNotSame($fileInput, $fileInput->form(''));
        $this->assertNotSame($fileInput, $fileInput->multiple());
    }

    public function testMultiple(): void
    {
        $this->assertSame(
            '<input type="file" id="typeform-tonull" name="TypeForm[toNull]" multiple>',
            File::widget()->config($this->formModel, 'toNull')->multiple()->render(),
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<input type="file" id="typeform-tonull" name="TypeForm[toNull]">',
            File::widget()->config($this->formModel, 'toNull')->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
