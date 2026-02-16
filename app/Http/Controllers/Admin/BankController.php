<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Bank\BankInterface;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    protected $bank;

    /**
     * TermController constructor.
     * @param BankInterface $bank
     */
    public function __construct(BankInterface $bank)
    {
        $this->bank = $bank;
    }

    /**
     * @return $this
     */
    public function banks()
    {
        $banks = $this->bank->getAll();
        return view('bank.banks', compact('banks'))
            ->with('banks', $banks);
    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_bank($id)
    {
        if (isset($id)) {
            $bank = $this->bank->getById($id);
            $banks = $this->bank->getAll();
            return view('bank.banks')
                ->with('bank', $bank)
                ->with('banks', $banks);
        }

    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function bank_update_save(Request $request, $id)
    {
        //dd($request);
        //$id = $request->get('bank_id');

        $d = $this->bank->getById($id);
        //dd($d);
        // store
        $attributes = [
            'name' => $request->get('bank_name'),
            'emi_message' => $request->get('emi_message'),
            'bank_icon' => $request->get('bank_icon'),
            'is_active' => 1
        ];

        //dd($attributes);

        try {
            $this->bank->update($id, $attributes);
            return redirect('banks')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('banks')->withErrors($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        //dd($request);
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'bank_name' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('banks')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'name' => $request->get('bank_name'),
                'emi_message' => $request->get('emi_message'),
                'bank_icon' => $request->get('bank_icon'),
            ];

            //dd($attributes);
            try {
                $done = $this->bank->create($attributes);
                //dd($done);
                return redirect('banks')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                return redirect('banks')->withErrors($ex->getMessage());
            }
        }

    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        try {
            $this->bank->delete($id);
            return redirect('banks')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('banks')->withErrors($ex->getMessage());
        }
    }
}
