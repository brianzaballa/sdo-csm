<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Survey;
use App\Models\Office;
use App\Models\Service;
use App\Models\SurveyResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    private Office $office;

    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->office = Office::create([
            'name' => 'ICT Unit',
            'code' => 'ICTU',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'office_id' => $this->office->id,
            'name' => 'Technical Support',
            'is_active' => true,
        ]);
    }

    // ── Step Navigation ──────────────────────────────────

    public function test_component_renders_successfully(): void
    {
        Livewire::test(Survey::class)
            ->assertStatus(200)
            ->assertSee('Client Satisfaction Measurement');
    }

    public function test_starts_at_step_1(): void
    {
        Livewire::test(Survey::class)
            ->assertSet('currentStep', 1);
    }

    public function test_can_navigate_forward_after_validating_step_1(): void
    {
        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->assertSet('currentStep', 2);
    }

    public function test_cannot_navigate_forward_with_invalid_step_1(): void
    {
        Livewire::test(Survey::class)
            ->call('nextStep')
            ->assertHasErrors(['officeId', 'serviceId', 'age', 'gender', 'customerType']);
    }

    public function test_can_navigate_back_to_previous_step(): void
    {
        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->call('prevStep')
            ->assertSet('currentStep', 1);
    }

    // ── Step 1: Client Information ───────────────────────

    public function test_office_selection_resets_service(): void
    {
        $office2 = Office::create([
            'name' => 'HRD',
            'code' => 'HRD',
            'is_active' => true,
        ]);

        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->assertSet('serviceId', $this->service->id)
            ->set('officeId', $office2->id)
            ->assertSet('serviceId', null);
    }

    public function test_services_load_based_on_selected_office(): void
    {
        $inactive = Service::create([
            'office_id' => $this->office->id,
            'name' => 'Inactive Service',
            'is_active' => false,
        ]);

        $component = Livewire::test(Survey::class)
            ->set('officeId', $this->office->id);

        $services = $component->instance()->services;
        $this->assertTrue($services->contains($this->service));
        $this->assertFalse($services->contains($inactive));
    }

    public function test_age_must_be_within_range(): void
    {
        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 10)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->assertHasErrors(['age']);
    }

    // ── Step 2: Citizen's Charter ────────────────────────

    public function test_cc1_option_4_skips_cc2_and_cc3(): void
    {
        $component = Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('cc1', 4)
            ->assertSet('cc2', null)
            ->assertSet('cc3', null);

        $this->assertFalse($component->instance()->showCc2);
        $this->assertFalse($component->instance()->showCc3);
    }

    public function test_cc1_option_1_shows_both_cc2_and_cc3(): void
    {
        $component = Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->set('cc1', 1);

        $this->assertTrue($component->instance()->showCc2);
        $this->assertTrue($component->instance()->showCc3);
    }

    public function test_cc1_option_3_shows_cc2_but_not_cc3(): void
    {
        $component = Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->set('cc1', 3);

        $this->assertTrue($component->instance()->showCc2);
        $this->assertFalse($component->instance()->showCc3);
    }

    // ── Full Flow & Submission ───────────────────────────

    public function test_full_survey_submission(): void
    {
        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Male')
            ->set('customerType', 'Citizen')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('cc1', 1)
            ->set('cc2', 1)
            ->set('cc3', 1)
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->set('sqd0', 5)
            ->set('sqd1', 4)
            ->set('sqd2', 5)
            ->set('sqd3', 4)
            ->set('sqd4', 3)
            ->set('sqd5', 5)
            ->set('sqd6', 4)
            ->set('sqd7', 5)
            ->set('sqd8', 4)
            ->call('nextStep')
            ->assertSet('currentStep', 4)
            ->set('suggestion', 'Great service!')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('survey_responses', [
            'office_id' => $this->office->id,
            'service_id' => $this->service->id,
            'age' => 30,
            'gender' => 'Male',
            'customer_type' => 'Citizen',
            'cc1' => 1,
            'cc2' => 1,
            'cc3' => 1,
            'sqd0' => 5,
            'suggestion' => 'Great service!',
            'is_complete' => true,
        ]);
    }

    public function test_save_later_creates_incomplete_response(): void
    {
        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 30)
            ->set('gender', 'Female')
            ->set('customerType', 'Government')
            ->call('saveLater')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('survey_responses', [
            'office_id' => $this->office->id,
            'service_id' => $this->service->id,
            'is_complete' => false,
        ]);
    }

    public function test_cc1_option_4_saves_null_cc2_and_cc3(): void
    {
        Livewire::test(Survey::class)
            ->set('officeId', $this->office->id)
            ->set('serviceId', $this->service->id)
            ->set('age', 25)
            ->set('gender', 'Male')
            ->set('customerType', 'Business')
            ->call('nextStep')
            ->set('cc1', 4)
            ->call('nextStep')
            ->set('sqd0', 5)->set('sqd1', 5)->set('sqd2', 5)
            ->set('sqd3', 5)->set('sqd4', 5)->set('sqd5', 5)
            ->set('sqd6', 5)->set('sqd7', 5)->set('sqd8', 5)
            ->call('nextStep')
            ->call('submit');

        $response = SurveyResponse::first();
        $this->assertNull($response->cc2);
        $this->assertNull($response->cc3);
    }
}
