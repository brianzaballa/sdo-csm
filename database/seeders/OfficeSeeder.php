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
            ['code' => 'CID', 'name' => 'Curriculum and Instruction Division', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Borrowing and Returning of Supplementary Learning Resources from Library Hub'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Registration for an Account in the LRMDS Portal'],

                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Quality Assurance of Supplementary Learning Resource'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Submission of Contextualized Learning Resources for Quality Assurance'],
            ]],
            ['code' => 'Cash', 'name' => 'Admin Unit – Cash', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Claiming of Checks for Payment of Obligation (made through Checks)'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Handling of Cash Advances'],
            ]],
            ['code' => 'Budget', 'name' => 'Finance Unit – Budget', 'services' => [
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Posting/Updating of Disbursement'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Processing of Obligation Request and Status (ORS)'],
            ]],

            ['code' => 'ICT', 'name' => 'ICT Unit', 'services' => [
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'User Account Management for Centrally Managed Systems'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Troubleshooting of ICT Equipment'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Uploading of Publications'],
            ]],

            ['code' => 'Legal', 'name' => 'Legal Unit', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Correction of Entries in School Records'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Legal Assistance to Walk-in Clients'],

                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Issuance of Certificate of No Pending Case'],
            ]],

            ['code' => 'Personnel', 'name' => 'Personnel Unit', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Acceptance of Application for Reclassification of Teaching Positions'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Acceptance of Employment (Non-Teaching and Teaching Related Positions)'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Certificate of Employment (COE)'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Service Record (SR)'],

                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Application for Equivalent Record Form (ERF)'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Application for Leave'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Application for Retirement'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Issuance of Certificate of Employment (COE)'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Issuance of Foreign Official Travel Authority'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Issuance of Foreign Personal Travel Authority'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Issuance of Service Record (SR)'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Loan Approval and Verification'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Processing of Appointment (Original, Reemployment, Reappointment, Promotion and Transfer)'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Processing of Terminal Leave Benefits'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Request for Correction of Name and Change of Status'],
            ]],
            ['code' => 'Property & Supply', 'name' => 'Property and Supply Unit', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Delivery Inspection and Acceptance of Tangible Assets (Supplies/ Materials/ Equipment) – Central Office (CO)-Procured with Logistics Services'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Delivery Inspection and Acceptance of Tangible Assets (Supplies/ Materials/ Equipment) – Central Office (CO)-Procured'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Delivery Inspection and Acceptance of Tangible Assets (Supplies/ Materials/ Equipment)'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Request for Certificate of Final Acceptance or Certificate of Completion'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Request for Supplier’s Performance Evaluation'],

                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Requisition and Issuance of Supplies'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Property and Equipment Clearance Signing'],
            ]],
            ['code' => 'SGOD - PRS', 'name' => 'School Governance and Operations Division – Planning and Research Section', 'services' => [
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Request for Basic Education Data (Internal Stakeholder)'],
                ['is_active'=>true, 'is_external'=>false, 'service_name'=>'Request for Data for EBEIS/LIS/NAT and Performance Indicators'],
            ]],
            ['code' => 'Records', 'name' => 'Records Unit', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Academic School Record (Referral from the School of the Non-Availability of School Records)'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Academic School Records for Certification, Authentication, and Verification (CAV) of ALS and PEPT Completers/Passers'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Academic School Records for Certification, Authentication, and Verification (CAV) for Learners from Closed Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Requested Documents – Walk-In'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Issuance of Requested Documents (Online)'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Handling of Incoming Communications'],
            ]],
            ['code' => 'SGOD', 'name' => 'School Governance and Operations Division', 'services' => [
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for the Issuance of Government Recognition to Private Schools (Kindergarten, Elementary, and Junior High School levels)'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for the Issuance of Government Permit to Operate for the Opening/Establishment of New Kindergarten, Elementary (Grades 1 to 6) and Junior High School (Grades 7 to 10) Levels/Additional Grade Level for Elementary (Grades 1 to 6) and Junior High School (Grades 7 to 10) for Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for the Issuance of Special Orders (SO) for Graduation of Private School Learners'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for the Opening/Additional Offering of Senior High School (SHS) Program for Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for the Renewal of Government Permit to Operate of Kindergarten, Elementary (Grades 1 to 6) and Junior High School (Grades 7 to 10) Levels of Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application of Summer Permit for Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for Tuition and Other Fees Increase, No Increase, and Proposed New Fees of Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Application for Voluntary (Temporary/Permanent) Closure of Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Endorsement of Voluntary (Temporary/Permanent) Closure of Private Schools'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Recognition of Professional Development (PD) Programs – SDO Level'],
                ['is_active'=>true, 'is_external'=>true, 'service_name'=>'Request for Basic Education Data'],
            ]],

        ];

        foreach ($offices as $data) {

            $services = $data['services'];

            unset($data['services']);

            $office = Office::create($data);

            foreach ($services as $service) {
                Service::create([
                    'office_id' => $office->id,
                    'name' => $service['service_name'],
                    'is_external' => $service['is_external'],
                ]);
            }
        }
    }
}
