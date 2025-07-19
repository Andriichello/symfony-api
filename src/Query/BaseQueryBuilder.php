<?php

namespace App\Query;

use App\Entity\Author;
use App\Enum\FilterOperator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BaseQueryBuilder.
 *
 * @package App\Query
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class BaseQueryBuilder extends QueryBuilder
{
    /**
     * AuthorQueryBuilder's constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * Adds a where statement for the `column`.
     *
     * @param string $column
     * @param mixed $value
     * @param FilterOperator $operator
     * @param bool $ignoreCase
     * @param bool $and
     * @param bool $not
     *
     * @return self
     */
    public function whereColumn(
        string $column,
        mixed $value,
        FilterOperator $operator = FilterOperator::EQ,
        bool $ignoreCase = false,
        bool $and = true,
        bool $not = false
    ): self {
        $parameter = 'val_' . str_replace('.', '_', $column);

        $x = $ignoreCase ? $this->expr()->lower($column) : $column;
        $y = $ignoreCase ? $this->expr()->lower(":$parameter") : ":$parameter";

        $expr = $this->expr();

        switch ($operator) {
            case FilterOperator::NULL:
                $expr = !(strtolower("$value") === 'false') && $value
                    ? $expr->isNull($column)
                    : $expr->isNotNull($column);
                break;

            case FilterOperator::EQ:
                $expr = $expr->eq($x, $y);
                break;

            case FilterOperator::GT:
                $expr = $expr->gt($x, $y);
                break;

            case FilterOperator::LT:
                $expr = $expr->lt($x, $y);
                break;

            case FilterOperator::GTE:
                $expr = $expr->gte($x, $y);
                break;

            case FilterOperator::LTE:
                $expr = $expr->lte($x, $y);
                break;

            case FilterOperator::LIKE:
                return $this->whereColumnLike($column, $value, $ignoreCase, $and, $not);
        }

        if ($not) {
            $expr = $this->expr()->not($expr);
        }

        $and ? $this->andWhere($expr)
            : $this->orWhere($expr);

        if ($operator !== FilterOperator::NULL) {
            $this->setParameter($parameter, $value);
        }

        return $this;
    }

    /**
     * Adds a where (like) statement for the `column`.
     *
     * @param string $column
     * @param mixed $value
     * @param bool $ignoreCase
     * @param bool $and
     * @param bool $not
     *
     * @return self
     */
    public function whereColumnLike(
        string $column,
        mixed $value,
        bool $ignoreCase = false,
        bool $and = true,
        bool $not = false
    ): self {
        $value = str_replace('%', '\%', $value);
        $parameter = 'val_' . str_replace('.', '_', $column);

        $expr = $this->expr()->like(
            $ignoreCase ? $this->expr()->lower($column) : $column,
            $ignoreCase ? $this->expr()->lower(":$parameter") : ":$parameter",
        );

        if ($not) {
            $expr = $this->expr()->not($expr);
        }

        $and ? $this->andWhere($expr)
            : $this->orWhere($expr);

        $this->setParameter($parameter, "%$value%");

        return $this;
    }

    /**
     * Adds a where (like) statement for the `column`. The method takes a string input,
     * splits it into individual words based on whitespace characters (spaces, tabs, newlines),
     * and creates separate SQL LIKE conditions for each word. These conditions are combined
     * with OR operators, allowing for flexible partial matching across multiple words.
     *
     * @param string $column
     * @param mixed $value
     * @param bool $ignoreCase
     * @param bool $and
     * @param bool $not
     *
     * @return self
     */
    public function whereColumnLikeByWords(
        string $column,
        mixed $value,
        bool $ignoreCase = false,
        bool $and = true,
        bool $not = false
    ): self {
        $value = str_replace('%', '\%', $value);
        $words = preg_split('/\s+/', trim($value));
        $conditions = [];

        foreach ($words as $i => $word) {
            $parameter = 'val_' .  str_replace('.', '_', $column) . '_' . $i;

            $expr = $this->expr()->like(
                $ignoreCase ? $this->expr()->lower($column) : $column,
                $ignoreCase ? $this->expr()->lower(":$parameter") : ":$parameter",
            );

            $conditions[] = $expr;
            $this->setParameter($parameter, "%{$word}%");
        }

        $expr = $this->expr()->orX(...$conditions);

        if ($not) {
            $expr = $this->expr()->not($expr);
        }

        $and ? $this->andWhere($expr)
            : $this->orWhere($expr);

        return $this;
    }
}
