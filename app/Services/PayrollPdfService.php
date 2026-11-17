<?php

namespace App\Services;

use App\Models\Payroll;
use Carbon\Carbon;

class PayrollPdfService
{
    public function generate(Payroll $payroll): string
    {
        $employee = $payroll->employee;
        $employeeName = $employee ? trim($employee->first_name.' '.$employee->last_name) : 'Employee';
        $employeeId = $employee ? (string) ($employee->employee_id ?? $employee->id) : (string) $payroll->employee_id;
        $departmentName = $employee?->department?->name ?? 'N/A';
        $designationName = $employee?->designation?->name ?? 'N/A';
        $monthName = Carbon::create(null, (int) $payroll->month, 1)->format('F');
        $period = $monthName.' '.$payroll->year;

        $basicSalary = (float) $payroll->basic_salary;
        $allowances = (float) $payroll->allowances;
        $grossSalary = $basicSalary + $allowances;
        $totalDeductions = (float) $payroll->deductions;
        $epf = (float) $payroll->EPF;
        $attendanceDeductions = max(0, $totalDeductions - $epf);
        $netSalary = (float) $payroll->net_salary;

        $stream = [];
        // Header background box (Teal)
        $stream[] = '0.07 0.30 0.32 rg 36 730 523 75 re f';

        // Header Text
        $stream[] = '1 1 1 rg';
        $stream[] = 'BT /F2 18 Tf 50 772 Td (HR SYSTEM - SALARY SLIP) Tj ET';
        $stream[] = 'BT /F1 10 Tf 50 754 Td (MONTHLY PAYROLL STATEMENT - '.$this->escape($period).') Tj ET';
        $stream[] = 'BT /F1 9 Tf 50 740 Td (Generated on: '.date('d M Y').') Tj ET';

        // Employee Info Card background
        $stream[] = '0.95 0.96 0.98 rg 36 630 523 85 re f';
        $stream[] = '0.85 0.88 0.90 RG 1 w 36 630 523 85 re s';

        // Employee Info Text
        $stream[] = '0.15 0.20 0.25 rg';
        $stream[] = 'BT /F2 11 Tf 50 695 Td (Employee Details) Tj ET';
        $stream[] = 'BT /F1 10 Tf 50 675 Td (Name: '.$this->escape($employeeName).') Tj ET';
        $stream[] = 'BT /F1 10 Tf 50 655 Td (Employee ID: #'.$this->escape($employeeId).') Tj ET';

        $stream[] = 'BT /F1 10 Tf 300 675 Td (Department: '.$this->escape($departmentName).') Tj ET';
        $stream[] = 'BT /F1 10 Tf 300 655 Td (Designation: '.$this->escape($designationName).') Tj ET';

        // Earnings Section Header
        $stream[] = '0.07 0.30 0.32 rg 36 590 523 24 re f';
        $stream[] = '1 1 1 rg';
        $stream[] = 'BT /F2 10 Tf 50 598 Td (EARNINGS) Tj ET';
        $stream[] = 'BT /F2 10 Tf 460 598 Td (AMOUNT (RS.)) Tj ET';

        // Earnings Rows
        $stream[] = '0.15 0.20 0.25 rg';
        $stream[] = '0.88 0.90 0.92 RG 0.5 w';
        $stream[] = 'BT /F1 10 Tf 50 568 Td (Basic Salary) Tj ET';
        $stream[] = 'BT /F1 10 Tf 460 568 Td ('.number_format($basicSalary, 2).') Tj ET';
        $stream[] = '36 560 m 559 560 l s';

        $stream[] = 'BT /F1 10 Tf 50 543 Td (Allowances / Overtime) Tj ET';
        $stream[] = 'BT /F1 10 Tf 460 543 Td ('.number_format($allowances, 2).') Tj ET';
        $stream[] = '36 535 m 559 535 l s';

        $stream[] = '0.95 0.97 0.96 rg 36 512 523 23 re f';
        $stream[] = '0.07 0.30 0.32 rg';
        $stream[] = 'BT /F2 10 Tf 50 519 Td (Total Gross Earnings) Tj ET';
        $stream[] = 'BT /F2 10 Tf 460 519 Td ('.number_format($grossSalary, 2).') Tj ET';

        // Deductions Section Header
        $stream[] = '0.65 0.15 0.15 rg 36 465 523 24 re f';
        $stream[] = '1 1 1 rg';
        $stream[] = 'BT /F2 10 Tf 50 473 Td (DEDUCTIONS) Tj ET';
        $stream[] = 'BT /F2 10 Tf 460 473 Td (AMOUNT (RS.)) Tj ET';

        // Deductions Rows
        $stream[] = '0.15 0.20 0.25 rg';
        $stream[] = 'BT /F1 10 Tf 50 443 Td (Late / No-Pay / Other Deductions) Tj ET';
        $stream[] = 'BT /F1 10 Tf 460 443 Td ('.number_format($attendanceDeductions, 2).') Tj ET';
        $stream[] = '36 435 m 559 435 l s';

        $stream[] = 'BT /F1 10 Tf 50 418 Td (EPF Contribution (8%)) Tj ET';
        $stream[] = 'BT /F1 10 Tf 460 418 Td ('.number_format($epf, 2).') Tj ET';
        $stream[] = '36 410 m 559 410 l s';

        $stream[] = '0.98 0.95 0.95 rg 36 387 523 23 re f';
        $stream[] = '0.65 0.15 0.15 rg';
        $stream[] = 'BT /F2 10 Tf 50 394 Td (Total Deductions) Tj ET';
        $stream[] = 'BT /F2 10 Tf 460 394 Td ('.number_format($totalDeductions, 2).') Tj ET';

        // Net Salary Box
        $stream[] = '0.07 0.30 0.32 rg 36 325 523 45 re f';
        $stream[] = '1 1 1 rg';
        $stream[] = 'BT /F2 13 Tf 50 342 Td (NET SALARY RECEIVED) Tj ET';
        $stream[] = 'BT /F2 14 Tf 420 342 Td (Rs. '.number_format($netSalary, 2).') Tj ET';

        // Footer notice
        $stream[] = '0.50 0.55 0.60 rg';
        $stream[] = 'BT /F1 8 Tf 50 280 Td (This is an electronically generated salary payslip produced by HR System. No signature is required.) Tj ET';

        $content = implode("\n", $stream);
        $contentLength = strlen($content);

        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[2] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[3] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >>';
        $objects[4] = "<< /Length {$contentLength} >>\nstream\n{$content}\nendstream";
        $objects[5] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        $objects[6] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>';

        $out = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];
        foreach ($objects as $num => $obj) {
            $offsets[$num] = strlen($out);
            $out .= "{$num} 0 obj\n{$obj}\nendobj\n";
        }

        $xrefOffset = strlen($out);
        $out .= 'xref'."\n0 ".(count($objects) + 1)."\n";
        $out .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $out .= 'trailer'."\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $out .= "startxref\n{$xrefOffset}\n%%EOF";

        return $out;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
