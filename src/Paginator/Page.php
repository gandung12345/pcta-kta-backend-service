<?php

declare(strict_types=1);

namespace Schnell\Paginator;

/**
 * @psalm-api
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
final class Page implements PageInterface
{
    /**
     * @var int
     */
    private int $totalCount;

    /**
     * @var int
     */
    private int $page;

    /**
     * @var int
     */
    private int $perPage;

    /**
     * @var int
     */
    private int $offset;

    /**
     * @var int
     */
    private int $totalPage;

    /**
     * @psalm-api
     * @return static
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTotalPage(): int
    {
        return $this->totalPage;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setTotalPage(int $totalPage): void
    {
        $this->totalPage = $totalPage;
    }
}
