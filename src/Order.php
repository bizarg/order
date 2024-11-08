<?php

namespace Bizarg\Order;

use Illuminate\Http\Request;

class Order
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';
    public const SORT_FIELD = 'createdAt';

    private array $fields = [];
    private array $directions = [];
    private array $cases = [];

    public function fields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): static
    {
        $this->fields = $fields;
        return $this;
    }

    public function directions(): array
    {
        return $this->directions;
    }

    public function setDirections(array $directions): static
    {
        $this->directions = $directions;
        return $this;
    }

    public static function fromRequest(
        Request $request,
        array $allowedFields,
        string $defaultSortField = self::SORT_FIELD,
        string $defaultDirection = self::SORT_ASC,
        string $sortParam = 'sort'
    ): static {
        $sortParams = explode(',', $request->get($sortParam, ''));

        $fields = [];
        $directions = [];

        foreach ($sortParams as $param) {
            $direction = $defaultDirection;
            if (substr($param, 0, 1) === '-') {
                $direction = self::SORT_DESC;
                $param = ltrim($param, '-');
            }

            $sortField = !in_array($param, array_keys($allowedFields))
                ? $allowedFields[$defaultSortField] ?? 'id'
                : $allowedFields[$param];

            $fields[] = $sortField;
            $directions[] = $direction;
        }

        $static = (new static())
            ->setFields($fields)
            ->setDirections($directions);

        foreach ($request->input('sortCases') ?? [] as $key => $value) {
            $static->setCases($value, $allowedFields, $key);
        }

        return $static;
    }

    public function setCases(array $values, array $allowedFields, string $field = 'id'): static
    {
        if (in_array($field, array_keys($allowedFields))) {
            $this->cases[$field] = $values;
        }

        return $this;
    }

    public function getCases(): array
    {
        return $this->cases;
    }
}
