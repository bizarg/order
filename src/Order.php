<?php

namespace Bizarg\Order;

use Illuminate\Http\Request;

class Order
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';
    public const SORT_FIELD = 'createdAt';

    private ?string $field = null;
    private ?string $direction = null;

    public function field(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): static
    {
        $this->field = $field;
        return $this;
    }

    public function direction(): ?string
    {
        return $this->direction;
    }

    public function setDirection(?string $direction): static
    {
        $this->direction = $direction;
        return $this;
    }

    public static function fromRequest(
        Request $request,
        array $allowedFields,
        string $defaultSortField = self::SORT_FIELD,
        string $direction = self::SORT_ASC,
        string $sortParam = 'sort'
    ): static {
        $param = $request->get($sortParam);

        if (substr($param, 0, 1) == '-') {
            $direction = self::SORT_DESC;
            $param = ltrim($param, '-');
        }

        $sortField = !in_array($param, array_keys($allowedFields))
            ? $allowedFields[$defaultSortField] ?? 'created_at'
            : $allowedFields[$param];

        return (new static())
            ->setField($sortField)
            ->setDirection($direction);
    }
}
