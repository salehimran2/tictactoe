    <div class="entryway-container">
      <div id="register-container">
        <h2>Register</h2>
        <form id="registration-form" method="post">
          <table>
            <tr>
              <td>
                <input name="username" placeholder="username" type="text">
              </td>
            </tr>  
            <tr>  
              <td>
                <input name="password" placeholder="password" type="password">
              </td>
            </tr>
            <tr>  
              <td>
                <input name="password-verify" placeholder="verify password" type="password">
              </td>
            </tr>
            <tr>  
              <td>
                <input name="email" placeholder="email" type="text">
              </td>
            </tr>
            <tr>  
              <td>
                <input name="email-verify" placeholder="verify email" type="text">
              </td>
            </tr>
            <tr>
              <td>
                <input name="submit" type="submit" value="Register">
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div id="login-container">
        <h2>Log in</h2>
        <form id="login-form" action="<?= APP . 'index.php?page=login' ?>" method="post">
          <table>
            <tr>
              <td>
                <input name="username" placeholder="username" type="text">
              </td>
            </tr>
            <tr>
              <td>
                <input name="password" placeholder="password" type="password">
              </td>
            </tr>
            <tr>
              <td>
                <input name="submit" type="submit" value="Log in">
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
    <div id="entryway-errors">
    </div>

