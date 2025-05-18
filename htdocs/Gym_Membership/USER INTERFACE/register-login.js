document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const authForm = document.querySelector('#auth-form');
    let currentAction = localStorage.getItem('currentAction') || 'login'; // Default action

    // Load the correct form based on the stored state
    if (currentAction === 'register') {
        document.querySelector('#register-tab').classList.add('active');
        renderRegisterForm();
    } else {
        document.querySelector('#login-tab').classList.add('active');
        renderLoginForm();
    }

    attachForgotPasswordListener();

    // Toggle between Login and Register
    tabButtons.forEach((button) => {
        button.addEventListener('click', function () {
            tabButtons.forEach((btn) => btn.classList.remove('active'));
            this.classList.add('active');

            if (this.id === 'register-tab') {
                currentAction = 'register';
                localStorage.setItem('currentAction', 'register');
                renderRegisterForm();
            } else {
                currentAction = 'login';
                localStorage.setItem('currentAction', 'login');
                renderLoginForm();
            }

            attachForgotPasswordListener();
        });
    });

    // Render the Login Form
    function renderLoginForm() {
        authForm.innerHTML = `
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
            <p>
                <button type="button" id="forgot-password-btn" class="link-btn">Forgot Password?</button>
            </p>
        `;
        attachForgotPasswordListener();
    }

    // Render the Register Form
    function renderRegisterForm() {
        authForm.innerHTML = `
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">REGISTER</button>
        `;
    }

    // Handle Forgot Password flow
    function attachForgotPasswordListener() {
        const forgotPasswordBtn = document.querySelector('#forgot-password-btn');
        if (forgotPasswordBtn) {
            forgotPasswordBtn.addEventListener('click', function () {
                authForm.innerHTML = `
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Enter your registered email" required>
                    </div>
                    <button type="submit" class="login-btn">SEND RESET LINK</button>
                    <p>
                        <button type="button" id="back-to-login" class="link-btn">Back to Login</button>
                    </p>
                `;

                const backToLoginBtn = document.querySelector('#back-to-login');
                backToLoginBtn.addEventListener('click', function () {
                    document.querySelector('#login-tab').click();
                });
            });
        }
    }

    // Validate the form inputs before submitting
    function validateForm() {
        const formData = new FormData(authForm);
        const username = formData.get('username');
        const password = formData.get('password');
        const email = formData.get('email');
        const confirmPassword = formData.get('confirm_password');
        const otp = formData.get('otp');
        const newPassword = formData.get('new_password');

        if (!username || !password) {
            alert("Please fill in all fields.");
            return false;
        }

        if (email && !validateEmail(email)) {
            alert("Invalid email address.");
            return false;
        }

        if (currentAction === 'register' && password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }

        if (authForm.innerHTML.includes('Enter the OTP sent to your email') && !otp) {
            alert("Please enter the OTP.");
            return false;
        }

        if (authForm.innerHTML.includes('Enter your new password') && (!newPassword || !confirmPassword)) {
            alert("Please enter and confirm your new password.");
            return false;
        }

        return true;
    }

    // Email validation function
    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    }

    // Handle form submission with AJAX
    authForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!validateForm()) return; // Prevent form submission if validation fails

        const formData = new FormData(authForm);
        let url;

        // Determine the action based on the form's content
        if (authForm.innerHTML.includes('SEND RESET LINK')) {
            url = 'forgot-password.php'; // Send reset link to email
        } else if (authForm.innerHTML.includes('VERIFY OTP')) {
            url = 'otp-verification.php'; // Server-side script for OTP verification
        } else if (authForm.innerHTML.includes('Set New Password')) {
            url = 'reset-password.php'; // Reset the password
        } else if (currentAction === 'register') {
            url = 'register.php'; // Registration logic
        } else {
            url = 'login.php'; // Login logic
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
            });

            const message = await response.text();

            if (response.ok) {
                alert(message);

                if (authForm.innerHTML.includes('SEND RESET LINK')) {
                    alert('Reset link sent! Please check your email.');
                } else if (authForm.innerHTML.includes('VERIFY OTP')) {
                    alert('OTP verified! You can now set your new password.');
                } else if (authForm.innerHTML.includes('Set New Password')) {
                    alert('Password reset successfully!');
                    document.querySelector('#login-tab').click();
                } else if (currentAction === 'register') {
                    alert('Registration successful! You can now log in.');
                    document.querySelector('#login-tab').click();
                } else {
                    window.location.href = 'index.html';
                }
            } else {
                alert(message);
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
            console.error('Error:', error);
        }
    });
});
