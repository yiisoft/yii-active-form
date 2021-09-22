<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\Stub\ValidatorMock;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Widget\WidgetFactory;

final class FormTest extends TestCase
{
    public function testAcceptCharset(): void
    {
        $this->assertSame(
            '<form method="POST" accept-charset="UTF-8">',
            Form::widget()->acceptCharset('UTF-8')->begin(),
        );
    }

    public function testAction(): void
    {
        $this->assertSame('<form action="/test" method="POST">', Form::widget()->action('/test')->begin());
    }

    public function testAttributes(): void
    {
        $this->assertSame(
            '<form class="test-class" method="POST">',
            Form::widget()->attributes(['class' => 'test-class'])->begin(),
        );
    }

    public function testAutocomplete(): void
    {
        /** on value */
        $this->assertSame(
            '<form method="POST" autocomplete="on">',
            Form::widget()->autocomplete()->begin(),
        );
        /** off value */
        $this->assertSame(
            '<form method="POST" autocomplete="off">',
            Form::widget()->autocomplete(false)->begin(),
        );
    }

    public function testBegin(): void
    {
        $this->assertSame('<form method="POST">', Form::widget()->begin());
        $hiddens = [
            '<input type="hidden" name="id" value="1">',
            '<input type="hidden" name="title" value="&lt;">',
        ];
        $this->assertSame(
            '<form action="/example" method="GET">' . PHP_EOL . implode(PHP_EOL, $hiddens),
            Form::widget()->action('/example?id=1&title=%3C')->method('GET')->begin()
        );
        $this->assertStringMatchesFormat(
            '<form action="/foo" method="GET">%A<input type="hidden" name="p" value>',
            Form::widget()->action('/foo?p')->method('GET')->begin(),
        );
    }

    /**
     * Data provider for {@see testCsrf()}.
     *
     * @return array test data
     */
    public function dataProviderCsrf(): array
    {
        return [
            ['<form action="/foo" method="GET">', 'GET',  ''],
            ['<form action="/foo" method="GET" _csrf="tokenCsrf">', 'GET',  'tokenCsrf'],
            ['<form action="/foo" method="POST">', 'POST', '_csrf' => ''],
            [
                '<form action="/foo" method="POST" _csrf="tokenCsrf">' . PHP_EOL .
                '<input type="hidden" name="_csrf" value="tokenCsrf">',
                'POST',
                '_csrf' => 'tokenCsrf',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderCsrf
     *
     * @param string $expected
     * @param string $method
     * @param array $options
     */
    public function testCsrf(string $expected, string $method, string $csrf): void
    {
        $this->assertSame($expected, Form::widget()->action('/foo')->method($method)->csrf($csrf)->begin());
        $this->assertSame(
            $expected,
            Form::widget()->action('/foo')->attributes(['_csrf' => $csrf])->method($method)->begin(),
        );
    }

    public function testEnd(): void
    {
        Form::widget()->begin();
        $this->assertSame('</form>', Form::end());
    }

    public function testEnctype(): void
    {
        $this->assertSame(
            '<form id="multipart/form-data" method="POST">',
            Form::widget()->enctype('multipart/form-data')->begin(),
        );
    }

    public function testId(): void
    {
        $this->assertSame('<form id="form-id" method="POST">', Form::widget()->id('form-id')->begin());
        $this->assertSame(
            '<form id="form-id" method="POST">',
            Form::widget()->attributes(['id' => 'form-id'])->begin(),
        );
    }

    public function testImmutability(): void
    {
        $form = Form::widget();
        $this->assertNotSame($form, $form->acceptCharset(''));
        $this->assertNotSame($form, $form->action(''));
        $this->assertNotSame($form, $form->attributes([]));
        $this->assertNotSame($form, $form->autocomplete());
        $this->assertNotSame($form, $form->csrf(''));
        $this->assertNotSame($form, $form->enctype(''));
        $this->assertNotSame($form, $form->id(''));
        $this->assertNotSame($form, $form->method(''));
        $this->assertNotSame($form, $form->noHtmlValidatation());
        $this->assertNotSame($form, $form->target(''));
    }

    public function testMethod(): void
    {
        $this->assertSame('<form method="GET">', Form::widget()->method('get')->begin());
        $this->assertSame('<form method="POST">', Form::widget()->method('post')->begin());
    }

    public function testNoHtmlValidatation(): void
    {
        $this->assertSame('<form method="POST" novalidate>', Form::widget()->noHtmlValidatation()->begin());
    }

    public function testTarget(): void
    {
        $this->assertSame('<form method="POST" target="_blank">', Form::widget()->target('_blank')->begin());
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
    }

    private function createValidatorMock(): ValidatorInterface
    {
        return new ValidatorMock();
    }
}
