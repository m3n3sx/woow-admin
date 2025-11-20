/**
 * DarkMode Component
 *
 * Manages dark mode detection, state, and application across WordPress interfaces.
 * Handles system preference detection, time-based switching, and manual overrides.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class DarkMode {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        
        // Configuration
        this.storageKey = 'woow-dark-mode';
        this.bodyClass = 'woow-dark-mode';
        
        // Night time hours (8 PM - 6 AM)
        this.nightStartHour = 20; // 8 PM
        this.nightEndHour = 6;    // 6 AM
        
        // State
        this.mode = 'auto'; // 'auto' | 'enabled' | 'disabled'
        this.systemPreference = null;
        this.timeCheckInterval = null;
        
        // Media query references for cleanup
        this.preferenceMediaQuery = null;
        this.preferenceHandler = null;
        
        // Error handling state (Requirement 1.5, 2.4)
        this.localStorageAvailable = this.checkLocalStorageAvailability();
        this.systemPreferenceAvailable = false;
        this.cssLoadingFailed = false;
        this.ajaxRetryCount = 0;
        this.maxAjaxRetries = 2;
        
        // Debug mode
        this.debug = window.woowAdminData?.debug || false;
        
        // Initialize
        this.init();
    }

    /**
     * Initialize dark mode system
     * 
     * Implements Requirements 10.2, 10.3, 10.5, 6.3, 1.5, 2.4:
     * - Reads localStorage before server requests (10.2)
     * - Applies preference immediately to prevent flash (10.3, 6.3)
     * - Syncs localStorage to server if mismatch detected (10.5)
     * - Removes preload class to enable transitions (6.1, 6.2)
     * - Handles localStorage unavailable gracefully (1.5, 2.4)
     * - Handles system preference detection failure (1.5)
     */
    init() {
        try {
            this.log('Initializing...');
            
            // Add preload class to prevent transitions during initial load (Requirement 6.3)
            document.body.classList.add('woow-preload');
            
            // Load mode from localStorage first (instant, Requirement 10.2)
            // Handles localStorage unavailable gracefully (Requirement 1.5)
            this.mode = this.getMode();
            
            // Apply theme immediately to prevent flash (Requirement 10.3, 6.3)
            this.apply();
            
            // Remove preload class after a brief delay to enable smooth transitions (Requirements 6.1, 6.2)
            // This prevents flash while allowing transitions for subsequent changes
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    document.body.classList.remove('woow-preload');
                    this.log('Transitions enabled');
                });
            });
            
            // Check for localStorage/server mismatch and sync if needed (Requirement 10.5)
            this.checkAndSyncMismatch();
            
            // Set up system preference detection (Requirement 1.5 - handles failure)
            this.watchSystemPreference();
            
            // Set up time-based checking (every minute) (Requirement 2.4 - fallback)
            this.watchTimeChanges();
            
            // Bind events for settings UI
            this.bindEvents();
            
            // Check CSS loading status
            this.checkCSSLoading();
            
            this.log('Initialized with mode:', this.mode);
            this.log('localStorage available:', this.localStorageAvailable);
            this.log('System preference available:', this.systemPreferenceAvailable);
        } catch (error) {
            this.logError('Initialization failed:', error);
            // Continue with defaults even if initialization fails
            this.mode = 'auto';
            this.apply();
        }
    }

    /**
     * Check if localStorage is available
     * 
     * Implements Requirement 1.5:
     * "WHEN the system preference cannot be detected, 
     *  THE Dark Mode System SHALL default to light mode"
     * 
     * Handles private browsing, disabled localStorage, quota exceeded, etc.
     *
     * @returns {boolean} True if localStorage is available and working
     */
    checkLocalStorageAvailability() {
        try {
            const testKey = '__woow_storage_test__';
            localStorage.setItem(testKey, 'test');
            localStorage.removeItem(testKey);
            this.log('localStorage is available');
            return true;
        } catch (error) {
            this.logError('localStorage is not available:', error.message);
            this.logError('Reason:', this.getStorageErrorReason(error));
            return false;
        }
    }

    /**
     * Get human-readable reason for localStorage error
     *
     * @param {Error} error - The error object
     * @returns {string} Human-readable error reason
     */
    getStorageErrorReason(error) {
        if (error.name === 'QuotaExceededError') {
            return 'Storage quota exceeded';
        } else if (error.name === 'SecurityError') {
            return 'Private browsing or security restrictions';
        } else if (error.message && error.message.includes('not available')) {
            return 'localStorage disabled in browser settings';
        }
        return 'Unknown storage error';
    }

    /**
     * Check CSS loading status
     * 
     * Implements Requirement 1.5, 2.4:
     * "Handle CSS loading failures"
     * 
     * Detects if dark mode CSS failed to load and provides fallback
     */
    checkCSSLoading() {
        try {
            // Check if dark mode stylesheet is loaded
            const darkModeStylesheet = Array.from(document.styleSheets).find(sheet => {
                try {
                    return sheet.href && sheet.href.includes('dark-mode.css');
                } catch (e) {
                    // Cross-origin stylesheet, can't access
                    return false;
                }
            });

            if (!darkModeStylesheet && this.shouldUseDarkMode()) {
                this.cssLoadingFailed = true;
                this.logError('Dark mode CSS failed to load');
                
                // Show warning to user
                if (this.woow && typeof this.woow.showNotification === 'function') {
                    this.woow.showNotification(
                        'Dark mode styles failed to load. Using fallback styles.',
                        'warning',
                        { duration: 5000 }
                    );
                }
                
                // Apply inline fallback styles
                this.applyFallbackStyles();
            } else if (darkModeStylesheet) {
                this.log('Dark mode CSS loaded successfully');
            }
        } catch (error) {
            this.logError('Failed to check CSS loading status:', error);
            // Continue without CSS check
        }
    }

    /**
     * Apply inline fallback styles when CSS fails to load
     * 
     * Provides basic dark mode styling as fallback
     */
    applyFallbackStyles() {
        try {
            const style = document.createElement('style');
            style.id = 'woow-dark-mode-fallback';
            style.textContent = `
                body.woow-dark-mode {
                    background-color: #111827 !important;
                    color: #e5e7eb !important;
                }
                body.woow-dark-mode #wpadminbar {
                    background-color: #1f2937 !important;
                    color: #f9fafb !important;
                }
                body.woow-dark-mode #adminmenuback,
                body.woow-dark-mode #adminmenuwrap,
                body.woow-dark-mode #adminmenu {
                    background-color: #1f2937 !important;
                }
                body.woow-dark-mode #wpcontent,
                body.woow-dark-mode #wpbody-content {
                    background-color: #111827 !important;
                    color: #e5e7eb !important;
                }
            `;
            document.head.appendChild(style);
            this.log('Fallback styles applied');
        } catch (error) {
            this.logError('Failed to apply fallback styles:', error);
        }
    }

    /**
     * Log message (only in debug mode)
     *
     * @param {...any} args - Arguments to log
     */
    log(...args) {
        if (this.debug || window.woowAdminData?.debug) {
            console.log('[DarkMode]', ...args);
        }
    }

    /**
     * Log error (always logged)
     *
     * @param {...any} args - Arguments to log
     */
    logError(...args) {
        console.error('[DarkMode]', ...args);
    }

    /**
     * Log warning (always logged)
     *
     * @param {...any} args - Arguments to log
     */
    logWarn(...args) {
        console.warn('[DarkMode]', ...args);
    }

    /**
     * Check for localStorage/server mismatch and sync if needed
     * 
     * Implements Requirements 10.5, 1.5:
     * "WHEN the server-side preference differs from localStorage, 
     *  THE Dark Mode System SHALL use the localStorage value and 
     *  sync to server on next save operation"
     * 
     * Handles localStorage unavailable gracefully (1.5)
     */
    checkAndSyncMismatch() {
        try {
            // Skip if localStorage is not available (Requirement 1.5)
            if (!this.localStorageAvailable) {
                this.log('Skipping mismatch check - localStorage unavailable');
                return;
            }
            
            // Get localStorage value
            const localMode = localStorage.getItem(this.storageKey);
            
            // Get server value from localized data
            const serverMode = window.woowDarkModeConfig?.mode || window.woowAdminData?.darkMode;
            
            // If both exist and differ, localStorage takes precedence (Requirement 10.5)
            if (localMode && serverMode && localMode !== serverMode) {
                this.log('Mismatch detected - localStorage:', localMode, 'server:', serverMode);
                this.log('Using localStorage value and syncing to server');
                
                // Sync localStorage value to server
                this.syncToServer(localMode);
            } else if (localMode && serverMode) {
                this.log('localStorage and server in sync:', localMode);
            } else if (localMode) {
                this.log('Using localStorage value (no server value):', localMode);
            } else if (serverMode) {
                this.log('Using server value (no localStorage):', serverMode);
            }
        } catch (error) {
            this.logError('Failed to check localStorage/server mismatch:', error);
            // Continue initialization even if check fails (Requirement 1.5)
        }
    }

    /**
     * Get current mode from localStorage with fallback
     * 
     * Implements Requirements 10.2, 10.3, 10.4, 1.5:
     * - Reads localStorage before server requests (10.2)
     * - Provides instant preference loading (10.3)
     * - Falls back to server when localStorage unavailable (10.4)
     * - Handles localStorage unavailable gracefully (1.5)
     *
     * @returns {string} Current mode ('auto' | 'enabled' | 'disabled')
     */
    getMode() {
        try {
            // Try localStorage first if available (Requirement 10.2)
            if (this.localStorageAvailable) {
                const stored = localStorage.getItem(this.storageKey);
                
                // Validate stored value
                if (stored && ['auto', 'enabled', 'disabled'].includes(stored)) {
                    this.log('Mode from localStorage:', stored);
                    return stored;
                }
            } else {
                this.logWarn('localStorage unavailable, using server-side preference');
            }
            
            // Fall back to server-side setting if available (Requirement 10.4)
            const serverMode = window.woowDarkModeConfig?.mode || window.woowAdminData?.darkMode;
            if (serverMode && ['auto', 'enabled', 'disabled'].includes(serverMode)) {
                this.log('Mode from server:', serverMode);
                return serverMode;
            }
            
            // Default to auto (Requirement 1.5)
            this.log('Mode defaulting to: auto');
            return 'auto';
        } catch (error) {
            // Unexpected error reading mode
            this.logError('Error reading mode:', error);
            
            // Try server-side setting as last resort
            try {
                const serverMode = window.woowDarkModeConfig?.mode || window.woowAdminData?.darkMode;
                if (serverMode && ['auto', 'enabled', 'disabled'].includes(serverMode)) {
                    this.log('Mode from server (after error):', serverMode);
                    return serverMode;
                }
            } catch (serverError) {
                this.logError('Error reading server mode:', serverError);
            }
            
            // Default to auto as final fallback (Requirement 1.5)
            this.log('Mode defaulting to: auto (after error)');
            return 'auto';
        }
    }

    /**
     * Save mode to localStorage and sync to server
     * 
     * Implements Requirements 10.1, 10.5, 1.5:
     * - Saves to localStorage immediately for instant application (10.1)
     * - Syncs to server in background for persistence across devices (10.5)
     * - Handles localStorage unavailable gracefully (1.5)
     *
     * @param {string} mode - Mode to save ('auto' | 'enabled' | 'disabled')
     */
    save(mode) {
        // Validate mode
        if (!['auto', 'enabled', 'disabled'].includes(mode)) {
            this.logError('Invalid mode:', mode);
            return;
        }
        
        this.mode = mode;
        
        // Save to localStorage first if available (instant, Requirement 10.1)
        if (this.localStorageAvailable) {
            try {
                localStorage.setItem(this.storageKey, mode);
                this.log('Saved mode to localStorage:', mode);
            } catch (error) {
                this.logError('Failed to save to localStorage:', error);
                this.logError('Reason:', this.getStorageErrorReason(error));
                
                // Show warning to user
                if (this.woow && typeof this.woow.showNotification === 'function') {
                    this.woow.showNotification(
                        'Unable to save preference locally. Changes will be saved to server only.',
                        'warning',
                        { duration: 3000 }
                    );
                }
            }
        } else {
            this.logWarn('localStorage unavailable, saving to server only');
        }
        
        // Apply the new mode immediately
        this.apply();
        
        // Sync to server in background (Requirement 10.5)
        this.syncToServer(mode);
    }

    /**
     * Sync mode preference to server via AJAX
     * 
     * Implements Requirements 3.4, 3.5, 2.4:
     * - Saves preference to server for persistence across sessions (3.5)
     * - Provides user feedback on save success/failure (3.4)
     * - Handles AJAX save failures with retry logic (2.4)
     * 
     * Handles errors gracefully - localStorage still works even if server sync fails
     *
     * @param {string} mode - Mode to sync ('auto' | 'enabled' | 'disabled')
     * @param {number} retryAttempt - Current retry attempt (default: 0)
     * @returns {Promise<boolean>} True if sync successful, false otherwise
     */
    async syncToServer(mode, retryAttempt = 0) {
        // Get AJAX configuration from localized script data
        const config = window.woowDarkModeConfig || {};
        const ajaxUrl = config.ajaxUrl || window.woowAdminData?.ajaxUrl || '/wp-admin/admin-ajax.php';
        const nonce = config.nonce || window.woowAdminData?.nonce || '';
        
        if (!nonce) {
            this.logWarn('No nonce available for server sync');
            
            // Show warning notification
            if (this.woow && typeof this.woow.showNotification === 'function') {
                this.woow.showNotification(
                    'Dark mode preference saved locally only (no security token available)',
                    'warning'
                );
            }
            
            return false;
        }
        
        try {
            this.log('Syncing to server:', mode, retryAttempt > 0 ? `(retry ${retryAttempt}/${this.maxAjaxRetries})` : '');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('action', 'woow_save_dark_mode');
            formData.append('nonce', nonce);
            formData.append('mode', mode);
            
            // Send AJAX request with timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
            
            const response = await fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            // Check if response is OK
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Parse response
            const data = await response.json();
            
            if (data.success) {
                this.log('Successfully synced to server:', data.data);
                
                // Reset retry count on success
                this.ajaxRetryCount = 0;
                
                // Show success notification (Requirement 3.4)
                if (this.woow && typeof this.woow.showNotification === 'function') {
                    const modeNames = {
                        'auto': 'Auto',
                        'enabled': 'Always Dark',
                        'disabled': 'Always Light'
                    };
                    this.woow.showNotification(
                        `Dark mode set to: ${modeNames[mode] || mode}`,
                        'success',
                        { duration: 2000 }
                    );
                }
                
                return true;
            } else {
                throw new Error(data.data?.message || 'Server returned error');
            }
        } catch (error) {
            this.logError('Server sync error:', error.message);
            
            // Retry logic (Requirement 2.4)
            if (retryAttempt < this.maxAjaxRetries) {
                this.logWarn(`Retrying server sync (${retryAttempt + 1}/${this.maxAjaxRetries})...`);
                
                // Wait before retrying (exponential backoff)
                const delay = Math.min(1000 * Math.pow(2, retryAttempt), 5000);
                await new Promise(resolve => setTimeout(resolve, delay));
                
                // Retry
                return this.syncToServer(mode, retryAttempt + 1);
            }
            
            // Max retries reached
            this.logError('Server sync failed after', this.maxAjaxRetries, 'retries');
            this.ajaxRetryCount = retryAttempt;
            
            // Show error notification (Requirement 3.4)
            if (this.woow && typeof this.woow.showNotification === 'function') {
                const message = error.name === 'AbortError' 
                    ? 'Server request timed out. Dark mode saved locally only.'
                    : 'Failed to sync dark mode preference to server. Saved locally only.';
                
                this.woow.showNotification(
                    message,
                    'warning',
                    { duration: 5000 }
                );
            }
            
            // Don't throw - localStorage still works
            return false;
        }
    }

    /**
     * Determine if dark mode should be active
     *
     * @returns {boolean} True if dark mode should be enabled
     */
    shouldUseDarkMode() {
        // Manual override: always dark
        if (this.mode === 'enabled') {
            return true;
        }
        
        // Manual override: always light
        if (this.mode === 'disabled') {
            return false;
        }
        
        // Auto mode: check system preference first
        if (this.mode === 'auto') {
            // Try system preference
            if (this.systemPreference !== null) {
                return this.systemPreference;
            }
            
            // Fall back to time-based detection
            return this.isNightTime();
        }
        
        // Default to light mode
        return false;
    }

    /**
     * Check if current time is night (8 PM - 6 AM)
     * 
     * Uses browser's local time, which should match the user's timezone.
     * For server-side timezone-aware calculations, see PHP is_night_time() method.
     * 
     * Note: JavaScript uses browser timezone, PHP uses WordPress timezone setting.
     * This is intentional - we want to respect the user's actual local time,
     * not necessarily the server's configured timezone.
     *
     * @returns {boolean} True if night time
     */
    isNightTime() {
        const now = new Date();
        const hour = now.getHours();
        
        // Night is from 20:00 (8 PM) to 05:59 (before 6 AM)
        // This handles the midnight boundary correctly
        if (hour >= this.nightStartHour || hour < this.nightEndHour) {
            return true;
        }
        
        return false;
    }

    /**
     * Apply current theme to DOM
     * 
     * Implements Requirements 6.3, 6.1, 6.2:
     * - Applies theme to body class
     * - Handles errors gracefully
     */
    apply() {
        try {
            const shouldBeDark = this.shouldUseDarkMode();
            const body = document.body;
            
            if (!body) {
                this.logError('document.body not available');
                return;
            }
            
            if (shouldBeDark) {
                if (!body.classList.contains(this.bodyClass)) {
                    body.classList.add(this.bodyClass);
                    this.log('Applied dark theme');
                }
            } else {
                if (body.classList.contains(this.bodyClass)) {
                    body.classList.remove(this.bodyClass);
                    this.log('Applied light theme');
                }
            }
        } catch (error) {
            this.logError('Failed to apply theme:', error);
            // Continue without applying theme
        }
    }

    /**
     * Watch for system preference changes
     * 
     * Implements Requirements 1.1, 1.2, 1.3, 1.4, 1.5, 9.2:
     * - Detects system dark mode preference using prefers-color-scheme media query
     * - Sets up event listener for real-time preference changes
     * - Includes fallback logic when system preference is unavailable (1.5)
     * - Performance: Completes within 50ms as required (9.2)
     */
    watchSystemPreference() {
        const startTime = performance.now();
        
        try {
            // Check if prefers-color-scheme is supported (Requirement 1.5)
            if (!window.matchMedia) {
                this.logWarn('matchMedia not supported - system preference detection disabled');
                this.logWarn('Falling back to time-based detection (Requirement 2.4)');
                this.systemPreference = null;
                this.systemPreferenceAvailable = false;
                return;
            }
            
            // Create media query for dark mode preference
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Check if media query is valid
            if (mediaQuery.media === 'not all') {
                this.logWarn('prefers-color-scheme not supported - falling back to time-based detection');
                this.systemPreference = null;
                this.systemPreferenceAvailable = false;
                return;
            }
            
            // Set initial preference (synchronous, fast)
            this.systemPreference = mediaQuery.matches;
            this.systemPreferenceAvailable = true;
            
            const detectionTime = performance.now() - startTime;
            this.log('System preference detected:', this.systemPreference ? 'dark' : 'light', `(${detectionTime.toFixed(2)}ms)`);
            
            // Verify performance requirement (should be < 50ms) (Requirement 9.2)
            if (detectionTime > 50) {
                this.logWarn('System preference detection took longer than expected:', detectionTime.toFixed(2) + 'ms');
            }
            
            // Listen for changes (event-driven, no polling)
            const handler = (e) => {
                try {
                    const changeStartTime = performance.now();
                    
                    this.systemPreference = e.matches;
                    this.log('System preference changed:', this.systemPreference ? 'dark' : 'light');
                    
                    // Re-apply if in auto mode (Requirements 1.2, 1.3)
                    if (this.mode === 'auto') {
                        this.apply();
                    }
                    
                    const changeTime = performance.now() - changeStartTime;
                    this.log('Preference change handled in', changeTime.toFixed(2) + 'ms');
                } catch (error) {
                    this.logError('Error handling preference change:', error);
                }
            };
            
            // Use addEventListener if available (modern browsers)
            if (mediaQuery.addEventListener) {
                mediaQuery.addEventListener('change', handler);
                this.log('System preference change listener registered (addEventListener)');
            } else if (mediaQuery.addListener) {
                // Fallback for older browsers (Safari < 14) - deprecated but still supported
                mediaQuery.addListener(handler);
                this.log('System preference change listener registered (addListener - legacy)');
            } else {
                this.logWarn('Cannot register preference change listener - no supported method');
                this.logWarn('System preference will not update automatically');
            }
            
            // Store reference for cleanup
            this.preferenceMediaQuery = mediaQuery;
            this.preferenceHandler = handler;
            
        } catch (error) {
            // System preference detection failed (Requirement 1.5)
            this.logError('System preference detection failed:', error);
            this.logWarn('Falling back to time-based detection (Requirement 2.4)');
            this.systemPreference = null;
            this.systemPreferenceAvailable = false;
        }
        
        const totalTime = performance.now() - startTime;
        this.log('System preference setup completed in', totalTime.toFixed(2) + 'ms');
    }

    /**
     * Watch for time changes (check every minute)
     * 
     * Implements Requirements 2.1, 2.2, 2.3, 2.4:
     * - Periodic time checks to detect transitions at 8 PM and 6 AM boundaries
     * - Only active when in auto mode and no system preference is available
     * - Performance: Checks every 60 seconds (Requirements 2.2, 2.3)
     * - Ensures transitions occur within 1 minute of boundary crossing
     * - Fallback when system preference unavailable (2.4)
     */
    watchTimeChanges() {
        try {
            // Clear any existing interval
            if (this.timeCheckInterval) {
                clearInterval(this.timeCheckInterval);
                this.log('Cleared existing time check interval');
            }
            
            // Store current night status for comparison
            let previousNightStatus = this.isNightTime();
            
            // Check every minute (60000ms) - Requirements 2.2, 2.3
            this.timeCheckInterval = setInterval(() => {
                try {
                    const currentHour = new Date().getHours();
                    
                    // Only check if in auto mode and no system preference (Requirement 2.4)
                    if (this.mode === 'auto' && this.systemPreference === null) {
                        const currentNightStatus = this.isNightTime();
                        
                        // If night status changed, re-apply theme (Requirements 2.2, 2.3)
                        if (previousNightStatus !== currentNightStatus) {
                            const transition = currentNightStatus ? 'day → night' : 'night → day';
                            const boundary = currentNightStatus ? '8 PM boundary' : '6 AM boundary';
                            
                            this.log(`Time-based transition detected: ${transition} (${boundary})`);
                            this.log(`Current hour: ${currentHour}:00`);
                            
                            // Update stored status
                            previousNightStatus = currentNightStatus;
                            
                            // Apply new theme with transition
                            this.apply();
                            
                            // Show notification if available
                            if (this.woow && typeof this.woow.showNotification === 'function') {
                                const message = currentNightStatus 
                                    ? 'Dark mode activated (night time)' 
                                    : 'Light mode activated (day time)';
                                this.woow.showNotification(message, 'info');
                            }
                        } else {
                            // Log periodic check (only in debug mode)
                            this.log(`Time check: ${currentHour}:00 - ${currentNightStatus ? 'night' : 'day'} mode`);
                        }
                    } else {
                        // Log why time-based checking is skipped
                        const reason = this.mode !== 'auto' 
                            ? `mode is ${this.mode}` 
                            : 'system preference available';
                        this.log(`Time check skipped: ${reason}`);
                    }
                } catch (error) {
                    this.logError('Error in time check interval:', error);
                }
            }, 60000); // 60 seconds
            
            this.log('Time-based checking enabled (every 60s)');
            this.log(`Initial night status: ${previousNightStatus ? 'night' : 'day'}`);
            this.log(`Night hours: ${this.nightStartHour}:00 - ${this.nightEndHour}:00`);
        } catch (error) {
            this.logError('Failed to set up time-based checking:', error);
            // Continue without time-based checking (Requirement 2.4)
        }
    }

    /**
     * Bind event listeners for settings UI
     * 
     * Implements Requirements 3.4, 3.5:
     * - Listens for mode selector changes (3.4)
     * - Applies theme immediately without reload (3.4)
     * - Saves to localStorage and server (3.5)
     * - Provides user feedback (3.4)
     */
    bindEvents() {
        try {
            // Listen for mode selector changes in settings
            const modeSelector = document.querySelector('select[name="dark_mode[mode]"], select[name="dark_mode"], select[name="woow_dark_mode"], #woow-dark-mode-select');
            
            if (modeSelector) {
                modeSelector.addEventListener('change', async (e) => {
                    try {
                        const newMode = e.target.value;
                        this.log('Mode changed via settings:', newMode);
                        
                        // Validate mode value
                        if (!['auto', 'enabled', 'disabled'].includes(newMode)) {
                            this.logError('Invalid mode value:', newMode);
                            if (this.woow && typeof this.woow.showNotification === 'function') {
                                this.woow.showNotification(
                                    'Invalid dark mode selection',
                                    'error'
                                );
                            }
                            return;
                        }
                        
                        // Save mode (updates localStorage and applies theme immediately)
                        // Requirement 3.4: Immediate theme application without reload
                        this.save(newMode);
                        
                        // Update status text if element exists
                        const statusElement = document.getElementById('woow-dark-mode-status');
                        if (statusElement) {
                            let statusText = '';
                            if (newMode === 'auto') {
                                statusText = 'Automatic detection is active. The theme will adapt based on your system preferences or time of day.';
                            } else if (newMode === 'enabled') {
                                statusText = 'Dark mode is always enabled, regardless of system preferences or time.';
                            } else {
                                statusText = 'Light mode is always enabled, regardless of system preferences or time.';
                            }
                            statusElement.textContent = statusText;
                        }
                        
                        // Note: User feedback notification is now handled in syncToServer()
                        // This provides more accurate feedback about save success/failure
                    } catch (error) {
                        this.logError('Error handling mode change:', error);
                        if (this.woow && typeof this.woow.showNotification === 'function') {
                            this.woow.showNotification(
                                'Failed to change dark mode setting',
                                'error'
                            );
                        }
                    }
                });
                
                this.log('Settings UI event listener bound');
            } else {
                this.log('Settings UI selector not found (may not be on settings page)');
            }
        } catch (error) {
            this.logError('Failed to bind settings UI events:', error);
            // Continue without settings UI binding
        }
    }

    /**
     * Get current dark mode status
     *
     * @returns {boolean} True if dark mode is currently active
     */
    isDark() {
        return document.body.classList.contains(this.bodyClass);
    }

    /**
     * Get current mode setting
     *
     * @returns {string} Current mode ('auto' | 'enabled' | 'disabled')
     */
    getCurrentMode() {
        return this.mode;
    }

    /**
     * Cleanup - remove event listeners and intervals
     */
    destroy() {
        try {
            // Clear time check interval
            if (this.timeCheckInterval) {
                clearInterval(this.timeCheckInterval);
                this.timeCheckInterval = null;
            }
            
            // Remove system preference listener
            if (this.preferenceMediaQuery && this.preferenceHandler) {
                try {
                    if (this.preferenceMediaQuery.removeEventListener) {
                        this.preferenceMediaQuery.removeEventListener('change', this.preferenceHandler);
                    } else if (this.preferenceMediaQuery.removeListener) {
                        // Deprecated but still supported in older browsers
                        this.preferenceMediaQuery.removeListener(this.preferenceHandler);
                    }
                } catch (error) {
                    this.logError('Failed to remove preference listener:', error);
                }
                this.preferenceMediaQuery = null;
                this.preferenceHandler = null;
            }
            
            this.log('Destroyed');
        } catch (error) {
            this.logError('Error during cleanup:', error);
        }
    }
}

export default DarkMode;
