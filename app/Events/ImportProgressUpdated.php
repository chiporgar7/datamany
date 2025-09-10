<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportProgressUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $progressId,
        public readonly string $status,
        public readonly int $processedRows,
        public readonly int $totalRows,
        public readonly float $percentage,
        public readonly ?string $message = null
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('import-progress.' . $this->progressId);
    }

    public function broadcastAs(): string
    {
        return 'progress.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'progress_id' => $this->progressId,
            'status' => $this->status,
            'processed_rows' => $this->processedRows,
            'total_rows' => $this->totalRows,
            'percentage' => $this->percentage,
            'message' => $this->message,
            'timestamp' => now()->toIso8601String()
        ];
    }
}