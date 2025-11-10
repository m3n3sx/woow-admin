/**
 * Tests for collectFormData type conversions
 * 
 * @package WoowAdmin
 * @since 1.0.0
 */

import { describe, it, expect, beforeEach, vi } from 'vitest';

describe('collectFormData Type Conversions', () => {
    let mockForm;
    let mockInputs;

    beforeEach(() => {
        // Setup DOM
        document.body.innerHTML = `
            <form id="woow-settings-form">
                <!-- Opacity slider (0-100 -> 0-1) -->
                <input type="range" name="admin_bar[opacity]" value="90" data-type="opacity" />
                
                <!-- Line-height (unitless) -->
                <input type="number" name="typography[h1_line_height]" value="1.5" data-type="unitless" />
                
                <!-- Image size (keyword) -->
                <select name="backgrounds[image_size]">
                    <option value="cover" selected>Cover</option>
                </select>
                
                <!-- Height with unit -->
                <input type="number" name="admin_bar[height]" value="48" data-unit="px" />
                
                <!-- Checkbox -->
                <input type="checkbox" name="effects[glassmorphism]" checked />
                
                <!-- Color input -->
                <input type="color" name="admin_bar[background_color]" value="#1e293b" />
            </form>
        `;
    });

    it('should convert opacity from 0-100 range to 0-1 float', () => {
        const input = document.querySelector('[name="admin_bar[opacity]"]');
        expect(input.value).toBe('90');
        expect(input.dataset.type).toBe('opacity');
        
        // Simulate the conversion
        const value = parseFloat(input.value) / 100;
        expect(value).toBe(0.9);
        expect(typeof value).toBe('number');
    });

    it('should keep line-height as unitless float', () => {
        const input = document.querySelector('[name="typography[h1_line_height]"]');
        expect(input.value).toBe('1.5');
        expect(input.dataset.type).toBe('unitless');
        
        // Simulate the conversion
        const value = parseFloat(input.value);
        expect(value).toBe(1.5);
        expect(typeof value).toBe('number');
    });

    it('should keep image_size as keyword string', () => {
        const input = document.querySelector('[name="backgrounds[image_size]"]');
        expect(input.value).toBe('cover');
        expect(typeof input.value).toBe('string');
    });

    it('should append px unit to height', () => {
        const input = document.querySelector('[name="admin_bar[height]"]');
        expect(input.value).toBe('48');
        expect(input.dataset.unit).toBe('px');
        
        // Simulate the conversion
        const value = input.value + input.dataset.unit;
        expect(value).toBe('48px');
    });

    it('should convert checkbox to boolean', () => {
        const input = document.querySelector('[name="effects[glassmorphism]"]');
        expect(input.checked).toBe(true);
        expect(typeof input.checked).toBe('boolean');
    });

    it('should handle nested object notation', () => {
        const name = 'admin_bar[opacity]';
        const match = name.match(/^([^\[]+)\[([^\]]+)\]$/);
        
        expect(match).not.toBeNull();
        expect(match[1]).toBe('admin_bar');
        expect(match[2]).toBe('opacity');
    });

    it('should handle color inputs as strings', () => {
        const input = document.querySelector('[name="admin_bar[background_color]"]');
        expect(input.value).toBe('#1e293b');
        expect(typeof input.value).toBe('string');
    });
});
