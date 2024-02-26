<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller
{

    //-------------- Get All Categories ---------------\\

    public function index(Request $request)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            if ($request->ajax()) {
                $data = Category::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
                
                return Datatables::of($data)->addIndexColumn()

                ->addColumn('action', function($row){

                        $btn = '<a id="' .$row->id. '"  class="edit cursor-pointer ul-link-action text-success"
                        data-toggle="tooltip" data-placement="top" title="Edit"><i class="far fa-edit"></i></a>';
                        $btn .= '&nbsp;&nbsp;';

                        $btn .= '<a id="' .$row->id. '" class="delete cursor-pointer ul-link-action text-danger"
                        data-toggle="tooltip" data-placement="top" title="Remove"><i class="far fa-times-circle"></i></a>';
                        $btn .= '&nbsp;&nbsp;';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('products.categories');

        }
        return abort('403', __('You are not authorized'));
     
    }

    public function create(Request $request)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            $results = Category::where('parent_id', 0)
                ->where('deleted_at', null)
                ->orderBy('name', 'ASC')
                ->get(['id', 'name']);
                
            return response()->json([
                'parents' => $results,
            ]);

        }
        return abort('403', __('You are not authorized'));

    }

    //-------------- Store New Category ---------------\\

    public function store(Request $request)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            request()->validate([
                'name' => 'required',
                //'parent_id' => 'required',
            ]);

            Category::create([
                'parent_id' => (int)$request['parent_id'],
                'name' => $request['name'],
            ]);
            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }

     //------------ function show -----------\\

    public function show($id){
        //
    
    }

    public function edit(Request $request, $id)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            $parents = Category::where('id' , '!=', $id)->where('parent_id', 0)
                ->where('deleted_at', null)
                ->orderBy('name', 'ASC')
                ->get(['id', 'name']);

            $category = Category::where('deleted_at', '=', null)->findOrFail($id);
                
            return response()->json([
                'category' => $category,
                'parents' => $parents,
            ]);

        }
        return abort('403', __('You are not authorized'));
    }

    //-------------- Update Category ---------------\\

    public function update(Request $request, $id)
    {

        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            request()->validate([
                'name' => 'required',
                //'parent_id' => 'required',
            ]);

            Category::whereId($id)->update([
                'parent_id' => (int)$request['parent_id'],
                'name' => $request['name'],
            ]);

            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));

    }

    //-------------- Remove Category ---------------\\

    public function destroy(Request $request, $id)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            Category::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('category')){

            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $category_id) {
                Category::whereId($category_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }

            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }

}
