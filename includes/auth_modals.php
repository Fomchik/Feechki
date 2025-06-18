<!-- Мольное окно -->
<div id="loginModal" class="modal">
   <div class="modal-content">
       <div class="modal-header">
           <h2>Вход</h2>
           <span class="close">&times;</span>
       </div>
       <div class="modal-body">
           <form id= "loginForm" method="post" action="/includes/auth_handler.php">
               <input type='hidden' name='action' value='login'>
               <div class="form-group">
                   <label for="login">Ваш email или логин</label>
                   <input type="text" id="login" name="login" required>
               </div>
               <div class='form-group'>
                    <label for="login_password">Ваш пароль</label>
                    <input type="password" id="login_password" name="password" required>
               </div>
               <div class="form-group remember-me">
                   <input type="checkbox" id="remember" name="remember" value="1">
                   <label for="remember">Запомнить меня</label>
               </div>
               <div class="form-actions">
                   <button type="submit" class="btn primary">Войти</button>
               </div>
               <div class="form-footer">
                   <p>
                       Не зарегистрированы? 
                       <a href="#" id="showRegisterModal">Зарегистрироваться</a>
                   </p>
               </div>
           </form>
       </div>
   </div>
</div>
<!-- Мольное окно регистрации -->
<div id="registerModal" class="modal">
   <div class="modal-content">
        <div class="modal-header">
           <h2>Регистрация</h2>
           <span class="close">&times;</span>
       </div>
       <div class="modal-body">
           <form id="registerForm" method="post" action="/includes/auth_handler.php">
               <input type="hidden" name="action" value="register">
       <div class="form-group">
           <label for="username">Ваш логин</label>
           <input type="text" id="username" name="username" required>
       </div>
        <div class="form-group">
                   <label for="email">Ваш email</label>
                   <input type="email" id="email" name="email" required>
               </div>
                               <div class="form-group">
                   <label for="register_password">Ваш пароль</label>
                   <input type="password" id="register_password" name="password" required>
               </div>
                <div class="form-group">
                   <label for="confirm_password">Повторите пароль</label>
                   <input type="password" id="confirm_password" name="confirm_password" required>
               </div>
                               <div class="form-actions">
                   <button type="submit" class="btn primary">Регистрация</button>
               </div>
                 <div class="form-footer">
                   <p>
                       Уже зарегистрированы? 
                       <a href="#" id="showLoginModal">Тогда войдите</a>
                   </p>
               </div>
           </form>
       </div>
   </div>
</div>
<script src="/assets/js/auth_modal.js"></script>