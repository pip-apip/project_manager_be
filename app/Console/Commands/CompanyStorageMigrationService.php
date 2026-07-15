<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CompanyStorageMigrationService
{
    /**
     * @param Command $command
     */
    // public function migrate(Command $command)
    // {
    //     $companies = Company::all();
    //     $noCountDirectorSignature = 0;
    //     $noCountLetterHead = 0;

    //     foreach ($companies as $company) {
    //         $command->line("====================================================================");
    //         $command->line("Company #{$company->id}");
    //         $command->newLine();

    //         // Director Signature
    //         $pathDirectorOld = $company->director_signature;
    //         if ($pathDirectorOld) {
    //             $fileName = basename($pathDirectorOld);
    //             $pathDirectorNew = "companies/{$company->id}/director_signature/{$fileName}";

    //             $command->info('Director Signature');
    //             $command->comment('FROM');
    //             $command->line($pathDirectorOld);
    //             $command->comment('TO');
    //             $command->line($pathDirectorNew);
    //             $command->newLine();

    //             $exists = Storage::disk('public')->exists($pathDirectorOld);
    //             $command->line("File exists: " . ($exists ? 'Yes' : 'No'));
    //             if (!$exists) {
    //                 $noCountDirectorSignature++;
    //             }
    //             $command->newLine(1);
    //         }

    //         // Letter Head
    //         $pathLetterHeadOld = $company->letter_head;
    //         if ($pathLetterHeadOld) {
    //             $fileName = basename($pathLetterHeadOld);
    //             $pathLetterHeadNew = "companies/{$company->id}/letter_head/{$fileName}";

    //             $command->info('Letter Head');
    //             $command->comment('FROM');
    //             $command->line($pathLetterHeadOld);
    //             $command->comment('TO');
    //             $command->line($pathLetterHeadNew);
    //             $command->newLine();

    //             $exists = Storage::disk('public')->exists($pathLetterHeadOld);
    //             $command->line("File exists: " . ($exists ? 'Yes' : 'No'));
    //             if (!$exists) {
    //                 $noCountLetterHead++;
    //             }
    //             $command->newLine();
    //         }
    //     }

    //     $command->comment("===================================================================");
    //     $command->info("Summary : ");
    //     $command->line("Total Companies : " . $companies->count());
    //     $command->line("Total Companies with missing Director Signature : " . $noCountDirectorSignature);
    //     $command->line("Total Companies with missing Letter Head : " . $noCountLetterHead);
    //     $command->comment("===================================================================");
    //     $command->newLine();
    // }

    protected array $errors = [];
    protected int $success = 0;
    protected int $failed = 0;
    protected int $skipped = 0;


    public function migrate(bool $dryRun = false, $output = null): array
    {
        $companies = Company::all();

        if ($output) {
            $output->progressStart($companies->count());
        }

        foreach ($companies as $company) {

            try {

                $this->processCompany(
                    $company,
                    $dryRun
                );

            } catch (Throwable $e) {

                $this->failed++;

                $this->errors[] = [
                    'company_id' => $company->id,
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
            'total' => $companies->count(),
            'success' => $this->success,
            'failed' => $this->failed,
            'skipped' => $this->skipped,
            'errors' => $this->errors
        ];
    }



    protected function processCompany(
        Company $company,
        bool $dryRun
    ): void {

        $files = [
            [
                'column' => 'director_signature',
                'folder' => 'director_signature'
            ],
            [
                'column' => 'letter_head',
                'folder' => 'letter_head'
            ]
        ];


        foreach ($files as $file) {

            $oldPath = $company->{$file['column']};


            if (!$oldPath) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Skip jika sudah menggunakan struktur baru
            |--------------------------------------------------------------------------
             */

            if (
                str_contains(
                    $oldPath,
                    "companies/{$company->id}/"
                )
            ) {

                $this->skipped++;

                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | File tidak ditemukan
            |--------------------------------------------------------------------------
             */

            if (!Storage::disk('public')->exists($oldPath)) {

                $this->errors[] = [
                    'company_id' => $company->id,
                    'type' => $file['column'],
                    'message' => "File tidak ditemukan: {$oldPath}"
                ];

                $this->failed++;

                continue;
            }



            $fileName = basename($oldPath);



            $newPath =
                "companies_docs/{$company->id}/{$file['folder']}/{$fileName}";



            /*
            |--------------------------------------------------------------------------
            | DRY RUN
            |--------------------------------------------------------------------------
             */

            if ($dryRun) {

                echo "\nCompany {$company->id}\n";

                echo "FROM : {$oldPath}\n";

                echo "TO   : {$newPath}\n";

                echo "READY\n";

                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | Move file
            |--------------------------------------------------------------------------
             */

            $moved = Storage::disk('public')
                ->move(
                    $oldPath,
                    $newPath
                );


            if (!$moved) {

                $this->failed++;

                $this->errors[] = [
                    'company_id' => $company->id,
                    'type' => $file['column'],
                    'message' => "Gagal memindahkan {$oldPath}"
                ];

                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | Update database setelah move berhasil
            |--------------------------------------------------------------------------
             */

            $company->update([
                $file['column'] => $newPath
            ]);


            $this->success++;
        }
    }
}
