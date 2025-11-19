@extends('authentication.layout-authentication')

@section('content')
    <!-- Form -->
    <div class="flex flex-col flex-1 w-full lg:w-1/2">
        <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
            <div>
                <div class="mb-5 sm:mb-8">
                    <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                        Forgot Password
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Enter your email to reset your password!
                    </p>
                </div>
                <div x-data="forgotPasswordForm()">
                    <form @submit.prevent="submitForm">
                        <div class="space-y-5">
                            <!-- Email -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Email<span class="text-error-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <input type="email" x-model="email" placeholder="info@gmail.com" required
                                            :class="emailError ? 'border-error-500 focus:border-error-500' :
                                                'border-gray-300 focus:border-brand-300'"
                                            class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                        <p x-show="emailError" x-text="emailError" class="mt-1 text-sm text-error-500"></p>
                                    </div>
                                    <button type="button" @click="requestCode"
                                        :disabled="!isEmailValid || cooldownTime > 0 || isLoading"
                                        :class="!isEmailValid || cooldownTime > 0 || isLoading ?
                                            'opacity-50 cursor-not-allowed' : 'hover:text-brand-600'"
                                        class="text-sm text-brand-500 dark:text-brand-400 text-nowrap my-auto px-2">
                                        <span x-show="cooldownTime === 0 && !isLoading">Get code</span>
                                        <span x-show="cooldownTime > 0" x-text="`Wait ${cooldownTime}s`"></span>
                                        <span x-show="isLoading">Sending...</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Code Verification -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Code Verification<span class="text-error-500">*</span>
                                </label>
                                <div>
                                    <input type="text" x-model="verificationCode" placeholder="Enter verification code"
                                        required maxlength="6" pattern="[A-Za-z0-9]{6}"
                                        :class="codeError ? 'border-error-500 focus:border-error-500' :
                                            'border-gray-300 focus:border-brand-300'"
                                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    <p x-show="codeError" x-text="codeError" class="mt-1 text-sm text-error-500"></p>
                                </div>
                            </div>
                            
                            <!-- New Password -->
                            <div x-show="codeVerified">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    New Password<span class="text-error-500">*</span>
                                </label>
                                <div x-data="{ showPassword: false }" class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" x-model="password"
                                        placeholder="Enter new password" :required="codeVerified" minlength="8"
                                        :class="passwordError ? 'border-error-500 focus:border-error-500' :
                                            'border-gray-300 focus:border-brand-300'"
                                        class="h-11 w-full rounded-lg border bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    <span @click="showPassword = !showPassword"
                                        class="absolute z-30 text-gray-500 cursor-pointer right-4 top-3 dark:text-gray-400">
                                        <svg x-show="!showPassword" class="fill-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                                                fill="#98A2B3" />
                                        </svg>
                                        <svg x-show="showPassword" class="fill-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                                fill="#98A2B3" />
                                        </svg>
                                    </span>
                                </div>
                                <p x-show="passwordError" x-text="passwordError" class="mt-1 text-sm text-error-500"></p>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div x-show="codeVerified">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Confirm New Password<span class="text-error-500">*</span>
                                </label>
                                <div x-data="{ showConfirmPassword: false }" class="relative">
                                    <input :type="showConfirmPassword ? 'text' : 'password'" x-model="confirmPassword"
                                        placeholder="Confirm new password" :required="codeVerified"
                                        :class="confirmPasswordError ? 'border-error-500 focus:border-error-500' :
                                            'border-gray-300 focus:border-brand-300'"
                                        class="h-11 w-full rounded-lg border bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    <span @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute z-30 text-gray-500 cursor-pointer right-4 top-3 dark:text-gray-400">
                                        <svg x-show="!showConfirmPassword" class="fill-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                                                fill="#98A2B3" />
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="fill-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                                fill="#98A2B3" />
                                        </svg>
                                    </span>
                                </div>
                                <p x-show="confirmPasswordError" x-text="confirmPasswordError" class="mt-1 text-sm text-error-500"></p>
                            </div>
                            <!-- Submit Button -->
                            <div>
                                <button type="submit" :disabled="!canSubmit || isSubmitting"
                                    :class="!canSubmit || isSubmitting ? 'opacity-50 cursor-not-allowed bg-gray-400' :
                                        'bg-brand-500 hover:bg-brand-600'"
                                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg shadow-theme-xs">
                                    <span x-show="!isSubmitting && !codeVerified">Verify Code</span>
                                    <span x-show="!isSubmitting && codeVerified">Reset Password</span>
                                    <span x-show="isSubmitting" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Verifying...
                                    </span>
                                </button>
                            </div>

                            <div class="mt-5">
                                <p class="text-sm font-normal text-center text-gray-700 dark:text-gray-400 sm:text-start">
                                    Remember your password?
                                    <a href="/" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">Sign
                                        In</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const forgotPasswordForm = () => {
            return {
                email: '',
                verificationCode: '',
                password: '',
                confirmPassword: '',
                emailError: '',
                codeError: '',
                passwordError: '',
                confirmPasswordError: '',
                isLoading: false,
                isSubmitting: false,
                codeVerified: false,
                cooldownTime: 0,
                cooldownInterval: null,

                init() {
                    // Initialize cooldown from localStorage if exists
                    this.initializeCooldown();

                    // Watch for validation
                    this.$watch('email', () => this.validateEmail());
                    this.$watch('verificationCode', () => this.validateCode());
                    this.$watch('password', () => this.validatePassword());
                    this.$watch('confirmPassword', () => this.validateConfirmPassword());
                },

                get isEmailValid() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(this.email);
                },

                get canSubmit() {
                    if (!this.codeVerified) {
                        // Step 1: Verify code
                        return this.isEmailValid &&
                               this.verificationCode.length === 6 &&
                               !this.emailError &&
                               !this.codeError;
                    } else {
                        // Step 2: Reset password
                        return this.password.length >= 8 &&
                               this.password === this.confirmPassword &&
                               !this.passwordError &&
                               !this.confirmPasswordError;
                    }
                },

                validateEmail() {
                    this.emailError = '';
                    if (this.email && !this.isEmailValid) {
                        this.emailError = 'Please enter a valid email address';
                    }
                },

                validateCode() {
                    this.codeError = '';
                    // Auto-convert to uppercase
                    this.verificationCode = this.verificationCode.toUpperCase();
                    
                    if (this.verificationCode && !/^[A-Z0-9]{6}$/.test(this.verificationCode)) {
                        this.codeError = 'Code must be 6 characters (letters and numbers)';
                    }
                },

                validatePassword() {
                    this.passwordError = '';
                    if (this.password && this.password.length < 8) {
                        this.passwordError = 'Password must be at least 8 characters';
                    }
                    // Re-validate confirm password if it exists
                    if (this.confirmPassword) {
                        this.validateConfirmPassword();
                    }
                },

                validateConfirmPassword() {
                    this.confirmPasswordError = '';
                    if (this.confirmPassword && this.password !== this.confirmPassword) {
                        this.confirmPasswordError = 'Passwords do not match';
                    }
                },

                async requestCode() {
                    if (!this.isEmailValid || this.cooldownTime > 0) return;

                    this.isLoading = true;
                    this.emailError = '';

                    try {
                        // Request reset code
                        const response = await axios.post('/forgot-password/get-reset-code', {
                            email: this.email,
                        });

                        // Check for success response
                        if (response.status === 200) {
                            // Show success notification
                            notyf.success(response.data.message || 'Reset code sent to your email!');
                            // Start cooldown timer
                            this.startCooldown(60);
                        } else {
                            throw new Error('Invalid response format');
                        }

                    } catch (error) {
                        // Handle error response
                        let errorMessage = 'An error occurred while sending the reset code. Please try again.';
                        
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                        
                        // Show error notification
                        notyf.error(errorMessage);
                        console.error('Forgot Password error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async submitForm() {
                    if (!this.canSubmit) return;

                    this.isSubmitting = true;

                    try {
                        if (!this.codeVerified) {
                            // Step 1: Verify the code
                            await this.verifyCode();
                        } else {
                            // Step 2: Reset the password
                            await this.resetPassword();
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async verifyCode() {
                    try {
                        const response = await axios.post('/forgot-password/check-reset-code', {
                            email: this.email,
                            code: this.verificationCode
                        });

                        if (response.status === 200) {
                            // Code verified successfully
                            this.codeVerified = true;
                            this.clearCooldown();
                            notyf.success('Code verified! Now enter your new password.');
                        } else {
                            throw new Error('Invalid code verification response');
                        }
                    } catch (error) {
                        let errorMessage = 'Invalid verification code. Please try again.';
                        
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                        
                        this.codeError = errorMessage;
                        notyf.error(errorMessage);
                    }
                },

                async resetPassword() {
                    try {
                        const response = await axios.post('/forgot-password/reset', {
                            email: this.email,
                            code: this.verificationCode,
                            password: this.password,
                            password_confirmation: this.confirmPassword
                        });

                        if (response.status === 200) {
                            // Password reset successful
                            notyf.success('Password reset successful! You can now login with your new password.');
                            
                            // Redirect to login after short delay
                            setTimeout(() => {
                                location.href = '/';
                            }, 2000);
                        } else {
                            throw new Error('Invalid password reset response');
                        }
                    } catch (error) {
                        let errorMessage = 'Failed to reset password. Please try again.';
                        
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                        
                        notyf.error(errorMessage);
                    }
                },

                initializeCooldown() {
                    const savedTime = localStorage.getItem('forgotPasswordCooldown');
                    if (savedTime) {
                        const timeLeft = Math.max(0, parseInt(savedTime) - Date.now());
                        if (timeLeft > 0) {
                            this.startCooldown(Math.ceil(timeLeft / 1000));
                        }
                    }
                },

                startCooldown(seconds) {
                    this.cooldownTime = seconds;
                    localStorage.setItem('forgotPasswordCooldown', Date.now() + (seconds * 1000));

                    this.cooldownInterval = setInterval(() => {
                        this.cooldownTime--;
                        if (this.cooldownTime <= 0) {
                            this.clearCooldown();
                        }
                    }, 1000);
                },

                clearCooldown() {
                    this.cooldownTime = 0;
                    localStorage.removeItem('forgotPasswordCooldown');
                    if (this.cooldownInterval) {
                        clearInterval(this.cooldownInterval);
                        this.cooldownInterval = null;
                    }
                }
            };
        }
    </script>
@endpush
