@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                            class="ri-group-line me-1_5"></i>Account</a></li>
                <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i
                            class="ri-notification-4-line me-1_5"></i>Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i
                            class="ri-link-m me-1_5"></i>Connections</a></li>
            </ul>
        </div>
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-6">
                    <img src="{{asset('assets/img/avatars/1.png')}}" alt="user-avatar"
                        class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-sm btn-primary me-3 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="ri-upload-2-line d-block d-sm-none"></i>
                            <input type="file" id="upload" class="account-file-input" hidden
                                accept="image/png, image/jpeg" />
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-danger account-image-reset mb-4">
                            <i class="ri-refresh-line d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>

                        <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="formAccountSettings" action="{{ route('account-settings') }}" method="POST">
                    @csrf
                    <div class="row mt-1 g-5">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="firstName" name="first_name"
                                    value="{{ old('first_name', $authUser->first_name) }}" autofocus />
                                <label for="firstName">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="lastName" name="last_name"
                                    value="{{ old('last_name', $authUser->last_name) }}" />
                                <label for="lastName">Last Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ old('email', $authUser->email) }}" placeholder="example@example.com" />
                                <label for="email">E-mail</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="organization" name="organization"
                                    value="{{ old('organization', $authUser->organization) }}" />
                                <label for="organization">Organization</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="phoneNumber" name="phone_number"
                                        value="{{ old('phone_number', $authUser->phone_number) }}" class="form-control"
                                        placeholder="202 555 0111" />
                                    <label for="phoneNumber">Phone Number</label>
                                </div>
                                <span class="input-group-text">US (+1)</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ old('address', $authUser->address) }}" placeholder="Address" />
                                <label for="address">Address</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="state" name="state"
                                    value="{{ old('state', $authUser->state) }}" placeholder="California" />
                                <label for="state">State</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="zipCode" name="zip_code"
                                    value="{{ old('zip_code', $authUser->zip_code) }}" placeholder="231465"
                                    maxlength="6" />
                                <label for="zipCode">Zip Code</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="country" name="country" class="select2 form-select">
                                    <option value="">Select</option>
                                    <option value="Australia"
                                        {{ old('country', $authUser->country) == 'Australia' ? 'selected' : '' }}>
                                        Australia</option>
                                    <option value="Bangladesh"
                                        {{ old('country', $authUser->country) == 'Bangladesh' ? 'selected' : '' }}>
                                        Bangladesh</option>
                                    <!-- Add other country options with similar logic -->
                                </select>
                                <label for="country">Country</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="language" name="language" class="select2 form-select">
                                    <option value="">Select Language</option>
                                    <option value="en"
                                        {{ old('language', $authUser->language) == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="fr"
                                        {{ old('language', $authUser->language) == 'fr' ? 'selected' : '' }}>French
                                    </option>
                                    <option value="de"
                                        {{ old('language', $authUser->language) == 'de' ? 'selected' : '' }}>German
                                    </option>
                                    <option value="pt"
                                        {{ old('language', $authUser->language) == 'pt' ? 'selected' : '' }}>Portuguese
                                    </option>
                                </select>
                                <label for="language">Language</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="timeZones" name="time_zones" class="select2 form-select">
                                    <option value="">Select Timezone</option>
                                    <option value="-12"
                                        {{ old('time_zones', $authUser->time_zones) == '-12' ? 'selected' : '' }}>
                                        (GMT-12:00) International Date Line West</option>
                                    <!-- Add other timezone options with similar logic -->
                                </select>
                                <label for="timeZones">Timezone</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="currency" name="currency" class="select2 form-select">
                                    <option value="">Select Currency</option>
                                    <option value="usd"
                                        {{ old('currency', $authUser->currency) == 'usd' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="euro"
                                        {{ old('currency', $authUser->currency) == 'euro' ? 'selected' : '' }}>Euro
                                    </option>
                                    <option value="pound"
                                        {{ old('currency', $authUser->currency) == 'pound' ? 'selected' : '' }}>Pound
                                    </option>
                                    <option value="bitcoin"
                                        {{ old('currency', $authUser->currency) == 'bitcoin' ? 'selected' : '' }}>
                                        Bitcoin</option>
                                </select>
                                <label for="currency">Currency</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </form>

            </div>
            <!-- /Account -->
        </div>
        <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                <form id="formAccountDeactivation" onsubmit="return false">
                    <div class="form-check mb-6 ms-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation"
                            id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">I confirm my account
                            deactivation</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account" disabled="disabled">Deactivate
                        Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection