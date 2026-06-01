<x-filament-panels::page>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="space-y-6">

    {{-- ══════════════════════════════════════════════════
         REPORT PARAMETERS
    ══════════════════════════════════════════════════ --}}
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon
                    icon="heroicon-o-adjustments-horizontal"
                    class="w-5 h-5 text-gray-400" />
                <span>Report Parameters</span>
            </div>
        </x-slot>
        <x-slot name="description">
            Select the fiscal year and quarter to aggregate data for the report.
        </x-slot>

        {{ $this->form }}

    </x-filament::section>

    {{-- ══════════════════════════════════════════════════
         REPORT SHEET PREVIEW
    ══════════════════════════════════════════════════ --}}
    @php $preview = $this->getPreviewData(); @endphp

    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon
                    icon="heroicon-o-eye"
                    class="w-5 h-5 text-gray-400" />
                <span>Report Sheet Preview</span>
            </div>
        </x-slot>
        <x-slot name="description">
            Click a tab to preview what each Excel sheet will contain.
        </x-slot>

        @php
            $tabs = [
                'office'    => [
                    'label' => 'Office Analytics',
                    'sub'   => 'Per-service ratings',
                    'icon'  => 'heroicon-o-building-office-2',
                ],
                'quarterly' => [
                    'label' => 'Quarterly Trend',
                    'sub'   => 'Q1–Q4 comparison',
                    'icon'  => 'heroicon-o-calendar-days',
                ],
                'q1'        => [
                    'label' => 'Service Detail',
                    'sub'   => 'Office breakdown',
                    'icon'  => 'heroicon-o-list-bullet',
                ],
                'feedback'  => [
                    'label' => 'Client Voice',
                    'sub'   => 'Suggestions',
                    'icon'  => 'heroicon-o-chat-bubble-left-ellipsis',
                ],
            ];
        @endphp

        {{-- ── Preview Table Container ───────────────────── --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700
                    overflow-hidden shadow-sm bg-white dark:bg-gray-950
                    min-h-[400px]">

            {{-- ── Excel-like Sheet Tab Bar ─────────────────── --}}
            <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                @foreach($tabs as $key => $tab)
                @php $isActive = $activeTab === $key; @endphp
                <button wire:click="setTab('{{ $key }}')"
                        class="flex items-center gap-2 px-5 py-3 text-xs font-semibold uppercase tracking-wider
                               border-r border-gray-200 dark:border-gray-700
                               transition-all duration-150 relative
                               {{ $isActive
                                   ? 'bg-teal-600 text-white shadow-sm'
                                   : 'bg-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    @if($isActive)
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-white"></span>
                    @endif
                    <x-filament::icon
                        :icon="$tab['icon']"
                        class="w-4 h-4" />
                    <span>{{ $tab['label'] }}</span>
                </button>
                @endforeach
            </div>

            {{-- Teal header bar --}}
            @php
                $q = $this->quarter;
                $quarterLabel = match ($q) {
                    '1' => 'Q1 — January to March',
                    '2' => 'Q2 — April to June',
                    '3' => 'Q3 — July to September',
                    '4' => 'Q4 — October to December',
                    default => '',
                };
                $sheetTitles = [
                    'office'    => 'Office Sheet — ' . ($preview['office'] ?? 'Office') . ' · FY ' . ($this->form->getState()['year'] ?? now()->year),
                    'quarterly' => ($quarterLabel ? $quarterLabel . ' · ' : '') . 'Quarterly Report — FY ' . ($this->form->getState()['year'] ?? now()->year),
                    'q1'        => ($quarterLabel ?: 'Q1 — January to March') . ' Detail · FY ' . ($this->form->getState()['year'] ?? now()->year),
                    'feedback'  => 'Client Feedback — FY ' . ($this->form->getState()['year'] ?? now()->year),
                ];
            @endphp
            <div class="bg-teal-700 dark:bg-teal-800 px-5 py-3 flex items-center
                        justify-between">
                <div class="flex items-center gap-2">
                    <x-filament::icon
                        :icon="$tabs[$activeTab]['icon']"
                        class="w-4 h-4 text-teal-200" />
                    <span class="text-sm font-semibold text-white">
                        {{ $sheetTitles[$activeTab] }}
                    </span>
                </div>
                <span class="text-xs text-teal-300 font-medium uppercase
                             tracking-widest">
                    Excel preview
                </span>
            </div>

            {{-- ── OFFICE SHEET TAB ───────────────────────── --}}
            @if($activeTab === 'office')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       w-28 sticky left-0 bg-gray-50
                                       dark:bg-gray-900">
                                Category
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       w-32">
                                Item
                            </th>
                            @foreach($preview['services'] ?? [] as $service)
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       text-center min-w-[140px]">
                                {{ \Str::limit($service->name, 22) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($preview['rows'] ?? [] as $row)
                        <tr class="transition-colors
                            {{ $row['type'] === 'rating'
                                ? 'bg-teal-50/40 dark:bg-teal-900/10'
                                : 'hover:bg-gray-50 dark:hover:bg-gray-900/30' }}">

                            <td class="px-5 py-3 text-xs text-gray-500
                                       dark:text-gray-400 sticky left-0
                                       bg-white dark:bg-gray-950
                                       {{ $row['type'] === 'rating' ? 'bg-teal-50/40 dark:bg-teal-900/10' : '' }}">
                                {{ $row['category'] }}
                            </td>
                            <td class="px-5 py-3 text-xs font-semibold
                                       text-gray-700 dark:text-gray-200">
                                {{ $row['item'] }}
                            </td>

                            @foreach($row['values'] ?? [] as $val)
                            <td class="px-5 py-3 text-xs text-center">
                                @if($row['type'] === 'adjectival')
                                    @php
                                        $badge = match($val) {
                                            'Outstanding'      => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'Very Satisfactory'=> 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'Satisfactory'     => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'Unsatisfactory'   => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'Poor'             => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            default            => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5
                                                 rounded-full text-xs font-bold
                                                 {{ $badge }}">
                                        {{ $val }}
                                    </span>
                                @elseif($row['type'] === 'rating')
                                    <span class="font-bold text-teal-600
                                                 dark:text-teal-400">
                                        {{ $val }}
                                    </span>
                                @else
                                    <span class="text-gray-800 dark:text-gray-200">
                                        {{ $val }}
                                    </span>
                                @endif
                            </td>
                            @endforeach

                        </tr>
                        @empty
                        <tr>
                            <td colspan="10"
                                class="px-5 py-16 text-center text-sm
                                       text-gray-400 italic">
                                No data for the selected period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── QUARTERLY TREND TAB ────────────────────── --}}
            @elseif($activeTab === 'quarterly')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700">
                                Office
                            </th>
                            @foreach([
                                'Q1' => 'Jan–Mar',
                                'Q2' => 'Apr–Jun',
                                'Q3' => 'Jul–Sep',
                                'Q4' => 'Oct–Dec',
                                'Total' => '',
                            ] as $col => $sub)
                            <th class="px-5 py-3 text-center border-b
                                       border-gray-200 dark:border-gray-700
                                       {{ $col === 'Total'
                                           ? 'bg-teal-50 dark:bg-teal-900/20'
                                           : '' }}">
                                <p class="text-xs font-bold uppercase
                                          tracking-wider text-gray-500
                                          dark:text-gray-400">
                                    {{ $col }}
                                </p>
                                @if($sub)
                                <p class="text-[9px] text-gray-400 font-normal
                                          normal-case tracking-normal mt-0.5">
                                    {{ $sub }}
                                </p>
                                @endif
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($preview['rows'] ?? [] as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30
                                   transition-colors">
                            <td class="px-5 py-3 text-sm font-semibold
                                       text-gray-800 dark:text-gray-200">
                                {{ $row['office'] }}
                            </td>
                            @foreach(['q1','q2','q3','q4'] as $q)
                            <td class="px-5 py-3 text-sm text-center
                                       tabular-nums text-gray-600
                                       dark:text-gray-400">
                                {{ number_format($row[$q]) }}
                            </td>
                            @endforeach
                            <td class="px-5 py-3 text-sm text-center
                                       font-bold text-teal-600 dark:text-teal-400
                                       tabular-nums bg-teal-50/50
                                       dark:bg-teal-900/10">
                                {{ number_format($row['total']) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6"
                                class="px-5 py-16 text-center text-sm
                                       text-gray-400 italic">
                                No quarterly data available.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── Q1 DETAIL TAB ───────────────────────────── --}}
            @elseif($activeTab === 'q1')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700">
                                Office
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       text-center">
                                Responses
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       text-center">
                                Numerical rating
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       text-center">
                                Adjectival rating
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase
                                       tracking-wider text-gray-500
                                       dark:text-gray-400 border-b
                                       border-gray-200 dark:border-gray-700
                                       text-center">
                                Avg Fill Time
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($preview['rows'] ?? [] as $row)
                        <tr class="transition-colors
                            {{ !empty($row['is_total'])
                                ? 'bg-teal-50 dark:bg-teal-900/20 font-bold'
                                : 'hover:bg-gray-50 dark:hover:bg-gray-900/30' }}">
                            <td class="px-5 py-3 text-sm
                                       {{ !empty($row['is_total'])
                                           ? 'font-bold text-teal-700 dark:text-teal-400'
                                           : 'text-gray-800 dark:text-gray-200' }}">
                                {{ $row['office'] }}
                            </td>
                            <td class="px-5 py-3 text-sm text-center
                                       tabular-nums text-gray-600
                                       dark:text-gray-400">
                                {{ number_format($row['total']) }}
                            </td>
                            <td class="px-5 py-3 text-sm text-center font-bold
                                       text-teal-600 dark:text-teal-400">
                                {{ $row['numerical'] }}
                            </td>
                            <td class="px-5 py-3 text-sm text-center">
                                @if($row['adjectival'] !== 'N/A')
                                @php
                                    $badge = match($row['adjectival']) {
                                        'Outstanding'      => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'Very Satisfactory'=> 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'Satisfactory'     => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'Unsatisfactory'   => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                        'Poor'             => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        default            => 'bg-gray-100 text-gray-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5
                                             rounded-full text-xs font-bold
                                             {{ $badge }}">
                                    {{ $row['adjectival'] }}
                                </span>
                                @else
                                <span class="text-xs text-gray-400 italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-sm text-center tabular-nums text-gray-600 dark:text-gray-400">
                                {{ $row['avg_fill_time'] ?? '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="px-5 py-16 text-center text-sm
                                       text-gray-400 italic">
                                No data for the selected period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── FEEDBACK TAB ─────────────────────────────── --}}
            @elseif($activeTab === 'feedback')
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($preview['rows'] ?? [] as $row)
                <div class="rounded-xl border border-gray-100 dark:border-gray-800
                            bg-gray-50/50 dark:bg-gray-900/30 p-4">

                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold uppercase tracking-tight
                                   text-gray-900 dark:text-white">
                            {{ $row['office'] }}
                        </h4>
                        <span class="text-[10px] font-semibold px-2 py-0.5
                                     rounded-md bg-gray-200 dark:bg-gray-700
                                     text-gray-600 dark:text-gray-400
                                     uppercase tracking-wide">
                            {{ $row['count'] }} feedback{{ $row['count'] !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    @if(!empty($row['suggestions']))
                    <div class="space-y-2.5">
                        @foreach($row['suggestions'] as $suggestion)
                        <div class="flex items-start gap-2.5">
                            <div class="w-1 h-1 rounded-full bg-teal-500 mt-2
                                        shrink-0"></div>
                            <p class="text-xs leading-relaxed text-gray-600
                                       dark:text-gray-400 italic">
                                "{{ \Str::limit($suggestion, 120) }}"
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-gray-400 italic">
                        No feedback recorded for this office.
                    </p>
                    @endif

                </div>
                @empty
                <div class="col-span-2 py-16 flex flex-col items-center
                            justify-center text-gray-400">
                    <x-filament::icon icon="heroicon-o-chat-bubble-left-ellipsis"
                                      class="w-10 h-10 mb-3 opacity-20" />
                    <p class="text-sm italic">No feedback entries found.</p>
                </div>
                @endforelse
            </div>
            @endif

        </div>
        {{-- end preview table container --}}

    </x-filament::section>

    {{-- ══════════════════════════════════════════════════
         DOWNLOAD BUTTON
    ══════════════════════════════════════════════════ --}}
    <div class="flex justify-center pb-4">
        <x-filament::button
            wire:click="download"
            wire:loading.attr="disabled"
            size="xl"
            color="success"
            icon="heroicon-o-arrow-down-tray"
            class="min-w-72 shadow-lg"
        >
            <span wire:loading.remove wire:target="download">
                Download consolidated Excel report
            </span>
            <span wire:loading wire:target="download">
                Generating report... please wait
            </span>
        </x-filament::button>
    </div>

</div>
</x-filament-panels::page>