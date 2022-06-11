<?php

namespace App\Models\Parametre;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = ['numero_compte_banque','banque','nom_compagnie','nc_tresor','ncc','rccm','capital','type_compagnie','email_compagnie','nom_responsable','contact_responsable','logo', 'cellulaire','telephone_fixe','telephone_faxe','site_web_compagnie','adresse_compagnie','expiration_licence'];
}
