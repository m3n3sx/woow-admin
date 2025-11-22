# Floating Style - Test Guide

## Quick Test Checklist

### 1. Enable Floating Style
1. Go to **WOOW! Admin → Dashboard (General)**
2. Find **Floating Style** toggle (green card)
3. Enable it
4. Click **Save Changes**

### 2. Visual Verification

#### Admin Bar
- [ ] Admin Bar sticks to **top edge** (no margin)
- [ ] Admin Bar has **sharp corners** (no border-radius)
- [ ] Admin Bar spans **full width**

#### Admin Menu
- [ ] Admin Menu sticks to **left edge** (no margin)
- [ ] Admin Menu has **sharp corners** (no border-radius)
- [ ] Menu items have **sharp corners**

#### Dashboard
- [ ] Dashboard widgets have **sharp corners**
- [ ] Welcome panel has **sharp corners**
- [ ] Quick Draft widget has **sharp corners**

#### Posts/Pages List
- [ ] Table has **sharp corners**
- [ ] Filter dropdowns have **sharp corners**
- [ ] Bulk action buttons have **sharp corners**
- [ ] Search box has **sharp corners**

#### Post Editor
- [ ] Meta boxes have **sharp corners** (Categories, Tags, etc.)
- [ ] Publish button has **sharp corners**
- [ ] All input fields have **sharp corners**

#### Media Library
- [ ] Media grid items have **sharp corners**
- [ ] Upload button has **sharp corners**
- [ ] Media modal has **sharp corners**

#### Plugins/Themes
- [ ] Plugin cards have **sharp corners**
- [ ] Theme cards have **sharp corners**
- [ ] Action buttons have **sharp corners**

#### Notices
- [ ] Success notices have **sharp corners**
- [ ] Error notices have **sharp corners**
- [ ] Warning notices have **sharp corners**

### 3. Interaction Test

#### Forms
1. Go to **Settings → General**
2. Check all input fields:
   - [ ] Text inputs have sharp corners
   - [ ] Textareas have sharp corners
   - [ ] Select dropdowns have sharp corners
   - [ ] Save button has sharp corners

#### Buttons
1. Test various buttons:
   - [ ] Primary buttons (blue)
   - [ ] Secondary buttons (gray)
   - [ ] Danger buttons (red)
   - [ ] All have sharp corners

#### Tooltips
1. Hover over help icons (?)
2. Check tooltip appearance:
   - [ ] Tooltip has sharp corners
   - [ ] Pointer has sharp corners

### 4. Compatibility Test

#### With Rounded Style
1. **Floating ON + Rounded ON**
   - [ ] Floating Style wins (sharp corners everywhere)

2. **Floating ON + Rounded OFF**
   - [ ] Sharp corners everywhere (both agree)

3. **Floating OFF + Rounded ON**
   - [ ] Rounded corners everywhere (normal behavior)

4. **Floating OFF + Rounded OFF**
   - [ ] Sharp corners everywhere (Rounded Style OFF)

#### With Glass Style
1. **Floating ON + Glass ON**
   - [ ] Sharp corners + glassmorphism effect
   - [ ] Blur and transparency work
   - [ ] No rounded corners

2. **Floating OFF + Glass ON**
   - [ ] Rounded corners + glassmorphism effect
   - [ ] Normal behavior

### 5. Edge Cases

#### Responsive
1. Resize browser window
   - [ ] Admin Bar stays at top edge
   - [ ] Admin Menu stays at left edge
   - [ ] No gaps appear

#### Scrolling
1. Scroll down the page
   - [ ] Admin Bar stays fixed at top
   - [ ] No margin appears during scroll

#### Different Pages
Test on:
- [ ] Dashboard
- [ ] Posts list
- [ ] Post editor
- [ ] Media library
- [ ] Plugins page
- [ ] Settings pages
- [ ] User profile

### 6. Performance Check

1. Open browser DevTools
2. Check Console for errors
   - [ ] No JavaScript errors
   - [ ] No CSS warnings

3. Check Network tab
   - [ ] CSS file loads correctly
   - [ ] No 404 errors

### 7. Disable Test

1. Disable Floating Style
2. Save changes
3. Verify:
   - [ ] Margins return (if configured)
   - [ ] Border-radius returns (if Rounded Style ON)
   - [ ] Everything looks normal

## Expected Results

### ✅ Pass Criteria
- All elements have sharp corners (border-radius: 0)
- Admin Bar and Menu stick to edges (no margins)
- Colors and glassmorphism still work
- No visual glitches or gaps
- No console errors

### ❌ Fail Criteria
- Some elements still have rounded corners
- Margins appear on Admin Bar/Menu
- Visual glitches or overlapping elements
- Console errors appear
- Styles don't apply after save

## Troubleshooting

### Issue: Rounded corners still visible
**Solution:** Clear browser cache (Ctrl+Shift+R)

### Issue: Changes don't apply
**Solution:** 
1. Check if save was successful
2. Clear WordPress cache
3. Rebuild assets: `npm run build`

### Issue: Admin Bar has margin
**Solution:** Check if Floating Style is actually enabled in database

### Issue: Some elements not affected
**Solution:** Check browser DevTools to see if other CSS is overriding

## Quick Visual Test

**Before Floating Style:**
```
┌─────────────────────────────────────────┐
│ Browser Window                          │
│  ┌───────────────────────────────────┐  │ ← 16px margin
│  │ Admin Bar (rounded)               │  │
│  └───────────────────────────────────┘  │
│  ┌─────┐                                 │
│  │Menu │ ← 16px margin                   │
│  │ ●   │ (rounded items)                 │
│  └─────┘                                 │
│     [Button] ← rounded                   │
└─────────────────────────────────────────┘
```

**After Floating Style:**
```
┌─────────────────────────────────────────┐
│ Browser Window                          │
├─────────────────────────────────────────┤ ← No margin
│ Admin Bar (sharp corners)               │
├─────────────────────────────────────────┤
│Menu │                                    │ ← No margin
│ ■   │ (sharp items)                     │
│     │                                    │
│     [Button] ← sharp                    │
└─────────────────────────────────────────┘
```

---

**Test Duration:** ~10 minutes
**Recommended Browser:** Chrome/Firefox (latest)
**WordPress Version:** 6.0+
