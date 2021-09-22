<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Tests\TestSupport\Validator\ValidatorMock;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Widget\WidgetFactory;

final class FieldTextTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testDirname(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="Typed your text string." dirname="test.dir">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['dirname' => 'test.dir'])->render(),
        );
    }

    public function testDirnameException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value cannot be empty.');
        Field::widget()->config($this->formModel, 'string')->text(['dirname' => ''])->render();
    }

    public function testForm(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value form="form-id" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['form' => 'form-id'])->render(),
        );
    }

    public function testMaxLength(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value maxlength="10" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['maxlength' => 10])->render(),
        );
    }

    public function testMinLength(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value minlength="4" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['minlength' => 4])->render(),
        );
    }

    public function testPattern(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value title="Only accepts uppercase and lowercase letters." pattern="[A-Za-z]" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'string')
            ->text(['pattern' => '[A-Za-z]', 'title' => 'Only accepts uppercase and lowercase letters.'])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testPlaceholder(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="PlaceHolder Text">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['placeholder' => 'PlaceHolder Text'])->render(),
        );
    }

    public function testReadOnly(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value readonly placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['readonly' => true])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->render(),
        );
    }

    public function testSize(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="text" id="typeform-string" name="TypeForm[string]" value size="10" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->text(['size' => 10])->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Text widget must be a string.');
        Field::widget()->config($this->formModel, 'array')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }

    private function createValidatorMock(): ValidatorInterface
    {
        return new ValidatorMock();
    }
}
