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
                                        required maxlength="6" pattern="[0-9]{6}"
                                        :class="codeError ? 'border-error-500 focus:border-error-500' :
                                            'border-gray-300 focus:border-brand-300'"
                                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    <p x-show="codeError" x-text="codeError" class="mt-1 text-sm text-error-500"></p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" :disabled="!canSubmit || isSubmitting"
                                    :class="!canSubmit || isSubmitting ? 'opacity-50 cursor-not-allowed bg-gray-400' :
                                        'bg-brand-500 hover:bg-brand-600'"
                                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg shadow-theme-xs">
                                    <span x-show="!isSubmitting">Verify Code</span>
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
                emailError: '',
                codeError: '',
                isLoading: false,
                isSubmitting: false,
                cooldownTime: 0,
                cooldownInterval: null,

                init() {
                    // Initialize cooldown from localStorage if exists
                    this.initializeCooldown();

                    // Watch email changes for validation
                    this.$watch('email', () => this.validateEmail());
                    this.$watch('verificationCode', () => this.validateCode());
                },

                get isEmailValid() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(this.email);
                },

                get canSubmit() {
                    return this.isEmailValid &&
                        this.verificationCode.length === 6 &&
                        !this.emailError &&
                        !this.codeError;
                },

                validateEmail() {
                    this.emailError = '';
                    if (this.email && !this.isEmailValid) {
                        this.emailError = 'Please enter a valid email address';
                    }
                },

                validateCode() {
                    this.codeError = '';
                    if (this.verificationCode && !/^\d{6}$/.test(this.verificationCode)) {
                        this.codeError = 'Code must be 6 digits';
                    }
                },

                async requestCode() {
                    if (!this.isEmailValid || this.cooldownTime > 0) return;

                    this.isLoading = true;
                    this.emailError = '';

                    try {
                        // Simulate API call - replace with actual endpoint
                        await new Promise(resolve => setTimeout(resolve, 1000));

                        // Start cooldown timer
                        this.startCooldown(60);

                        // Show success message (you might want to add a success state)
                        console.log('Verification code sent to:', this.email);

                    } catch (error) {
                        this.emailError = 'Failed to send code. Please try again.';
                        console.error('Error sending code:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async submitForm() {
                    if (!this.canSubmit) return;

                    this.isSubmitting = true;
                    this.emailError = '';
                    this.codeError = '';

                    try {
                        // Simulate API call - replace with actual endpoint
                        await new Promise(resolve => setTimeout(resolve, 1500));

                        console.log('Verifying code:', {
                            email: this.email,
                            code: this.verificationCode
                        });

                        // Clear cooldown on successful verification
                        this.clearCooldown();

                        // Redirect or show success message
                        // window.location.href = '/reset-password';

                    } catch (error) {
                        this.codeError = 'Invalid verification code. Please try again.';
                        console.error('Error verifying code:', error);
                    } finally {
                        this.isSubmitting = false;
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
