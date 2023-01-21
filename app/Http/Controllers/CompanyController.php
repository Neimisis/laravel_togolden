<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
    
        $data['companies'] = Company::orderBy('created_at', 'desc')->get();

        return view('pages.company.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'inn'  => 'required|string|size:10',
            'information' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ];

        $messages = [
            'inn.size' => __('Поле ИНН должно состоять из :size цифр'),
            'required' => __('Данное поле обязательно для заполнения'),
            'max' => __('Длина данного поля не должна превышать :max символов'),
        ];

        $validateData = $request->validate($rules, $messages);

        $company = new Company($validateData);
        $company->save();
        
        echo $company;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
    
        $company = Company::find($id);


        $data['company'] = [
            'id' => $company->id,
            'fields' => [
                'name' => ['title' => __('Название'), 'value' => $company->name, 'comments' => $this->getComments('name', $company->id)],
                'inn' => ['title' => __('ИНН'), 'value' => $company->inn, 'comments' => $this->getComments('inn', $company->id)],
                'information' => ['title' => __('Общая информация'), 'value' => $company->information, 'comments' => $this->getComments('information', $company->id)],
                'director' => ['title' => __('Генеральный директор'), 'value' => $company->director, 'comments' => $this->getComments('director', $company->id)],
                'address' => ['title' => __('Адрес'), 'value' => $company->address, 'comments' => $this->getComments('address', $company->id)],
                'phone' => ['title' => __('Телефон'), 'value' => $company->phone, 'comments' => $this->getComments('phone', $company->id)],
            ],
        ];

        $data['comments'] = $this->getComments('general', $company->id);


        $data['text_comment'] = __('Прокомментировать');
        $data['text_comment_company'] = __('Прокомментировать компанию');

        return view('pages.company.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $field
     * @param  int  $id
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function getComments($field, $id) {
        return DB::table('comments')
                ->select('comments.*', 'users.name')
                ->join('users','comments.user_id','=','users.id')
                ->where(['comments.company_id' => $id, 'comments.field' => $field])
                ->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        Comment::where('company_id', '=', (int)$id)->delete();
        
        echo response('success');
    }

}
