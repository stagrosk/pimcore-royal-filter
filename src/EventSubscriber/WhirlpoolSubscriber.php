<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use OpenDxp\Model\DataObject\Whirlpool;

/**
 * Notifies Vendure (via /pimcore/trigger-sync) on Whirlpool POST_ADD / POST_UPDATE / POST_DELETE.
 * The pimcore-bridge worker matches class === "Whirlpool" and dispatches the ES reindex.
 *
 * Publish/unpublish + CLI semantics are inherited from AbstractWebhookSubscriber:
 *  - unpublished save → delete webhook (cleans up Vendure ES doc)
 *  - CLI saves without an admin user are skipped (avoids reindex storms during bulk imports)
 */
class WhirlpoolSubscriber extends AbstractWebhookSubscriber
{
    protected function getObjectClass(): string
    {
        return Whirlpool::class;
    }

    protected function getLogPrefix(): string
    {
        return 'WhirlpoolSubscriber';
    }
}
