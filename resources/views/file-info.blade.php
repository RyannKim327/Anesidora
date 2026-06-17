@extends('app')

@section('title', 'File Info')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] w-full max-w-4xl mx-auto px-4">
    <!-- File Info Card -->
    <div id="file-card" class="hidden w-full bg-slate-800/50 rounded-3xl border border-slate-700 shadow-2xl backdrop-blur-sm overflow-hidden animate-fade-in">
        <div class="p-8 md:p-12">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
                <div class="flex flex-row items-center gap-4">
                    <div class="p-4 bg-blue-500/20 rounded-2xl">
                        <i id="file-icon" class="fa fa-file-o text-4xl text-blue-400"></i>
                    </div>
                    <div>
                        <h1 id="file-name" class="text-2xl md:text-3xl font-bold text-white mb-1 truncate max-w-xs md:max-w-md">Loading...</h1>
                        <p id="file-type" class="text-sm font-medium text-blue-400 uppercase tracking-wider"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-slate-700/50 rounded-full border border-slate-600">
                    <i class="fa fa-download text-slate-400"></i>
                    <span id="file-downloads" class="text-sm font-semibold text-slate-200">0</span>
                    <span class="text-xs text-slate-400">downloads</span>
                </div>
            </div>

            <div class="space-y-6 mb-10">
                <div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-2">Description</h3>
                    <p id="file-description" class="text-lg text-slate-200 leading-relaxed"></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-slate-900/40 rounded-2xl border border-slate-700/50">
                        <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1">Expires On</h4>
                        <p id="file-expiration" class="text-slate-300 font-medium"></p>
                    </div>
                    <div class="p-4 bg-slate-900/40 rounded-2xl border border-slate-700/50">
                        <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1">Status</h4>
                        <div id="file-status" class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-slate-300 font-medium">Public</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <button id="download-btn" class="flex-1 inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out shadow-lg hover:shadow-blue-500/20 group">
                    <i class="fa fa-download mr-3 group-hover:animate-bounce"></i>
                    Download Now
                </button>
                <button id="share-btn" class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-4 border border-slate-600 text-lg font-bold rounded-2xl text-slate-300 hover:bg-slate-700/50 transition duration-150 ease-in-out group">
                    <i class="fa fa-share-alt mr-3 group-hover:scale-110 transition-transform"></i>
                    Share Link
                </button>
                <button onclick="window.history.back()" class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-4 border border-slate-600 text-lg font-bold rounded-2xl text-slate-300 hover:bg-slate-700/50 transition duration-150 ease-in-out">
                    Back
                </button>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-card" class="hidden text-center p-12 bg-slate-800/50 rounded-3xl border border-red-500/30 shadow-2xl backdrop-blur-sm">
        <i class="fa fa-exclamation-triangle text-6xl text-red-400 mb-6"></i>
        <h2 class="text-2xl font-bold text-white mb-2">File Not Found</h2>
        <p class="text-slate-400 mb-8">The file you're looking for might have expired or doesn't exist.</p>
        <a href="/" class="inline-flex items-center px-8 py-3 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-full transition duration-150 ease-in-out">
            Return Home
        </a>
    </div>

    <!-- Password Modal -->
    <div id="password-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md"></div>
        <div class="relative w-full max-w-md bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden animate-zoom-in">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-500/20">
                    <i class="fa fa-lock text-4xl text-blue-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Protected File</h2>
                <p class="text-slate-400 mb-8">This file is encrypted. Please enter the password to access its contents.</p>

                <form id="password-form" class="space-y-4">
                    <div class="relative">
                        <input type="password" id="file-password" required
                            class="w-full px-6 py-4 bg-slate-900 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all"
                            placeholder="Enter password">
                        <i class="fa fa-key absolute right-6 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    </div>
                    <div id="password-error" class="hidden text-red-400 text-sm font-medium">
                        Incorrect password. Please try again.
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition duration-150 ease-in-out shadow-lg shadow-blue-900/20">
                        Unlock File
                    </button>
                    <div class="flex gap-4">
                        <button type="button" id="share-btn-modal" class="flex-1 py-4 border border-slate-600 text-slate-300 font-bold rounded-2xl hover:bg-slate-700/50 transition duration-150 ease-in-out">
                            <i class="fa fa-share-alt mr-2"></i> Share
                        </button>
                        <button type="button" onclick="location.href='/'" class="flex-1 py-4 text-slate-400 font-medium hover:text-slate-200 transition duration-150">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes zoom-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in { animation: fade-in 0.5s ease-out forwards; }
    .animate-zoom-in { animation: zoom-in 0.3s ease-out forwards; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const fileId = "{{ $id }}";
        const fileCard = document.getElementById('file-card');
        const errorCard = document.getElementById('error-card');
        const passwordModal = document.getElementById('password-modal');
        const passwordForm = document.getElementById('password-form');
        const passwordError = document.getElementById('password-error');

        try {
            const response = await fetch(`/api/file/${fileId}`);
            const file = await response.json();

            if (file.error) {
                errorCard.classList.remove('hidden');
                return;
            }

            // Populate file info
            document.getElementById('file-name').textContent = file.file;
            document.getElementById('file-description').textContent = file.description || 'No description provided.';
            document.getElementById('file-downloads').textContent = (file.downloads || 0).toLocaleString();

            const extension = file.file.split('.').pop();
            document.getElementById('file-type').textContent = `${extension} File`;

            // Set icon based on type
            const iconElement = document.getElementById('file-icon');
            if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(extension.toLowerCase())) iconElement.className = 'fa fa-file-image-o text-4xl text-blue-400';
            else if (['pdf'].includes(extension.toLowerCase())) iconElement.className = 'fa fa-file-pdf-o text-4xl text-red-400';
            else if (['doc', 'docx'].includes(extension.toLowerCase())) iconElement.className = 'fa fa-file-word-o text-4xl text-blue-500';
            else if (['xls', 'xlsx'].includes(extension.toLowerCase())) iconElement.className = 'fa fa-file-excel-o text-4xl text-green-500';

            // Format date
            if (file.expiration) {
                const expDate = new Date(file.expiration);
                document.getElementById('file-expiration').textContent = expDate.toLocaleDateString(undefined, {
                    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });
            } else {
                document.getElementById('file-expiration').textContent = 'Never';
            }

            // Handle protection
            if (file.password) {
                passwordModal.classList.remove('hidden');
                document.getElementById('file-status').innerHTML = `
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-slate-300 font-medium">Protected</span>
                `;

                passwordForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const password = document.getElementById('file-password').value;

                    if (password === file.password) {
                        passwordModal.classList.add('hidden');
                        fileCard.classList.remove('hidden');
                    } else {
                        passwordError.classList.remove('hidden');
                    }
                });
            } else {
                fileCard.classList.remove('hidden');
            }

            document.getElementById('download-btn').onclick = async () => {
                const password = document.getElementById('file-password').value;
                const btn = document.getElementById('download-btn');
                const originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-3"></i> Preparing...';

                try {
                    const response = await fetch(`/api/file/${fileId}/download`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ password })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Create a temporary link to trigger the download
                        const link = document.createElement('a');
                        link.href = result.download_url;
                        link.download = result.file_name;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Update download count UI
                        const downloadsSpan = document.getElementById('file-downloads');
                        downloadsSpan.textContent = (parseInt(downloadsSpan.textContent.replace(/,/g, '')) + 1).toLocaleString();
                    } else {
                        alert(result.error || 'Download failed. Please check your credentials.');
                    }
                } catch (error) {
                    console.error('Download error:', error);
                    alert('A network error occurred. Please try again.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            };

            // Share functionality
            const handleShare = (btnId) => {
                const btn = document.getElementById(btnId);
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    const originalContent = btn.innerHTML;
                    btn.innerHTML = '<i class="fa fa-check mr-3 text-green-400"></i> Copied!';
                    setTimeout(() => {
                        btn.innerHTML = originalContent;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            };

            document.getElementById('share-btn').onclick = () => handleShare('share-btn');
            document.getElementById('share-btn-modal').onclick = () => handleShare('share-btn-modal');

        } catch (error) {
            console.error('Error fetching file:', error);
            errorCard.classList.remove('hidden');
        }
    });
</script>
@endsection
