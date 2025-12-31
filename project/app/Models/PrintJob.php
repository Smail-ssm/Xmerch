<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'design_file',
        'mockup_preview',
        'quantity',
        'quality_tier',
        'status',
        'printer_id',
        'started_at',
        'completed_at',
        'estimated_time_minutes',
        'actual_time_minutes',
        'notes',
        'priority'
    ];

    protected $dates = [
        'started_at',
        'completed_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_QUEUED = 'queued';
    const STATUS_PRINTING = 'printing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_ON_HOLD = 'on_hold';

    /**
     * Priority constants
     */
    const PRIORITY_HIGH = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_LOW = 3;

    /**
     * Quality tier constants
     */
    const QUALITY_STANDARD = 'standard';
    const QUALITY_PREMIUM = 'premium';
    const QUALITY_DELUXE = 'deluxe';

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function printer()
    {
        return $this->belongsTo(Admin::class, 'printer_id');
    }

    /**
     * Scopes
     */
    public function scopeQueued($query)
    {
        return $query->where('status', self::STATUS_QUEUED);
    }

    public function scopePrinting($query)
    {
        return $query->where('status', self::STATUS_PRINTING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopePriority($query)
    {
        return $query->orderBy('priority', 'asc')
                    ->orderBy('created_at', 'asc');
    }

    public function scopeByPrinter($query, $printerId)
    {
        return $query->where('printer_id', $printerId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_QUEUED => '<span class="badge badge-warning">Queued</span>',
            self::STATUS_PRINTING => '<span class="badge badge-info">Printing</span>',
            self::STATUS_COMPLETED => '<span class="badge badge-success">Completed</span>',
            self::STATUS_FAILED => '<span class="badge badge-danger">Failed</span>',
            self::STATUS_ON_HOLD => '<span class="badge badge-secondary">On Hold</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-light">Unknown</span>';
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_LOW => 'Low',
        ];

        return $labels[$this->priority] ?? 'Normal';
    }

    public function getQualityTierLabelAttribute()
    {
        return ucfirst($this->quality_tier);
    }

    /**
     * Methods
     */
    public function start($printerId = null)
    {
        $this->update([
            'status' => self::STATUS_PRINTING,
            'printer_id' => $printerId ?? auth()->guard('admin')->id(),
            'started_at' => now()
        ]);
    }

    public function complete($notes = null)
    {
        $actualTime = $this->started_at ? now()->diffInMinutes($this->started_at) : null;

        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'actual_time_minutes' => $actualTime,
            'notes' => $notes
        ]);
    }

    public function fail($reason)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'notes' => $reason
        ]);
    }

    public function hold($reason)
    {
        $this->update([
            'status' => self::STATUS_ON_HOLD,
            'notes' => $reason
        ]);
    }

    public function resume()
    {
        $this->update([
            'status' => self::STATUS_QUEUED
        ]);
    }

    /**
     * Calculate estimated completion time
     */
    public function getEstimatedCompletionAttribute()
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return $this->completed_at;
        }

        if ($this->status === self::STATUS_PRINTING && $this->started_at) {
            return $this->started_at->addMinutes($this->estimated_time_minutes ?? 30);
        }

        // Calculate based on queue position
        $queuePosition = PrintJob::queued()
            ->where('priority', '<=', $this->priority)
            ->where('created_at', '<', $this->created_at)
            ->count();

        $estimatedMinutes = ($queuePosition * 30) + ($this->estimated_time_minutes ?? 30);
        return now()->addMinutes($estimatedMinutes);
    }
}
