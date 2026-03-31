<?php

namespace App\Livewire;

use App\Models\Office;
use App\Models\Service;
use App\Models\SurveyResponse;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Survey extends Component
{
    // ── Step navigation ───────────────────────────────────
    public int $currentStep = 1;

    public bool $submitted = false;

    // ── Step 1: Client Information ────────────────────────
    #[Rule('required|exists:offices,id')]
    public ?int $officeId = null;

    #[Rule('required|exists:services,id')]
    public ?int $serviceId = null;

    #[Rule('required|integer|min:15|max:90')]
    public ?int $age = null;

    #[Rule('required|in:Male,Female')]
    public ?string $gender = null;

    #[Rule('required|in:Business,Citizen,Government')]
    public ?string $customerType = null;

    // ── Step 2: Citizen's Charter ─────────────────────────
    #[Rule('required|integer|min:1|max:4')]
    public ?int $cc1 = null;

    #[Rule('nullable|integer|min:1|max:5')]
    public ?int $cc2 = null;

    #[Rule('nullable|integer|min:1|max:4')]
    public ?int $cc3 = null;

    // ── Step 3: Service Quality Dimension ─────────────────
    public ?int $sqd0 = null;

    public ?int $sqd1 = null;

    public ?int $sqd2 = null;

    public ?int $sqd3 = null;

    public ?int $sqd4 = null;

    public ?int $sqd5 = null;

    public ?int $sqd6 = null;

    public ?int $sqd7 = null;

    public ?int $sqd8 = null;

    // ── Step 4: Suggestion ────────────────────────────────
    public ?string $suggestion = null;

    // ── Navigation ────────────────────────────────────────

    public function nextStep(): void
    {
        $this->validate();

        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // ── Reactive handlers ────────────────────────────────

    public function updatedOfficeId(): void
    {
        $this->serviceId = null;
    }

    // ── Computed properties ───────────────────────────────

    #[Computed]
    public function offices(): Collection
    {
        return Office::active()->orderBy('code')->orderBy('name')->get();
    }

    #[Computed]
    public function services(): Collection
    {
        if (! $this->officeId) {
            return collect();
        }

        return Service::where('office_id', $this->officeId)
            ->active()
            ->orderBy('name')
            ->get();
    }

    // ── CC conditional logic ──────────────────────────────

    public function getShowCc2Property(): bool
    {
        return in_array($this->cc1, [1, 2, 3]);
    }

    public function getShowCc3Property(): bool
    {
        return in_array($this->cc1, [1, 2]);
    }

    // ── Step-specific validation ──────────────────────────

    public function validateStep1(): void
    {
        $this->validate([
            'officeId' => 'required|exists:offices,id',
            'serviceId' => 'required|exists:services,id',
            'age' => 'required|integer|min:15|max:90',
            'gender' => 'required|in:Male,Female',
            'customerType' => 'required|in:Business,Citizen,Government',
        ]);
    }

    public function validateStep2(): void
    {
        $rules = [
            'cc1' => 'required|integer|min:1|max:4',
        ];

        if (in_array($this->cc1, [1, 2, 3])) {
            $rules['cc2'] = 'required|integer|min:1|max:5';
        }

        if (in_array($this->cc1, [1, 2])) {
            $rules['cc3'] = 'required|integer|min:1|max:4';
        }

        $this->validate($rules);
    }

    public function validateStep3(): void
    {
        $rules = [];
        foreach (SurveyResponse::sqdKeys() as $key) {
            $rules[$key] = 'required|integer|min:0|max:5';
        }

        $this->validate($rules);
    }

    // ── Override validation to use step-specific rules ────

    public function validate($rules = null, $messages = [], $attributes = []): array
    {
        if ($rules === null) {
            return match ($this->currentStep) {
                1 => parent::validate($this->getStep1Rules(), $messages, $attributes),
                2 => parent::validate($this->getStep2Rules(), $messages, $attributes),
                3 => parent::validate($this->getStep3Rules(), $messages, $attributes),
                default => [],
            };
        }

        return parent::validate($rules, $messages, $attributes);
    }

    private function getStep1Rules(): array
    {
        return [
            'officeId' => 'required|exists:offices,id',
            'serviceId' => 'required|exists:services,id',
            'age' => 'required|integer|min:15|max:90',
            'gender' => 'required|in:Male,Female',
            'customerType' => 'required|in:Business,Citizen,Government',
        ];
    }

    private function getStep2Rules(): array
    {
        $rules = [
            'cc1' => 'required|integer|min:1|max:4',
        ];

        if (in_array($this->cc1, [1, 2, 3])) {
            $rules['cc2'] = 'required|integer|min:1|max:5';
        }

        if (in_array($this->cc1, [1, 2])) {
            $rules['cc3'] = 'required|integer|min:1|max:4';
        }

        return $rules;
    }

    private function getStep3Rules(): array
    {
        $rules = [];
        foreach (SurveyResponse::sqdKeys() as $key) {
            $rules[$key] = 'required|integer|min:0|max:5';
        }

        return $rules;
    }

    // ── Submission ────────────────────────────────────────

    public function submit(): void
    {
        $this->validateStep3();

        $this->saveResponse(isComplete: true);
        $this->submitted = true;
    }

    public function saveLater(): void
    {
        $this->saveResponse(isComplete: false);
        $this->submitted = true;
    }

    private function saveResponse(bool $isComplete): void
    {
        $cc2 = $this->cc1 === 4 ? null : $this->cc2;
        $cc3 = in_array($this->cc1, [3, 4]) ? null : $this->cc3;

        SurveyResponse::create([
            'office_id' => $this->officeId,
            'service_id' => $this->serviceId,
            'age' => $this->age,
            'gender' => $this->gender,
            'customer_type' => $this->customerType,
            'cc1' => $this->cc1,
            'cc2' => $cc2,
            'cc3' => $cc3,
            'sqd0' => $this->sqd0,
            'sqd1' => $this->sqd1,
            'sqd2' => $this->sqd2,
            'sqd3' => $this->sqd3,
            'sqd4' => $this->sqd4,
            'sqd5' => $this->sqd5,
            'sqd6' => $this->sqd6,
            'sqd7' => $this->sqd7,
            'sqd8' => $this->sqd8,
            'suggestion' => $this->suggestion,
            'is_complete' => $isComplete,
        ]);
    }

    public function render()
    {
        return view('livewire.survey');
    }
}
