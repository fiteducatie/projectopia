/**
 * Schedule History Component
 * Shows a history button and modal with all past schedule information
 */
class ScheduleHistory {
    constructor(projectSlug, container) {
        this.projectSlug = projectSlug;
        this.container = container;
        this.historyData = null;
        this.isLoading = false;

        this.init();
    }

    init() {
        this.createHistoryButton();
        this.createHistoryModal();
    }

    createHistoryButton() {
        const button = document.createElement('button');
        button.className = 'inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors';
        button.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Team Update History
        `;

        button.addEventListener('click', () => {
            this.openHistoryModal();
        });

        this.container.appendChild(button);
    }

    createHistoryModal() {
        const modal = document.createElement('div');
        modal.id = 'schedule-history-modal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 hidden';
        modal.innerHTML = `
            <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <header class="flex items-center justify-between px-6 py-4 bg-blue-50 border-b border-blue-200">
                    <h2 class="text-xl font-bold text-blue-900">Team Update History</h2>
                    <button onclick="this.closest('#schedule-history-modal').classList.add('hidden')"
                            class="p-2 hover:bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </header>

                <!-- Stats Bar -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="grid grid-cols-4 gap-4" id="schedule-stats">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600" id="total-count">-</div>
                            <div class="text-sm text-gray-600">Totaal</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600" id="completed-count">-</div>
                            <div class="text-sm text-gray-600">Voltooid</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600" id="upcoming-count">-</div>
                            <div class="text-sm text-gray-600">Gepland</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600" id="active-count">-</div>
                            <div class="text-sm text-gray-600">Actief</div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="px-6 py-3 bg-white border-b border-gray-200">
                    <div class="flex space-x-1">
                        <button class="filter-tab active px-4 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700"
                                data-filter="all">Alle</button>
                        <button class="filter-tab px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100"
                                data-filter="completed">Voltooid</button>
                        <button class="filter-tab px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100"
                                data-filter="upcoming">Gepland</button>
                        <button class="filter-tab px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100"
                                data-filter="active">Actief</button>
                    </div>
                </div>

                <!-- Content -->
                <main class="p-6 max-h-96 overflow-y-auto">
                    <div id="schedule-history-content">
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Laden van schedule geschiedenis...</p>
                        </div>
                    </div>
                </main>

                <!-- Footer -->
                <footer class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span id="showing-count">0</span> van <span id="total-items">0</span> items
                        </div>
                        <button onclick="this.closest('#schedule-history-modal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Sluiten
                        </button>
                    </div>
                </footer>
            </div>
        `;

        document.body.appendChild(modal);
        this.setupFilterTabs();
    }

    setupFilterTabs() {
        const tabs = document.querySelectorAll('.filter-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                tabs.forEach(t => {
                    t.classList.remove('active', 'bg-blue-100', 'text-blue-700');
                    t.classList.add('text-gray-600', 'hover:bg-gray-100');
                });

                // Add active class to clicked tab
                tab.classList.add('active', 'bg-blue-100', 'text-blue-700');
                tab.classList.remove('text-gray-600', 'hover:bg-gray-100');

                // Filter content
                const filter = tab.dataset.filter;
                this.filterContent(filter);
            });
        });
    }

    async openHistoryModal() {
        const modal = document.getElementById('schedule-history-modal');
        modal.classList.remove('hidden');

        if (!this.historyData) {
            await this.loadHistoryData();
        } else {
            this.renderContent();
        }
    }

    async loadHistoryData() {
        if (this.isLoading) return;

        this.isLoading = true;

        try {
            const response = await fetch(`/api/schedule/project/${this.projectSlug}/history?with_messages=true`);
            const result = await response.json();

            if (result.success) {
                this.historyData = result.data;
                this.updateStats(result.stats);
                this.renderContent();
            } else {
                throw new Error('Failed to load history data');
            }
        } catch (error) {
            console.error('Error loading schedule history:', error);
            this.showError('Er is een fout opgetreden bij het laden van de schedule geschiedenis.');
        } finally {
            this.isLoading = false;
        }
    }

    updateStats(stats) {
        document.getElementById('total-count').textContent = stats.total;
        document.getElementById('completed-count').textContent = stats.completed;
        document.getElementById('upcoming-count').textContent = stats.upcoming;
        document.getElementById('active-count').textContent = stats.active;
    }

    renderContent(filter = 'all') {
        const content = document.getElementById('schedule-history-content');

        if (!this.historyData || this.historyData.length === 0) {
            content.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600">Geen schedule items gevonden</p>
                </div>
            `;
            return;
        }

        const filteredData = filter === 'all' ? this.historyData :
                           this.historyData.filter(item => item.status === filter);

        content.innerHTML = filteredData.map(item => this.renderScheduleItem(item)).join('');

        // Update counters
        document.getElementById('showing-count').textContent = filteredData.length;
        document.getElementById('total-items').textContent = this.historyData.length;
    }

    renderScheduleItem(item) {
        const statusColors = {
            'completed': 'bg-green-100 text-green-800',
            'upcoming': 'bg-yellow-100 text-yellow-800',
            'active': 'bg-purple-100 text-purple-800'
        };

        const statusLabels = {
            'completed': 'Voltooid',
            'upcoming': 'Gepland',
            'active': 'Actief'
        };

        return `
            <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">${item.title}</h3>
                        <div class="flex items-center gap-4 mt-1 text-sm text-gray-600">
                            <span>📅 ${item.time_from_formatted} - ${item.time_until_formatted}</span>
                            <span>⏱️ ${item.duration}</span>
                            ${item.days_ago ? `<span>${item.days_ago} dagen geleden</span>` : ''}
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${statusColors[item.status]}">
                        ${statusLabels[item.status]}
                    </span>
                </div>

                <p class="text-gray-700 mb-4">${item.description}</p>

                ${item.teamleaders ? `
                    <div class="border-t border-gray-200 pt-3">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Teamleider berichten:</h4>
                        <div class="space-y-2">
                            ${item.teamleaders.map(tl => `
                                <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                                    ${tl.avatar_url ? 
                                        `<img src="${tl.avatar_url}" alt="${tl.name}" class="w-8 h-8 rounded-full">` :
                                        `<div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-xs font-medium">${tl.name.charAt(0)}</div>`
                                    }
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-medium text-blue-900">${tl.name}</div>
                                            <div class="text-xs text-blue-600">${this.formatTimestamp(tl.timestamp)}</div>
                                        </div>
                                        <div class="text-sm text-blue-700 mt-1">${this.parseMarkdown(tl.message)}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    parseMarkdown(text) {
        if (!text) return '';
        
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/__(.*?)__/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/_(.*?)_/g, '<em>$1</em>')
            .replace(/\n/g, '<br>')
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-blue-600 underline" target="_blank">$1</a>');
    }

    formatTimestamp(timestamp) {
        if (!timestamp) return '';
        
        const date = new Date(timestamp);
        return date.toLocaleTimeString('nl-NL', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    filterContent(filter) {
        this.renderContent(filter);
    }

    showError(message) {
        const content = document.getElementById('schedule-history-content');
        content.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-600">${message}</p>
            </div>
        `;
    }
}

// Auto-initialize for projects
document.addEventListener('DOMContentLoaded', function() {
    const projectContainer = document.querySelector('[data-project-container]');
    if (projectContainer) {
        const projectSlug = projectContainer.getAttribute('data-project-slug');
        if (projectSlug) {
            new ScheduleHistory(projectSlug, projectContainer);
        }
    }
});

export default ScheduleHistory;
