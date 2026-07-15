<?php

namespace App\Console\Commands;

use App\Models\AdminDoc;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdminDocStorageMigrationService
{
    protected array $errors = [];
    protected int $failed = 0;
    protected int $success = 0;
    protected int $skipped = 0;
    protected array $dataSkipped = [];

    public function migrate(bool $dryRun = false, $output = null): array
    {
        $adminDocs = AdminDoc::all();

        if ($output) {
            $output->progressStart($adminDocs->count());
        }

        foreach ($adminDocs as $adminDoc) {

            try {

                $this->processAdminDoc(
                    $adminDoc,
                    $dryRun
                );

            } catch (Throwable $e) {

                $this->failed++;

                $this->errors[] = [
                    'admin_doc_id' => $adminDoc->id,
                    'type' => 'general',
                    'message' => $e->getMessage()
                ];
            }


            if ($output) {
                $output->progressAdvance();
            }
        }


        if ($output) {
            $output->progressFinish();
        }


        return [
            'total' => $adminDocs->count(),
            'success' => $this->success,
            'failed' => $this->failed,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'dataSkipped' => $this->dataSkipped
        ];
    }

    protected function processAdminDoc(
        AdminDoc $adminDoc,
        bool $dryRun
    ): void {

        $oldPath = $adminDoc->file;
        $project_year = Carbon::parse($adminDoc->project->start_date)->format('Y');
        $project_id = $adminDoc->project_id;
        $fileName = basename($oldPath);
        $newPath = "projects_docs/{$project_year}/{$project_id}/{$fileName}";

        if (!$oldPath) {
            return;
        }

        if (
            str_contains(
                $oldPath,
                'projects_docs/' . $project_year . '/' . $project_id . '/'
                )
        ) {
            $this->errors[] = [
                'admin_doc_id' => $adminDoc->id,
                'type' => 'structure',
                'message' => "File already in the correct structure: {$oldPath}"
            ];
            $this->skipped++;
            return;
        }

        if (!Storage::disk('public')->exists($oldPath)) {
            $this->errors[] = [
                'admin_doc_id' => $adminDoc->id,
                'type' => 'file_not_found',
                'message' => "File not found at path: {$oldPath}"
            ];
            $this->failed++;
            return;
        }

        if ($dryRun) {
            echo "\nAdminDoc {$adminDoc->id}\n";
            echo "FROM : {$oldPath}\n";
            echo "TO   : {$newPath}\n";
            return;
        }

        $moved = Storage::disk('public')->move(
            $oldPath,
            $newPath
        );

        if (!$moved) {
            $this->errors[] = [
                'admin_doc_id' => $adminDoc->id,
                'type' => 'file_not_found',
                'message' => "File not found at path: {$oldPath}"
            ];
            $this->failed++;
            return;
        }

        $save = $adminDoc->update([
            'file' => $newPath
        ]);

        if (!$save) {
            $this->errors[] = [
                'admin_doc_id' => $adminDoc->id,
                'type' => 'update_failed',
                'message' => "Failed to update AdminDoc record with new file path: {$newPath}"
            ];
            $this->failed++;
            return;
        }

        $this->success++;
    }
}
