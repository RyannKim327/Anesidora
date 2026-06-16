@extends('app')

@section('title', 'Upload File')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[calc(100dvh-10rem)] w-full py-10">
    <div class="bg-[#0f172a] border border-[#4c58a6] rounded-2xl w-full max-w-2xl p-8 md:p-12 shadow-2xl">
        <div class="flex flex-col items-center mb-8">
            <h1 class="text-3xl font-extrabold text-[#3B82F6]">Upload New File</h1>
            <p class="text-[#64748b] mt-2 text-center text-lg">Share your files securely with anyone, anywhere.</p>
        </div>

        <form id="upload-form" class="flex flex-col gap-6" autocomplete="off">
            @csrf
            <div id="upload-errors" class="hidden bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-xl text-sm mb-4"></div>
            <div id="upload-success" class="hidden bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-xl text-sm mb-4 text-center"></div>

            <!-- File Drop Zone -->
            <label for="file-input" id="drop-zone" class="relative group cursor-pointer border-2 border-dashed border-[#4c58a6] hover:border-[#3B82F6] rounded-2xl p-10 flex flex-col items-center transition-all bg-[#1e293b]/50">
                <input type="file" id="file-input" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div id="file-preview" class="flex flex-col items-center pointer-events-none">
                    <i class="fa fa-cloud-upload text-5xl text-[#3B82F6] mb-4 group-hover:scale-110 transition-transform"></i>
                    <span class="text-lg font-semibold text-[#f8fafc]" id="file-name-display">Drop file here or click to browse</span>
                    <span class="text-sm text-[#64748b] mt-2">Max file size: 100MB</span>
                </div>
            </label>

            <!-- Metadata Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-1">
                    <label for="name" class="text-sm font-bold text-[#64748b] uppercase tracking-wider">File Title</label>
                    <input type="text" id="name" name="name" placeholder="E.g., Project Proposal" required class="bg-[#1e293b] border border-[#4c58a6] rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] text-[#f8fafc]">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="expiration" class="text-sm font-bold text-[#64748b] uppercase tracking-wider">Expiration</label>
                    <select id="expiration" name="expiration" required class="bg-[#1e293b] border border-[#4c58a6] rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] text-[#f8fafc] appearance-none cursor-pointer">
                        <option value="1h">1 Hour</option>
                        <option value="24h" selected>24 Hours</option>
                        <option value="7d">7 Days</option>
                        <option value="30d">30 Days</option>
                        <option value="lifetime">Lifetime</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label for="description" class="text-sm font-bold text-[#64748b] uppercase tracking-wider">Description</label>
                <textarea id="description" name="description" placeholder="What's in this file?" rows="3" class="bg-[#1e293b] border border-[#4c58a6] rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] text-[#f8fafc] resize-none"></textarea>
            </div>

            <div class="flex flex-col gap-1">
                <label for="password" class="text-sm font-bold text-[#64748b] uppercase tracking-wider">Password Protection (Optional)</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Leave empty for public access" class="w-full bg-[#1e293b] border border-[#4c58a6] rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] text-[#f8fafc]">
                    <i class="fa fa-lock absolute right-5 top-4 text-[#64748b]"></i>
                </div>
            </div>

            <!-- Progress Bar -->
            <div id="progress-container" class="hidden flex flex-col gap-2">
                <div class="flex justify-between text-sm">
                    <span class="text-[#64748b]">Uploading...</span>
                    <span id="progress-percent" class="text-[#3B82F6] font-bold">0%</span>
                </div>
                <div class="w-full bg-[#1e293b] rounded-full h-2">
                    <div id="progress-bar" class="bg-[#3B82F6] h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <button type="submit" id="upload-submit" class="bg-[#3B82F6] text-[#f8fafc] rounded-full py-4 font-bold text-lg hover:bg-[#2563eb] transition-all transform hover:-translate-y-1 shadow-lg flex items-center justify-center gap-3">
                <i class="fa fa-paper-plane"></i>
                <span>Register & Upload</span>
            </button>
        </form>
    </div>
</div>

<script>
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('file-input');
    const fileNameDisplay = document.getElementById('file-name-display');
    const dropZone = document.getElementById('drop-zone');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressPercent = document.getElementById('progress-percent');
    const submitBtn = document.getElementById('upload-submit');
    const errorDiv = document.getElementById('upload-errors');
    const successDiv = document.getElementById('upload-success');
    let fileType = "doc";

    // Update file name on selection
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileNameDisplay.textContent = file.name;
            // Auto-fill title if empty
            const titleInput = document.getElementById('name');
            titleInput.value = file.name.split('.').slice(0, -1).join('.');
            if(file.type){
                const type = file.type.split('/')
                fileType = type[type.length]
            }
            console.log(fileType)
        }
    });

    // Drag and Drop Effects
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('border-[#3B82F6]', 'bg-[#3B82F6]/5');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-[#3B82F6]', 'bg-[#3B82F6]/5');

            if (eventName === 'drop' && e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                // Trigger the change event to update the UI
                fileInput.dispatchEvent(new Event('change'));
            }
        }, false);
    });

    // Form Submission using XHR for progress tracking
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        if (!fileInput.files.length) {
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'Please select a file to upload.';
            return;
        }

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        formData.set('name', `${formData.get('name')}.${fileType}`)
        // Progress Handler
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressContainer.classList.remove('hidden');
                progressBar.style.width = percent + '%';
                progressPercent.textContent = percent + '%';
            }
        });

        // Response Handler
        xhr.addEventListener('load', () => {
            submitBtn.disabled = false;
            const response = JSON.parse(xhr.responseText);

            if (xhr.status >= 200 && xhr.status < 300 && response.success) {
                successDiv.classList.remove('hidden');
                successDiv.textContent = response.message;
                form.reset();
                fileNameDisplay.textContent = 'Drop file here or click to browse';
                setTimeout(() => {
                    location.href = response.redirect || '/';
                }, 1500);
            } else {
                errorDiv.classList.remove('hidden');
                if (response.errors) {
                    const errorList = Object.values(response.errors).flat();
                    errorDiv.innerHTML = errorList.join('<br>');
                } else {
                    errorDiv.textContent = response.message || 'An error occurred during upload.';
                }
                progressContainer.classList.add('hidden');
            }
        });

        // Error Handler
        xhr.addEventListener('error', () => {
            submitBtn.disabled = false;
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'A network error occurred. Please try again.';
            progressContainer.classList.add('hidden');
        });

        xhr.open('POST', '/api/file/upload');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        // CSRF Token is already in FormData from @csrf

        submitBtn.disabled = true;
        xhr.send(formData);
    });
</script>
@endsection
