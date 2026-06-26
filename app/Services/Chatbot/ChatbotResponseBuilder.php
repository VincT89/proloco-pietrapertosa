<?php

namespace App\Services\Chatbot;

class ChatbotResponseBuilder
{
    protected string $reply = '';
    protected string $type = 'text'; // text, cards
    protected array $cards = [];
    protected array $quickReplies = [];
    protected array $links = [];
    protected array $context = [];

    public function setReply(string $reply): self
    {
        $this->reply = $reply;
        return $this;
    }

    public function getReply(): string
    {
        return $this->reply;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    protected function isSafeUrl(string $url): bool
    {
        // Allowed domains
        $allowedPrefixes = [
            '/',
            route('home.it'),
            route('home.en'),
            'https://res.cloudinary.com/',
            'https://www.borgoracconta.it/'
        ];

        // Reject potentially dangerous schemes or malformed paths
        if (preg_match('/^(javascript|data|file):/i', $url) || preg_match('/^(\/|\\\\){2}/', $url)) {
            return false;
        }

        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($url, $prefix)) {
                return true;
            }
        }

        return false;
    }

    public function addCard(array $card): self
    {
        if (isset($card['url']) && !$this->isSafeUrl($card['url'])) {
            $card['url'] = '#';
        }
        if (isset($card['image']) && $card['image'] && !$this->isSafeUrl($card['image'])) {
            $card['image'] = null;
        }

        $this->cards[] = $card;
        $this->type = 'cards';
        return $this;
    }

    public function addQuickReply(string $label, string $payload): self
    {
        $this->quickReplies[] = ['label' => $label, 'payload' => $payload];
        return $this;
    }

    public function addLink(string $label, string $url): self
    {
        if (!$this->isSafeUrl($url)) {
            $url = '#';
        }
        $this->links[] = ['label' => $label, 'url' => $url];
        return $this;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function build(): array
    {
        return [
            'reply' => $this->reply,
            'type' => $this->type,
            'cards' => $this->cards,
            'quickReplies' => $this->quickReplies,
            'links' => $this->links,
            'context' => empty($this->context) ? (object)[] : $this->context, // Always return object for empty context in JSON
        ];
    }
}
