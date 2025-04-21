@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-[50px]">
    <h1 class="text-2xl font-bold mb-4">Halo, {{ Auth::user()->name }} 👋</h1>

    <div class="mb-4">
        <a href="{{ route('tutorials.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
           + Buat Master Tutorial
        </a>
    </div>

    <h2 class="text-xl font-semibold mb-2">Daftar Tutorial Kamu:</h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($tutorials->count())
        <ul class="space-y-2">
            @foreach ($tutorials as $tutorial)
                <li class="border p-3 rounded shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">{{ $tutorial->course_code }} - {{ $tutorial->title }}</span>
                        <div class="space-x-2">
                            <a href="{{ route('public.presentation', $tutorial->url_presentation) }}" target="_blank" class="text-yellow-500">URL Presentation</a>
                            <a href="{{ route('public.finished', $tutorial->url_finished) }}" target="_blank" class="text-green-500">URL Finished</a>
                            <a href="{{ route('tutorials.show', $tutorial->id) }}" class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-300">Lihat</a>
                            <a href="{{ route('tutorials.edit', $tutorial->id) }}" class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-300">Edit</a>
                            <form action="{{ route('tutorials.destroy', $tutorial->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-400 text-white rounded hover:bg-red-300"
                                    onclick="return confirm('Yakin hapus tutorial ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            {{ $tutorials->links() }}
        </div>
    @else
        <p>Kamu belum punya tutorial. Yuk buat sekarang!</p>
    @endif
</div>
@endsection
