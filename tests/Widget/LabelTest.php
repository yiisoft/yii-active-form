<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\Label;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class LabelTest extends TestCase
{
    private TypeForm $formModel;

    /**
     * @link https://github.com/yiisoft/form/issues/85
     */
    public function testEncodeFalse(): void
    {
        $this->assertSame(
            '<label for="typeform-string">My&nbsp;Field</label>',
            Label::widget()->config($this->formModel, 'string', ['encode' => false])->label('My&nbsp;Field')->render(),
        );
    }

    public function testFor(): void
    {
        $this->assertSame(
            '<label for="for-id">String</label>',
            Label::widget()->config($this->formModel, 'string')->for('for-id')->render(),
        );
    }

    public function testImmutability(): void
    {
        $label = Label::widget();
        $this->assertNotSame($label, $label->for(''));
        $this->assertNotSame($label, $label->label(''));
    }

    public function testLabel(): void
    {
        $this->assertSame(
            '<label for="typeform-string">Label:</label>',
            Label::widget()->config($this->formModel, 'string')->label('Label:')->render(),
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<label for="typeform-string">String</label>',
            Label::widget()->config($this->formModel, 'string')->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
