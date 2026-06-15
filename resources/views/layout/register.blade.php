<div id="register-modal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#0f172a] border border-[#4c58a6] rounded-2xl w-full max-w-md p-8 relative">
        <button onclick="closeRegisterModal()" class="absolute top-4 right-4 text-[#64748b] hover:text-[#f8fafc]">
            <i class="fa fa-times"></i>
        </button>

        <h2 class="text-2xl font-extrabold text-[#3B82F6] mb-6">Create Account</h2>

        <form id="register-form" class="flex flex-col gap-4">
            @csrf
            <div id="register-errors" class="hidden bg-red-500/10 border border-red-500 text-red-500 px-4 py-2 rounded-lg text-sm"></div>

            <div class="flex flex-col gap-1">
                <label for="name" class="text-sm text-[#64748b]">Username</label>
                <input type="text" id="name" name="name" required class="bg-[#1e293b] border border-[#4c58a6] rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
            </div>

            <div class="flex flex-col gap-1">
                <label for="email" class="text-sm text-[#64748b]">Email Address</label>
                <input type="email" id="email" name="email" required class="bg-[#1e293b] border border-[#4c58a6] rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
            </div>

            <div class="flex flex-col gap-1">
                <label for="password" class="text-sm text-[#64748b]">Password</label>
                <input type="password" id="password" name="password" required class="bg-[#1e293b] border border-[#4c58a6] rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
            </div>

            <div class="flex flex-col gap-1">
                <label for="password_confirmation" class="text-sm text-[#64748b]">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="bg-[#1e293b] border border-[#4c58a6] rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
            </div>

            <button type="submit" id="register-submit" class="bg-[#3B82F6] text-[#f8fafc] rounded-full py-3 font-bold hover:bg-[#2563eb] transition-colors mt-4 flex items-center justify-center gap-2">
                <span>Register</span>
                <i id="register-spinner" class="fa fa-spinner fa-spin hidden"></i>
            </button>
        </form>

        <p class="text-center text-[#64748b] mt-6">
            Already have an account? <button onclick="switchToLogin()" class="text-[#3B82F6] hover:underline">Login</button>
        </p>
        </div>
        </div>

        <script>
        document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const submitBtn = document.getElementById('register-submit');
        const spinner = document.getElementById('register-spinner');
        const errorDiv = document.getElementById('register-errors');

        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = new FormData(form);

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                location.reload(); // Reload to update UI with auth state
            } else {
                errorDiv.classList.remove('hidden');
                if (result.errors) {
                    const errorList = Object.values(result.errors).flat();
                    errorDiv.innerHTML = errorList.join('<br>');
                } else {
                    errorDiv.textContent = result.message || 'An error occurred during registration.';
                }
            }
        } catch (error) {
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'A network error occurred. Please try again.';
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
        }
        });

        if (typeof openRegisterModal !== 'function') {
        window.openRegisterModal = function() {
            if (typeof closeLoginModal === 'function') closeLoginModal();
            document.getElementById('register-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    if (typeof closeRegisterModal !== 'function') {
        window.closeRegisterModal = function() {
            document.getElementById('register-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('register-modal');
        if (e.target === modal) {
            closeRegisterModal();
        }
    });
</script>
