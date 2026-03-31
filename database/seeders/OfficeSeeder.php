<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Service;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            ['code' => 'OSDS', 'name' => 'Office of the Schools Division Superintendent', 'services' => [
                'Issuance of Special Order',
                'Issuance of Memorandum',
                'Signing of Documents',
                'Endorsement of Documents',
            ]],
            ['code' => 'ASDS 1', 'name' => 'Office of the Assistant Schools Division Superintendent', 'services' => [
                'Endorsement of Documents',
                'Signing of Documents',
                'Approval of Travel Orders',
            ]],
            ['code' => 'ASDS 2', 'name' => 'Office of the Assistant Schools Division Superintendent, 'services' => [
                'Endorsement of Documents',
                'Signing of Documents',
                'Approval of Travel Orders',
            ]],
            ['code' => 'LEGAL', 'name' => 'Legal Unit', 'services' => [
                'Legal Opinion / Advice',
                'Notarization of Documents',
                'Review of Contracts',
                'Legal Assistance',
            ]],
            ['code' => 'ICTU', 'name' => 'Information and Communications Technology Unit', 'services' => [
                'Technical Support',
                'Network / Internet Concerns',
                'System Access Request',
                'Hardware / Software Request',
                'Website Update Request',
            ]],
            ['code' => 'BUDGET', 'name' => 'Budget Section', 'services' => [
                'Budget Release / Allotment',
                'Budget Verification',
                'Financial Assistance Processing',
                'Review of Budget Documents',
            ]],
            ['code' => 'ACCOUNTING', 'name' => 'Accounting Section', 'services' => [
                'Processing of Claims',
                'Issuance of Certificate of No Accountability',
                'Liquidation Review',
                'Financial Report Request',
            ]],
            ['code' => 'PROCUREMENT', 'name' => 'Procurement Unit', 'services' => [
                'Procurement of Goods',
                'Procurement of Services',
                'Bid Document Processing',
                'Purchase Order Processing',
            ]],
            ['code' => 'ADMIN', 'name' => 'General Services Section', 'services' => [
                'Issuance of Clearance',
                'Facility Reservation',
                'Vehicle Request',
                'General Inquiry',
            ]],
            ['code' => 'PERSONNEL', 'name' => 'Personnel Unit', 'services' => [
                'Issuance of Certificate of Employment',
                'Processing of Leave Applications',
                'Personnel Action Processing',
                'Employee Records Request',
                'Service Record Request',
            ]],
            ['code' => 'RECORDS', 'name' => 'Records Unit', 'services' => [
                'Receiving / Releasing of Documents',
                'File Retrieval',
                'Certification / Authentication of Records',
                'FOI Request',
            ]],
            ['code' => 'SUPPLY', 'name' => 'Supply and Property Unit', 'services' => [
                'Issuance of Supplies',
                'Property / Asset Disposal',
                'Inventory Request',
                'Requisition of Supplies',
            ]],
            ['code' => 'CASH', 'name' => 'Cash/Disbursing Unit', 'services' => [
                'Processing of Salaries / Wages',
                'Cash Advance Processing',
                'Reimbursement Processing',
                'Payroll Inquiry',
            ]],
            ['code' => 'CID', 'name' => 'Curriculum Implementation Division', 'services' => [
                'Curriculum Review',
                'Learning Material Request',
                'Training / Workshop Registration',
                'Instructional Support',
            ]],
            ['code' => 'CID-LRMS', 'name' => 'CID-Learning Resource Management Section', 'services' => [
                'Book / Learning Material Distribution',
                'Library Resource Request',
                'LR Portal Assistance',
                'Textbook Requisition',
            ]],
            ['code' => 'PHYSICAL', 'name' => 'Educational Facilities', 'services' => [
                'School Building Inspection',
                'Facility Repair Request',
                'Construction Project Inquiry',
                'Facility Assessment',
            ]],
            ['code' => 'HRD', 'name' => 'SGOD-Human Resource Development', 'services' => [
                'Training Program Enrollment',
                'Scholarship Application',
                'Professional Development Inquiry',
                'NDAP / NQP Assistance',
            ]],
            ['code' => 'PLANNING', 'name' => 'SGOD-Planning and Research', 'services' => [
                'Research Proposal Review',
                'Data Request',
                'Planning Document Processing',
                'Statistical Report Request',
            ]],
            ['code' => 'HEALTH', 'name' => 'SGOD- Health & Nutrition Unit', 'services' => [
                'Medical / Dental Services',
                'Health Program Enrollment',
                'Nutrition Program Inquiry',
                'Medical Certificate Request',
            ]],
            ['code' => 'SMME', 'name' => 'SGOD-School Management Monitoring and Evaluation Section', 'services' => [
                'School Monitoring Report',
                'Compliance Assessment',
                'Evaluation Request',
                'Quality Assurance Inquiry',
            ]],
            ['code' => 'SOCMOBNET', 'name' => 'SGOD-Social Mobilization and Networking & DRRM', 'services' => [
                'Partnership / MOA Processing',
                'Community Engagement',
                'DRRM Coordination',
                'Stakeholder Meeting Request',
            ]],
            ['code' => 'Office of the Chief ES-SGOD', 'name' => 'SGOD-Office of the Chief ES', 'services' => [
                'Endorsement of Documents',
                'Signing of Documents',
                'General Inquiry',
            ]],
            ['code' => 'LFP', 'name' => 'SGOD-Learner Formation Program Unit', 'services' => [
                'Student Affairs Concern',
                'Discipline Referral',
                'Guidance Counseling Request',
                'Youth Formation Program',
            ]],
        ];

        foreach ($offices as $data) {
            $services = $data['services'];
            unset($data['services']);

            $office = Office::create($data);

            foreach ($services as $serviceName) {
                Service::create([
                    'office_id' => $office->id,
                    'name' => $serviceName,
                ]);
            }
        }
    }
}
