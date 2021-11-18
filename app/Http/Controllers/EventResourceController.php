<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRegistration;
use App\Events\UserEventRegistration as EventManager;
use App\Mail\Events\NewEventUserEmail;
use Illuminate\Support\Facades\Storage;

class EventResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $newUser = null;
        if($request->has('newId')){
            $newUser = EventRegistration::findOrFail($request->get('newId'));
        }

        return view('event-listener.registration', ['user' => $newUser]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required',
            ]
        );


        $model = EventRegistration::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        $file = $request->file('upload');

        if ($file->isFile()) {
            $filename = now()->format('His') . $file->getClientOriginalName();

            $path = $file->storeAs(
                'book',
                $filename,
                'public'
            );

            $model->avatar = $path;
        }

        $model->email = $request->get('email');
        $model->save();

        EventManager::dispatch($model);

        return redirect(route('registration.new', ['newId' => $model->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
