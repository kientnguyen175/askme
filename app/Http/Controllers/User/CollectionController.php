<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class CollectionController extends Controller
{
    public function show($collectionId)
    {
        $collection = Collection::with(['questions.user', 'questions.content', 'questions.tags'])->where('id', $collectionId)->first();

        return view('collection', compact('collection'));
    }

    public function update(Request $request, $collectionId)
    {
        if (isset($request->image)) {
            $collectionImageName = time() . '_' . $request->image->getClientOriginalName();
            $whereToSaveCollectionImage = public_path('images/uploads');
            $request->image->move($whereToSaveCollectionImage, $collectionImageName);
            $url = "http://localhost:8000/images/uploads/$collectionImageName" ;
            Collection::find($collectionId)->update([
                'name' => $request->name,
                'image' => $url
            ]);
        } else {
            Collection::find($collectionId)->update([
                'name' => $request->name,
            ]);
        }

        return response()->json(['name' => $request->name]);
    }

    public function destroy($collectionId)
    {
        $collection = Collection::find($collectionId);
        $collection->questions()->detach();
        $collection->delete();
    }
}
