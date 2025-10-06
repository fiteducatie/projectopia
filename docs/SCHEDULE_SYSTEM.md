# Schedule System Documentation

## Overview

The schedule system allows team leaders to proactively communicate with users about scheduled activities. When a schedule item is active (current time is between `time_from` and `time_until`), the team leader automatically sends a message about the activity.

## Components

### 1. ScheduleService (`app/Services/ScheduleService.php`)
- **Purpose**: Core service that handles schedule logic
- **Key Methods**:
  - `getActiveScheduleItems()`: Gets currently active schedule items
  - `generateScheduleMessage()`: Creates personalized messages based on teamleader personality
  - `shouldShowScheduleMessage()`: Checks if a schedule message should be displayed

### 2. TeamleaderChatController (`app/Http/Controllers/Api/TeamleaderChatController.php`)
- **Purpose**: Handles teamleader chat with schedule message injection
- **Key Features**:
  - Automatically injects schedule messages when active
  - Provides API endpoints for checking pending messages
  - Maintains message persistence during schedule period

### 3. ScheduleNotification (`resources/js/components/ScheduleNotification.js`)
- **Purpose**: Frontend component for notifications
- **Features**:
  - Checks for schedule messages every 30 seconds
  - Shows notification banners when new schedule messages are available
  - Provides visual feedback with pulse animations

### 4. Schedule Tabs
- **Form Tab**: `app/Filament/Resources/ProjectResource/Tabs/ScheduleTab.php`
- **Infolist Tab**: `app/Filament/Resources/ProjectResource/InfolistTabs/ScheduleInfolistTab.php`

## How It Works

### 1. Schedule Creation
- Users create schedule items in the ProjectResource form
- Each item has: `time_from`, `time_until`, `title`, `description`
- Schedule is stored as JSON in the `schedule` column

### 2. Active Schedule Detection
- The system continuously checks if current time falls within any schedule item
- Uses Carbon for precise time comparisons

### 3. Message Generation
- When an active schedule is detected, a message is generated
- Message style depends on teamleader's `communication_style`:
  - **Direct**: Short and to the point
  - **Enthousiasmerend**: Exciting and motivational
  - **Data-gedreven**: Structured and professional
  - **Informeel**: Casual and friendly
  - **Default**: Professional with signature

### 4. Message Display
- Schedule messages appear at the top of the chat
- They have a distinct blue styling to differentiate from regular messages
- Messages persist for the entire duration of the schedule item
- Users receive notifications when new schedule messages are available

### 5. Notification System
- JavaScript component checks for pending messages every 30 seconds
- Shows notification banners when schedule messages are available
- Provides visual feedback on chat buttons/tabs

## API Endpoints

- `GET /api/teamleader/{id}/messages` - Get chat messages (including schedule messages)
- `GET /api/teamleader/{id}/schedule/pending` - Check if there are pending schedule messages

## Configuration

### Schedule Item Structure
```json
{
  "time_from": "2024-01-15 09:00:00",
  "time_until": "2024-01-15 10:30:00", 
  "title": "Daily Standup",
  "description": "Team synchronization and progress update"
}
```

### Teamleader Communication Styles
- `direct` - Short and direct messages
- `enthousiasmerend` - Exciting and motivational
- `data-gedreven` - Structured and professional  
- `informeel` - Casual and friendly
- Default - Professional with signature

## Maintenance

### Cleanup Command
- `php artisan schedule:cleanup` - Cleans up expired schedule messages
- Can be added to cron for automated cleanup

### Monitoring
- Check logs for schedule service errors
- Monitor API endpoint performance
- Track schedule message delivery rates

## Future Enhancements

1. **Recurring Schedules**: Support for daily/weekly recurring items
2. **Schedule Templates**: Pre-defined schedule templates for common activities
3. **Advanced Notifications**: Email/SMS notifications for important schedule items
4. **Analytics**: Track engagement with schedule messages
5. **Integration**: Connect with external calendar systems



