@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Add error and success messages here -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <h1 class="text-3xl font-bold">{{ $tutorial->title }}</h1>
    <p class="mt-2 text-gray-600">{{ $tutorial->description }}</p>

    <!-- Add Step Button -->
    <div class="mt-6">
        <button type="button" onclick="showAddForm()" id="add-step-btn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            + Tambah Langkah
        </button>
    </div>

    <!-- Add Step Form (Hidden by default) -->
    <div id="add-step-form" class="mt-4 border p-4 rounded hidden">
        <h3 class="font-semibold">Tambah Langkah Baru</h3>
        <form id="step-form" enctype="multipart/form-data" method="POST" action="{{ route('tutorial.steps.store', $tutorial->id) }}">
            @csrf
            <div class="mt-3">
                <label for="order" class="block text-sm font-medium text-gray-700">Urutan</label>
                <input type="number" name="order" id="order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="1" value="{{ $tutorial->detailTutorials->count() + 1 }}" readonly>
            </div>

            <div class="mt-3">
                <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="showContentInput(this.value)">
                    <option value="text">Text</option>
                    <option value="image">Gambar</option>
                    <option value="code">Kode</option>
                    <option value="url">URL</option>
                </select>
            </div>

            <!-- Text input -->
            <div id="content-text" class="mt-3 content-input">
                <label for="content-text-input" class="block text-sm font-medium text-gray-700">Konten Text</label>
                <textarea name="content" id="content-text-input" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <!-- Image input -->
            <div id="content-image" class="mt-3 content-input hidden">
                <label for="content-image-input" class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                <input type="file" name="image" id="content-image-input" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-500">Format yang didukung: JPG, PNG, GIF. Maks 2MB.</p>
            </div>

            <!-- Code input -->
            <div id="content-code" class="mt-3 content-input hidden">
                <label for="content-code-input" class="block text-sm font-medium text-gray-700">Potongan Kode</label>
                <textarea name="content" id="content-code-input" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 font-mono text-sm p-3 focus:border-blue-500 focus:ring focus:ring-blue-200" spellcheck="false"></textarea>
            </div>

            <!-- URL input -->
            <div id="content-url" class="mt-3 content-input hidden">
                <label for="content-url-input" class="block text-sm font-medium text-gray-700">URL</label>
                <input type="url" name="content" id="content-url-input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="https://">
            </div>

            <div class="mt-3">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="status" class="rounded border-gray-300 text-blue-600 shadow-sm">
                    <span class="ml-2 text-sm text-gray-700">Tampilkan</span>
                </label>
            </div>

            <div class="mt-4 flex space-x-3">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Simpan
                </button>
                <button type="button" onclick="hideAddForm()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
            </div>
        </form>
    </div>


    <h2 class="text-xl mt-6 font-semibold">Langkah-langkah:</h2>
    <div class="space-y-3 mt-4" id="tutorial-steps">
        @foreach ($tutorial->detailTutorials as $step)
            <div class="border p-4 rounded step-item" data-step-id="{{ $step->id }}">
                <div class="flex justify-between items-center">
                    <strong>Langkah {{ $step->order }}</strong>
                    <div class="flex space-x-2">
                        <button type="button" class="toggle-visibility px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                onclick="toggleContent(this, '{{ $step->id }}-content')"
                                data-visible="{{ $step->status ? 'true' : 'false' }}">
                            {{ $step->status ? 'Sembunyikan' : 'Tampilkan' }}
                        </button>
                        <button type="button" class="edit-step px-2 py-1 bg-yellow-200 rounded hover:bg-yellow-300"
                                onclick="showEditModal('{{ $step->id }}')">
                            Edit
                        </button>
                        <button type="button" class="delete-step px-2 py-1 bg-red-200 rounded hover:bg-red-300"
                                onclick="confirmDelete('{{ $step->id }}')">
                            Hapus
                        </button>
                    </div>
                </div>
                <div id="{{ $step->id }}-content" class="mt-2 {{ $step->status ? '' : 'hidden' }}">
                    @if($step->type == 'text')
                        <div class="prose max-w-none">
                            <p class="text-gray-800">{{ $step->content }}</p>
                        </div>
                    @elseif($step->type == 'image')
                        <div class="my-3">
                            <img src="{{ asset('storage/'.$step->content) }}" alt="Step Image" class="max-w-full h-auto rounded shadow-sm">
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
                <div class="text-xs text-gray-500 mt-2">
                    Dibuat: {{ $step->created_at->format('d M Y H:i') }} |
                    Diperbarui: {{ $step->updated_at->format('d M Y H:i') }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Edit Step Modal -->
    <div id="edit-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Edit Langkah</h3>
                <button type="button" onclick="hideEditModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="edit-form" enctype="multipart/form-data" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mt-3">
                    <label for="edit-order" class="block text-sm font-medium text-gray-700">Urutan</label>
                    <input type="number" name="order" id="edit-order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="1">
                </div>

                <div class="mt-3">
                    <label for="edit-type" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="type" id="edit-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="showEditContentInput(this.value)">
                        <option value="text">Text</option>
                        <option value="image">Gambar</option>
                        <option value="code">Kode</option>
                        <option value="url">URL</option>
                    </select>
                </div>

                <!-- Text input -->
                <div id="edit-content-text" class="mt-3 edit-content-input">
                    <label for="edit-content-text-input" class="block text-sm font-medium text-gray-700">Konten Text</label>
                    <textarea name="content" id="edit-content-text-input" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                </div>

                <!-- Image input -->
                <div id="edit-content-image" class="mt-3 edit-content-input hidden">
                    <label for="edit-content-image-input" class="block text-sm font-medium text-gray-700">Upload Gambar Baru (Opsional)</label>
                    <input type="file" name="image" id="edit-content-image-input" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">Format yang didukung: JPG, PNG, GIF. Maks 2MB.</p>
                    <div id="current-image-container" class="mt-2 hidden">
                        <p class="text-sm text-gray-700">Gambar saat ini:</p>
                        <img id="current-image" src="" alt="Current Image" class="mt-1 max-w-xs h-auto rounded shadow-sm">
                    </div>
                </div>

                <!-- Code input -->
                <div id="edit-content-code" class="mt-3 edit-content-input hidden">
                    <label for="edit-content-code-input" class="block text-sm font-medium text-gray-700">Potongan Kode</label>
                    <textarea name="content" id="edit-content-code-input" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 font-mono text-sm p-3 focus:border-blue-500 focus:ring focus:ring-blue-200" spellcheck="false"></textarea>
                </div>

                <!-- URL input -->
                <div id="edit-content-url" class="mt-3 edit-content-input hidden">
                    <label for="edit-content-url-input" class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" name="content" id="edit-content-url-input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="https://">
                </div>

                <div class="mt-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="status" id="edit-status" class="rounded border-gray-300 text-blue-600 shadow-sm">
                        <span class="ml-2 text-sm text-gray-700">Tampilkan</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
            <p class="my-4 text-gray-600">Apakah Anda yakin ingin menghapus langkah ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showAddForm() {
        document.getElementById('add-step-form').classList.remove('hidden');
        document.getElementById('add-step-btn').classList.add('hidden');
    }

    function hideAddForm() {
        document.getElementById('add-step-form').classList.add('hidden');
        document.getElementById('add-step-btn').classList.remove('hidden');
    }

    function showContentInput(type) {
        // Hide all content inputs
        const contentInputs = document.querySelectorAll('.content-input');
        contentInputs.forEach(input => {
            input.classList.add('hidden');

            // Find all input/textarea elements inside and disable them
            const formElements = input.querySelectorAll('input, textarea');
            formElements.forEach(el => {
                el.disabled = true;
                if (el.name === 'content') {
                    el.name = 'content_unused';
                }
            });
        });

        // Show the selected content input
        const selectedInput = document.getElementById('content-' + type);
        selectedInput.classList.remove('hidden');

        // Enable the inputs in the selected type
        const activeElements = selectedInput.querySelectorAll('input, textarea');
        activeElements.forEach(el => {
            el.disabled = false;
            if (el.id === 'content-' + type + '-input') {
                el.name = 'content';
            }
        });

        // Special handling for image type
        if (type === 'image') {
            document.getElementById('content-image-input').name = 'image';
        }
    }

    function showEditContentInput(type) {
        // Hide all edit content inputs
        const contentInputs = document.querySelectorAll('.edit-content-input');
        contentInputs.forEach(input => {
            input.classList.add('hidden');

            // Find all input/textarea elements inside and disable them
            const formElements = input.querySelectorAll('input, textarea');
            formElements.forEach(el => {
                if (el.name === 'content') {
                    el.name = 'content_unused';
                }
                el.disabled = true;
            });
        });

        // Show the selected content input
        const selectedInput = document.getElementById('edit-content-' + type);
        selectedInput.classList.remove('hidden');

        // Enable the inputs in the selected type
        const activeElements = selectedInput.querySelectorAll('input, textarea');
        activeElements.forEach(el => {
            el.disabled = false;
            if (el.id === 'edit-content-' + type + '-input') {
                el.name = 'content';
            }
        });

        // Special handling for image type
        if (type === 'image') {
            document.getElementById('edit-content-image-input').name = 'image';

            // Show current image if available
            if (document.getElementById('current-image').src) {
                document.getElementById('current-image-container').classList.remove('hidden');
            }
        }
    }

    function showEditModal(stepId) {
        // Show edit modal
        document.getElementById('edit-modal').classList.remove('hidden');

        // Set the form action URL
        document.getElementById('edit-form').action = `/tutorials/steps/${stepId}`;

        // Fetch step data and populate form
        fetchStepData(stepId);
    }

    function hideEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    function fetchStepData(stepId) {
        // Make an AJAX request to get step data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/tutorials/steps/${stepId}/edit`, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const step = JSON.parse(xhr.responseText);
                populateEditForm(step);
            } else {
                alert('Failed to fetch step data');
            }
        };
        xhr.send();
    }

    function populateEditForm(step) {
        // Set basic fields
        document.getElementById('edit-order').value = step.order;
        document.getElementById('edit-type').value = step.type;
        document.getElementById('edit-status').checked = step.status;

        // Reset all content fields
        document.getElementById('edit-content-text-input').value = '';
        document.getElementById('edit-content-code-input').value = '';
        document.getElementById('edit-content-url-input').value = '';
        document.getElementById('current-image-container').classList.add('hidden');

        // Set content based on step type
        if (step.type === 'text') {
            document.getElementById('edit-content-text-input').value = step.content;
        } else if (step.type === 'code') {
            document.getElementById('edit-content-code-input').value = step.content;
        } else if (step.type === 'url') {
            document.getElementById('edit-content-url-input').value = step.content;
        } else if (step.type === 'image') {
            // Set current image
            document.getElementById('current-image').src = '/storage/' + step.content;
            document.getElementById('current-image-container').classList.remove('hidden');
        }

        // Show the appropriate content input
        showEditContentInput(step.type);
    }

    function confirmDelete(stepId) {
        // Set the delete form action
        document.getElementById('delete-form').action = `/tutorials/steps/${stepId}`;

        // Show the modal
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    function toggleContent(button, contentId) {
        const contentElement = document.getElementById(contentId);
        const isVisible = button.getAttribute('data-visible') === 'true';

        if (isVisible) {
            // Hide content
            contentElement.classList.add('hidden');
            button.textContent = 'Tampilkan';
            button.setAttribute('data-visible', 'false');
        } else {
            // Show content
            contentElement.classList.remove('hidden');
            button.textContent = 'Sembunyikan';
            button.setAttribute('data-visible', 'true');
        }

        // Send request to server to update status
        const stepId = contentId.split('-')[0];
        updateStepVisibility(stepId, !isVisible);
    }

    function updateStepVisibility(stepId, isVisible) {
        // Create and submit form to update visibility in database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/tutorials/steps/' + stepId + '/toggle-visibility', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.send('status=' + (isVisible ? '1' : '0'));
    }

    // Form validation and submission
    document.getElementById('step-form').addEventListener('submit', function(e) {
        const selectedType = document.getElementById('type').value;

        // Make sure we're submitting the right content field
        if (selectedType === 'text') {
            if (!document.getElementById('content-text-input').value.trim()) {
                e.preventDefault();
                alert('Please enter text content');
            }
        } else if (selectedType === 'code') {
            // Ensure the code input's name is set to content
            document.getElementById('content-code-input').name = 'content';
            if (!document.getElementById('content-code-input').value.trim()) {
                e.preventDefault();
                alert('Please enter code content');
            }
        } else if (selectedType === 'url') {
            // Ensure the URL input's name is set to content
            document.getElementById('content-url-input').name = 'content';
            if (!document.getElementById('content-url-input').value.trim()) {
                e.preventDefault();
                alert('Please enter URL content');
            }
        } else if (selectedType === 'image') {
            if (!document.getElementById('content-image-input').files.length) {
                e.preventDefault();
                alert('Please select an image file');
            }
        }
    });

    document.getElementById('edit-form').addEventListener('submit', function(e) {
        const selectedType = document.getElementById('edit-type').value;

        // Make sure we're submitting the right content field
        if (selectedType === 'text') {
            if (!document.getElementById('edit-content-text-input').value.trim()) {
                e.preventDefault();
                alert('Please enter text content');
            }
        } else if (selectedType === 'code') {
            // Ensure the code input's name is set to content
            document.getElementById('edit-content-code-input').name = 'content';
            if (!document.getElementById('edit-content-code-input').value.trim()) {
                e.preventDefault();
                alert('Please enter code content');
            }
        } else if (selectedType === 'url') {
            // Ensure the URL input's name is set to content
            document.getElementById('edit-content-url-input').name = 'content';
            if (!document.getElementById('edit-content-url-input').value.trim()) {
                e.preventDefault();
                alert('Please enter URL content');
            }
        }
        // For image, it's OK if no new image is selected
    });

    // Initialize form on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial content type
        const initialType = document.getElementById('type').value;
        showContentInput(initialType);

        // Close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-red-100, .bg-green-100');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 1s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 1000);
            });
        }, 5000);

        // Add click event listeners to close modals when clicking outside
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        document.getElementById('edit-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideEditModal();
            }
        });
    });
</script>
@endsection
