@extends('app')

@section('title', 'File Info')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] w-full max-w-4xl mx-auto px-4">
    <!-- File Info Card -->
    <div id="file-card" class="w-full bg-slate-800/50 rounded-3xl border border-slate-700 shadow-2xl backdrop-blur-sm overflow-hidden animate-fade-in">
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
                <p id="uploader">Anonymous</p>
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
        try {
            const response = await fetch(`/api/file/${fileId}`);
            const file = await response.json();

            if (file.error) {
                errorCard.classList.remove('hidden');
                return;
            }

            // Show file info directly for unprotected files
            if (fileCard) fileCard.classList.remove('hidden');

            // Populate file info (will be shown when unlocked or if unprotected)
            document.getElementById('file-name').textContent = file.file;
            document.getElementById("uploader").textContent = `Uploaded by ${file.user.name || "Anonymous"}`
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

            document.getElementById('download-btn').onclick = async () => {
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

            if (document.getElementById('share-btn')) {
                document.getElementById('share-btn').onclick = () => handleShare('share-btn');
            }
            if (document.getElementById('share-btn-modal')) {
                document.getElementById('share-btn-modal').onclick = () => handleShare('share-btn-modal');
            }

        } catch (error) {
            console.error('Error fetching file:', error);
            if (errorCard) errorCard.classList.remove('hidden');
        }
    });
</script>
@endsection
