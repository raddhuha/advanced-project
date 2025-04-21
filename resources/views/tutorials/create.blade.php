@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow mt-[50px]">
    <h1 class="text-2xl font-bold mb-4">Buat Tutorial Baru</h1>

    <form method="POST" action="{{ route('tutorials.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Judul Tutorial</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Kode Mata Kuliah / Kelas</label>
            <select name="course_code" id="course_select" class="w-full border p-2 rounded" required>
                <option value="">Pilih Mata Kuliah</option>
                @if(isset($courses) && is_array($courses))
                    @foreach($courses as $course)
                        <option value="{{ $course['kdmk'] }}" data-name="{{ $course['nama'] }}">{{ $course['kdmk'] }} - {{ $course['nama'] }}</option>
                    @endforeach
                @endif
            </select>
            <input type="hidden" name="course_name" id="course_name">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('dashboard') }}" class="text-gray-500 mr-4">Batal</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('course_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const courseName = selectedOption.getAttribute('data-name');
        document.getElementById('course_name').value = courseName;
    });
</script>
@endsection
