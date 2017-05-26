<?php

namespace App\Http\Controllers;

use App\Models\Cdr;
use Illuminate\Http\Request;

class CdrsController extends Controller
{
    /**
     *  Show the AutoDialer CDR's for this User
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cdrs = \Auth::user()->cdrs()->orderBy('created_at', 'desc')->paginate(15);
        return view('autodialer.cdrs', compact('cdrs'));
    }

    /**
     *  Delete a CDR
     *
     * @param Cdr $cdr
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cdr $cdr)
    {
        $cdr->delete();
        return redirect()->back()->with('info', 'CDR deleted!');
    }
}
