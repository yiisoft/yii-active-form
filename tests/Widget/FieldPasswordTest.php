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

final class FieldPasswordTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testForm(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value form="form-id" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->password(['form' => 'form-id'])->render(),
        );
    }

    public function testMaxLength(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value maxlength="16" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->password(['maxlength' => 16])->render(),
        );
    }

    public function testMinLength(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value minlength="8" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->password(['minlength' => 8])->render(),
        );
    }

    public function testPattern(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->password(
                [
                    'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                    'title' => 'Must contain at least one number and one uppercase and lowercase letter, and at ' .
                    'least 8 or more characters.',
                ]
            )
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testPlaceholder(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value placeholder="PlaceHolder Text">
        <div>Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->password(['placeholder' => 'PlaceHolder Text'])
            ->render();
        $this->assertEqualsWithoutLE(
            $expected,
            $html,
        );
    }

    public function testReadOnly(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value readonly placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->password(['readonly' => true])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="password" id="typeform-string" name="TypeForm[string]" value placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->password()->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password widget must be a string.');
        Field::widget()->config($this->formModel, 'array')->password()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
