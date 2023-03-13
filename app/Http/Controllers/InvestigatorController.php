<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class InvestigatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $response = Http::accept('application/json')
            ->get('https://pub.orcid.org/v3.0/'.$request->orcid);

        if ($response->notFound())
            return response()->json(['response' => 'No se Encontro el Orcid']);

        // return $response->json()['person']['keywords']['keyword'];

        $orcidData['orcid'] = $response->json()['orcid-identifier']['path'];
        $orcidData['name'] = $response->json()['person']['name']['given-names']['value'];
        $orcidData['last_name'] = $response->json()['person']['name']['family-name']['value'];

        if (!empty($response->json()['person']['emails']['email']))
        {
            foreach ($response->json()['person']['emails']['email'] as $email)
            {
                if (!$email['primary'])
                    continue;

                $orcidData['principal_email'] = $email['email'];
            }
        }

        return response()->json($orcidData);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
