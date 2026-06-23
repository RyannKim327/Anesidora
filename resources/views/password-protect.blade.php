@extends('app')

@section('title', 'Protected File')

@section('content')
  <div class="flex flex-col items-center justify-center min-h-[60vh] w-full max-w-4xl mx-auto px-4">

    @if(!@empty($error))
        <!-- Error State -->
        <div id="error-card" class="text-center p-12 bg-slate-800/50 rounded-3xl border border-red-500/30 shadow-2xl backdrop-blur-sm">
            <i class="fa fa-exclamation-triangle text-6xl text-red-400 mb-6"></i>
            <h2 class="text-2xl font-bold text-white mb-2">File Not Found</h2>
            <p class="text-slate-400 mb-8">{{ $error ?? "The file you're looking for might have expired or doesn't exist." }}</p>
            <a href="/" class="inline-flex items-center px-8 py-3 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-full transition duration-150 ease-in-out">
                Return Home
            </a>
        </div>
    @endif

    <!-- Password Modal -->
    <div id="password-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md"></div>
        <div class="relative w-full max-w-md bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden animate-zoom-in">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-500/20">
                    <i class="fa fa-lock text-4xl text-blue-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Protected File</h2>
                <p class="text-slate-400 mb-8">This file is encrypted. Please enter the password to access its contents.</p>

                <form method="POST" id="password-form" class="space-y-4" action="/file/{{ $id }}">
                    <div class="relative">
                        <input type="password" name="password" id="file-password" required
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
        let fileData = null; // To store file data after validation

        try {
            const response = await fetch(`/api/file/${fileId}`);
            const file = await response.json();

            if (file.error) {
                errorCard.classList.remove('hidden');
                return;
            }

            fileData = file;
            // Download button handler - requires password even after viewing
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
            errorCard.classList.remove('hidden');
        }
    });
</script>


@endsection
