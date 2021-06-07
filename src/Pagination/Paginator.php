<?php

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator
{
    const PAGE_SIZE = 3;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var array
     */
    private $results;

    /**
     * Liczba wszystkich wynikow
     * @var int
     */
    private $numResults;

    public function __construct(QueryBuilder $queryBuilder, int $pageSize = self::PAGE_SIZE)
    {
        $this->queryBuilder = $queryBuilder;
        // limit
        $this->pageSize = $pageSize;
    }

    public function paginate(int $page = 1)
    {
        $this->currentPage = max(1, $page);
        // offset
        $firstResult = ($this->currentPage - 1) * $this->pageSize;

        // dodaj offset i limit do zapytania
        $query = $this->queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($this->pageSize)
            ->getQuery();

        $paginator = new DoctrinePaginator($query, true);

        $this->results = $paginator->getIterator();
        $this->numResults = $paginator->count();

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Ile wynikow na stronie
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Liczba wszystkich wynikow
     * @return int
     */
    public function getNumResults(): int
    {
        return $this->numResults;
    }

    /**
     * Wyniki
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Jaka jest ostatnia strona
     * @return int
     */
    public function getLastPage(): int
    {
        return (int) ceil($this->numResults / $this->pageSize);
    }

    /**
     * Czy w ogole jest co stronicowac
     * @return bool
     */
    public function hasToPaginate(): bool
    {
        return $this->numResults > $this->pageSize;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }
}
