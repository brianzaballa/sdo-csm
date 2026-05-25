<x-filament-panels::page>
    <div class="space-y-8">
        {{-- ── Header Section: Filters & Quick Actions ────────────────────────── --}}
        <x-filament::section>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="p-2 bg-primary-50 dark:bg-primary-950 rounded-lg">
                            <x-filament::icon icon="heroicon-o-adjustments-horizontal" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                        </div>
                        <h2 class="text-lg font-bold tracking-tight text-gray-950 dark:text-white">Report Parameters</h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Select the period to aggregate data for the consolidated report.</p>
                </div>

                <div class="flex-1 max-w-2xl">
                    {{ $this->form }}
                </div>
            </div>
        </x-filament::section>

        {{-- ── Stats Grid ────────────────────────────────────────────────────── --}}
        @php
            $stats = $this->getStats();
            $statItems = [
                [
                    'label' => 'Total Responses',
                    'value' => $stats['total'],
                    'desc'  => 'Across all offices',
                    'icon'  => 'heroicon-m-clipboard-document-list',
                    'color' => 'text-info-600 dark:text-info-400',
                ],
                [
                    'label' => 'Offices Covered',
                    'value' => $stats['offices'],
                    'desc'  => 'Active offices in report',
                    'icon'  => 'heroicon-m-building-office-2',
                    'color' => 'text-primary-600 dark:text-primary-400',
                ],
                [
                    'label' => 'Overall Average SQD',
                    'value' => $stats['avg_sqd'],
                    'desc'  => $stats['avg_sqd_label'],
                    'icon'  => 'heroicon-m-star',
                    'color' => 'text-warning-600 dark:text-warning-400',
                ],
                [
                    'label' => 'Satisfaction Rate',
                    'value' => $stats['satisfied_pct'],
                    'desc'  => $stats['satisfied_count'] . ' Positive feedbacks',
                    'icon'  => 'heroicon-m-check-badge',
                    'color' => 'text-success-600 dark:text-success-400',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($statItems as $item)
                <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $item['label'] }}</span>
                        </div>
                        <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $item['value'] }}
                        </div>
                        <div class="flex items-center gap-x-1 text-sm font-medium {{ $item['color'] }}">
                            <x-filament::icon :icon="$item['icon']" class="h-5 w-5" />
                            <span>{{ $item['desc'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Analytics Visualization ────────────────────────────────────────── --}}
        @php
            $officeChart = $this->getOfficeChartData();
            $sqdChart    = $this->getSqdChartData();
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Offices --}}
            <x-filament::section icon="heroicon-o-building-office" icon-color="primary">
                <x-slot name="heading">Top Performing Offices</x-slot>
                <x-slot name="description">Offices with the highest volume of feedback responses.</x-slot>

                <div class="space-y-5 mt-6">
                    @forelse($officeChart as $row)
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-gray-600 dark:text-gray-400 uppercase tracking-tight">{{ $row['label'] }}</span>
                                <span class="font-mono text-primary-600">{{ number_format($row['count']) }}</span>
                            </div>
                            <div class="h-2.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-1000 ease-out"
                                     style="width: {{ max($row['pct'], 2) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                            <x-filament::icon icon="heroicon-o-circle-stack" class="w-12 h-12 mb-3 opacity-20" />
                            <p class="text-xs italic font-medium uppercase tracking-widest">No office records</p>
                        </div>
                    @endforelse
                </div>
            </x-filament::section>

            {{-- SQD Breakdown --}}
            <x-filament::section icon="heroicon-o-chart-bar-square" icon-color="warning">
                <x-slot name="heading">SQD Performance Breakdown</x-slot>
                <x-slot name="description">Average scores across eight service quality dimensions.</x-slot>

                <div class="space-y-5 mt-6">
                    @forelse($sqdChart as $row)
                        <div class="flex items-center gap-6">
                            <div class="w-28 shrink-0">
                                <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tighter truncate" title="{{ $row['label'] }}">
                                    {{ $row['label'] }}
                                </p>
                            </div>
                            <div class="flex-1 h-2.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                @php
                                    $color = match(true) {
                                        $row['avg'] >= 4.5 => 'bg-success-500',
                                        $row['avg'] >= 3.5 => 'bg-info-500',
                                        $row['avg'] >= 2.5 => 'bg-warning-500',
                                        default => 'bg-danger-500'
                                    };
                                @endphp
                                <div class="h-full {{ $color }} rounded-full transition-all duration-1000 ease-out"
                                     style="width: {{ max($row['pct'], 2) }}%"></div>
                            </div>
                            <div class="w-10 text-right">
                                <span class="text-xs font-black tabular-nums {{ str_replace('bg-', 'text-', $color) }}">
                                    {{ number_format($row['avg'], 1) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                            <x-filament::icon icon="heroicon-o-chart-pie" class="w-12 h-12 mb-3 opacity-20" />
                            <p class="text-xs italic font-medium uppercase tracking-widest">No dimension data</p>
                        </div>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        {{-- ── Data Preview Section ───────────────────────────────────────────── --}}
        @php $preview = $this->getPreviewData(); @endphp
        <x-filament::section collapsible>
            <x-slot name="heading">
                <div class="flex items-center gap-3">
                    <x-filament::icon icon="heroicon-o-eye" class="w-5 h-5 text-gray-400" />
                    <span>Report Sheet Preview</span>
                </div>
            </x-slot>

            <div class="mt-4">
                {{-- Tabs --}}
                <div class="flex flex-wrap gap-2 mb-6 p-1 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                    @foreach([
                        'office'    => ['label' => 'Office Analytics', 'icon' => 'heroicon-m-building-office-2'],
                        'quarterly' => ['label' => 'Quarterly Trend',  'icon' => 'heroicon-m-calendar-days'],
                        'q1'        => ['label' => 'Service Detail',   'icon' => 'heroicon-m-list-bullet'],
                        'feedback'  => ['label' => 'Client Voice',     'icon' => 'heroicon-m-chat-bubble-bottom-center-text'],
                    ] as $tabKey => $tab)
                        <button wire:click="setTab('{{ $tabKey }}')"
                                @class([
                                    'flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none',
                                    'bg-white dark:bg-gray-800 shadow-sm text-primary-600 ring-1 ring-gray-950/5' => $activeTab === $tabKey,
                                    'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800' => $activeTab !== $tabKey,
                                ])>
                            <x-filament::icon :icon="$tab['icon']" class="w-4 h-4" />
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </div>

                {{-- Preview Table Content --}}
                <div class="relative overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 shadow-sm min-h-[400px]">
                    {{-- Loading Overlay --}}
                    <div wire:loading.delay.flex class="absolute inset-0 z-10 bg-white/50 dark:bg-gray-900/50 backdrop-blur-[1px] items-center justify-center">
                        <x-filament::loading-indicator class="w-10 h-10 text-primary-600" />
                    </div>

                    @if($activeTab === 'office')
                        {{-- Office Sheet Table --}}
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">Category</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">Item</th>
                                    @foreach($preview['services'] ?? [] as $service)
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 text-center min-w-[150px]">
                                            {{ \Str::limit($service->name, 25) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($preview['rows'] ?? [] as $row)
                                    <tr @class([
                                        'hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors',
                                        'bg-primary-50/30 dark:bg-primary-900/10 font-medium' => $row['type'] === 'rating'
                                    ])>
                                        <td class="px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">{{ $row['category'] }}</td>
                                        <td class="px-6 py-3.5 text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $row['item'] }}</td>
                                        @foreach($row['values'] ?? [] as $val)
                                            <td class="px-6 py-3.5 text-sm text-center">
                                                @if($row['type'] === 'adjectival')
                                                    @php
                                                        $badgeColor = match($val) {
                                                            'Outstanding' => 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400',
                                                            'Very Satisfactory' => 'bg-info-100 text-info-700 dark:bg-info-900/30 dark:text-info-400',
                                                            'Satisfactory' => 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400',
                                                            'Unsatisfactory' => 'bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400',
                                                            'Poor' => 'bg-danger-200 text-danger-800 dark:bg-danger-900/50 dark:text-danger-300',
                                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeColor }}">
                                                        {{ $val }}
                                                    </span>
                                                @else
                                                    <span @class(['font-bold text-primary-600' => $row['type'] === 'rating', 'text-gray-900 dark:text-white' => $row['type'] !== 'rating'])>
                                                        {{ $val }}
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @elseif($activeTab === 'quarterly')
                        {{-- Quarterly Trend Table --}}
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">Office Name</th>
                                    @foreach(['Q1', 'Q2', 'Q3', 'Q4', 'Total'] as $col)
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 text-center">{{ $col }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($preview['rows'] ?? [] as $row)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors">
                                        <td class="px-6 py-3.5 text-sm font-bold text-gray-800 dark:text-gray-200">{{ $row['office'] }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center tabular-nums">{{ number_format($row['q1']) }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center tabular-nums">{{ number_format($row['q2']) }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center tabular-nums">{{ number_format($row['q3']) }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center tabular-nums">{{ number_format($row['q4']) }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center font-black text-primary-600 tabular-nums bg-primary-50/10">{{ number_format($row['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @elseif($activeTab === 'q1')
                        {{-- Detail Table --}}
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">Office</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 text-center">Responses</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 text-center">Numerical Rating</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 text-center">Adjectival Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($preview['rows'] ?? [] as $row)
                                    <tr @class([
                                        'hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors',
                                        'bg-primary-50 dark:bg-primary-900/20 font-bold' => !empty($row['is_total'])
                                    ])>
                                        <td class="px-6 py-3.5 text-sm text-gray-800 dark:text-gray-200">{{ $row['office'] }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center tabular-nums">{{ number_format($row['total']) }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center font-bold text-primary-600">{{ $row['numerical'] }}</td>
                                        <td class="px-6 py-3.5 text-sm text-center">
                                            @if($row['adjectival'] !== 'N/A')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-400">
                                                    {{ $row['adjectival'] }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic text-xs">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @elseif($activeTab === 'feedback')
                        {{-- Feedback Preview --}}
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($preview['rows'] ?? [] as $row)
                                <div class="p-5 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $row['office'] }}</h4>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                            {{ $row['count'] }} FEEDBACKS
                                        </span>
                                    </div>
                                    @if(!empty($row['suggestions']))
                                        <div class="space-y-3">
                                            @foreach($row['suggestions'] as $suggestion)
                                                <div class="relative pl-6">
                                                    <x-filament::icon icon="heroicon-m-chat-bubble-left" class="absolute left-0 top-0.5 w-4 h-4 text-primary-400" />
                                                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400 italic">"{{ $suggestion }}"</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic">No qualitative feedback available for this office.</p>
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-2 py-12 text-center text-gray-400 italic">No feedback entries found.</div>
                            @endforelse
                        </div>
                    @endif
                </div>

                @if(empty($preview['rows']) && $activeTab !== 'feedback')
                    <div class="py-20 flex flex-col items-center justify-center text-gray-400">
                        <x-filament::icon icon="heroicon-o-no-symbol" class="w-12 h-12 mb-3 opacity-20" />
                        <p class="text-sm italic font-medium">No results found for current filters.</p>
                    </div>
                @endif
            </div>
        </x-filament::section>

        {{-- ── Report Contents Index ──────────────────────────────────────────── --}}
        <x-filament::section>
            <x-slot name="heading">What's inside the generated report?</x-slot>
            <x-slot name="description">The Excel file exports a comprehensive multi-sheet workbook containing:</x-slot>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                @php
                    $contents = [
                        ['title' => 'Office Sheets',     'icon' => 'heroicon-o-building-office-2', 'color' => 'primary', 'desc' => 'Individual sheets for every office with demographics and ratings.'],
                        ['title' => 'Quarterly Summary', 'icon' => 'heroicon-o-calendar-days',     'color' => 'success', 'desc' => 'Consolidated overview of performance across all quarters.'],
                        ['title' => 'Service Detail',    'icon' => 'heroicon-o-table-cells',       'color' => 'info',    'desc' => 'Granular service-level counts and numerical/adjectival ratings.'],
                        ['title' => 'Feedback Bank',     'icon' => 'heroicon-o-chat-bubble-left-ellipsis', 'color' => 'warning', 'desc' => 'Collection of client suggestions and comments for review.'],
                        ['title' => 'SQD Distribution',  'icon' => 'heroicon-o-chart-bar',         'color' => 'danger',  'desc' => 'Statistical breakdown of satisfaction scores per dimension.'],
                        ['title' => 'Demographics',      'icon' => 'heroicon-o-users',             'color' => 'primary', 'desc' => 'Aggregated respondent profile by age, gender, and type.'],
                    ];
                @endphp

                @foreach($contents as $item)
                    <div class="flex gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors group">
                        <div @class([
                            'w-12 h-12 shrink-0 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110',
                            "bg-{$item['color']}-50 text-{$item['color']}-600 dark:bg-{$item['color']}-900/20 dark:text-{$item['color']}-400"
                        ])>
                            <x-filament::icon :icon="$item['icon']" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $item['title'] }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        {{-- ── Final Action: Download ────────────────────────────────────────── --}}
        <div class="flex items-center justify-center py-8">
            <x-filament::button
                wire:click="download"
                wire:loading.attr="disabled"
                size="xl"
                color="success"
                icon="heroicon-o-arrow-down-tray"
                class="min-w-[300px] shadow-lg shadow-success-500/20"
            >
                <span wire:loading.remove wire:target="download">Download Consolidated Excel Report</span>
                <span wire:loading wire:target="download">Generating Report... Please Wait</span>
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
