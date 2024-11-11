@extends('layouts/contentNavbarLayout')

@section('title', 'Project Gantt Chart')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $project->name }} - Planning</h4>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Terug naar Project
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="gantt_here" style="height: 600px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            gantt.config.date_format = "%Y-%m-%d";
            gantt.config.scale_height = 50;
            gantt.config.subscales = [{
                unit: "week",
                step: 1,
                date: "Week #%W"
            }];

            gantt.config.columns = [{
                    name: "text",
                    label: "Task name",
                    tree: true,
                    width: 200
                },
                {
                    name: "start_date",
                    label: "Start",
                    align: "center",
                    width: 80
                },
                {
                    name: "duration",
                    label: "Duration",
                    align: "center",
                    width: 60
                },
                {
                    name: "progress",
                    label: "Progress",
                    align: "center",
                    width: 60,
                    template: function(obj) {
                        return Math.round(obj.progress * 100) + "%";
                    }
                }
            ];

            gantt.templates.progress_text = function(start, end, task) {
                return Math.round(task.progress * 100) + "%";
            };

            // Configure task types
            gantt.config.types = {
                project: "project",
                phase: "phase",
                task: "task"
            };

            // Set custom styling for different task types
            gantt.templates.task_class = function(start, end, task) {
                return task.type;
            };

            gantt.init("gantt_here");

            const tasks = @json($tasks);
            gantt.parse({
                data: tasks
            });
        });
    </script>
@endpush
