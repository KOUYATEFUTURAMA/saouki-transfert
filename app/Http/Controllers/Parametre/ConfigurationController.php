<?php

namespace App\Http\Controllers\Parametre;

use Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Parametre\Configuration;
use Illuminate\Support\Carbon;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infoConfig = Configuration::find(1);
        $menuPrincipal = "Parametre";
        $titleControlleur = "Configuration du logiciel";
        $btnModalAjout = "FALSE";

        if(Auth::user()->id == 1){
            return view('parametre.configuration.index', compact('infoConfig','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }
     /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
    public function show($id)
    {
        $infoConfig = Configuration::find($id);
        $menuPrincipal = "Parametre";
        $titleControlleur = "Configuration du logiciel";
        $btnModalAjout = "FALSE";

        if(Auth::user()->id == 1){
            return view('parametre.configuration.update', compact('infoConfig','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->all();

        $request->validate([
            'nom_compagnie' => 'required',
            'nom_responsable' => 'required',
            'contact_responsable' => 'required',
        ]);

        $configuration = isset($data['id']) ? Configuration::findOrFail($data['id']) : new Configuration();
        $configuration->nom_compagnie = $data['nom_compagnie'];
        $configuration->nom_responsable = $data['nom_responsable'];
        $configuration->contact_responsable = $data['contact_responsable'];
        $configuration->cellulaire = isset($data['cellulaire']) && !empty($data['cellulaire']) ? $data['cellulaire'] : null;
        $configuration->telephone_fixe = isset($data['telephone_fixe']) && !empty($data['telephone_fixe']) ? $data['telephone_fixe'] : null;
        $configuration->telephone_faxe = isset($data['telephone_faxe']) && !empty($data['telephone_faxe']) ? $data['telephone_faxe'] : null;
        $configuration->site_web_compagnie = isset($data['site_web_compagnie']) && !empty($data['site_web_compagnie']) ? $data['site_web_compagnie'] : null;
        $configuration->adresse_compagnie = isset($data['adresse_compagnie']) && !empty($data['adresse_compagnie']) ? $data['adresse_compagnie'] : null;
        $configuration->email_compagnie = isset($data['email_compagnie']) && !empty($data['email_compagnie']) ? $data['email_compagnie'] : null;
        $configuration->expiration_licence = isset($data['expiration_licence']) && !empty($data['expiration_licence']) ? Carbon::createFromFormat('d-m-Y', $data['expiration_licence']) : null;

        //Insertion de l'image du logo
        if (isset($data['logo']) && !empty($data['logo'])) {
            $file_name = Str::slug('logo'.$data['nom_compagnie'] . date('dmY')) . '.jpg';

            $img = Image::make($data['logo']);
            $img->resize(200, 200);

            $img->save(storage_path('app/public/images/logo/'.$file_name),60);

            $configuration->logo = 'storage/images/logo/' . $file_name;
        }
        $configuration->save();
       
        return redirect()->route('parametre.configurations.index');
    }
}
