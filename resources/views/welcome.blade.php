@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">

        {{-- Hero Section --}}
        <section id="home" class="relative overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 py-24 sm:py-32">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 50%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <img src="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png"
                     alt="SDO Legazpi City Logo"
                     class="h-24 w-24 sm:h-32 sm:w-32 mx-auto rounded-full border-4 border-white/30 shadow-xl mb-8">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 tracking-tight">
                    Client Satisfaction Measurement
                </h1>
                <p class="text-lg sm:text-xl text-blue-100/80 max-w-3xl mx-auto mb-10 leading-relaxed">
                    Help us improve our services by sharing your experience.
                    Your feedback drives better public service for everyone.
                </p>
                <a href="{{ route('survey') }}"
                   class="inline-block bg-teal-500 hover:bg-teal-400 text-white font-semibold px-8 py-4 rounded-xl text-lg shadow-lg transition-all hover:shadow-xl hover:scale-105">
                    Take the Survey
                </a>
            </div>
        </section>

        {{-- About Us Section --}}
        <section id="about" class="py-20 sm:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 text-center">About Us</h2>
                    <div class="w-16 h-1 bg-teal-500 mx-auto mb-10 rounded-full"></div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Schools Division Office of Legazpi City</h3>
                            <p class="text-gray-600 leading-relaxed">
                                The Schools Division Office (SDO) of Legazpi City is dedicated to providing quality
                                education and efficient public service to the community. As part of the Department of
                                Education (DepEd), we strive to uphold the highest standards of governance and
                                client satisfaction in all our transactions.
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">What is CSM?</h3>
                            <p class="text-gray-600 leading-relaxed">
                                The <strong>Client Satisfaction Measurement (CSM)</strong> is a standardized survey
                                tool mandated by the <strong>Anti-Red Tape Authority (ARTA)</strong> under the
                                <strong>Ease of Doing Business and Efficient Government Service Delivery Act (RA 11032)</strong>.
                                It measures the quality of government service delivery across key dimensions including
                                responsiveness, reliability, and overall satisfaction.
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Why Your Feedback Matters</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Every response helps us identify areas for improvement, recognize excellent service,
                                and ensure that our offices remain accountable to the public we serve. Your honest
                                feedback directly contributes to better, more efficient government services.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Units and Sections Section --}}
        <section id="units-sections" class="py-20 sm:py-28 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 text-center">Units and Sections</h2>
                <div class="w-16 h-1 bg-teal-500 mx-auto mb-4 rounded-full"></div>
                <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
                    Our division is composed of dedicated units and sections working together to serve the
                    Schools Division Office of Legazpi City and its stakeholders.
                </p>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($offices as $office)
                        <div onclick="openOfficeModal({{ $office->id }})"
                             class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-block bg-teal-100 text-teal-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $office->code ?? '—' }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $office->services_count }} {{ Str::plural('service', $office->services_count) }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $office->name }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Office Modal --}}
        <div id="office-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4" role="dialog">
            <div id="modal-backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-8">
                <button id="modal-close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div id="modal-body">
                    <div class="flex items-center justify-between mb-6">
                        <span id="modal-code" class="inline-block bg-teal-100 text-teal-800 text-sm font-semibold px-3 py-1 rounded-full"></span>
                        <span id="modal-services-count" class="text-sm text-gray-400"></span>
                    </div>

                    <h3 id="modal-name" class="text-xl font-bold text-gray-900 mb-6"></h3>

                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Feedback Summary</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-teal-50 rounded-xl p-4 text-center">
                                <p id="modal-satisfaction" class="text-2xl font-bold text-teal-700"></p>
                                <p class="text-xs text-teal-600 mt-1">Avg Satisfaction</p>
                                <p class="text-xs text-teal-500">out of 5</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p id="modal-responses" class="text-2xl font-bold text-blue-700"></p>
                                <p class="text-xs text-blue-600 mt-1">Total Responses</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Services Offered</h4>
                        <div id="modal-services" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const offices = @json($officesJson);

            function openOfficeModal(id) {
                const office = offices.find(o => o.id === id);
                if (!office) return;

                document.getElementById('modal-code').textContent = office.code;
                document.getElementById('modal-name').textContent = office.name;
                document.getElementById('modal-satisfaction').textContent = office.avg_satisfaction;
                document.getElementById('modal-responses').textContent = office.responses_count;
                document.getElementById('modal-services-count').textContent =
                    office.services.length + ' service' + (office.services.length !== 1 ? 's' : '');

                const container = document.getElementById('modal-services');
                container.innerHTML = '';
                if (office.services.length === 0) {
                    container.innerHTML = '<span class="text-sm text-gray-400">No services listed.</span>';
                } else {
                    office.services.forEach(s => {
                        const span = document.createElement('span');
                        span.className = 'inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1.5 rounded-lg';
                        span.textContent = s;
                        container.appendChild(span);
                    });
                }

                document.getElementById('office-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeOfficeModal() {
                document.getElementById('office-modal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            document.getElementById('modal-close').addEventListener('click', closeOfficeModal);
            document.getElementById('modal-backdrop').addEventListener('click', closeOfficeModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeOfficeModal();
            });
        </script>

        {{-- Contact Section --}}
        {{-- <section id="contact" class="py-20 sm:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 text-center">Contact Us</h2>
                <div class="w-16 h-1 bg-teal-500 mx-auto mb-10 rounded-full"></div>

                <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12">
                    <div class="space-y-8">
                        <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Office Information</h3>
                            <div class="space-y-5">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Address</p>
                                        <p class="text-gray-600 mt-1">
                                            Schools Division Office of Legazpi City<br>
                                            Legazpi City, Albay, Philippines
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Phone</p>
                                        <p class="text-gray-600 mt-1">(052) 555-1234</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Email</p>
                                        <p class="text-gray-600 mt-1">sdolegazpi@deped.gov.ph</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Send Us a Message</h3>

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-colors @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-colors @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea id="message" name="message" rows="5"
                                          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-colors @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section> --}}

        {{-- Footer --}}
        <footer class="bg-gray-900 text-gray-400 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; {{ date('Y') }} SDO Legazpi City — Client Satisfaction Measurement. All rights reserved.</p>
            </div>
        </footer>

    </div>
@endsection
