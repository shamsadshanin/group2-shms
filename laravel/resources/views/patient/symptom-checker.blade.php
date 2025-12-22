@extends('layouts.app')

@section('title', 'AI Symptom Checker')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">AI Symptom Checker</h1>
        <a href="{{ route('patient.dashboard') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left Column: Input Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-blue-600 p-4">
                    <h2 class="text-white font-bold text-lg"><i class="fas fa-robot mr-2"></i>Describe Symptoms</h2>
                    <p class="text-blue-100 text-sm">Our AI analyzes historical medical records to predict potential conditions.</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('patient.check-symptoms') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="symptoms" class="block text-gray-700 font-bold mb-2">What are you feeling?</label>
                            <textarea name="symptoms" id="symptoms" rows="5"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                placeholder="e.g., I have a high fever, severe headache, and nausea..." required></textarea>
                            <p class="text-xs text-gray-500 mt-2">Try to be as specific as possible for better accuracy.</p>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:scale-105">
                            <i class="fas fa-stethoscope mr-2"></i> Analyze Symptoms
                        </button>
                    </form>
                </div>
            </div>

            {{-- Diagnosis Result Block --}}
            @if(session('prediction'))
            <div class="mt-8 bg-white border-l-4 {{ session('prediction')['score'] > 70 ? 'border-green-500' : 'border-yellow-500' }} rounded-r-xl shadow-lg p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Preliminary Diagnosis</h3>
                        <p class="text-2xl font-bold text-blue-700 mb-2">{{ session('prediction')['disease'] }}</p>
                        <p class="text-gray-600">{{ session('prediction')['advice'] }}</p>
                    </div>
                    <div class="text-center">
                        <div class="radial-progress text-blue-600 font-bold text-sm" style="--value:{{ session('prediction')['score'] }}; --size:3rem;">
                            {{ number_format(session('prediction')['score'], 0) }}%
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Confidence</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-red-500 italic">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Disclaimer: This is an AI-generated prediction based on historical data. It is not a substitute for professional medical advice. Please consult a doctor.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('patient.book-appointment') }}" class="text-sm font-bold text-blue-600 hover:underline">
                            Book an Appointment with a specialist &rarr;
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column: Previous Checks --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Recent Checks</h3>
                @if(isset($history) && $history->count() > 0)
                    <div class="space-y-4">
                        @foreach($history as $item)
                        <div class="text-sm border-b border-gray-100 pb-2 last:border-0">
                            <p class="font-semibold text-gray-700">{{ $item->disease->DiseaseName ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500 truncate">"{{ $item->Description }}"</p>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->InputDate)->diffForHumans() }}</span>
                                <span class="text-xs font-bold text-blue-500">
                                    {{ isset($item->disease->Confidence_Score) ? round($item->disease->Confidence_Score) . '%' : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">No previous history.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
