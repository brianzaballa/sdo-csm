<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\SurveyResponse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SurveyResponseSeeder extends Seeder
{
    private array $firstNames = ['Juan', 'Maria', 'Pedro', 'Ana', 'Jose', 'Elena', 'Carlos', 'Rosa', 'Antonio', 'Luz', 'Miguel', 'Sofia', 'Ricardo', 'Carmen', 'Fernando'];
    private array $lastNames = ['Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Mendoza', 'Garcia', 'Torres', 'Rivera', 'Lopez', 'Dela Cruz', 'Villanueva', 'Castro', 'Gonzales', 'Ramos'];

    public function run(): void
    {
        $offices = Office::with('activeServices')->get();

        if ($offices->isEmpty()) {
            $this->command->error('No offices found. Run OfficeSeeder first.');
            return;
        }

        $startDate = Carbon::parse('2026-01-01');
        $endDate = Carbon::today();

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $bar = $this->command->getOutput()->createProgressBar($totalDays);
        $bar->start();

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $responsesCount = max(1, (int) round(35 + rand(-12, 12)));

            $batch = [];

            for ($i = 0; $i < $responsesCount; $i++) {
                $office = $offices->random();
                $service = $office->activeServices->isEmpty() ? null : $office->activeServices->random();

                $cc1 = $this->weightedRandom([1 => 25, 2 => 25, 3 => 25, 4 => 25]);
                $cc2 = in_array($cc1, [1, 2, 3]) ? $this->weightedRandom([1 => 30, 2 => 30, 3 => 15, 4 => 10, 5 => 15]) : null;
                $cc3 = in_array($cc1, [1, 2]) ? $this->weightedRandom([1 => 35, 2 => 35, 3 => 15, 4 => 15]) : null;

                $startMinutes = rand(420, 1020); // 7:00 AM to 5:00 PM
                $duration = rand(60, 600);        // 1 to 10 minutes

                $startedAt = $currentDate->copy()->startOfDay()->addMinutes($startMinutes);
                $endedAt = $startedAt->copy()->addSeconds($duration);

                $hasName = rand(0, 1);
                $fn = $this->firstNames[array_rand($this->firstNames)];
                $ln = $this->lastNames[array_rand($this->lastNames)];

                $batch[] = [
                    'office_id'        => $office->id,
                    'service_id'       => $service?->id,
                    'age'              => rand(18, 75),
                    'gender'           => rand(0, 1) ? 'Male' : 'Female',
                    'customer_type'    => ['Business', 'Citizen', 'Government'][rand(0, 2)],
                    'email_address'    => $hasName ? strtolower("{$fn}.{$ln}" . rand(1, 99) . '@example.com') : null,
                    'complete_name'    => $hasName ? "{$fn} {$ln}" : null,
                    'cc1'              => $cc1,
                    'cc2'              => $cc2,
                    'cc3'              => $cc3,
                    'sqd0'             => $this->sqdRandom(),
                    'sqd1'             => $this->sqdRandom(),
                    'sqd2'             => $this->sqdRandom(),
                    'sqd3'             => $this->sqdRandom(),
                    'sqd4'             => $this->sqdRandom(),
                    'sqd5'             => $this->sqdRandom(),
                    'sqd6'             => $this->sqdRandom(),
                    'sqd7'             => $this->sqdRandom(),
                    'sqd8'             => $this->sqdRandom(),
                    'suggestion'       => rand(0, 2) === 0 ? $this->randomSuggestion() : null,
                    'is_complete'      => true,
                    'ip_address'       => rand(10, 223) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254),
                    'is_flagged'       => false,
                    'started_at'       => $startedAt->format('Y-m-d H:i:s'),
                    'ended_at'         => $endedAt->format('Y-m-d H:i:s'),
                    'duration_seconds' => $duration,
                    'created_at'       => $endedAt->format('Y-m-d H:i:s'),
                    'updated_at'       => $endedAt->format('Y-m-d H:i:s'),
                ];
            }

            SurveyResponse::insert($batch);

            $bar->advance();
            $currentDate->addDay();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Survey responses seeded successfully!');
    }

    private function weightedRandom(array $weights): int
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        $cumulative = 0;

        foreach ($weights as $value => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $value;
            }
        }

        return array_key_last($weights);
    }

    private function sqdRandom(): int
    {
        return $this->weightedRandom([0 => 5, 1 => 5, 2 => 10, 3 => 20, 4 => 30, 5 => 30]);
    }

    private function randomSuggestion(): string
    {
        $suggestions = [
            'Wala naman, okay naman ang serbisyo.',
            'Sana po mas mapabilis pa ang processing.',
            'Maganda ang serbisyo, keep it up!',
            'Sana po magkaroon ng online appointment system.',
            'Mababait ang mga staff, thank you!',
            'Sana po madagdagan ang upuan sa waiting area.',
            'Maayos ang proseso at mabilis.',
            'Sana po ayusin ang pila, medyo matagal.',
            'Magaling at maasikaso ang mga empleyado.',
            'Sana po may malinaw na signage para hindi malito.',
            'The service was excellent and fast.',
            'Please improve the waiting time.',
            'Very helpful and accommodating staff.',
            'Sana po magkaroon ng sapat na parking space.',
            'Thank you for your prompt service.',
            'Medyo mainit sa loob ng opisina, sana may aircon.',
            'Maganda ang serbisyo ng inyong opisina.',
            'Sana po mas marami pang staff para hindi matagal ang pila.',
            'Napakabagal ng processing, sana po mapabilis.',
        ];

        return $suggestions[array_rand($suggestions)];
    }
}
