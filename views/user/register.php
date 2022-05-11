<html>
<head>
    <title>Pokes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
<form class="form-horizontal" method="post" action="/register">
    <div class="form-group">
        <label  class="col-sm-2 control-label">Vartotojo vardas:</label>
        <div class="col-sm-10">
            <input type="text" name="user" class="form-control"  placeholder="Jonas123">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Elektroninis paštas:</label>
        <div class="col-sm-10">
            <div class="input-group">
                <span class="input-group-addon">@</span>
                <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="jonas@jonaitis.lt">
            </div>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label class="col-sm-2 control-label">Slaptažodis:</label>
        <div class="col-sm-10">
            <input type="password" name="pass" class="form-control" id="inputPassword3" placeholder="******">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Pakartokite slaptažodi:</label>
        <div class="col-sm-10">
            <input type="password" name="pass2" class="form-control" id="inputPassword3" placeholder="******">
        </div>
    </div>
    <br>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary btn-lg active" name="processRegister">Registruotis</button>
        </div>
    </div>
</form>
</body>
</html>
