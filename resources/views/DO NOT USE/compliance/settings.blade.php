@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Settings')

@section('content')
    <h4 class="fw-bold">Compliance Settings</h4>

    <div class="row">
        <!-- Notification Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notification Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label d-block">Email Notifications</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="requirementNotifications" checked>
                                <label class="form-check-label" for="requirementNotifications">
                                    Requirement Updates
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="auditNotifications" checked>
                                <label class="form-check-label" for="auditNotifications">
                                    Audit Schedules and Results
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="deadlineNotifications" checked>
                                <label class="form-check-label" for="deadlineNotifications">
                                    Upcoming Deadlines
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="complianceScoreNotifications">
                                <label class="form-check-label" for="complianceScoreNotifications">
                                    Compliance Score Changes
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notification Frequency</label>
                            <select class="form-select">
                                <option value="immediately">Immediately</option>
                                <option value="daily">Daily Digest</option>
                                <option value="weekly">Weekly Summary</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Compliance Score Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Compliance Score Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Score Calculation Method</label>
                            <select class="form-select mb-2">
                                <option value="weighted">Weighted Average</option>
                                <option value="simple">Simple Average</option>
                                <option value="custom">Custom Formula</option>
                            </select>
                            <small class="text-muted">Choose how the overall compliance score is calculated</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Component Weights</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Requirements</span>
                                <input type="number" class="form-control" value="40" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Audits</span>
                                <input type="number" class="form-control" value="40" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Documentation</span>
                                <input type="number" class="form-control" value="20" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Score Thresholds</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">High Risk</span>
                                <input type="number" class="form-control" value="60" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Medium Risk</span>
                                <input type="number" class="form-control" value="80" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Score Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Audit Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Audit Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Default Audit Frequency</label>
                            <select class="form-select">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="biannual">Bi-Annual</option>
                                <option value="annual">Annual</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Audit Template</label>
                            <select class="form-select">
                                <option value="standard">Standard Template</option>
                                <option value="detailed">Detailed Template</option>
                                <option value="custom">Custom Template</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Audit Requirements</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="documentationRequired" checked>
                                <label class="form-check-label" for="documentationRequired">
                                    Documentation Required
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="evidenceRequired" checked>
                                <label class="form-check-label" for="evidenceRequired">
                                    Evidence Collection Required
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="approvalRequired" checked>
                                <label class="form-check-label" for="approvalRequired">
                                    Management Approval Required
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Audit Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Requirement Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Requirement Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Default Priority Levels</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">High</span>
                                <input type="color" class="form-control form-control-color" value="#dc3545">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Medium</span>
                                <input type="color" class="form-control form-control-color" value="#ffc107">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Low</span>
                                <input type="color" class="form-control form-control-color" value="#0dcaf0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Requirement Categories</label>
                            <select class="form-select" multiple size="4">
                                <option selected>Legal Requirements</option>
                                <option selected>Industry Standards</option>
                                <option selected>Internal Policies</option>
                                <option selected>Security Requirements</option>
                                <option>Environmental Requirements</option>
                                <option>Quality Standards</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Requirement Options</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="autoAssign" checked>
                                <label class="form-check-label" for="autoAssign">
                                    Auto-assign to Department Heads
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="requireEvidence" checked>
                                <label class="form-check-label" for="requireEvidence">
                                    Require Evidence for Completion
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enableReminders" checked>
                                <label class="form-check-label" for="enableReminders">
                                    Enable Deadline Reminders
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Requirement Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
