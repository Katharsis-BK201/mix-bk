@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">User /</span> User List
</h4>

<!-- Striped Rows -->
<div class="card">
  <h5 class="card-header">Users</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($users as $user)
        @php
            $encryptedId = Crypt::encrypt($user->id);
        @endphp
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td><span class="badge bg-label-primary me-1">Active</span></td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal-{{ $encryptedId }}">
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
              </div>
            </div>
          </td>
        </tr>

        <div class = "modal fade" id="editModal-{{$encryptedId}}" tabindex="-1" aria-labelledby="editModalLabel-{{$encryptedId}}" aria-hidden="true">
        <div class = "modal-dialog">
          <div class = "modal-content">
            <h5 class = "modal-title" id = "editModalLabel-{{$encryptedId}}">Edit User</h5>
            <button type = "button" class = "btn-close" data-bs-dismiss = "modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="{{route('update-user',['id'=>$encryptedId])}}" class="edit-user-form" data-user-id = "{{$encryptedId}}">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <div class= "mb-3">
                <label for="name-{{$encryptedId}}" class = "form-label">Name</label>
                <input type="text" class="form-control" id="name-{{$encryptedId}}" name="name" value="{{ $user->name }}" required>
              </div>
              <div class =  "mb-3">
                <label for="email-{{$encryptedID}}" class = "form-label">Email</label>
                <input type="email" class = "form-control" id="email-{{$encryptedId}}" name = "email" value="{{ $user->email }}" required>
              </div>
            </div>
            <div class="modal-footer">
            <button type = "button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class = "btn btn-primary">Save Changes</button>
            </div>
          </form>
          </div>
        </div>
        </div>
        @endforeach
      </tbody>
    </table>
  </div>
  </div>
</div>
<script>
  $(document).ready(function() {
      $('.edit-user-form').on('submit', function(e) {
          e.preventDefault();

          const form = $(this);
          const encryptedUserId = form.data('user-id'); // Get the encrypted user ID
          const formData = form.serialize();
          const csrfToken = $('meta[name="csrf-token"]').attr('content');
          if (!csrfToken) {
              console.warn('CSRF token not found in meta tag!');
          }
          console.log('Encrypted User ID:', encryptedUserId); // Debug
          console.log('Form Data:', formData); // Debug

          $.ajax({
              url: '/users/'+ encryptedUserId,
              type: 'PUT', // Laravel expects PUT
              data: formData,
              success: function(response) {
                console.log('Response:', response);
                  if (response.status === 'success') {
                     $('#editModal-' + encryptedUserId).modal('hide');
                      Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: response.message
                      }).then(() => {
                          location.reload();
                      });
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error!',
                          text: 'Update failed.'
                      });
                  }
              },
              error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText); // Debug
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                          text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Something went wrong.'
                      });
            }

          });
        });
  });
</script>
@endsection
