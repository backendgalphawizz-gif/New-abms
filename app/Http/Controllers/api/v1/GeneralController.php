<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\HelpTopic;
use App\Model\BusinessSetting;
use App\Model\TrainingAttempt;
use App\Model\TrainingQuestion;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function faq()
    {

        $response = ['status' => true, 'message' => 'Faq Lists', 'data' => HelpTopic::orderBy('ranking')->get()];

        return response()->json($response, 200);
    }

    public function headerColors()
    {
        $settings = BusinessSetting::where('type', 'app_header_colors')->first();

        if ($settings) {
            $data = json_decode($settings->value, true);
        } else {
            $data = [
                'header_color' => '#000000',
                'text_color' => '#FFFFFF',
            ];
        }

        $formatted = [
            'header_color1' => ltrim($data['header_color'], '#'),
            'header_color2'   => ltrim($data['text_color'], '#'),
        ];

        return response()->json([
            'success' => true,
            'data' => $formatted,
        ]);
    }
    public function header_text_update()
    {
        $settings = BusinessSetting::where('type', 'app_marque_text')->first();

       
        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }


  public function saveAttempt(Request $request)
{
   
    $attempt = TrainingAttempt::where('training_id', $request->training_id)
                ->where('user_id', $request->user_id)
                ->first();

    if (!$attempt) {

        $attempt = TrainingAttempt::create([
            'training_id'     => $request->training_id,
            'user_id'         => $request->user_id,
            'total_questions' => $request->total_questions ?? 0,
            'correct_answers' => $request->correct_answers ?? 0,
            'wrong_answers'   => $request->wrong_answers ?? 0,
            'is_completed'    => 0, 
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Training started',
            'data'    => $attempt
        ]);
    }

    if ($attempt->is_completed == 1) {
        return response()->json([
            'status'  => false,
            'message' => 'Training already completed.',
        ], 400);
    }

    $total_questions = $request->total_questions ?? $attempt->total_questions;
    $correct         = $request->correct_answers ?? $attempt->correct_answers;
    $wrong           = $request->wrong_answers ?? $attempt->wrong_answers;

    $attempted = $correct + $wrong;

    $is_completed = $attempted > 0 ? 1 : 0;

    $attempt->update([
        'total_questions' => $total_questions,
        'correct_answers' => $correct,
        'wrong_answers'   => $wrong,
        'is_completed'    => $is_completed,
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Training attempt updated successfully',
        'data'    => $attempt
    ]);
}

// public function updateAttemptAnswers(Request $request)
// {
 
//     $attempt = TrainingAttempt::where('training_id', $request->training_id)
//         ->where('user_id', $request->user_id)
//         ->first();

//     if (!$attempt) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Assessor Attempt not found. Take this service first.'
//         ], 404);
//     }

//     $questions = TrainingQuestion::where('training_id', $request->training_id)->get();
   
//     $correct = 0;
//     $wrong   = 0;

//     foreach ($questions as $q) {

//         if (isset($request->answers[$q->id])) {
            
//             $givenAnswer = strtolower($request->answers[$q->id]);
//             $rightAnswer = strtolower($q->correct_answer);

//             if ($givenAnswer === $rightAnswer) {
//                 $correct++;
//             } else {
//                 $wrong++;
//             }
//         }
//     }

//     $attempt->update([
//         'total_questions' =>($correct + $wrong) ,
//         'correct_answers' => $correct,
//         'wrong_answers'   => $wrong,
//         'is_completed'    => 1,  
//     ]);

//     return response()->json([
//         'status'  => true,
//         'message' => 'Attempt updated successfully',
//         'data'    => $attempt
//     ]);
// }


public function updateAttemptAnswers(Request $request)
{
 
    $attempt = TrainingAttempt::where('training_id', $request->training_id)
        ->where('user_id', $request->user_id)
        ->first();

    if (!$attempt) {
        return response()->json([
            'status'  => false,
            'message' => 'Attempt not found. Please take the service first.'
        ], 404);
    }
    if ($attempt->is_completed == 1) {
        return response()->json([
            'status'  => false,
            'message' => 'Training already completed.'
        ], 403);
    }

 
    $questions = TrainingQuestion::where('training_id', $request->training_id)->get();

    if ($questions->count() == 0) {
        return response()->json([
            'status'  => false,
            'message' => 'No questions found for this training.'
        ], 400);
    }

    $correct = 0;
    $wrong = 0;
    $matched = 0; 

    foreach ($questions as $q) {

        if (isset($request->answers[$q->id])) {

            $matched++; 

            $givenAnswer = strtolower($request->answers[$q->id]);
            $rightAnswer = strtolower($q->correct_answer);

            if ($givenAnswer === $rightAnswer) {
                $correct++;
            } else {
                $wrong++;
            }
        }
    }

    if ($matched == 0) {
        return response()->json([
            'status'  => false,
            'message' => 'No valid answers provided for the available questions.'
        ], 400);
    }

    $is_completed = 1;

   
    $attempt->update([
        'total_questions' => ($correct + $wrong), 
        'correct_answers' => $correct,
        'wrong_answers'   => $wrong,
        'is_completed'    => $is_completed,
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Attempt updated successfully',
        'data'    => $attempt
    ]);
}

public function assessorTrainingAttempt(Request $request)
{
   
    if (!$request->training_id || !$request->user_id) {
        return response()->json([
            'status' => false,
            'message' => 'training_id and user_id are required'
        ], 422);
    }

   
    $questions = TrainingQuestion::where('training_id', $request->training_id)->get();
    $totalQuestionsFromService = $questions->count();

    $attempt = TrainingAttempt::where('training_id', $request->training_id)
        ->where('user_id', $request->user_id)
        ->first();

    if (!$attempt) {

        if ($request->has('answers')) 
        {
            if ($totalQuestionsFromService == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No questions available for this training.'
                ], 400);
            }

            $correct = 0;
            $wrong   = 0;

            foreach ($questions as $q) {
                if (isset($request->answers[$q->id])) {
                    $given  = strtolower($request->answers[$q->id]);
                    $right  = strtolower($q->correct_answer);

                    $given === $right ? $correct++ : $wrong++;
                }
            }

            if (($correct + $wrong) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid answers submitted. Cannot create attempt.'
                ], 400);
            }

            $attempt = TrainingAttempt::create([
                'training_id'     => $request->training_id,
                'user_id'         => $request->user_id,
                'total_questions' => $correct + $wrong,
                'correct_answers' => $correct,
                'wrong_answers'   => $wrong,
                'is_completed'    => 1,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Training attempt saved successfully',
                'data'    => $attempt
            ], 200);
        }

        $attempt = TrainingAttempt::create([
            'training_id'     => $request->training_id,
            'user_id'         => $request->user_id,
            'total_questions' => 0,
            'correct_answers' => 0,
            'wrong_answers'   => 0,
            'is_completed'    => 0,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Training started',
            'data'    => $attempt
        ], 200);
    }

    if ($attempt->is_completed == 1) {
        return response()->json([
            'status' => false,
            'message' => 'Training already completed.'
        ], 400);
    }

    if ($request->has('answers')) {

        if ($totalQuestionsFromService == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No questions available to evaluate.'
            ]);
        }

        $correct = 0;
        $wrong   = 0;

        foreach ($questions as $q) {
            if (isset($request->answers[$q->id])) {

                $given = strtolower($request->answers[$q->id]);
                $right = strtolower($q->correct_answer);

                ($given === $right) ? $correct++ : $wrong++;
            }
        }

        if (($correct + $wrong) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No valid answers submitted.'
            ], 400);
        }

        $attempt->update([
            'total_questions' => $correct + $wrong,
            'correct_answers' => $correct,
            'wrong_answers'   => $wrong,
            'is_completed'    => 1,
        ]);

        return response()->json([
            'status'  => true,
            'message' => ' Assessor Attempt Submit successfully',
            'data'    => $attempt
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'No answers provided.'
    ], 400);
}




}
