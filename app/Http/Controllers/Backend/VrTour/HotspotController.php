<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use App\Libs\DataGrid;
use App\Libs\Util;
use App\Models\Project;
use App\Models\Hotspot;
use Auth;

class HotspotController extends Controller
{
    public $hotspot;

    public function __construct(Hotspot $hotspot)
    {
        $this->hotspot = $hotspot;
        $this->selectedMainMenu = 'hotspot';
        parent::__construct();

        if (!Gate::allows('hotspot')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vrtour     = Project::all();
        return view('backend.vrtour.hotspot.index', compact(['vrtour']));
    }

    public function getHotspot(Request $request, $vrtour_id)
    {
        //get tour
        $vrtour         = Project::find($vrtour_id);
        $link_vrtour    = $vrtour->link_vrtour;
        //get hotspot from db
        $hotspot_db = Hotspot::where('vrtour_id', $vrtour_id)->whereIn('type', $request->type == 0 ? [1,2] : [1,2,3])->get();
        if (count($hotspot_db) == 0) {
            $hotspot       = json_decode(getDataVrtour($link_vrtour.'vista3d/hotspot.json'), true);
            foreach ($hotspot as $hp) {
                $new_hp             = new Hotspot;
                $new_hp->vrtour_id  = $vrtour_id;
                $new_hp->potision   = $hp['position'];
                $new_hp->url        = $hp['url'];
                $new_hp->opacity    = $hp['opacity'];
                $new_hp->tooltip    = (!str_contains($hp['position'], 'cms_eye') && !str_contains($hp['position'], 'cms_fly')) ? $hp['tooltip'] : '';
                $new_hp->type       = str_contains($hp['position'], 'cms_') ? 1 : (str_contains($hp['position'], 'cmss_') ? 2 : 3);
                $new_hp->user_id    = Auth::id();
                $new_hp->save();
            }
            $hotspot_db = Hotspot::where('vrtour_id', $vrtour_id)->whereIn('type', $request->type == 0 ? [1,2] : [3])->get();
            createFile('vrtour/'.$vrtour->name, 'hotspot.js');
            file_put_contents('vrtour/'.$vrtour->name.'/hotspot.js', Hotspot::where('vrtour_id', $vrtour_id)->get());
        } 
        $html = '';
        foreach ($hotspot_db as $key => $hp) {
            $html .= '<tr>';
            $html .= '<td>'.(++$key).'</td>';
            $html .= '<td><img src="'.$hp->url.'" style="width:100px;height:100px;""></td>';
            $html .= '<td>'.$hp->potision.'</td>';
            $html .= '<td>'.$hp->tooltip.'</td>';
            $html .= '<td>'.$hp->opacity.'</td>';
            $html .= '<td class="grid_row1">';
            $html .=    '<a class="btn btn-info btn-sm mr-1" href="'.route('backend_vrtour_hotspot_edit', $hp->id).'" title="Chỉnh sửa"><i class="fas fa-pencil-alt"></i></a>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        return response()->json(['data' => $html]);
    }

    public function edit($hotspot_id)
    {
        if (!Gate::allows('hotspot/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $hotspot = Hotspot::findorFail($hotspot_id);
        return view('backend.vrtour.hotspot.edit', compact(['hotspot']));
    }

    public function store(Request $request, $hotspot_id)
    {
        if (!Gate::allows('hotspot/update')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $request->validate([
            'hp_url'        => 'required',
            'hp_potision'   => 'required',
            'hp_opacity'    => 'required',
        ]);
        $new_hp             = Hotspot::findorFail($hotspot_id);
        $new_hp->potision   = $request->hp_potision;
        $new_hp->url        = $request->hp_url;
        $new_hp->url_en     = $request->hp_url_en;
        $new_hp->opacity    = $request->hp_opacity;
        $new_hp->tooltip    = $request->hp_tooltip;
        $new_hp->tooltip_en = $request->hp_tooltip_en;
        $new_hp->user_id    = Auth::id();
        $new_hp->save();
        file_put_contents('vrtour/'.Project::find($new_hp->vrtour_id)->name.'/hotspot.js', Hotspot::where('vrtour_id', $new_hp->vrtour_id)->get());
        return redirect()->to(route('backend_vrtour_hotspot_index') . '?vrtour='.$new_hp->vrtour_id.'&type='.($new_hp->type == 3 ? 1 : 0))->with('success', 'Cập nhật thông tin thành công');
    }
}
