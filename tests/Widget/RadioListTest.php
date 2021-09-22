<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\RadioList;
use Yiisoft\Html\Widget\RadioList\RadioItem;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class RadioListTest extends TestCase
{
    use TestTrait;

    private array $cities = ['1' => 'Moscu', '2' => 'San Petersburgo', '3' => 'Novosibirsk', '4' => 'Ekaterinburgo'];
    private TypeForm $formModel;

    public function testContainerAttributes(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div id="typeform-int" class="test-class">
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int')
            ->containerAttributes(['class' => 'test-class'])
            ->items($this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testContainerTag(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <tag-test id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </tag-test>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int')
            ->containerTag('tag-test')
            ->items($this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testContainerTagWithNull(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int')
            ->containerTag(null)
            ->items($this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testDisabled(): void
    {
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" disabled> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2" disabled> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3" disabled> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4" disabled> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->disabled()->items($this->cities)->render(),
        );
    }

    public function testForceUncheckedValue(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <input type="hidden" name="TypeForm[int]" value="0">
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int', ['forceUncheckedValue' => '0'])
            ->items($this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testIndividualItemsAttributes(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" disabled> Moscu</label>
        <label><input type="radio" class="test-class" name="TypeForm[int]" value="2" checked> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int')
            ->individualItemsAttributes([1 => ['disabled' => true], 2 => ['class' => 'test-class']])
            ->items($this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testImmutability(): void
    {
        $radioList = RadioList::widget();
        $this->assertNotSame($radioList, $radioList->containerAttributes([]));
        $this->assertNotSame($radioList, $radioList->containerTag(null));
        $this->assertNotSame($radioList, $radioList->disabled());
        $this->assertNotSame($radioList, $radioList->items());
        $this->assertNotSame($radioList, $radioList->itemsAttributes());
        $this->assertNotSame(
            $radioList,
            $radioList->itemsFormatter(
                static function (RadioItem $item): string {
                    return '';
                }
            ),
        );
        $this->assertNotSame($radioList, $radioList->readOnly());
        $this->assertNotSame($radioList, $radioList->separator(''));
    }

    public function testItemsAttributes(): void
    {
        $this->formModel->setAttribute('int', 3);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" class="test-class" name="TypeForm[int]" value="1"> Moscu</label>
        <label><input type="radio" class="test-class" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" class="test-class" name="TypeForm[int]" value="3" checked> Novosibirsk</label>
        <label><input type="radio" class="test-class" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int')
            ->items($this->cities)
            ->itemsAttributes(['class' => 'test-class'])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testItemsFormater(): void
    {
        $this->formModel->setAttribute('int', 3);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <div class='col-sm-12'><label><input type='radio' name='TypeForm[int]' class='test-class' value='1' tabindex='0'> Moscu</label></div>
        <div class='col-sm-12'><label><input type='radio' name='TypeForm[int]' class='test-class' value='2' tabindex='1'> San Petersburgo</label></div>
        <div class='col-sm-12'><label><input type='radio' name='TypeForm[int]' class='test-class' value='3' tabindex='2' checked> Novosibirsk</label></div>
        <div class='col-sm-12'><label><input type='radio' name='TypeForm[int]' class='test-class' value='4' tabindex='3'> Ekaterinburgo</label></div>
        </div>
        HTML;
        $html = RadioList::widget()
            ->config($this->formModel, 'int')
            ->items($this->cities)
            ->itemsFormatter(static function (RadioItem $item) {
                $check = $item->checked ? 'checked' : '';
                return $item->checked
                    ? "<div class='col-sm-12'><label><input type='radio' name='{$item->name}' class='test-class' value='{$item->value}' tabindex='{$item->index}' checked> {$item->label}</label></div>"
                    : "<div class='col-sm-12'><label><input type='radio' name='{$item->name}' class='test-class' value='{$item->value}' tabindex='{$item->index}'> {$item->label}</label></div>";
            })
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testReadOnly(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" checked readonly> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2" readonly> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3" readonly> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4" readonly> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items($this->cities)->readOnly()->render(),
        );
    }

    public function testRender(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1"> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2" checked> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items($this->cities)->render(),
        );
    }

    public function testSeparator(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items($this->cities)->separator(PHP_EOL)->render(),
        );
    }

    public function testValues(): void
    {
        // value bool false
        $this->formModel->setAttribute('int', 0);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="0" checked> Female</label>
        <label><input type="radio" name="TypeForm[int]" value="1"> Male</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items([0 => 'Female', 1 => 'Male'])->render(),
        );

        // value bool true
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="0"> Female</label>
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Male</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items([0 => 'Female', 1 => 'Male'])->render(),
        );

        // value int 0
        $this->formModel->setAttribute('int', 0);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1"> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items($this->cities)->render(),
        );

        // value int 1
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div id="typeform-int">
        <label><input type="radio" name="TypeForm[int]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[int]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[int]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[int]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'int')->items($this->cities)->render(),
        );

        // value string '0'
        $this->formModel->setAttribute('string', 0);
        $expected = <<<'HTML'
        <div id="typeform-string">
        <label><input type="radio" name="TypeForm[string]" value="1"> Moscu</label>
        <label><input type="radio" name="TypeForm[string]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[string]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[string]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'string')->items($this->cities)->render(),
        );

        // value string '1'
        $this->formModel->setAttribute('string', 1);
        $expected = <<<'HTML'
        <div id="typeform-string">
        <label><input type="radio" name="TypeForm[string]" value="1" checked> Moscu</label>
        <label><input type="radio" name="TypeForm[string]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[string]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[string]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'string')->items($this->cities)->render(),
        );

        // value null
        $this->formModel->setAttribute('toNull', null);
        $expected = <<<'HTML'
        <div id="typeform-tonull">
        <label><input type="radio" name="TypeForm[toNull]" value="1"> Moscu</label>
        <label><input type="radio" name="TypeForm[toNull]" value="2"> San Petersburgo</label>
        <label><input type="radio" name="TypeForm[toNull]" value="3"> Novosibirsk</label>
        <label><input type="radio" name="TypeForm[toNull]" value="4"> Ekaterinburgo</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            RadioList::widget()->config($this->formModel, 'toNull')->items($this->cities)->render(),
        );
    }

    public function testValueException(): void
    {
        $this->formModel->setAttribute('array', []);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('RadioList widget required bool|float|int|string|null.');
        $html = RadioList::widget()->config($this->formModel, 'array')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new typeForm();
    }
}
