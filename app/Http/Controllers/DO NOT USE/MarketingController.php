<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailCampaign;
use App\Models\SocialMediaPost;
use App\Models\MarketingAnalytics;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
  /**
   * Display marketing campaigns.
   *
   * @return \Illuminate\View\View
   */
  public function campaigns()
  {
    $campaigns = Campaign::with(['leads', 'opportunities'])
      ->latest()
      ->paginate(10);

    return view('marketing.campaigns', compact('campaigns'));
  }

  /**
   * Display email marketing.
   *
   * @return \Illuminate\View\View
   */
  public function email()
  {
    $emailCampaigns = EmailCampaign::with(['template', 'segments'])
      ->latest()
      ->paginate(10);

    return view('marketing.email', compact('emailCampaigns'));
  }

  /**
   * Display social media marketing.
   *
   * @return \Illuminate\View\View
   */
  public function social()
  {
    $posts = SocialMediaPost::with(['platform', 'campaign'])
      ->latest()
      ->paginate(10);

    return view('marketing.social', compact('posts'));
  }

  /**
   * Display marketing analytics.
   *
   * @return \Illuminate\View\View
   */
  public function analytics()
  {
    $analytics = MarketingAnalytics::with(['campaign'])
      ->latest()
      ->paginate(10);

    return view('marketing.analytics', compact('analytics'));
  }

  /**
   * Store a new campaign.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeCampaign(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'nullable|date|after:start_date',
      'budget' => 'required|numeric|min:0',
      'target_audience' => 'required|array',
      'goals' => 'required|array',
      'description' => 'nullable|string'
    ]);

    Campaign::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('marketing.campaigns')
      ->with('success', 'Campaign created successfully.');
  }

  /**
   * Store a new email campaign.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeEmailCampaign(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'subject' => 'required|string|max:255',
      'template_id' => 'required|exists:email_templates,id',
      'segment_ids' => 'required|array',
      'segment_ids.*' => 'exists:customer_segments,id',
      'scheduled_at' => 'required|date|after:now',
      'content' => 'required|string'
    ]);

    EmailCampaign::create([
      ...$validated,
      'status' => 'scheduled',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('marketing.email')
      ->with('success', 'Email campaign created successfully.');
  }

  /**
   * Store a new social media post.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeSocialPost(Request $request)
  {
    $validated = $request->validate([
      'platform_id' => 'required|exists:social_media_platforms,id',
      'campaign_id' => 'nullable|exists:campaigns,id',
      'content' => 'required|string',
      'media' => 'nullable|array',
      'scheduled_at' => 'required|date|after:now',
      'target_audience' => 'nullable|array'
    ]);

    SocialMediaPost::create([
      ...$validated,
      'status' => 'scheduled',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('marketing.social')
      ->with('success', 'Social media post created successfully.');
  }
}
