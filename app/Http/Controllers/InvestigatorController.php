<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestigatorCollection;
use App\Http\Resources\InvestigatorResource;
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
        return new InvestigatorCollection(Investigator::paginate(2));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store($createOrcid)
    {
        $response = Http::accept('application/json')
            ->get('https://pub.orcid.org/v3.0/'.$createOrcid);

        if ($response->notFound())
        {
            return response()->json([
                'response' => 'unsuccessful',
                'message' => 'No se Encontro el orcid'
            ]);
        }

        $orcidData['orcid'] = $response->json()['orcid-identifier']['path'];

        $orcidExists = $this->findInvestigator($orcidData['orcid'], 'El registro ya está en base de datos');

        if (gettype($orcidExists) != 'boolean')
            return $orcidExists;

        unset($orcidExists);

        $orcidData['name'] = $response->json()['person']['name']['given-names']['value'];
        $orcidData['last_name'] = $response->json()['person']['name']['family-name']['value'];

        if (!empty($response->json()['person']['emails']['email']))
            $orcidData['principal_email'] = $this->setEmail($response->json()['person']['emails']['email']);

        $orcid = Investigator::create($orcidData);

        $keywords = array();

        if (!empty($response->json()['person']['keywords']['keyword']))
            $keywords = $this->setKeywords($response->json()['person']['keywords']['keyword'], $orcid->orcid);

        Keyword::insert($keywords);

        return response()->json([
            'response' => 'succesful',
            'message' => 'Registro Exitoso'
        ]);
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
        {
            return response()->json([
                "response" => 'unsuccesful',
                'message' => 'Registro no encontrado'
            ]);

        }

        return new InvestigatorResource($orcid);
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
        {
            return response()->json([
                'response' => 'unsuccesful',
                'message' => 'El registro no está en Base de Datos'
            ]);
        }

        Keyword::where('investigator_id', $id)->delete();
        $orcid->delete();

        return response()->json([
            'response' => 'succesful',
            'message' => 'Eliminado satisfactoriamente'
        ]);
    }

    public function findInvestigator($orcid, $message)
    {
        $orcidExists = Investigator::findOr($orcid, function () {
            return false;
        });

        if (gettype($orcidExists) != 'boolean')
        {
            return response()->json([
                'response' => 'unsuccesful',
                'message' => $message
            ]);
        }
        else
            return false;
    }

    private function setEmail($emails)
    {
        foreach ($emails as $email)
        {
            if (!$email['primary'])
                continue;

            return $email['email'];
        }
    }

    private function setKeywords($keywords, $orcid)
    {
        $setKeywords = array();

        foreach($keywords as $keyword)
        {
            array_push($setKeywords, array(
                "id" => $keyword['put-code'],
                "investigator_id" => $orcid,
                "keyword" => $keyword['content']
            ));
        }

        return $setKeywords;
    }
}
