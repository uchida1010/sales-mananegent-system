<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerIndexRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerIndexRequest $request)
    {
        $query = Customer::with([
            'personInCharge',
            'leadSource',
        ]);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('name_kana', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('id')) {
            $id = $request->id;

            $query->where('id', $id);
        }

        if ($request->filled('address')) {
            $address = $request->address;

            $query->where('address', 'like', "%{$address}%");
        }

        if ($request->filled('email')) {
            $email = $request->email;

            $query->whereHas('contacts', function ($q) use ($email) {
                $q->where('email', 'like', "%{$email}%");
            });
        }

        if ($request->filled('telFax')) {
            $telFax = $request->telFax;

            $query->where(function ($q) use ($telFax) {
                $q->where('tel', 'like', "%{$telFax}%")
                    ->orWhere('fax', 'like', "%{$telFax}%");
            });
        }

        if ($request->filled('personInChargeId')) {
            $query->where('person_in_charge_id', $request->personInChargeId);
        }

        if ($request->filled('leadSourceId')) {
            $query->where('lead_source_id', $request->leadSourceId);
        }

        return CustomerResource::collection($query->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
