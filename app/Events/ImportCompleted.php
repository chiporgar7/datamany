<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $progressId,
        public readonly string $type,
        public readonly bool $success,
        public readonly int $totalProcessed,
        public readonly ?string $errorMessage = null
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('import-progress.' . $this->progressId);
    }

    public function broadcastAs(): string
    {
        return 'import.completed';
    }

    public function broadcastWith(): array
    {
        return [
            'progress_id' => $this->progressId,
            'type' => $this->type,
            'success' => $this->success,
            'total_processed' => $this->totalProcessed,
            'error_message' => $this->errorMessage,
            'completed_at' => now()->toIso8601String()
        ];
    }
}