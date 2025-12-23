<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class CloseExpiredTickets extends Command
{
    protected $signature = 'tickets:close-expired';
    protected $description = 'Auto close tickets that exceed SLA';

    public function handle(): void
    {
        Ticket::whereNull('first_response_at')
            ->whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->update([
                'status' => 'closed',
            ]);

        $this->info('Expired tickets closed.');
    }
}
