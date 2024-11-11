<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UiController extends Controller
{
  public function buttons()
  {
    return view('ui.buttons');
  }

  public function cards()
  {
    return view('ui.cards');
  }

  public function carousel()
  {
    return view('ui.carousel');
  }

  public function dropdowns()
  {
    return view('ui.dropdowns');
  }

  public function footer()
  {
    return view('ui.footer');
  }

  public function listGroups()
  {
    return view('ui.list-groups');
  }

  public function modals()
  {
    return view('ui.modals');
  }

  public function navbar()
  {
    return view('ui.navbar');
  }

  public function offcanvas()
  {
    return view('ui.offcanvas');
  }

  public function pagination()
  {
    return view('ui.pagination');
  }

  public function progress()
  {
    return view('ui.progress');
  }

  public function spinners()
  {
    return view('ui.spinners');
  }

  public function tabsPills()
  {
    return view('ui.tabs-pills');
  }

  public function toasts()
  {
    return view('ui.toasts');
  }

  public function tooltipsPopovers()
  {
    return view('ui.tooltips-popovers');
  }

  public function typography()
  {
    return view('ui.typography');
  }
}
