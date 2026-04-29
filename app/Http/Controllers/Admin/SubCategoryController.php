<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index( Request $request )
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = Category::where(['position'=>1])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories=Category::where(['position'=>1]);
        }
        $categories = $categories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.sub-category-view',compact('categories','search'));
    }

    public function paginate(Request $request) {
        $orderbyName = isset($request->columns[$request->order[0]['column']]['name']) ? $request->columns[$request->order[0]['column']]['name'] : 'priority';
        $orderBy = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : 'ASC';

        $search = $request['search']['value'];

        if($request->has('search'))
        {
            $key = $request['search'];
            $categories = Category::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        }

        $categories = $categories->where(['position' => 1])->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);;

        $pro = $categories->get();
        $dt = [];

        $errorImage = asset('public/assets/front-end/img/image-place-holder.png');

        foreach($pro as $k=>$p):

            $image = $p['icon'] != "def.png" ? asset('storage/app/public/category/' . $p['icon']) : $errorImage;
            $pId = $p['id'];
            $isChecked = $p->home_status == 1?'checked':'';
            $url = route('admin.category.edit',[$pId]);
            $dt[] = [
                ($k+1),
                '<img class="rounded" width="64" onerror="this.src='.$errorImage.'" src="'.$image.'">',
                $p['defaultname'],
                $p['priority'],
                '<div class="d-flex justify-content-center gap-10">
                    <a class="btn btn-outline-info btn-sm square-btn"
                        title="'.translate('Edit').'"
                        href="'.$url.'">
                        <i class="tio-edit"></i>
                    </a>
                    <a class="btn btn-outline-danger btn-sm delete square-btn"
                        title="'.translate('Delete').'"
                        id="'.$pId.'">
                        <i class="tio-delete"></i>
                    </a>
                </div>',
            ];
        endforeach;

        $rows = [
            "draw" => (int)$request->input('draw'),
            "recordsTotal" => Category::where(['position' => 1])->count(),
            "recordsFiltered" =>  Category::where(['position' => 1])->count(),
            "data" => $dt
        ];

        return response()->json($rows);


    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'priority'=>'required',
            'parent_id'=>'required'
        ], [
            'name.required' => 'Category name is required!',
            'priority.required' => 'Category priority is required!',
            'parent_id.required' => 'Main Category is required!',
        ]);
        $category = new Category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        if ($request->image){
            $category->icon = ImageManager::upload('category/', 'png', $request->file('image'));
        }
        $category->parent_id = $request->parent_id;
        $category->position = 1;
        $category->priority = $request->priority;
        $category->save();

        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Model\Category',
                        'translationable_id'    => $category->id,
                        'locale'                => $key,
                        'key'                   => 'name'],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        Toastr::success('Category updated successfully!');
        return back();
    }

    public function edit(Request $request)
    {
        $data = Category::where('id', $request->id)->first();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->image) {
            $category->icon = ImageManager::update('category/', $category->icon, 'png', $request->file('image'));
        }
        $category->parent_id = $request->parent_id;
        $category->position = 1;
        $category->priority = $request->priority;
        $category->save();
        return response()->json();
    }

    public function delete(Request $request)
    {
        $categories = Category::where('parent_id', $request->id)->get();
        if (!empty($categories)) {

            foreach ($categories as $category) {
                $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$category->id);
                $translation->delete();
                Category::destroy($category->id);
            }
        }
        $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        Category::destroy($request->id);
        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('position', 1)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }
}
