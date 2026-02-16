

<!DOCTYPE html>
<html lang="en">
   <head>
	  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
      <title>Login</title>
	  <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	  <link rel="stylesheet" type="text/css" href="public/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	  <link rel="stylesheet" type="text/css" href="public/login/css/style.css">
   </head>
   <body>
      <div class="limiter">
         <div class="container-login100">
            <div class="wrap-login100">
               <div class="login100-form-title" style="background-image: url(public/login/images/bg-01.jpg);">
                  <span class="login100-form-title-1">
                  Sign In
                  </span>
               </div>
               <form class="login100-form validate-form" action="{{route('web_login')}}" method="POST">
                   @csrf
                  <div class="wrap-input100 validate-input m-b-26" data-validate="Email is required">
                     <span class="label-input100">Email</span>
                     <input class="input100" type="text" name="email" placeholder="Enter email">
                     <span class="focus-input100"></span>
                  </div>
                  <div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
                     <span class="label-input100">Password</span>
                     <input class="input100" type="password" name="password" placeholder="Enter password">
                     <span class="focus-input100"></span>
                  </div>
                  <div class="flex-sb-m w-full p-b-30">
                     <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
                        Remember me
                        </label>
                     </div>
                     <div>
                        <a href="#" class="txt1">
                        Forgot Password?
                        </a>
                     </div>
                  </div>
                  <div class="container-login100-form-btn">
                     <input type="submit" class="login100-form-btn" value="Login">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </body>
</html>

