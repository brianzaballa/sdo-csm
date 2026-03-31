<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            // ── INFO (Step 1) ──────────────────────────────────
            $table->foreignId('office_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('service_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedTinyInteger('age');

            $table->enum('gender', ['Male', 'Female']);

            $table->enum('customer_type', [
                'Business',
                'Citizen',
                'Government',
            ]);

            // ── CITIZEN'S CHARTER (Step 2) ─────────────────────
            // CC1: 1=Know & saw, 2=Know but didn't see,
            //      3=Learned when saw, 4=Didn't know & didn't see
            $table->unsignedTinyInteger('cc1')->nullable();

            // CC2: 1=Easy to see, 2=Somewhat easy, 3=Difficult,
            //      4=Not visible at all, 5=N/A
            $table->unsignedTinyInteger('cc2')->nullable();

            // CC3: 1=Helped very much, 2=Somewhat helped,
            //      3=Did not help, 4=N/A
            $table->unsignedTinyInteger('cc3')->nullable();

            // ── SERVICE QUALITY DIMENSION (Step 3) ────────────
            // Scale: 5=Strongly Agree, 4=Agree,
            //        3=Neither, 2=Disagree, 1=Strongly Disagree,
            //        0=Not Applicable
            // SQD0: I am satisfied with the service that I availed.
            $table->unsignedTinyInteger('sqd0')->nullable();

            // SQD1: I spent a reasonable amount of time for my transaction.
            $table->unsignedTinyInteger('sqd1')->nullable();

            // SQD2: The office followed the transaction's requirements
            //       and steps based on the information provided.
            $table->unsignedTinyInteger('sqd2')->nullable();

            // SQD3: The steps (including payment) I needed to do
            //       for my transaction were easy and simple.
            $table->unsignedTinyInteger('sqd3')->nullable();

            // SQD4: I easily found information about my transaction
            //       from the office or its website.
            $table->unsignedTinyInteger('sqd4')->nullable();

            // SQD5: I paid a reasonable amount of fees for my transaction.
            $table->unsignedTinyInteger('sqd5')->nullable();

            // SQD6: I am confident my transaction was secure.
            $table->unsignedTinyInteger('sqd6')->nullable();

            // SQD7: The office's online transaction system was accessible.
            $table->unsignedTinyInteger('sqd7')->nullable();

            // SQD8: I got what I needed from the government office.
            $table->unsignedTinyInteger('sqd8')->nullable();

            // ── SUGGESTION / REMARKS (Step 4) ─────────────────
            $table->text('suggestion')->nullable();

            // ── META ───────────────────────────────────────────
            $table->string('return_url')->nullable();

            // Track if submitted as "Later" (incomplete)
            $table->boolean('is_complete')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
