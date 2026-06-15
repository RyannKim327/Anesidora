<div id="login-modal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#0f172a] border border-[#4c58a6] rounded-2xl w-full max-w-md p-8 relative">
        <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-[#64748b] hover:text-[#f8fafc]">
            <i class="fa fa-times"></i>
        </button>

        <h2 class="text-2xl font-extrabold text-[#3B82F6] mb-6">Welcome Back</h2>

        <form id="login-form" class="flex flex-col gap-4">
            @csrf
            <div id="login-errors" class="hidden bg-red-500/10 border border-red-500 text-red-500 px-4 py-2 rounded-lg text-sm"></div>

            <div class="flex flex-col gap-1">
                <label for="login_email" class="text-sm text-[#64748b]">Email Address</label>
                <input type="email" id="login_email" name="email" required class="bg-[#1e293b] border border-[#4c58a6] rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
            </div>

            <div class="flex flex-col gap-1">
                <label for="login_password" class="text-sm text-[#64748b]">Password</label>
                <input type="password" id="login_password" name="password" required class="bg-[#1e293b] border border-[#4c58a6] rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
            </div>

            <div class="flex flex-row justify-between items-center text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-[#1e293b] border-[#4c58a6] text-[#3B82F6]">
                    <span class="text-[#64748b]">Remember me</span>
                </label>
                <a href="/password/reset" class="text-[#3B82F6] hover:underline">Forgot password?</a>
            </div>

            <button type="submit" id="login-submit" class="bg-[#3B82F6] text-[#f8fafc] rounded-full py-3 font-bold hover:bg-[#2563eb] transition-colors mt-4 flex items-center justify-center gap-2">
                <span>Login</span>
                <i id="login-spinner" class="fa fa-spinner fa-spin hidden"></i>
            </button>
        </form>

        <p class="text-center text-[#64748b] mt-6">
            Don't have an account? <button onclick="switchToRegister()" class="text-[#3B82F6] hover:underline">Register</button>
        </p>
        </div>
        </div>

        <script>
        document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const submitBtn = document.getElementById('login-submit');
        const spinner = document.getElementById('login-spinner');
        const errorDiv = document.getElementById('login-errors');

        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = new FormData(form);

        try {
            const response = await fetch('/api/login', {
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
                errorDiv.textContent = result.message || 'The provided credentials do not match our records.';
            }
        } catch (error) {
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'A network error occurred. Please try again.';
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
        }
        });

        if (typeof openLoginModal !== 'function') {
        window.openLoginModal = function() {
            if (typeof closeRegisterModal === 'function') closeRegisterModal();
            document.getElementById('login-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    if (typeof closeLoginModal !== 'function') {
        window.closeLoginModal = function() {
            document.getElementById('login-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    if (typeof switchToRegister !== 'function') {
        window.switchToRegister = function() {
            closeLoginModal();
            openRegisterModal();
        }
    }

    if (typeof switchToLogin !== 'function') {
        window.switchToLogin = function() {
            closeRegisterModal();
            openLoginModal();
        }
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('login-modal');
        if (e.target === modal) {
            closeLoginModal();
        }
    });
</script>
