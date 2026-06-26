<div id="proloco-chatbot">
    <button class="chatbot-toggle" id="chatbotToggle" aria-label="Open Chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="10" rx="2"></rect>
            <circle cx="12" cy="5" r="2"></circle>
            <path d="M12 7v4"></path>
            <line x1="8" y1="16" x2="8" y2="16"></line>
            <line x1="16" y1="16" x2="16" y2="16"></line>
        </svg>
    </button>

    <div class="chatbot-panel" id="chatbotPanel">
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                        <circle cx="12" cy="5" r="2"></circle>
                        <path d="M12 7v4"></path>
                        <line x1="8" y1="16" x2="8" y2="16"></line>
                        <line x1="16" y1="16" x2="16" y2="16"></line>
                    </svg>
                    <span class="chatbot-online"></span>
                </div>
                <div>
                    <h3 class="chatbot-header-title">Pro Loco Assistant</h3>
                    <span class="chatbot-status-text">Online</span>
                </div>
            </div>
            <div class="chatbot-header-actions" style="display: flex; gap: 8px; align-items: center;">
                <button class="chatbot-clear" id="chatbotClear" aria-label="Clear Chat" style="background: none; border: none; color: white; cursor: pointer; padding: 4px;" title="{{ app()->getLocale() === 'en' ? 'Clear Chat' : 'Cancella Chat' }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
                <button class="chatbot-close" id="chatbotClose">&times;</button>
            </div>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <!-- Messages will be rendered here -->
        </div>
        <div class="chatbot-suggestions" id="chatbotSuggestions">
            <!-- Suggestions will be rendered here -->
        </div>
        <div class="chatbot-input-area">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="{{ app()->getLocale() === 'en' ? 'Type a message...' : 'Scrivi un messaggio...' }}" autocomplete="off">
            <button class="chatbot-send" id="chatbotSend" aria-label="Send">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>
</div>
