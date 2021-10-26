@extends('layouts.layout')


@section('content')
    <p class="m-3">
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Task</button>
    </p>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Action</th>
            <th scope="col">Task Name</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($todos))
            @foreach($todos as $todo)
                <tr>
                    <th scope="row">
                        <button data-id="{{ $todo->id }}" class="btn btn-sm btn-info">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </th>
                    <td>{{ $todo->task_name }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="task_name" class="form-label">Task Name</label>
                    <input required type="text" class="form-control" id="task_name" placeholder="Task Name">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="add()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("custom_script")

    <script type="text/javascript">
        function add() {
            $.ajax({
                type: "POST",
                data: {'task_name': jQuery('#task_name').val()},
                url: "{{ route('todo.store') }}",
                dataType: 'json',
                success: function (data) {
                    if (data.message) {
                        toastr.success(data.message)
                        setTimeout(function () {
                            window.location.href = data.page;
                        }, 2000);
                    }
                },
                error: function (data) {
                    var response = JSON.parse(data.responseText);
                    var errorString = '<div>';
                    if(response.errors) {
                        $.each( response.errors, function( key, value) {
                            errorString += '<p>' + value + '</p>';
                        });
                    } else {
                        errorString += '<p>' + response.error + '</p>';
                    }
                    errorString += '</div>';
                    toastr.error(errorString)
                }
            });
        }
    </script>
@endsection


