<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;

class GenerateInvoiceQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-qr-codes {--force : Force regenerate existing QR codes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR codes for all invoices that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');

        $query = Invoice::with(['customer', 'tenant']);

        if (!$force) {
            $query->whereNull('qr_code');
        }

        $invoices = $query->get();

        if ($invoices->isEmpty()) {
            $this->info('No invoices found that need QR codes.');
            return;
        }

        $this->info("Found {$invoices->count()} invoices to process.");

        $bar = $this->output->createProgressBar($invoices->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($invoices as $invoice) {
            try {
                $invoice->generateQrCode();
                $success++;
            } catch (\Exception $e) {
                $this->error("\nFailed to generate QR code for invoice {$invoice->id}: " . $e->getMessage());
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info("QR code generation completed!");
        $this->info("Success: {$success}");

        if ($failed > 0) {
            $this->error("Failed: {$failed}");
        }
    }
}
