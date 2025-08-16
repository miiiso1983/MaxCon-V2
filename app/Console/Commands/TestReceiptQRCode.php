<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Services\Accounting\ReceiptService;
use Illuminate\Support\Facades\Auth;

class TestReceiptQRCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:receipt-qr {--payment-id= : Test specific payment ID} {--create-test : Create test payment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test QR code generation for payment receipts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Receipt QR Code Generation...');
        
        // Test QR libraries availability
        $this->testQRLibraries();
        
        if ($this->option('create-test')) {
            $payment = $this->createTestPayment();
            if (!$payment) {
                $this->error('Failed to create test payment');
                return 1;
            }
        } else {
            $paymentId = $this->option('payment-id');
            if ($paymentId) {
                $payment = InvoicePayment::find($paymentId);
                if (!$payment) {
                    $this->error("Payment with ID {$paymentId} not found");
                    return 1;
                }
            } else {
                $payment = InvoicePayment::with(['invoice.customer', 'invoice.tenant'])->latest()->first();
                if (!$payment) {
                    $this->error('No payments found. Use --create-test to create a test payment');
                    return 1;
                }
            }
        }
        
        $this->info("Testing payment ID: {$payment->id}");
        $this->info("Receipt number: {$payment->receipt_number}");
        $this->info("Amount: " . number_format((float)$payment->amount, 2) . " IQD");
        
        // Test QR code generation
        $this->testQRGeneration($payment);
        
        // Test receipt service
        $this->testReceiptService($payment);
        
        $this->info('✅ QR Code testing completed!');
        return 0;
    }
    
    private function testQRLibraries()
    {
        $this->info('📚 Testing QR Code Libraries...');
        
        // Test SimpleSoftwareIO QrCode
        if (class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode')) {
            $this->info('✅ SimpleSoftwareIO QrCode library is available');
            
            try {
                $testData = 'Test QR Code Data';
                $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate($testData);
                $this->info('✅ SimpleSoftwareIO QrCode PNG generation works');
                
                $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($testData);
                $this->info('✅ SimpleSoftwareIO QrCode SVG generation works');
            } catch (\Throwable $e) {
                $this->error('❌ SimpleSoftwareIO QrCode generation failed: ' . $e->getMessage());
            }
        } else {
            $this->error('❌ SimpleSoftwareIO QrCode library not found');
        }
        
        // Test external API
        $this->info('🌐 Testing external QR API...');
        try {
            $testUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode('Test API QR');
            $context = stream_context_create([
                'http' => ['timeout' => 5, 'user_agent' => 'MaxCon Test']
            ]);
            $result = @file_get_contents($testUrl, false, $context);
            if ($result !== false) {
                $this->info('✅ External QR API is accessible');
            } else {
                $this->error('❌ External QR API is not accessible');
            }
        } catch (\Throwable $e) {
            $this->error('❌ External QR API test failed: ' . $e->getMessage());
        }
    }
    
    private function testQRGeneration($payment)
    {
        $this->info('🎯 Testing QR Code Data Generation...');
        
        $invoice = $payment->invoice;
        $qrData = [
            'type' => 'payment_receipt',
            'receipt_number' => $payment->receipt_number,
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'tenant' => $invoice->tenant->name ?? 'Test Tenant',
            'customer' => optional($invoice->customer)->name ?? 'Test Customer',
            'amount' => (float) $payment->amount,
            'currency' => 'IQD',
            'payment_method' => $payment->payment_method,
            'payment_date' => optional($payment->payment_date)->format('Y-m-d') ?? now()->format('Y-m-d'),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        $qrJsonData = json_encode($qrData, JSON_UNESCAPED_UNICODE);
        $this->info('QR Data size: ' . strlen($qrJsonData) . ' bytes');
        
        if (strlen($qrJsonData) > 2000) {
            $this->warn('⚠️  QR data is large (' . strlen($qrJsonData) . ' bytes). May cause scanning issues.');
        }
        
        // Test different QR generation methods
        $this->testQRMethod1($qrJsonData);
        $this->testQRMethod2($qrJsonData);
        $this->testQRMethod3($payment);
    }
    
    private function testQRMethod1($qrData)
    {
        $this->info('🔧 Method 1: SimpleSoftwareIO QrCode PNG...');
        try {
            if (class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode')) {
                $qrPng = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(220)->margin(1)->generate($qrData));
                $this->info('✅ PNG QR generated successfully (' . strlen($qrPng) . ' chars base64)');
            } else {
                $this->error('❌ SimpleSoftwareIO QrCode not available');
            }
        } catch (\Throwable $e) {
            $this->error('❌ Method 1 failed: ' . $e->getMessage());
        }
    }
    
    private function testQRMethod2($qrData)
    {
        $this->info('🔧 Method 2: External API PNG...');
        try {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&format=png&data=' . urlencode($qrData);
            $context = stream_context_create([
                'http' => ['timeout' => 10, 'user_agent' => 'MaxCon Receipt System']
            ]);
            $qrImageData = @file_get_contents($qrUrl, false, $context);
            if ($qrImageData !== false) {
                $qrPng = base64_encode($qrImageData);
                $this->info('✅ External API QR generated successfully (' . strlen($qrPng) . ' chars base64)');
            } else {
                $this->error('❌ External API failed to return data');
            }
        } catch (\Throwable $e) {
            $this->error('❌ Method 2 failed: ' . $e->getMessage());
        }
    }
    
    private function testQRMethod3($payment)
    {
        $this->info('🔧 Method 3: Simple text fallback...');
        try {
            $simpleData = "سند استلام رقم: {$payment->receipt_number}\nالمبلغ: " . number_format((float)$payment->amount, 2) . " د.ع\nالتاريخ: " . ($payment->payment_date ? $payment->payment_date->format('Y-m-d') : now()->format('Y-m-d'));
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&format=png&data=' . urlencode($simpleData);
            $qrImageData = @file_get_contents($qrUrl);
            if ($qrImageData !== false) {
                $qrPng = base64_encode($qrImageData);
                $this->info('✅ Simple text QR generated successfully (' . strlen($qrPng) . ' chars base64)');
            } else {
                $this->error('❌ Simple text QR generation failed');
            }
        } catch (\Throwable $e) {
            $this->error('❌ Method 3 failed: ' . $e->getMessage());
        }
    }
    
    private function testReceiptService($payment)
    {
        $this->info('📄 Testing ReceiptService...');
        try {
            $receiptService = app(ReceiptService::class);
            $pdfPath = $receiptService->generatePdf($payment);
            $this->info('✅ Receipt PDF generated: ' . $pdfPath);
        } catch (\Throwable $e) {
            $this->error('❌ ReceiptService failed: ' . $e->getMessage());
        }
    }
    
    private function createTestPayment()
    {
        $this->info('🏗️  Creating test payment...');
        
        // Find or create test invoice
        $invoice = Invoice::with(['customer', 'tenant'])->where('remaining_amount', '>', 0)->first();
        if (!$invoice) {
            $this->error('No invoice with remaining amount found');
            return null;
        }
        
        $amount = min(100.00, (float)$invoice->remaining_amount);
        
        $payment = $invoice->payments()->create([
            'amount' => $amount,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'reference_number' => 'TEST-' . now()->format('YmdHis'),
            'notes' => 'Test payment for QR code testing',
            'created_by' => 1,
        ]);
        
        $payment->receipt_number = InvoicePayment::generateReceiptNumber($invoice->tenant_id);
        $payment->save();
        
        $this->info("✅ Test payment created: ID {$payment->id}, Amount: {$amount} IQD");
        return $payment;
    }
}
