<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget\Attribute;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldAttributesTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testAriaDescribedBy(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value aria-describedby="typeform-string" placeholder="Typed your text string.">
        <div id="typeform-string">Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->ariaDescribedBy()
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testContainerClass(): void
    {
        $expected = <<<'HTML'
        <div class="test-class">
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->containerClass('test-class')
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testGetFormModelException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Form model is not set.');
        $this->invokeMethod(Field::widget(), 'getFormModel');
    }

    public function testInputClass(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" class="test-class" name="TypeForm[string]" value placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->inputClass('test-class')
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testImmutability(): void
    {
        $field = Field::widget();
        $this->assertNotSame($field, $field->ariaDescribedBy());
        $this->assertNotSame($field, $field->config($this->formModel, 'string', []));
        $this->assertNotSame($field, $field->containerClass(''));
        $this->assertNotSame($field, $field->errorClass(''));
        $this->assertNotSame($field, $field->hintClass(''));
        $this->assertNotSame($field, $field->inputClass(''));
        $this->assertNotSame($field, $field->invalidClass(''));
        $this->assertNotSame($field, $field->labelClass(''));
        $this->assertNotSame($field, $field->template(''));
        $this->assertNotSame($field, $field->validClass(''));
    }

    public function testLabelClass(): void
    {
        $expected = <<<'HTML'
        <div>
        <label class="test-class" for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->labelClass('test-class')
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testTemplate(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="Typed your text string.">
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->template('{input}')
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
