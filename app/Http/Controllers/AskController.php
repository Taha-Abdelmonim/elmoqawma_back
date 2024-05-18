<?php

namespace App\Http\Controllers;

use App\Http\Requests\AskRequest;
use App\Models\Ask;
use App\Models\Course;
use App\Models\Offer;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AskController extends Controller
{


	public function index(Request $request)
	{
		$this->authorize('show_asks');
		$asks = Ask::orderBy('id', 'asc')->get();
//		dd($asks);
		return view('asks.index', compact("asks"));
	}


	public function create()
	{
		$this->authorize('create_asks');
		return view('asks.create');
	}

	public function store(AskRequest $request)
	{
		$this->authorize('create_asks');
		$ask = Ask::create($request->all());
		$description = " تم إنشاء سؤال #" . $ask->id;
		transaction("asks", $description);
		return redirect()->route('asks')->with('success', trans("global.success_create"));;
	}

	public function show($id)
	{
		$this->authorize('show_asks');
	}

	public function edit($id)
	{
		$this->authorize('edit_asks');
		$ask = Ask::find($id);
		return view('asks.edit', compact("ask"));
	}

	public function update($id, AskRequest $request)
	{
		$this->authorize('edit_asks');
		$ask = Ask::find($id);
		$ask->update($request->all());
		$description = " تم تعديل سؤال #" . $ask->id;
		transaction("asks", $description);
		return redirect()->route('asks')->with('success', trans("global.success_create"));
	}

	public function destroy($id)
	{
		$this->authorize('remove_asks');
		$ask = Ask::findorFail($id);
		$description = " تم حزف سؤال #" . $ask->id;
		transaction("asks", $description);
		$ask->delete();
		return redirect()->route('asks')->with('success', trans("global.success_delete"));
	}

	public function destroyAll(Request $request)
	{

		$this->authorize('remove_asks');
		$asks = Ask::whereIn('id', json_decode($request->ids))->get();
		$description = " تم حزف أسئلة (";
		foreach ($asks as $ask) {
			$description .= ", " . $ask->id . " ";
			$ask->delete();
		}
		$description .= " )";
		transaction("asks", $description);
		return redirect()->route("asks")->with('success', trans("global.success_delete_all"));
	}
}
