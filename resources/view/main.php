<?php require_once VIEW_ROOT . 'partials/header.php' ?>

    <form>


        <div class="form-group">
            <label  class="col-sm-2 control-label">Vartotojo vardas:</label>
            <div class="col-sm-10">
                <input type="text" id="username" class="form-control"  placeholder="Jonas123">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Elektroninis paštas:</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <span class="input-group-addon">@</span>
                    <input type="email" id="email" class="form-control" placeholder="jonas@jonaitis.lt">
                </div>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label class="col-sm-2 control-label">Slaptažodis:</label>
            <div class="col-sm-10">
                <input type="password" id="pass1" class="form-control"  placeholder="******">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Pakartokite slaptažodi:</label>
            <div class="col-sm-10">
                <input type="password" id="pass2" class="form-control" placeholder="******">
            </div>
        </div>
        <br>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary btn-lg active" id="processRegister">Registruotis</button>
            </div>
        </div>


        <div id="error" class="alert alert-danger hidden d-none" role="alert">
        </div>
        <div id="confirmation" class="alert alert-success d-none" role="alert">
        </div>
    </form>

<?php require_once VIEW_ROOT . 'partials/footer.php' ?>

