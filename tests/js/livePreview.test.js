/**
 * LivePreview Component Tests
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

import { describe, it, expect, beforeEach, vi } from 'vitest';
import { LivePreview } from '../../assets/src/js/components/LivePreview.js';

describe('LivePreview Component', () => {
    let mockWoowAdmin;
    let livePreview;

    beforeEach(() => {
        // Setup DOM
        document.body.innerHTML = `
            <div id="woow-preview-adminbar" class="woow-preview-element"></div>
            <div id="woow-preview-menu" class="woow-preview-element">
                <div class="woow-preview-menu-item active"></div>
            </div>
            <div id="woow-preview-widget" class="woow-preview-element"></div>
            <button class="woow-preview-refresh"></button>
        `;

        // Mock WoowAdmin instance
        mockWoowAdmin = {
            i18n: {
                previewRefreshed: 'Preview refreshed'
            },
            showNotification: vi.fn(),
            updateLivePreview: vi.fn()
        };

        livePreview = new LivePreview(mockWoowAdmin);
    });

    it('should initialize with preview elements', () => {
        expect(livePreview.enabled).toBe(true);
        expect(livePreview.previewElements.adminBar).toBeTruthy();
        expect(livePreview.previewElements.adminMenu).toBeTruthy();
        expect(livePreview.previewElements.widget).toBeTruthy();
    });

    it('should gracefully handle missing preview elements', () => {
        document.body.innerHTML = '';
        const disabledPreview = new LivePreview(mockWoowAdmin);
        expect(disabledPreview.enabled).toBe(false);
    });

    it('should update admin bar preview', async () => {
        const settings = {
            admin_bar: {
                background_color: '#1e293b',
                text_color: '#ffffff',
                height: '48px',
                border_radius: '24px'
            }
        };

        await livePreview.updatePreview(settings);

        const adminBar = document.getElementById('woow-preview-adminbar');
        expect(adminBar.style.background).toBe('rgb(30, 41, 59)');
        expect(adminBar.style.color).toBe('rgb(255, 255, 255)');
        expect(adminBar.style.height).toBe('48px');
        expect(adminBar.style.borderRadius).toBe('24px');
    });

    it('should update admin menu preview', async () => {
        const settings = {
            admin_menu: {
                background_color: 'rgba(255,255,255,0.9)',
                text_color: '#0f172a',
                active_bg_start: '#6366f1',
                active_bg_end: '#8b5cf6'
            }
        };

        await livePreview.updatePreview(settings);

        const adminMenu = document.getElementById('woow-preview-menu');
        expect(adminMenu.style.background).toBe('rgba(255, 255, 255, 0.9)');
        expect(adminMenu.style.color).toBe('rgb(15, 23, 42)');

        const activeItem = adminMenu.querySelector('.woow-preview-menu-item.active');
        expect(activeItem.style.background).toContain('linear-gradient');
    });

    it('should update widget preview', async () => {
        const settings = {
            dashboard_widgets: {
                background_color: 'rgba(255,255,255,0.9)',
                border_radius: '24px'
            }
        };

        await livePreview.updatePreview(settings);

        const widget = document.getElementById('woow-preview-widget');
        expect(widget.style.background).toBe('rgba(255, 255, 255, 0.9)');
        expect(widget.style.borderRadius).toBe('24px');
    });

    it('should handle errors gracefully without throwing', async () => {
        const settings = {
            admin_bar: {
                background_color: '#1e293b'
            }
        };

        // Remove preview element to cause error
        document.getElementById('woow-preview-adminbar').remove();
        livePreview.previewElements.adminBar = null;

        // Should not throw
        await expect(livePreview.updatePreview(settings)).resolves.not.toThrow();
    });

    it('should skip update when disabled', async () => {
        livePreview.enabled = false;
        const settings = {
            admin_bar: {
                background_color: '#1e293b'
            }
        };

        await livePreview.updatePreview(settings);

        const adminBar = document.getElementById('woow-preview-adminbar');
        expect(adminBar.style.background).toBe('');
    });

    it('should check if preview is enabled', () => {
        expect(livePreview.isEnabled()).toBe(true);

        livePreview.enabled = false;
        expect(livePreview.isEnabled()).toBe(false);
    });
});
