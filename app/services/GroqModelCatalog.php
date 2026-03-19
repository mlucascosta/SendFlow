<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Catálogo local de modelos e rotas de fallback da Groq.
 * Local catalog of Groq models and fallback routes.
 */
class GroqModelCatalog
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public static function models(): array
    {
        return [
            'groq/compound' => ['capabilities' => ['reasoning', 'tools', 'tasks']],
            'groq/compound-mini' => ['capabilities' => ['summary', 'routing', 'classification']],
            'openai/gpt-oss-120b' => ['capabilities' => ['reasoning', 'long-form']],
            'openai/gpt-oss-20b' => ['capabilities' => ['summary', 'general']],
            'openai/gpt-oss-safeguard-20b' => ['capabilities' => ['moderation', 'safety']],
            'llama-3.1-8b-instant' => ['capabilities' => ['fallback', 'fast-summary']],
            'llama-3.3-70b-versatile' => ['capabilities' => ['general', 'classification']],
            'meta-llama/llama-4-maverick-17b-128e-instruct' => ['capabilities' => ['reasoning', 'labels']],
            'meta-llama/llama-4-scout-17b-16e-instruct' => ['capabilities' => ['classification', 'labels']],
            'meta-llama/llama-guard-4-12b' => ['capabilities' => ['moderation', 'spam']],
            'meta-llama/llama-prompt-guard-2-22m' => ['capabilities' => ['prompt-safety']],
            'meta-llama/llama-prompt-guard-2-86m' => ['capabilities' => ['prompt-safety', 'moderation']],
            'moonshotai/kimi-k2-instruct' => ['capabilities' => ['long-context', 'reasoning']],
            'moonshotai/kimi-k2-instruct-0905' => ['capabilities' => ['reasoning', 'long-context']],
            'qwen/qwen3-32b' => ['capabilities' => ['tasks', 'planning']],
            'allam-2-7b' => ['capabilities' => ['multilingual']],
            'whisper-large-v3' => ['capabilities' => ['audio']],
            'whisper-large-v3-turbo' => ['capabilities' => ['audio', 'fast']],
        ];
    }

    /**
     * @return array<int,string>
     */
    public static function featureFallbackChain(string $feature): array
    {
        return match ($feature) {
            'email_summary' => ['groq/compound-mini', 'openai/gpt-oss-20b', 'llama-3.1-8b-instant'],
            'spam_guard' => ['meta-llama/llama-guard-4-12b', 'openai/gpt-oss-safeguard-20b', 'meta-llama/llama-prompt-guard-2-86m'],
            'task_extraction' => ['groq/compound', 'qwen/qwen3-32b', 'openai/gpt-oss-20b'],
            'smart_labels' => ['meta-llama/llama-4-scout-17b-16e-instruct', 'groq/compound-mini', 'llama-3.1-8b-instant'],
            'schedule_recommendation' => ['groq/compound', 'moonshotai/kimi-k2-instruct', 'qwen/qwen3-32b'],
            default => ['openai/gpt-oss-20b', 'llama-3.1-8b-instant'],
        };
    }
}
