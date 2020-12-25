@extends('layouts.app')

@section('headerext')
    <style>
        .uploadContainer {
            max-width: 800px;
        }
        .uploadContainer dl, .uploadContainer ol, .uploadContainer ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .uploadContainer .input {
            margin-top: 20px;
        }
    </style>
    <script type="text/javascript">
        function snycFilename() {
            let inputFile = document.getElementById("file");
            let inputFilename = document.getElementById("filename");
            let filepathArr = inputFile.value.split('\\');

            inputFilename.value = filepathArr.reverse()[0];
        }
    </script>
@endsection

@section('content')
    
    <div class="uploadContainer container mt-5">
        <form action="{{route('document.create')}}" method="post" enctype="multipart/form-data">
          <h3 class="text-center mb-5">Add new Document</h3>
            @csrf
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif

          @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

            <div class="text-center">
                <div class="alert alert-secondary" role="alert" style="height:50px">
                    <input type="file" name="file" id="file" onchange="snycFilename()" accept=".pdf" style="float:left" />
                </div>
            </div>

            <div class="input">
                <label class="filename-label" for="filename">Filename:</label>
                <input type="text" name="filename" class="filename-input" id="filename" style="width:100%">
            </div>

            <div class="input">
                <label class="status-label" for="status">Status:</label>
                <input type="text" name="status" class="status-input" id="status" disabled value="New">
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">Add</button>
        </form>
    </div>
    
@endsection