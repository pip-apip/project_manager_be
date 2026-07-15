<?php

namespace App\Console\Commands;

use App\Console\Commands\AdminDocStorageMigrationService;
use Illuminate\Console\Command;


class AdminDocMigrateStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =
        'adminDoc:migrate-storage
        {--dry-run : Simulate migration without moving files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate admin document storage directory structure';

    /**
     * Execute the console command.
     */

    public function handle(AdminDocStorageMigrationService $service)
    {
        $dryRun = $this->option('dry-run');

        $this->info(
            "AdminDoc Storage Migration"
        );

        if ($dryRun) {
            $this->warn(
                "DRY RUN MODE - No files will be moved"
            );
        }

        if (!$dryRun) {
            if (!$this->confirm(
                'Continue migration?'
            )) {
                $this->info(
                    'Migration cancelled'
                );

                return Command::SUCCESS;
            }
        }

        $result = $service->migrate(
            $dryRun,
            $this->output
        );

        $this->newLine();

        $this->info(
            'Migration Finished'
        );

        $this->table(
            ['Total', 'Success', 'Failed', 'Skipped'],
            [
                [
                    $result['total'],
                    $result['success'],
                    $result['failed'],
                    $result['skipped'],
                ],
            ]
        );

        if (count($result['errors'])) {
            $this->error('ERROR LIST');
            foreach ($result['errors'] as $error) {
                $this->line("AdminDoc {$error['admin_doc_id']} | {$error['type']} | {$error['message']}");
            }
        }

        return Command::SUCCESS;
    }
}
