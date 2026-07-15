<?php

namespace App\Console\Commands;

use App\Models\AdminDoc;
use Illuminate\Console\Command;

class AdminDocSyncKeyword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminDoc:sync-keyword
        {--dry-run : Dry run mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync admin document keywords with their respective category keywords';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $getAdminDoc = AdminDoc::with('adminDocCategory')
            ->select('id', 'admin_doc_category_id')
            ->get();

        $data = [];
        foreach ($getAdminDoc as $item) {
            $data[] = [
                'id' => $item->id,
                'keyword' => ["{$item->adminDocCategory->name}"] ?? null,
            ];
        }
        // $getAdminDoc = \DB::table('tp_2_admin_docs')
        //     ->join('tm_admin_doc_categories', 'tp_2_admin_docs.admin_doc_category_id', '=', 'tm_admin_doc_categories.id')
        //     ->select('tp_2_admin_docs.id', 'tm_admin_doc_categories.name as category_name', 'tm_admin_doc_categories.id as category_id')
        //     ->orderBy('tp_2_admin_docs.id', 'asc')
        //     ->get();

        if ($dryRun) {
            $this->warn(
                "DRY RUN MODE - No changes will be made"
            );

            // foreach ($getAdminDoc as $item) {
            //     $this->info(
            //         "Admin Doc ID: {$item->id},
            //         Category ID: {$item->adminDocCategory->id},
            //         Category Name: {$item->adminDocCategory->name}"
            //     );
            // }

            foreach ($data as $item) {
                $this->info(
                    "Admin Doc ID: {$item['id']},
                    Keyword: {$item['keyword'][0]}"
                );
            }
        }else{
            $this->info("Syncing admin document keywords with their respective category keywords");

            // Test update only first data
            $firstData = $data[0];

            $adminDoc = AdminDoc::where('id', $firstData['id'])->first();
            $this->info($adminDoc);

            if ($adminDoc) {
                $keyword = $adminDoc->keyword ?? [];

                // update index 0
                $keyword[0] = $firstData['keyword'][0];

                $adminDoc->update([
                    'keyword' => $keyword,
                ]);

                $this->info(
                    "Updated Admin Doc ID: {$adminDoc->id}, Keyword: {$keyword[0]}"
                );
                $this->info($adminDoc);
            } else {
                $this->error("Admin Doc not found");
            }
        }
    }
}
