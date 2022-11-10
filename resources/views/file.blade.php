<form action="{{ url('store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <p><input type="file" name="myfile" /></p>
    <button type="submit" name="submit">Submit</button>
</form>