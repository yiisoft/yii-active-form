<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\Radio;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class RadioTest extends TestCase
{
    public function testEnClosedByLabelWithFalse(): void
    {
        $this->assertSame(
            '<input type="radio" id="typeform-int" name="TypeForm[int]" value="0">',
            Radio::widget()->config($this->formModel, 'int')->enclosedByLabel(false)->render(),
        );
    }

    public function testForceUncheckedValue(): void
    {
        $expected = <<<'HTML'
        <input type="hidden" name="TypeForm[int]" value="0"><label><input type="radio" id="typeform-int" name="TypeForm[int]" value="0"> Int</label>
        HTML;
        $this->assertSame(
            $expected,
            Radio::widget()->config($this->formModel, 'int', ['forceUncheckedValue' => '0'])->render(),
        );
    }

    public function testForm(): void
    {
        $this->assertSame(
            '<label><input type="radio" id="typeform-int" name="TypeForm[int]" value="0" form="form-id"> Int</label>',
            Radio::widget()->config($this->formModel, 'int')->form('form-id')->render(),
        );
    }

    public function testImmutability(): void
    {
        $radio = Radio::widget();
        $this->assertNotSame($radio, $radio->enclosedByLabel(false));
        $this->assertNotSame($radio, $radio->form(''));
        $this->assertNotSame($radio, $radio->label(''));
        $this->assertNotSame($radio, $radio->labelAttributes());
    }

    public function testLabelWithLabelAttributes(): void
    {
        $expected = <<<'HTML'
        <label class="test-class"><input type="radio" id="typeform-int" name="TypeForm[int]" value="0"> Label:</label>
        HTML;
        $html = Radio::widget()
            ->config($this->formModel, 'int')
            ->label('Label:')
            ->labelAttributes(['class' => 'test-class'])
            ->render();
        $this->assertSame($expected, $html);
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<label><input type="radio" id="typeform-int" name="TypeForm[int]" value="0"> Int</label>',
            Radio::widget()->config($this->formModel, 'int')->render()
        );
    }

    public function testValues(): void
    {
        // value bool false
        $this->formModel->setAttribute('bool', false);
        $this->assertSame(
            '<label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0"> Bool</label>',
            Radio::widget()->config($this->formModel, 'bool')->render(),
        );

        // value bool true
        $this->formModel->setAttribute('bool', true);
        $this->assertSame(
            '<label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="1" checked> Bool</label>',
            Radio::widget()->config($this->formModel, 'bool')->render(),
        );

        // value int 0
        $this->formModel->setAttribute('int', 0);
        $this->assertSame(
            '<label><input type="radio" id="typeform-int" name="TypeForm[int]" value="0"> Int</label>',
            Radio::widget()->config($this->formModel, 'int')->render(),
        );

        // value int 1
        $this->formModel->setAttribute('int', 1);
        $this->assertSame(
            '<label><input type="radio" id="typeform-int" name="TypeForm[int]" value="1" checked> Int</label>',
            Radio::widget()->config($this->formModel, 'int')->render(),
        );

        // value string '0'
        $this->formModel->setAttribute('string', '0');
        $this->assertSame(
            '<label><input type="radio" id="typeform-string" name="TypeForm[string]" value="0"> String</label>',
            Radio::widget()->config($this->formModel, 'string')->render(),
        );

        // value string '1'
        $this->formModel->setAttribute('string', '1');
        $this->assertSame(
            '<label><input type="radio" id="typeform-string" name="TypeForm[string]" value="1" checked> String</label>',
            Radio::widget()->config($this->formModel, 'string')->render(),
        );

        // value null
        $this->formModel->setAttribute('toNull', null);
        $this->assertSame(
            '<label><input type="radio" id="typeform-tonull" name="TypeForm[toNull]" value="0"> To Null</label>',
            Radio::widget()->config($this->formModel, 'toNull')->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radio widget requires a bool|float|int|string|null value.');
        $html = Radio::widget()->config($this->formModel, 'array')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
