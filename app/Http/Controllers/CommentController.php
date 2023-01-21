<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'text' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'company_id' => 'required|string|max:255',
        ];

        $messages = [
            'required' => __('Данное поле обязательно для заполнения'),
            'max' => __('Длина данного поля не должна превышать :max символов'),
        ];

        $validateData = $request->validate($rules, $messages);

        $validateData['user_id'] = $request->user()->id;

        $company = new Comment($validateData);
        $company->save();

        echo $company->toJson();

    }

}
