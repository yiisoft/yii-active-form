<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldNumberTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testMax(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="number" id="typeform-int" name="TypeForm[int]" value="0" max="8">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->number(['max' => 8])->render(),
        );
    }

    public function testMin(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="number" id="typeform-int" name="TypeForm[int]" value="0" min="4">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->number(['min' => 4])->render(),
        );
    }

    public function testPlaceholder(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="number" id="typeform-int" name="TypeForm[int]" value="0" placeholder="PlaceHolder Text">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->number(['placeholder' => 'PlaceHolder Text'])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="number" id="typeform-int" name="TypeForm[int]" value="0">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->number()->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number widget must be a numeric value.');
        Field::widget()->config($this->formModel, 'array')->number()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
