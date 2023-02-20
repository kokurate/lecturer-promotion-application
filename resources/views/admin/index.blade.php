<?php 
echo "success logged in as admin"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        {{-- <button type="submit" class="" style="text-decoration: none; border:none; border-radius:none;">Log Out</button> --}}
        <button type="submit" class="btn text-decoration-none border-0 p-0 m-0">Log Out</button>

      </form> 
</body>
</html>