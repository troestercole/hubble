<?php

namespace App\Models\Traits;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    /**
     * Boot the trait and register global scope + creating event.
     */
    protected static function bootBelongsToOrganization(): void
    {
        // Auto-scope all queries to the current organization
        static::addGlobalScope('organization', function (Builder $query) {
            $organizationId = static::resolveCurrentOrganizationId();

            if ($organizationId) {
                $query->where('organization_id', $organizationId);
            }
        });

        // Auto-assign organization_id when creating new records
        static::creating(function ($model) {
            if (!$model->organization_id) {
                $organizationId = static::resolveCurrentOrganizationId();

                if ($organizationId) {
                    $model->organization_id = $organizationId;
                }
            }
        });
    }

    /**
     * Resolve the current organization ID from auth or app container.
     */
    protected static function resolveCurrentOrganizationId(): ?int
    {
        // First, check if there's a manually set organization (for jobs/commands)
        if (app()->bound('currentOrganization') && app('currentOrganization')) {
            return app('currentOrganization')->id;
        }

        // Fall back to authenticated user's organization
        if (auth()->check()) {
            return auth()->user()->organization_id;
        }

        return null;
    }

    /**
     * Get the organization that this model belongs to.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope query to a specific organization (bypasses global scope).
     */
    public function scopeForOrganization(Builder $query, int|Organization $organization): Builder
    {
        $organizationId = $organization instanceof Organization
            ? $organization->id
            : $organization;

        return $query->withoutGlobalScope('organization')
            ->where('organization_id', $organizationId);
    }
}
