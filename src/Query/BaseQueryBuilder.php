<?php

namespace App\Query;

use App\Entity\Author;
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
     * @param bool $and
     *
     * @return self
     */
    public function whereColumn(string $column, mixed $value, bool $and = true): self
    {
        $parameter = 'val_' . str_replace('.', '_', $column);

        $and ? $this->andWhere("$column = :$parameter")
            : $this->orWhere("$column = :$parameter");

        $this->setParameter($parameter, $value);

        return $this;
    }

    /**
     * Adds a where (like) statement for the `column`.
     *
     * @param string $column
     * @param mixed $value
     * @param bool $ignoreCase
     * @param bool $and
     *
     * @return self
     */
    public function whereColumnLike(string $column, mixed $value, bool $ignoreCase = false, bool $and = true): self
    {
        $value = str_replace('%', '\%', $value);
        $parameter = 'val_' . str_replace('.', '_', $column);

        $statement = $ignoreCase
            ? "LOWER($column) like LOWER(:$parameter)"
            : "$column like :$parameter";

        $and ? $this->andWhere($statement)
            : $this->orWhere($statement);

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
     *
     * @return self
     */
    public function whereColumnLikeByWords(string $column, mixed $value, bool $ignoreCase = false, bool $and = true): self
    {
        $value = str_replace('%', '\%', $value);
        $words = preg_split('/\s+/', trim($value));
        $conditions = [];

        foreach ($words as $i => $word) {
            $parameter = 'val_' .  str_replace('.', '_', $column) . '_' . $i;
            $conditions[] = $ignoreCase
                ? "LOWER($column) LIKE LOWER(:{$parameter})"
                : "$column like :{$parameter}";
            $this->setParameter($parameter, "%{$word}%");
        }

        $statement = implode(' OR ', $conditions);

        $and ? $this->andWhere($statement)
            : $this->orWhere($statement);

        return $this;
    }
}
