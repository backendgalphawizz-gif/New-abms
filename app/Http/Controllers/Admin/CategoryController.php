<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = Category::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories = Category::where(['position' => 0]);
        }

        $categories = $categories->orderBy('priority', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.category.view', compact('categories','search'));
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
        }else{
            $categories = Category::where(['position' => 0]);
        }

        $categories = $categories->where(['position' => 0])->orderBy($orderbyName, $orderBy)->limit($request->length)->offset($request->start);;

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
                '<label class="switcher mx-auto">
                    <input type="checkbox" class="switcher_input category-status"
                            id="'.$pId.'" '.$isChecked.'>
                    <span class="switcher_control"></span>
                </label>',
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
            "recordsTotal" => Category::where(['position' => 0])->count(),
            "recordsFiltered" =>  Category::where(['position' => 0])->count(),
            "data" => $dt
        ];

        return response()->json($rows);


    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'priority'=>'required'
        ], [
            'name.required' => 'Category name is required!',
            'image.required' => 'Category image is required!',
            'priority.required' => 'Category priority is required!',
        ]);

        $category = new Category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        $category->icon = ImageManager::upload('category/', 'png', $request->file('image'));
        $category->parent_id = $request->input('parent_id') ?? 0;
        $category->position = 0;
        $category->priority = $request->priority;
        $category->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ));
            }
        }
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success('Category added successfully!');
        return back();
    }

    public function edit(Request $request, $id)
    {
        $category = Category::with('translations')->withoutGlobalScopes()->find($id);
        return view('admin-views.category.category-edit', compact('category'));
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        if ($request->image) {
            $category->icon = ImageManager::update('category/', $category->icon, 'png', $request->file('image'));
        }
        $category->priority = $request->priority;
        $category->parent_id = $request->parent_id ?? 0;
        $category->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success('Category updated successfully!');
        
        // return back();
        if($request->parent_id == 0) {
            return redirect()->route('admin.category.view');
        } else {
            return redirect()->route('admin.sub-category.view');
        }
    }

    public function delete(Request $request)
    {
        $categories = Category::where('parent_id', $request->id)->get();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $categories1 = Category::where('parent_id', $category->id)->get();
                if (!empty($categories1)) {
                    foreach ($categories1 as $category1) {
                        $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$category1->id);
                        $translation->delete();
                        Category::destroy($category1->id);

                    }
                }
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
            $data = Category::where('position', 0)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }

    public function status(Request $request)
    {
        $category = Category::find($request->id);
        $category->home_status = $request->home_status;
        $category->save();
        // Toastr::success('Service status updated!');
        // return back();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
