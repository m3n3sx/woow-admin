# WOOW! Admin - Figma UI Rebuild Plan

## Overview

This document outlines the comprehensive plan to rebuild the WOOW! Admin UI to match the Figma design specifications exactly. The current implementation deviates from the professional Figma design, and this plan ensures pixel-perfect compliance.

## What Was Done

### 1. Created Figma UI Specification Document
**Location**: `.kiro/steering/figma-ui-spec.md`

This comprehensive steering rule document defines:
- **Exact layout structure** with two-row header (64px + 56px) and 256px sidebar
- **Component specifications** for all UI elements (cards, buttons, inputs, etc.)
- **Color system** with CSS variables for light and dark modes
- **Typography scale** matching Figma exactly (32px H1, 24px H2, etc.)
- **Spacing system** based on 4px increments
- **Border radius system** (24px for cards, 12px for controls)
- **Palette and template grid layouts** with exact specifications
- **Dashboard tab structure** with welcome card and stats grid
- **Toast notification design** with glassmorphism
- **All measurements, colors, and styling rules** from Figma

### 2. Updated Requirements Document
**Location**: `.kiro/specs/woow-admin/requirements.md`

Added **Requirement 26: Figma Design Compliance** with 10 acceptance criteria:
1. Two-row header implementation (64px + 56px)
2. Sidebar with 256px width and glassmorphism
3. 24px border-radius for cards
4. 12px border-radius for controls
5. Gradient background (slate-50 → indigo-50/30 → purple-50/30)
6. Active navigation with indigo-to-purple gradient
7. Status indicators with 8px dots and 11px labels
8. Responsive palette grid (2/3/4 columns)
9. Template gallery with aspect-video previews
10. Exact color values from Figma CSS variables

### 3. Updated Design Document
**Location**: `.kiro/specs/woow-admin/design.md`

Updated the overview section to:
- Reference the Figma UI Specification as the single source of truth
- Mandate that all UI implementations MUST match Figma exactly
- List key design requirements (two-row header, sidebar, border-radius, gradients, etc.)
- Direct developers to consult `.kiro/steering/figma-ui-spec.md` for exact specifications

### 4. Created Comprehensive UI Rebuild Tasks
**Location**: `.kiro/specs/woow-admin/tasks.md`

Added **Task 12: Figma UI Compliance Rebuild** with 18 subtasks:

#### 12.1 - Rebuild Header Component
- Two-row structure (title bar + control bar)
- Exact spacing, typography, and component placement
- Status indicators, badges, and action buttons

#### 12.2 - Rebuild Sidebar
- 256px width with glassmorphism
- Logo section with gradient icon
- Navigation with active state gradients

#### 12.3 - Rebuild Main Content Layout
- Gradient background
- Max-width container with proper spacing

#### 12.4 - Rebuild Card Components
- 24px border-radius
- Glassmorphism with backdrop-blur
- Proper header structure with icons

#### 12.5 - Rebuild Form Controls
- All inputs with exact styling
- Color pickers with proper layout
- Sliders with value displays
- Toggle switches in containers

#### 12.6 - Rebuild Palette Grid
- Responsive grid (2/3/4 columns)
- Card styling with hover effects
- Active indicators with gradients

#### 12.7 - Rebuild Template Gallery
- Responsive grid (1/2/3 columns)
- Aspect-video previews
- Active indicators and hover overlays

#### 12.8 - Rebuild Dashboard Tab
- Welcome card with gradient background
- Stats grid with icon containers
- Quick actions and recent activity

#### 12.9 - Rebuild Toast Notifications
- Glassmorphism with colored borders
- Icon containers with backgrounds
- Proper positioning and animations

#### 12.10-12.14 - Update Design Systems
- CSS variables for colors
- Typography scale
- Spacing system
- Border radius system
- Shadow system

#### 12.15-12.16 - Responsive & Animations
- Mobile/tablet/desktop breakpoints
- Hover and active state animations
- Reduced motion support

#### 12.17 - Accessibility
- Focus indicators
- Color contrast verification
- ARIA labels
- Keyboard navigation

#### 12.18 - Final Verification
- Pixel-by-pixel comparison with Figma
- Documentation of any deviations

## Figma Design Reference

The Figma design is available in the `figma-design` directory, which contains:
- React/TypeScript implementation showing exact component structure
- Design tokens with all colors, spacing, typography
- Component examples (Header, Sidebar, Cards, Forms, etc.)
- Exact CSS specifications

**Figma Project URL**: https://www.figma.com/design/vAU0wAK9LirPwlgMxZmSE4/Design-WordPress-Admin-Plugin

## Key Design Principles from Figma

### 1. Glassmorphism
- **Standard glass**: `backdrop-filter: blur(8px)`, `background: rgba(255,255,255,0.8)`
- **Strong glass**: `backdrop-filter: blur(12px)`, `background: rgba(255,255,255,0.9)`
- All glass elements have 1px white borders

### 2. Border Radius
- **Cards/Panels**: 24px (rounded-3xl)
- **Buttons/Inputs**: 12px (rounded-xl)
- **Icons/Avatars**: 16-20px (rounded-2xl)

### 3. Gradients
- **Background**: slate-50 → indigo-50/30 → purple-50/30
- **Active states**: indigo-500 → purple-500
- **Buttons**: indigo-500 → indigo-600 on hover

### 4. Typography
- **H1**: 32px, 700 weight, -0.02em tracking
- **H2**: 24px, 700 weight, -0.01em tracking
- **H3**: 20px, 600 weight, -0.01em tracking
- **Body**: 15px, 400 weight, 1.6 line-height
- **Labels/Buttons**: 14px, 600 weight

### 5. Spacing
- Base unit: 4px
- Common values: 8px, 12px, 16px, 24px, 32px, 48px, 64px

### 6. Colors (Light Mode)
- **Primary**: #6366f1 (Indigo)
- **Background**: #fafafa
- **Card**: #ffffff
- **Foreground**: #0f172a
- **Border**: #e2e8f0
- **Muted**: #64748b

### 7. Colors (Dark Mode)
- **Primary**: #818cf8 (Light Indigo)
- **Background**: #0f172a
- **Card**: #1e293b
- **Foreground**: #f1f5f9
- **Border**: #334155
- **Muted**: #94a3b8

## Implementation Strategy

### Phase 1: Foundation (Tasks 12.10-12.14)
Update all design system variables (colors, typography, spacing, border-radius, shadows) to match Figma exactly.

### Phase 2: Core Layout (Tasks 12.1-12.3)
Rebuild the main layout structure: header (2 rows), sidebar, and main content area.

### Phase 3: Components (Tasks 12.4-12.5)
Rebuild all reusable components: cards, form controls, buttons, inputs.

### Phase 4: Content Sections (Tasks 12.6-12.8)
Rebuild specific content sections: palette grid, template gallery, dashboard tab.

### Phase 5: Interactions (Tasks 12.9, 12.16)
Implement toast notifications and all animations/transitions.

### Phase 6: Responsive & Accessibility (Tasks 12.15, 12.17)
Ensure responsive behavior and accessibility compliance.

### Phase 7: Verification (Task 12.18)
Pixel-perfect comparison with Figma design and final quality checks.

## Success Criteria

The UI rebuild will be considered complete when:

1. ✅ All components match Figma design pixel-perfectly
2. ✅ All colors use exact CSS variable values from Figma
3. ✅ All spacing uses the defined spacing system
4. ✅ All border-radius values match specifications
5. ✅ All typography matches the scale exactly
6. ✅ Glassmorphism effects are applied consistently
7. ✅ Gradients match Figma specifications
8. ✅ Responsive behavior works on all breakpoints
9. ✅ Animations are smooth and respect reduced motion
10. ✅ Accessibility standards (WCAG AA) are met

## Next Steps

1. **Review the Figma UI Specification** (`.kiro/steering/figma-ui-spec.md`)
2. **Start with Task 12.1** (Rebuild header component)
3. **Work through tasks sequentially** for best results
4. **Test each component** against Figma design after implementation
5. **Document any intentional deviations** with clear justification

## Resources

- **Figma Design**: `figma-design/` directory
- **UI Specification**: `.kiro/steering/figma-ui-spec.md`
- **Requirements**: `.kiro/specs/woow-admin/requirements.md` (Requirement 26)
- **Design Doc**: `.kiro/specs/woow-admin/design.md`
- **Tasks**: `.kiro/specs/woow-admin/tasks.md` (Task 12)

---

**This rebuild ensures WOOW! Admin has a professional, polished UI that matches the Figma design exactly, providing users with a consistent and delightful experience.**
