<?php

declare(strict_types=1);

namespace Yiisoft\Form;

/**
 * FormErrors represents a form validation errors collection.
 */
final class FormErrors implements FormErrorsInterface
{
    /** @psalm-var array<string, array<array-key, string>> */
    private array $attributesErrors;

    public function __construct(array $attributesErrors = [])
    {
        /** @psalm-var array<string, array<array-key, string>> */
        $this->attributesErrors = $attributesErrors;
    }

    public function addError(string $attribute, string $error): void
    {
        $this->attributesErrors[$attribute][] = $error;
    }

    public function getAllErrors(): array
    {
        return $this->attributesErrors;
    }

    public function getErrors(string $attribute): array
    {
        return $this->attributesErrors[$attribute] ?? [];
    }

    public function getErrorSummary(): array
    {
        return $this->renderErrorSumary($this->getAllErrors());
    }

    public function getErrorSummaryFirstErrors(): array
    {
        return $this->renderErrorSumary([$this->getFirstErrors()]);
    }

    public function getFirstError(string $attribute): string
    {
        if (empty($this->attributesErrors[$attribute])) {
            return '';
        }

        return reset($this->attributesErrors[$attribute]);
    }

    public function getFirstErrors(): array
    {
        if (empty($this->attributesErrors)) {
            return [];
        }

        $errors = [];

        foreach ($this->attributesErrors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }

        return $errors;
    }

    public function hasErrors(?string $attribute = null): bool
    {
        return $attribute === null ? !empty($this->attributesErrors) : isset($this->attributesErrors[$attribute]);
    }

    public function clear(string $attribute = null): void
    {
        if ($attribute !== null) {
            unset($this->attributesErrors[$attribute]);
        } else {
            $this->attributesErrors = [];
        }
    }

    private function renderErrorSumary(array $errors): array
    {
        $lines = [];

        /** @var string[] errors */
        foreach ($errors as $error) {
            $lines = array_merge($lines, $error);
        }

        return $lines;
    }
}
