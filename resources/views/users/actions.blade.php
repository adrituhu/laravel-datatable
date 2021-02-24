<a href="/users/{{$user->id}}/edit" class="btn btn-primary btn-sm">Edit</a>
<form method="POST" class="d-inline" action="/users/{{$user->id}}">
  @method("DELETE")
  @csrf
  <input type="hidden" value="{{$user->id}}" />

  <button class="btn btn-danger btn-sm"> Hapus </button>
</form>
