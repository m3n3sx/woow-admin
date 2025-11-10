/**
 * Header Controller
 * 
 * Manages Figma-compliant header interactions:
 * - Theme toggle
 * - Undo/Redo functionality
 * - Real-time preview toggle
 * - Status updates
 * - Save/Reset actions
 * 
 * @package WoowAdmin
 * @since 1.0.0
 */

export class HeaderController {
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.changesCount = 0;
        this.undoStack = [];
        this.redoStack = [];
        this.maxStackSize = 20;
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateStatus();
    }

    bindEvents() {
        // Theme Toggle
        const themeToggle = document.getElementById('woow-theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }

        // Undo/Redo
        const undoBtn = document.getElementById('woow-undo-btn');
        const redoBtn = document.getElementById('woow-redo-btn');
        
        if (undoBtn) {
            undoBtn.addEventListener('click', () => this.undo());
        }
        
        if (redoBtn) {
            redoBtn.addEventListener('click', () => this.redo());
        }

        // Real-time Toggle
        const realtimeToggle = document.getElementById('woow-realtime-toggle');
        if (realtimeToggle) {
            realtimeToggle.addEventListener('change', (e) => {
                this.woow.state.realtimePreview = e.target.checked;
                this.updatePreviewStatus();
            });
        }

        // Reset Button
        const resetBtn = document.getElementById('woow-reset-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.confirmReset());
        }

        // Save Button
        const saveBtn = document.getElementById('woow-save-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.woow.saveSettings());
        }

        // Keyboard Shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + Z for Undo
            if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                this.undo();
            }
            
            // Ctrl/Cmd + Shift + Z or Ctrl/Cmd + Y for Redo
            if ((e.ctrlKey || e.metaKey) && (e.shiftKey && e.key === 'z' || e.key === 'y')) {
                e.preventDefault();
                this.redo();
            }
            
            // Ctrl/Cmd + S for Save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.woow.saveSettings();
            }
        });

        // Track form changes
        const form = document.getElementById('woow-settings-form');
        if (form) {
            form.addEventListener('change', () => {
                this.incrementChanges();
            });
        }
    }

    toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('woow-theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('woow-theme', 'dark');
        }
        
        // Update icon
        const icon = document.querySelector('#woow-theme-toggle .dashicons');
        if (icon) {
            icon.classList.toggle('dashicons-admin-appearance');
            icon.classList.toggle('dashicons-admin-site');
        }
    }

    incrementChanges() {
        this.changesCount++;
        this.updateChangesCount();
        this.updateSaveStatus('unsaved');
        
        // Save current state to undo stack
        this.saveToUndoStack();
    }

    updateChangesCount() {
        const countEl = document.getElementById('woow-changes-count');
        if (countEl) {
            countEl.textContent = this.changesCount;
        }
    }

    updateSaveStatus(status) {
        const statusEl = document.getElementById('woow-save-status');
        if (!statusEl) return;

        statusEl.classList.remove('unsaved', 'saving', 'saved');
        
        switch (status) {
            case 'unsaved':
                statusEl.classList.add('unsaved');
                statusEl.innerHTML = '<span class="dashicons dashicons-warning" style="width: 12px; height: 12px; font-size: 12px;"></span> Unsaved';
                statusEl.style.background = '#fef3c7';
                statusEl.style.color = '#d97706';
                statusEl.style.borderColor = '#fde68a';
                break;
                
            case 'saving':
                statusEl.classList.add('saving');
                statusEl.innerHTML = '<span class="dashicons dashicons-update" style="width: 12px; height: 12px; font-size: 12px;"></span> Saving...';
                statusEl.style.background = '#dbeafe';
                statusEl.style.color = '#2563eb';
                statusEl.style.borderColor = '#bfdbfe';
                break;
                
            case 'saved':
                statusEl.classList.add('saved');
                statusEl.innerHTML = '<span class="dashicons dashicons-yes-alt" style="width: 12px; height: 12px; font-size: 12px;"></span> Saved';
                statusEl.style.background = '#f0fdf4';
                statusEl.style.color = '#16a34a';
                statusEl.style.borderColor = '#bbf7d0';
                this.changesCount = 0;
                this.updateChangesCount();
                break;
        }
    }

    updatePreviewStatus() {
        const statusEl = document.getElementById('woow-preview-status');
        if (statusEl) {
            statusEl.textContent = this.woow.state.realtimePreview ? 'Active' : 'Paused';
        }
    }

    saveToUndoStack() {
        const currentState = this.woow.collectFormData();
        
        // Don't save if state hasn't changed
        if (this.undoStack.length > 0) {
            const lastState = this.undoStack[this.undoStack.length - 1];
            if (JSON.stringify(currentState) === JSON.stringify(lastState)) {
                return;
            }
        }
        
        this.undoStack.push(currentState);
        
        // Limit stack size
        if (this.undoStack.length > this.maxStackSize) {
            this.undoStack.shift();
        }
        
        // Clear redo stack when new change is made
        this.redoStack = [];
        
        this.updateUndoRedoButtons();
    }

    undo() {
        if (this.undoStack.length === 0) return;
        
        // Save current state to redo stack
        const currentState = this.woow.collectFormData();
        this.redoStack.push(currentState);
        
        // Get previous state
        const previousState = this.undoStack.pop();
        
        // Apply previous state
        this.applyState(previousState);
        
        this.updateUndoRedoButtons();
        this.woow.showNotification('Changes undone', 'info');
    }

    redo() {
        if (this.redoStack.length === 0) return;
        
        // Save current state to undo stack
        const currentState = this.woow.collectFormData();
        this.undoStack.push(currentState);
        
        // Get next state
        const nextState = this.redoStack.pop();
        
        // Apply next state
        this.applyState(nextState);
        
        this.updateUndoRedoButtons();
        this.woow.showNotification('Changes redone', 'info');
    }

    applyState(state) {
        // Apply state to form inputs
        Object.keys(state).forEach(section => {
            Object.keys(state[section]).forEach(key => {
                const input = document.querySelector(`[name="${section}[${key}]"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = state[section][key];
                    } else {
                        input.value = state[section][key];
                    }
                }
            });
        });
        
        // Trigger preview update
        if (this.woow.state.realtimePreview) {
            this.woow.debouncedPreview();
        }
    }

    updateUndoRedoButtons() {
        const undoBtn = document.getElementById('woow-undo-btn');
        const redoBtn = document.getElementById('woow-redo-btn');
        
        if (undoBtn) {
            undoBtn.disabled = this.undoStack.length === 0;
        }
        
        if (redoBtn) {
            redoBtn.disabled = this.redoStack.length === 0;
        }
    }

    async confirmReset() {
        const confirmed = confirm(
            'Are you sure you want to reset all settings to defaults? This action cannot be undone.'
        );
        
        if (!confirmed) return;
        
        try {
            const formData = new FormData();
            formData.append('action', 'woow_reset_all_settings');
            formData.append('nonce', this.woow.nonce);
            
            const response = await fetch(this.woow.ajaxUrl, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.woow.showNotification('Settings reset to defaults', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.woow.showNotification('Reset failed: ' + (data.data?.message || 'Unknown error'), 'error');
            }
        } catch (error) {
            console.error('[WOOW Admin] Reset error:', error);
            this.woow.showNotification('Network error during reset', 'error');
        }
    }

    updateStatus() {
        // Initialize theme from localStorage
        const savedTheme = localStorage.getItem('woow-theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
        
        // Update undo/redo buttons
        this.updateUndoRedoButtons();
        
        // Update preview status
        this.updatePreviewStatus();
        
        // Update save status
        this.updateSaveStatus('saved');
    }

    // Public method to be called after successful save
    onSaveSuccess() {
        this.updateSaveStatus('saved');
        this.undoStack = [];
        this.redoStack = [];
        this.updateUndoRedoButtons();
    }

    // Public method to be called when save starts
    onSaveStart() {
        this.updateSaveStatus('saving');
    }

    // Public method to be called when save fails
    onSaveError() {
        this.updateSaveStatus('unsaved');
    }
}
