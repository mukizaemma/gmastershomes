<?php

namespace App\Console\Commands;

use App\Services\HostingBilling;
use Illuminate\Console\Command;

class RenewHostingCommand extends Command
{
    protected $signature = 'hosting:renew';

    protected $description = 'Issue the 1 October hosting invoice and email renewal reminders';

    public function handle(HostingBilling $billing): int
    {
        $billing->sync();
        $this->info('Hosting renewal check complete.');

        return self::SUCCESS;
    }
}
