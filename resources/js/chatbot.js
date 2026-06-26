// Chatbot Logic
document.addEventListener('DOMContentLoaded', () => {
    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatbotClose = document.getElementById('chatbotClose');
    const chatbotPanel = document.getElementById('chatbotPanel');
    const chatbotMessages = document.getElementById('chatbotMessages');
    const chatbotInput = document.getElementById('chatbotInput');
    const chatbotSend = document.getElementById('chatbotSend');
    const chatbotSuggestions = document.getElementById('chatbotSuggestions');

    if (!chatbotToggle || !chatbotPanel) return;

    const locale = document.documentElement.lang || 'it';
    const baseUrl = `/${locale}/chatbot`;

    let context = {};
    let isTyping = false;

    // Load history from session storage
    let messageHistory = JSON.parse(sessionStorage.getItem('chatbotHistory')) || [];

    // Initialize
    function init() {
        if (messageHistory.length === 0) {
            // Fetch initial suggestions
            fetchSuggestions();
            // Show welcome message client-side
            const welcomeMsg = locale === 'en' 
                ? "Hello! I am the Pro Loco virtual assistant. How can I help you today?" 
                : "Ciao! Sono l'assistente virtuale della Pro Loco. Come posso aiutarti oggi?";
            appendMessage('bot', welcomeMsg);
        } else {
            // Restore UI from history
            messageHistory.forEach(msg => {
                if (msg.role === 'user') {
                    appendMessage('user', msg.text, false);
                } else {
                    appendBotResponse(msg, false);
                }
            });
            // Try to fetch context from session storage
            const savedContext = sessionStorage.getItem('chatbotContext');
            if (savedContext) {
                try { context = JSON.parse(savedContext); } catch (e) {}
            }
        }
        scrollToBottom();
    }

    function toggleChat() {
        chatbotPanel.classList.toggle('is-open');
    }

    function closeChat() {
        chatbotPanel.classList.remove('is-open');
    }

    const chatbotClear = document.getElementById('chatbotClear');

    chatbotToggle.addEventListener('click', toggleChat);
    chatbotClose.addEventListener('click', closeChat);

    if (chatbotClear) {
        chatbotClear.addEventListener('click', () => {
            sessionStorage.removeItem('chatbotHistory');
            sessionStorage.removeItem('chatbotContext');
            messageHistory = [];
            context = {};
            chatbotMessages.innerHTML = '';
            
            // Add a small system message indicating chat was cleared, followed by normal initialization
            const clearedMsgDiv = document.createElement('div');
            clearedMsgDiv.style.textAlign = 'center';
            clearedMsgDiv.style.fontSize = '0.8rem';
            clearedMsgDiv.style.color = '#888';
            clearedMsgDiv.style.margin = '10px 0';
            clearedMsgDiv.textContent = locale === 'en' ? 'Chat history cleared' : 'Cronologia chat cancellata';
            chatbotMessages.appendChild(clearedMsgDiv);
            
            init();
        });
    }

    function fetchSuggestions() {
        fetch(`${baseUrl}/suggestions`)
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data)) {
                    renderSuggestions(data);
                }
            })
            .catch(err => console.error(err));
    }

    function renderSuggestions(suggestions) {
        chatbotSuggestions.innerHTML = '';
        suggestions.forEach(text => {
            const btn = document.createElement('button');
            btn.className = 'chatbot-suggestion-btn';
            btn.textContent = text;
            btn.onclick = () => {
                chatbotInput.value = text;
                sendMessage();
            };
            chatbotSuggestions.appendChild(btn);
        });
    }

    async function sendMessage() {
        if (isTyping) return;
        const text = chatbotInput.value.trim();
        if (!text) return;

        chatbotInput.value = '';
        chatbotSuggestions.innerHTML = ''; // Clear suggestions after first message
        
        appendMessage('user', text);
        saveHistory('user', { text });

        showTypingIndicator();
        
        try {
            const response = await fetch(`${baseUrl}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: text,
                    context: context
                })
            });

            if (!response.ok) {
                removeTypingIndicator();
                appendMessage('bot', locale === 'en' ? 'Server error. Please try again later.' : 'Errore del server. Riprova più tardi.');
                return;
            }

            let data;
            try {
                data = await response.json();
            } catch (e) {
                removeTypingIndicator();
                appendMessage('bot', locale === 'en' ? 'Invalid response from server.' : 'Risposta del server non valida.');
                return;
            }

            removeTypingIndicator();
            
            if (data.context) {
                context = data.context;
                sessionStorage.setItem('chatbotContext', JSON.stringify(context));
            }
            appendBotResponse(data);
            saveHistory('bot', data);

        } catch (error) {
            console.error(error);
            removeTypingIndicator();
            appendMessage('bot', locale === 'en' ? 'Network error. Please try again.' : 'Errore generico. Riprova.');
        }
    }

    function appendMessage(role, text, doScroll = true) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `chatbot-message ${role}`;
        
        const bubble = document.createElement('div');
        bubble.className = 'chatbot-bubble';
        bubble.textContent = text;
        
        msgDiv.appendChild(bubble);
        chatbotMessages.appendChild(msgDiv);
        if (doScroll) scrollToBottom();
    }

    function appendBotResponse(data, doScroll = true) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `chatbot-message bot`;
        
        // Text reply
        if (data.reply) {
            const bubble = document.createElement('div');
            bubble.className = 'chatbot-bubble';
            bubble.textContent = data.reply;
            msgDiv.appendChild(bubble);
        }

        // Cards
        if (data.type === 'cards' && data.cards && data.cards.length > 0) {
            const cardsContainer = document.createElement('div');
            cardsContainer.className = 'chatbot-cards';
            
            data.cards.forEach(card => {
                const a = document.createElement('a');
                a.className = 'chatbot-card';
                a.href = card.url;
                
                if (card.image) {
                    const img = document.createElement('img');
                    img.src = card.image;
                    img.className = 'chatbot-card-image';
                    img.alt = card.title;
                    a.appendChild(img);
                }

                const contentDiv = document.createElement('div');
                contentDiv.className = 'chatbot-card-content';
                
                const titleEl = document.createElement('h4');
                titleEl.className = 'chatbot-card-title';
                titleEl.textContent = card.title;
                contentDiv.appendChild(titleEl);

                const subtitleEl = document.createElement('p');
                subtitleEl.className = 'chatbot-card-subtitle';
                subtitleEl.textContent = card.subtitle;
                contentDiv.appendChild(subtitleEl);

                const descEl = document.createElement('p');
                descEl.className = 'chatbot-card-desc';
                descEl.textContent = card.description;
                contentDiv.appendChild(descEl);

                a.appendChild(contentDiv);
                cardsContainer.appendChild(a);
            });
            msgDiv.appendChild(cardsContainer);
        }

        // Links
        if (data.links && data.links.length > 0) {
            const linksContainer = document.createElement('div');
            linksContainer.className = 'chatbot-links';
            
            data.links.forEach(link => {
                const a = document.createElement('a');
                a.className = 'chatbot-link';
                a.href = link.url;
                a.textContent = link.label;
                
                try {
                    const parsedUrl = new URL(link.url, window.location.origin);
                    if (parsedUrl.origin !== window.location.origin) {
                        a.target = '_blank';
                        a.rel = 'noopener noreferrer';
                    }
                } catch (e) {
                    // Ignore parsing errors
                }

                linksContainer.appendChild(a);
            });
            msgDiv.appendChild(linksContainer);
        }

        // Quick Replies
        if (data.quickReplies && data.quickReplies.length > 0) {
            const qrContainer = document.createElement('div');
            qrContainer.className = 'chatbot-quick-replies';
            
            data.quickReplies.forEach(qr => {
                const btn = document.createElement('button');
                btn.className = 'chatbot-quick-reply-btn';
                btn.textContent = qr.label;
                btn.onclick = () => {
                    chatbotInput.value = qr.payload;
                    sendMessage();
                };
                qrContainer.appendChild(btn);
            });
            msgDiv.appendChild(qrContainer);
        }

        chatbotMessages.appendChild(msgDiv);
        if (doScroll) scrollToBottom();
    }

    function showTypingIndicator() {
        isTyping = true;
        chatbotSend.disabled = true;
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-typing';
        typingDiv.id = 'chatbotTyping';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';
        chatbotMessages.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        isTyping = false;
        chatbotSend.disabled = false;
        const typingDiv = document.getElementById('chatbotTyping');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    function scrollToBottom() {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function saveHistory(role, data) {
        // Keep last 10 messages
        messageHistory.push({ role, ...data });
        if (messageHistory.length > 20) {
            messageHistory.shift();
        }
        sessionStorage.setItem('chatbotHistory', JSON.stringify(messageHistory));
    }

    chatbotSend.addEventListener('click', sendMessage);
    chatbotInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    init();
});
