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
                        <button type="button" data-toggle="modal" data-target="#editModal" data-id="{{ $todo->id }}"
                                class="btn edit-id btn-sm btn-info">Edit
                        </button>
                        <button type="button" data-toggle="modal" data-target="#deleteModal" data-id="{{ $todo->id }}"
                                class="btn delete-id btn-sm btn-danger">Delete
                        </button>
                    </th>
                    <td>{{ $todo->task_name }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    <!-- Create Modal -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="addTodo">
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
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="editTodo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Task</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="task_name" class="form-label">Task Name</label>
                        <input value="" name="task_name" required type="text" class="form-control taskname"
                               placeholder="Task Name">
                        <input id="edit-id" type="hidden" value="" name="edit_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="deleteTodo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input id="delete-id" type="hidden" value="" name="delete_id">
                        Are you sure you want to delete this task?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@section("custom_script")

    <script type="text/javascript">
        $(".addTodo").validate({
            errorElement: "div",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                if (element.prop("type") !== "checkbox") {
                    error.insertAfter(element);
                }
            },
            submitHandler: function () {
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
                        if (response.errors) {
                            $.each(response.errors, function (key, value) {
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
        });

        let id;
        $(".edit-id").click(function () {
            $('#edit-id').val($(this).data("id"))
            id = $(this).data("id");
            $.ajax({
                url: "/todo/" + id,
                type: "GET",
                success: function (data) {
                    $(".taskname").val(data.html);
                },
                error: function (data) {
                    var response = JSON.parse(data.responseText);
                    var errorString = '<div>';
                    errorString += '<p>' + response.error + '</p>';
                    errorString += '</div>';
                    toastr.error(errorString)
                }
            })
            $('#editModel').modal('show');
        });


        $(".editTodo").validate({
            errorElement: "div",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                if (element.prop("type") !== "checkbox") {
                    error.insertAfter(element);
                }
            },
            submitHandler: function () {
                $.ajax({
                    type: "PUT",
                    data: $(".editTodo").serialize(),
                    url: "{{ route('todo.update', 'id') }}",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
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
                        if (response.errors) {
                            $.each(response.errors, function (key, value) {
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
        });

        $(".delete-id").click(function () {
            $('#deleteModal').modal('show');
            $('#delete-id').val($(this).data("id"))
        });

        $(".deleteTodo").validate({
            submitHandler: function () {
                $.ajax({
                    type: "DELETE",
                    data: $(".deleteTodo").serialize(),
                    url: "{{ route('todo.destroy', 'id') }}",
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
                        if (response.errors) {
                            $.each(response.errors, function (key, value) {
                                errorString += '<p>' + value + '</p>';
                            });
                        } else {
                            errorString += '<p>' + response.error + '</p>';
                        }
                        errorString += '</div>';
                        toastr.error(errorString)
                    }
                })
            }
        });

    </script>
@endsection


