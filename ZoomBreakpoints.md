# WordPress Admin Bar Zoom Breakpoints - UI Behavior Specification

## Overview

This document defines the UI behavior rules for WordPress admin bar elements across different zoom levels and device types. These rules ensure consistent user experience and maintain WordPress core compatibility while providing accessible navigation at all zoom levels.

## Core UI Behavior Rules

Device Configuration Matrix
Mobile Portrait
iPhone 8: 375px × 667px
iPhone 11: 414px × 896px
Android Standard: 360px × 640px
Mobile Landscape
iPhone 8: 667px × 375px
iPhone 11: 896px × 414px
Android Standard: 640px × 360px
Tablet Portrait
iPad: 768px × 1024px
Android Tablet: 800px × 1280px
Tablet Landscape
iPad: 1024px × 768px
Android Tablet: 1280px × 800px
Desktop Standard
Full HD: 1920px × 1080px
Common Laptop: 1366px × 768px
MacBook: 1440px × 900px

### Zoom Level Breakpoints

#### Standard Zoom Levels (100% - 175%)
- **Icon Display**: Always visible and fully rendered
- **Label Display**: Always visible with full text content
- **Rationale**: At standard zoom levels, screen real estate allows for complete information display without compromising usability

#### High Zoom Levels (200% - 250%)
- **Icon Display**: Always visible and properly scaled
- **Label Display**: Hidden to preserve essential functionality
- **Rationale**: High zoom levels indicate accessibility needs; icons provide essential navigation while labels are sacrificed to prevent layout overflow

#### Extreme Zoom Levels (300%+)
- **Icon Display**: Remains visible but may be simplified
- **Label Display**: Hidden completely
- **Rationale**: Extreme zoom prioritizes core functionality over descriptive text

### Device-Specific Behavior Patterns

#### Desktop Devices (≥1200px width)
- **Full Range Support**: Implements all zoom level behaviors
- **Progressive Degradation**: Smoothly transitions from full display to icon-only as zoom increases
- **WordPress Core Alignment**: Matches native WordPress admin bar behavior patterns

#### Tablet Devices (768px - 1199px width)
- **Adaptive Behavior**: Responds to both device constraints and zoom levels
- **Touch Optimization**: Maintains adequate touch targets even when labels are hidden
- **Flexible Thresholds**: May hide labels at lower zoom levels than desktop due to space constraints

#### Mobile Devices (<768px width)
- **Icon Priority**: Icons always take precedence over labels
- **Space Conservation**: Labels typically hidden by default regardless of zoom level
- **Essential Functionality**: Maintains core navigation capabilities in minimal space

## Element Visibility Rules

### Sidebar Toggle Button
- **Icon Component**: Never hidden across any zoom level or device type
- **Label Component**: Hidden when zoom ≥200% or on mobile devices
- **Accessibility**: Icon includes appropriate ARIA labels when text labels are hidden

### WordPress Core Compatibility
- **Behavior Matching**: Custom elements follow the same visibility patterns as core WordPress admin bar elements
- **CSS Integration**: Uses WordPress core CSS classes and responsive breakpoints
- **Theme Consistency**: Maintains visual consistency with active WordPress theme

## Responsive Design Principles

### Progressive Enhancement
- **Base Functionality**: Core navigation always available through icons
- **Enhanced Experience**: Labels provide additional context when space permits
- **Graceful Degradation**: Smooth transition between display states

### Accessibility Considerations
- **High Contrast**: Icons remain visible and distinguishable at all zoom levels
- **Screen Reader Support**: Hidden labels replaced with appropriate ARIA attributes
- **Keyboard Navigation**: All functionality remains accessible via keyboard regardless of zoom level

### Performance Optimization
- **CSS-Driven**: Visibility changes handled through CSS media queries and zoom detection
- **No JavaScript Dependencies**: Core visibility behavior works without JavaScript
- **Minimal Reflow**: Changes minimize layout recalculation impact

## Implementation Guidelines

### CSS Breakpoint Strategy
- **Zoom Detection**: Uses CSS zoom media queries where supported
- **Fallback Behavior**: Implements viewport-based breakpoints for broader compatibility
- **Mobile-First**: Starts with mobile constraints and progressively enhances

### WordPress Integration
- **Hook Compatibility**: Works with standard WordPress admin bar hooks
- **Theme Independence**: Functions correctly across different WordPress themes
- **Plugin Compatibility**: Does not interfere with other admin bar modifications

### Browser Support
- **Modern Browsers**: Full zoom detection and responsive behavior
- **Legacy Support**: Graceful fallback to viewport-based responsive design
- **Cross-Platform**: Consistent behavior across operating systems

## Design Rationale

### User Experience Priority
- **Accessibility First**: High zoom users get essential functionality without layout breaks
- **Cognitive Load**: Reduces visual complexity at high zoom levels
- **Familiar Patterns**: Follows established WordPress interface conventions

### Technical Considerations
- **Performance**: Minimal impact on page load and rendering
- **Maintainability**: Simple, predictable behavior rules
- **Extensibility**: Framework allows for future enhancements without breaking changes

This specification ensures that WordPress admin bar elements provide optimal user experience across all zoom levels and device types while maintaining consistency with WordPress core design principles.