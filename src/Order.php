<?php

namespace Bizarg\Order;

use Illuminate\Http\Request;

/**
 * Class Order
 * @package Bizarg\Order
 */
class Order
{
    /**
     * Define sort
     * @type string
     */
    public const SORT_ASC = 'asc';
    /**
     * Define sort
     * @type string
     */
    public const SORT_DESC = 'desc';
    /**
     * Define sort field
     * @type string
     */
    public const SORT_FIELD = 'createdAt';

    /**
     * @var string|null
     */
    private ?string $field = null;

    /**
     * @var string|null
     */
    private ?string $direction = null;

    /**
     * @return string|null
     */
    public function field(): ?string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function setField(?string $field): self
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string|null
     */
    public function direction(): ?string
    {
        return $this->direction;
    }

    /**
     * @param string|null $direction
     * @return $this
     */
    public function setDirection(?string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @param Request $request
     * @param array $allowedFields
     * @param string $defaultSortField
     * @param string $direction
     * @param string $sortParam
     * @return $this
     */
    public static function fromRequest(
        Request $request,
        array $allowedFields,
        string $defaultSortField = self::SORT_FIELD,
        string $direction = self::SORT_ASC,
        string $sortParam = 'sort'
    ) {
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
