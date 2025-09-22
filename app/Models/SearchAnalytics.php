<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'user_id',
        'ip_address',
        'user_agent',
        'results_count',
        'filters_applied',
        'session_id',
        'searched_at',
    ];

    protected $casts = [
        'filters_applied' => 'array',
        'searched_at' => 'datetime',
    ];

    /**
     * Get the user that performed this search.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for recent searches within a time period.
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('searched_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope for popular searches.
     */
    public function scopePopular($query, $limit = 20)
    {
        return $query->select('query', \DB::raw('COUNT(*) as search_count'))
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit($limit);
    }

    /**
     * Scope for trending searches (high growth rate).
     */
    public function scopeTrending($query, $recentHours = 24, $compareHours = 48)
    {
        $recentStart = now()->subHours($recentHours);
        $compareStart = now()->subHours($compareHours);

        return $query->select('query')
            ->selectRaw('
                COUNT(CASE WHEN searched_at >= ? THEN 1 END) as recent_count,
                COUNT(CASE WHEN searched_at >= ? AND searched_at < ? THEN 1 END) as previous_count
            ', [$recentStart, $compareStart, $recentStart])
            ->where('searched_at', '>=', $compareStart)
            ->groupBy('query')
            ->having('recent_count', '>', 0)
            ->orderByRaw('CASE WHEN previous_count > 0 THEN (recent_count - previous_count) / previous_count ELSE recent_count END DESC')
            ->orderBy('recent_count', 'desc');
    }
}
