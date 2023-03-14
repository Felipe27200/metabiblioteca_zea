<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


use App\Models\Investigator;
use App\Models\Keyword;
use Illuminate\Support\Facades\DB;

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
            return response()->json(['response' => 'No se Encontro el orcid']);

        $orcidData['orcid'] = $response->json()['orcid-identifier']['path'];

        $orcidExists = $this->findInvestigator($orcidData['orcid'], 'El registro ya está en base de datos');

        if (gettype($orcidExists) != 'boolean')
            return $orcidExists;

        unset($orcidExists);

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

        $orcid = Investigator::create($orcidData);

        $keywords = array();

        if (!empty($response->json()['person']['keywords']['keyword']))
        {
            foreach($response->json()['person']['keywords']['keyword'] as $keyword)
            {
                array_push($keywords, array(
                    "id" => $keyword['put-code'],
                    "investigator_id" => $orcid->orcid,
                    "keyword" => $keyword['content']
                ));
            }
        }

        Keyword::insert($keywords);

        return response()->json(['response' => 'Se logro Nea!!!']);
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
    public function show($id)
    {
        $orcid = Investigator::findOr($id, function () {
            return false;
        });

        if (gettype($orcid) == 'boolean')
            return response()->json(["response" => 'Registro no encontrado']);

        $keywords = Investigator::find($id)->keywords;

        return response()->json([
            'response' => 'Exito',
            'orcid' => $orcid,
            'keywords' => $keywords
        ]);
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
        $orcid = Investigator::findOr($id, function () {
            return false;
        });

        if (gettype($orcid) == 'boolean')
            return response()->json(['response' => 'El registro no está en Base de Datos']);

        Keyword::where('investigator_id', $id)->delete();
        $orcid->delete();

        return response()->json(['response' => 'Eliminado satisfactoriamente']);
    }

    public function findInvestigator($orcid, $message)
    {
        $orcidExists = Investigator::findOr($orcid, function () {
            return false;
        });

        if (gettype($orcidExists) != 'boolean')
            return response()->json(['response' => $message]);
        else
            return false;
    }
}
