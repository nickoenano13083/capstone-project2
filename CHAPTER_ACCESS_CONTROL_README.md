# Chapter-Based Access Control Implementation

## Overview
This implementation ensures that chapter leaders can only manage members, events, and attendance for their own chapters. Users who select the same chapter as a leader will be visible to that leader in the management interfaces.

## Key Features Implemented

### 1. Enhanced Controllers
- **MemberController**: Filters members by leader's chapters + users with same preferred chapter
- **EventController**: Filters events by leader's chapters  
- **AttendanceController**: Filters attendance by leader's chapters
- **AdminUserController**: Shows users who select the same chapter as leader

### 2. New Middleware
- **ChapterLeaderMiddleware**: Ensures leaders have assigned chapters before accessing protected routes
- **AdminLeaderMiddleware**: Existing middleware for admin/leader access control

### 3. Route Protection
- Applied `chapter.leader` middleware to attendance, events, and QR management routes
- Applied `admin.leader` middleware to members and chapters routes

## How It Works

### For Chapter Leaders
1. **Members Management**: Can see all members in their chapters + users who selected their chapter as preferred
2. **Events Management**: Can only create/edit/delete events for their chapters
3. **Attendance Management**: Can only manage attendance for their chapter members
4. **User Management**: Can see users who selected their chapter as preferred

### For Regular Users
1. **Chapter Selection**: Users can select a preferred chapter during registration/profile update
2. **Visibility**: Users become visible to chapter leaders when they select that chapter
3. **Access**: Users can only see data from their selected chapter

## Database Relationships

```sql
-- Users table has preferred_chapter_id
users.preferred_chapter_id -> chapters.id

-- Members table has chapter_id  
members.chapter_id -> chapters.id

-- Events table has chapter_id
events.chapter_id -> chapters.id

-- Chapters table has leader_id and leader_type
chapters.leader_id -> users.id OR members.id
chapters.leader_type -> 'App\Models\User' OR 'App\Models\Member'
```

## Access Control Rules

### Leaders Can:
- ✅ View/manage members in their chapters
- ✅ View users who selected their chapter as preferred
- ✅ Create/edit/delete events for their chapters
- ✅ Manage attendance for their chapter members
- ✅ Access QR management for their chapter events

### Leaders Cannot:
- ❌ Access data from other chapters
- ❌ Manage users from other chapters
- ❌ Create events for other chapters
- ❌ View attendance from other chapters

### Admins Can:
- ✅ Access all data across all chapters
- ✅ Assign users to any chapter
- ✅ Promote users to leader roles
- ✅ Manage all chapters

## Example Scenario

**Chapter**: "JIL Sorsogon City"
**Leader**: John Doe (User ID: 123)

**What the leader sees:**
1. All members assigned to "JIL Sorsogon City" chapter
2. All users who selected "JIL Sorsogon City" as their preferred chapter
3. All events created for "JIL Sorsogon City" chapter
4. All attendance records for "JIL Sorsogon City" chapter members

**What the leader cannot see:**
1. Members from other chapters
2. Users who selected other chapters
3. Events from other chapters
4. Attendance from other chapters

## Implementation Details

### Controller Filtering
All controllers now use the `getLeaderChapterIds()` method or similar logic to filter data:

```php
// Example from MemberController
if (auth()->check() && auth()->user()->role === 'Leader') {
    $leaderChapterIds = $this->getLeaderChapterIds();
    $query->where(function($q) use ($leaderChapterIds) {
        $q->whereIn('chapter_id', $leaderChapterIds)
          ->orWhereHas('user', function($uq) use ($leaderChapterIds) {
              $uq->whereIn('preferred_chapter_id', $leaderChapterIds);
          });
    });
}
```

### Middleware Chain
1. **auth**: Ensures user is logged in
2. **admin.leader**: Ensures user is admin or leader
3. **chapter.leader**: Ensures leader has assigned chapters (for chapter-specific routes)

### Route Protection
```php
// Protected routes
Route::resource('members', MemberController::class)->middleware('admin.leader');
Route::resource('events', EventController::class)->middleware('chapter.leader');
Route::resource('attendance', AttendanceController::class)->middleware('chapter.leader');
```

## Testing

Run the test script to verify functionality:
```bash
php test_chapter_access.php
```

## Security Considerations

1. **Data Isolation**: Leaders cannot access data from other chapters
2. **Role Validation**: Proper role checking at controller and middleware levels
3. **Chapter Validation**: All operations validate against leader's assigned chapters
4. **Input Sanitization**: Proper validation and sanitization of all inputs

## Future Enhancements

1. **Audit Logging**: Track all chapter-based access for security monitoring
2. **Permission Granularity**: More fine-grained permissions within chapters
3. **Cross-Chapter Collaboration**: Allow leaders to collaborate on shared events
4. **Bulk Operations**: Efficient bulk management of chapter data

## Troubleshooting

### Common Issues

1. **Leader cannot see users**: Check if users have selected the leader's chapter as preferred
2. **Access denied errors**: Verify leader is properly assigned to chapters
3. **Missing data**: Ensure proper chapter relationships in database

### Debug Steps

1. Check user role: `auth()->user()->role`
2. Check led chapters: `auth()->user()->ledChapters()`
3. Check user's preferred chapter: `auth()->user()->preferred_chapter_id`
4. Verify database relationships and foreign keys

## Support

For issues or questions regarding this implementation, please refer to the codebase or contact the development team.
