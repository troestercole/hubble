<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Http\Resources\OrganizationResource;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Organization::whereHas('users', function ($query) {
            $query->where('id', auth()->id());
        })->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $organization = Organization::create([
            'name' => $request->name,
        ]);

        auth()->user()->update(['organization_id' => $organization->id]);

        auth()->user()->assignRole('organization_admin');

        return new OrganizationResource($organization);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return new OrganizationResource(auth()->user()->organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $organization->update([
            'name' => $request->name,
        ]);

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        $organization->delete();

        return response()->noContent();
    }
}
