@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold">{{ $tutorial->title }}</h1>
    <p class="mt-2 text-gray-600">Course Code: {{ $tutorial->ourse_code }}</p>

    <h2 class="text-xl mt-6 font-semibold">Langkah-langkah:</h2>
    <div class="space-y-4 mt-4" id="tutorial-steps">

    @foreach ($steps as $step)
    <div class="border p-4 rounded bg-white shadow step-item">
        <strong class="block mb-2 text-600">Langkah {{ $step->order }} ({{ ucfirst($step->type) }})</strong>

            @if($step->type == 'text')
                <div class="prose max-w-none">
                    <p class="text-gray-800">{{ $step->content }}</p>
                </div>
            @elseif($step->type == 'image')
                <div class="my-3">
                    <img src="{{ asset('storage/' . $step->content) }}" alt="Step Image" class="max-w-full h-auto rounded shadow-sm">
                </div>
            @elseif($step->type == 'code')
                <div class="my-3">
                    <pre class="bg-gray-100 p-4 rounded-md shadow-sm overflow-x-auto"><code class="text-sm font-mono text-gray-800 whitespace-pre-wrap break-words">{{ $step->content }}</code></pre>
                </div>
            @elseif($step->type == 'url')
                <div class="my-3">
                    <a href="{{ $step->content }}" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline break-words">{{ $step->content }}</a>
                </div>
            @endif
        </div>
    @endforeach



{{-- Auto-refresh setiap 5 detik --}}
<meta http-equiv="refresh" content="5">
@endsection
