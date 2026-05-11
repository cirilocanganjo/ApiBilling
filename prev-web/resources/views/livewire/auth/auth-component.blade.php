@section('title', 'PV-S01 | Login')
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>PS - </b>01</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicie sessão para começar</p>

      <form wire:submit="authentication" >

        <div class="d-flex flex-column mb-3">
            <div class="input-group">
              <input wire:model='email' type="email" class="form-control" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>

            <div>
                @error('email')<span class="text-danger">{{$message ?? ''}}</span> @enderror
            </div>
        </div>

        <div class="d-flex flex-column mb-3">
            <div class="input-group">
              <input wire:model='password' type="password" class="form-control" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>

            <div>
                @error('password')<span class="text-danger">{{$message ?? ''}}</span> @enderror
            </div>
        </div>


        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
               Lembrar-me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center mb-3">
        <p>- OU -</p>
        <a href="" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Iniciar sessão com o Facebook
        </a>
        <a href="" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Iniciar sessão com o Google+
        </a>
      </div>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">Esqueci-me da palavra-passe</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Registar uma nova conta</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

