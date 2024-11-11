<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Models\Discount;
use App\Models\Promotion;
use App\Models\SpecialOffer;
use Illuminate\Http\Request;

class PricingController extends Controller
{
  /**
   * Display price lists.
   *
   * @return \Illuminate\View\View
   */
  public function priceLists()
  {
    $priceLists = PriceList::with(['items'])
      ->latest()
      ->paginate(10);

    return view('pricing.price-lists', compact('priceLists'));
  }

  /**
   * Display discounts.
   *
   * @return \Illuminate\View\View
   */
  public function discounts()
  {
    $discounts = Discount::with(['products', 'customers'])
      ->latest()
      ->paginate(10);

    return view('pricing.discounts', compact('discounts'));
  }

  /**
   * Display promotions.
   *
   * @return \Illuminate\View\View
   */
  public function promotions()
  {
    $promotions = Promotion::with(['products', 'conditions'])
      ->latest()
      ->paginate(10);

    return view('pricing.promotions', compact('promotions'));
  }

  /**
   * Display special offers.
   *
   * @return \Illuminate\View\View
   */
  public function specialOffers()
  {
    $specialOffers = SpecialOffer::with(['products', 'customers'])
      ->latest()
      ->paginate(10);

    return view('pricing.special-offers', compact('specialOffers'));
  }

  /**
   * Store a new price list.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePriceList(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'currency' => 'required|string|size:3',
      'effective_from' => 'required|date',
      'effective_to' => 'nullable|date|after:effective_from',
      'status' => 'required|in:draft,active,inactive',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.price' => 'required|numeric|min:0'
    ]);

    $priceList = PriceList::create([
      'name' => $validated['name'],
      'description' => $validated['description'],
      'currency' => $validated['currency'],
      'effective_from' => $validated['effective_from'],
      'effective_to' => $validated['effective_to'],
      'status' => $validated['status']
    ]);

    foreach ($validated['items'] as $item) {
      $priceList->items()->create($item);
    }

    return redirect()->route('pricing.price-lists')
      ->with('success', 'Price list created successfully.');
  }

  /**
   * Store a new discount.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeDiscount(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|in:percentage,fixed',
      'value' => 'required|numeric|min:0',
      'start_date' => 'required|date',
      'end_date' => 'nullable|date|after:start_date',
      'product_ids' => 'required|array',
      'product_ids.*' => 'exists:products,id',
      'customer_ids' => 'nullable|array',
      'customer_ids.*' => 'exists:customers,id',
      'minimum_quantity' => 'nullable|integer|min:1',
      'minimum_amount' => 'nullable|numeric|min:0',
      'status' => 'required|in:active,inactive'
    ]);

    $discount = Discount::create($validated);
    $discount->products()->attach($validated['product_ids']);

    if (!empty($validated['customer_ids'])) {
      $discount->customers()->attach($validated['customer_ids']);
    }

    return redirect()->route('pricing.discounts')
      ->with('success', 'Discount created successfully.');
  }

  /**
   * Store a new promotion.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePromotion(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|in:bogo,bundle,gift',
      'start_date' => 'required|date',
      'end_date' => 'nullable|date|after:start_date',
      'product_ids' => 'required|array',
      'product_ids.*' => 'exists:products,id',
      'conditions' => 'required|array',
      'reward_type' => 'required|in:product,discount',
      'reward_value' => 'required|string',
      'status' => 'required|in:active,inactive'
    ]);

    $promotion = Promotion::create($validated);
    $promotion->products()->attach($validated['product_ids']);

    foreach ($validated['conditions'] as $condition) {
      $promotion->conditions()->create($condition);
    }

    return redirect()->route('pricing.promotions')
      ->with('success', 'Promotion created successfully.');
  }

  /**
   * Store a new special offer.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeSpecialOffer(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'discount_type' => 'required|in:percentage,fixed',
      'discount_value' => 'required|numeric|min:0',
      'start_date' => 'required|date',
      'end_date' => 'nullable|date|after:start_date',
      'product_ids' => 'required|array',
      'product_ids.*' => 'exists:products,id',
      'customer_ids' => 'nullable|array',
      'customer_ids.*' => 'exists:customers,id',
      'usage_limit' => 'nullable|integer|min:1',
      'minimum_purchase' => 'nullable|numeric|min:0',
      'status' => 'required|in:active,inactive'
    ]);

    $specialOffer = SpecialOffer::create($validated);
    $specialOffer->products()->attach($validated['product_ids']);

    if (!empty($validated['customer_ids'])) {
      $specialOffer->customers()->attach($validated['customer_ids']);
    }

    return redirect()->route('pricing.special-offers')
      ->with('success', 'Special offer created successfully.');
  }
}
