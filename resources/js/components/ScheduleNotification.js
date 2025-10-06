/**
 * Schedule Notification Component
 * Handles checking for and displaying schedule messages from teamleaders
 */
class ScheduleNotification {
    constructor(teamleaderId, chatContainer) {
        this.teamleaderId = teamleaderId;
        this.chatContainer = chatContainer;
        this.checkInterval = null;
        this.lastCheckTime = null;
        this.isChecking = false;

        this.init();
    }

    init() {
        // Check for schedule messages every 30 seconds
        this.checkInterval = setInterval(() => {
            this.checkForScheduleMessages();
        }, 30000);

        // Initial check
        this.checkForScheduleMessages();
    }

    async checkForScheduleMessages() {
        if (this.isChecking) return;

        this.isChecking = true;

        try {
            const response = await fetch(`/api/teamleader/${this.teamleaderId}/schedule/pending`);
            const hasPending = await response.json();

            // The button styling now handles the notification, so we don't need the banner
            // Just trigger any additional UI updates if needed

        } catch (error) {
            console.error('Error checking for schedule messages:', error);
        } finally {
            this.isChecking = false;
        }
    }

    // Notification functionality is now handled by the button styling in the teamleader component

    destroy() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }
    }
}

// Auto-initialize for teamleader chats
document.addEventListener('DOMContentLoaded', function() {
    const teamleaderChats = document.querySelectorAll('[data-teamleader-chat]');

    teamleaderChats.forEach(chatElement => {
        const teamleaderId = chatElement.getAttribute('data-teamleader-id');
        if (teamleaderId) {
            new ScheduleNotification(teamleaderId, chatElement);
        }
    });
});

export default ScheduleNotification;



