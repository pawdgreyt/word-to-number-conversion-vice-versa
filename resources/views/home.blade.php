<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WN CONVERTER</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel="stylesheet" />
    
</head>
<body class="bg-dark">
    <div class="container w-50 mt-5">
        <div class="card">
            <div class="card-body">
                <h3>Word To Number Conversion</h3>
                <form action="{{ route('store') }}" method="POST" autocomplete="off">
                    @csrf
                    @if ($errors->has('user_input'))
                        <div class="alert alert-danger">{{ $errors->first('user_input') }}</div>
                    @endif
                    <div class="input-group">
                        <input type="text" name="user_input" class="form-control" placeholder="Enter here...">
                        <button type="submit" class="btn btn-dark btn-sm px-4"><i class="fas fa-plus"></i></button>
                    </div>
                </form>
                <br>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Input</th>
                            <th>WN CONVERSION</th>
                            <th>USD CONVERSION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($convertedlists))
                            @foreach($convertedlists as $convertedlist)
                                <tr>
                                    <td>{{ $convertedlist->user_input }}</td>
                                    <td>{{ $convertedlist->wn_conversion }}</td>
                                    <td>
                                        @if (is_numeric($convertedlist->usd_conversion))
                                            ${{ $convertedlist->usd_conversion }}
                                        @else
                                            {{ $convertedlist->usd_conversion }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.js"></script>