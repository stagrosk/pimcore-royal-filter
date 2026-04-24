<?php

namespace App\Component\BatchProcessing;

use Countable;
use Iterator;
use OpenDxp\Model\Listing\AbstractListing;

final class BatchListing implements Iterator, Countable
{
    private AbstractListing $list;

    private int $batchSize;

    private int $index = 0;

    private int $loop = 0;

    private int $total = 0;

    private array $items = [];

    /**
     * @param \Pimcore\Model\Listing\AbstractListing $list
     * @param int $batchSize
     */
    public function __construct(AbstractListing $list, int $batchSize)
    {
        $this->list = $list;
        $this->batchSize = $batchSize;

        $this->list->setLimit($batchSize);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        return $this->items[$this->index];
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->index++;

        if ($this->index >= $this->batchSize) {
            $this->index = 0;
            $this->loop++;

            $this->load();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function key(): mixed
    {
        return ($this->index + 1) * ($this->loop + 1);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return isset($this->items[$this->index]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->index = 0;
        $this->loop = 0;

        $this->load();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        if (!$this->total) {
            $dao = $this->list->getDao();

            if (!method_exists($dao, 'getTotalCount')) {
                throw new \InvalidArgumentException(sprintf('%s listing class does not support count.', get_class($this->list)));
            }

            $this->total = $dao->getTotalCount();
        }

        return $this->total;
    }

    /**
     * Load all items based on current state.
     */
    private function load(): void
    {
        $this->list->setOffset($this->loop * $this->batchSize);

        $dao = $this->list->getDao();

        if (!method_exists($dao, 'load')) {
            throw new \InvalidArgumentException(sprintf('%s listing class does not support load.', get_class($this->list)));
        }

        $this->items = $dao->load();
    }
}
