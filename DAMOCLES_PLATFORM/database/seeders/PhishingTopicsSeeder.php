<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhishingTopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('phishing_topics')->insert([
            ['description' => 'Banking/Financial', 'subject_email' => 'Alert: Suspicious Activity Detected on Your Account', 'body_email' => 'Dear -name -surname, your account has been flagged for suspicious activity. Please verify your identity by clicking here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Banking/Financial', 'subject_email' => 'Important: Verify Your Identity to Secure Your Account', 'body_email' => 'Hello -name, we noticed unusual transactions on your account. Secure your account now: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Online accounts', 'subject_email' => 'Security Alert: Account Compromised – Immediate Action Required', 'body_email' => 'Hi -name, your -email account has been compromised. Reset your password immediately: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Online accounts', 'subject_email' => 'Important: Verify Your Account for Unauthorized Access', 'body_email' => 'Dear -name, we detected a login attempt from an unknown device. Verify your account: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Online shopping', 'subject_email' => 'Action Required: Confirm Your Recent Order to Avoid Cancellation', 'body_email' => 'Hello -name, your recent order needs confirmation. Avoid cancellation by clicking here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Online shopping', 'subject_email' => 'Exclusive Offer: Claim Your Special Discount Now', 'body_email' => 'Hi -name -surname, you have won a special discount! Claim your offer now: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Technical support', 'subject_email' => 'Urgent: System at Risk – Download Latest Security Update', 'body_email' => 'Dear -name, your system is at risk. Download the latest security update here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Technical support', 'subject_email' => 'Warning: Malware Detected on Your Account – Immediate Action Needed', 'body_email' => 'Hello -name, your -email is infected with malware. Clean your account now: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Work/Career', 'subject_email' => 'Exciting Job Opportunity Awaits You – Apply Now', 'body_email' => 'Hi -name, we reviewed your profile and have a job offer for you! Apply now: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Work/Career', 'subject_email' => 'Important: Confirm Your Details for Your Recent Job Application', 'body_email' => 'Dear -name -surname, your application for the position has been received. Confirm your details here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Competitions/Awards', 'subject_email' => 'Congratulations! You’ve Won Our Monthly Giveaway', 'body_email' => 'Congratulations -name! You have won our monthly giveaway! Claim your prize here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Competitions/Awards', 'subject_email' => 'Exclusive Reward: Verify Your Eligibility for a Special Prize', 'body_email' => 'Hi -name, you have been selected for an exclusive reward. Verify your eligibility now: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Personal safety', 'subject_email' => 'Urgent: Secure Your Personal Details from Potential Threats', 'body_email' => 'Dear -name, we detected unusual activity related to your personal details. Secure your data: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Personal safety', 'subject_email' => 'Immediate Attention Needed: Your Personal Data May Be at Risk', 'body_email' => 'Hello -name, your private information might be exposed. Check your security settings here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Healthcare', 'subject_email' => 'Important: View Your Recent Test Results Securely', 'body_email' => 'Hi -name, your recent test results are available. View them securely here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Healthcare', 'subject_email' => 'Action Needed: Review Your Test Results for Health Concerns', 'body_email' => 'Hi -name, your recent test results show an important deseases. View them securely here: -clickHere.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
