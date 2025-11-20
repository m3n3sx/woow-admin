/**
 * GoogleFontsLoader Component Tests
 *
 * @package WoowAdmin
 * @since 2.0.0
 */

import { describe, it, expect, beforeEach, vi } from 'vitest';
import { GoogleFontsLoader } from './GoogleFontsLoader.js';

describe('GoogleFontsLoader', () => {
    let mockWoowAdmin;
    let loader;

    beforeEach(() => {
        // Reset DOM
        document.body.innerHTML = '';
        document.head.innerHTML = '';

        // Mock WoowAdmin instance
        mockWoowAdmin = {
            debouncedPreview: vi.fn()
        };

        // Create minimal DOM structure
        document.body.innerHTML = `
            <select class="woow-font-selector" data-font-type="body">
                <option value="system">System Default</option>
                <option value="Inter">Inter</option>
                <option value="Roboto">Roboto</option>
            </select>
            
            <input type="checkbox" name="typography[body_weights][]" value="400" checked />
            <input type="checkbox" name="typography[body_weights][]" value="600" checked />
            <input type="checkbox" name="typography[body_weights][]" value="700" />
            
            <button class="woow-font-preview-btn" data-font-type="body">Preview</button>
            
            <div class="woow-font-preview-panel" data-font-type="body" style="display: none;">
                <div class="woow-font-preview-samples"></div>
                <button class="woow-font-preview-close" data-font-type="body">Close</button>
            </div>
        `;

        loader = new GoogleFontsLoader(mockWoowAdmin);
    });

    describe('buildFontUrl', () => {
        it('should build correct URL for single weight', () => {
            const url = loader.buildFontUrl('Inter', [400]);
            expect(url).toBe('https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap');
        });

        it('should build correct URL for multiple weights', () => {
            const url = loader.buildFontUrl('Inter', [400, 600, 700]);
            expect(url).toBe('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        });

        it('should handle font names with spaces', () => {
            const url = loader.buildFontUrl('Open Sans', [400, 700]);
            expect(url).toBe('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap');
        });

        it('should sort weights in ascending order', () => {
            const url = loader.buildFontUrl('Inter', [700, 400, 600]);
            expect(url).toBe('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        });

        it('should default to weight 400 if no weights provided', () => {
            const url = loader.buildFontUrl('Inter', []);
            expect(url).toBe('https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap');
        });

        it('should return empty string for system font', () => {
            const url = loader.buildFontUrl('system', [400]);
            expect(url).toBe('');
        });

        it('should return empty string for empty font name', () => {
            const url = loader.buildFontUrl('', [400]);
            expect(url).toBe('');
        });
    });

    describe('getSelectedWeights', () => {
        it('should extract selected weights from checkboxes', () => {
            const weights = loader.getSelectedWeights('body');
            expect(weights).toEqual([400, 600]);
        });

        it('should return [400] if no weights selected', () => {
            // Uncheck all checkboxes
            document.querySelectorAll('input[name="typography[body_weights][]"]').forEach(cb => {
                cb.checked = false;
            });

            const weights = loader.getSelectedWeights('body');
            expect(weights).toEqual([400]);
        });
    });

    describe('applyFont', () => {
        it('should apply Google Font to preview panel', () => {
            loader.applyFont('Inter', 'body');

            const samplesContainer = document.querySelector('.woow-font-preview-samples');
            expect(samplesContainer.style.fontFamily).toContain('Inter');
        });

        it('should apply system fonts when font is "system"', () => {
            loader.applyFont('system', 'body');

            const samplesContainer = document.querySelector('.woow-font-preview-samples');
            expect(samplesContainer.style.fontFamily).toContain('-apple-system');
        });

        it('should quote font names with spaces', () => {
            loader.applyFont('Open Sans', 'body');

            const samplesContainer = document.querySelector('.woow-font-preview-samples');
            expect(samplesContainer.style.fontFamily).toContain('"Open Sans"');
        });
    });

    describe('showPreview', () => {
        it('should display preview panel', async () => {
            const selector = document.querySelector('.woow-font-selector[data-font-type="body"]');
            selector.value = 'Inter';

            await loader.showPreview('body');

            const previewPanel = document.querySelector('.woow-font-preview-panel[data-font-type="body"]');
            expect(previewPanel.style.display).toBe('block');
            expect(loader.previewActive.body).toBe(true);
        });
    });

    describe('hidePreview', () => {
        it('should hide preview panel', () => {
            // First show it
            const previewPanel = document.querySelector('.woow-font-preview-panel[data-font-type="body"]');
            previewPanel.style.display = 'block';
            loader.previewActive.body = true;

            // Then hide it
            loader.hidePreview('body');

            expect(previewPanel.style.display).toBe('none');
            expect(loader.previewActive.body).toBe(false);
        });
    });

    describe('font caching', () => {
        it('should cache loaded fonts', async () => {
            const selector = document.querySelector('.woow-font-selector[data-font-type="body"]');
            selector.value = 'Inter';

            await loader.loadFont('Inter', 'body');

            const stats = loader.getCacheStats();
            expect(stats.size).toBe(1);
            expect(stats.fonts).toContain('Inter-body');
        });

        it('should not reload cached fonts', async () => {
            const selector = document.querySelector('.woow-font-selector[data-font-type="body"]');
            selector.value = 'Inter';

            // Load font first time
            await loader.loadFont('Inter', 'body');
            const initialLinkCount = document.querySelectorAll('link[rel="stylesheet"]').length;

            // Load same font again
            await loader.loadFont('Inter', 'body');
            const finalLinkCount = document.querySelectorAll('link[rel="stylesheet"]').length;

            // Should not create additional link elements
            expect(finalLinkCount).toBe(initialLinkCount);
        });

        it('should clear cache', () => {
            loader.loadedFonts.set('test-font', { fontName: 'Test' });
            expect(loader.getCacheStats().size).toBe(1);

            loader.clearCache();
            expect(loader.getCacheStats().size).toBe(0);
        });
    });

    describe('error handling', () => {
        it('should not throw when font selector is missing', async () => {
            document.body.innerHTML = '';

            await expect(loader.showPreview('body')).resolves.not.toThrow();
        });

        it('should not throw when preview panel is missing', () => {
            document.querySelector('.woow-font-preview-panel').remove();

            expect(() => loader.applyFont('Inter', 'body')).not.toThrow();
        });

        it('should log errors without disrupting UI', () => {
            const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {});

            loader.logError('TestFont', new Error('Test error'));

            expect(consoleSpy).toHaveBeenCalled();
            consoleSpy.mockRestore();
        });
    });
});
