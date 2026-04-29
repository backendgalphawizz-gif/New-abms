<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Scheme;
use App\Model\SchemeArea;
use App\Model\Scope;
use App\Model\Training;
use App\Model\TrainingFile;
use App\Model\FeeStructure;
use App\Model\TrainingQuestion;
use App\Model\TrainingAttempt;
use App\Model\Media;
use App\Model\Assessor;
use App\CPU\ImageManager;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use DB;

class TrainingController extends Controller
{

public function add()
{
    $schemes = Scheme::all();

    $assessors = Assessor::with('admin:id,name,email')->get();

    return view('admin-views.training.add', compact('schemes', 'assessors'));
}



   public function list(Request $request)
{
    $search = $request->search;

    $trainings = Training::with([
            'scheme:id,title',
            'area:id,title',
            'scopeData:id,title',
            'files.media:id,file,type'  
        ])
        ->when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%");
        })
        ->orderBy('id', 'DESC')
        ->paginate(10);

    return view('admin-views.training.list', compact('trainings', 'search'));
}

    public function getAreas(Request $request)
    {
        return SchemeArea::where('scheme_id', $request->scheme_id)
            ->get();
    }

    public function getScopes(Request $request)
    {
        return Scope::where('area_id', $request->area_id)
            ->get();
    }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        // 'scheme_id'        => 'required|integer',
        // 'area_id'          => 'required|integer',
        'title'            => 'required|string',
        'description'      => 'required|string',
        'image'            => 'nullable|file|mimes:jpg,jpeg,png',
        'section_type'     => 'required|array',
        'section_type.*'   => 'required|in:video,pdf',
        'section_file'     => 'required|array',
        'section_file.*'   => 'required|file'
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }
   
        $imagePath = null;

        if ($request->hasFile('image')) 
        {
            $img = $request->file('image');
            $ext = $img->getClientOriginalExtension();

            $uploadedPath = ImageManager::uploadMedia('media/', $ext, $img);

            $media = new Media();
            $media->type = $ext;
            $media->file = $uploadedPath;
            $media->save();

            $imagePath = $uploadedPath;
        }

        $training = new Training();
        $training->scheme_id   = $request->scheme_id;
        $training->area_id     = $request->area_id;
        $training->scope_id    = $request->scope_id;
        $training->title       = $request->title;
        $training->description = $request->description;
        $training->image       = $imagePath;
        $training->status      = 1;
        $training->auth_id     = auth('admin')->id(); 
        $training->type = $request->training_for; 

        if ($request->training_for === "specific") {
            $training->assessor_ids = implode(',', $request->assessor_ids ?? []);
        } else {
            $training->assessor_ids = null;
        }

        $training->save();
        $sectionTypes = $request->section_type;
        $files        = $request->file('section_file');

        foreach ($files as $i => $file) 
        {
            if (!$file) continue;

            $fileType = $sectionTypes[$i];
            $ext      = $file->getClientOriginalExtension();
            $mime     = $file->getMimeType();
            if ($fileType === "video" && strpos($mime, 'video/') !== 0) {
             
                return back()->withErrors(['error' => "Row ".($i + 1)." must be a VIDEO file."]);
            }

            if ($fileType === "pdf" && $mime !== "application/pdf") {
                return back()->withErrors(['error' => "Row ".($i + 1)." must be a PDF file."]);
            }

            $filePath = ImageManager::uploadMedia('media/', $ext, $file);

            $media = new Media();
            $media->type = $ext;
            $media->file = $filePath;
            $media->save();
            $trainingFile           = new TrainingFile();
            $trainingFile->training_id = $training->id;
            $trainingFile->file_type   = $fileType;
            $trainingFile->media_id    = $media->id;
            $trainingFile->file_path   = $filePath;
            $trainingFile->status      = 1;
            $trainingFile->save();
        }


        Toastr::success("Training Added Successfully");
        return redirect()->route('admin.training.list-training');

}

public function edit($id)
{
    $training = Training::with(['files'])->find($id);

    if (!$training) {
        Toastr::error('Training not found');
        return back();
    }

    $schemes = Scheme::get();
    $areas   = SchemeArea::where('scheme_id', $training->scheme_id)->get();
    $scopes  = Scope::where('area_id', $training->area_id)->get();

   
    $assessors = Assessor::with('admin')->get();

  
    $selectedAssessors = $training->assessor_ids
        ? explode(',', $training->assessor_ids)
        : [];

    return view('admin-views.training.edit', compact(
        'training',
        'schemes',
        'areas',
        'scopes',
        'assessors',
        'selectedAssessors'
    ));
}


//  public function update(Request $request, $id)
//     {
//         $validator = Validator::make($request->all(), [
//             'scheme_id'      => 'required|integer',
//             'area_id'        => 'required|integer',
//             'scope_id'       => 'nullable|integer',
//             'title'          => 'required|string|max:255',
//             'description'    => 'required|string',
//             'image'          => 'nullable|file|mimes:jpg,jpeg,png',
//         ]);

//         if ($validator->fails()) {
//             return back()->withErrors($validator)->withInput();
//         }

//         $training = Training::findOrFail($id);

       
           
//             $training->scheme_id   = $request->scheme_id;
//             $training->area_id     = $request->area_id;
//             $training->scope_id    = $request->scope_id;
//             $training->title       = $request->title;
//             $training->description = $request->description;

            
//             if ($request->hasFile('image')) {
//                 $img = $request->file('image');
//                 $ext = $img->getClientOriginalExtension();
//                 $uploadedPath = ImageManager::uploadMedia('media/', $ext, $img);

                
//                 $m = new Media();
//                 $m->type = $ext;
//                 $m->file = $uploadedPath;
//                 $m->save();

//                 $training->image = $uploadedPath; 
//             }
//             $training->save();

          
//             $toDelete = $request->input('delete_file_ids', []);
//             if (!empty($toDelete)) {
//                 TrainingFile::where('training_id', $training->id)
//                     ->whereIn('id', $toDelete)
//                     ->delete();
//             }

//             $types       = $request->input('section_type', []);
//             $existingIds = $request->input('existing_file_id', []);
//             $files       = $request->file('section_file', []);

//             $max = max(count($types), count($existingIds), count($files ?? []));
//             for ($i = 0; $i < $max; $i++) {
//                 $type     = $types[$i]           ?? null;        
//                 $existId  = $existingIds[$i]     ?? null;            
//                 $newFile  = is_array($files) ? ($files[$i] ?? null) : null;

             
//                 if ($existId && in_array($existId, $toDelete)) {
//                     continue;
//                 }

               
//                 if (empty($type) && empty($newFile) && empty($existId)) {
//                     continue;
//                 }

               
//                 if ($newFile) {
//                     $mime = $newFile->getMimeType();
//                     if ($type === 'video' && strpos($mime, 'video/') !== 0) {
//                         DB::rollBack();
//                         return back()->withErrors(['error' => "Row ".($i+1)." must be a VIDEO file."]);
//                     }
//                     if ($type === 'pdf' && $mime !== 'application/pdf') {
//                         DB::rollBack();
//                         return back()->withErrors(['error' => "Row ".($i+1)." must be a PDF file."]);
//                     }
//                 }

//                 if ($existId) {
                   
//                     $tf = TrainingFile::where('training_id', $training->id)->find($existId);
//                     if (!$tf) {
//                         continue; 
//                     }
//                     $tf->file_type = $type ?: $tf->file_type;

//                     if ($newFile) {
//                         $ext   = $newFile->getClientOriginalExtension();
//                         $path  = ImageManager::uploadMedia('media/', $ext, $newFile);

//                         $media = new Media();
//                         $media->type = $ext;
//                         $media->file = $path;
//                         $media->save();

//                         $tf->media_id  = $media->id;
//                         $tf->file_path = $path;
//                     }
//                     $tf->save();

//                 } else {
                    
//                     if ($newFile && $type) {
//                         $ext   = $newFile->getClientOriginalExtension();
//                         $path  = ImageManager::uploadMedia('media/', $ext, $newFile);

//                         $media = new Media();
//                         $media->type = $ext;
//                         $media->file = $path;
//                         $media->save();

//                         $tf = new TrainingFile();
//                         $tf->training_id = $training->id;
//                         $tf->file_type   = $type;
//                         $tf->media_id    = $media->id;
//                         $tf->file_path   = $path;
//                         $tf->status      = 1;
//                         $tf->save();
//                     }
//                 }
//             }

            
//             Toastr::success('Training updated successfully.');
//             return redirect()->route('admin.training.list-training');

//     } 


public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        // 'scheme_id'      => 'required|integer',
        // 'area_id'        => 'required|integer',
        // 'scope_id'       => 'nullable|integer',
        'title'          => 'required|string|max:255',
        'description'    => 'required|string',
        'image'          => 'nullable|file|mimes:jpg,jpeg,png',
       
        'assessor_ids'   => 'nullable|array',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $training = Training::findOrFail($id);

    
    $training->scheme_id   = $request->scheme_id;
    $training->area_id     = $request->area_id;
    $training->scope_id    = $request->scope_id;
    $training->title       = $request->title;
    $training->description = $request->description;

 
    if ($request->hasFile('image')) {
        $img = $request->file('image');
        $ext = $img->getClientOriginalExtension();
        $uploadedPath = ImageManager::uploadMedia('media/', $ext, $img);

        $m = new Media();
        $m->type = $ext;
        $m->file = $uploadedPath;
        $m->save();

        $training->image = $uploadedPath;
    }

    
    $training->type = $request->type;

    if ($request->type === "specific") {
        $training->assessor_ids = implode(",", $request->assessor_ids ?? []);
    } else {
        $training->assessor_ids = null;
    }

    $training->save();


    $toDelete = $request->input('delete_file_ids', []);
    if (!empty($toDelete)) {
        TrainingFile::where('training_id', $training->id)
            ->whereIn('id', $toDelete)
            ->delete();
    }

   
    $types       = $request->input('section_type', []);
    $existingIds = $request->input('existing_file_id', []);
    $files       = $request->file('section_file', []);

    $max = max(count($types), count($existingIds), count($files ?? []));

    for ($i = 0; $i < $max; $i++) {

        $type     = $types[$i] ?? null;
        $existId  = $existingIds[$i] ?? null;
        $newFile  = is_array($files) ? ($files[$i] ?? null) : null;


        if ($existId && in_array($existId, $toDelete)) {
            continue;
        }

        if ($newFile) {
            $mime = $newFile->getMimeType();

            if ($type === 'video' && strpos($mime, 'video/') !== 0) {
                return back()->withErrors(['error' => "Row ".($i+1)." must be a VIDEO file."]);
            }
            if ($type === 'pdf' && $mime !== 'application/pdf') {
                return back()->withErrors(['error' => "Row ".($i+1)." must be a PDF file."]);
            }
        }

  
        if ($existId) {
            $tf = TrainingFile::where('training_id', $training->id)->find($existId);
            if (!$tf) continue;

            $tf->file_type = $type ?: $tf->file_type;

            if ($newFile) {
                $ext  = $newFile->getClientOriginalExtension();
                $path = ImageManager::uploadMedia('media/', $ext, $newFile);

                $media = new Media();
                $media->type = $ext;
                $media->file = $path;
                $media->save();

                $tf->media_id  = $media->id;
                $tf->file_path = $path;
            }

            $tf->save();

        } else {
        
            if ($newFile && $type) {
                $ext  = $newFile->getClientOriginalExtension();
                $path = ImageManager::uploadMedia('media/', $ext, $newFile);

                $media = new Media();
                $media->type = $ext;
                $media->file = $path;
                $media->save();

                $tf = new TrainingFile();
                $tf->training_id = $training->id;
                $tf->file_type   = $type;
                $tf->media_id    = $media->id;
                $tf->file_path   = $path;
                $tf->status      = 1;
                $tf->save();
            }
        }
    }

    Toastr::success('Training updated successfully.');
    return redirect()->route('admin.training.list-training');
}


public function updateStatus(Request $request)
{
    $training = Training::find($request->id);

    if (!$training) {
        return response()->json(['error' => 'Training not found'], 404);
    }

    $training->status = $request->status;
    $training->save();

    return response()->json(['success' => true]);
}

// public function view($id, $tab = 'details')
// {
//     $training = Training::with(['scheme', 'area', 'scopeData', 'files'])->findOrFail($id);

//     $questions = TrainingQuestion::where('training_id', $id)->get();

//     $attempts = TrainingAttempt::with('admin')
//         ->where('training_id', $id)
//         ->paginate(4);

//     // validate allowed tabs
//     $allowedTabs = ['details', 'questions', 'users-training'];
//     if (!in_array($tab, $allowedTabs)) {
//         $tab = 'details';
//     }

//     return view('admin-views.training.view', compact('training', 'questions', 'attempts', 'tab'));
// }


 public function view($id, Request $request, $tab = 'details')
{
   
    $search = $request->search ?? null;

    
    $training = Training::with(['scheme', 'area', 'scopeData', 'files'])
        ->findOrFail($id);

  
    $questions = TrainingQuestion::where('training_id', $id)
        ->when($search, function($q) use ($search) {
            $q->where('question', 'LIKE', "%{$search}%")
              ->orWhere('option_a', 'LIKE', "%{$search}%")
              ->orWhere('option_b', 'LIKE', "%{$search}%")
              ->orWhere('option_c', 'LIKE', "%{$search}%")
              ->orWhere('option_d', 'LIKE', "%{$search}%");
        })
        ->orderBy('id', 'DESC')
        ->paginate(10, ['*'], 'questions_page');

   
    $attempts = TrainingAttempt::with('admin')
        ->where('training_id', $id)
        ->when($search, function ($q) use ($search) {
            $q->whereHas('admin', function ($adminQ) use ($search) {
                $adminQ->where('name', 'like', "%$search%")
                       ->orWhere('email', 'like', "%$search%");
            });
        })
        ->orderBy('id', 'DESC')
        ->paginate(10, ['*'], 'attempts_page');

    $totalQuestions = TrainingQuestion::where('training_id', $id)->count();

    $allowedTabs = ['details', 'questions', 'users-training'];
    if (!in_array($tab, $allowedTabs)) {
        $tab = 'details';
    }

    return view('admin-views.training.view', compact(
        'training',
        'questions',
        'attempts',
        'totalQuestions',
        'tab',
        'search'   
    ));
}





public function questions($training_id)
{
    $training = Training::findOrFail($training_id);

    $questions = TrainingQuestion::where('training_id', $training_id)
        ->orderBy('id', 'DESC')
        ->get();

    return view('admin-views.training.questions', compact('training', 'questions'));
}

public function questionsStore(Request $request)
{
    $request->validate([
        'training_id'     => 'required|integer',
        'question'        => 'required|string',
        'option_a'        => 'required|string',
        'option_b'        => 'required|string',
        'option_c'        => 'required|string',
        'option_d'        => 'required|string',
        'correct_answer'  => 'required',
    ]);

    $question = new TrainingQuestion(); 

    $question->training_id    = $request->training_id;
    $question->question       = $request->question;
    $question->option_a       = $request->option_a;
    $question->option_b       = $request->option_b;
    $question->option_c       = $request->option_c;
    $question->option_d       = $request->option_d;
    $question->correct_answer = $request->correct_answer;

    $question->save(); 

    Toastr::success('Question Added Successfully.');

    return redirect()->back();
}





public function questionsEdit($id)
{
    $question = TrainingQuestion::findOrFail($id);
    $training = Training::findOrFail($question->training_id);

    return view('admin-views.training.questions-edit', compact('question', 'training'));
}

public function questionsUpdate(Request $request, $id)
{
    $request->validate([
        'question'        => 'required|string',
        'option_a'        => 'required|string',
        'option_b'        => 'required|string',
        'option_c'        => 'required|string',
        'option_d'        => 'required|string',
        'correct_answer'  => 'required',
    ]);

    $question = TrainingQuestion::findOrFail($id);

   
    $question->question       = $request->question;
    $question->option_a       = $request->option_a;
    $question->option_b       = $request->option_b;
    $question->option_c       = $request->option_c;
    $question->option_d       = $request->option_d;
    $question->correct_answer = $request->correct_answer;
    $question->save(); 

    Toastr::success('Question Updated Successfully.');

    return redirect()
        ->route('admin.training.training-view', [$question->training_id, 'questions'])
        ->with('success', 'Question updated successfully!');
}



public function questionsDelete(Request $request)
{
    $question = TrainingQuestion::find($request->id);

    if (!$question) {
        return response()->json([
            'status' => false,
            'message' => 'Question not found'
        ]);
    }

    $question->delete();

    return response()->json([
        'status' => true,
        'message' => 'Deleted successfully'
    ]);
}





public function delete(Request $request)
{
    $training = Training::with('files')->find($request->id);

    if (!$training) {
        return response()->json(['error' => 'Training not found'], 404);
    }
    foreach ($training->files as $file) {
        $file->delete();
    }
    $training->delete();

    return response()->json(['success' => true]);
}

public function feeList()
{
    $fees = FeeStructure::orderBy('id','ASC')->get();

    $schemes = Scheme::select('id','title')->get()->keyBy('id');


    foreach ($fees as $fee) {
        $fee->scheme = $schemes[$fee->scheme_id] ?? null;
    }

    return view('admin-views.training.fee.list', compact('fees'));
}



public function feeUpdate(Request $request, $id)
{
    $fee = FeeStructure::findOrFail($id);

    
    $fee->application_fee              = $request->application_fee;
    $fee->application_additional_fee   = $request->application_additional_fee;
    $fee->document_fee                 = $request->document_fee;
    $fee->document_additional_fee      = $request->document_additional_fee;
    $fee->assessment_fee               = $request->assessment_fee;
    $fee->assessment_additional_fee    = $request->assessment_additional_fee;
    $fee->assessment_mandays           = $request->assessment_mandays;
    $fee->technical_assessment_fee     = $request->technical_assessment_fee;
    $fee->technical_mandays            = $request->technical_mandays;

    
    $fee->save();

    Toastr::success('Fee Structure Updated Successfully');
    return redirect()->route('admin.training.fee-list');
}




}
