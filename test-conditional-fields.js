// Test conditional fields in browser console
// Copy and paste this into browser console on WOOW Admin page

console.log('=== Testing Conditional Fields ===');

// 1. Find background_type select
const bgTypeSelect = document.querySelector('[name="admin_menu[background_type]"]');
console.log('Background Type Select:', bgTypeSelect);
console.log('Current value:', bgTypeSelect?.value);

// 2. Find all conditional fields
const conditionalFields = document.querySelectorAll('.woow-conditional, .woow-conditional-field');
console.log('Total conditional fields:', conditionalFields.length);

conditionalFields.forEach((field, index) => {
    console.log(`Field ${index}:`, {
        showWhen: field.dataset.showWhen,
        condition: field.dataset.condition,
        value: field.dataset.value,
        display: field.style.display,
        visible: field.offsetParent !== null
    });
});

// 3. Find background_color input
const bgColorInput = document.querySelector('[name="admin_menu[background_color]"]');
console.log('Background Color Input:', bgColorInput);
console.log('Parent conditional:', bgColorInput?.closest('.woow-conditional, .woow-conditional-field'));
console.log('Is visible:', bgColorInput?.offsetParent !== null);

// 4. Test changing background_type
console.log('\n=== Testing Change Event ===');
if (bgTypeSelect) {
    bgTypeSelect.addEventListener('change', (e) => {
        console.log('Background type changed to:', e.target.value);
        
        setTimeout(() => {
            conditionalFields.forEach((field, index) => {
                console.log(`Field ${index} after change:`, {
                    showWhen: field.dataset.showWhen,
                    display: field.style.display,
                    visible: field.offsetParent !== null
                });
            });
        }, 100);
    });
    
    console.log('Change listener attached. Try changing background type now.');
}

// 5. Test form data collection
console.log('\n=== Testing Form Data Collection ===');
const form = document.querySelector('#woow-settings-form');
if (form) {
    const inputs = form.querySelectorAll('[name^="admin_menu["]');
    console.log('Admin menu inputs:', inputs.length);
    
    inputs.forEach(input => {
        const conditionalParent = input.closest('.woow-conditional, .woow-conditional-field');
        if (conditionalParent) {
            const isHidden = conditionalParent.style.display === 'none';
            console.log(input.name, {
                value: input.value,
                hasConditionalParent: true,
                isHidden: isHidden,
                willBeCollected: !isHidden
            });
        }
    });
}
