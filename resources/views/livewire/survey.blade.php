<div x-data="{
        initDraft() {
            const saved = localStorage.getItem('sdo_csm_survey_draft');
            if (! saved) return;
            try {
                $wire.call('restoreDraft', JSON.parse(saved));
            } catch (e) {
                localStorage.removeItem('sdo_csm_survey_draft');
            }
        },
        saveDraft() {
            localStorage.setItem('sdo_csm_survey_draft', JSON.stringify({
                currentStep: $wire.currentStep,
                officeId: $wire.officeId,
                serviceId: $wire.serviceId,
                age: $wire.age,
                gender: $wire.gender,
                customerType: $wire.customerType,
                cc1: $wire.cc1,
                cc2: $wire.cc2,
                cc3: $wire.cc3,
                sqd0: $wire.sqd0,
                sqd1: $wire.sqd1,
                sqd2: $wire.sqd2,
                sqd3: $wire.sqd3,
                sqd4: $wire.sqd4,
                sqd5: $wire.sqd5,
                sqd6: $wire.sqd6,
                sqd7: $wire.sqd7,
                sqd8: $wire.sqd8,
                suggestion: $wire.suggestion,
                startedAt: $wire.startedAt,
            }));
            $wire.cancelled = true;
            $wire.$commit();
        },
        discardDraft() {
            localStorage.removeItem('sdo_csm_survey_draft');
        }
    }" x-init="initDraft()" x-on:draft-cleared.window="discardDraft()" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    {{-- CSM Notice Modal --}}
    @if($showNotice)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/75 px-4" role="dialog" aria-modal="true">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-auto">
                <div class="p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-gray-900 mb-2">Client Satisfaction Measurement (CSM)</h3>
                            <p class="text-xs text-gray-600 mb-2">
                                The CSM tracks customer experience of government offices. Your feedback helps improve service delivery.
                            </p>
                            <p class="text-xs text-gray-600 mb-2">
                                Personal information is confidential. You may skip any question.
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                We collect your IP address for duplicate detection purposes only.
                            </p>
                            <p class="text-xs font-semibold text-gray-700">
                                ANTI-RED TAPE AUTHORITY
                            </p>
                            <p class="text-xs text-gray-500">
                                PSA Approval No. ARTA-2242-3
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 rounded-b-xl flex justify-end">
                    <button type="button" wire:click="closeNotice"
                            class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-2xl mx-auto px-4 py-8 sm:px-6">

        @if($submitted)
            {{-- Success message --}}
            <div class="bg-white shadow-xl rounded-2xl p-10 text-center border border-gray-100">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 mb-6 shadow-lg shadow-green-200">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Thank you!</h2>
                <p class="text-gray-500 text-base">Your feedback has been recorded. We appreciate your time.</p>
                <div class="mt-6 inline-flex items-center gap-2 text-sm text-blue-600 bg-blue-50 px-4 py-2 rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Response saved at {{ now()->format('g:i A') }}
                </div>
            </div>
        @elseif($cancelled)
            {{-- Saved for later --}}
            <div class="bg-white shadow-xl rounded-2xl p-10 text-center border border-gray-100">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 mb-6 shadow-lg shadow-amber-200">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Progress Saved</h2>
                <p class="text-gray-500 text-base">Your progress has been saved in this browser. Come back anytime to continue.</p>
            </div>
        @else
            {{-- Progress bar --}}
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="flex flex-col items-center relative z-10">
                            <div class="flex items-center justify-center h-11 w-11 rounded-full text-sm font-bold shadow-md transition-all duration-300
                                {{ $currentStep > $i ? 'bg-gradient-to-br from-green-400 to-emerald-500 text-white' : ($currentStep === $i ? 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white ring-4 ring-blue-200' : 'bg-white text-gray-400 border-2 border-gray-200') }}">
                                @if($currentStep > $i)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            <span class="mt-2 text-xs font-medium {{ $currentStep >= $i ? 'text-blue-600' : 'text-gray-400' }} hidden sm:block">
                                @switch($i)
                                    @case(1) Info @break
                                    @case(2) Charter @break
                                    @case(3) Rating @break
                                    @case(4) Submit @break
                                @endswitch
                            </span>
                        </div>
                        @if($i < 4)
                            <div class="flex-1 h-1 mx-1 rounded-full transition-all duration-300 {{ $currentStep > $i ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gray-200' }}"></div>
                        @endif
                    @endfor
                </div>
            </div>

            {{-- Form Card --}}
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">

                {{-- Step 1: Client Information --}}
                @if($currentStep === 1)
                    <div class="p-6 sm:p-8 space-y-6" wire:key="step-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-blue-100 rounded-lg p-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">Client Information</h2>
                        </div>

                        {{-- Office --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Office transacted with <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="officeId"
                                    class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3"
                                    {{ $officeLocked ? 'disabled' : '' }}>
                                <option value="">Select Office</option>
                                @foreach($this->offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->display_name }}</option>
                                @endforeach
                            </select>
                            @error('officeId') <p class="mt-1.5 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        {{-- Service --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Service availed <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="serviceId"
                                    class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 {{ !$officeId ? 'bg-gray-50 text-gray-400' : '' }}"
                                    {{ !$officeId ? 'disabled' : '' }}>
                                <option value="">{{ $officeId ? 'Select Service' : 'Select an office first' }}</option>
                                @foreach($this->services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                            @error('serviceId') <p class="mt-1.5 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        {{-- Email Address (optional) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email Address <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <input type="email" wire:model="emailAddress"
                                   placeholder="you@example.com"
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3">
                            @error('emailAddress') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Complete Name (optional) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Complete Name <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <input type="text" wire:model="completeName"
                                   placeholder="Juan Dela Cruz"
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3">
                            @error('completeName') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Age --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Age <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="age" min="15" max="90"
                                       placeholder="e.g. 30"
                                       class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3">
                                @error('age') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-3">
                                    <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 cursor-pointer transition-all
                                        {{ $gender === 'Male' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 hover:border-gray-300 text-gray-600' }}">
                                        <input type="radio" wire:model.live="gender" value="Male" class="sr-only">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="text-sm font-medium">Male</span>
                                    </label>
                                    <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 cursor-pointer transition-all
                                        {{ $gender === 'Female' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 hover:border-gray-300 text-gray-600' }}">
                                        <input type="radio" wire:model.live="gender" value="Female" class="sr-only">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="text-sm font-medium">Female</span>
                                    </label>
                                </div>
                                @error('gender') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Customer Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Customer Type <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2.5">
                                @foreach([
                                    'Business' => ['Business', 'private school, corporations, etc.', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                    'Citizen' => ['Citizen', 'general public, learners, parents, former DepEd employees, researchers, NGOs etc.', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                                    'Government' => ['Government', 'current DepEd employees or employees of other government agencies & LGUs', 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                                ] as $type => [$label, $desc, $icon])
                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all
                                        {{ $customerType === $type ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                                        <input type="radio" wire:model.live="customerType" value="{{ $type }}" class="sr-only">
                                        <div class="mt-0.5 flex-shrink-0">
                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ $customerType === $type ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/></svg>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-semibold {{ $customerType === $type ? 'text-blue-700' : 'text-gray-800' }}">{{ $label }}</span>
                                            <p class="text-xs {{ $customerType === $type ? 'text-blue-600' : 'text-gray-500' }} mt-0.5">{{ $desc }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('customerType') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                {{-- Step 2: Citizen's Charter --}}
                @if($currentStep === 2)
                    <div class="p-6 sm:p-8 space-y-6" wire:key="step-2">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-amber-100 rounded-lg p-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">Citizen's Charter</h2>
                        </div>

                        {{-- CC1 --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                CC1: Which of the following best describes your awareness of a CC? <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                @foreach([
                                    1 => 'I know what a CC is and I saw this office\'s CC',
                                    2 => 'I know what a CC is but I did NOT see this office\'s CC',
                                    3 => 'I learned of the CC only when I saw this office\'s CC',
                                    4 => 'I do not know what a CC is and I did not see one in this office',
                                ] as $val => $text)
                                    <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all
                                        {{ $cc1 == $val ? 'border-blue-500 bg-blue-50' : 'border-gray-100 hover:border-gray-200' }}">
                                        <input type="radio" wire:model.live="cc1" value="{{ $val }}" class="sr-only">
                                        <div class="flex-shrink-0 mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center
                                            {{ $cc1 == $val ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}">
                                            @if($cc1 == $val)
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            @endif
                                        </div>
                                        <span class="text-sm {{ $cc1 == $val ? 'text-blue-700 font-medium' : 'text-gray-700' }}">{{ $text }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('cc1') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- CC2 (conditional) --}}
                        <div x-show="$wire.cc1 >= 1 && $wire.cc1 <= 3" x-cloak>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                CC2: Would you say that the CC of this office was... <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach([
                                    1 => 'Easy to see',
                                    2 => 'Somewhat easy to see',
                                    3 => 'Difficult to see',
                                    4 => 'Not visible at all',
                                    5 => 'N/A',
                                ] as $val => $text)
                                    <label class="flex items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                           :class="$wire.cc2 == {{ $val }} ? 'border-blue-500 bg-blue-50' : 'border-gray-100 hover:border-gray-200'">
                                        <input type="radio" wire:model.live="cc2" value="{{ $val }}" class="sr-only">
                                        <div class="flex-shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center transition-colors"
                                             :class="$wire.cc2 == {{ $val }} ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                            <div x-show="$wire.cc2 == {{ $val }}" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                        <span class="text-sm transition-colors" :class="$wire.cc2 == {{ $val }} ? 'text-blue-700 font-medium' : 'text-gray-700'">{{ $text }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('cc2') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- CC3 (conditional) --}}
                        <div x-show="$wire.cc1 <= 2" x-cloak>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                CC3: How much did the CC help you in your transaction? <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach([
                                    1 => 'Helped very much',
                                    2 => 'Somewhat helped',
                                    3 => 'Did not help',
                                    4 => 'N/A',
                                ] as $val => $text)
                                    <label class="flex items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                           :class="$wire.cc3 == {{ $val }} ? 'border-blue-500 bg-blue-50' : 'border-gray-100 hover:border-gray-200'">
                                        <input type="radio" wire:model.live="cc3" value="{{ $val }}" class="sr-only">
                                        <div class="flex-shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center transition-colors"
                                             :class="$wire.cc3 == {{ $val }} ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                            <div x-show="$wire.cc3 == {{ $val }}" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                        <span class="text-sm transition-colors" :class="$wire.cc3 == {{ $val }} ? 'text-blue-700 font-medium' : 'text-gray-700'">{{ $text }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('cc3') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                {{-- Step 3: Service Quality Dimension --}}
                @if($currentStep === 3)
                    <div class="p-6 sm:p-8 space-y-6" wire:key="step-3">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-purple-100 rounded-lg p-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">Service Quality Dimension</h2>
                        </div>

                        @foreach(\App\Models\SurveyResponse::sqdQuestions() as $key => $question)
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-5">
                                <label class="block text-sm font-semibold text-gray-800 mb-3">
                                    {{ strtoupper($key) }}: {{ $question }} <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                    @foreach([
                                        5 => ['Strongly Agree', '😊'],
                                        4 => ['Agree', '🙂'],
                                        3 => ['Neither', '😐'],
                                        2 => ['Disagree', '😕'],
                                        1 => ['Strongly Disagree', '😞'],
                                        0 => ['N/A', '—'],
                                    ] as $value => [$label, $emoji])
                                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border-2 cursor-pointer transition-all text-center"
                                               :class="$wire.{{ $key }} == {{ $value }} ? 'border-blue-500 bg-white shadow-md scale-105' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm'">
                                            <input type="radio" wire:model.live="{{ $key }}" value="{{ $value }}" class="sr-only">
                                            <span class="text-xl mb-1">{{ $emoji }}</span>
                                            <span class="text-[10px] sm:text-xs font-medium leading-tight transition-colors"
                                                  :class="$wire.{{ $key }} == {{ $value }} ? 'text-blue-600' : 'text-gray-600'">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error($key) <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Step 4: Suggestion & Submit --}}
                @if($currentStep === 4)
                    <div class="p-6 sm:p-8 space-y-6" wire:key="step-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-green-100 rounded-lg p-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">Suggestion / Remarks</h2>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Any suggestions or remarks?
                            </label>
                            <textarea wire:model="suggestion" rows="4"
                                      placeholder="Share your feedback or suggestions to help us improve our service..."
                                      class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3"></textarea>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100 space-y-4">
                            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Review your answers
                            </h3>

                            {{-- Client Info --}}
                            <div>
                                <h4 class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Client Information</h4>
                                <dl class="text-sm space-y-1.5">
                                    <div class="flex items-start">
                                        <dt class="w-24 font-medium text-gray-500 flex-shrink-0">Office</dt>
                                        <dd class="text-gray-800 font-medium">: {{ $this->offices->firstWhere('id', $officeId)?->display_name }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-24 font-medium text-gray-500 flex-shrink-0">Service</dt>
                                        <dd class="text-gray-800 font-medium">: {{ $this->services->firstWhere('id', $serviceId)?->name }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-24 font-medium text-gray-500 flex-shrink-0">Age / Gender</dt>
                                        <dd class="text-gray-800 font-medium">: {{ $age }} / {{ $gender }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-24 font-medium text-gray-500 flex-shrink-0">Type</dt>
                                        <dd class="text-gray-800 font-medium">: {{ $customerType }}</dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Citizen's Charter --}}
                            @if($cc1)
                                <div>
                                    <h4 class="text-xs font-semibold text-amber-600 uppercase tracking-wide mb-2">Citizen's Charter</h4>
                                    <dl class="text-sm space-y-1.5">
                                        <div class="flex items-start">
                                            <dt class="w-12 font-medium text-gray-500 flex-shrink-0">CC1</dt>
                                            <dd class="text-gray-800 font-medium">: {{ \App\Models\SurveyResponse::cc1Labels()[$cc1] ?? $cc1 }}</dd>
                                        </div>
                                        @if($cc2)
                                            <div class="flex items-start">
                                                <dt class="w-12 font-medium text-gray-500 flex-shrink-0">CC2</dt>
                                                <dd class="text-gray-800 font-medium">: {{ \App\Models\SurveyResponse::cc2Labels()[$cc2] ?? $cc2 }}</dd>
                                            </div>
                                        @endif
                                        @if($cc3)
                                            <div class="flex items-start">
                                                <dt class="w-12 font-medium text-gray-500 flex-shrink-0">CC3</dt>
                                                <dd class="text-gray-800 font-medium">: {{ \App\Models\SurveyResponse::cc3Labels()[$cc3] ?? $cc3 }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                            @endif

                            {{-- Service Quality --}}
                            <div>
                                <h4 class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-2">Service Quality</h4>
                                <dl class="text-sm space-y-1.5">
                                    @foreach(\App\Models\SurveyResponse::sqdQuestions() as $key => $question)
                                        @php $val = $$key; @endphp
                                        @if($val !== null)
                                            <div class="flex items-start">
                                                <dt class="w-10 font-medium text-gray-500 flex-shrink-0">{{ strtoupper($key) }}</dt>
                                                <dd class="text-gray-800">: <span class="font-medium">{{ \App\Models\SurveyResponse::ratingLabel((int) $val) }}</span> <span class="text-base">{{ \App\Models\SurveyResponse::ratingEmoji((int) $val) }}</span></dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Navigation buttons --}}
                <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        @if($currentStep > 1)
                            <button type="button" wire:click="prevStep"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @if($currentStep < 4)
                            <button type="button" x-on:click="saveDraft()"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Later
                            </button>
                            <button type="button" wire:click="nextStep"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-md hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                Next Step
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        @else
                            <button type="button" x-on:click="saveDraft()"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Save for Later
                            </button>
                            <button type="button" wire:click="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-md hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="submit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span wire:loading wire:target="submit">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="submit">Submit</span>
                                <span wire:loading wire:target="submit">Submitting...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                Your responses are confidential and will be used to improve our services.
            </p>
        @endif
    </div>
</div>
