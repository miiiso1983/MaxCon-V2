<?php

namespace App\Imports;

use App\Models\Tenant\HR\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToCollection, WithHeadingRow
{
    protected $tenantId;
    protected $options;

    protected $created = 0;
    protected $skipped = 0;
    protected $failed = 0;
    protected $errors = [];

    protected $nextNumber;

    public function __construct($tenantId, array $options = [])
    {
        $this->tenantId = $tenantId;
        $this->options = array_merge([
            'skip_duplicates' => true,
            'validate_emails' => true,
            'send_welcome_email' => false,
        ], $options);

        // Initialize next employee code number from last existing code
        $last = Employee::where('tenant_id', $this->tenantId)->orderBy('id', 'desc')->first();
        if ($last && !empty($last->employee_code) && preg_match('/(\d+)/', $last->employee_code, $m)) {
            $this->nextNumber = (int)$m[1] + 1;
        } else {
            $this->nextNumber = 1;
        }
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Normalize keys to snake_case
            $data = [];
            foreach ($row->toArray() as $k => $v) {
                $data[Str::snake(trim((string)$k))] = is_string($v) ? trim($v) : $v;
            }

            // Skip header-like or empty rows (require at least first_name and last_name)
            if (empty($data['first_name']) && empty($data['last_name'])) {
                continue;
            }

            // Basic mapping and normalization
            $data['gender'] = isset($data['gender']) ? strtolower($data['gender']) : null;
            $data['employment_type'] = isset($data['employment_type']) ? strtolower($data['employment_type']) : null;
            if (isset($data['marital_status'])) {
                $data['marital_status'] = strtolower($data['marital_status']);
            }

            // Coerce dates
            foreach (['date_of_birth', 'hire_date'] as $dateField) {
                if (!empty($data[$dateField])) {
                    try {
                        $data[$dateField] = Carbon::parse($data[$dateField])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $data[$dateField] = null;
                    }
                }
            }

            // Per-row validation rules
            $rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'national_id' => 'required|string',
                'email' => 'required|email',
                'mobile' => 'required|string|max:20',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'department_id' => 'required|integer',
                'position_id' => 'required|integer',
                'hire_date' => 'required|date',
                'basic_salary' => 'required|numeric|min:0',
                'employment_type' => 'required|in:full_time,part_time,contract,internship,consultant',
            ];

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $this->failed++;
                $this->errors[] = 'Row ' . ($index + 2) . ': ' . implode(' | ', $validator->errors()->all());
                continue;
            }

            // Duplicate checks
            $duplicate = Employee::where('tenant_id', $this->tenantId)
                ->where(function ($q) use ($data) {
                    $q->where('email', $data['email'])
                      ->orWhere('national_id', $data['national_id']);
                })
                ->exists();

            if ($duplicate && ($this->options['skip_duplicates'] ?? false)) {
                $this->skipped++;
                continue;
            }

            if ($duplicate && !($this->options['skip_duplicates'] ?? false)) {
                $this->failed++;
                $this->errors[] = 'Row ' . ($index + 2) . ': duplicate email or national_id';
                continue;
            }

            // Default fields
            $data['tenant_id'] = $this->tenantId;
            $data['created_by'] = auth()->id();
            $data['employment_status'] = $data['employment_status'] ?? 'active';
            if (empty($data['employee_code'])) {
                $data['employee_code'] = 'EMP' . str_pad((string)$this->nextNumber, 4, '0', STR_PAD_LEFT);
                $this->nextNumber++;
            }

            try {
                Employee::create($data);
                $this->created++;
            } catch (\Throwable $e) {
                $this->failed++;
                $this->errors[] = 'Row ' . ($index + 2) . ': DB error - ' . $e->getMessage();
            }
        }
    }

    public function getSummary(): array
    {
        return [
            'created' => $this->created,
            'skipped' => $this->skipped,
            'failed' => $this->failed,
            'errors' => $this->errors,
        ];
    }
}

