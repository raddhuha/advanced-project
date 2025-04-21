@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-[50px]">
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">Halo, {{ Auth::user()->email }} 👋</h1>

    <div class="mb-4">
        <a href="{{ route('tutorials.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
           + Buat Master Tutorial
        </a>
    </div>

    <div class="container mx-auto px-4 py-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Tutorial Kamu</h2>

        @if ($tutorials->count())
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table id="tutorials-table" class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presentasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Finished</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diperbarui Pada</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($tutorials as $tutorial)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $tutorial->course_code }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $tutorial->title }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <a href="{{ route('public.presentation', $tutorial->url_presentation) }}" target="_blank" class="text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-external-link-alt mr-1"></i> URL Presentasi
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <a href="{{ route('public.finished', $tutorial->url_finished) }}" target="_blank" class="text-green-500 hover:text-green-700">
                                        <i class="fas fa-external-link-alt mr-1"></i> URL Finished
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $tutorial->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $tutorial->updated_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('tutorials.show', $tutorial->id) }}" class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-300 transition">
                                            Tutorial
                                        </a>
                                        <a href="{{ route('tutorials.edit', $tutorial->id) }}" class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-300 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('tutorials.destroy', $tutorial->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-400 text-white rounded hover:bg-red-300 transition"
                                                onclick="return confirm('Yakin hapus tutorial ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tutorials->links() }}
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-gray-500">Belum ada tutorial yang tersedia.</p>
            </div>
        @endif
    </div>
</div>
@endsection
