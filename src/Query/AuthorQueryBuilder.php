<?php

namespace App\Query;

use App\Entity\Author;

/**
 * Class AuthorQueryBuilder.
 *
 * @package App\Query
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class AuthorQueryBuilder extends BaseQueryBuilder
{
    /**
     * Adds a where statement for the `name` column.
     *
     * @param string $name
     *
     * @return self
     */
    public function whereName(string $name): self
    {
        return $this->andWhere("{$this->alias}.name = :val_name")
            ->setParameter('val_name', $name);
    }

    /**
     * Adds a where (like) statement for the `name` column.
     *
     * @param string $name
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function whereNameLike(string $name, bool $ignoreCase = false): self
    {
        return $this->andWhere(
            $ignoreCase
                ? "LOWER({$this->alias}.name) like LOWER(:val_name)"
                : "{$this->alias}.name like :val_name"
        )
            ->setParameter('val_name', "%{$name}%");
    }

    /**
     * Adds a where (like) statement for the `name` column.
     *
     * @param string $name
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function whereNameLikeByWords(string $name, bool $ignoreCase = false): self
    {
        $words = preg_split('/\s+/', trim($name));
        $conditions = [];

        foreach ($words as $i => $word) {
            $param = "val_name_{$i}";
            $conditions[] = $ignoreCase
                ? "LOWER({$this->alias}.name) LIKE LOWER(:{$param})"
                : "{$this->alias}.name like :{$param}";
            $this->setParameter($param, "%{$word}%");
        }

        return $this->andWhere(implode(' OR ', $conditions));
    }

    /**
     * Adds a where statement for the `name` column.
     *
     * @param string $alias
     *
     * @return self
     */
    public function whereAlias(string $alias): self
    {
        return $this->andWhere("{$this->alias}.alias = :val_alias")
            ->setParameter('val_alias', $alias);
    }

    /**
     * Adds a where (like) statement for the `alias` column.
     *
     * @param string $alias
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function whereAliasLike(string $alias, bool $ignoreCase = false): self
    {
        return $this->andWhere(
            $ignoreCase
                ? "LOWER({$this->alias}.alias) like LOWER(:val_alias)"
                : "{$this->alias}.alias like :val_alias"
        )
            ->setParameter('val_alias', "%{$alias}%");
    }

    /**
     * Adds a where (like) statement for the `alias` column.
     *
     * @param string $alias
     * @param bool $ignoreCase
     *
     * @return self
     */
    public function whereAliasLikeByWords(string $alias, bool $ignoreCase = false): self
    {
        $words = preg_split('/\s+/', trim($alias));
        $conditions = [];

        foreach ($words as $i => $word) {
            $param = "val_alias_{$i}";
            $conditions[] = $ignoreCase
                ? "LOWER({$this->alias}.alias) LIKE LOWER(:{$param})"
                : "{$this->alias}.alias like :{$param}";
            $this->setParameter($param, "%{$word}%");
        }

        return $this->andWhere(implode(' OR ', $conditions));
    }
}
