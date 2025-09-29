# Chat Avatar System with Online Status Indicators

## Overview

This implementation adds a comprehensive chat avatar system with online/offline indicators, profile image support, and accessibility features to the church management Laravel application.

## Features

### ✅ Online/Offline Indicators
- **Green dot**: User is online (active within last 5 minutes)
- **Gray dot**: User is offline (inactive for more than 5 minutes)
- Real-time status updates based on user activity

### ✅ Avatar System
- **Profile images**: Display user profile photos when available
- **Initials fallback**: Colored circular backgrounds with user initials when no profile image exists
- **Multiple sizes**: xs, sm, md, lg, xl for different contexts
- **Member badges**: Special indicators for members without user accounts

### ✅ Visual Design
- **Selected state**: Soft gold/amber highlighting for active chat
- **Glassmorphism effects**: Modern backdrop blur and transparency
- **Tailwind CSS**: Responsive and accessible styling
- **Color schemes**: Sky blue to lavender gradients for consistency

### ✅ Accessibility Features
- **ARIA labels**: Screen reader support for status indicators
- **High contrast support**: Enhanced visibility in high contrast mode
- **Dark mode support**: Automatic adaptation to system preferences
- **Reduced motion**: Respects user motion preferences
- **Semantic HTML**: Proper roles and labels

## Implementation Details

### Backend Changes

#### MessageController.php
- Added real-time online status checking based on `last_activity` field
- Implemented activity tracking endpoint (`/messages/activity`)
- Enhanced user data with online status information

#### Routes
```php
Route::post('/messages/activity', [MessageController::class, 'updateActivity'])->name('messages.activity');
Route::get('/avatar-demo', function() { return view('components.avatar-demo'); })->name('avatar.demo');
```

### Frontend Components

#### Chat Avatar Component (`x-chat-avatar`)
```blade
<x-chat-avatar 
    :user="$userData" 
    size="md" 
    :show-online-status="true"
    :selected="false"
/>
```

**Props:**
- `user`: Array containing user information (name, member, online, type)
- `size`: Avatar size (xs, sm, md, lg, xl)
- `show-online-status`: Boolean to show/hide online indicator
- `selected`: Boolean for active chat highlighting

#### Activity Tracking
- Automatic activity updates every 30 seconds
- User interaction tracking (mouse, keyboard, clicks)
- Real-time online status updates

## Usage Examples

### Basic Avatar
```blade
<x-chat-avatar 
    :user="['name' => 'John Doe', 'member' => null, 'online' => true, 'type' => 'user']" 
    size="md" 
/>
```

### Selected State (Active Chat)
```blade
<x-chat-avatar 
    :user="$user" 
    size="lg" 
    :selected="true"
/>
```

### Without Online Status
```blade
<x-chat-avatar 
    :user="$user" 
    size="sm" 
    :show-online-status="false"
/>
```

## Styling Classes

### Size Variants
- `xs`: 24x24px (w-6 h-6)
- `sm`: 32x32px (w-8 h-8) 
- `md`: 48x48px (w-12 h-12)
- `lg`: 56x56px (w-14 h-14)
- `xl`: 64x64px (w-16 h-16)

### Status Colors
- **Online**: `bg-green-400` with green glow
- **Offline**: `bg-slate-400` (neutral gray)
- **Selected**: Amber/yellow gradient with ring

### Member Badges
- **Member type**: Yellow/amber badge with "M" indicator
- **Position**: Top-right corner of avatar

## Accessibility

### Screen Readers
- Status indicators have `role="status"` and descriptive `aria-label`
- Avatar containers have `role="img"` and descriptive labels

### Color Contrast
- High contrast mode support
- Dark text on light backgrounds
- Accessible color combinations

### Motion
- Respects `prefers-reduced-motion` setting
- Smooth transitions when motion is enabled
- Static display when motion is reduced

## Browser Support

- **Modern browsers**: Full feature support
- **Legacy browsers**: Graceful degradation
- **Mobile devices**: Responsive design
- **Screen readers**: Full accessibility support

## Testing

Visit `/avatar-demo` to see all avatar variants and states in action.

## Future Enhancements

- [ ] Real-time status updates via WebSockets
- [ ] Custom avatar upload interface
- [ ] Avatar cropping and editing tools
- [ ] Group chat avatars
- [ ] Status message support ("Away", "Busy", etc.)

## Dependencies

- Laravel 10+
- Tailwind CSS
- Alpine.js
- Font Awesome (for icons)

## Installation

The system is already integrated into the existing chat interface. No additional installation steps required.

## Troubleshooting

### Online Status Not Updating
- Check if the `last_activity` field exists in the users table
- Verify the activity tracking endpoint is working
- Check browser console for JavaScript errors

### Avatar Not Displaying
- Ensure profile images are stored in the correct storage path
- Check if the member relationship exists
- Verify the component is properly included

### Styling Issues
- Ensure Tailwind CSS is properly compiled
- Check for CSS conflicts in the main stylesheet
- Verify responsive breakpoints are working

