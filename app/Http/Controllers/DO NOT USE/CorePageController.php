<?php

namespace App\Http\Controllers;

use App\Models\CorePageModal;

class CorePageController extends Controller
{
  public function index()
  {
    // Implement Dutch sentences here
    $dutchSentence = "Dit is de kern van de toepassing.";
    $corePageModal = new CorePageModal();
    $corePageModal->dutch_sentence = $dutchSentence;
    $corePageModal->save();

    return view('core-page.index', [
      'corePageModal' => $corePageModal
    ]);
  }
}
