@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Tutorial</h1>

    <form method="POST" action="{{ route('tutorials.update', $tutorial->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Judul</label>
            <input type="text" name="title" value="{{ old('title', $tutorial->title) }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Kode Mata Kuliah - Kelas</label>
            <select name="course_code" class="w-full border border-gray-300 p-2 rounded" required>
                @if(isset($courses) && is_array($courses))
                    @foreach($courses as $course)
                        <option value="{{ $course['kdmk'] }}" data-name="{{ $course['nama'] }}" {{ $tutorial->course_code == $course['kdmk'] ? 'selected' : '' }}>
                            {{ $course['kdmk'] }} - {{ $course['nama'] }}
                        </option>
                    @endforeach
                @else
                    <option value="{{ $tutorial->course_code }}" selected>{{ $tutorial->course_code }}</option>
                @endif
            </select>
            <input type="hidden" name="course_name" id="course_name" value="{{ $tutorial->course_name }}">
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 text-black rounded">Batal</a>
        </div>
    </form>
</div>

<script>
    // Set course_name on page load for selected option
    window.addEventListener('DOMContentLoaded', function() {
        const selectedOption = document.querySelector('#course_select option:checked');
        if (selectedOption) {
            const courseName = selectedOption.getAttribute('data-name');
            document.getElementById('course_name').value = courseName;
        }
    });

    // Update course_name when selection changes
    document.getElementById('course_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const courseName = selectedOption.getAttribute('data-name');
        document.getElementById('course_name').value = courseName;
    });
</script>
@endsection
